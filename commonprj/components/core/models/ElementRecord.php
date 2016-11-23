<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "element".
 *
 * @property integer $id
 * @property string $name
 * @property integer $schema_element_id
 * @property boolean $is_active
 *
 * @property ElementRecord $schemaElement
 * @property ElementRecord[] $elements
 * @property Element2elementClassRecord[] $element2elementClasses
 * @property ElementClassRecord[] $elementClasses
 * @property ModelRecord[] $models
 * @property PropertyRelationRecord[] $propertyRelations
 * @property PropertyRecord[] $properties
 * @property PropertyVariantRecord[] $propertyVariants
 * @property RelationRecord[] $relations
 * @property RelationRecord[] $relations0
 * @property RelationGroupRecord[] $relationGroups
 * @property RelationGroupRecord[] $relationGroups0
 * @property RelationClassRecord[] $relationClasses
 * @property RelationVariantRecord[] $relationVariants
 * @property RelationVariantRecord[] $relationVariants0
 */
class ElementRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['schema_element_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['name', 'schema_element_id'], 'unique', 'targetAttribute' => ['name', 'schema_element_id'], 'message' => 'The combination of Name and Schema Element ID has already been taken.'],
            [['schema_element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['schema_element_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'name'              => 'Name',
            'schema_element_id' => 'Schema Element ID',
            'is_active'         => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchemaElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'schema_element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(ElementRecord::className(), ['schema_element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement2elementClasses()
    {
        return $this->hasMany(Element2elementClassRecord::className(), ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClasses()
    {
        return $this->hasMany(ElementClassRecord::className(), ['id' => 'element_class_id'])->viaTable('element2element_class', ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(ModelRecord::className(), ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyRelations()
    {
        return $this->hasMany(PropertyRelationRecord::className(), ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(PropertyRecord::className(), ['id' => 'property_id'])->viaTable('property_relation', ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyVariants()
    {
        return $this->hasMany(PropertyVariantRecord::className(), ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelations()
    {
        return $this->hasMany(RelationRecord::className(), ['parent_element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(ElementRecord::className(), ['id' => 'parent_element_id'])->viaTable('relation', ['child_element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(ElementRecord::className(), ['id' => 'child_element_id'])->viaTable('relation', ['parent_element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelations0()
    {
        return $this->hasMany(RelationRecord::className(), ['child_element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationGroups()
    {
        return $this->hasMany(RelationGroupRecord::className(), ['root_id' => 'id']);

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationGroups0()
    {
        return $this->hasMany(RelationGroupRecord::className(), ['id' => 'relation_group_id'])->viaTable('relation', ['child_element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationClasses()
    {
        return $this->hasMany(RelationClassRecord::className(), ['id' => 'relation_class_id'])->viaTable('relation_group', ['root_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationVariants()
    {
        return $this->hasMany(RelationVariantRecord::className(), ['element_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationVariants0()
    {
        return $this->hasMany(RelationVariantRecord::className(), ['related_element_id' => 'id']);
    }
}
