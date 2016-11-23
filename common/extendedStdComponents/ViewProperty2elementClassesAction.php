<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 19.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewPropertyClassesAction
 * @package common\extendedStdComponents
 */
class ViewProperty2elementClassesAction extends PlanironAction
{
    /**
     * @param $id
     * @return \commonprj\components\core\models\ElementClassRecord[]
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Property $model */
        $model = $this->findModel($id);

        return $model->getPropertyClasses();
    }
}