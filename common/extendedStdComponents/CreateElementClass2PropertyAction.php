<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.08.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\extendedStdComponents\PlanironAction;
use yii\base\Model;

/**
 * Class CreateElementClass2PropertyAction
 * @package common\extendedStdComponents
 */
class CreateElementClass2PropertyAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @param $id
     * @param $propertyId
     * @return \yii\db\ActiveRecord
     */
    public function run($id, $propertyId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementClass $model */
        $model = new $this->modelClass();

        return $model->createProperty2ElementClass($id, $propertyId);
    }
}