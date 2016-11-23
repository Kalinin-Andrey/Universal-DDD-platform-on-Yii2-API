<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property2element_class".
 *
 * @property integer $element_class_id
 * @property integer $property_id
 *
 * @property ElementClassRecord $elementClass
 * @property PropertyRecord $property
 */
class Property2elementClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property2element_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_class_id', 'property_id'], 'required'],
            [['element_class_id', 'property_id'], 'integer'],
            [['element_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementClassRecord::className(), 'targetAttribute' => ['element_class_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'element_class_id' => 'Element Class ID',
            'property_id'      => 'Property ID',
        ];
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
    public function getProperty()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_id']);
    }
}
