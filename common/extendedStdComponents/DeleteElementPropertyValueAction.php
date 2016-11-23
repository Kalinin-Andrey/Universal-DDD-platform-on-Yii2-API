<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 26.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\PropertyRelationRecord;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class DeletePropertyValueAction
 * @package common\extendedStdComponents
 */
class DeleteElementPropertyValueAction extends PlanironAction
{
    /**
     * @param $id
     * @param $propertyId
     * @throws HttpException
     */
    public function run($id, $propertyId)
    {
        /** @var PropertyRelationRecord $modelRecord */
        $modelRecord = 'commonprj\components\core\models\PropertyRelationRecord';

        /** @var ActiveRecord $model */
        $model = $modelRecord::find()->where([
            'property_id'    => $propertyId,
            'element_id'       => $id,
        ])->one();

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model) {
            if ($model->delete() === false) {
                throw new HttpException(500, 'Failed to delete the object for unknown reason.');
            }

            Yii::$app->getResponse()->setStatusCode(204);
        } else {
            Yii::$app->getResponse()->setStatusCode(404);
        }
    }
}