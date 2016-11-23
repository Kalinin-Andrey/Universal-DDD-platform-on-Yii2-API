<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property_relation".
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $element_id
 * @property integer $value_table_id
 * @property integer $value_id
 *
 * @property ElementRecord $element
 * @property PropertyRecord $property
 */
class PropertyRelationRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'element_id', 'value_table_id', 'value_id'], 'required'],
            [['property_id', 'element_id', 'value_table_id', 'value_id'], 'integer'],
            [['property_id', 'element_id'], 'unique', 'targetAttribute' => ['property_id', 'element_id'], 'message' => 'The combination of Property ID and Element ID has already been taken.'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'property_id'    => 'Property ID',
            'element_id'     => 'Element ID',
            'value_table_id' => 'Value Table ID',
            'value_id'       => 'Value ID',
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
    public function getProperty()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_id']);
    }
}
