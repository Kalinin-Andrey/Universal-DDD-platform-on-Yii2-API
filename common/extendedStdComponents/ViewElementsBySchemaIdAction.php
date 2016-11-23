<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 13.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use yii\db\ActiveRecord;

/**
 * Class ViewElementsBySchemaIdAction
 * @package common\extendedStdComponents
 */
class ViewElementsBySchemaIdAction extends PlanironAction
{
    /**
     * @param $id
     * @return ActiveRecord
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var Element $model */
        $model = new $this->modelClass();

        return $model->getElementsBySchemaId($id);
    }
}