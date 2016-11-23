<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 08.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class RelationGroupController
 * @package api\modules\common\controllers
 */
class RelationGroupController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\relationGroup\RelationGroup';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $defaultActions = parent::actions();
        $currentActions = [
            'viewRelationGroupRelationClass' => [
                'class'       => 'common\extendedStdComponents\ViewRelationGroupRelationClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
        $resultActions = array_merge($defaultActions, $currentActions);

        return $resultActions;
    }
}