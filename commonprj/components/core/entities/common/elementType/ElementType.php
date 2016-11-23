<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\elementType;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class ElementType
 * @package commonprj\components\core\entities\common\elementType
 *
 * @property string $name
 */
class ElementType extends BaseCrudModel
{
    public $id;
    public $elementClassId;
    public $variantTypeId;
    public $name;
    public $sysname;
    public $elementCategory;
    public $elementClass;
    public $variant;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->elementTypeRepository;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['elementClassId'], 'integer'],
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
     * @throws HttpException
     */
    function delete()
    {
        $this->repository->deleteElementTypeById($this->id);

        return true;
    }

    /**
     * @return \yii\db\ActiveRecord|array
     * @throws HttpException
     */
    public function update()
    {
        return $this->save();
    }

    /**
     * @param bool $condition
     * @return mixed
     */
    public function find($condition = null)
    {
        return $this->repository->find($condition['condition']);
    }

    /**
     * @param int $id
     * @return \commonprj\components\core\entities\common\elementClass\ElementClass
     * @throws HttpException
     */
    public function getElementClass(int $id)
    {

        return $this->repository->getElementClassById($id);
    }

    /**
     * @param int $id
     * @return \commonprj\components\core\entities\common\elementCategory\ElementCategory[]
     * @throws HttpException
     */
    public function getCategory(int $id)
    {
        return $this->repository->getElementCategoriesById($id);
    }

    /**
     * @return mixed
     * @internal param $id
     */
    public function getVariant()
    {
        return $this->repository->getVariantById($this->id);
    }
}
