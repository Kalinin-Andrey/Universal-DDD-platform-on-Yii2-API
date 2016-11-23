<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 29.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class ElementClassController
 * @package api\modules\common\controllers
 */
class ElementClassController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\elementClass\ElementClass';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index'                            => [
                'class'       => 'common\extendedStdComponents\IndexAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view'                             => [
                'class'       => 'common\extendedStdComponents\ViewAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementClass2RelationClasses'    => [
                'class'       => 'common\extendedStdComponents\ViewElementClass2RelationClassesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementClassProperties'       => [
                'class'       => 'common\extendedStdComponents\ViewElementClassPropertiesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementClassByName'               => [
                'class'       => 'common\extendedStdComponents\ViewElementClassByNameAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementClassContext' => [
                'class'       => 'common\extendedStdComponents\ViewElementClassContextAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'update'                           => [
                'class'       => 'common\extendedStdComponents\UpdateAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario'    => $this->updateScenario,
            ],
            'delete'                           => [
                'class'       => 'common\extendedStdComponents\DeleteAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteElementClass2RelationClass'                           => [
                'class'       => 'common\extendedStdComponents\DeleteElementClass2RelationClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElementClass2RelationClass' => [
                'class'       => 'common\extendedStdComponents\CreateElementClass2RelationClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElementClass2Property' => [
                'class'       => 'common\extendedStdComponents\CreateElementClass2PropertyAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }
}