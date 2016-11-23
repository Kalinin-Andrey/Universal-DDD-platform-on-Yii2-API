<?php

namespace commonprj\components\templateEngine\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%template}}".
 *
 * @property integer $id
 * @property string $sysname
 * @property string $name
 * @property string $description
 * @property string $path
 * @property boolean $is_active
 *
 * @property SectionLayoutRecord[] $sectionLayouts
 * @property Template2placeholderRecord[] $template2placeholders
 * @property PlaceholderRecord[] $placeholders
 */
class TemplateRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysname', 'name', 'description', 'path'], 'required'],
            ['sysname', 'string', 'max' => 50],
            ['sysname', 'match', 'pattern' => '/^[A-Za-z0-9]{1,50}$/'],
            [['sysname'], 'unique'],
            ['name', 'string', 'max' => 255],
            ['name', 'match', 'pattern' => '/^[A-Za-z0-9]{1}[A-Za-z0-9-_ ]{1,253}[A-Za-z0-9]{1}$/'],
            ['description', 'string'],
            ['description', 'trim'],
            ['path', 'string', 'max' => 255],
            ['path', 'match', 'pattern' => '/^([a-zA-Z0-9-_]+[\/]{1})+[a-zA-Z0-9-_]+\.tpl+$/'],
            ['is_active', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'sysname'     => 'Sysname',
            'name'        => 'Name',
            'description' => 'Description',
            'path'        => 'Path',
            'is_active'   => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionLayouts()
    {
        return $this->hasMany(SectionLayoutRecord::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate2placeholders()
    {
        return $this->hasMany(Template2placeholderRecord::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceholders()
    {
        return $this->hasMany(PlaceholderRecord::className(), ['id' => 'placeholder_id'])->viaTable('{{%template2placeholder}}', ['template_id' => 'id']);
    }
}
