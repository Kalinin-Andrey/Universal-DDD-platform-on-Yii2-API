<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 29.08.2016
 */

namespace commonprj\components\core\entities\common\elementClass;


use commonprj\components\core\entities\common\property\Property;
use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class ElementClassServiceRepository
 * @package commonprj\components\core\entities\common\elementClass
 */
class ElementClassServiceRepository extends BaseServiceRepository implements ElementClassRepository
{
    /**
     * @param null $condition
     * @return ElementClass[]
     */
    public function find($condition = null)
    {
        $this->requestUri = 'common/element-class';
        $arModel = $this->getAndCheckApiData(true);

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param int $elementClassId
     * @param bool $byClass
     * @return BaseCrudModel
     */
    public function findOne($elementClassId, $byClass = false)
    {
        $this->requestUri = 'common/element-class/' . $elementClassId;
        $arModel = $this->getAndCheckApiData(true);

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $elementClassId
     * @return array
     */
    public function getContext(int $elementClassId)
    {
        $this->requestUri = 'common/element-class/' . $elementClassId . '/context';

        return $this->getAndCheckApiData();
    }

    /**
     * @param $elementClassId
     * @param bool $isRoot
     * @return RelationClass[]
     */
    public function getRelationClassesById($elementClassId, bool $isRoot)
    {
        $this->requestUri = 'common/element-class/' . $elementClassId . '/relation-classes';
        $this->requestParams = [
            'isRoot' => $isRoot,
        ];
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param string $className example 'material.material'
     * @return BaseCrudModel
     */
    public function getElementClassByName($className)
    {
        $this->requestUri = 'common/element-class/by-name/' . $className;
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param $elementClassId
     * @return Property[]
     */
    public function getPropertiesById($elementClassId)
    {
        $this->requestUri = 'common/element-class/' . $elementClassId . '/properties';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }
}