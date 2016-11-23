<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementCategoryHierarchyAction
 * @package common\extendedStdComponents
 */
class ViewElementCategoryHierarchyAction extends PlanironAction
{
    /**
     * Displays a model.
     * @param int $rootElementCategoryId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function run($rootElementCategoryId)
    {
        /** @var ElementCategory $model */
        $model = new $this->modelClass();

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model->getHierarchyByRootId($rootElementCategoryId);
    }
}