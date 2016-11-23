<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 26.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class CreateRelationClassClassAction
 * @package common\extendedStdComponents
 */
class CreateRelationClass2ElementClassAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Deletes a model.
     * @param $id
     * @param $elementClassId
     * @return ActiveRecord
     * @throws HttpException
     */
    public function run($id, $elementClassId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $modelClass = 'commonprj\components\core\models\ElementClass2relationClassRecord';
        /** @var ActiveRecord $model */
        $model = new $modelClass();
        $attributes['relation_class_id'] = $id;
        $attributes['element_class_id'] = $elementClassId;
        $model->setAttributes(BaseDBRepository::arrayKeysCamelCase2Underscore(Yii::$app->getRequest()->getBodyParams()));
        $model->setAttributes($attributes, false);

        if (!$model->save() && !$model->hasErrors()) {
            throw new HttpException(500, 'Failed to create the object for unknown reason.');
        }

        return $model;
    }
}