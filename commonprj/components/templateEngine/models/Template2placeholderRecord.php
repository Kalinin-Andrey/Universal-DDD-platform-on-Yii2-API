<?php

namespace commonprj\components\templateEngine\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%template2placeholder}}".
 *
 * @property integer $template_id
 * @property integer $placeholder_id
 *
 * @property PlaceholderRecord $placeholder
 * @property TemplateRecord $template
 */
class Template2placeholderRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template2placeholder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'placeholder_id'], 'required'],
            [['template_id', 'placeholder_id'], 'integer'],
            [['template_id', 'placeholder_id'], 'unique', 'targetAttribute' => ['template_id', 'placeholder_id']],
            [['placeholder_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceholderRecord::className(), 'targetAttribute' => ['placeholder_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => TemplateRecord::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id'    => 'Template ID',
            'placeholder_id' => 'Placeholder ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceholder()
    {
        return $this->hasOne(PlaceholderRecord::className(), ['id' => 'placeholder_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateRecord::className(), ['id' => 'template_id']);
    }
}
