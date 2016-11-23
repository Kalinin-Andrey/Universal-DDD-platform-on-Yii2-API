<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\Property2elementClassRecord;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class UpdatePropertyClassAction
 * @package common\extendedStdComponents
 */
class UpdateProperty2elementClassAction extends PlanironAction
{

    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Updates an existing model.
     * @param $id
     * @param $elementClassId
     * @return Property2elementClassRecord
     * @throws HttpException
     */
    public function run($id, $elementClassId)
    {
        $this->modelClass = 'commonprj\components\core\models\Property2elementClassRecord';
        /** @var Property2elementClassRecord $model */
        $model = $this->findModel("{$elementClassId},{$id}");
        $attributes = Yii::$app->getRequest()->getBodyParams();
        $model->setAttributes(BaseDBRepository::arrayKeysCamelCase2Underscore($attributes));
        if ($model->save() === false && !$model->hasErrors()) {
            throw new HttpException(500, 'Failed to update the object for unknown reason.');
        }

        return $model;
    }
}