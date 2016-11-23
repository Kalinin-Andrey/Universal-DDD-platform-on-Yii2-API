<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.08.2016
 */

namespace commonprj\components\core\entities\common\relationGroup;

use commonprj\components\core\entities\common\relationClass\RelationClass;
use commonprj\components\core\models\RelationGroupRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class RelationGroupRepository
 * @package commonprj\components\core\entities\common\relationGroup
 */
class RelationGroupDBRepository extends BaseDBRepository implements RelationGroupRepository
{
    public $activeRecord = 'commonprj\components\core\models\RelationGroupRecord';

    /**
     * @param $condition
     * @return array
     */
    public function find($condition)
    {
        if (ArrayHelper::isAssociative($condition)) {
            $relationRecords = RelationGroupRecord::find()->where($condition)->all();
        } else {
            $relationRecords = RelationGroupRecord::find()->all();
        }

        $result = [];
        foreach ($relationRecords as $relationRecord) {
            $result[$relationRecord['id']] = self::instantiateByARAndClassName($relationRecord);
        }

        return $result;
    }

    /**
     * @param $id
     * @return RelationClass
     */
    public function getRelationClass($id)
    {
        $relationGroupRecord = RelationGroupRecord::findOne($id);
        $relationClass = $relationGroupRecord->getRelationClass()->one();
        $resultRelationGroup = self::instantiateByARAndClassName($relationClass, 'commonprj\components\core\entities\common\relationClass\RelationClass');

        return $resultRelationGroup;
    }

    /**
     * @param RelationGroup $relationGroup
     * @return bool
     */
    public function save(RelationGroup $relationGroup)
    {
        if (!$relationGroupRecord = RelationGroupRecord::findOne($relationGroup->id)) {
            $relationGroupRecord = new RelationGroupRecord();
        }

        $relationGroupRecord->setAttributes(self::arrayKeysCamelCase2Underscore($relationGroup->attributes));
        $result = $relationGroupRecord->save();

        if ($result) {
            $relationGroup->setAttributes(self::arrayKeysUnderscore2CamelCase($relationGroupRecord->attributes), false);
        } else {
            $relationGroup->addErrors($relationGroupRecord->getErrors());
        }

        return $result;
    }

    /**
     * @param $id
     * @return bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function deleteRelationGroupById($id)
    {
        $model = RelationGroupRecord::findOne($id);

        if ($model === null) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($model->getRelations()->all());
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }

        if (!$model->delete()) {
            Yii::$app->getDb()->getTransaction()->rollBack();

            return false;
        }

        Yii::$app->getDb()->getTransaction()->commit();

        return true;
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return RelationGroupRecord::primaryKey();
    }
}