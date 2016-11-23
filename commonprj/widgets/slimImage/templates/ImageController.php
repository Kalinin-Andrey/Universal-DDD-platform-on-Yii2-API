<?php
/**
 * Created by Bogachev.Petr
 * Date: 07.06.2016
 *
 * ПРИМЕР СОЗДАНИЯ КОНТРОЛЁРА ДЛЯ ВИДЖЕТА
 *
 * !!! Поменять namespace
 */

namespace a\b;

use yii\web\Response;
use yii\web\Controller;
use Yii;

class ImageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'upload') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionUpload()
    {
        return Yii::$app->protectedBackImage->post('image/base', Yii::$app->request->post());
    }
}