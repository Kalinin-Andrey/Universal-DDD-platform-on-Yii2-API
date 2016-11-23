<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace commonprj\extendedStdComponents;

use Yii;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

/**
 * Class PlanironAction
 * @package commonprj\extendedStdComponents
 */
class PlanironAction extends Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->findModel = [$this, 'findModel'];
        Parent::init();
    }

    /**
     * Returns the data model based on the primary key given.
     * If the data model is not found, a 404 HTTP exception will be raised.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return BaseCrudModel
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        $quertyParams = Yii::$app->getRequest()->getQueryParams();

        if (!empty($quertyParams['with'])) {
            $condition['with'] = $quertyParams['with'];
        }

        $condition['condition'] = $quertyParams;
        $condition['modelClass'] = $this->modelClass;

        if (!strpos($this->modelClass, 'Record')) {
            $model = new $this->modelClass();
            $result = $model->repository->findModel($condition);

            if (!is_null($result)) {
                return $result;
            }

            throw new NotFoundHttpException(basename(__FILE__, '.php') . __LINE__);
        }

        $result = ($this->modelClass)::findOne($id);

        if (!is_null($result)) {
            return $result;
        }

        throw new NotFoundHttpException(basename(__FILE__, '.php') . __LINE__);
    }
}
