<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 15.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class ViewRelationClassesAction
 * @package common\extendedStdComponents
 */
class ViewElementRelationClassesAction  extends PlanironAction
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

        /** @var Element $model */
        $model = $this->findModel($id);
        return $model->getRelationClasses();
    }
}