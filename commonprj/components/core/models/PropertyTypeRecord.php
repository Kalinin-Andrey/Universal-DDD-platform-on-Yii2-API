<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 *
 * @property PropertyRecord[] $properties
 */
class PropertyTypeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'name'      => 'Name',
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(PropertyRecord::className(), ['property_type_id' => 'id']);
    }
}
