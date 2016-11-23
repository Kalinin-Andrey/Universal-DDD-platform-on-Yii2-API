<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\elementType;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\components\core\models\ElementTypeRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ElementTypeRepository
 * @package commonprj\components\core\entities\common\elementType
 */
class ElementTypeDBRepository extends BaseDBRepository implements ElementTypeRepository
{
    public $activeRecord = 'commonprj\components\core\models\ElementTypeRecord';

    /**
     * Сохранение переданного объекта посредством active record
     * @param ElementType $elementType
     * @return bool
     */
    public function save(ElementType $elementType)
    {
        if (!$elementTypeRecord = ElementTypeRecord::findOne($elementType->id)) {
            $elementTypeRecord = new ElementTypeRecord();
        }

        $elementTypeRecord->setAttributes(self::arrayKeysCamelCase2Underscore($elementType->attributes));
        $result = $elementTypeRecord->save();

        if ($result) {
            $elementType->setAttributes(self::arrayKeysUnderscore2CamelCase($elementTypeRecord->attributes), false);
        } else {
            $elementType->addErrors($elementTypeRecord->getErrors());
        }

        return $result;
    }

    /**
     * @param null $condition
     * @return array|\yii\db\ActiveRecord[]
     * @throws InvalidConfigException
     */
    public function find($condition = null)
    {
        preg_match('/(\w+)Repository$/', get_class($this), $match);
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
            $result[$elementRecord['id']] = self::instantiateByARAndClassName($elementRecord, $classNameDomain);
        }

        return $result;
    }

    /**
     * @param int $id
     * @throws HttpException
     */
    public function deleteElementTypeById(int $id)
    {
        $model = ElementTypeRecord::findOne($id);

        if (empty($model)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            //todo может getElementCategories вынести в метод и в доменный слой elementCategory?
            BaseCrudModel::deleteRows($model->getElementCategories()->all());
            //todo может это вынести в метод?
            BaseCrudModel::deleteRows($model::find()->where(['id' => $id])->all());
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @param int $id
     * @return ElementCategory[]
     */
    public function getElementCategoriesById(int $id)
    {
        $elementTypeRecord = ElementTypeRecord::findOne($id);
        $elementCategoryRecords = $elementTypeRecord->getElementCategories()->all();
        $result = [];
        foreach ($elementCategoryRecords as $elementCategoryRecord) {
            $result[$elementCategoryRecord['id']] = self::instantiateByARAndClassName($elementCategoryRecord, 'commonprj\components\core\entities\common\elementCategory\ElementCategory');
        }

        return $result;
    }

    /**
     * @param int $id
     * @return ElementClass
     */
    public function getElementClassById(int $id)
    {
        $resultElementClass = [];
        $elementTypeRecord = ElementTypeRecord::findOne($id);

        if ($elementTypeRecord) {
            $elementClassRecord = $elementTypeRecord->getElementClass()->one();

            if ($elementClassRecord) {
                $resultElementClass = self::instantiateByARAndClassName($elementClassRecord, 'commonprj\components\core\entities\common\elementClass\ElementClass');
            }
        }

        return $resultElementClass;
    }

    /**
     * @param $id
     * @return array|BaseCrudModel
     * @throws HttpException
     */
    public function getVariantById($id)
    {
        $elementTypeRecord = ElementTypeRecord::findOne($id);
        $propertyVariant = $elementTypeRecord->getPropertyVariants()->all();
        $relationVariant = $elementTypeRecord->getRelationVariants()->all();

        if (!empty($propertyVariant) && !empty($relationVariant) || count($propertyVariant) > 1 || count($relationVariant) > 1) {
            throw new HttpException(500, 'Данный тип элемента имеет несколько вариантов! Это ошибка, которую нужно немедленно исправить! ' . basename(__FILE__, '.php') . __LINE__);
        }

        if (!empty($propertyVariant)) {
            return self::instantiateByARAndClassName($propertyVariant[0]);
        }

        if (!empty($relationVariant)) {
            return self::instantiateByARAndClassName($relationVariant[0]);
        }

        return [];
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return ElementTypeRecord::primaryKey();
    }
}