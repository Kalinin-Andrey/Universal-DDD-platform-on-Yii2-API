<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 16.08.2016
 */

namespace commonprj\extendedStdComponents;

use Yii;
use yii\data\BaseDataProvider;
use yii\web\HttpException;

/**
 * Class ServiceDataProvider
 * @package commonprj\extendedStdComponents
 */
class ServiceDataProvider extends BaseDataProvider
{
    /**
     * @var string
     */
    public $subsystemSysname;

    /**
     * @var string
     */
    public $className;

    /**
     * @var integer
     */
    public $entityId = 0;

    public $advancedParams = [];

    /**
     * @var string|callable the column that is used as the key of the data models.
     * This can be either a column name, or a callable that returns the key value of a given data model.
     * If this is not set, the index of the [[models]] array will be used.
     * @see getKeys()
     */
    public $key;

    /**
     * Prepares the data models that will be made available in the current page.
     * @return array the available data models
     * @throws HttpException
     */
    protected function prepareModels()
    {
        $entityId = $this->entityId ? '/' . $this->entityId : '';

        $requestUri = $this->className . $entityId;
        $modelJson = Yii::$app->coreService->get($requestUri, $this->advancedParams);
        $model = json_decode($modelJson, true);

        if (isset($model['status'])) {
            Yii::trace($model['status'] . $model['message'], __METHOD__);
            throw new HttpException(404, 'This page is not found!');
        }

        return $model;
    }

    /**
     * Prepares the keys associated with the currently available data models.
     * @param array $models the available data models
     * @return array the keys
     */
    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        } else {
            return array_keys($models);
        }
    }

    /**
     * Returns a value indicating the total number of data models in this data provider.
     * @return integer total number of data models in this data provider.
     */
    protected function prepareTotalCount()
    {

    }

    /**
     * Returns the total number of data models.
     * Otherwise, it will call [[prepareTotalCount()]] to get the count.
     * @return integer total number of possible data models.
     */
    public function getTotalCount()
    {
        return $this->prepareTotalCount();
    }
}
