<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\extendedStdComponents\PlanironAction;
use yii\data\ActiveDataProvider;

/**
 * Class ViewElementCategoryChildrenAction
 * @package common\extendedStdComponents
 */
class ViewElementCategoryChildrenAction extends PlanironAction
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return ActiveDataProvider|\yii\db\ActiveQuery
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        /** @var ElementCategory $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model->getChildren();
    }
}