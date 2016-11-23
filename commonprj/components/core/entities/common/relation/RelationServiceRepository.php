<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 18.10.2016
 */

namespace commonprj\components\core\entities\common\relation;

use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class RelationServiceRepository
 * @package commonprj\components\core\entities\common\relation
 */
class RelationServiceRepository extends BaseServiceRepository
{
    public function find($condition = null)
    {
        $this->requestUri = 'common/relation';

        if (!empty($condition)) {
            $this->requestParams = $condition;
        }
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }
}