<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 26.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use yii\base\Model;

/**
 * Class CreateElementClassAction
 * @package common\extendedStdComponents
 */
class CreateElement2ElementClassAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @param $id
     * @param $elementClassId
     * @return \yii\db\ActiveRecord
     */
    public function run($id, $elementClassId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = new $this->modelClass();

        return $model->createElement2ElementClass($id, $elementClassId);
    }
}