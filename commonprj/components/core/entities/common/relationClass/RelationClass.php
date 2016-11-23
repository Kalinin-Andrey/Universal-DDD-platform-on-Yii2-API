<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.08.2016
 */

namespace commonprj\components\core\entities\common\relationClass;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class RelationClass
 * @package commonprj\components\core\entities\common\relationClass
 */
class RelationClass extends BaseCrudModel
{
    public $id;
    public $relationTypeId;
    public $name;
    public $sysname;
    public $description;
    public $relationGroups;
    public $elementClasses;
    public $elementClass2relationClasses;
    public $entity;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->relationClassRepository;
    }

    /**
     * Найти модель по id или другому условию.
     * @param mixed $condition - id (обычный или composite) записи или ассоциативный массив условий для WHERE.
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
        return $this->repository->deleteRelationClassById($this->id);
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
     * @return BaseCrudModel[]
     */
    public function find($condition = null)
    {
        $condition['condition'] = $condition['condition'] ?? null;

        return $this->repository->find($condition['condition']);
    }

    /**
     * @return BaseCrudModel
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * @param $isRoot
     * @return mixed
     */
    public function getElementClassesByIsRoot($isRoot)
    {
        return $this->repository->getElementClassesById($this->id, $isRoot);
    }

    /**
     * @return mixed
     */
    public function getRelationGroups()
    {
        return $this->repository->getRelationGroups($this->id);
    }
}
