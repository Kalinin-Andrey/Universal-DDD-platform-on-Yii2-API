<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class ViewRelationClassGroupsAction
 * @package common\extendedStdComponents
 */
class ViewRelationClassGroupsAction extends PlanironAction
{
    /**
     * @param int $id
     * @return mixed|ActiveDataProvider
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var RelationClass $model */
        $model = $this->findModel($id);

        return $model->getRelationGroups();
    }
}