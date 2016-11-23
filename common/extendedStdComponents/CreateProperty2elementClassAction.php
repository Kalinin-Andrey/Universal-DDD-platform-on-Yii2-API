<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 19.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\Property2elementClassRecord;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class createPropertyClassAction
 * @package common\extendedStdComponents
 */
class CreateProperty2elementClassAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @param $id
     * @param $elementClassId
     * @throws HttpException
     */
    public function run($id, $elementClassId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $this->modelClass = 'commonprj\components\core\models\Property2elementClassRecord';
        $attributes['property_id'] = $id;
        $attributes['element_class_id'] = $elementClassId;
        /** @var Property2elementClassRecord $model */
        $model = new $this->modelClass();
        $model->setAttributes(BaseDBRepository::arrayKeysCamelCase2Underscore($attributes));

        if (!$model->save() && !$model->hasErrors()) {
            throw new HttpException(500, 'Failed to create the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}