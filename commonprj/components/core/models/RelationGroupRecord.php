<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "relation_group".
 *
 * @property integer $id
 * @property string $name
 * @property integer $relation_class_id
 * @property integer $root_id
 *
 * @property RelationRecord[] $relations
 * @property ElementRecord[] $childElements
 * @property ElementRecord $root
 * @property RelationClassRecord $relationClass
 */
class RelationGroupRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'relation_class_id', 'root_id'], 'required'],
            [['relation_class_id', 'root_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['relation_class_id', 'root_id'], 'unique', 'targetAttribute' => ['relation_class_id', 'root_id'], 'message' => 'The combination of Relation Class ID and Root ID has already been taken.'],
            [['root_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementRecord::className(), 'targetAttribute' => ['root_id' => 'id']],
            [['relation_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelationClassRecord::className(), 'targetAttribute' => ['relation_class_id' => 'id']],
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
            'relation_class_id' => 'Relation Class ID',
            'root_id'           => 'Root ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelations()
    {
        return $this->hasMany(RelationRecord::className(), ['relation_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildElements()
    {
        return $this->hasMany(ElementRecord::className(), ['id' => 'child_element_id'])->viaTable('relation', ['relation_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoot()
    {
        return $this->hasOne(ElementRecord::className(), ['id' => 'root_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationClass()
    {
        return $this->hasOne(RelationClassRecord::className(), ['id' => 'relation_class_id']);
    }
}
