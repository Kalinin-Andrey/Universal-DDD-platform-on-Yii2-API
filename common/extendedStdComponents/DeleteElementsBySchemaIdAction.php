<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 15.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\ElementDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;

/**
 * Class DeleteElementsBySchemaIdAction
 * @package common\extendedStdComponents
 */
class DeleteElementsBySchemaIdAction extends PlanironAction
{
    /**
     * @param $id
     * @throws HttpException
     */
    public function run($id)
    {
        $this->modelClass = 'commonprj\components\core\entities\common\element\ElementDBRepository';
        /** @var ElementDBRepository $model */
        $model = new $this->modelClass();

        if ($model) {
            if ($model->deleteElementsBySchemaId($id) === false) {
                throw new HttpException(500, 'Failed to delete the object for unknown reason.');
            }

            Yii::$app->getResponse()->setStatusCode(204);
        } else {
            Yii::$app->getResponse()->setStatusCode(404);
        }
    }
}