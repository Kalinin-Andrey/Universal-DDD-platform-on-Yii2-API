<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace commonprj\components\core\entities\common\relationVariant;

use commonprj\components\core\entities\common\variant\VariantDBRepository;
use commonprj\components\core\models\ElementRecord;
use commonprj\components\core\models\RelationClassRecord;
use commonprj\components\core\models\RelationVariantRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class RelationVariantDBRepository
 * @package commonprj\components\core\entities\common\relationVariant
 */
class RelationVariantDBRepository extends VariantDBRepository
{
    public $activeRecord = 'commonprj\components\core\models\RelationVariantRecord';

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
            $result['variantsByTypeId'][2][$elementRecord['id']] = self::instantiateByARAndClassName($elementRecord, $classNameDomain);
        }

        return $result;
    }

    /**
     * @param RelationVariant $variant
     * @return bool
     */
    public function save(RelationVariant &$variant)
    {
        $relationVariantRecord = new RelationVariantRecord();
        $relationVariantRecord->setAttributes(self::arrayKeysCamelCase2Underscore($variant->getAttributes()));

        if (!$relationVariantRecord->save() && !$relationVariantRecord->hasErrors()) {
            return false;
        }

        $variant->setAttributes(self::arrayKeysUnderscore2CamelCase($relationVariantRecord->getAttributes()));
        $variant->id = $relationVariantRecord->getPrimaryKey();

        if ($relationVariantRecord->hasErrors()) {
            $variant->addErrors($relationVariantRecord->getErrors());
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
        $model = RelationVariantRecord::findOne($id);

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
        return RelationVariantRecord::primaryKey();
    }

    /**
     * @param $id
     * @return array|BaseCrudModel
     */
    public function getElementType($id)
    {
        $model = RelationVariantRecord::findOne($id);
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
        $model = RelationVariantRecord::findOne($id);
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
     */
    public function getRelatedElement($id)
    {
        $elementRecord = ElementRecord::findOne($id);

        return self::instantiateByARAndClassName($elementRecord);
    }

    /**
     * @param $id
     * @return BaseCrudModel
     */
    public function getRelationClass($id)
    {
        $relationVariantRecord = RelationVariantRecord::findOne($id);
        $relationClassRecord = RelationClassRecord::findOne($relationVariantRecord->getAttribute('relation_class_id'));

        return self::instantiateByARAndClassName($relationClassRecord);
    }
}