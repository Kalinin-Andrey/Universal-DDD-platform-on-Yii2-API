<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\elementClass;

use commonprj\components\core\models\Property2elementClassRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class ElementClass
 * @package commonprj\components\core\entities\common\elementClass
 *
 * @property integer $context_id
 * @property string $name
 */
class ElementClass extends BaseCrudModel
{
    public $id;
    public $contextId;
    public $name;
    public $sysname;
    public $description;
    public $relationClasses;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->elementClassRepository;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contextId'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
        ];
    }

    /**
     * Сохранение инстанса объекта в БД
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * @inheritdoc
     */
    public function findOne($condition)
    {
        return $this->repository->findOne($condition);
    }

    /**
     * @return mixed
     */
    function delete()
    {
        $this->repository->deleteElementClassById($this->id);

        return true;
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
     * @return array
     */
    public function find($condition = null)
    {
        return $this->repository->find($condition['condition']);
    }

    /**
     * Метод возвращает объект контекста к которому принадлежит текущий класс.
     * @return string
     */
    public function getContext()
    {
        return $this->repository->getContext($this->id);
    }

    /**
     * @param bool $isRoot
     * @return array
     */
    public function getRelationClassesByIsRoot(bool $isRoot)
    {
        return $this->repository->getRelationClassesById($this->id, $isRoot);
    }

    /**
     * @return mixed
     */
    public function getPropertiesById()
    {
        return $this->repository->getPropertiesById($this->id);
    }

    /**
     * @param $contextNameAndClassName
     * @return ElementClass
     */
    public function getElementClassByName($contextNameAndClassName)
    {
        return $this->repository->getElementClassByName($contextNameAndClassName);
    }

    /**
     * @param $elementClassId
     * @param $propertyId
     * @return Property2elementClassRecord
     */
    public function createProperty2ElementClass($elementClassId, $propertyId)
    {
        return $this->repository->createProperty2ElementClass($elementClassId, $propertyId);
    }
}
