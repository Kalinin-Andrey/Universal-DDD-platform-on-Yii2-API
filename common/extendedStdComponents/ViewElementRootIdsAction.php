<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 13.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class viewRootIdsAction
 * @package common\extendedStdComponents
 */
class ViewElementRootIdsAction extends PlanironAction
{
    /**
     * @param $id
     * @param $relationGroupId
     * @return mixed|ActiveDataProvider|\yii\db\ActiveQuery
     */
    public function run($id, $relationGroupId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = $this->findModel($id);

        return $model->getRoot($relationGroupId);
    }
}