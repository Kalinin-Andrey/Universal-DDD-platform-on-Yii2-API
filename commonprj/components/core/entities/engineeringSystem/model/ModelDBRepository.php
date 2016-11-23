<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.06.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\model;

use commonprj\components\core\models\ModelRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ModelRepository
 * @package commonprj\components\core\entities\engineeringSystem\model
 */
class ModelDBRepository extends BaseDBRepository
{
    public $activeRecord = 'commonprj\components\core\models\ModelRecord';

    /**
     * @param null $condition
     * @return array
     * @throws InvalidConfigException
     */
    public function find($condition = null)
    {
        preg_match('/(\w+)Repository$/', get_class($this), $match);
        $ucFirst = ucfirst($match[1]);
        $classNameRecord = "commonprj\\components\\core\\models\\{$ucFirst}Record";
        $query = call_user_func("{$classNameRecord}::find");
        if ($condition) {
            if (!ArrayHelper::isAssociative($condition)) {
                // query by primary key
                $primaryKey = call_user_func("{$classNameRecord}::primaryKey");
                if (isset($primaryKey[0])) {
                    $condition = [$primaryKey[0] => $condition];
                } else {
                    throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
                }
            }

            $elementRecords = $query->where($condition)->all();
        } else {
            $elementRecords = $query->all();
        }

        $result = [];

        $lcFirst = lcfirst($match[1]);
        $classNameDomain = "commonprj\\components\\core\\entities\\common\\{$lcFirst}\\{$ucFirst}";
        foreach ($elementRecords as $elementRecord) {
            $result[] = self::instantiateByARAndClassName($elementRecord, $classNameDomain);
        }

        return $result;
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function save(Model $model)
    {
        if (!$modelRecord = ModelRecord::findOne($model->id)) {
            $modelRecord = new ModelRecord();
        }
        $modelRecord->setAttributes(self::arrayKeysCamelCase2Underscore($model->attributes));

        $result = $modelRecord->save();
        if ($result) {
            $model->setAttributes(self::arrayKeysUnderscore2CamelCase($modelRecord->attributes), false);
        } else {
            $model->addErrors($modelRecord->getErrors());
        }

        return $result;
    }

    /**
     * @param int $id
     * @throws HttpException
     */
    public function deleteModelById(int $id)
    {
        if (!$model = ModelRecord::find()->where(['id' => $id])->all()) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($model);
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return ModelRecord::primaryKey();
    }
}