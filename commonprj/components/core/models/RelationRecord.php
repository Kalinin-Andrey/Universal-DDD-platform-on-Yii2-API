<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "relation".
 *
 * @property integer $id
 * @property integer $relation_group_id
 * @property integer $parent_element_id
 * @property integer $child_element_id
 * @property double $value
 * @property integer $property_unit_id
 * @property integer $order
 *
 * @property ElementRecord $parentElement
 * @property ElementRecord $childElement
 * @property PropertyUnitRecord $propertyUnit
 * @property RelationGroupRecord $relationGroup
 */
class RelationRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relation_group_id', 'parent_element_id', 'child_element_id'], 'required'],
            [['relation_group_id', 'parent_element_id', 'child_element_id', 'property_unit_id', 'order'], 'integer'],
            [['value'], 'number'],
            [['relation_group_id', 'child_element_id'], 'unique', 'targetAttribute' => ['relation_group_id', 'child_element_id'], 'message' => 'The combination of Relation Group ID and Child Element ID has already been taken.'],
            [['parent_element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['parent_element_id' => 'id']],
            [['child_element_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['child_element_id' => 'id']],
            [['property_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyUnitRecord::className(), 'targetAttribute' => ['property_unit_id' => 'id']],
            [['relation_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelationGroupRecord::className(), 'targetAttribute' => ['relation_group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'relation_group_id' => 'Relation Group ID',
            'parent_element_id' => 'Parent Element ID',
            'child_element_id'  => 'Child Element ID',
            'value'             => 'Value',
            'property_unit_id'  => 'Property Unit ID',
            'order'             => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'parent_element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildElement()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'child_element_id']);
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
    public function getRelationGroup()
    {
        return $this->hasOne(RelationGroupRecord::className(), ['id' => 'relation_group_id']);
    }
}
