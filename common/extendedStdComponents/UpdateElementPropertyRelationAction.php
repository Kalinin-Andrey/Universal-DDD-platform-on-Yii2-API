<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 07.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\PropertyRelationRecord;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class UpdateElementPropertyRelationAction
 * @package common\extendedStdComponents
 */
class UpdateElementPropertyRelationAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @param $id
     * @param $propertyId
     * @return ActiveRecord
     * @throws HttpException
     */
    public function run($id, $propertyId)
    {
        $attributes = array_merge(Yii::$app->getRequest()->getQueryParams(), Yii::$app->getRequest()->getBodyParams());
        /** @var PropertyRelationRecord $modelRecord */
        $modelRecord = 'commonprj\components\core\models\PropertyRelationRecord';

        /** @var ActiveRecord $model */
        $model = $modelRecord::find()->where([
            'property_id'    => $propertyId,
            'owner_id'       => $id,
            'owner_table_id' => BaseDBRepository::OWNER_TABLE_ID['element'],
        ])->one();

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model) {
            $model->setAttributes(BaseDBRepository::arrayKeysCamelCase2Underscore($attributes));
            if (!$model->save() && !$model->hasErrors()) {
                throw new HttpException(500, basename(__FILE__, '.php') . __LINE__);
            }

            Yii::$app->getResponse()->setStatusCode(204);
        } else {
            Yii::$app->getResponse()->setStatusCode(404);
        }
    }
}