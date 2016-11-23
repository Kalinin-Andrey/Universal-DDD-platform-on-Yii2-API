<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 04.08.2016
 */

namespace api\modules\construction\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class ConstructionController
 * @package api\modules\construction\controllers
 */
class ConstructionController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\construction\construction\Construction';
}