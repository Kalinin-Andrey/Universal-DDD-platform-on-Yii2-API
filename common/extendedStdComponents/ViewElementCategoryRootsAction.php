<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementCategoryRoots
 * @package common\extendedStdComponents
 */
class ViewElementCategoryRootsAction extends PlanironAction
{
    /**
     * Displays a model.
     * @return \yii\db\ActiveRecord[]
     */
    public function run()
    {
        /** @var ElementCategory $model */
        $model = new $this->modelClass;

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model->getRoots();
    }
}