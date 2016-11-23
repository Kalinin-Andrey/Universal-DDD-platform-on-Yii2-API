<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 10.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementClassRelationClassAction
 * @package common\extendedStdComponents
 */
class ViewElementClass2RelationClassesAction extends PlanironAction
{
    /**
     * @param $id
     * @param $isRoot
     * @return \commonprj\components\core\models\ElementClassRecord[]
     */
    public function run($id, $isRoot)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementClass $model */
        $model = $this->findModel($id);

        return $model->getRelationClassesByIsRoot($isRoot);
    }
}