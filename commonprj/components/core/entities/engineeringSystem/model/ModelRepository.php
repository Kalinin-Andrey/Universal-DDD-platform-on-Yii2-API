<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.08.2016
 */
namespace commonprj\components\core\entities\engineeringSystem\model;

use commonprj\extendedStdComponents\BaseCrudModel;
use yii\web\HttpException;

/**
 * Class Model
 * @package commonprj\components\core\entities\engineeringSystem\model
 */
interface ModelRepository
{
    /**
     * Найти модель по id или другому условию.
     * @param mixed $condition - id (обычный или composite) записи или ассоциативный массив условий для WHERE.
     * @return BaseCrudModel - Возвращает класс доменного слоя, наследуемый от BaseCrudModel.
     */
    public function findOne($condition);

    /**
     * Удаляет запись текущего инстанса вместе со всеми зависимостями.
     */
    function delete();

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function update();

    /**
     * @param bool $condition
     * @return array|BaseCrudModel
     */
    public function find($condition = false);

    /**
     * @return BaseCrudModel
     */
    public function save();
}
