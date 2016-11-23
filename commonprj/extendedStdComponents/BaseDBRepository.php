<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 02.08.2016
 */

namespace commonprj\extendedStdComponents;

use commonprj\components\core\entities\common\element\Element;
use commonprj\components\core\helpers\ClassAndContextHelper;
use commonprj\components\core\models\ElementRecord;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Class BaseDBRepository
 * @package commonprj\extendedStdComponents
 */
class BaseDBRepository
{
    /**
     * Мапинг для колонки owner_table_id в таблице property_relation
     */
    const MULTIPLICITY_ID = [
        'value' => 1,
        'range' => 2,
        'array' => 3,
    ];

    /**
     * Мапинг для колонки owner_table_id в таблице property_relation
     */
    const OWNER_TABLE_ID = [
        'element_type' => '1',
        'element'      => '1',
    ];

    /**
     * Мапинг для колонки value_table_id в таблицах property_relation
     */
    const VALUE_TABLE_ID = [
        1  => 'boolean_property_value',
        2  => 'int_property_value',
        3  => 'bigint_property_value',
        4  => 'float_property_value',
        5  => 'string_property_value',
        6  => 'text_property_value',
        7  => 'date_property_value',
        8  => 'timestamp_property_value',
        9  => 'geolocation_property_value',
        10 => 'list_item_property_value',
        11 => 'bigint_property_value',
        12 => 'property_range',
        13 => 'property_array',
    ];

    const ENTITY_TO_RECORD = [
        'commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValue' => 'commonprj\components\core\models\BooleanPropertyValueRecord',
        'commonprj\components\core\entities\common\dateProperty\DateProperty'                   => 'commonprj\components\core\models\DatePropertyValueRecord',
        'commonprj\components\core\entities\common\element\Element'                             => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\common\elementCategory\ElementCategory'             => 'commonprj\components\core\models\ElementCategoryRecord',
        'commonprj\components\core\entities\common\elementClass\ElementClass'                   => 'commonprj\components\core\models\ElementClassRecord',
        'commonprj\components\core\entities\common\elementType\ElementType'                     => 'commonprj\components\core\models\ElementTypeRecord',
        'commonprj\components\core\entities\common\floatProperty\FloatProperty'                 => 'commonprj\components\core\models\FloatPropertyValueRecord',
        'commonprj\components\core\entities\common\geolocationProperty\GeolocationProperty'     => 'commonprj\components\core\models\GeolocationPropertyValueRecord',
        'commonprj\components\core\entities\common\intProperty\IntProperty'                     => 'commonprj\components\core\models\IntPropertyValueRecord',
        'commonprj\components\core\entities\common\listItemProperty\ListItemProperty'           => 'commonprj\components\core\models\ListItemPropertyValueRecord',
        'commonprj\components\core\entities\common\model\Model'                                 => 'commonprj\components\core\models\ModelRecord',
        'commonprj\components\core\entities\common\property\Property'                           => 'commonprj\components\core\models\PropertyRecord',
        'commonprj\components\core\entities\common\propertyRange\PropertyRange'                 => 'commonprj\components\core\models\PropertyRangeRecord',
        'commonprj\components\core\entities\common\propertyType\PropertyType'                   => 'commonprj\components\core\models\PropertyTypeRecord',
        'commonprj\components\core\entities\common\relationClass\RelationClass'                 => 'commonprj\components\core\models\RelationClassRecord',
        'commonprj\components\core\entities\common\relationGroup\RelationGroup'                 => 'commonprj\components\core\models\RelationGroupRecord',
        'commonprj\components\core\entities\common\stringProperty\StringProperty'               => 'commonprj\components\core\models\StringPropertyValueRecord',
        'commonprj\components\core\entities\common\textProperty\TextProperty'                   => 'commonprj\components\core\models\TextPropertyValueRecord',
        'commonprj\components\core\entities\common\timeStampProperty\TimeStampProperty'         => 'commonprj\components\core\models\TimestampPropertyValueRecord',

        'commonprj\components\core\entities\construction\bracing\Bracing'               => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\bracingElement\BracingElement' => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\construction\Construction'     => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\element\Element'               => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\method\Method'                 => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\process\Process'               => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\tool\Tool'                     => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\construction\work\Work'                     => 'commonprj\components\core\models\ElementRecord',

        'commonprj\components\core\entities\engineeringSystem\accumulator\Accumulator'           => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\conductor\Conductor'               => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\controlElement\ControlElement'     => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\converter\Converter'               => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\covering\Covering'                 => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\element\Element'                   => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\model\Model'                       => 'commonprj\components\core\models\ModelRecord',
        'commonprj\components\core\entities\engineeringSystem\sensor\Sensor'                     => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\subsystem\Subsystem'               => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\engineeringSystem\workingSubstance\WorkingSubstance' => 'commonprj\components\core\models\ElementRecord',

        'commonprj\components\core\entities\material\material\Material'   => 'commonprj\components\core\models\ElementRecord',
        'commonprj\components\core\entities\material\substance\Substance' => 'commonprj\components\core\models\ElementRecord',

        'commonprj\components\core\entities\common\propertyVariant\PropertyVariant' => 'commonprj\components\core\models\PropertyVariantRecord',
        'commonprj\components\core\entities\common\relationVariant\RelationVariant' => 'commonprj\components\core\models\RelationVariantRecord',

        'commonprj\components\core\entities\common\relation\Relation' => 'commonprj\components\core\models\RelationRecord',
    ];

    /**
     * @var
     */
    public $activeRecord;

    /**
     * @param int $valueTableId
     * @return array
     */
    public static function getRecordNameByTableId(int $valueTableId)
    {
        $elementPropertyValueRecordName = false;
        $elementPropertyValueTableName = self::VALUE_TABLE_ID[$valueTableId];
        if ($elementPropertyValueTableName) {
            $elementPropertyValueTableName = BaseInflector::id2camel($elementPropertyValueTableName, '_');
            $elementPropertyValueRecordName = "commonprj\\components\\core\\models\\{$elementPropertyValueTableName}Record";
        }

        return $elementPropertyValueRecordName;
    }

    /**
     * Общий для ядра метод для поиска записи по primary key. Так же возможен поиск по дополнительным условиям.
     * @param int|string|array $condition - Условия посика. Должен содерать primary key, остальные уловия опциональны.
     * Если true - вернет элемент только если он принадлежит обратившемуся по api классу.
     * @return BaseCrudModel - Возвращает объект доменного слоя.
     * @throws HttpException
     */
    public function findOne($condition)
    {
        if (!class_exists($this->activeRecord)) {
            throw new HttpException(500, basename(__FILE__, '.php') . __LINE__);
        }

        if (!empty($condition['with'])) {
            $element = ($this->activeRecord)::find()->where($condition['condition'])->with(explode(',', $condition['with']))->one();
        } else {
            $element = ($this->activeRecord)::find()->where($condition['condition'])->one();
        }

        if (!$element) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $calledEntity = preg_replace('/DBRepository/', '', get_called_class());

        if (new $calledEntity() instanceof Element) {
            $condition['byClass'] = true;
        }

        if (!empty($condition['byClass'])) {
            $className = ClassAndContextHelper::getContextAndClassName(get_called_class())['className'];
            // todo вынести magic number 3 в массив классов?
            if ($className != 'common\Element') {
                $classId = ClassAndContextHelper::getClassId(get_called_class());
                // фильтруем все классы к которым принадлежит данный элемент
                $elementClassRecordArray = $element->getElementClasses()->all();

                // определяем id обратившегося по апи класса
                /* проверяем есть ли среди всех классов к которым принадлежит данный элемент - обратившийся класс, если да
                то возвращаем результат, в противном случае говорим что ничего не нашли*/
                foreach ($elementClassRecordArray as $elementClassRecord) {
                    if ($elementClassRecord['id'] == $classId) {
                        return $this->instantiateByARAndClassName($element, get_called_class());
                    }
                }
                throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
            }
        }

        return $this->instantiateByARAndClassName($element);
    }

    /**
     * Фабрика для создания объекта доменной модели.
     * @param $activeRecord - Данные из БД, которые будут мапиться в объект доменного слоя.
     * @param string $fullClassName - Имя класса доменной модели или ее DB репозитория (включая namespace), который нужно инстанциировать.
     * @return BaseCrudModel - Возвращает объект доменного слоя.
     * @throws HttpException
     */
    public static function instantiateByARAndClassName($activeRecord, string $fullClassName = null)
    {
        $instanceofActiveRecord = $activeRecord instanceof ActiveRecord;

        if (is_null($fullClassName)) {
            if (!$instanceofActiveRecord) {
                throw new HttpException(500, basename(__FILE__, '.php') . __LINE__);
            }

            $entityClassName = array_keys(self::ENTITY_TO_RECORD, get_class($activeRecord));

            if (empty($entityClassName) || count($entityClassName) > 1) {
                /** @var ElementRecord $activeRecord */
                try {
                    $allClasses = $activeRecord->getElementClasses()->all();
                } catch (\Exception $e) {
                    // в последствии логировать
                    return $activeRecord;
                }
                if (count($allClasses) === 1) {
                    $contextName = preg_replace('/\\\\\w+/', '', $allClasses[0]->getAttribute('name'));
                    $className = ucfirst(preg_replace('/\w+\\\\/', '', $allClasses[0]->getAttribute('name')));
                    $entityClassName[0] = "commonprj\\components\\core\\entities\\{$contextName}\\" . lcfirst($className) . "\\$className";
                } else {
                    //todo сделать учет для нескольких классов
                    $contextName = preg_replace('/\\\\\w+/', '', $allClasses[0]->getAttribute('name'));
                    $className = ucfirst(preg_replace('/\w+\\\\/', '', $allClasses[0]->getAttribute('name')));
                    $entityClassName[0] = "commonprj\\components\\core\\entities\\{$contextName}\\" . lcfirst($className) . "\\$className";
                }
            }

            $entityClassName = $entityClassName[0];

        } else {
            $entityClassName = preg_replace('/DBRepository/', '', $fullClassName);
        }

        if (!class_exists($entityClassName)) {
            return $activeRecord;
        }

        /** @var BaseCrudModel $resultElement */
        $resultElement = new $entityClassName();
        // Пока не перевели все свойства в camelcase, потом убрать за ненадобностью.
        if ($instanceofActiveRecord) {
            $resultElement->setAttributes(self::arrayKeysUnderscore2CamelCase($activeRecord->getAttributes()), false);
            /** @var ActiveRecord $activeRecord */
            if (!empty($activeRecord->getRelatedRecords())) {
                foreach ($activeRecord->getRelatedRecords() as $propertyName => $relatedRecordArray) {
                    $resultArray = [];
                    if (is_array($relatedRecordArray)) {
                        foreach ($relatedRecordArray as $relatedRecord) {
                            $resultArray[$relatedRecord['id']] = self::instantiateByARAndClassName($relatedRecord);
                        }
                        $resultElement->$propertyName = $resultArray;
                    } else {
                        if ($relatedRecordArray instanceof ActiveRecord) {
                            $resultElement->$propertyName = self::instantiateByARAndClassName($relatedRecordArray);
                        } else {
                            $resultElement->$propertyName = [];
                        }
                    }
                }
            }
            /** @var ActiveRecord $activeRecord */
            if ($activeRecord->hasErrors()) {
                $resultElement->addErrors($activeRecord->getErrors());
            }
        } else {
            $resultElement->setAttributes(self::arrayKeysUnderscore2CamelCase($activeRecord), false);
        }

        $resultElement->entity = $entityClassName;

        return $resultElement;
    }

    /**
     * Метод конвертирует названия ключей пришедшего массива из underscore в camelCase
     * @param array $attributes
     * @return array
     */
    public static function arrayKeysUnderscore2CamelCase(array $attributes, $excludeKeys = []):array
    {
        $result = [];

        foreach ($attributes as $key => $attribute) {
            if (!in_array($key, $excludeKeys)) {
                $result[lcfirst(BaseInflector::camelize($key))] = $attribute;
            } else {
                $result[$key] = $attribute;
            }
        }

        return $result;
    }

    /**
     * @param $id
     * @param $httpContidion
     * @return BaseCrudModel
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function findModel($httpContidion)
    {
        $activeRecord = self::ENTITY_TO_RECORD[$httpContidion['modelClass']];

        if (!class_exists($activeRecord)) {
            throw new HttpException(500, basename(__FILE__, '.php') . __LINE__);
        }
        /** @var BaseCrudModel $whereParamsModel */
        $whereParamsModel = new $activeRecord();
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $whereParamsModel->setAttributes(self::arrayKeysCamelCase2Underscore($queryParams), false);
        $condition = [];

        if (!empty($queryParams['with'])) {
            $condition['with'] = $queryParams['with'];
        }

        foreach ($whereParamsModel as $key => $value) {
            if (isset($value) && in_array(lcfirst(BaseInflector::camelize($key)), array_keys($queryParams))) {
                $condition['condition'][$key] = $value;
            }
        }

        if (!$whereParamsModel->validate($queryParams)) {
            return $whereParamsModel;
        }
        /** @var BaseCrudModel $modelClass */
        $modelClass = new $httpContidion['modelClass'];
        $keys = $this->primaryKey();

        if (count($keys) > 1) {
            $values = explode(',', $httpContidion['condition']['id']);

            if (count($keys) === count($values)) {
                $condition['condition']['id'] = array_combine($keys, $values);
            }
        }

        $model = $modelClass->findOne($condition);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: {$httpContidion['condition']['id']}");
        }
    }

    /**
     * Метод конвертирует названия ключей пришедшего массива из camelCase в underscore
     * @param array $attributes
     * @return array
     */
    public static function arrayKeysCamelCase2Underscore(array $attributes, $excludeKeys = []):array
    {
        $result = [];

        foreach ($attributes as $key => $attribute) {
            if (!in_array($key, $excludeKeys)) {
                $result[BaseInflector::underscore($key)] = $attribute;
            } else {
                $result[$key] = $attribute;
            }
        }

        return $result;
    }
}