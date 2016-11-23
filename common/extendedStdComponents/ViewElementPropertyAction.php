<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 26.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class ViewPropertyAction
 * @package common\extendedStdComponents
 */
class ViewElementPropertyAction extends PlanironAction
{
    /**
     * @param $id
     * @param $propertyId
     * @return \commonprj\components\core\entities\common\property\Property[]|mixed|ActiveDataProvider
     */
    public function run($id, $propertyId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = $this->findModel($id);
        $condition = Yii::$app->getRequest()->getQueryParams();

        return $model->getProperty($propertyId, $condition);
    }
}