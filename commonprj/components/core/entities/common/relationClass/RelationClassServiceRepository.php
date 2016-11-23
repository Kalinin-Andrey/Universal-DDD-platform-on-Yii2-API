<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 29.08.2016
 */

namespace commonprj\components\core\entities\common\relationClass;


use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class RelationClassServiceRepository
 * @package commonprj\components\core\entities\common\relationClass
 */
class RelationClassServiceRepository extends BaseServiceRepository implements RelationClassRepository
{
    /**
     * @param mixed $condition
     * @return array
     */
    public function find($condition = null)
    {
        $this->requestUri = 'common/relation-class';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param $relationClassId
     * @param bool $byClass
     * @return BaseCrudModel
     */
    public function findOne($relationClassId, $byClass = false)
    {
        $this->requestUri = 'common/relation-class/' . $relationClassId;
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param $relationClassId
     * @param $isRoot
     * @return BaseCrudModel
     * @internal param $condition
     */
    public function getElementClassesById($relationClassId, $isRoot)
    {
        $this->requestUri = 'common/relation-class/' . $relationClassId . '/element-classes';
        $this->requestParams = ['isRoot' => $isRoot];
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $relationClassId
     * @return array
     */
    public function getRelationGroups(int $relationClassId)
    {
        $this->requestUri = 'common/relation-class' . $relationClassId . '/relation-groups';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }
}