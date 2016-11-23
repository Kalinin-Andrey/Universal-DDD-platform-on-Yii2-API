<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 14.09.2016
 */

namespace common\extendedStdComponents;

ini_set('xdebug.var_display_max_depth', 50);

use commonprj\components\core\entities\common\element\ElementDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

/**
 * Class CreateElementsBySchemaIdAction
 * @package common\extendedStdComponents
 */
class CreateElementsBySchemaIdAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @param $id
     * @return bool|\commonprj\components\core\models\ElementRecord|\yii\db\ActiveRecord
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $attributes = array_merge(Yii::$app->getRequest()->getQueryParams(), Yii::$app->getRequest()->getBodyParams());
        $this->modelClass = 'commonprj\components\core\entities\common\element\ElementDBRepository';
        /** @var ElementDBRepository $model */
        $model = new $this->modelClass();
        $model->deleteElementsBySchemaId($id);
        $result = $model->createElementsBySchemaId($attributes);
        if ($result === true) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$result->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        } else {
            return $result;
        }
    }
}