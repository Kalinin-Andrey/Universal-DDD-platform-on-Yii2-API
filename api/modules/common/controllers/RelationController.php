<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.10.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

class RelationController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\relation\Relation';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return  [
            'index' => [
                'class' => 'common\extendedStdComponents\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'common\extendedStdComponents\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewRelationChilds' => [
                'class' => 'common\extendedStdComponents\ViewRelationChildsAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }
}