<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 06.09.2016
 */

namespace commonprj\components\core\entities\common\relationGroup;


use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;
use yii\web\HttpException;

/**
 * Class RelationGroupServiceRepository
 * @package commonprj\components\core\entities\common\relationGroup
 */
class RelationGroupServiceRepository  extends BaseServiceRepository implements RelationGroupRepository
{
    /**
     * @param $condition
     * @return array
     */
    public function find($condition)
    {
        $this->requestUri = 'common/relation-group';
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * Найти модель по id или другому условию.
     * @param $relationGroupId
     * @param bool $byClass - Если true, вернет модель только если она принадлежит классу от которого пришел запрос.
     * @return BaseCrudModel - Возвращает класс доменного слоя, наследуемый от BaseCrudModel.
     */
    public function findOne($relationGroupId, $byClass = false)
    {
        $this->requestUri = 'common/relation-group/' . $relationGroupId;
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param $relationGroupId
     * @return BaseCrudModel
     */
    public function getRelationClass($relationGroupId)
    {
        $this->requestUri = 'common/relation-group/' . $relationGroupId . '/relation-class';
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }
}