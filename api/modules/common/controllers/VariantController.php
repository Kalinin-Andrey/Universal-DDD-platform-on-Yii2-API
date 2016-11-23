<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class VariantController
 * @package api\modules\common\controllers
 */
class VariantController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\variant\Variant';

    public function actions()
    {
        $parentAction = parent::actions();
        $currentAction = [
            'viewVariantElementType' => [
                'class' => 'common\extendedStdComponents\ViewVariantElementTypeAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewVariantProperty' => [
                'class' => 'common\extendedStdComponents\ViewVariantPropertyAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewVariantPropertyValue' => [
                'class' => 'common\extendedStdComponents\ViewVariantPropertyValueAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewVariantRelatedElement' => [
                'class' => 'common\extendedStdComponents\ViewVariantRelatedElementAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewVariantRelationClass' => [
                'class' => 'common\extendedStdComponents\ViewVariantRelationClassAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewVariantSchemaElement' => [
                'class' => 'common\extendedStdComponents\ViewVariantSchemaElementAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];

        return array_merge($parentAction, $currentAction);
    }
}