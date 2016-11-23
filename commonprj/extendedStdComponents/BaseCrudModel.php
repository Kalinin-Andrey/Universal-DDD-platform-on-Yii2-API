<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 13.07.2016
 */

namespace commonprj\extendedStdComponents;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class BaseCrudModel
 * Родительский базовый класс для доменного слоя.
 * @package commonprj\components\core\entities
 */
abstract class BaseCrudModel extends Model
{
    public $repository;
    public $entity;

    /**
     * Удаляет строки из таблиц.
     * Ожидается что перед вызовом метода начата транзакция.
     * В случае не удачного удаления будет rollBack транзакции.
     * @param ActiveRecord[] $rows - Массив ActiveRecord строк для удаления.
     * @throws HttpException - При неудаче возвращает ServerErrorHttpException и делает rollBack.
     */
    public static function deleteRows(array $rows)
    {
        /** @var ActiveRecord $row */
        foreach ($rows as $row) {

            if (!$row->delete()) {
                // todo после прикрутки системы логирования не возвращать getErrors, а писать его в лог.
                throw new ServerErrorHttpException(basename(__FILE__, '.php') . __LINE__ . ' ' . print_r($row->getErrors(), true));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['repository']);

        return $fields;
    }

    /**
     * Возвращает имя класса без неймспейса.
     * @return string
     */
    public function getThisShortClassName()
    {
        preg_match('/\w+$/', get_called_class(), $match);

        return $match[0];
    }

    /**
     * Найти модель по id или другому условию.
     * @param int|string|array $condition - id (обычный или composite) записи или ассоциативный массив условий для WHERE.
     * @return BaseCrudModel - Возвращает класс доменного слоя, наследуемый от BaseCrudModel.
     * @throws HttpException
     */
    abstract public function findOne($condition);

    /**
     * Удаляет запись текущего инстанса вместе со всеми зависимостями.
     */
    abstract function delete();

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    abstract public function update();

    /**
     * @param array|null $condition
     * @return BaseCrudModel
     */
    abstract public function find($condition = null);

    /**
     * @return BaseCrudModel
     */
    abstract public function save();
}
