<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 22.08.2016
 */

namespace commonprj\components\core\entities\material\substance;
use commonprj\components\core\entities\common\element\ElementDBRepository;

/**
 * Class SubstanceDBRepository
 * @package commonprj\components\core\entities\material\substance
 */
class SubstanceDBRepository extends ElementDBRepository
{
    public $activeRecord = 'commonprj\components\core\models\ElementRecord';
}