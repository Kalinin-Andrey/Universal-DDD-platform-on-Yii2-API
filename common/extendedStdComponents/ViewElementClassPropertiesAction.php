<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 11.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementClassPropertiesAction
 * @package common\extendedStdComponents
 */
class ViewElementClassPropertiesAction extends PlanironAction
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

        /** @var ElementClass $model */
        $model = $this->findModel($id);

        return $model->getPropertiesById();
    }
}