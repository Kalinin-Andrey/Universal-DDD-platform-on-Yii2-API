<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 13.09.2016
 */

namespace api\modules\common\controllers;

use commonprj\extendedStdComponents\PlanironActiveController;

/**
 * Class SchemaElementController
 * @package api\modules\common\controllers
 */
class SchemaElementController extends PlanironActiveController
{
    public $modelClass = 'commonprj\components\core\entities\common\element\Element';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'viewElementsBySchemaId' => [
                'class' => 'common\extendedStdComponents\ViewElementsBySchemaIdAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'viewElementVariantsBySchemaId' => [
                'class' => 'common\extendedStdComponents\ViewElementVariantsBySchemaIdAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'createElementsBySchemaId' => [
                'class' => 'common\extendedStdComponents\CreateElementsBySchemaIdAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteElementsBySchemaId' => [
                'class' => 'common\extendedStdComponents\DeleteElementsBySchemaIdAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'deleteVariantsBySchemaId' => [
                'class' => 'common\extendedStdComponents\DeleteVariantsBySchemaIdAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }
}