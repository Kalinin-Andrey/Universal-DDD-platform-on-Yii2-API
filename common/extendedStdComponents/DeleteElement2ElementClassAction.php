<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 15.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\Element2elementClassRecord;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;

/**
 * Class DeleteElement2ElementClassAction
 * @package common\extendedStdComponents
 */
class DeleteElement2ElementClassAction extends PlanironAction
{
    /**
     * @param $id
     * @param $elementClassId
     * @return bool|\yii\db\ActiveRecord
     * @throws HttpException
     */
    public function run($id, $elementClassId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element2elementClassRecord $modelClass */
        $modelClass = 'commonprj\components\core\models\Element2elementClassRecord';

        $elementClasses = $modelClass::find()->where(['element_id' => $id])->all();

        if (count($elementClasses) < 2) {
            $elementClasses[0]->addError('Element Class', 'If an element has only one class, this class can not be deleted.');

            return $elementClasses[0];
        }

        $relationToDelete = $modelClass::find()
            ->where(['element_class_id' => $elementClassId, 'element_id' => $id])
            ->one();

        if (!$relationToDelete) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        if ($relationToDelete->delete() === false) {
            throw new HttpException(500, 'Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);

        return 0;
    }
}