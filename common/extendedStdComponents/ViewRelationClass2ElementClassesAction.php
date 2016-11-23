<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class ViewRelationClassClassesAction
 * @package common\extendedStdComponents
 */
class ViewRelationClass2ElementClassesAction extends PlanironAction
{
    /**
     * @param int $id
     * @param $isRoot
     * @return mixed|ActiveDataProvider
     */
    public function run($id, $isRoot)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var RelationClass $model */
        $model = $this->findModel($id);

        return $model->getElementClassesByIsRoot($isRoot);
    }
}