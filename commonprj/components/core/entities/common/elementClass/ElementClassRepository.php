<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */
namespace commonprj\components\core\entities\common\elementClass;

use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\extendedStdComponents\BaseCrudModel;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class ElementClassRepository
 * @package commonprj\components\core\entities\common\elementClass
 */
interface ElementClassRepository
{
    /**
     * Метод возвращает объект контекста к которому принадлежит текущий класс.
     * @param int $elementClassId - Класс чей контекст нужно вернуть.
     * @return ActiveRecord
     */
    public function getContext(int $elementClassId);

    /**
     * @param mixed $elementClassIds
     * @return array
     */
    public function find($elementClassIds = null);

    /**
     * @param $condition
     * @param bool $isRoot
     * @return RelationClass[]
     * @throws HttpException
     */
    public function getRelationClassesById($condition, bool $isRoot);

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord[]
     * @throws HttpException
     */
    public function getPropertiesById($condition);

    /**
     * @param $contextNameAndClassName
     * @return BaseCrudModel
     */
    public function getElementClassByName($contextNameAndClassName);
}