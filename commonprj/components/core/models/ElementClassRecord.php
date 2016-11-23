<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "element_class".
 *
 * @property integer $id
 * @property integer $context_id
 * @property string $name
 * @property string $description
 * @property string $sysname
 *
 * @property Element2elementClassRecord[] $element2elementClasses
 * @property ElementRecord[] $elements
 * @property ContextRecord $context
 * @property ElementClass2relationClassRecord[] $elementClass2relationClasses
 * @property ElementTypeRecord[] $elementTypes
 * @property Property2elementClassRecord[] $property2elementClasses
 * @property PropertyRecord[] $properties
 */
class ElementClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['context_id', 'name', 'sysname'], 'required'],
            [['context_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
            [['sysname'], 'unique'],
            [['context_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContextRecord::className(), 'targetAttribute' => ['context_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'context_id'  => 'Context ID',
            'name'        => 'Name',
            'description' => 'Description',
            'sysname'     => 'Sysname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement2elementClasses()
    {
        return $this->hasMany(Element2elementClassRecord::className(), ['element_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(ElementRecord::className(), ['id' => 'element_id'])->viaTable('element2element_class', ['element_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContext()
    {
        return $this->hasOne(ContextRecord::className(), ['id' => 'context_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClass2relationClasses()
    {
        return $this->hasMany(ElementClass2relationClassRecord::className(), ['element_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationClasses()
    {
        return $this->hasMany(RelationClassRecord::className(), ['id' => 'element_class_id'])->viaTable('element_class2relation_class', ['relation_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementTypes()
    {
        return $this->hasMany(ElementTypeRecord::className(), ['element_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty2elementClasses()
    {
        return $this->hasMany(Property2elementClassRecord::className(), ['element_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(PropertyRecord::className(), ['id' => 'property_id'])->viaTable('property2element_class', ['element_class_id' => 'id']);
    }
}
