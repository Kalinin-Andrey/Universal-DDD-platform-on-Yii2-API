<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 05.08.2016
 */

namespace api\modules\engineeringSystem\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class SubsystemController
 * @package api\modules\engineeringSystem\controllers
 */
class SubsystemController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\engineeringSystem\subsystem\Subsystem';
}