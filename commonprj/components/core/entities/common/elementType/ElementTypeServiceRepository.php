<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 29.08.2016
 */

namespace commonprj\components\core\entities\common\elementType;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class ElementTypeServiceRepository
 * @package commonprj\components\core\entities\common\elementType
 */
class ElementTypeServiceRepository extends BaseServiceRepository implements ElementTypeRepository
{
    /**
     * @param null $condition
     * @return ElementType[]
     */
    public function find($condition = null)
    {
        $this->requestUri = 'common/element-type';
        $arModel = $this->getAndCheckApiData(true);

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $elementTypeId
     * @param bool $byClass
     * @return BaseCrudModel
     */
    public function findOne($elementTypeId, $byClass = false)
    {
        $this->requestUri = 'common/element-type/' . $elementTypeId;
        $arModel = $this->getAndCheckApiData(true);

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $elementTypeId
     * @return ElementCategory[]
     */
    public function getElementCategoriesById(int $elementTypeId)
    {
        $this->requestUri = 'common/element-type/' . $elementTypeId . '/element-category';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $elementTypeId
     * @return BaseCrudModel
     */
    public function getElementClassById(int $elementTypeId)
    {
        $this->requestUri = 'common/element-type/' . $elementTypeId . '/element-class';
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param $elementTypeId
     * @return array|BaseCrudModel
     */
    public function getVariantById($elementTypeId)
    {
        $this->requestUri = 'common/element-type/' . $elementTypeId . '/variant';
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }
}