<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 11.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class ViewPropertiesAction
 * @package common\extendedStdComponents
 */
class ViewElementPropertiesAction extends PlanironAction
{
    /**
     * @param $id
     * @return \commonprj\components\core\entities\common\property\Property[]|mixed|ActiveDataProvider
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $attributes = Yii::$app->getRequest()->getQueryParams();

        /** @var Element $model */
        $model = new $this->modelClass();
        $condition['condition'] = ['id' => $id];
        $model->findOne($condition);

        return $model->getProperties($attributes);
    }
}