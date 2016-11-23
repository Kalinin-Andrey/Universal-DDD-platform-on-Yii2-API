<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.10.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\ElementDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;

/**
 * Class DeleteVariantsBySchemaIdAction
 * @package common\extendedStdComponents
 */
class DeleteVariantsBySchemaIdAction extends PlanironAction
{
    public function run($id, $variantTypeId)
    {
        $this->modelClass = 'commonprj\components\core\entities\common\element\ElementDBRepository';
        /** @var ElementDBRepository $model */
        $model = new $this->modelClass();

        if ($model) {
            if ($model->deleteVariantsBySchemaId($id, $variantTypeId) === false) {
                throw new HttpException(500, 'Failed to delete the object for unknown reason.');
            }

            Yii::$app->getResponse()->setStatusCode(204);
        } else {
            Yii::$app->getResponse()->setStatusCode(404);
        }
    }
}