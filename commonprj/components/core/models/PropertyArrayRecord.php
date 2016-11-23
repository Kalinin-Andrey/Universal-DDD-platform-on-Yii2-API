<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property_array".
 *
 * @property integer $id
 * @property string $name
 * @property integer $property_id
 * @property string $value_ids
 *
 * @property PropertyRecord $property
 */
class PropertyArrayRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_array';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'property_id', 'value_ids'], 'required'],
            [['property_id'], 'integer'],
            [['value_ids'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['property_id', 'name'], 'unique', 'targetAttribute' => ['property_id', 'name'], 'message' => 'The combination of Name and Property ID has already been taken.'],
            [['property_id', 'value_ids'], 'unique', 'targetAttribute' => ['property_id', 'value_ids'], 'message' => 'The combination of Property ID and Value Ids has already been taken.'],
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
            'name'        => 'Name',
            'property_id' => 'Property ID',
            'value_ids'   => 'Value Ids',
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
