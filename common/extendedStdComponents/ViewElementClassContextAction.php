<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 16.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementClassContextAction
 * @package common\extendedStdComponents
 */
class ViewElementClassContextAction extends PlanironAction
{
    /**
     * @param $id
     * @return string
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementClass $model */
        $model = $this->findModel($id);

        return $model->getContext();
    }
}