<?php

namespace commonprj\components\templateEngine\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%placeholder}}".
 *
 * @property integer $id
 * @property string $sysname
 * @property string $name
 * @property boolean $is_active
 *
 * @property SectionLayoutRecord[] $sectionLayouts
 * @property Template2placeholderRecord[] $template2placeholders
 * @property TemplateRecord[] $templates
 */
class PlaceholderRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%placeholder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysname', 'name'], 'required'],
            ['sysname', 'string', 'max' => 50],
            ['sysname', 'match', 'pattern' => '/^[A-Za-z0-9]{1,50}$/'],
            [['sysname'], 'unique'],
            ['name', 'string', 'max' => 255],
            ['name', 'match', 'pattern' => '/^[A-Za-z0-9]{1}[A-Za-z0-9-_ ]{1,253}[A-Za-z0-9]{1}$/'],
            [['is_active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'sysname'   => 'Sysname',
            'name'      => 'Name',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionLayouts()
    {
        return $this->hasMany(SectionLayoutRecord::className(), ['placeholder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate2placeholders()
    {
        return $this->hasMany(Template2placeholderRecord::className(), ['placeholder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(TemplateRecord::className(), ['id' => 'template_id'])->viaTable('{{%template2placeholder}}', ['placeholder_id' => 'id']);
    }
}
