<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 11.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class viewIsParentAction
 * @package common\extendedStdComponents
 */
class ViewElementIsParentAction extends PlanironAction
{

    /**
     * @param $id
     * @param $relationGroupId
     * @return array
     */
    public function run($id, $relationGroupId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = $this->findModel($id);
        return $model->getIsParent($relationGroupId);
    }
}