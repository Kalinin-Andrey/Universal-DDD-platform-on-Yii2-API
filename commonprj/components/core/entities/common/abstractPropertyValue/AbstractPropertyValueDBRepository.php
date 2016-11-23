<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.08.2016
 */

namespace commonprj\components\core\entities\common\abstractPropertyValue;

use commonprj\components\core\models\PropertyRecord;
use commonprj\components\core\models\PropertyTypeRecord;
use commonprj\extendedStdComponents\BaseDBRepository;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class AbstractPropertyValueRepository
 * @package commonprj\components\core\entities\common\abstractPropertyValue
 */
class AbstractPropertyValueDBRepository extends BaseDBRepository
{
    /**
     * @param $attributes
     * @return bool
     * @throws HttpException
     */
    public static function consistentAbstractPropertyValueWithId($attributes)
    {
        $attributes = AbstractPropertyValueDBRepository::addPropertyKeysToAbstractValuePropertiesArray($attributes);
        /** @var ActiveRecord $propertyValueRecordName */
        $propertyValueRecordName = $attributes['propertyValueRecordName'];
        $validId = $propertyValueRecordName::find()->where(['id' => $attributes['id']])->scalar();

        if ($validId === false) {
            throw new HttpException(400, 'Wrong property value id given. ' . basename(__FILE__, '.php') . __LINE__);
        }

        self::validateAbstractPropertyValue($attributes);

        if (!in_array($attributes['multiplicityId'], self::MULTIPLICITY_ID)) {
            throw new HttpException(404, 'Wrong multiplicityId given. ' . basename(__FILE__, '.php') . __LINE__);
        }

        return $attributes;
    }

    /**
     * @param $attributes
     * @return bool
     * @throws HttpException
     */
    public static function validateAbstractPropertyValue($attributes)
    {
        $abstractPropertyValue = self::instantiateByARAndClassName($attributes, 'commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValue');

        if (!$abstractPropertyValue->validate()) {
            $firstErrors = $abstractPropertyValue->getFirstErrors();
            $firstKey = array_keys($firstErrors)[0];
            $errorMessage = reset($firstErrors);
            throw new HttpException(400, "attribute: {$firstKey}. {$errorMessage} " . basename(__FILE__, '.php') . __LINE__);
        }

        return true;
    }

    /**
     * @param array $attributes
     * @return array
     */
    public static function addPropertyKeysToAbstractValuePropertiesArray(array $attributes):array
    {
        $attributes['propertyTypeId'] = PropertyRecord::find()
            ->select('property_type_id')
            ->where(['id' => $attributes['propertyId']])
            ->scalar();

        // костыль для Image
        if ($attributes['propertyTypeId'] === 11) {
            $attributes['propertyTypeId'] = 3;
        }

        $attributes['propertyTypeName'] = PropertyTypeRecord::find()
            ->select(['name'])
            ->where(['id' => $attributes['propertyTypeId']])
            ->scalar();
        /**
         * Определяем название ActiveRecord модели по пришедшему property id
         * @var ActiveRecord $propertyValueRecordName
         */
        $propertyValueRecordName = 'commonprj\components\core\models\\' . $attributes['propertyTypeName'] . 'PropertyValueRecord';
        $attributes['propertyValueRecordName'] = $propertyValueRecordName;

        return $attributes;
    }

    /**
     * Метод проверяет корректность переданных параметров для создания нового property_relation с несуществующим value_id.
     * @param $attributes
     * @return bool
     * @throws HttpException
     */
    public static function consistentAbstractPropertyValueWithoutId($attributes)
    {
        self::validateAbstractPropertyValue($attributes);
        $attributes = AbstractPropertyValueDBRepository::addPropertyKeysToAbstractValuePropertiesArray($attributes);

        if (empty($attributes['propertyTypeId']) || $attributes['propertyTypeId'] === false) {
            throw new HttpException(400, 'Invalid propertyId given. ' . basename(__FILE__, '.php') . __LINE__);
        }

        switch ($attributes['multiplicityId']) {
            case 1:
                if (!isset($attributes['value']) || $attributes['value'] == '') {
                    throw new HttpException(400, 'Invalid value given. ' . basename(__FILE__, '.php') . __LINE__);
                }

                if ($attributes['propertyTypeId'] === 10) {
                    if (empty($attributes['label']) && $attributes['value'] != 0) {
                        throw new HttpException(400, 'label can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
                    }
                }
                break;
            case 2:
                if (empty($attributes['name'])) {
                    throw new HttpException(400, 'name can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
                }

                if (empty($attributes['fromValue']) && $attributes['value'] != 0) {
                    throw new HttpException(400, 'fromValue can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
                }

                if (empty($attributes['toValue']) && $attributes['value'] != 0) {
                    throw new HttpException(400, 'toValue can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
                }

                if (!is_numeric($attributes['fromValue']) || !is_numeric($attributes['toValue'])) {
                    $attributes['fromValue'] = strtotime($attributes['fromValue']);
                    $attributes['toValue'] = strtotime($attributes['toValue']);
                    if (!$attributes['fromValue'] || !$attributes['toValue']) {
                        throw new HttpException(400, 'Invalid fromValue or toValue given. ' . basename(__FILE__, '.php') . __LINE__);
                    }
                }

                // Проверка на пустые toValue, fromValue или name
                if (
                    $attributes['toValue'] == $attributes['fromValue'] ||
                    $attributes['toValue'] < $attributes['fromValue']
                ) {
                    throw new HttpException(400, 'Invalid fromValue or toValue given. ' . basename(__FILE__, '.php') . __LINE__);
                }
                break;
            case 3:
                if (empty($attributes['name'])) {
                    throw new HttpException(400, 'name can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
                }

                if (empty($attributes['values'])) {
                    throw new HttpException(400, 'values can\'t be empty. ' . basename(__FILE__, '.php') . __LINE__);
                }
                break;
            default:
                throw new HttpException(404, 'Wrong multiplicityId given. ' . basename(__FILE__, '.php') . __LINE__);
        }

        return $attributes;
    }
}