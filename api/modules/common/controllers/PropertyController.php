<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class PropertyController
 * @package api\modules\common\controllers
 */
class PropertyController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\property\Property';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $defaultActions = parent::actions();
        $currentActions = [
            'viewProperty2elementClasses' => [
                'class'       => 'common\extendedStdComponents\ViewProperty2elementClassesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewPropertyValues' => [
                'class'       => 'common\extendedStdComponents\ViewPropertyValuesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewPropertyUnit' => [
                'class'       => 'common\extendedStdComponents\ViewPropertyUnitAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createProperty2elementClass' => [
                'class'       => 'common\extendedStdComponents\CreateProperty2elementClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteProperty2elementClass' => [
                'class'       => 'common\extendedStdComponents\DeletePropertyClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deletePropertyValue' => [
                'class'       => 'common\extendedStdComponents\DeletePropertyValueAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'updateProperty2elementClass' => [
                'class'       => 'common\extendedStdComponents\UpdateProperty2elementClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario'    => $this->updateScenario,
            ],
        ];
        $resultActions = array_merge($defaultActions, $currentActions);

        return $resultActions;
    }
}