<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace commonprj\components\core\entities\common\propertyVariant;

use commonprj\components\core\entities\common\variant\Variant;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;

/**
 * Class PropertyVariant
 * @package commonprj\components\core\entities\common\propertyVariant
 */
class PropertyVariant extends Variant
{
    public $id;
    public $elementId;
    public $elementTypeId;
    public $variantTypeId;
    public $propertyId;
    public $valueTableId;
    public $valueId;
    public $propertyValue;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->variantTypeId = array_search('PropertyVariant', Variant::VARIANT_TYPES);
        $this->repository = Yii::$app->propertyVariantRepository;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['value_table_id']);
        unset($fields['value_id']);
        unset($fields['repository']);
        unset($fields['property_type_id']);

        return $fields;
    }

    /**
     * @inheritdoc
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
     * @return bool|self
     */
    public function update()
    {
        return $this->save();
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * @return bool
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
    public function getProperty()
    {
        return $this->repository->getProperty($this->id);
    }

    /**
     * @return mixed
     */
    public function getPropertyValue()
    {
        return $this->repository->getPropertyValue($this->id);
    }
}