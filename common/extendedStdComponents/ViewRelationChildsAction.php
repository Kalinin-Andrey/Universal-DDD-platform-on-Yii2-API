<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.10.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\relation\Relation;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewRelationChildsAction
 * @package common\extendedStdComponents
 */
class ViewRelationChildsAction extends PlanironAction
{
    /**
     * @param $id
     * @return array
     */
    public function run($id)
    {
        /** @var Relation $model */
        $model = $this->findModel([
            'condition' => ['id' => $id],
            'modelClass' => $this->modelClass
        ]);

        return $model->getChildren();
    }
}