<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "list_item_property_value".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $value
 * @property string $label
 *
 * @property PropertyRecord $property
 */
class ListItemPropertyValueRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'list_item_property_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'value', 'label'], 'required'],
            [['property_id'], 'integer'],
            [['value', 'label'], 'string', 'max' => 50],
            [['property_id', 'label'], 'unique', 'targetAttribute' => ['property_id', 'label'], 'message' => 'The combination of Property ID and Label has already been taken.'],
            [['property_id', 'value'], 'unique', 'targetAttribute' => ['property_id', 'value'], 'message' => 'The combination of Property ID and Value has already been taken.'],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'property_id' => 'Property ID',
            'value'       => 'Value',
            'label'       => 'Label',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_id']);
    }
}
