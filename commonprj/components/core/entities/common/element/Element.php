<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\element;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class Element
 * @package commonprj\components\core\entities\common\element
 */
class Element extends BaseCrudModel
{
    public $id;
    public $name;
    public $schemaElementId;
    public $isSchemaElement;
    public $isActive = 0;
    public $elementClasses;
    public $elementTypes;
    public $models;
    public $properties;
    public $parents;
    public $children;
    public $root;
    public $hierarchy;
    public $inclusions;
    public $relationClasses;
    public $relationGroups;
    public $schemaElement;
    public $variants;
    public $relations;
    public $relationVariants;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->elementRepository;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isActive'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * @inheritdoc
     */
    public function update()
    {
        return $this->repository->update($this);
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        $this->repository->deleteElementById($this->id);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function findOne($condition)
    {
        return $this->repository->findOne($condition);
    }

    /**
     * @param string $condition
     * @return Element[]
     */
    public function find($condition = null)
    {
        return $this->repository->find($condition);
    }

    /**
     * Метод возвращает классы к которым принадлежит текущий элемент.
     * @return \commonprj\components\core\models\ElementClassRecord[]
     * @throws HttpException
     */
    public function getElementClasses()
    {
        return $this->repository->getClassesByElementId($this->id);
    }

    /**
     * @param $attributes
     * @return ActiveRecord
     */
    public function createInclusion($attributes)
    {
        return $this->repository->createInclusion($attributes);
    }

    /**
     * @param int $childId
     * @param int $relationGroupId
     * @throws HttpException
     */
    public function deleteChildById(int $childId, int $relationGroupId)
    {
        $this->repository->deleteChildById($this->id, $childId, $relationGroupId);
    }

    /**
     * Метод определяет является ли текущий элемент родителем.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая иерархия.
     * @param int $id
     * @return array
     */
    public function getIsParent(int $relationGroupId, int $id = null)
    {
        if (!$id) {
            $id = $this->id;
        }

        return $this->repository->isParent($id, $relationGroupId);
    }

    /**
     * @param $elementId
     * @param $relationGroupId
     * @return bool
     * @throws HttpException
     */
    public function deleteInclusionById(int $elementId, int $relationGroupId)
    {
        if (!$this->repository->ownerHasInclusion($this->id, $elementId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            $this->repository->deleteFromRelationInclusion($elementId, $relationGroupId);
        } catch (\Exception $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }

        Yii::$app->getDb()->getTransaction()->commit();

        return true;
    }

    /**
     * Метод возвращает дочерние элементы.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая иерархия.
     * @param bool $recursion - Если true, то вернет все дерево иерархии, по умолчанию false
     * и возвращает только прямых потомков элемента.
     * @return Element[]
     * @throws HttpException
     */
    public function getChildren(int $relationGroupId, bool $recursion = false)
    {
        return $this->repository->getChildren($this->id, $relationGroupId, $recursion);
    }

    /**
     * @param int $relationGroupId
     * @return \commonprj\components\core\models\RelationHierarchyRecord[]
     * @throws HttpException
     */
    public function getElementHierarchy(int $relationGroupId)
    {
        return $this->repository->getHierarchyRecursion($this->id, $relationGroupId);
    }

    /**
     * @return array|\yii\db\ActiveQuery
     * @throws HttpException
     */
    public function getElementTypes()
    {
        return $this->repository->getElementTypesByElementId($this->id);
    }

    /**
     * Метод возвращает связи в которых участвует текущий элемент.
     * @param int $relationGroupId
     * @return \commonprj\components\core\models\ElementRecord[]
     */
    public function getInclusions(int $relationGroupId)
    {
        return $this->repository->getInclusions($this, $relationGroupId);
    }

    /**
     * Метод возвращает связанные с элементом модели.
     * @return \commonprj\components\core\models\ModelRecord[]
     * @throws HttpException
     */
    public function getModels()
    {
        return $this->repository->getModels($this->id);
    }

    /**
     * Метод возвращает родительский элемент текущего элемента.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая иерархия.
     * @return Element
     * @throws HttpException
     */
    public function getParent(int $relationGroupId)
    {
        return $this->repository->getParent($this->id, $relationGroupId);
    }

    /**
     * Метод возвращает свойства текущего элемента.
     * @return Property[]
     * @throws HttpException
     */
    public function getProperties()
    {
        return $this->repository->getProperties($this->id);
    }

    /**
     * @return Element[]
     */
    public function getRelationClasses()
    {
        return $this->repository->getRelationClasses($this->id);
    }

    /**
     * Метод возвращает список групп связей в которых задействован текущий эелемент.
     * @return \yii\db\ActiveQuery
     * @throws HttpException
     */
    public function getRelationGroups()
    {
        return $this->repository->getRelationGroups($this->id);
    }

    /**
     * @param $relationGroupId
     * @return \yii\db\ActiveQuery
     */
    public function getRoot($relationGroupId)
    {
        return $this->repository->getRoot($this->id, $relationGroupId);
    }

    /**
     * @param $propertyId
     * @param array $condition
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getProperty($propertyId, array $condition = [])
    {
        return $this->repository->getProperty($this->id, $propertyId, $condition);
    }

    /**
     * @param int $id
     * @param int $elementClassId
     * @return ActiveRecord
     */
    public function createElement2ElementClass(int $id, int $elementClassId)
    {
        return $this->repository->createElement2ElementClass($id, $elementClassId);
    }

    /**
     * @param $propertyId
     * @throws HttpException
     */
    public function deletePropertyValue($propertyId)
    {
        $type = $this->repository->getPropertyTypeById($propertyId);
        $propertyValueString = "\\commonprj\\components\\core\\models\\{$type}PropertyValueRecord";
        $propertyValueRecord = $this->repository->getPropertyValueRecord($propertyValueString, $propertyId)->all();
        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($propertyValueRecord);
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @param $modelId
     * @return mixed
     */
    public function getModelById($modelId)
    {
        return $this->repository->getModelById($modelId);
    }

    /**
     * @param $elementId
     * @param $propertyId
     * @return \commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValue
     */
    public function getPropertyValue($elementId, $propertyId)
    {
        return $this->repository->getAbstractPropertyValueByElementAndPropertyId($elementId, $propertyId);
    }

    public function getIsSchemaElement()
    {

    }

    /**
     * @param null $variantTypeId
     * @return array
     */
    public function getVariants($variantTypeId = null)
    {
        return $this->repository->getVariants($this->id, $variantTypeId);
    }

    /**
     * @param $schemaElementId
     * @return mixed
     */
    public function getElementsBySchemaId($schemaElementId)
    {
        return $this->repository->getElementsBySchemaId($schemaElementId);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function createModel($attributes)
    {
        return $this->repository->createModel($attributes);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getElementsByPropertyValues($params)
    {
        return $this->repository->getElementsByPropertyValues($params);
    }
}