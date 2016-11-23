<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 22.08.2016
 */

namespace commonprj\extendedStdComponents;


use Yii;
use yii\base\InvalidParamException;
use yii\web\HttpException;

/**
 * Class BaseServiceRepository
 * @package commonprj\extendedStdComponents
 * Класс - родитель, для всех ServiceRepository
 */
class BaseServiceRepository
{
    /**
     * @var string
     * API URI, обязательно нужно определять в любом методе дочернего класса
     */
    protected $requestUri = '';
    /**
     * @var array
     * Параметры добавляемые к API calls, если нужно передать в API дополнительные параметры,
     * нужно переопределить данный параметр перед вызовом любого метода данного класса, кроме
     * getModelAndApiCallData()
     *
     */
    protected $requestParams = [];

    const RELATION_PROPERTIES_ONE_MODEL = [
        'elementType',
        'elementCategory',
        'elementClass',
        'element',
        'parent',
        'root',
        'hierarchy',
        'schemaElement',
        'variant',
        'property',
        'relationGroup',
        'child',
        'relationClass',
        'propertyValue',
        'relatedElement',
    ];

    const RELATION_PROPERTIES_ARRAY_MODELS = [
        'elementClasses',
        'elementTypes',
        'models',
        'properties',
        'children',
        'inclusions',
        'relationClasses',
        'relationGroups',
        'variants',
        'elements',
        'relations',
        'parents',
    ];

    const CORE_ENTITIES_NAMESPACE = 'commonprj\components\core\entities';

    /**
     * @return string
     * Метод получает строку "контекст/класс"
     */
    protected function getModelAndApiCallData()
    {
        $className = get_called_class();
        preg_match('/(.*(\w+)\\\\\w+\\\\(\w+))ServiceRepository$/U', $className, $match);
        $apiCallString = $match[2] . '/' . lcfirst($match[3]);

        return $apiCallString;
    }

    /**
     * @param bool $throwException
     * @return array
     * @throws HttpException
     * Метод совершает запрос к АПИ, получает JSON, преобразует его в массив и возвращает, в случае ошибки или вернет
     * пустой массив, или запишет ошибку в лог и выбросит 404 ошибку, за это отвечает параметр $throwException
     */
    protected function getAndCheckApiData(bool $throwException = false)
    {
        if (empty($this->requestUri) || !is_string($this->requestUri)) {
            throw new InvalidParamException('RequestUri cannot be blank!');
        }

        $json = Yii::$app->coreService->get($this->requestUri, $this->requestParams);
        //Обнуляем свойства для избежания случайного повторного использования
        $this->requestUri = '';
        $this->requestParams = [];

        $arModel = json_decode($json, true);

        if (Yii::$app->coreService->status() !== 200 || empty($arModel)) {

            if ($throwException) {
                Yii::trace($arModel['status'] ?? '' . ' ' . $arModel['message'] ?? '', __METHOD__);

                if (YII_ENV == 'dev') {
                    throw new HttpException(404, ($arModel['status'] ?? '') . ' ' . ($arModel['message'] ?? ''));
                } else {
                    throw new HttpException(404, 'This page is not found!');
                }
            } else {
                $arModel = [];
            }
        }

        return $arModel;
    }

    /**
     * @param array $arModel
     * @param string $modelClassName
     * @return BaseCrudModel Метод мапит одну доменную модель
     * Метод мапит одну доменную модель
     */
    protected function getOneModel(array $arModel, string $modelClassName = '')
    {
        if ($arModel) {

            if (!empty($arModel['entity']) && is_string($arModel['entity'])) {
                /** @var BaseCrudModel $model */
                $model = new $arModel['entity']();
            } elseif ($modelClassName) {
                $model = new $modelClassName();
            } else{
                throw new InvalidParamException('Can\'t map model, class name is empty! ' . __FILE__ . __LINE__);
            }
            $arModel = $this->recursiveModelMapping($arModel);
            $model->setAttributes($arModel, false);
            $arModel = $model;
        }

        return $arModel;
    }

    /**
     * @param array $arModel
     * @param string $modelClassName
     * @return array|BaseCrudModel[] Метод мапит массив доменных моделей
     * Метод мапит массив доменных моделей
     */
    protected function getArrayOfModels(array $arModel, string $modelClassName = ''):array
    {
        if ($arModel) {

            foreach ($arModel as $key => $item) {

                if (!empty($item['entity']) && is_string($item['entity'])) {
                    /** @var BaseCrudModel $model */
                    $model = new $item['entity']();

                } elseif ($modelClassName) {
                    $model = new $modelClassName();
                } else{
                    throw new InvalidParamException('Can\'t map model, class name is empty! ' . __FILE__ . __LINE__);
                }
                $item = $this->recursiveModelMapping($item);
                $model->setAttributes($item, false);
                $arModel[(int)$key] = $model;
            }
        }

        return $arModel;
    }

    /**
     * @param array $arModel
     * @return array
     * Метод мапит многомерные массивы
     */
    private function recursiveModelMapping(array $arModel)
    {
        foreach ($arModel as $key => $item) {

            if (!empty($item) && is_array($item)) {

                if(in_array($key, self::RELATION_PROPERTIES_ONE_MODEL)) {
                    $arModel[$key] = $this->getOneModel($item);
                } elseif (in_array($key, self::RELATION_PROPERTIES_ARRAY_MODELS)) {
                    $arModel[$key] = $this->getArrayOfModels($item);
                }
            }
        }

        return $arModel;
    }

    /**
     * @param array $arModel
     * @param string $jsonBoolKey
     * @return bool Метод возвращает boolean результат
     * Метод возвращает boolean результат
     * @internal param string $requestUri
     */
    protected function getBooleanResult(array $arModel, string $jsonBoolKey):bool
    {
        $result = false;

        if ($arModel) {
            $result = $arModel[$jsonBoolKey];
        }

        return $result;
    }

    /**
     * @param string $elementClassName
     * @return string Метод возвращает полное имя класса доменной модели по ее ElementClassName
     * Метод возвращает полное имя класса доменной модели по ее имени и имени контекста
     */
    protected function getFullModelClassName(string $elementClassName)
    {
        if (!$elementClassName){
            throw new InvalidParamException('ElementClassName can\'t be blank');
        }

        $parts = explode('\\', $elementClassName);

        return self::CORE_ENTITIES_NAMESPACE . '\\' . $parts[0] . '\\' . lcfirst($parts[1]) . '\\' . $parts[1];
    }
}