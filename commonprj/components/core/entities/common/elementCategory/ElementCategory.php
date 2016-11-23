<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 27.06.2016
 */

namespace commonprj\components\core\entities\common\elementCategory;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class elementCategory
 * @package commonprj\components\core\entities\common\elementCategory
 */
class ElementCategory extends BaseCrudModel
{
    public $id;
    public $elementTypeId;
    public $parentId;
    public $rootId;
    public $isParent = 0;
    public $isActive = 0;
    public $name;
    public $sysname;
    public $description;
    public $elementType;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->elementCategoryRepository;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['parentId', 'rootId', 'elementTypeId'], 'integer'],
            [['isParent', 'isActive'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
        ];
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
        $this->repository->deleteElementCategoryById($this->id);

        return true;
    }

    /**
     * @return array|\yii\db\ActiveRecord
     * @throws HttpException
     */
    public function update()
    {
        return $this->repository->save($this);
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
     * @return BaseCrudModel
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->repository->getChildrenById($this->id);
    }

    /**
     * @return array|\yii\db\ActiveRecord
     * @throws HttpException
     */
    public function getParent()
    {
        return $this->repository->getParentByChildId($this->id);
    }

    /**
     * @return bool|string
     */
    public function getIsParent()
    {
        return $this->repository->getIsParent($this->id);
    }

    /**
     * @return null|\yii\db\ActiveRecord
     */
    public function getRoot()
    {
        return $this->repository->getRootById($this->id);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRoots()
    {
        return $this->repository->getRoots();
    }

    /**
     * @param $rootElementCategoryId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getHierarchyByRootId($rootElementCategoryId)
    {
        return $this->repository->getHierarchyByRootId($rootElementCategoryId);
    }
}
