<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "element2element_class".
 *
 * @property integer $element_class_id
 * @property integer $element_id
 *
 * @property ElementRecord $element
 * @property ElementClassRecord $elementClass
 */
class Element2elementClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element2element_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_class_id', 'element_id'], 'required'],
            [['element_class_id', 'element_id'], 'integer'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['element_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementClassRecord::className(), 'targetAttribute' => ['element_class_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'element_class_id' => 'Element Class ID',
            'element_id'       => 'Element ID',
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
    public function getElementClass()
    {
        return $this->hasOne(ElementClassRecord::className(), ['id' => 'element_class_id']);
    }
}
