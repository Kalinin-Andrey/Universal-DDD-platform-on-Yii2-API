<?php

namespace commonprj\components\core\models;

/**
 * This is the model class for table "element_class2relation_class".
 *
 * @property integer $element_class_id
 * @property integer $relation_class_id
 * @property boolean $is_root
 *
 * @property ElementClassRecord $elementClass
 * @property RelationClassRecord $relationClass
 */
class ElementClass2relationClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element_class2relation_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_class_id', 'relation_class_id', 'is_root'], 'required'],
            [['element_class_id', 'relation_class_id'], 'integer'],
            [['is_root'], 'boolean'],
            [['element_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => ElementClassRecord::className(), 'targetAttribute' => ['element_class_id' => 'id']],
            [['relation_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelationClassRecord::className(), 'targetAttribute' => ['relation_class_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'element_class_id'  => 'Element Class ID',
            'relation_class_id' => 'Relation Class ID',
            'is_root'           => 'Is Root',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementClass()
    {
        return $this->hasOne(ElementClassRecord::className(), ['id' => 'element_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationClass()
    {
        return $this->hasOne(RelationClassRecord::className(), ['id' => 'relation_class_id']);
    }
}
