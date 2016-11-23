<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\common\property;

use commonprj\components\core\helpers\ClassAndContextHelper;
use commonprj\components\core\models\Property2elementClassRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class Property
 * @package commonprj\components\core\entities\common\property
 */
class Property extends BaseCrudModel
{
    public $id;
    public $propertyTypeId;
    public $name;
    public $sysname;
    public $isSpecific = 0;
    public $propertyUnitId;
    public $description;
    public $propertyValues;
    public $elements;
    public $elementClasses;
    public $elementTypes;
    public $propertyUnit;
    public $entity;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->propertyRepository;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer', 'skipOnEmpty' => true],
            [['description'], 'string'],
            [['isSpecific'], 'boolean'],
            [['propertyTypeId', 'propertyUnitId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 50],
        ];
    }

    /**
     * Сохранение инстанса объекта в БД
     */
    public function save()
    {
        return $this->repository->save($this);
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        $this->repository->deletePropertyById($this->id);

        return true;
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    public function update()
    {
        return $this->save();
    }

    /**
     * @inheritdoc
     */
    public function findOne($condition)
    {
        return $this->repository->findOne($condition);
    }

    /**
     * @param bool $condition
     * @return mixed|void
     * @throws HttpException
     */
    public function find($condition = null)
    {
        if ($condition['byClassId']) {
            $elementClassId = ClassAndContextHelper::getClassId(get_called_class());
            $elementsByClass = Property2elementClassRecord::find()
                ->where(['element_class_id' => $elementClassId])
                ->asArray()
                ->all();
            $elementIds = [];

            if (empty($elementsByClass)) {
                return [];
            } else {
                foreach ($elementsByClass as $element) {
                    $elementIds[] = $element['element_id'];
                }

                $condition['condition']['id'] = $elementIds;
                $result = [];
                $properties = $this->repository->find($condition['condition']);

                foreach ($properties as $property) {
                    $result[$property['id']] = $property;
                }

                return $result;
            }
        } else {
            $result = [];
            $properties = $this->repository->find($condition['condition']);

            foreach ($properties as $property) {
                $result[$property['id']] = $property;
            }

            return $result;
        }
    }

    /**
     * Метод возвращает классы к которым принадлежит текущее свойство.
     * @return \commonprj\components\core\models\ElementClassRecord[]
     * @throws HttpException
     */
    public function getPropertyClasses()
    {
        return $this->repository->getPropertyClassesById($this->id);
    }

    /**
     * @return array
     */
    public function getTypeById()
    {
        return $this->repository->getPropertyTypeNameByPropertyId($this->id);
    }

    /**
     * Метод возвращает массив значений с групировкой по multiplicityId
     * @param null $multiplicityId
     * @return array
     */
    public function getValues($multiplicityId = null)
    {
        return $this->repository->getValues($this->id, $multiplicityId);
    }

    /**
     * @param $elementClassId
     * @throws HttpException
     */
    public function deletePropertyClass($elementClassId)
    {
        $this->repository->deletePropertyClassByClassId($elementClassId, $this->id);
    }

    /**
     * @param $attributes
     * @return \commonprj\components\core\models\PropertyRelationRecord
     */
    public function createElementPropertyRelation($attributes)
    {
        return $this->repository->createElementPropertyRelation($attributes);
    }

    /**
     * @return array|\yii\db\ActiveRecord
     */
    public function getPropertyUnit()
    {
        return $this->repository->getPropertyUnitById($this->id);
    }

    /**
     * @param $propertyValueId
     * @return bool
     */
    public function deletePropertyValue($propertyValueId)
    {
        return $this->repository->deletePropertyValueByValueId($propertyValueId, $this->id);
    }
}
