<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "context".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ElementClassRecord[] $elementClasses
 */
class ContextRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'context';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClasses()
    {
        return $this->hasMany(ElementClassRecord::className(), ['context_id' => 'id']);
    }
}
