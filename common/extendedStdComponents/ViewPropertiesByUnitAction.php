<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 12.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\models\PropertyUnitRecord;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewPropertiesByUnitAction
 * @package common\extendedStdComponents
 */
class ViewPropertiesByUnitAction extends PlanironAction
{
    /**
     * Возвращает все свойства по данному $id
     * @param $id - id единицы измерения свойства
     * @return \yii\db\ActiveRecord[]
     */
    public function run($id)
    {
        /** @var PropertyUnitRecord $model */
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $properties = $model->getProperties()->all();
        $result = [];

        foreach ($properties as $property) {
            $result[$property['id']] = $property;
        }

        return $result;
    }
}