<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 16.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\ElementClass2relationClassRecord;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;

/**
 * Class DeleteElementClass2RelationClassAction
 * @package common\extendedStdComponents
 */
class DeleteElementClass2RelationClassAction extends PlanironAction
{
    /**
     * @param $id
     * @param $relationClassId
     * @throws HttpException
     */
    public function run($id, $relationClassId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementClass2relationClassRecord $modelClass */
        $modelClass = 'commonprj\components\core\models\ElementClass2relationClassRecord';
        $relationToDelete = $modelClass::find()
            ->where(['relation_class_id' => $relationClassId, 'element_class_id' => $id])
            ->one();

        if (!$relationToDelete) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        if ($relationToDelete->delete() === false) {
            throw new HttpException(500, 'Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}