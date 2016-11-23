<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 29.06.2016
 */

namespace common\extendedStdComponents;

use commonprj\components\core\helpers\ClassAndContextHelper;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use commonprj\extendedStdComponents\PlanironAction;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use yii\web\BadRequestHttpException;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class IndexAction extends PlanironAction
{
    const META_DATA = [
        'variantTypeId',
    ];

    /**
     * @return BaseCrudModel|\yii\db\ActiveRecord[]
     * @throws BadRequestHttpException
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        if ($this->modelClass === 'commonprj\components\core\entities\common\variant\Variant') {
            $variantTypeId = Yii::$app->getRequest()->getQueryParam('variantTypeId');

            switch ($variantTypeId) {
                case 1:
                    $this->modelClass = 'commonprj\components\core\entities\common\propertyVariant\PropertyVariant';
                    break;
                case 2:
                    $this->modelClass = 'commonprj\components\core\entities\common\relationVariant\RelationVariant';
                    break;
            }
        }

        /** @var BaseCrudModel $modelClass */
        $modelClass = new $this->modelClass();
        $isRecord = strpos($this->modelClass, 'Record');
        $whereParams = [];

        if (!$isRecord) {
            $modelClass = new $this->modelClass();
            $queryParams = Yii::$app->getRequest()->getQueryParams();
            $modelClass->setAttributes($queryParams, false);

            foreach ($modelClass as $key => $value) {
                if (isset($value) && in_array($key, array_keys($queryParams)) && !in_array($key, self::META_DATA)) {
                    $whereParams[$key] = $value;
                }
            }

            foreach ($queryParams as $httpkey => &$queryParam) {
                if ($queryParam === 'NULL' || $queryParam === 'null') {
                    foreach ($modelClass as $key => $value) {
                        if ($key === $httpkey || $key === BaseInflector::underscore($httpkey)) {
                            $whereParams[$key] = null;
                        }
                    }
                }

                if ($httpkey === 'with') {
                    $condition['with'] = $queryParam;
                }
            }

            if (!$modelClass->validate()) {
                return $modelClass;
            }
        }

        $condition['condition'] = BaseDBRepository::arrayKeysCamelCase2Underscore($whereParams);

        if (is_subclass_of($modelClass, 'commonprj\components\core\entities\common\element\Element')) {
            $classId = ClassAndContextHelper::getClassId($this->modelClass);
            $condition['byClassId'] = $classId;

            return $modelClass->find($condition);
        } else {
            if ($isRecord) {
                /** @var ActiveRecord $modelClass */
                $records = $modelClass::find()->all();
                $result = [];

                foreach ($records as $record) {
                    $result[$record['id']] = $record;
                }

                return $result;
            } else {
                $condition['byClassId'] = false;

                return $modelClass->find($condition);
            }
        }
    }
}
