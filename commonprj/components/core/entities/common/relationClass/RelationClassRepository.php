<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */
namespace commonprj\components\core\entities\common\relationClass;

use yii\web\HttpException;

/**
 * Class RelationClassReposotory
 * @package commonprj\components\core\entities\common\relationClass
 */
interface RelationClassRepository
{
    /**
     * @param $condition
     * @param $isRoot
     * @return array
     * @throws HttpException
     */
    public function getElementClassesById($condition, $isRoot);

    /**
     * @param $condition
     * @return RelationClass
     */
    public function findOne($condition);

    /**
     * @param mixed $condition
     * @return array
     */
    public function find($condition = null);

    /**
     * @param int $id
     * @return array
     */
    public function getRelationGroups(int $id);
}