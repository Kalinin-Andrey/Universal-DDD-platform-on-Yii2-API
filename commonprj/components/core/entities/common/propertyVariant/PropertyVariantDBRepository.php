<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace commonprj\components\core\entities\common\propertyVariant;

use commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValueDBRepository;
use commonprj\components\core\entities\common\property\PropertyDBRepository;
use commonprj\components\core\entities\common\variant\VariantDBRepository;
use commonprj\components\core\models\PropertyVariantRecord;
use commonprj\components\core\models\RelationVariantRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class PropertyVariantDBRepository
 * @package commonprj\components\core\entities\common\propertyVariant
 */
class PropertyVariantDBRepository extends VariantDBRepository
{
    public $activeRecord = 'commonprj\components\core\models\PropertyVariantRecord';

    /**
     * @inheritdoc
     */
    public function find(array $condition = null)
    {
        preg_match('/(\w+)Repository$/', self::class, $match);
        $ucFirst = ucfirst($match[1]);
        $ucFirst = preg_replace('/DB/', '', $ucFirst);
        $classNameRecord = "commonprj\\components\\core\\models\\{$ucFirst}Record";
        $query = call_user_func("{$classNameRecord}::find");

        if (ArrayHelper::isAssociative($condition)) {
            $elementRecords = $query->where($condition)->all();
        } else {
            $elementRecords = $query->all();
        }

        $result = [];

        $lcFirst = lcfirst($ucFirst);
        $classNameDomain = "commonprj\\components\\core\\entities\\common\\{$lcFirst}\\{$ucFirst}";

        foreach ($elementRecords as $elementRecord) {
            $result['variantsByTypeId'][1][$elementRecord['id']] = self::instantiateByARAndClassName($elementRecord, $classNameDomain);
        }

        return $result;
    }

    /**
     * @param PropertyVariant $variant
     * @return bool|ActiveRecord
     * @throws HttpException
     */
    public function save(PropertyVariant &$variant)
    {
        if (is_null($variant->propertyValue)) {
            throw new HttpException(422, 'propertyValue can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
        }

        if (!empty($variant['propertyValue']['id'])) {
            $variant['propertyValue'] = AbstractPropertyValueDBRepository::consistentAbstractPropertyValueWithId($variant['propertyValue']);
        } elseif (is_array($variant['propertyValue'])) {
            $variant['propertyValue'] = AbstractPropertyValueDBRepository::consistentAbstractPropertyValueWithoutId($variant['propertyValue']);
            $propertyDBRepository = new PropertyDBRepository();
            $result = $propertyDBRepository->blackBox($variant['propertyValue']);
            $variant->valueTableId = $result['valueTableId'];
            $variant['valueId'] = $result['valueId'];
            $result['id'] = $result['valueId'];
            unset($result['valueId']);
            unset($result['valueTableId']);
            unset($result['propertyTypeId']);
            unset($result['propertyTypeName']);
            unset($result['propertyValueRecordName']);
            $variant['propertyValue'] = $result;

            if (empty($variant['propertyId'])) {
                throw new HttpException(422, 'propertyId can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
            }

            if ($variant['propertyId'] != $result['propertyId']) {
                throw new HttpException(422, 'different Property Ids given. ' . basename(__FILE__, '.php') . __LINE__);
            }
        } else {
            throw new HttpException(422, 'propertyValue must be an array. ' . basename(__FILE__, '.php') . __LINE__);
        }

        $propertyVariantElementTypeIdExists = PropertyVariantRecord::find()->where(['element_type_id' => $variant->elementTypeId])->one();
        $relationVariantElementTypeIdExists = RelationVariantRecord::find()->where(['element_type_id' => $variant->elementTypeId])->one();

        if (!is_null($propertyVariantElementTypeIdExists) || !is_null($relationVariantElementTypeIdExists)) {
            throw new HttpException(422, 'element_type_id already exists. ' . basename(__FILE__, '.php') . __LINE__);
        }

        if (!empty($variant->id)) {
            $propertyVariantRecord = PropertyVariantRecord::findOne($variant->id);
        } else {
            $propertyVariantRecord = new PropertyVariantRecord();

        }

        $propertyVariantRecord->setAttributes(self::arrayKeysCamelCase2Underscore($variant->getAttributes()));

        if (!$propertyVariantRecord->save() && !$propertyVariantRecord->hasErrors()) {
            return false;
        }

        $variant->setAttributes(self::arrayKeysUnderscore2CamelCase($propertyVariantRecord->getAttributes()));
        $variant->id = $propertyVariantRecord->getPrimaryKey();

        if ($propertyVariantRecord->hasErrors()) {
            $variant->addErrors($propertyVariantRecord->getErrors());
        }

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function deleteById($id)
    {
        $model = PropertyVariantRecord::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException(basename(__FILE__, '.php') . __LINE__);
        }

        if ($model->delete() === false) {
            return false;
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return PropertyVariantRecord::primaryKey();
    }

    /**
     * @param $id
     * @return array|BaseCrudModel
     */
    public function getElementType($id)
    {
        $model = PropertyVariantRecord::findOne($id);
        $elementTypeRecord = $model->getElementType()->one();

        if (is_null($elementTypeRecord)) {
            return [];
        }

        $result = self::instantiateByARAndClassName($elementTypeRecord);

        return $result;
    }

    /**
     * @param $id
     * @return array|BaseCrudModel
     */
    public function getSchemaElement($id)
    {
        $model = PropertyVariantRecord::findOne($id);
        $schemaElementRecord = $model->getElement()->one();

        if (is_null($schemaElementRecord)) {
            return [];
        }

        $result = self::instantiateByARAndClassName($schemaElementRecord);

        return $result;
    }

    /**
     * @param $id
     * @return BaseCrudModel
     * @throws NotFoundHttpException
     */
    public function getProperty($id)
    {
        $model = PropertyVariantRecord::findOne($id);
        $propertyRecord = $model->getProperty()->one();

        if (is_null($propertyRecord)) {
            throw new NotFoundHttpException('Someone manually deleted a property foreign key. ' . basename(__FILE__, '.php') . __LINE__);
        }

        $result = self::instantiateByARAndClassName($propertyRecord);

        return $result;
    }

    /**
     * @param $id
     * @return BaseCrudModel
     * @throws ServerErrorHttpException
     */
    public function getPropertyValue($id)
    {

        $propertyVariantRecord = PropertyVariantRecord::findOne($id);
        $tableName = self::VALUE_TABLE_ID[$propertyVariantRecord->getAttribute('value_table_id')];
        $tableNameRecord = 'commonprj\components\core\models\\' . BaseInflector::camelize($tableName) . 'Record';

        if (!class_exists($tableNameRecord)) {
            throw new ServerErrorHttpException(basename(__FILE__, '.php') . __LINE__);
        }

        $propertyValueRecord = call_user_func("{$tableNameRecord}::findOne", $propertyVariantRecord->getAttribute('value_id'));

        return self::instantiateByARAndClassName($propertyValueRecord);
    }
}