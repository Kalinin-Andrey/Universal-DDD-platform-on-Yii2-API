<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "relation_class".
 *
 * @property integer $id
 * @property string $name
 * @property string $sysname
 * @property string $description
 * @property integer $relation_type_id
 *
 * @property ElementClass2relationClassRecord[] $elementClass2relationClasses
 * @property RelationGroupRecord[] $relationGroups
 * @property ElementRecord[] $roots
 * @property RelationVariantRecord[] $relationVariants
 */
class RelationClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sysname', 'relation_type_id'], 'required'],
            [['description'], 'string'],
            [['relation_type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['sysname'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'name'             => 'Name',
            'sysname'          => 'Sysname',
            'description'      => 'Description',
            'relation_type_id' => 'Relation Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClass2relationClasses()
    {
        return $this->hasMany(ElementClass2relationClassRecord::className(), ['relation_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClass()
    {
        return $this->hasMany(ElementClass2relationClassRecord::className(), ['relation_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationGroups()
    {
        return $this->hasMany(RelationGroupRecord::className(), ['relation_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoots()
    {
        return $this->hasMany(ElementRecord::className(), ['id' => 'root_id'])->viaTable('relation_group', ['relation_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationVariants()
    {
        return $this->hasMany(RelationVariantRecord::className(), ['relation_class_id' => 'id']);
    }
}
