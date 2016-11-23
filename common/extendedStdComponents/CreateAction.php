<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 04.07.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CreateAction extends PlanironAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @return BaseCrudModel
     * @throws BadRequestHttpException if variantTypeId is not given in HTTP Query Param
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        if ($this->modelClass === 'commonprj\components\core\entities\common\variant\Variant') {
            $variantTypeId = Yii::$app->getRequest()->getBodyParam('variantTypeId');

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
        $model = new $this->modelClass();
        if (strpos($this->modelClass, 'Record')) {
            $model->setAttributes(BaseDBRepository::arrayKeysCamelCase2Underscore(Yii::$app->getRequest()->getBodyParams()), false);
        } else {
            $model->setAttributes(Yii::$app->getRequest()->getBodyParams(), false);
        }

        if (!$model->save() && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
