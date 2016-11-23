<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.08.2016
 */
namespace commonprj\components\core\entities\common\model;

/**
 * Class Model
 * @package commonprj\components\core\entities\common\model
 */
interface ModelRepository
{
    /**
     * @return \commonprj\components\core\entities\common\element\Element
     */
    public function getElement();

    public function delete();
}
