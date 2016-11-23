<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * UpdateAction implements the API endpoint for updating a model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UpdateAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @param int $id
     * @return BaseCrudModel
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function run(int $id)
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

        /** @var BaseCrudModel $model */
        $model = $this->findModel($id);


        $model->setAttributes(Yii::$app->getRequest()->getBodyParams(), false);

        if ($model->update() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }
}
