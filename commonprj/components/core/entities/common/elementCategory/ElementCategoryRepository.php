<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */
namespace commonprj\components\core\entities\common\elementCategory;

use commonprj\extendedStdComponents\BaseCrudModel;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class ElementCategoryRepository
 * @package commonprj\components\core\entities\common\elementCategory
 */
interface ElementCategoryRepository
{
    /**
     * @param mixed $condition
     * @return array
     */
    public function find($condition = null);

    /**
     * @param $id
     * @return ElementCategory[]
     */
    public function getChildrenById($id);

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws HttpException
     */
    public function getParentByChildId($id);

    /**
     * @param int $id
     * @return bool|string
     */
    public function getIsParent(int $id);

    /**
     * @param int $id
     * @return ElementCategory
     */
    public function getRootById(int $id);

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRoots();

    /**
     * @param $rootElementCategoryId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getHierarchyByRootId($rootElementCategoryId);

    /**
     * Общий для ядра метод для поиска записи по primary key. Так же возможен поиск по дополнительным условиям.
     * @param int|string|array $condition - Условия посика. Должен содерать primary key, остальные уловия опциональны.
     * Если true - вернет элемент только если он принадлежит обратившемуся по api классу.
     * @return BaseCrudModel - Возвращает объект доменного слоя.
     * @throws HttpException
     */
    public function findOne($condition);
}