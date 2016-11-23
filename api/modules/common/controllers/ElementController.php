<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 19.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class ElementController
 * @package api\modules\common\controllers
 */
class ElementController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\element\Element';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class'       => 'common\extendedStdComponents\IndexAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view'  => [
                'class'       => 'common\extendedStdComponents\ViewAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }
}