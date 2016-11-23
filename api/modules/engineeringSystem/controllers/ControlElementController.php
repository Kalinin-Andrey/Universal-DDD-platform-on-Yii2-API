<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 08.08.2016
 */

namespace api\modules\engineeringSystem\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class ControlElementController
 * @package api\modules\engineeringSystem\controllers
 */
class ControlElementController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\engineeringSystem\controlElement\ControlElement';
}