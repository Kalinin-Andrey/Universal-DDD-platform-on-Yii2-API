<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 06.09.2016
 */

namespace commonprj\components\core\entities\common\propertyRange;


use commonprj\extendedStdComponents\BaseCrudModel;
use yii\web\HttpException;

/**
 * Class PropertyRangeServiceRepository
 * @package commonprj\components\core\entities\common\propertyRange
 */
class PropertyRangeServiceRepository implements PropertyRangeRepository
{

    /**
     * @inheritdoc
     */
    public function findOne($condition)
    {
        // TODO: Implement findOne() method.
    }

    /**
     * @return mixed
     */
    function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function update()
    {
        // TODO: Implement update() method.
    }

    /**
     * @param bool $condition
     * @return BaseCrudModel
     */
    public function find($condition = false)
    {
        // TODO: Implement find() method.
    }

    /**
     * @return BaseCrudModel
     */
    public function save()
    {
        // TODO: Implement save() method.
    }
}