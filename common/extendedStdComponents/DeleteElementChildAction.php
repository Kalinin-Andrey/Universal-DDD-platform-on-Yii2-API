<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 07.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * Class DeleteChildAction
 * @package common\extendedStdComponents
 */
class DeleteElementChildAction extends PlanironAction
{
    /**
     * @param $id
     * @param $childElementId
     * @param $relationGroupId
     */
    public function run($id, $childElementId, $relationGroupId)
    {
        /** @var Element $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->deleteChildById($childElementId, $relationGroupId);
        Yii::$app->getResponse()->setStatusCode(204);
    }
}