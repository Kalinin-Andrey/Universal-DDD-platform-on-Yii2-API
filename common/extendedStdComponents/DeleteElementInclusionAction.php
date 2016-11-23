<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteInclusionAction
 * @package common\extendedStdComponents
 */
class DeleteElementInclusionAction extends PlanironAction
{
    /**
     * @param $id
     * @param $inclusionElementId
     * @param $relationGroupId
     * @throws ServerErrorHttpException
     */
    public function run($id, $inclusionElementId, $relationGroupId)
    {
        /** @var Element $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->deleteInclusionById($inclusionElementId, $relationGroupId) === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}