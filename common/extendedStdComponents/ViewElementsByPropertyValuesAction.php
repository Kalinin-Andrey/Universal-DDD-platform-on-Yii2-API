<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 19.10.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * Class ViewElementsByPropertyValues
 * @package common\extendedStdComponents
 */
class ViewElementsByPropertyValuesAction extends PlanironAction
{
    /**
     */
    public function run()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        $model = new $this->modelClass();
        /** @var Element $model */
        return $model->getElementsByPropertyValues($params);
    }
}