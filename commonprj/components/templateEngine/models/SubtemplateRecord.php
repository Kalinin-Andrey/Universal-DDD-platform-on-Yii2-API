<?php

namespace commonprj\components\templateEngine\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%subtemplate}}".
 *
 * @property integer $id
 * @property string $sysname
 * @property string $name
 * @property string $description
 * @property string $path
 * @property boolean $is_active
 *
 * @property SectionLayoutRecord[] $sectionLayouts
 */
class SubtemplateRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subtemplate}}';
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
        return $this->hasMany(SectionLayoutRecord::className(), ['subtemplate_id' => 'id']);
    }
}
