<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 06.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * Class ViewPropertyValuesAction
 * @package common\extendedStdComponents
 */
class ViewPropertyValuesAction extends PlanironAction
{
    /**
     * @param $id
     * @return array
     */
    public function run($id)
    {
        /** @var Property $model */
        $model = $this->findModel($id);
        $multiplicityId = Yii::$app->getRequest()->getQueryParam('multiplicityId');

        return $model->getValues($multiplicityId);
    }
}