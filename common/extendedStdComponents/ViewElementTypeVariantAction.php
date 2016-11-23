<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\elementType\ElementType;
use commonprj\extendedStdComponents\PlanironAction;

/**
 * Class ViewElementTypeVariantsAction
 * @package common\extendedStdComponents
 */
class ViewElementTypeVariantAction extends PlanironAction
{
    /**
     * @param $id
     * @return mixed
     * @internal param $variantTypeId
     */
    public function run($id )
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ElementType $model */
        $model = $this->findModel($id);

        return $model->getVariant();
    }
}