<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementCategoryParentAction
 * @package common\extendedStdComponents
 */
class ViewElementCategoryParentAction extends PlanironAction
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return array|\yii\db\ActiveRecord
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        /** @var ElementCategory $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model->getParent();
    }
}