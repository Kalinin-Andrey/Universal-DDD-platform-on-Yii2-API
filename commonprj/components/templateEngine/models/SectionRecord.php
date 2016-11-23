<?php

namespace commonprj\components\templateEngine\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $id
 * @property string $sysname
 * @property string $name
 * @property string $description
 * @property integer $template_id
 * @property boolean $is_active
 *
 * @property SectionLayoutRecord[] $sectionLayouts
 */
class SectionRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%section}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysname', 'name', 'description', 'template_id'], 'required'],
            ['sysname', 'string', 'max' => 50],
            ['sysname', 'match', 'pattern' => '/^[A-Za-z0-9]{1,50}$/'],
            [['sysname'], 'unique'],
            ['name', 'string', 'max' => 255],
            ['name', 'match', 'pattern' => '/^[A-Za-z0-9]{1}[A-Za-z0-9-_ ]{1,253}[A-Za-z0-9]{1}$/'],
            ['description', 'string'],
            ['description', 'trim'],
            [['template_id'], 'integer'],
            [['is_active'], 'boolean'],
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
            'template_id' => 'Template ID',
            'is_active'   => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateRecord::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionLayouts()
    {
        return $this->hasMany(SectionLayoutRecord::className(), ['section_id' => 'id']);
    }
}
