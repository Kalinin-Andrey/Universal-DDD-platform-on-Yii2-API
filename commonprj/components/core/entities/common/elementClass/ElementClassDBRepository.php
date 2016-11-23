<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\elementClass;

use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\components\core\helpers\ClassAndContextHelper;
use commonprj\components\core\models\ElementClassRecord;
use commonprj\components\core\models\Property2elementClassRecord;
use commonprj\components\core\models\RelationClassRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ElementClassRepository
 * @package commonprj\components\core\entities\common\elementClass
 */
class ElementClassDBRepository extends BaseDBRepository implements ElementClassRepository
{
    public $activeRecord = 'commonprj\components\core\models\ElementClassRecord';

    /**
     * Сохранение переданного объекта посредством active record
     * @param ElementClass $elementClass
     * @return bool
     */
    public function save(ElementClass $elementClass)
    {
        if (!$elementClassRecord = ElementClassRecord::findOne($elementClass->id)) {
            $elementClassRecord = new ElementClassRecord();
        }

        $elementClassRecord->setAttributes(self::arrayKeysCamelCase2Underscore($elementClass->attributes));
        $result = $elementClassRecord->save();

        if ($result) {
            $elementClass->setAttributes(self::arrayKeysUnderscore2CamelCase($elementClassRecord->attributes), false);
        } else {
            $elementClass->addErrors($elementClassRecord->getErrors());
        }

        return $result;
    }

    /**
     * Метод возвращает объект контекста к которому принадлежит текущий класс.
     * @param int $elementClassId - Класс чей контекст нужно вернуть.
     * @return ActiveRecord
     */
    public function getContext(int $elementClassId)
    {
        $elementClassRecord = ElementClassRecord::findOne($elementClassId);

        return $elementClassRecord->getContext()->one();
    }

    /**
     * @param mixed $elementClassIds
     * @return array
     */
    public function find($elementClassIds = null)
    {
        if ($elementClassIds) {
            $elementRecords = ElementClassRecord::find()->where($elementClassIds)->all();
        } else {
            $elementRecords = ElementClassRecord::find()->all();
        }

        $result = [];
        foreach ($elementRecords as $elementRecord) {
            $result[$elementRecord['id']] = self::instantiateByARAndClassName($elementRecord, 'commonprj\components\core\entities\common\elementClass\ElementClass');
        }

        return $result;
    }

    /**
     * @param $condition
     * @param bool $isRoot
     * @return RelationClass[]
     * @throws HttpException
     */
    public function getRelationClassesById($condition, bool $isRoot)
    {
        if (!$elementClassRecord = ElementClassRecord::findOne($condition)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementClass2relationClasses = $elementClassRecord->getElementClass2relationClasses()
            ->select(['relation_class_id'])
            ->where(['is_root' => $isRoot])
            ->all();

        $relationClassIds = [];
        foreach ($elementClass2relationClasses as $elementClass2relationClass) {
            $relationClassIds[] = $elementClass2relationClass->getAttribute('relation_class_id');
        }

        if (!$relationClassIds) {
            return [];
        }

        $result = [];
        $relationClassRecords = RelationClassRecord::find()->where(['id' => $relationClassIds])->all();

        foreach ($relationClassRecords as $relationClassRecord) {
            $result[$relationClassRecord['id']] = self::instantiateByARAndClassName($relationClassRecord, 'commonprj\components\core\entities\common\relationClass\RelationClass');
        }

        return $result;
    }

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord[]
     * @throws HttpException
     */
    public function getPropertiesById($condition)
    {
        if (!$elementClassRecord = ElementClassRecord::findOne($condition)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $properties = $elementClassRecord->getProperties()->all();
        $result = [];

        foreach ($properties as $property) {
            $result[$property['id']] = $property;
        }

        return $result;
    }

    /**
     * @param $contextNameAndClassName
     * @return BaseCrudModel
     */
    public function getElementClassByName($contextNameAndClassName)
    {
        $className = Yii::$app->route->classNameUrlDecode($contextNameAndClassName);
        $elementClassId = ClassAndContextHelper::getElementClassIdByClassAndContextName($className);
        $elementClassRecord = ElementClassRecord::findOne($elementClassId);

        return $this->instantiateByARAndClassName($elementClassRecord, get_called_class());
    }

    /**
     * @param $id
     * @throws HttpException
     */
    public function deleteElementClassById($id)
    {
        $model = ElementClassRecord::findOne($id);

        if (empty($model)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($model->getElementTypes()->all());
            BaseCrudModel::deleteRows($model->getProperty2elementClasses()->all());
            BaseCrudModel::deleteRows($model->getElementClass2relationClasses()->all());
            BaseCrudModel::deleteRows($model->getElement2elementClasses()->all());
            //todo может это вынести в метод?
            BaseCrudModel::deleteRows($model::find()->where(['id' => $id])->all());
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return ElementClassRecord::primaryKey();
    }

    /**
     * @param $elementClassId
     * @param $propertyId
     * @return Property2elementClassRecord
     */
    public function createProperty2ElementClass($elementClassId, $propertyId)
    {

        $property2ElementClassRecord = new Property2elementClassRecord();
        $property2ElementClassRecord->setAttributes([
            'element_class_id' => $elementClassId,
            'property_id'      => $propertyId,
        ], false);
        $property2ElementClassRecord->save();

        return $property2ElementClassRecord;
    }
}