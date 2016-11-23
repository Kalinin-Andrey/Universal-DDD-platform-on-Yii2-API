<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 08.08.2016
 */

namespace api\modules\engineeringSystem\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class SensorController
 * @package api\modules\engineeringSystem\controllers
 */
class SensorController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\engineeringSystem\sensor\Sensor';
}