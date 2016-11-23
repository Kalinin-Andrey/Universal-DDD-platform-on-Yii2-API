<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.10.2016
 */

namespace commonprj\components\core\entities\common\relation;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class Relation
 * @package commonprj\components\core\entities\common\relation
 */
class Relation extends BaseCrudModel
{
    public $id;
    public $relationGroupId;
    public $parentElementId;
    public $childElementId;
    public $value;
    public $propertyUnitId;
    public $order;
    public $parentElement;
    public $relationGroup;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->relationRepository;
    }

    /**
     * Найти модель по id или другому условию.
     * @param int|string|array $condition - id (обычный или composite) записи или ассоциативный массив условий для WHERE.
     * @return BaseCrudModel - Возвращает класс доменного слоя, наследуемый от BaseCrudModel.
     * @throws HttpException
     */
    public function findOne($condition)
    {
        return $this->repository->findOne($condition);
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
     * @param array|null $condition
     * @return BaseCrudModel
     */
    public function find($condition = null)
    {
        return $this->repository->find($condition);
    }

    /**
     * @return BaseCrudModel
     */
    public function save()
    {
        // TODO: Implement save() method.
    }

    public function getChildren()
    {
        return $this->repository->getChildren($this->id);
    }
}