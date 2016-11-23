<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */
namespace commonprj\components\core\entities\common\property;

use commonprj\extendedStdComponents\BaseCrudModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class PropertyRepository
 * @package commonprj\components\core\entities\common\property
 */
interface PropertyRepository
{
    /**
     * @param null $condition
     * @return array
     * @throws InvalidConfigException
     */
    public function find($condition = null);

    /**
     * @param int $id
     * @return array
     */
    public function getPropertyClassesById(int $id);

    /**
     * @param int $propertyId
     * @param null $multiplicityId
     * @return array
     */
    public function getValues(int $propertyId, $multiplicityId = null);

    /**
     * @param $propertyUnitId
     * @return array|ActiveRecord
     */
    public function getPropertyUnitById($propertyUnitId);

    /**
     * Общий для ядра метод для поиска записи по primary key. Так же возможен поиск по дополнительным условиям.
     * @param int|string|array $condition - Условия посика. Должен содерать primary key, остальные уловия опциональны.
     * Если true - вернет элемент только если он принадлежит обратившемуся по api классу.
     * @return BaseCrudModel - Возвращает объект доменного слоя.
     * @throws HttpException
     */
    public function findOne($condition);
}