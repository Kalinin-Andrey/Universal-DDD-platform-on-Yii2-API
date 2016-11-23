<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "model".
 *
 * @property integer $id
 * @property integer $element_id
 * @property string $data
 *
 * @property ElementRecord $element
 */
class ModelRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'model';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_id', 'data'], 'required'],
            [['element_id'], 'integer'],
            [['data'], 'string'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['element_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'element_id' => 'Element ID',
            'data'       => 'Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'element_id']);
    }
}
