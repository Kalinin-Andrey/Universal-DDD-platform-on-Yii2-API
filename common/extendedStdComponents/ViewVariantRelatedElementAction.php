<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.09.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\entities\common\relationVariant\RelationVariant;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Class ViewVariantRelatedElementAction
 * @package common\extendedStdComponents
 */
class ViewVariantRelatedElementAction extends PlanironAction
{
    /**
     * @param $id
     * @return RelationVariant
     * @throws BadRequestHttpException
     */
    public function run($id)
    {
        if ($this->modelClass === 'commonprj\components\core\entities\common\variant\Variant') {
            $this->modelClass = 'commonprj\components\core\entities\common\relationVariant\RelationVariant';
            $variantTypeId = Yii::$app->getRequest()->getQueryParam('variantTypeId');

            if (is_null($variantTypeId)) {
                throw new BadRequestHttpException('variantTypeId can not be empty! ' . basename(__FILE__, '.php') . __LINE__);
            }

            if ($variantTypeId != 2) {
                /** @var RelationVariant $model */
                $model = new $this->modelClass();
                $model->addError('variantTypeId', 'Available only for Variant type id = 2');

                return $model;
            }
        }

        /** @var RelationVariant $model */
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model->getRelatedElement();
    }
}