<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 07.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class ViewInclusionsAction
 * @package common\extendedStdComponents
 */
class ViewElementInclusionsAction extends PlanironAction
{
    /**
     * @param $id
     * @param $relationGroupId
     * @return ActiveDataProvider
     */
    public function run($id, $relationGroupId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = $this->findModel($id);
        $result = $model->getInclusions($relationGroupId);

        return $result;
    }
}