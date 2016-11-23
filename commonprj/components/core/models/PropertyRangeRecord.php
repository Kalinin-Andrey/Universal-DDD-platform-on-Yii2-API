<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property_range".
 *
 * @property integer $id
 * @property string $name
 * @property integer $property_id
 * @property integer $from_value_id
 * @property integer $to_value_id
 *
 * @property PropertyRecord $property
 */
class PropertyRangeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_range';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'property_id', 'from_value_id', 'to_value_id'], 'required'],
            [['property_id', 'from_value_id', 'to_value_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['property_id', 'from_value_id', 'to_value_id'], 'unique', 'targetAttribute' => ['property_id', 'from_value_id', 'to_value_id'], 'message' => 'The combination of Property ID, From Value ID and To Value ID has already been taken.'],
            [['property_id', 'name'], 'unique', 'targetAttribute' => ['property_id', 'name'], 'message' => 'The combination of Name and Property ID has already been taken.'],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'name'          => 'Name',
            'property_id'   => 'Property ID',
            'from_value_id' => 'From Value ID',
            'to_value_id'   => 'To Value ID',
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
