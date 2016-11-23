<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property_variant".
 *
 * @property integer $id
 * @property integer $element_id
 * @property integer $property_id
 * @property integer $value_table_id
 * @property integer $value_id
 * @property integer $element_type_id
 *
 * @property ElementRecord $element
 * @property ElementTypeRecord $elementType
 * @property PropertyRecord $property
 */
class PropertyVariantRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_id', 'property_id', 'value_table_id', 'value_id', 'element_type_id'], 'required'],
            [['element_id', 'property_id', 'value_table_id', 'value_id', 'element_type_id'], 'integer'],
            [['element_id', 'property_id', 'value_table_id', 'value_id'], 'unique', 'targetAttribute' => ['element_id', 'property_id', 'value_table_id', 'value_id'], 'message' => 'The combination of Element ID, Property ID, Value Table ID and Value ID has already been taken.'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['element_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementTypeRecord::className(), 'targetAttribute' => ['element_type_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'element_id'      => 'Element ID',
            'property_id'     => 'Property ID',
            'value_table_id'  => 'Value Table ID',
            'value_id'        => 'Value ID',
            'element_type_id' => 'Element Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementType()
    {
        return $this->hasOne(ElementTypeRecord::className(), ['id' => 'element_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_id']);
    }
}
