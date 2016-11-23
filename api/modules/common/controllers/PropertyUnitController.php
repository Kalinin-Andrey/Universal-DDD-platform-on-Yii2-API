<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 26.08.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class PropertyUnitController
 * @package api\modules\common\controllers
 */
class PropertyUnitController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\models\PropertyUnitRecord';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $parentActions = parent::actions();
        $currentActions = [
            'viewPropertiesByUnit' => [
                'class' => 'common\extendedStdComponents\ViewPropertiesByUnitAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];

        return array_merge($parentActions, $currentActions);
    }
}