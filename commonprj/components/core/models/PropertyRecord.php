<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $property_unit_id
 * @property boolean $is_specific
 * @property integer $property_type_id
 * @property string $sysname
 *
 * @property BigintPropertyValueRecord[] $bigintPropertyValues
 * @property BooleanPropertyValueRecord[] $booleanPropertyValues
 * @property DatePropertyValueRecord[] $datePropertyValues
 * @property FloatPropertyValueRecord[] $floatPropertyValues
 * @property GeolocationPropertyValueRecord[] $geolocationPropertyValues
 * @property IntPropertyValueRecord[] $intPropertyValues
 * @property ListItemPropertyValueRecord[] $listItemPropertyValues
 * @property PropertyTypeRecord $propertyType
 * @property PropertyUnitRecord $propertyUnit
 * @property Property2elementClassRecord[] $property2elementClasses
 * @property ElementClassRecord[] $elementClasses
 * @property PropertyArrayRecord[] $propertyArrays
 * @property PropertyRangeRecord[] $propertyRanges
 * @property PropertyRelationRecord[] $propertyRelations
 * @property ElementRecord[] $elements
 * @property PropertyVariantRecord[] $propertyVariants
 * @property StringPropertyValueRecord[] $stringPropertyValues
 * @property TextPropertyValueRecord[] $textPropertyValues
 * @property TimestampPropertyValueRecord[] $timestampPropertyValues
 */
class PropertyRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'property_type_id'], 'required'],
            [['description'], 'string'],
            [['property_unit_id', 'property_type_id'], 'integer'],
            [['is_specific'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['property_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyTypeRecord::className(), 'targetAttribute' => ['property_type_id' => 'id']],
            [['property_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyUnitRecord::className(), 'targetAttribute' => ['property_unit_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'name'             => 'Name',
            'description'      => 'Description',
            'property_unit_id' => 'Property Unit ID',
            'is_specific'      => 'Is Specific',
            'property_type_id' => 'Property Type ID',
            'sysname'          => 'Sysname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBigintPropertyValues()
    {
        return $this->hasMany(BigintPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooleanPropertyValues()
    {
        return $this->hasMany(BooleanPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatePropertyValues()
    {
        return $this->hasMany(DatePropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloatPropertyValues()
    {
        return $this->hasMany(FloatPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeolocationPropertyValues()
    {
        return $this->hasMany(GeolocationPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntPropertyValues()
    {
        return $this->hasMany(IntPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListItemPropertyValues()
    {
        return $this->hasMany(ListItemPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyType()
    {
        return $this->hasOne(PropertyTypeRecord::className(), ['id' => 'property_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyUnit()
    {
        return $this->hasOne(PropertyUnitRecord::className(), ['id' => 'property_unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty2elementClasses()
    {
        return $this->hasMany(Property2elementClassRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClasses()
    {
        return $this->hasMany(ElementClassRecord::className(), ['id' => 'element_class_id'])->viaTable('property2element_class', ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyArrays()
    {
        return $this->hasMany(PropertyArrayRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyRanges()
    {
        return $this->hasMany(PropertyRangeRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyRelations()
    {
        return $this->hasMany(PropertyRelationRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(ElementRecord::className(), ['id' => 'element_id'])->viaTable('property_relation', ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyVariants()
    {
        return $this->hasMany(PropertyVariantRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStringPropertyValues()
    {
        return $this->hasMany(StringPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTextPropertyValues()
    {
        return $this->hasMany(TextPropertyValueRecord::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimestampPropertyValues()
    {
        return $this->hasMany(TimestampPropertyValueRecord::className(), ['property_id' => 'id']);
    }
}
