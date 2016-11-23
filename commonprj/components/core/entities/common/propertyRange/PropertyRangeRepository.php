<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.08.2016
 */
namespace commonprj\components\core\entities\common\propertyRange;

use commonprj\extendedStdComponents\BaseCrudModel;
use yii\web\HttpException;

/**
 * Class PropertyRange
 * @package commonprj\components\core\entities\common\propertyRange
 */
interface PropertyRangeRepository
{
    /**
     * @inheritdoc
     */
    public function findOne($condition);

    /**
     * @return mixed
     */
    function delete();

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function update();

    /**
     * @param bool $condition
     * @return BaseCrudModel
     */
    public function find($condition = false);

    /**
     * @return BaseCrudModel
     */
    public function save();
}