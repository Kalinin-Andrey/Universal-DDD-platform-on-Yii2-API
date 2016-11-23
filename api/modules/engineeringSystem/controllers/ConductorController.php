<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 08.08.2016
 */

namespace api\modules\engineeringSystem\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class ConductorController
 * @package api\modules\engineeringSystem\controllers
 */
class ConductorController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\engineeringSystem\conductor\Conductor';
}