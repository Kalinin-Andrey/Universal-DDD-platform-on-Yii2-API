<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class DeletePropertyValueAction
 * @package common\extendedStdComponents
 */
class DeletePropertyValueAction extends PlanironAction
{
    /**
     * @param $id
     * @param $propertyValueId
     * @throws ServerErrorHttpException
     */
    public function run($id, $propertyValueId)
    {
        /** @var Property $model */
        $model = $this->findModel($id);

        if ($model->deletePropertyValue($propertyValueId) === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}