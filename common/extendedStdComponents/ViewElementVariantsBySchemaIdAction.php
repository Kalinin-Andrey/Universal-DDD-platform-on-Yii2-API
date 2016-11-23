<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * Class ViewElementVariantsBySchemaIdAction
 * @package common\extendedStdComponents
 */
class ViewElementVariantsBySchemaIdAction extends PlanironAction
{
    /**
     * @param $id
     * @return \commonprj\components\core\entities\common\variant\Variant[]
     * @internal param $variantTypeId
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $variantTypeId = Yii::$app->getRequest()->getQueryParam('variantTypeId');
        /** @var Element $model */
        $model = $this->findModel($id);

        return $model->getVariants($variantTypeId);
    }
}