<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 05.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * DeleteAction implements the API endpoint for deleting a model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DeleteAction extends PlanironAction
{
    /**
     * @param $id
     * @return Variant|BaseCrudModel
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        if ($this->modelClass === 'commonprj\components\core\entities\common\variant\Variant') {
            $variantTypeId = Yii::$app->getRequest()->getQueryParam('variantTypeId');

            switch ($variantTypeId) {
                case 1:
                    $this->modelClass = 'commonprj\components\core\entities\common\propertyVariant\PropertyVariant';
                    break;
                case 2:
                    $this->modelClass = 'commonprj\components\core\entities\common\relationVariant\RelationVariant';
                    break;
                default:
                    /** @var Variant $model */
                    $model = new $this->modelClass();
                    $model->addError('variantTypeId', 'Available only for Variant type id = 1 or 2');

                    return $model;
            }
        }

        /** @var BaseCrudModel $model */
        $model = $this->findModel($id);


        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}
