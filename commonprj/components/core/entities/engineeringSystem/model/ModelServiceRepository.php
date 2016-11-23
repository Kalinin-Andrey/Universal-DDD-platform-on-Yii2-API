<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 06.09.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\model;


use commonprj\extendedStdComponents\BaseCrudModel;
use yii\web\HttpException;

/**
 * Class ModelServiceRepository
 * @package commonprj\components\core\entities\engineeringSystem\model
 */
class ModelServiceRepository implements ModelRepository
{

    /**
     * Найти модель по id или другому условию.
     * @param mixed $condition - id (обычный или composite) записи или ассоциативный массив условий для WHERE.
     * @return BaseCrudModel - Возвращает класс доменного слоя, наследуемый от BaseCrudModel.
     */
    public function findOne($condition)
    {
        // TODO: Implement findOne() method.
    }

    /**
     * Удаляет запись текущего инстанса вместе со всеми зависимостями.
     */
    function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function update()
    {
        // TODO: Implement update() method.
    }

    /**
     * @param bool $condition
     * @return array|BaseCrudModel
     */
    public function find($condition = false)
    {
        // TODO: Implement find() method.
    }

    /**
     * @return BaseCrudModel
     */
    public function save()
    {
        // TODO: Implement save() method.
    }
}