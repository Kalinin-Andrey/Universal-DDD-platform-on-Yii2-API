<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 10.08.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class PropertyTypeController
 * @package api\modules\common\controllers
 */
class PropertyTypeController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\models\PropertyTypeRecord';

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
        ];
    }
}