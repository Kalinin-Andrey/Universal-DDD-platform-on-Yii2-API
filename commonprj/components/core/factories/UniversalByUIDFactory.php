<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 10.08.2016
 */

namespace commonprj\components\core\factories;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;

/**
 * Class UniversalByUIDFactory
 * @package commonprj\components\core\factories
 */
class UniversalByUIDFactory
{
    /**
     * @param string $uid
     * @param array $requestParams
     * @return BaseCrudModel
     * Метод создает объект доменной модели по UID
     * @throws HttpException Метод создает объект доменной модели по UID
     */
    public function create(string $uid, array $requestParams = [])
    {
        $factoryString = '';
        $uid2params = Yii::$app->route->UID2params($uid, true);
        //Объект можно получить если передан id
        if ($uid2params['id']) {
            $factoryString = Yii::$app->templateEngineHelper->getRepositoryName($uid2params['className']);
        }

        if (!$factoryString) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);

        } else {
            $requestParams['id'] = $uid2params['id'];
            $model = Yii::$app->{$factoryString}->findOne($requestParams);

            return $model;
        }
    }


    /**
     * @param array $requestParams
     * @return object ServiceDataProvider
     * @throws HttpException
     */
    public function createServiceDataProvider(array $requestParams)
    {
        $defaultConfig = [
            'class'            => 'commonprj\extendedStdComponents\ServiceDataProvider',
            'id'               => 'ServiceDataProvider',
            'subsystemSysname' => '',
            'className'        => '',
            'advancedParams'   => [
                'is_active' => 1,
            ],
        ];

        $uid = $requestParams['UID'];

        try {
            $uid2params = Yii::$app->route->UID2params($uid, true);
        } catch (InvalidParamException $e) {
            Yii::trace($e->getMessage(), __METHOD__);
            throw new HttpException(404, 'This page is not found!');
        }

        $config = [
            'subsystemSysname' => $uid2params['subsystemSysname'],
            'className'        => $uid2params['className'],
        ];

        if ($uid2params['id']) {
            $config['entityId'] = $uid2params['id'];
        }

        $advancedParams = [];
        $validateData = ['page' => null, 'per-page' => null, 'sort' => null];
        $checkAdvancedParams = false;

        foreach ($validateData as $key => $val) {

            if (isset($requestParams[$key])) {
                $advancedParams[$key] = $val;
                $validateData[$key] = $val;
                $checkAdvancedParams = true;
            }
        }

        if ($checkAdvancedParams) {
            $model = DynamicModel::validateData($validateData, [
                [['page', 'per-page'], 'integer', 'min' => 0],
                ['sort', 'match', 'pattern' => '~^([-]*[a-zA-z0-9_]+[,]*)*$~'],
            ]);

            if ($model->hasErrors()) {
                throw new HttpException(404, 'This page is not found!');
            }
            $defaultConfig['advancedParams'] = array_merge($defaultConfig['advancedParams'], $advancedParams);
        }

        $dataProvider = Yii::createObject(array_merge($defaultConfig, $config));

        return $dataProvider;
    }

    /**
     * @param array $models
     * @param int $pageSize
     * @param array $sortAttributes
     * @return ArrayDataProvider
     * Метод нужен для пагинации и сортировки локальных данных в подшаблоне, НЕ рекомендуется для получения глобального списка
     * элементов. Пагинация в данном контексте - просто ограничение кол-ва выводимых данных.
     */
    public function createArrayDataProvider(array $models, int $pageSize, array $sortAttributes)
    {
        $arModels = ArrayHelper::toArray($models);

        $provider = new ArrayDataProvider([
            'allModels'  => $arModels,
            'sort'       => [
                'attributes' => $sortAttributes,
            ],
            'pagination' => ['pageSize' => $pageSize,],
        ]);

        return $provider;
    }
}