<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\ElementClass2relationClassRecord;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\HttpException;

/**
 * Class DeleteRelationClassClassAction
 * @package common\extendedStdComponents
 */
class DeleteRelationClass2ElementClassAction extends PlanironAction
{
    /**
     * @param $id
     * @param $elementClassId
     * @throws HttpException
     */
    public function run($id, $elementClassId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementClass2relationClassRecord $modelClass */
        $modelClass = 'commonprj\components\core\models\ElementClass2relationClassRecord';
        $relationToDelete = $modelClass::find()
            ->where(['element_class_id' => $elementClassId, 'relation_class_id' => $id])
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