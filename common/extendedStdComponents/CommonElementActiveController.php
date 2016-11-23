<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 29.06.2016
 */

namespace common\extendedStdComponents;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * ActiveController implements a common set of actions for supporting RESTful access to ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `options`: return the allowed HTTP methods
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * To add a new action, either override [[actions()]] by appending a new action class or write a new action method.
 * Make sure you also override [[verbs()]] to properly declare what HTTP methods are allowed by the new action.
 *
 * You should usually override [[checkAccess()]] to check whether the current user has the privilege to perform
 * the specified action against the specified model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CommonElementActiveController extends PlanironActiveController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        $defaultActions = parent::actions();
        $currentActions = [
            'viewElementElementClasses'     => [
                'class'       => 'common\extendedStdComponents\ViewElementElementClassesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementElementTypes'       => [
                'class'       => 'common\extendedStdComponents\ViewElementElementTypesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementChildren'           => [
                'class'       => 'common\extendedStdComponents\ViewElementChildrenAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementHierarchy'          => [
                'class'       => 'common\extendedStdComponents\ViewElementHierarchyAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementInclusions'         => [
                'class'       => 'common\extendedStdComponents\ViewElementInclusionsAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementIsParent'           => [
                'class'       => 'common\extendedStdComponents\ViewElementIsParentAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementParent'             => [
                'class'       => 'common\extendedStdComponents\ViewElementParentAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementRelationGroups'     => [
                'class'       => 'common\extendedStdComponents\ViewElementRelationGroupsAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementRelationClasses'    => [
                'class'       => 'common\extendedStdComponents\ViewElementRelationClassesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementModels'             => [
                'class'       => 'common\extendedStdComponents\ViewElementModelsAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementProperties'         => [
                'class'       => 'common\extendedStdComponents\ViewElementPropertiesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementRootIds'            => [
                'class'       => 'common\extendedStdComponents\ViewElementRootIdsAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementProperty'           => [
                'class'       => 'common\extendedStdComponents\ViewElementPropertyAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementPropertyValue'           => [
                'class'       => 'common\extendedStdComponents\ViewElementPropertyValueAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementsByPropertyValues'           => [
                'class'       => 'common\extendedStdComponents\ViewElementsByPropertyValuesAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElementChild'            => [
                'class'       => 'common\extendedStdComponents\CreateElementChildAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElementInclusion'        => [
                'class'       => 'common\extendedStdComponents\CreateElementInclusionAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElementPropertyRelation' => [
                'class'       => 'common\extendedStdComponents\CreateElementPropertyRelationAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElement2ElementClass'    => [
                'class'       => 'common\extendedStdComponents\CreateElement2ElementClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario'    => $this->createScenario,
            ],
            'createElementModel'    => [
                'class'       => 'common\extendedStdComponents\CreateElementModelAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario'    => $this->createScenario,
            ],
            'updateElementPropertyRelation'    => [
                'class'       => 'common\extendedStdComponents\UpdateElementPropertyRelationAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario'    => $this->createScenario,
            ],
            'deleteElementChild'            => [
                'class'       => 'common\extendedStdComponents\DeleteElementChildAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteElementInclusion'        => [
                'class'       => 'common\extendedStdComponents\DeleteElementInclusionAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteElementPropertyValue'    => [
                'class'       => 'common\extendedStdComponents\DeleteElementPropertyValueAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteElement2ElementClass'    => [
                'class'       => 'common\extendedStdComponents\DeleteElement2ElementClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteElementModel'            => [
                'class'       => 'common\extendedStdComponents\DeleteElementModelAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
        $resultActions = array_merge($defaultActions, $currentActions);

        return $resultActions;
    }
}
