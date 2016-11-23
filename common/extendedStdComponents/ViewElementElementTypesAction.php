<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 11.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class viewElementTypesAction
 * @package common\extendedStdComponents
 */
class ViewElementElementTypesAction extends PlanironAction
{
    /**
     * @param $id
     * @return array|\yii\db\ActiveQuery
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = $this->findModel($id);

        return $model->getElementTypes();
    }
}