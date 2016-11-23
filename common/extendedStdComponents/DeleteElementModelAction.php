<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 15.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\components\core\entities\common\model\Model;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;

/**
 * Class DeleteElementModelAction
 * @package common\extendedStdComponents
 */
class DeleteElementModelAction extends PlanironAction
{
    /**
     * @param $id
     * @param $modelId
     * @throws HttpException
     */
    public function run($id, $modelId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $element */
        $element = $this->findModel($id);

        /** @var Model $model */
        $model = $element->getModelById($modelId);

        if ($model->delete() === false) {
            throw new HttpException(500, 'Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}