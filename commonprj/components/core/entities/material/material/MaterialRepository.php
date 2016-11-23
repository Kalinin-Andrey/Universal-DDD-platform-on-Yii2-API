<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.08.2016
 */

namespace commonprj\components\core\entities\material\material;

/**
 * Interface MaterialRepository
 * @package commonprj\components\core\entities\material\material
 */
interface MaterialRepository
{
    /**
     * @param $condition
     * @return mixed
     */
    public function findOne($condition);
}
