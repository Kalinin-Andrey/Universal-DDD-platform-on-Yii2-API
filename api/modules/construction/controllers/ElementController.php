<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 04.07.2016
 */

namespace api\modules\construction\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class BracingElementController
 * @package api\modules\construction\controllers
 */
class ElementController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\construction\element\Element';
}