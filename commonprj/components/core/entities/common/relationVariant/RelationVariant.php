<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace commonprj\components\core\entities\common\relationVariant;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;

/**
 * Class RelationVariant
 * @package commonprj\components\core\entities\common\relationVariant
 */
class RelationVariant extends Variant
{
    public $id;
    public $elementId;
    public $relationClassId;
    public $relatedElementId;
    public $value;
    public $propertyUnitId;
    public $elementTypeId;
    public $variantTypeId;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->variantTypeId = array_search('RelationVariant', Variant::VARIANT_TYPES);
        $this->repository = Yii::$app->relationVariantRepository;
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
     * @param array|null $condition
     * @return BaseCrudModel
     */
    public function find($condition = null)
    {
        return $this->repository->find($condition['condition']);
    }

    /**
     * @inheritdoc
     */
    public function findOne($condition)
    {
        return $this->repository->findOne($condition);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * Удаляет запись текущего инстанса.
     */
    public function delete()
    {
        return $this->repository->deleteById($this->id);
    }

    /**
     * @return BaseCrudModel
     */
    public function getElementType()
    {
        return $this->repository->getElementType($this->id);
    }

    /**
     * @return array|BaseCrudModel
     */
    public function getSchemaElement()
    {
        return $this->repository->getSchemaElement($this->id);
    }

    /**
     * @return BaseCrudModel
     */
    public function getRelatedElement()
    {
        return $this->repository->getRelatedElement($this->id);
    }

    /**
     * @return mixed
     */
    public function getRelationClass()
    {
        return $this->repository->getRelationClass($this->id);
    }
}