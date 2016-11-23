<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 11.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class viewModelsAction
 * @package common\extendedStdComponents
 */
class ViewElementModelsAction extends PlanironAction
{
    /**
     * @param $id
     * @return \commonprj\components\core\models\ModelRecord[]|mixed|ActiveDataProvider
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = $this->findModel($id);

        return $model->getModels();
    }
}