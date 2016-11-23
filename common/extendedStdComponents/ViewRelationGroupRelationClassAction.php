<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 26.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\relationGroup\RelationGroup;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class ViewRelationGroupClassAction
 * @package common\extendedStdComponents
 */
class ViewRelationGroupRelationClassAction extends PlanironAction
{

    /**
     * @param $id
     * @return mixed|ActiveDataProvider
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var RelationGroup $model */
        $model = $this->findModel($id);

        return $model->getRelationClass();
    }
}