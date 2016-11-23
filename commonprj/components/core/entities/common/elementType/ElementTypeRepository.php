<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */
namespace commonprj\components\core\entities\common\elementType;

use commonprj\components\core\entities\common\elementCategory\ElementCategory;
use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\extendedStdComponents\BaseCrudModel;
use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 * Class ElementTypeRepository
 * @package commonprj\components\core\entities\common\elementType
 */
interface ElementTypeRepository
{
    /**
     * Общий для ядра метод для поиска записи по primary key. Так же возможен поиск по дополнительным условиям.
     * @param int|string|array $condition - Условия посика. Должен содерать primary key, остальные уловия опциональны.
     * Если true - вернет элемент только если он принадлежит обратившемуся по api классу.
     * @return BaseCrudModel - Возвращает объект доменного слоя.
     * @throws HttpException
     */
    public function findOne($condition);

    /**
     * @param null $condition
     * @return array|\yii\db\ActiveRecord[]
     * @throws InvalidConfigException
     */
    public function find($condition = null);

    /**
     * @param int $id
     * @return ElementCategory[]
     */
    public function getElementCategoriesById(int $id);

    /**
     * @param int $id
     * @return ElementClass
     */
    public function getElementClassById(int $id);

    /**
     * @param $id
     * @return array|BaseCrudModel
     * @throws HttpException
     */
    public function getVariantById($id);
}