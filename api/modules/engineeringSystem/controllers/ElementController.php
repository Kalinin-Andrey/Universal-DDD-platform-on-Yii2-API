<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 05.08.2016
 */

namespace api\modules\engineeringSystem\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class ElementController
 * @package api\modules\engineeringSystem\controllers
 */
class ElementController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\engineeringSystem\element\Element';
}