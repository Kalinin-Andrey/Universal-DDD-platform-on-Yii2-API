<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\property\Property;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewPropertyUnitAction
 * @package common\extendedStdComponents
 */
class ViewPropertyUnitAction extends PlanironAction
{
    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord
     */
    public function run($id)
    {
        /** @var Property $model */
        $model = $this->findModel($id);

        return $model->getPropertyUnit();
    }
}