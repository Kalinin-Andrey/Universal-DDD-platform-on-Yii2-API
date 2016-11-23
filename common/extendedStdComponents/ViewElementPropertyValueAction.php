<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 06.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementPropertyValueAction
 * @package common\extendedStdComponents
 */
class ViewElementPropertyValueAction extends PlanironAction
{
    /**
     * @param $id
     * @param $propertyId
     * @return \commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValue
     */
    public function run($id, $propertyId)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $this->modelClass = 'commonprj\components\core\entities\common\element\Element';
        /** @var Element $model */
        $model = new $this->modelClass();

        return $model->getPropertyValue($id, $propertyId);
    }
}