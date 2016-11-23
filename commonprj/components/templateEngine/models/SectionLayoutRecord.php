<?php

namespace commonprj\components\templateEngine\models;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "section_layout".
 *
 * @property integer $id
 * @property integer $section_id
 * @property integer $template_id
 * @property integer $placeholder_id
 * @property integer $subtemplate_id
 *
 * @property PlaceholderRecord $placeholder
 * @property SectionRecord $section
 * @property SubtemplateRecord $subtemplate
 * @property TemplateRecord $template
 */
class SectionLayoutRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'section_layout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'template_id', 'placeholder_id', 'subtemplate_id'], 'required'],
            [['section_id', 'template_id', 'placeholder_id', 'subtemplate_id'], 'integer'],
            [['section_id', 'template_id', 'placeholder_id', 'subtemplate_id'], 'unique', 'targetAttribute' => ['section_id', 'template_id', 'placeholder_id', 'subtemplate_id']],
            [['placeholder_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceholderRecord::className(), 'targetAttribute' => ['placeholder_id' => 'id']],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => SectionRecord::className(), 'targetAttribute' => ['section_id' => 'id']],
            [['subtemplate_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubtemplateRecord::className(), 'targetAttribute' => ['subtemplate_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => TemplateRecord::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'section_id'     => 'Section ID',
            'template_id'    => 'Template ID',
            'placeholder_id' => 'Placeholder ID',
            'subtemplate_id' => 'Subtemplate ID',
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
    public function getSection()
    {
        return $this->hasOne(SectionRecord::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubtemplate()
    {
        return $this->hasOne(SubtemplateRecord::className(), ['id' => 'subtemplate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateRecord::className(), ['id' => 'template_id']);
    }

}
