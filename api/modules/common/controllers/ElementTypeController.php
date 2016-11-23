<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 21.07.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class ElementTypeController
 * @package api\modules\common\controllers
 */
class ElementTypeController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\elementType\ElementType';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $defaultActions = parent::actions();
        $currentActions = [
            'viewElementTypeCategory' => [
                'class'       => 'common\extendedStdComponents\ViewElementTypeCategoryAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementTypeClass'    => [
                'class'       => 'common\extendedStdComponents\ViewElementTypeClassAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementTypeVariant'    => [
                'class'       => 'common\extendedStdComponents\ViewElementTypeVariantAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
        $resultActions = array_merge($defaultActions, $currentActions);

        return $resultActions;
    }
}