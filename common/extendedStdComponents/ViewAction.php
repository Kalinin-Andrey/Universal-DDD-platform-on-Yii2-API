<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 29.06.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;

/**
 * ViewAction implements the API endpoint for returning the detailed information about a model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ViewAction extends PlanironAction
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return Variant|BaseCrudModel
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
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
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model;
    }
}
