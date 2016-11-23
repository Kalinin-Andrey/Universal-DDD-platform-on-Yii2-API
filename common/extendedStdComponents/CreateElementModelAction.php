<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

/**
 * Class createElementModelAction
 * @package common\extendedStdComponents
 */
class CreateElementModelAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $attributes = array_merge(Yii::$app->getRequest()->getQueryParams(), Yii::$app->getRequest()->getBodyParams());

        /** @var Element $model */
        $model = $this->findModel($attributes['id']);
        $result = $model->createModel($attributes);

        if ($result) {
            return $result;
        } else {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }
}