<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 25.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class ElementCategoryController
 * @package api\modules\common\controllers
 */
class ElementCategoryController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\elementCategory\ElementCategory';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $defaultActions = parent::actions();
        $currentActions = [
            'viewElementCategoryChildren'  => [
                'class'       => 'common\extendedStdComponents\ViewElementCategoryChildrenAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementCategoryParent'    => [
                'class'       => 'common\extendedStdComponents\ViewElementCategoryParentAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementCategoryIsParent'  => [
                'class'       => 'common\extendedStdComponents\ViewElementCategoryIsParentAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementCategoryRoot'      => [
                'class'       => 'common\extendedStdComponents\ViewElementCategoryRootAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementCategoryRoots'     => [
                'class'       => 'common\extendedStdComponents\ViewElementCategoryRootsAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementCategoryHierarchy' => [
                'class'       => 'common\extendedStdComponents\ViewElementCategoryHierarchyAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
        $resultActions = array_merge($defaultActions, $currentActions);

        return $resultActions;
    }
}