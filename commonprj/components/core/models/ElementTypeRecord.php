<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "element_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $sysname
 * @property integer $element_class_id
 * @property integer $variant_type_id
 *
 * @property ElementCategoryRecord[] $elementCategories
 * @property ElementClassRecord $elementClass
 * @property PropertyVariantRecord[] $propertyVariants
 * @property RelationVariantRecord[] $relationVariants
 */
class ElementTypeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'element_class_id', 'variant_type_id'], 'required'],
            [['element_class_id', 'variant_type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
            [['element_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementClassRecord::className(), 'targetAttribute' => ['element_class_id' => 'id']],
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
            'sysname'          => 'Sysname',
            'element_class_id' => 'Element Class ID',
            'variant_type_id'  => 'Variant Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementCategories()
    {
        return $this->hasMany(ElementCategoryRecord::className(), ['element_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClass()
    {
        return $this->hasOne(ElementClassRecord::className(), ['id' => 'element_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyVariants()
    {
        return $this->hasMany(PropertyVariantRecord::className(), ['element_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationVariants()
    {
        return $this->hasMany(RelationVariantRecord::className(), ['element_type_id' => 'id']);
    }
}
