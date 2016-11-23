<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "property_unit".
 *
 * @property integer $id
 * @property string $name
 *
 * @property PropertyRecord[] $properties
 * @property RelationRecord[] $relations
 * @property RelationVariantRecord[] $relationVariants
 */
class PropertyUnitRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(PropertyRecord::className(), ['property_unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelations()
    {
        return $this->hasMany(RelationRecord::className(), ['property_unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationVariants()
    {
        return $this->hasMany(RelationVariantRecord::className(), ['property_unit_id' => 'id']);
    }
}
