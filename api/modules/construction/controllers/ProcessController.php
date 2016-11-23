<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 04.08.2016
 */

namespace api\modules\construction\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class ProcessController
 * @package api\modules\construction\controllers
 */
class ProcessController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\construction\process\Process';
}