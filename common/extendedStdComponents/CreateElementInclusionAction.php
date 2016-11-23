<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 13.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;

/**
 * Class createInclusion2ElementAction
 * @package common\extendedStdComponents
 */
class CreateElementInclusionAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecord
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $attributes = array_merge(Yii::$app->getRequest()->getQueryParams(), Yii::$app->getRequest()->getBodyParams());

        /** @var Element $model */
        $model = $this->findModel($attributes['id']);

        return $model->createInclusion($attributes);
    }
}