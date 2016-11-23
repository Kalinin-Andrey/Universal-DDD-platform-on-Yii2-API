<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "relation_variant".
 *
 * @property integer $id
 * @property integer $element_id
 * @property integer $relation_class_id
 * @property integer $related_element_id
 * @property double $value
 * @property integer $property_unit_id
 * @property integer $element_type_id
 *
 * @property ElementRecord $element
 * @property ElementRecord $relatedElement
 * @property ElementTypeRecord $elementType
 * @property PropertyUnitRecord $propertyUnit
 * @property RelationClassRecord $relationClass
 */
class RelationVariantRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation_variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_id', 'relation_class_id', 'related_element_id', 'element_type_id'], 'required'],
            [['element_id', 'relation_class_id', 'related_element_id', 'property_unit_id', 'element_type_id'], 'integer'],
            [['value'], 'number'],
            [['element_id', 'relation_class_id', 'related_element_id', 'value', 'property_unit_id'], 'unique', 'targetAttribute' => ['element_id', 'relation_class_id', 'related_element_id', 'value', 'property_unit_id'], 'message' => 'The combination of Element ID, Relation Class ID, Related Element ID, Value and Property Unit ID has already been taken.'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['related_element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['related_element_id' => 'id']],
            [['element_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementTypeRecord::className(), 'targetAttribute' => ['element_type_id' => 'id']],
            [['property_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyUnitRecord::className(), 'targetAttribute' => ['property_unit_id' => 'id']],
            [['relation_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelationClassRecord::className(), 'targetAttribute' => ['relation_class_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                 => 'ID',
            'element_id'         => 'Element ID',
            'relation_class_id'  => 'Relation Class ID',
            'related_element_id' => 'Related Element ID',
            'value'              => 'Value',
            'property_unit_id'   => 'Property Unit ID',
            'element_type_id'    => 'Element Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'related_element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementType()
    {
        return $this->hasOne(ElementTypeRecord::className(), ['id' => 'element_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyUnit()
    {
        return $this->hasOne(PropertyUnitRecord::className(), ['id' => 'property_unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationClass()
    {
        return $this->hasOne(RelationClassRecord::className(), ['id' => 'relation_class_id']);
    }
}
