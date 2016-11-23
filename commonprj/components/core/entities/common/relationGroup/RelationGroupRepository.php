<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 23.09.2016
 */
namespace commonprj\components\core\entities\common\relationGroup;

use commonprj\components\core\entities\common\relationClass\RelationClass;
use yii\web\HttpException;

/**
 * Class RelationGroupRepository
 * @package commonprj\components\core\entities\common\relationGroup
 */
interface RelationGroupRepository
{
    /**
     * @param $condition
     * @return array
     */
    public function find($condition);

    /**
     * @param array|int|string $condition
     * @return RelationGroup
     * @throws HttpException
     */
    public function findOne($condition);

    /**
     * @param $id
     * @return RelationClass
     */
    public function getRelationClass($id);
}