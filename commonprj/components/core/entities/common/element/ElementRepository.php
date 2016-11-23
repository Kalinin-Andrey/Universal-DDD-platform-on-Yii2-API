<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 19.09.2016
 */
namespace commonprj\components\core\entities\common\element;

use commonprj\components\core\entities\common\elementClass\ElementClass;
use commonprj\components\core\entities\common\elementType\ElementType;
use commonprj\components\core\entities\common\model\Model;
use commonprj\components\core\entities\common\property\Property;
use commonprj\components\core\entities\common\relationGroup\RelationGroup;
use commonprj\extendedStdComponents\BaseCrudModel;
use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 * Class ElementRepository
 * @package commonprj\components\core\entities\common\element
 */
interface ElementRepository
{
    /**
     * Возвращает дочерние элементы по id запрошенного элемента и id реляционной группы.
     * @param int $elementId - ID элемента, дочерние элементы которого надо вернуть.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая иерархия.
     * @param bool $recursion - Если true, то вернет все дерево иерархии, если false, то возвращает только прямых
     * потомков элемента. По умолчанию false.
     * @return Element[]
     * @throws HttpException
     */
    public function getChildren(int $elementId, int $relationGroupId, bool $recursion = false);

    /**
     * Возвращает инклюзии по id запрошенного элемента и id реляционной группы.
     * @param Element $element - Элемент инклюзии которого надо вернуть.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая связь.
     * @return Element[]
     * @throws HttpException
     */
    public function getInclusions(Element $element, int $relationGroupId);

    /**
     * Возвращает модели, связанные с id запрошенного элемента.
     * @param int $elementId - Элемент id, чьи модели надо вернуть.
     * @return Model[]
     * @throws HttpException
     */
    public function getModels(int $elementId);

    /**
     * Возвращает свойства, связанные с id запрошенного элемента.
     * @param int $elementId - Элемент id, чьи свойства надо вернуть.
     * @return Property[]
     * @throws HttpException
     */
    public function getProperties(int $elementId);

    /**
     * Возвращает массив объектов обратившегося класса.
     * @param array $condition - Условия для SQL WHERE Clause. По умолчанию null.
     * @return \commonprj\extendedStdComponents\BaseCrudModel[]
     * @throws InvalidConfigException
     */
    public function find(array $condition = null);

    /**
     * Возвращает массив классов, связанных с id запрошенного элемента.
     * @param int $elementId - Элемент id, чьи классы надо вернуть.
     * @return ElementClass[]
     */
    public function getClassesByElementId(int $elementId);

    /**
     * Возвращает массив реляционных групп, связанных с id запрошенного элемента.
     * @param int $elementId - id Элемента, чей список реляционных групп надо вернуть.
     * @return RelationGroup[]
     * @throws HttpException
     */
    public function getRelationGroups(int $elementId);

    /**
     * Метод возвращает массив элементов,
     * из которых состоит дерево иерархии в котором запрошенный id элемента является корнем.
     * @param array|int $element - id Элемента, иерархию которого надо вернуть.
     * @param int $relationGroupId
     * @return array
     */
    public function getHierarchyRecursion($element, int $relationGroupId);

    /**
     * Возвращает родительский элемент переданного id.
     * @param int $id - id элемента по которому запрашивается родитель.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая иерархия.
     * @return Element
     * @throws HttpException
     */
    public function getParent(int $id, int $relationGroupId);

    /**
     * Определяет является ли запрошенный по id элемент родителем.
     * @param int $id - Id элемента, родительство которого надо определить.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая иерархия.
     * @return array
     * @throws HttpException
     */
    public function isParent(int $id, int $relationGroupId);

    /**
     * Возвращает корневой элемент реляции в которой участвует переданный id элемента.
     * @param int $elementId - id элемента по которому запрашивается корневой элемент.
     * @param int $relationGroupId - Обязательный параметр. relation_group_id к которой относится искомая связь.
     * @return Element - Возвращает объект Element или пустой массив.
     * @throws HttpException
     */
    public function getRoot(int $elementId, int $relationGroupId);

    /**
     * Возвращает массив типов элемента, к которым относится переданный id элемента.
     * @param int $id - id элемента по которому запрашивается список типов элемента.
     * @return ElementType[]
     */
    public function getElementTypesByElementId(int $id);

    /**
     * Возвращает объект Property (без значения) по заданному $propertyId если он связан с заданным $elementId
     * @param $elementId - id элемента по которому запрашивается свойство.
     * @param $propertyId - id свойства которое запрашивается.
     * @param array $condition - параметр для передачи дополнительных условий
     * @return BaseCrudModel
     */
    public function getProperty($elementId, $propertyId, array $condition = []);

    /**
     * Возвращает объект Property (со значением) по заданному $propertyId если он связан с заданным $elementId
     * @param $elementId - id элемента по которому запрашивается свойство.
     * @param $propertyId - id свойства которое запрашивается.
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function getAbstractPropertyValueByElementAndPropertyId($elementId, $propertyId);

    /**
     * Общий для ядра метод для поиска записи по primary key. Так же возможен поиск по дополнительным условиям.
     * @param int|string|array $condition - Условия посика. Должен содерать primary key, остальные уловия опциональны.
     * Если true - вернет элемент только если он принадлежит обратившемуся по api классу.
     * @return BaseCrudModel - Возвращает объект доменного слоя.
     * @throws HttpException
     */
    public function findOne($condition);
}