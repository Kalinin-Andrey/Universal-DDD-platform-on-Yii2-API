<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class CreatePropertyValueAction
 * @package common\extendedStdComponents
 */
class CreateElementPropertyRelationAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @return \yii\db\ActiveRecord
     * @throws HttpException
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $attributes = array_merge(Yii::$app->getRequest()->getQueryParams(), Yii::$app->getRequest()->getBodyParams());
        $this->modelClass = 'commonprj\components\core\entities\common\property\Property';
        /** @var Property $model */
        $model = new $this->modelClass();
        $result = $model->createElementPropertyRelation($attributes);

        if ($result === true) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$result->hasErrors()) {
            throw new HttpException(500, 'Failed to create the object for unknown reason.');
        } else {
            return $result;
        }
    }
}