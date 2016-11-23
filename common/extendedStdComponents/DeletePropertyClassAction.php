<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * Class deletePropertyClass
 * @package common\extendedStdComponents
 */
class DeletePropertyClassAction extends PlanironAction
{
    /**
     * @param $propertyId
     * @param $elementClassId
     */
    public function run($propertyId, $elementClassId)
    {
        /** @var Property $model */
        $model = $this->findModel([$propertyId, $elementClassId]);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->deletePropertyClass($elementClassId);
        Yii::$app->getResponse()->setStatusCode(204);
    }
}