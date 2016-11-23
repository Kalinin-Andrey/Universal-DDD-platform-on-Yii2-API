<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 07.07.2016
 */

namespace common\extendedStdComponents;

use Codeception\Lib\Interfaces\ActiveRecord;
use commonprj\components\core\entities\common\element\Element;
use commonprj\components\core\models\RelationRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class CreateChildAction
 * @package common\extendedStdComponents
 */
class CreateElementChildAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @param $id
     * @param $childElementId
     * @return \yii\db\ActiveRecord - The model newly created
     * @throws HttpException
     */
    public function run($id, $childElementId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $relationGroupId = Yii::$app->getRequest()->getBodyParam('relationGroupId');

        if (is_null($relationGroupId)) {
            /** @var BaseCrudModel $element */
            $element = new $this->modelClass();
            $element->addError('relationGroupId', 'relationGroupId can\'t be empty.');
        }

        $attributes['parent_element_id'] = $id;
        $attributes['relation_group_id'] = $relationGroupId;
        $attributes['child_element_id'] = $childElementId;
        /** @var Element $element */
        $element = new $this->modelClass();

        if (!$element->findOne(['condition' => ['id' => $id]])) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        if (!$element->findOne(['condition' => ['id' => $childElementId]])) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        /** @var RelationRecord $model */
        $model = new RelationRecord();

        if ($id == $childElementId) {
            $model->addError('id, childElementId', 'id and childElementId can not be the same!');

            return $model;
        }

        $model->setAttributes(BaseDBRepository::arrayKeysCamelCase2Underscore($attributes));

        if (!$model->save() && !$model->hasErrors()) {
            throw new HttpException(500, basename(__FILE__, '.php') . __LINE__);
        }

        return $model;
    }
}