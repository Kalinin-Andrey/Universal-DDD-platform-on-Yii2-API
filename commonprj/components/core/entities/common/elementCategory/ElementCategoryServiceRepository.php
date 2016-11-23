<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.08.2016
 */

namespace commonprj\components\core\entities\common\elementCategory;

use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class ElementCategoryServiceRepository
 * @package commonprj\components\core\entities\common\elementCategory
 */
class ElementCategoryServiceRepository extends BaseServiceRepository implements ElementCategoryRepository
{
    /**
     * @param null $condition
     * @return ElementCategory[]
     */
    public function find($condition = null)
    {
        $this->requestUri = 'common/element-category';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData(true);

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $elementCategoryId
     * @param bool $byClass
     * @return BaseCrudModel
     */
    public function findOne($elementCategoryId, $byClass = false)
    {
        $this->requestUri = 'common/element-category/' . $elementCategoryId;
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData(true);

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $elementCategoryId
     * @return ElementCategory[]
     */
    public function getChildrenById($elementCategoryId)
    {
        $this->requestUri = 'common/element-category/' . $elementCategoryId . '/children';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $elementCategoryId
     * @return bool
     */
    public function getIsParent(int $elementCategoryId)
    {
        $this->requestUri = 'common/element-category/' . $elementCategoryId . '/is-parent';
        $arModel = $this->getAndCheckApiData();

        return $this->getBooleanResult($arModel, 'isParent');
    }

    /**
     * @param int $elementCategoryId
     * @return BaseCrudModel
     */
    public function getParentByParentId(int $elementCategoryId)
    {
        $this->requestUri = 'common/element-category/' . $elementCategoryId . '/parent';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $elementCategoryId
     * @return BaseCrudModel
     */
    public function getRootById(int $elementCategoryId)
    {
        $this->requestUri = 'common/element-category/' . $elementCategoryId . '/root';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $rootElementCategoryId
     * @return ElementCategory[]
     */
    public function getHierarchyByRootId($rootElementCategoryId)
    {
        $this->requestUri = 'common/element-category/hierarchy/' . $rootElementCategoryId;
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @return ElementCategory[]
     */
    public function getRoots()
    {
        $this->requestUri = 'common/element-category/roots';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param $childElementCategoryId
     * @return BaseCrudModel
     */
    public function getParentByChildId($childElementCategoryId)
    {
        $this->requestUri = 'common/element-category/' . $childElementCategoryId . 'parent';
        $this->requestParams = [
            'isActive' => 1,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }
}