<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\components\core\entities\common\elementType\ElementType;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementTypeCategoryAction
 * @package common\extendedStdComponents
 */
class ViewElementTypeCategoryAction extends PlanironAction
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return ElementCategory[]
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        /** @var ElementType $model */
        $model = new $this->modelClass();
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model->getCategory($id);
    }
}