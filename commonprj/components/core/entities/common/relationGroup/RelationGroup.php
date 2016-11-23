<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.08.2016
 */

namespace commonprj\components\core\entities\common\relationGroup;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class RelationGroup
 * @package commonprj\components\core\entities\common\relationGroup
 */
class RelationGroup extends BaseCrudModel
{
    public $id;
    public $relationClassId;
    public $rootId;
    public $name;
    public $relationClass;
    public $relations;
    public $entity;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->relationGroupRepository;
    }

    /**
     * Удаляет запись текущего инстанса вместе со всеми зависимостями.
     */
    function delete()
    {
        return Yii::$app->relationGroupRepository->deleteRelationGroupById($this->id);
    }

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function update()
    {
        return $this->save();
    }

    /**
     * @param bool $condition
     * @return RelationGroup[]
     */
    public function find($condition = null)
    {
        $condition['condition'] = $condition['condition'] ?? null;

        return Yii::$app->relationGroupRepository->find($condition['condition']);
    }

    /**
     * @return BaseCrudModel
     */
    public function save()
    {
        return Yii::$app->relationGroupRepository->save($this);
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
     * @return mixed
     */
    public function getRelationClass()
    {
        return Yii::$app->relationGroupRepository->getRelationClass($this->id);
    }
}
