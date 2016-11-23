<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 21.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * Class ViewVariantSchemaElementAction
 * @package common\extendedStdComponents
 */
class ViewVariantSchemaElementAction extends PlanironAction
{
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        if ($this->modelClass === 'commonprj\components\core\entities\common\variant\Variant') {
            $variantTypeId = Yii::$app->getRequest()->getQueryParam('variantTypeId');

            switch ($variantTypeId) {
                case 1:
                    $this->modelClass = 'commonprj\components\core\entities\common\propertyVariant\PropertyVariant';
                    break;
                case 2:
                    $this->modelClass = 'commonprj\components\core\entities\common\relationVariant\RelationVariant';
                    break;
                default:
                    /** @var Variant $model */
                    $model = new $this->modelClass();
                    $model->addError('variantTypeId', 'Available only for Variant type id = 1 or 2');

                    return $model;
            }
        }

        $model = $this->findModel($id);

        return $model->getSchemaElement();
    }
}