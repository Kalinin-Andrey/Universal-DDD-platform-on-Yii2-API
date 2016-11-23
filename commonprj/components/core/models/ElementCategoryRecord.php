<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "element_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $sysname
 * @property string $description
 * @property integer $parent_id
 * @property boolean $is_parent
 * @property integer $root_id
 * @property boolean $is_active
 * @property integer $element_type_id
 *
 * @property ElementCategoryRecord $root
 * @property ElementCategoryRecord[] $elementCategoryRecords
 * @property ElementTypeRecord $elementType
 */
class ElementCategoryRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'element_type_id'], 'required'],
            [['description'], 'string'],
            [['parent_id', 'root_id', 'element_type_id'], 'integer'],
            [['is_parent', 'is_active'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
            [['root_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementCategoryRecord::className(), 'targetAttribute' => ['root_id' => 'id']],
            [['element_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementTypeRecord::className(), 'targetAttribute' => ['element_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'name'            => 'Name',
            'sysname'         => 'Sysname',
            'description'     => 'Description',
            'parent_id'       => 'Parent ID',
            'is_parent'       => 'Is Parent',
            'root_id'         => 'Root ID',
            'is_active'       => 'Is Active',
            'element_type_id' => 'Element Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoot()
    {
        return $this->hasOne(ElementCategoryRecord::className(), ['id' => 'root_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementCategoryRecords()
    {
        return $this->hasMany(ElementCategoryRecord::className(), ['root_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementType()
    {
        return $this->hasOne(ElementTypeRecord::className(), ['id' => 'element_type_id']);
    }
}
