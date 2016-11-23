<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\property;

use commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValue;
use commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValueDBRepository;
use commonprj\components\core\models\ListItemPropertyValueRecord;
use commonprj\components\core\models\PropertyArrayRecord;
use commonprj\components\core\models\PropertyRangeRecord;
use commonprj\components\core\models\PropertyRecord;
use commonprj\components\core\models\PropertyRelationRecord;
use commonprj\components\core\models\PropertyTypeRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class PropertyRepository
 * @package commonprj\components\core\entities\common\property
 */
Class PropertyDBRepository extends BaseDBRepository implements PropertyRepository
{
    public $activeRecord = 'commonprj\components\core\models\PropertyRecord';

    /**
     * @param $attributes
     * @return bool
     * @throws HttpException
     */
    public static function validateProperty($attributes)
    {
        $attributes = BaseDBRepository::arrayKeysCamelCase2Underscore($attributes);
        $property = self::instantiateByARAndClassName($attributes, 'commonprj\components\core\entities\common\property\Property');

        if (!$property->validate()) {
            $firstErrors = $property->getFirstErrors();
            $firstKey = array_keys($firstErrors)[0];
            $errorMessage = reset($firstErrors);
            throw new HttpException(400, "attribute: {$firstKey}. {$errorMessage} " . basename(__FILE__, '.php') . __LINE__);
        }

        /** @var ActiveRecord $validPropertyId */
        $validPropertyId = PropertyRecord::find()->where([
            'id' => $property['id'],
        ])->scalar();

        if ($validPropertyId === false) {
            throw new HttpException(400, 'Wrong property id given. ' . basename(__FILE__, '.php') . __LINE__);
        }

        return true;
    }

    /**
     * Внутренний метод для получения из записи в PropertyRelationRecord - значения свойства элемента.
     * @param ActiveRecord $propertyRelationRecord
     * @return AbstractPropertyValue
     */
    public static function getAbstractPropertyValueByPropertyRelation(ActiveRecord $propertyRelationRecord)
    {
        $elementPropertyValueId = $propertyRelationRecord->getAttribute('value_id');
        $valueTableId = $propertyRelationRecord->getAttribute('value_table_id');
        $elementPropertyValueRecordName = BaseDBRepository::getRecordNameByTableId($valueTableId);
        $elementPropertyValueRecord = call_user_func("{$elementPropertyValueRecordName}::findOne", $elementPropertyValueId);

        if ($valueTableId === array_search('property_range', self::VALUE_TABLE_ID)) {
            $result = self::getAbstractPropertyValueByPropertyRangeRecord($elementPropertyValueRecord);
        } elseif ($valueTableId === array_search('property_array', self::VALUE_TABLE_ID)) {
            $result = self::getAbstractPropertyValueByPropertyArrayRecord($elementPropertyValueRecord);
        } elseif ($valueTableId === array_search('list_item_property_value', self::VALUE_TABLE_ID)) {
            $result = self::getAbstractPropertyValueByListPropertyRecord($elementPropertyValueRecord);
        } else {
            $result = self::getAbstractPropertyValueByPropertyValueRecord($elementPropertyValueRecord);
        }

        return $result;
    }

    /**
     * @param PropertyRangeRecord $propertyRangeRecord
     * @return AbstractPropertyValue
     */
    private static function getAbstractPropertyValueByPropertyRangeRecord(PropertyRangeRecord $propertyRangeRecord):AbstractPropertyValue
    {
        $propertyRecord = PropertyRecord::findOne($propertyRangeRecord->getAttribute('property_id'));
        $valueTableId = $propertyRecord->getAttribute('property_type_id');
        $elementPropertyValueRecordName = BaseDBRepository::getRecordNameByTableId($valueTableId);
        $fromValueId = $propertyRangeRecord->getAttribute('from_value_id');
        /** @var ActiveRecord $elementPropertyFromValueRecord */
        $elementPropertyFromValueRecord = call_user_func("{$elementPropertyValueRecordName}::findOne", $fromValueId);
        $toValueId = $propertyRangeRecord->getAttribute('to_value_id');
        $elementPropertyToValueRecord = call_user_func("{$elementPropertyValueRecordName}::findOne", $toValueId);
        $result = new AbstractPropertyValue();
        $result->fromValue = $elementPropertyFromValueRecord->getAttribute('value');
        $result->toValue = $elementPropertyToValueRecord->getAttribute('value');
        $result->name = $propertyRangeRecord->getAttribute('name');
        $result->id = $propertyRangeRecord->getAttribute('id');
        $result->propertyId = $propertyRangeRecord->getAttribute('property_id');
        $result->multiplicityId = 2;

        return $result;
    }

    /**
     * @param PropertyArrayRecord $propertyArrayRecord
     * @return AbstractPropertyValue
     */
    private static function getAbstractPropertyValueByPropertyArrayRecord(PropertyArrayRecord $propertyArrayRecord):AbstractPropertyValue
    {
        $elementPropertyArrayRecord = $propertyArrayRecord;
        $valueIds = explode(',', preg_replace('/{|}/', '', $elementPropertyArrayRecord->getAttribute('value_ids')));
        $propertyRecord = PropertyRecord::findOne($elementPropertyArrayRecord->getAttribute('property_id'));
        $valueTableId = $propertyRecord->getAttribute('property_type_id');
        $elementPropertyValueRecordName = BaseDBRepository::getRecordNameByTableId($valueTableId);
        $result = new AbstractPropertyValue();

        foreach ($valueIds as $valueId) {
            /** @var ActiveRecord $elementPropertyArrayRecord */
            $elementPropertyValueRecord = call_user_func("{$elementPropertyValueRecordName}::findOne", $valueId);
            $result->values[] = $elementPropertyValueRecord->getAttribute('value');
        }

        $result->name = $elementPropertyArrayRecord->getAttribute('name');
        $result->id = $elementPropertyArrayRecord->getAttribute('id');
        $result->propertyId = $elementPropertyArrayRecord->getAttribute('property_id');
        $result->multiplicityId = 3;

        return $result;
    }

    /**
     * @param ListItemPropertyValueRecord $listItemPropertyValueRecord
     * @return AbstractPropertyValue
     */
    private static function getAbstractPropertyValueByListPropertyRecord(ListItemPropertyValueRecord $listItemPropertyValueRecord):AbstractPropertyValue
    {
        $result = new AbstractPropertyValue();
        $result->value = $listItemPropertyValueRecord->getAttribute('value');
        $result->label = $listItemPropertyValueRecord->getAttribute('label');
        $result->id = $listItemPropertyValueRecord->getAttribute('id');
        $result->propertyId = $listItemPropertyValueRecord->getAttribute('property_id');
        $result->multiplicityId = 1;

        return $result;
    }

    /**
     * @param ActiveRecord $elementPropertyValueRecord
     * @return AbstractPropertyValue
     */
    private static function getAbstractPropertyValueByPropertyValueRecord(ActiveRecord $elementPropertyValueRecord):AbstractPropertyValue
    {
        $result = new AbstractPropertyValue();
        $result->value = $elementPropertyValueRecord->getAttribute('value');
        $result->id = $elementPropertyValueRecord->getAttribute('id');
        $result->propertyId = $elementPropertyValueRecord->getAttribute('property_id');
        $result->multiplicityId = 1;

        return $result;
    }

    /**
     * Сохранение переданного объекта посредством active record
     * @param Property $property
     * @return PropertyRecord
     */
    public function save(Property $property)
    {
        if (!$elementTypeRecord = PropertyRecord::findOne($property->id)) {
            $elementTypeRecord = new PropertyRecord();
        }
        $elementTypeRecord->setAttributes(self::arrayKeysCamelCase2Underscore($property->attributes));

        $result = $elementTypeRecord->save();

        if ($result) {
            $property->setAttributes(self::arrayKeysUnderscore2CamelCase($elementTypeRecord->attributes), false);
        } else {
            $property->addErrors($elementTypeRecord->getErrors());
        }

        return $result;
    }

    /**
     * @param null $condition
     * @return array
     * @throws InvalidConfigException
     */
    public function find($condition = null)
    {
        preg_match('/(\w+)Repository$/', get_class($this), $match);
        $ucFirst = ucfirst($match[1]);
        $ucFirst = preg_replace('/DB/', '', $ucFirst);
        $classNameRecord = "commonprj\\components\\core\\models\\{$ucFirst}Record";
        $query = call_user_func("{$classNameRecord}::find");

        if (ArrayHelper::isAssociative($condition)) {
            $elementRecords = $query->where($condition)->all();
        } else {
            $elementRecords = $query->all();
        }

        $result = [];

        $lcFirst = lcfirst($ucFirst);
        $classNameDomain = "commonprj\\components\\core\\entities\\common\\{$lcFirst}\\{$ucFirst}";

        foreach ($elementRecords as $elementRecord) {
            $result[] = self::instantiateByARAndClassName($elementRecord, $classNameDomain);
        }

        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getPropertyClassesById(int $id)
    {
        $elementRecord = PropertyRecord::findOne($id);
        $elementClassRecords = $elementRecord->getElementClasses()->all();
        $result = [];

        foreach ($elementClassRecords as $elementClassRecord) {
            $result[$elementClassRecord['id']] = self::instantiateByARAndClassName($elementClassRecord, 'commonprj\components\core\entities\common\property\Property');
        }

        return $result;
    }

    /**
     * @param int $propertyId
     * @param null $multiplicityId
     * @return array
     */
    public function getValues(int $propertyId, $multiplicityId = null)
    {
        $propertyRecord = PropertyRecord::findOne($propertyId);
        /** @var ActiveRecord $recordName */
        $recordName = BaseDBRepository::getRecordNameByTableId($propertyRecord->getAttribute('property_type_id'));
        $propertyRanges = PropertyRangeRecord::find()->where(['property_id' => $propertyId])->all();
        $propertyArrays = PropertyArrayRecord::find()->where(['property_id' => $propertyId])->all();
        $propertyLists = ListItemPropertyValueRecord::find()->where(['property_id' => $propertyId])->all();
        if ($recordName != 'commonprj\components\core\models\ListItemPropertyValueRecord') {
            $propertyValues = $recordName::find()->where(['property_id' => $propertyId])->all();
        } else {
            $propertyValues = [];
        }

        $result = [];

        switch ($multiplicityId) {
            case 1:
                /** @var ListItemPropertyValueRecord $propertyList */
                foreach ($propertyLists as $propertyList) {
                    $result['propertyValuesByMultiplicityId'][1][$propertyList['id']] = self::getAbstractPropertyValueByListPropertyRecord($propertyList);
                }

                foreach ($propertyValues as $propertyValue) {
                    $result['propertyValuesByMultiplicityId'][1][$propertyValue['id']] = self::getAbstractPropertyValueByPropertyValueRecord($propertyValue);
                }
                break;
            case 2:
                /** @var PropertyRangeRecord $propertyRange */
                foreach ($propertyRanges as $propertyRange) {
                    $result['propertyValuesByMultiplicityId'][2][$propertyRange['id']] = self::getAbstractPropertyValueByPropertyRangeRecord($propertyRange);
                }
                break;
            case 3:
                /** @var PropertyArrayRecord $propertyArray */
                foreach ($propertyArrays as $propertyArray) {
                    $result['propertyValuesByMultiplicityId'][3][$propertyArray['id']] = self::getAbstractPropertyValueByPropertyArrayRecord($propertyArray);
                }
                break;
            default:
                /** @var ListItemPropertyValueRecord $propertyList */
                foreach ($propertyLists as $propertyList) {
                    $result['propertyValuesByMultiplicityId'][1][$propertyList['id']] = self::getAbstractPropertyValueByListPropertyRecord($propertyList);
                }

                foreach ($propertyValues as $propertyValue) {
                    $result['propertyValuesByMultiplicityId'][1][$propertyValue['id']] = self::getAbstractPropertyValueByPropertyValueRecord($propertyValue);
                }

                /** @var PropertyRangeRecord $propertyRange */
                foreach ($propertyRanges as $propertyRange) {
                    $result['propertyValuesByMultiplicityId'][2][$propertyRange['id']] = self::getAbstractPropertyValueByPropertyRangeRecord($propertyRange);
                }

                /** @var PropertyArrayRecord $propertyArray */
                foreach ($propertyArrays as $propertyArray) {
                    $result['propertyValuesByMultiplicityId'][3][$propertyArray['id']] = self::getAbstractPropertyValueByPropertyArrayRecord($propertyArray);
                }
        }

        return $result;
    }

    /**
     * @param $propertyUnitId
     * @return array|ActiveRecord
     */
    public function getPropertyUnitById($propertyUnitId)
    {
        $propertyRecord = PropertyRecord::findOne($propertyUnitId);
        $propertyUnitRecord = $propertyRecord->getPropertyUnit()->one();

        if (is_null($propertyUnitRecord)) {
            return [];
        } else {
            return $propertyUnitRecord;
        }
    }

    /**
     * @param int $elementClassId
     * @param int $id
     * @throws HttpException
     */
    public function deletePropertyClassByClassId(int $elementClassId, int $id)
    {
        $propertyRecord = PropertyRecord::findOne($id);
        $deletionRow = $propertyRecord->getProperty2elementClasses()->where(['element_class_id' => $elementClassId])->all();

        if (!$deletionRow) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($deletionRow);
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @param int $id
     * @throws HttpException
     */
    public function deletePropertyById(int $id)
    {
        $propertyRecord = PropertyRecord::findOne($id);

        if (is_null($propertyRecord)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();
        try {
            BaseCrudModel::deleteRows($propertyRecord->getProperty2elementClasses()->all());
            BaseCrudModel::deleteRows($propertyRecord->getPropertyRanges()->all());
            BaseCrudModel::deleteRows($propertyRecord->getListItemPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getGeolocationPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getTimestampPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getDatePropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getTextPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getStringPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getFloatPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getBigintPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getIntPropertyValues()->all());
            BaseCrudModel::deleteRows($propertyRecord->getBooleanPropertyValues()->all());
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        if (!$propertyRecord->delete()) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return PropertyRecord::primaryKey();
    }

    /**
     * Метод делает запись в property_relation получив id элемента и массив свойств AbstractPropertyValue.
     * @param array $attributes - Массив свойств common\PropertyValue переданный в POST запросе
     * @return bool|ActiveRecord
     */
    public function createElementPropertyRelation(array $attributes)
    {
        $attributes['elementId'] = $attributes['id'];
        unset($attributes['id']);

        if (!empty($attributes['propertyValueId'])) {
            $attributes['id'] = $attributes['propertyValueId'];
        }
        // todo поменять текст error при неправильном кол-ве параметров
        if (!empty($attributes['propertyValueId'])) {
            $attributes = AbstractPropertyValueDBRepository::consistentAbstractPropertyValueWithId($attributes);
            $attributes = $this->createPropertyRelationByExistingValue($attributes);
        } else {
            $attributes = AbstractPropertyValueDBRepository::consistentAbstractPropertyValueWithoutId($attributes);
            $attributes = $this->createPropertyRelationAttributesForNewValue($attributes);
        }

        if ($attributes instanceof ActiveRecord) {
            return $attributes;
        }

        // Проверяем привязано ли данное свойство к данному элементу
        $propertyRelationRecord = PropertyRelationRecord::find()->where([
            'property_id' => $attributes['propertyId'],
            'element_id'  => $attributes['elementId'],
        ])->one();

        // Если данное свойство не привязано к данному элементу, то делаем insert, в противном случае update
        if (is_null($propertyRelationRecord)) {
            $propertyRelationRecord = new PropertyRelationRecord();
        }

        $propertyRelationRecord->setAttributes(self::arrayKeysCamelCase2Underscore($attributes));
        $saveResult = $propertyRelationRecord->save();

        if ($propertyRelationRecord->hasErrors()) {
            return $propertyRelationRecord;
        } else {
            return $saveResult;
        }
    }

    /**
     * @param $attributes
     * @return array
     */
    private function createPropertyRelationByExistingValue($attributes)
    {
        $attributes = $this->attributes2PropertyRelationColumns($attributes);

        switch ($attributes['multiplicity_id']) {
            case 1:
                $attributes['value_table_id'] = $attributes['property_type_id'];
                break;
            case 2:
                $attributes['value_table_id'] = array_search('property_range', self::VALUE_TABLE_ID);
                break;
            case 3:
                $attributes['value_table_id'] = array_search('property_array', self::VALUE_TABLE_ID);
                break;
        }

        return $attributes;
    }

    /**
     * @param $attributes
     * @return array|PropertyArrayRecord|PropertyRangeRecord|null|ActiveRecord
     * @throws HttpException
     */
    private function createPropertyRelationAttributesForNewValue($attributes)
    {
        $attributes = $this->attributes2PropertyRelationColumns($attributes);

        if (!isset($attributes['propertyTypeName'])) {
            throw new HttpException(404, 'Wrong propertyId given. ' . basename(__FILE__, '.php') . __LINE__);
        }

        return $this->blackBox($attributes);
    }

    /**
     * Внутренний метод.
     * Переименовывает пришедший element_id в owner_id и сэтит owner_table_id для будущей записи в property_relation.
     * @param $attributes
     * @return array
     */
    private function attributes2PropertyRelationColumns($attributes)
    {
        if (!empty($attributes['id'])) {
            $attributes['valueId'] = $attributes['id'];
            unset($attributes['id']);
        }

        return $attributes;
    }

    /**
     * @param $attributes
     * @return ActiveRecord
     */
    public function blackBox($attributes)
    {
        Yii::$app->getDb()->beginTransaction();
        if ($attributes['multiplicityId'] === '1') {
            // Кейс с конкретным значением: В начале сохраняем valueTableId (в данном случае он совпадает с propertyTypeId)
            $attributes['valueTableId'] = $attributes['propertyTypeId'];
            /** @var ActiveRecord $propertyValueRecord */
            $propertyValueRecord = $attributes['propertyValueRecordName']::find()
                ->where(['value' => $attributes['value'], 'property_id' => $attributes['propertyId']])
                ->one();
            // Если value не существует, то создаем его и записываем его id в valueId (для propertyRelation).
            if (is_null($propertyValueRecord)) {

                $propertyValueRecord = new $attributes['propertyValueRecordName']();
                $propertyValueRecord->setAttributes([
                    'property_id' => $attributes['propertyId'],
                    'value'       => $attributes['value'],
                ]);

                if (isset($attributes['label']) && $propertyValueRecord->hasAttribute('label')) {
                    $propertyValueRecord->setAttribute('label', $attributes['label']);
                }

                if ($propertyValueRecord->save()) {
                    $attributes['valueId'] = $propertyValueRecord->getPrimaryKey();
                } else {
                    Yii::$app->getDb()->getTransaction()->rollBack();

                    return $propertyValueRecord;
                }
            } else {
                $attributes['valueId'] = $propertyValueRecord->getAttribute('id');
            }
        } elseif ($attributes['multiplicityId'] === '2') {
            // Кейс с рейнджем
            $attributes['valueTableId'] = array_search('property_range', self::VALUE_TABLE_ID);
            /** @var ActiveRecord $propertyFromValueRecord */
            $propertyFromValueRecord = $attributes['propertyValueRecordName']::find()
                ->where(['value' => $attributes['fromValue'], 'property_id' => $attributes['propertyId']])
                ->one();
            /** @var ActiveRecord $propertyToValueRecord */
            $propertyToValueRecord = $attributes['propertyValueRecordName']::find()
                ->where(['value' => $attributes['toValue'], 'property_id' => $attributes['propertyId']])
                ->one();
            // Если fromValue не существует, то создаем его и записываем его id в fromValueId (для propertyRange).
            if (is_null($propertyFromValueRecord)) {
                $propertyFromValueRecord = new $attributes['propertyValueRecordName']();
                $propertyFromValueRecord->setAttributes([
                    'property_id' => $attributes['propertyId'],
                    'value'       => $attributes['fromValue'],
                ]);

                if (isset($attributes['label']) && $propertyToValueRecord->hasAttribute('label')) {
                    $propertyToValueRecord->setAttribute('label', $attributes['label']);
                }

                if ($propertyFromValueRecord->save()) {
                    $propertyRangeAttributes['fromValueId'] = $propertyFromValueRecord->getPrimaryKey();
                } else {
                    Yii::$app->getDb()->getTransaction()->rollBack();

                    return $propertyFromValueRecord;
                }
            } else {
                $propertyRangeAttributes['fromValueId'] = $propertyFromValueRecord->getAttribute('id');
            }
            // Если toValue не существует, то создаем его и записываем его id в toValueId (для property_range).
            if (is_null($propertyToValueRecord)) {
                /** @var ActiveRecord $propertyToValueRecord */
                $propertyToValueRecord = new $attributes['propertyValueRecordName']();
                $propertyToValueRecord->setAttributes([
                    'property_id' => $attributes['propertyId'],
                    'value'       => $attributes['toValue'],
                ]);

                if (isset($attributes['label']) && $propertyToValueRecord->hasAttribute('label')) {
                    $propertyToValueRecord->setAttribute('label', $attributes['label']);
                }

                if ($propertyToValueRecord->save()) {
                    $propertyRangeAttributes['toValueId'] = $propertyToValueRecord->getPrimaryKey();
                } else {
                    Yii::$app->getDb()->getTransaction()->rollBack();

                    return $propertyToValueRecord;
                }
            } else {
                $propertyRangeAttributes['toValueId'] = $propertyToValueRecord->getAttribute('id');
            }

            $propertyRangeAttributes['name'] = $attributes['name'];
            $propertyRangeAttributes['propertyId'] = $attributes['propertyId'];
            $propertyRangeRecord = PropertyRangeRecord::find()
                ->where([
                    'from_value_id' => $propertyRangeAttributes['fromValueId'],
                    'to_value_id'   => $propertyRangeAttributes['toValueId'],
                    'name'          => $propertyRangeAttributes['name'],
                    'property_id'   => $propertyRangeAttributes['propertyId'],
                ])
                ->one();
            // Если range не существует, то создаем его и записываем его id в valueId (для property_relation).
            if (is_null($propertyRangeRecord)) {
                $propertyRangeRecord = new PropertyRangeRecord();
                $propertyRangeRecord->setAttributes(self::arrayKeysCamelCase2Underscore($propertyRangeAttributes));

                if ($propertyRangeRecord->save()) {
                    $attributes['valueId'] = $propertyRangeRecord->getPrimaryKey();
                } else {
                    Yii::$app->getDb()->getTransaction()->rollBack();

                    return $propertyRangeRecord;
                }
            } else {
                $attributes['valueId'] = $propertyRangeRecord->getAttribute('id');
            }
        } elseif ($attributes['multiplicityId'] === '3') {
            // Кейс с массивом
            $attributes['valueTableId'] = array_search('property_array', self::VALUE_TABLE_ID);

            foreach ($attributes['values'] as $valueLabel) {
                /** @var ActiveRecord $propertyValueRecord */
                $propertyValueRecord = $attributes['propertyValueRecordName']::find()
                    ->where(['value' => $valueLabel['value'], 'property_id' => $attributes['propertyId']])
                    ->one();
                // Если $value не существует, то создаем его и записываем его id в valueIds (для property_array).
                if (is_null($propertyValueRecord)) {
                    $propertyValueRecord = new $attributes['propertyValueRecordName']();
                    $propertyValueRecord->setAttributes([
                        'property_id' => $attributes['propertyId'],
                        'value'       => $valueLabel['value'],
                    ]);

                    if (isset($valueLabel['label']) && $propertyValueRecord->hasAttribute('label')) {
                        $propertyValueRecord->setAttribute('label', $valueLabel['label']);
                    }

                    if ($propertyValueRecord->save()) {
                        $propertyArrayAttributes['valueIds'][] = $propertyValueRecord->getPrimaryKey();
                    } else {
                        Yii::$app->getDb()->getTransaction()->rollBack();

                        return $propertyValueRecord;
                    }
                } else {
                    $propertyArrayAttributes['valueIds'][] = $propertyValueRecord->getAttribute('id');
                }
            }

            $propertyArrayAttributes['name'] = $attributes['name'];
            $propertyArrayAttributes['propertyId'] = $attributes['propertyId'];
            // Костыль для массивов в PostgreSQL
            $propertyArrayAttributes['valueIds'] = '{' . implode(', ', $propertyArrayAttributes['valueIds']) . '}';
            $propertyArrayRecord = PropertyArrayRecord::find()
                ->where([
                    'name'        => $propertyArrayAttributes['name'],
                    'property_id' => $propertyArrayAttributes['propertyId'],
                    'value_ids'   => $propertyArrayAttributes['valueIds'],
                ])
                ->one();
            // Если array не существует, то создаем его и записываем его id в valueId (для property_relation).
            if (is_null($propertyArrayRecord)) {
                $propertyArrayRecord = new PropertyArrayRecord();
                $propertyArrayRecord->setAttributes(self::arrayKeysCamelCase2Underscore($propertyArrayAttributes));

                if ($propertyArrayRecord->save()) {
                    $attributes['valueId'] = $propertyArrayRecord->getPrimaryKey();
                } else {
                    Yii::$app->getDb()->getTransaction()->rollBack();

                    return $propertyArrayRecord;
                }
            } else {
                $attributes['valueId'] = $propertyArrayRecord->getAttribute('id');
            }
        }

        Yii::$app->getDb()->getTransaction()->commit();

        return $attributes;
    }

    /**
     * Метод определяет название типа характеристики по property id
     * @param int $id
     * @return mixed
     */
    public function getPropertyTypeNameByPropertyId(int $id)
    {
        $propertyTypeId = PropertyRecord::find()->select('property_type_id')->where(['id' => $id])->scalar();

        return PropertyTypeRecord::find()->select(['name'])->where(['id' => $propertyTypeId])->scalar();
    }

    /**
     * @param $propertyValueId
     * @param $propertyId
     * @return bool
     * @throws HttpException
     */
    public function deletePropertyValueByValueId($propertyValueId, $propertyId)
    {
        $propertyRecord = PropertyRecord::findOne($propertyId);
        $propertyTypeName = $propertyRecord->getPropertyType()->select('name')->one()['name'];
        $getValueByTypeName = "get{$propertyTypeName}PropertyValues";
        $deletionRow = $propertyRecord->$getValueByTypeName()->where(['id' => $propertyValueId])->all();

        if (!$deletionRow) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($deletionRow);
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }

        Yii::$app->getDb()->getTransaction()->commit();

        return true;
    }
}