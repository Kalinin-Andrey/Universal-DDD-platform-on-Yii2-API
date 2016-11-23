<?php

/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\element;

use commonprj\components\core\entities\common\model\Model;
use commonprj\components\core\entities\common\property\Property;
use commonprj\components\core\entities\common\property\PropertyDBRepository;
use commonprj\components\core\helpers\ClassAndContextHelper;
use commonprj\components\core\models\Element2elementClassRecord;
use commonprj\components\core\models\ElementRecord;
use commonprj\components\core\models\ModelRecord;
use commonprj\components\core\models\PropertyRecord;
use commonprj\components\core\models\PropertyRelationRecord;
use commonprj\components\core\models\PropertyVariantRecord;
use commonprj\components\core\models\RelationClassRecord;
use commonprj\components\core\models\RelationGroupRecord;
use commonprj\components\core\models\RelationRecord;
use commonprj\components\core\models\RelationVariantRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ElementRepository
 * @package commonprj\components\core\entities\common\element
 */
class ElementDBRepository extends BaseDBRepository implements ElementRepository
{
    public $activeRecord = 'commonprj\components\core\models\ElementRecord';

    /**
     * @inheritdoc
     */
    public function getChildren(int $elementId, int $relationGroupId, bool $recursion = false)
    {
        if (!$model = ElementRecord::findOne($elementId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
        $children = self::getChildrenRecursion($model, $relationGroupId, $recursion);
        $elementRecordIds = [];
        foreach ($children as $child) {
            $elementRecordIds[] = $child->getAttribute('child_element_id');
        }

        $result = [];
        if ($elementRecordIds) {
            $elementRecords = ElementRecord::find()->where(['id' => $elementRecordIds])->all();
            /** @var ActiveRecord $elementRecord */
            foreach ($elementRecords as $elementRecord) {
                $result[$elementRecord['id']] = self::instantiateByARAndClassName($elementRecord);
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getInclusions(Element $element, int $relationGroupId)
    {
        if (!$elementRecords = ElementRecord::findOne($element->id)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $relationInclusionObject = $elementRecords
            ->getRelationGroups()
            ->where(['id' => $relationGroupId])
            ->one();

        if (!$relationInclusionObject) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementIds = RelationRecord::find()
            ->select('child_element_id')
            ->where(['relation_group_id' => $relationGroupId])
            ->asArray()
            ->all();
        $resultElementIds = [];
        foreach ($elementIds as $element) {
            $resultElementIds[] = $element['child_element_id'];
        }
        $resultElementIds = array_keys(array_flip($resultElementIds));

        $result = [];
        if ($resultElementIds) {
            $elementRecords = ElementRecord::find()->where(['id' => $resultElementIds])->all();
            foreach ($elementRecords as $elementRecord) {
                $result[$elementRecord['id']] = self::instantiateByARAndClassName($elementRecord);
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getModels(int $elementId)
    {
        if (!$elementRecord = ElementRecord::findOne($elementId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementModelRecords = $elementRecord->getModels()->all();

        $result = [];
        foreach ($elementModelRecords as $elementModelRecord) {
            $result[$elementModelRecord['id']] = self::instantiateByARAndClassName($elementModelRecord, 'commonprj\components\core\entities\common\model\Model');
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getProperties(int $elementId)
    {
        if (!ElementRecord::findOne($elementId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $propertyRelationElementRecords = PropertyRelationRecord::find()
            ->where(['element_id' => $elementId])
            ->all();

        $result = [];
        $propertyIds = [];

        foreach ($propertyRelationElementRecords as $propertyRelationElementRecord) {
            $propertyIds[] = $propertyRelationElementRecord->getAttribute('property_id');
        }

        $elementRecord = ElementRecord::findOne($elementId);
        if (!is_null($elementRecord['schema_element_id'])) {
            $propertyRelationSchemaElementRecords = PropertyRelationRecord::find()
                ->where(['element_id' => $elementRecord['schema_element_id']])
                ->all();

            foreach ($propertyRelationSchemaElementRecords as $propertyRelationSchemaElementRecord) {
                $propertyIds[] = $propertyRelationSchemaElementRecord->getAttribute('property_id');
            }
        }

        $propertyRecords = [];
        if ($propertyIds) {
            $propertyRecords = PropertyRecord::find()->where(['id' => $propertyIds])->all();
        }

        foreach ($propertyRecords as $propertyRecord) {
            $result[$propertyRecord['id']] = self::instantiateByARAndClassName($propertyRecord, 'commonprj\components\core\entities\common\property\Property');
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function find(array $condition = null)
    {
        if (!empty($condition['byClassId'])) {
            $elementIdsByClass = $this->elementIdsByClassId($condition['byClassId']);
            $elementIds = [];

            if (empty($elementIdsByClass)) {
                return [];
            } else {
                foreach ($elementIdsByClass as $element) {
                    $elementIds[] = $element['element_id'];
                }

                $condition['condition']['id'] = $elementIds;
                $result = [];
                unset($condition['byClassId']);
                $elements = $this->find($condition);
                /** @var BaseCrudModel $element */
                foreach ($elements as $element) {
                    $result[$element['id']] = $element;
                }

                return $result;
            }
        }

        if (!isset($condition['condition'])) {
            $condition['condition'] = null;
        }

        preg_match('/(\w+)DBRepository$/', self::class, $match);
        $ucFirst = ucfirst($match[1]);
        $classNameRecord = "commonprj\\components\\core\\models\\{$ucFirst}Record";
        $query = call_user_func("{$classNameRecord}::find");

        if (ArrayHelper::isAssociative($condition['condition'])) {
            $elementRecords = $query->where($condition['condition']);
        } else {
            $elementRecords = $query;
        }

        if (!empty($condition['with'])) {
            $elementRecords = $elementRecords->with(explode(',', $condition['with']));
        }

        $elementRecords = $elementRecords->all();
        $result = [];

        foreach ($elementRecords as $elementRecord) {
            $result[] = self::instantiateByARAndClassName($elementRecord, get_called_class());
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getClassesByElementId(int $elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);
        $elementClassRecords = $elementRecord->getElementClasses()->all();
        $result = [];

        foreach ($elementClassRecords as $elementClassRecord) {
            $result[$elementClassRecord['id']] = self::instantiateByARAndClassName($elementClassRecord, 'commonprj\components\core\entities\common\elementClass\ElementClass');
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getRelationGroups(int $elementId)
    {
        $relationGroupIds = [];

        if (!$elementRecord = ElementRecord::findOne($elementId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $allRelationGroups = $elementRecord->getRelationGroups()->all();

        foreach ($allRelationGroups as $allRelationGroupItem) {
            $relationGroupIds[] = $allRelationGroupItem->getAttribute('id');
        }

        $relationGroupIds = array_keys(array_flip($relationGroupIds));
        $relationGroupRecords = RelationGroupRecord::find()->where(['id' => $relationGroupIds])->all();
        $result = [];

        foreach ($relationGroupRecords as $relationGroupRecord) {
            $result[$relationGroupRecord['id']] = self::instantiateByARAndClassName($relationGroupRecord, 'commonprj\components\core\entities\common\relationGroup\RelationGroup');
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getHierarchyRecursion($element, int $relationGroupId)
    {
        if (is_int($element)) {
            $element = $this->getElementHierarchyRoot($element, $relationGroupId);
        } else {
            if (empty($element['id'])) {
                throw new HttpException(400, basename(__FILE__, '.php') . __LINE__);
            }
        }

        $hierarchyElements = RelationRecord::find()
            ->where(['parent_element_id' => $element['id'], 'relation_group_id' => $relationGroupId])
            ->asArray()
            ->all();
        if ($hierarchyElements) {
            foreach ($hierarchyElements as $hierarchyElement) {
                $nextHierarchyElement = ElementRecord::find()
                    ->where(['id' => $hierarchyElement['child_element_id']])
                    ->asArray()
                    ->one();
                $element['children'][$hierarchyElement['id']] = $this->getHierarchyRecursion($nextHierarchyElement, $relationGroupId);
            }
        }

        return $element;
    }

    /**
     * @inheritdoc
     */
    public function getParent(int $id, int $relationGroupId)
    {
        /** @var ElementRecord $elementRecord */
        if (!$elementRecord = ElementRecord::findOne($id)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
        $parent = $elementRecord->getRelations0()->where(['relation_group_id' => $relationGroupId])->one();

        $resultElement = [];
        if ($parent) {
            $parentElementId = $parent->getAttribute('parent_element_id');
            $elementRecord = ElementRecord::find()->where(['id' => $parentElementId])->one();
            $resultElement = self::instantiateByARAndClassName($elementRecord);
        }

        return $resultElement;
    }

    /**
     * @inheritdoc
     */
    public function isParent(int $id, int $relationGroupId)
    {
        if (!$elementRecord = ElementRecord::findOne($id)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
        $result = $elementRecord->getRelations()->where(['relation_group_id' => $relationGroupId])->one();

        return $result ? ['isParent' => true] : ['isParent' => false];
    }

    /**
     * @inheritdoc
     */
    public function getRoot(int $elementId, int $relationGroupId)
    {
        $rootId = RelationGroupRecord::find()->select('root_id')->where(['id' => $relationGroupId])->scalar();
        $elementIdInHierarchy = RelationRecord::find()
            ->where(['relation_group_id' => $relationGroupId])
            ->andWhere(['or', ['parent_element_id' => $elementId], ['child_element_id' => $elementId]])
            ->one();
        $elementIdInInclusion = RelationRecord::find()
            ->where(['relation_group_id' => $relationGroupId])
            ->andWhere(['child_element_id' => $elementId])
            ->one();

        if (!$elementIdInHierarchy && !$elementIdInInclusion) {
            return [];
        }

        if (!is_int($rootId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementRecord = ElementRecord::findOne($rootId);
        $resultElement = self::instantiateByARAndClassName($elementRecord);

        return $resultElement;
    }

    /**
     * @inheritdoc
     */
    public function getElementTypesByElementId(int $id)
    {
    }

    /**
     * @inheritdoc
     */
    public function getProperty($elementId, $propertyId, array $condition = [])
    {
        $propertyRelationRecord = PropertyRelationRecord::find()
            ->where([
                'property_id' => $propertyId,
                'element_id'  => $elementId,
            ])
            ->one();

        if (is_null($propertyRelationRecord)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $propertyRecord = PropertyRecord::findOne($propertyId);

        if (
            !empty($condition['with']) && (
                ($condition['with'] === 'property-value') ||
                (is_array($condition['with']) && in_array('property-value', $condition['with']))
            )
        ) {
            $abstractPropertyValue = PropertyDBRepository::getAbstractPropertyValueByPropertyRelation($propertyRelationRecord);
        }

        /** @var Property $resultPropertyRecord */
        $resultPropertyRecord = $this->instantiateByARAndClassName($propertyRecord);
        if (isset($abstractPropertyValue)) {
            $resultPropertyRecord->propertyValue = $abstractPropertyValue;
        }

        return $resultPropertyRecord;
    }

    /**
     * @inheritdoc
     */
    public function getAbstractPropertyValueByElementAndPropertyId($elementId, $propertyId)
    {
        $propertyRelationRecord = PropertyRelationRecord::find()
            ->where([
                'property_id' => $propertyId,
                'element_id'  => $elementId,
            ])
            ->one();

        if ($propertyRelationRecord) {
            return Yii::$app->propertyRepository->getAbstractPropertyValueByPropertyRelation($propertyRelationRecord);
        } else {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
    }

    /**
     * Внутренний метод для поиска потомков элемента.
     * @param ElementRecord $elementRecord
     * @param int $relationGroupId
     * @param array $elements
     * @param bool $recursion
     * @return ElementRecord[]
     */
    private function getChildrenRecursion(
        ElementRecord $elementRecord,
        int $relationGroupId,
        bool $recursion = false,
        array $elements = []
    )
    {
        if (!is_null($elementRecord)) {
            $children = $elementRecord->getRelations()->where(['relation_group_id' => $relationGroupId])->all();
            if (!empty($children)) {
                if ($recursion) {
                    foreach ($children as $child) {
                        $elementRecord = $elementRecord::findOne($child->getAttribute('child_element_id'));
                        $elements = array_merge(
                            [$child],
                            self::getChildrenRecursion($elementRecord, $relationGroupId, $recursion, $elements)
                        );
                    }
                } else {
                    return $children;
                }
            }
        }

        return $elements;
    }

    /**
     * @param int $id
     * @param int $relationGroupId
     * @return bool|ActiveRecord
     * @throws HttpException
     */
    private function getElementHierarchyRoot(int $id, int $relationGroupId)
    {
        $relationHierarchyRecords = RelationRecord::find()
            ->where(['parent_element_id' => $id, 'relation_group_id' => $relationGroupId])
            ->orWhere(['child_element_id' => $id, 'relation_group_id' => $relationGroupId])
            ->one();

        if (!$relationHierarchyRecords) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $rootId = RelationGroupRecord::find()
            ->where(['id' => $relationHierarchyRecords['relation_group_id']])
            ->select('root_id')
            ->asArray()
            ->one();

        return ElementRecord::find()->where(['id' => $rootId['root_id']])->asArray()->one();
    }

    /**
     * Сохранение переданного объекта Element через ActiveRecord. Запись идет транзакцией в 2 таблицы:
     * element и element2element_class
     * @param Element $element
     * @return bool|Element2elementClassRecord|ElementRecord
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function save(Element $element)
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        $elementRecord = new ElementRecord();
        $elementRecord->setAttributes(self::arrayKeysCamelCase2Underscore($element->getAttributes()));
        if (!$elementRecord->save()) {
            $element->addErrors($elementRecord->getErrors());
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }
        // Если запись в таблицу element успешна, переходим к записи в таблицу element2class
        $element2elementClassRecord = new Element2elementClassRecord();
        $element2elementClassRecord->setAttributes([
            'element_id'       => $elementRecord->getPrimaryKey(),
            'element_class_id' => ClassAndContextHelper::getClassId(get_class($element)),
        ]);
        if (!$element2elementClassRecord->save()) {
            $element->addErrors($element2elementClassRecord->getErrors());
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }
        // Если записи в предыдущие таблицы успешны, делаем комит транзакции
        $transaction->commit();
        $element->setAttributes(self::arrayKeysUnderscore2CamelCase($elementRecord->attributes), false);

        return true;
    }

    /**
     * @param Element $element
     * @return bool
     * @throws HttpException
     */
    public function update(Element $element)
    {
        $elementRecord = ElementRecord::findOne($element->id);
        $elementRecord->setAttributes(self::arrayKeysCamelCase2Underscore($element->getAttributes()));
        if ($elementRecord->save()) {
            $element->setAttributes(self::arrayKeysUnderscore2CamelCase($elementRecord->attributes), false);

            return true;
        } else {
            $element->addErrors($elementRecord->getErrors());

            return false;
        }
    }

    /**
     * @param int $id
     * @throws HttpException
     */
    public function deleteElementById(int $id)
    {
        $propertyRecord = ElementRecord::findOne($id);

        if (is_null($propertyRecord)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();
        try {
            $this->deleteFromElement2elementClass($id);
            $this->deleteFromRelation($id);
            $this->deleteFromModels($id);
            $this->deleteFromRelationGroup($id);
            $this->deleteFromPropertyRelation($id);
            $this->deleteFromPropertyVariant($id);
            $this->deleteFromRelationVariant($id);
            $this->deleteFromElement($id);
        } catch (\Exception $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * Внутренний метод для удаления по id элемента связей с классами.
     * @param int $elementId - id элемента, чьи связи надо удалить.
     * @throws HttpException - В случае ошибки при удалении вернет ServerErrorHttpException
     */
    private function deleteFromElement2elementClass(int $elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getElement2elementClasses()->all();
            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * Внутренний метод для удаления по id элемента и реляционной группе реляций.
     * @param int $elementId - id элемента, чьи реляции надо удалить.
     * @param int $relationGroupId
     * @throws ServerErrorHttpException
     */
    private function deleteFromRelation(int $elementId, int $relationGroupId = null)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            /** @var ActiveQuery $rows */
            $rows = $elementRecord->getRelations();
            if ($relationGroupId) {
                $rows = $rows->where(['relation_group_id' => $relationGroupId]);
            }
            $rows = $rows->all();
            /** @var ActiveRecord[] $rows */
            BaseCrudModel::deleteRows($rows);
            /** @var ActiveQuery $rows */
            $rows = $elementRecord->getRelations0();
            if ($relationGroupId) {
                $rows = $rows->where(['relation_group_id' => $relationGroupId]);
            }
            $rows = $rows->all();
            /** @var ActiveRecord[] $rows */
            BaseCrudModel::deleteRows($rows);

            $relationGroupIds = $elementRecord->getRelationGroups0()->select('id')->asArray()->all();
            $resultRelationGroupIds = [];
            foreach ($relationGroupIds as $relationGroupId) {
                $resultRelationGroupIds[] = $relationGroupId['id'];
            }
            $rows = RelationRecord::find()->where(['relation_group_id' => $resultRelationGroupIds])->all();

            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * @param int $elementId
     * @return void
     * @throws HttpException
     */
    public function deleteFromModels(int $elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getModels()->all();
            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * @param $elementId
     * @throws ServerErrorHttpException
     */
    private function deleteFromRelationGroup($elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getRelationGroups0()->all();
            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * @param $elementId
     */
    private function deleteFromPropertyRelation($elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getPropertyRelations()->all();
            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * @param $elementId
     */
    private function deleteFromPropertyVariant($elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getPropertyVariants()->all();
            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * @param $elementId
     */
    private function deleteFromRelationVariant($elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getRelationVariants()->all();
            BaseCrudModel::deleteRows($rows);
            $rows = $elementRecord->getRelationVariants0()->all();
            BaseCrudModel::deleteRows($rows);
        }
    }

    /**
     * @param int $elementId
     * @throws HttpException
     * @throws \Exception
     */
    public function deleteFromElement(int $elementId)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if (is_null($elementRecord)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementRecord->delete();
    }

    /**
     * @param int $elementId
     * @param int $relationGroupId
     * @throws HttpException
     */
    public function deleteFromRelationInclusion(int $elementId, int $relationGroupId = null)
    {
        $elementRecord = ElementRecord::findOne($elementId);

        if ($elementRecord) {
            $rows = $elementRecord->getRelations0();

            if ($relationGroupId) {
                $rows = $rows->where(['relation_group_id' => $relationGroupId]);
            }

            $rows = $rows->all();
            BaseCrudModel::deleteRows($rows);
        } else {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
    }

    /**
     * @param int $elementId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRelationClasses(int $elementId)
    {
        $relationGroups = $this->getRelationGroups($elementId);
        $relationClassIds = [];
        /** @var \yii\db\ActiveRecord $relationGroup */
        foreach ($relationGroups as $relationGroup) {
            $relationClassIds[] = $relationGroup['relationClassId'];
        }

        $relationClassRecords = RelationClassRecord::find()->where(['id' => $relationClassIds])->all();
        $result = [];

        foreach ($relationClassRecords as $relationClassRecord) {
            $result[$relationClassRecord['id']] = self::instantiateByARAndClassName($relationClassRecord, 'commonprj\components\core\entities\common\relationClass\RelationClass');
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function createInclusion(array $attributes)
    {
        if (isset($attributes['inclusionElementId'])) {
            $attributes['child_element_id'] = $attributes['inclusionElementId'];
        }

        if (isset($attributes['elementId'])) {
            $attributes['parent_element_id'] = $attributes['elementId'];
        }

        $attributes['elementId'] = $attributes['inclusionElementId'];
        unset($attributes['inclusionElementId']);
        $relationInclusionRecord = new RelationRecord();
        // todo сделать проверку если задан value то relation_group_id обязателен
        $relationInclusionRecord->setAttributes(self::arrayKeysCamelCase2Underscore($attributes));
        $relationInclusionRecord->save();

        return $relationInclusionRecord;
    }

    /**
     * @param int $ownerId
     * @param int $inclusionId
     * @return bool
     */
    public function ownerHasInclusion(int $ownerId, int $inclusionId)
    {
        $elementRecord = ElementRecord::findOne($ownerId);
        $groupIds = $elementRecord->getRelationGroups0()->select('id')->distinct('id')->asArray()->all();
        $resultGroupIds = [];
        foreach ($groupIds as $groupId) {
            $resultGroupIds[] = $groupId['id'];
        }
        $elementRecord = ElementRecord::findOne($inclusionId);
        $result = $elementRecord->getRelations0()->where(['relation_group_id' => $resultGroupIds])->one();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param int $propertyId
     * @return array
     * @throws HttpException
     */
    public function getPropertyTypeById(int $propertyId)
    {
        if (!$propertyRecord = PropertyRecord::findOne($propertyId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
        /** @var Property $property */
        $property = self::instantiateByARAndClassName($propertyRecord);
        $type = $property->getTypeById();

        return $type;
    }

    /**
     * @param int $id
     * @param int $elementClassId
     * @return Element2elementClassRecord
     */
    public function createElement2ElementClass(int $id, int $elementClassId)
    {
        $element2elementClassRecord = new Element2elementClassRecord();
        $element2elementClassRecord->setAttributes(['element_id' => $id, 'element_class_id' => $elementClassId]);
        $element2elementClassRecord->save();

        return $element2elementClassRecord;
    }

    /**
     * @param int $elementId
     * @param int $childId
     * @param int $relationGroupId
     * @throws HttpException
     */
    public function deleteChildById(int $elementId, int $childId, int $relationGroupId)
    {
        if (!$elementRecord = ElementRecord::findOne($elementId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $parent = $this->getParent($childId, $relationGroupId);
        $child = $elementRecord->getRelations()
            ->where(['child_element_id' => $childId, 'relation_group_id' => $relationGroupId])
            ->one();

        if ($child && $parent['id'] == $elementId) {
            if ($this->isParent($childId, $relationGroupId)['isParent']) {
                throw new HttpException(400, 'Only leaf elements can be deleted. ' . basename(__FILE__, '.php') . __LINE__);
            }

            Yii::$app->getDb()->beginTransaction();

            try {
                ElementDBRepository::deleteFromRelation($childId, $relationGroupId);
            } catch (\Exception $e) {
                Yii::$app->getDb()->getTransaction()->rollBack();
                throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
            }

            Yii::$app->getDb()->getTransaction()->commit();
        } else {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
    }

    /**
     * @param string $propertyValueString
     * @param $propertyId
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyValueRecord(string $propertyValueString, int $propertyId)
    {
        /** @var ActiveRecord $model */
        $model = new $propertyValueString();

        return $model::find()->where(['property_id' => $propertyId]);
    }

    /**
     * @param int $modelId
     * @return Model
     */
    public function getModelById(int $modelId)
    {
        $modelRecord = ModelRecord::findOne($modelId);

        return self::instantiateByARAndClassName($modelRecord);
    }

    /**
     * @param int $classId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function elementIdsByClassId(int $classId)
    {
        return Element2elementClassRecord::find()
            ->select('element_id')
            ->where(['element_class_id' => $classId])
            ->asArray()
            ->all();
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return ElementRecord::primaryKey();
    }

    /**
     * @param $attributes
     * @return bool|ElementRecord|ActiveRecord
     */
    public function createElementsBySchemaId($attributes)
    {
        // проверяем корректность входящего массива
        $this->consistentSchemaElement($attributes);

        Yii::$app->getDb()->beginTransaction();

        // работа с каждым переданным элементом
        foreach ($attributes['schemaElement'] as $schemaElement) {
            $schemaElement['schemaElementId'] = $attributes['id'];

            if (!empty($schemaElement['id'])) {
                // если элемент уже есть, то будем брать его id
                $elementRecord = ElementRecord::findOne($schemaElement['id']);
            } else {
                // если элемент уже есть, то будем брать его id
                $elementRecord = ElementRecord::find()->where([
                    'name'              => $schemaElement['name'],
                    'schema_element_id' => $schemaElement['schemaElementId'],
                ])->one();
            }

            // если элемента еще нет, то создаем его и будем брать id только что созданного
            if (is_null($elementRecord)) {
                $elementRecord = new ElementRecord();
                $elementRecord->setAttributes(self::arrayKeysCamelCase2Underscore($schemaElement), false);
                if (!$elementRecord->save()) {
                    Yii::$app->getDb()->getTransaction()->rollBack();

                    return $elementRecord;
                }
            }

            // запоминаем id текущего элемента
            $currentElementId = $elementRecord->getPrimaryKey();
            // запоминаем классы абстрактного элемента
            $element2elementClassRecords = Element2elementClassRecord::find()->where(['element_id' => $schemaElement['schemaElementId']])->all();

            // наследуем каждый класс абстракного элемента - текущему элементу.
            foreach ($element2elementClassRecords as $element2elementClassRecord) {
                $newElement2elementClass = Element2elementClassRecord::find()->where([
                    'element_id'       => $currentElementId,
                    'element_class_id' => $element2elementClassRecord->getAttribute('element_class_id'),
                ])->one();

                if (is_null($newElement2elementClass)) {
                    $newElement2elementClass = new Element2elementClassRecord();
                    $newElement2elementClass->setAttributes([
                        'element_id'       => $currentElementId,
                        'element_class_id' => $element2elementClassRecord->getAttribute('element_class_id'),
                    ]);
                    if (!$newElement2elementClass->save()) {
                        Yii::$app->getDb()->getTransaction()->rollBack();

                        return $newElement2elementClass;
                    }
                }
            }

            if (!empty($schemaElement['elementTypesIds'])) {
                // текущий элемент наследует все свойства и связи каждого типа элемента
                foreach ($schemaElement['elementTypesIds'] as $elementTypesId) {
                    $propertyVariantByElementTypeId = PropertyVariantRecord::find()->where(['element_type_id' => $elementTypesId])->one();
                    $relationVariantByElementTypeId = RelationVariantRecord::find()->where(['element_type_id' => $elementTypesId])->one();

                    if (!empty($propertyVariantByElementTypeId)) {
                        $newPropertyRelation = PropertyRelationRecord::find()->where([
                            'element_id'     => $currentElementId,
                            'property_id'    => $propertyVariantByElementTypeId['property_id'],
                            'value_table_id' => $propertyVariantByElementTypeId['value_table_id'],
                            'value_id'       => $propertyVariantByElementTypeId['value_id'],
                        ])->one();

                        if (is_null($newPropertyRelation)) {
                            $newPropertyRelation = new PropertyRelationRecord();
                            $newPropertyRelation->setAttributes([
                                'element_id'     => $currentElementId,
                                'property_id'    => $propertyVariantByElementTypeId['property_id'],
                                'value_table_id' => $propertyVariantByElementTypeId['value_table_id'],
                                'value_id'       => $propertyVariantByElementTypeId['value_id'],
                            ]);
                            if (!$newPropertyRelation->save()) {
                                Yii::$app->getDb()->getTransaction()->rollBack();

                                return $newPropertyRelation;
                            }
                        }
                    }

                    if (!empty($relationVariantByElementTypeId)) {
                        $relationClassRecord = RelationClassRecord::findOne($relationVariantByElementTypeId->getAttribute('relation_class_id'));
                        $relationGroup = RelationGroupRecord::find()->where([
                            'relation_class_id' => $relationClassRecord->getAttribute('id'),
                            'root_id'           => $currentElementId,
                        ])->one();

                        if (is_null($relationGroup)) {
                            $relationGroup = new RelationGroupRecord();
                            $relationGroup->setAttributes([
                                'name'              => $relationClassRecord->getAttribute('name') . ' ' . $schemaElement['name'],
                                'relation_class_id' => $relationClassRecord->getAttribute('id'),
                                'root_id'           => $currentElementId,
                            ], false);
                            if (!$relationGroup->save()) {
                                Yii::$app->getDb()->getTransaction()->rollBack();

                                return $relationGroup;
                            }
                        }

                        $currentRelationGroupId = $relationGroup->getPrimaryKey();
                        $relationRecord = RelationRecord::find()->where([
                            'relation_group_id' => $currentRelationGroupId,
                            'child_element_id'  => $relationVariantByElementTypeId->getAttribute('related_element_id'),
                        ])->one();

                        if (is_null($relationRecord)) {
                            $relationRecord = new RelationRecord();
                            $relationRecord->setAttributes([
                                'relation_group_id' => $currentRelationGroupId,
                                'parent_element_id' => $currentElementId,
                                'child_element_id'  => $relationVariantByElementTypeId->getAttribute('related_element_id'),
                                'value'             => $relationVariantByElementTypeId->getAttribute('value'),
                                'property_unit_id'  => $relationVariantByElementTypeId->getAttribute('property_unit_id'),
                            ]);
                            if (!$relationRecord->save()) {
                                Yii::$app->getDb()->getTransaction()->rollBack();

                                return $relationRecord;
                            }
                        }
                    }
                }
            }
        }

        Yii::$app->getDb()->getTransaction()->commit();

        return true;
    }

    /**
     * @param $attributes
     * @return bool
     * @throws HttpException
     */
    private function consistentSchemaElement($attributes)
    {
        if (!isset($attributes['schemaElement'])) {
            throw new HttpException(400, 'Body must consist of schemaElement array elements. ' . basename(__FILE__, '.php') . __LINE__);
        }

        foreach ($attributes['schemaElement'] as $schemaElement) {
            if (empty($schemaElement['name']) || !is_string($schemaElement['name'])) {
                throw new HttpException(400, 'name can\'t be empty and must be string. ' . basename(__FILE__, '.php') . __LINE__);
            }

            if (!isset($schemaElement['isSchemaElement']) || !in_array($schemaElement['isSchemaElement'], ['0', '1'])) {
                throw new HttpException(400, 'isSchemaElement can\'t be empty and must be 0 or 1. ' . basename(__FILE__, '.php') . __LINE__);
            }

            if (!isset($schemaElement['isActive']) || !in_array($schemaElement['isActive'], ['0', '1'])) {
                throw new HttpException(400, 'isActive can\'t be empty and must be 0 or 1. ' . basename(__FILE__, '.php') . __LINE__);
            }
        }

        return true;
    }

    /**
     * @param $schemaElementId
     * @return bool
     */
    public function deleteElementsBySchemaId($schemaElementId)
    {
        $elementsToDelete = ElementRecord::find()->where(['schema_element_id' => $schemaElementId])->all();
        $elementIdsToDelete = [];

        foreach ($elementsToDelete as $elementToDelete) {
            $elementIdsToDelete[] = $elementToDelete['id'];
        }

        $propertyRelationRecords = PropertyRelationRecord::find()->where([
            'element_id' => $elementIdsToDelete,
        ])->all();

        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($propertyRelationRecords);
            BaseCrudModel::deleteRows($elementsToDelete);
        } catch (\Exception $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }

        Yii::$app->getDb()->getTransaction()->commit();

        return true;
    }

    /**
     * @param $id
     * @param null $variantTypeId
     * @return array
     */
    public function getVariants($id, $variantTypeId = null)
    {
        $elementRecord = ElementRecord::findOne($id);
        $result = [];

        if ($variantTypeId == 1) {
            foreach ($elementRecord->getPropertyVariants()->all() as $propertyVariant) {
                $result['variantsByTypeId'][1][$propertyVariant['id']] = self::instantiateByARAndClassName($propertyVariant);
            }
        } elseif ($variantTypeId == 2) {
            foreach ($elementRecord->getRelationVariants()->all() as $relationVariant) {
                $result['variantsByTypeId'][2][$relationVariant['id']] = self::instantiateByARAndClassName($relationVariant);
            }
        } else {
            foreach ($elementRecord->getPropertyVariants()->all() as $propertyVariant) {
                $result['variantsByTypeId'][1][$propertyVariant['id']] = self::instantiateByARAndClassName($propertyVariant);
            }
            foreach ($elementRecord->getRelationVariants()->all() as $relationVariant) {
                $result['variantsByTypeId'][2][$relationVariant['id']] = self::instantiateByARAndClassName($relationVariant);
            }
        }

        return $result;
    }

    public function getElementsBySchemaId($schemaElementId)
    {
        $elementRecord = new ElementRecord();
        $elements = $elementRecord::find()->where(['schema_element_id' => $schemaElementId])->all();
        $result = [];

        foreach ($elements as $element) {
            $result[$element['id']] = self::instantiateByARAndClassName($element);
        }

        return $result;
    }

    /**
     * @param $attributes
     * @return bool|BaseCrudModel
     */
    public function createModel($attributes)
    {
        $modelRecord = new ModelRecord();
        $modelRecord->setAttributes(self::arrayKeysCamelCase2Underscore($attributes));

        if ($modelRecord->save() || $modelRecord->hasErrors()) {
            return self::instantiateByARAndClassName($modelRecord, 'commonprj\components\core\entities\common\model\Model');
        } else {
            return false;
        }
    }

    /**
     * @param array $params
     * @return array|PropertyRecord|mixed|null|ActiveRecord
     */
    public function getElementsByPropertyValues(array $params)
    {
        $result = [];
        if (isset($params['with'])) {
            $with = $params['with'];
            unset($params['with']);
        }

        $sysmanes = array_keys($params);
        $values = array_values($params);
        $propertyRecords = [];
        foreach ($sysmanes as $key => $sysname) {
            $propertyRecord = PropertyRecord::find()->where(['sysname' => $sysname])->one();

            if (empty($propertyRecord)) {
                $propertyRecord = new PropertyRecord();
                $propertyRecord->addError('sysname', "Property with sysname {$sysname} doesn't exist!");

                return $propertyRecord;
            } else {
                $propertyRecords[] = $propertyRecord;
            }
        }

        foreach ($propertyRecords as $key => $propertyRecord) {
            $propertyIds[] = $propertyRecord->getAttribute('id');
            $valueTableId = $propertyRecord->getAttribute('property_type_id');
            $valueTableIds[] = $valueTableId;
            $propertyValueTable = $propertyValueTable = self::VALUE_TABLE_ID[$valueTableId];
            $valueTableRecord = 'commonprj\components\core\models\\' . BaseInflector::camelize($propertyValueTable) . 'Record';
            $propertyValueIds[] = $valueTableRecord::find()->select('id')->where(['value' => $values[$key], 'property_id' => $propertyIds[$key]])->scalar();
        }

        if (!empty($propertyValueIds) && is_array($propertyValueIds) && in_array(false, $propertyValueIds)) {
            $propertyRecord = new PropertyRecord();
            $propertyRecord->addError('Query Params', "Given wrong query parameters!");

            return $propertyRecord;
        }

        if (!empty($propertyIds) && !empty($valueTableIds) && !empty($propertyValueIds) &&
            ((count($propertyIds) === count($valueTableIds)) && count($valueTableIds) === count($propertyValueIds))
        ) {
            foreach ($propertyIds as $key => $propertyId) {
                $propertyRelationRecords = PropertyRelationRecord::find()->select('element_id')->where([
                    'property_id'    => $propertyId,
                    'value_table_id' => $valueTableIds[$key],
                    'value_id'       => $propertyValueIds[$key],
                ])->all();

                if (!empty($propertyRelationRecords)) {
                    foreach ($propertyRelationRecords as $propertyRelationRecord) {
                        $resultElementIds[] = $propertyRelationRecord->getAttribute('element_id');
                    }
                }
            }
        }

        if (!empty($resultElementIds)) {
            $elementIds = array_keys(array_count_values($resultElementIds), count($sysmanes));
        }

        if (!empty($elementIds)) {
            if (!empty($with)) {
                $elementRecords = ElementRecord::find()->with($with)->where(['id' => $elementIds])->all();
            } else {
                $elementRecords = ElementRecord::find()->where(['id' => $elementIds])->all();
            }

            foreach ($elementRecords as $elementRecord) {
                $result[] = self::instantiateByARAndClassName($elementRecord);
            }
        }

        return $result;
    }
}