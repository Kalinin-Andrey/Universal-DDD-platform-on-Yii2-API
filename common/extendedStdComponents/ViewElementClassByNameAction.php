<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 12.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementClassIdAction
 * @package common\extendedStdComponents
 */
class ViewElementClassByNameAction extends PlanironAction
{
    /**
     * @param $contextNameAndClassName
     * @return ElementClass|\commonprj\extendedStdComponents\BaseCrudModel
     */
    public function run($contextNameAndClassName)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementClass $model */
        $model = new $this->modelClass();

        return $model->getElementClassByName($contextNameAndClassName);
    }
}