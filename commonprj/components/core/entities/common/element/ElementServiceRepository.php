<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 22.08.2016
 */

namespace commonprj\components\core\entities\common\element;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\components\core\entities\common\elementType\ElementType;
use commonprj\components\core\entities\common\model\Model;
use commonprj\components\core\entities\common\property\Property;
use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\components\core\entities\common\relationGroup\RelationGroup;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class ElementServiceRepository
 * @package commonprj\components\core\entities\common\element
 */
class ElementServiceRepository extends BaseServiceRepository implements ElementRepository
{
    /**
     * @param array $condition
     * @return \commonprj\extendedStdComponents\BaseCrudModel[]
     */
    public function find(array $condition = null)
    {
        $arResult = $this->getModelAndApiCallData();
        //Здесь и везде сетим $this->requestUri и если нужно $this->requestParams
        $this->requestUri = $arResult;
        $this->requestParams = ['isActive' => 1];

        if (!empty($condition)) {
            $this->requestParams = array_merge($this->requestParams, $condition);
        }
        $arModel = $this->getAndCheckApiData(true);

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param array|bool $condition
     * @return BaseCrudModel
     */
    public function findOne($condition)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $condition['id'];
        unset($condition['id']);
        $this->requestParams = ['isActive' => 1];

        if ($condition) {
            $this->requestParams = array_merge($this->requestParams, $condition);
        }
        $arModel = $this->getAndCheckApiData(true);

        return $this->getOneModel($arModel);
    }

    /**
     * Метод возвращает дочерние элементы.
     * @param int $id
     * @param int $relationGroupId
     * @param bool $recursion
     * @return Element[]
     */
    public function getChildren(int $id, int $relationGroupId, bool $recursion = false)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/children';
        $this->requestParams = [
            'isActive'       => 1,
            'relationGroupId' => $relationGroupId,
        ];
        $arModel = $this->getAndCheckApiData();
        $arModel = $this->getArrayOfModels($arModel);

        return $arModel;
    }

    /**
     * @param int $id
     * @return ElementClass[]
     */
    public function getElementClassesById(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/element-classes';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $id
     * @return ElementType[]
     */
    public function getElementTypesByElementId(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult['classAndContext'] . '/' . $id . '/element-types';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param $id
     * @param int $relationGroupId
     * @return RelationGroup[]
     */
    public function getHierarchyRecursion($id, int $relationGroupId)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/hierarchy';
        $this->requestParams = [
            'isActive'       => 1,
            'relationGroupId' => $relationGroupId,
        ];
        $arModel = $this->getAndCheckApiData();

        if ($arModel) {
            $root = $this->getOneModel($arModel);
            //Переменные для рекурсивной работы цикла
            $children = $this->getChildrenRecursion($arModel['children']);
            $arModel = [];
            $arModel['root'] = $root;
            $arModel['children'] = $children;
        }

        return $arModel;
    }

    /**
     * @param array $children
     * @param array $arResult
     * @return array Метод рекурсивно мапит детей иерархии
     * Метод рекурсивно мапит детей иерархии
     */
    private function getChildrenRecursion(array $children, array $arResult = [])
    {
        foreach ($children as $key => $child) {
            $arResult[$key]['root'] = $this->getOneModel($child);
            $arResult[$key]['children'] = [];

            if (!empty($child['children'])) {
                $arResult[$key]['children'] = array_merge($arResult[$key]['children'], $this->getChildrenRecursion($child['children'], $arResult));
            }
        }
        return $arResult;
    }

    /**
     * Метод возвращает связи в которых участвует текущий элемент.
     * @param Element $element
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая связь.
     * @return BaseCrudModel[]
     */
    public function getInclusions(Element $element, int $relationGroupId)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $element->id . '/inclusions';
        $this->requestParams = [
            'isActive'       => 1,
            'relationGroupId' => $relationGroupId,
        ];
        $arModel = $this->getAndCheckApiData();
        $arModel = $this->getArrayOfModels($arModel);

        return $arModel;
    }

    /**
     * Метод определяет является ли текущий элемент родителем.
     * @param int $id
     * @param int $relationGroupId
     * @return bool
     */
    public function isParent(int $id, int $relationGroupId)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/is-parent';
        $this->requestParams = [
            'relationGroupId' => $relationGroupId,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getBooleanResult($arModel, 'isParent');
    }

    /**
     * Метод возвращает связанные с элементом модели.
     * @param int $id
     * @return Model[]
     */
    public function getModels(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/models';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * Метод возвращает родительский элемент переданного id.
     * @param int $id
     * @param int $relationGroupId
     * @return BaseCrudModel
     */
    public function getParent(int $id, int $relationGroupId)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/parent';
        $this->requestParams = [
            'isActive'       => 1,
            'relationGroupId' => $relationGroupId,
        ];
        $arModel = $this->getAndCheckApiData();
        $arModel = $this->getOneModel($arModel);

        return $arModel;
    }

    /**
     * @param int $id
     * @return Property[]
     */
    public function getProperties(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/properties';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param $id
     * @param $propertyId
     * @param array $condition
     * @return BaseCrudModel
     */
    public function getProperty($id, $propertyId, array $condition = [])
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/property/' . $propertyId;
        $this->requestParams = $condition;
        $arModel = $this->getAndCheckApiData();
        $property = $this->getOneModel($arModel);
        //TODO extract relation properties into map method
        if (!empty($property->propertyValue)) {
            $property->propertyValue = $this->getOneModel($property->propertyValue);
        }

        return $property;
    }

    /**
     * @param $id
     * @return RelationClass[]
     */
    public function getRelationClasses(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/relation-classes/';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * Метод возвращает список групп связей в которых задействован текущий эелемент.
     * @param int $id
     * @return RelationGroup[]
     */
    public function getRelationGroups(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/relation-groups';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $id
     * @param int $relationGroupId
     * @return BaseCrudModel
     */
    public function getRoot(int $id, int $relationGroupId)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/root';
        $this->requestParams = [
            'isActive'       => 1,
            'relationGroupId' => $relationGroupId,
        ];
        $arModel = $this->getAndCheckApiData();
        $arModel = $this->getOneModel($arModel);

        return $arModel;
    }

    /**
     * Возвращает массив классов, связанных с id запрошенного элемента.
     * @param int $id
     * @return ElementClass[]
     */
    public function getClassesByElementId(int $id)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/element-classes';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * Возвращает объект Property (со значением) по заданному $propertyId если он связан с заданным $elementId
     * @param $id
     * @param $propertyId - id свойства которое запрашивается.
     * @return BaseCrudModel
     */
    public function getAbstractPropertyValueByElementAndPropertyId($id, $propertyId)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/' . $id . '/property/' . $propertyId . '/property-value';
        $arModel = $this->getAndCheckApiData();
        $modelClass = $this->getFullModelClassName('common\AbstractPropertyValue');

        return $this->getOneModel($arModel, $modelClass);
    }

    /**
     * @param array $params
     * @return array|\commonprj\extendedStdComponents\BaseCrudModel[]
     */
    public function getElementsByPropertyValues(array $params)
    {
        $arResult = $this->getModelAndApiCallData();
        $this->requestUri = $arResult . '/find-by-properties';
        $this->requestParams = $params;
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }
}