<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 04.07.2016
 */

namespace api\modules\material\controllers;

use common\extendedStdComponents\CommonElementActiveController;

/**
 * Class CommonController
 * @package api\modules\common\controllers
 */
class MaterialController extends CommonElementActiveController
{
    public $modelClass = 'commonprj\components\core\entities\material\material\Material';
}