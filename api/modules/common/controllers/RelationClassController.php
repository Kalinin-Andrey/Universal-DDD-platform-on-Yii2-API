<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 15.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class RelationClassController
 * @package api\modules\common\controllers
 */
class RelationClassController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\relationClass\RelationClass';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $defaultActions = parent::actions();
        $currentActions = [
            'viewRelationClassGroups'          => [
                'class'       => 'common\extendedStdComponents\ViewRelationClassGroupsAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewRelationClass2ElementClasses' => [
                'class'       => 'common\extendedStdComponents\ViewRelationClass2ElementClassesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteRelationClass2ElementClass' => [
                'class'       => 'common\extendedStdComponents\DeleteRelationClass2ElementClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createRelationClass2ElementClass' => [
                'class'       => 'common\extendedStdComponents\CreateRelationClass2ElementClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
        $resultActions = array_merge($defaultActions, $currentActions);

        return $resultActions;
    }
}