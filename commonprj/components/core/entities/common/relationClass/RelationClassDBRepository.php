<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.08.2016
 */

namespace commonprj\components\core\entities\common\relationClass;

use commonprj\components\core\models\ElementClassRecord;
use commonprj\components\core\models\RelationClassRecord;
use commonprj\components\core\models\RelationGroupRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class RelationClassReposotory
 * @package commonprj\components\core\entities\common\relationClass
 */
class RelationClassDBRepository extends BaseDBRepository implements RelationClassRepository
{
    public $activeRecord = 'commonprj\components\core\models\RelationClassRecord';

    /**
     * @param $condition
     * @param $isRoot
     * @return array
     * @throws HttpException
     */
    public function getElementClassesById($condition, $isRoot)
    {
        if (!$relationClassRecord = RelationClassRecord::findOne($condition)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementClass2relationClasses = $relationClassRecord->getElementClass2relationClasses()
            ->select(['element_class_id'])
            ->where(['is_root' => $isRoot])
            ->all();

        $elementClassIds = [];
        foreach ($elementClass2relationClasses as $elementClass2relationClass) {
            $elementClassIds[] = $elementClass2relationClass->getAttribute('element_class_id');
        }

        if (!$elementClassIds) {
            return [];
        }

        $elementClassRecord = ElementClassRecord::find()->where(['id' => $elementClassIds])->one();

        return $this->instantiateByARAndClassName($elementClassRecord);
    }

    /**
     * @param mixed $condition
     * @return array
     */
    public function find($condition = null)
    {
        if (ArrayHelper::isAssociative($condition)) {
            $relationClassRecords = RelationClassRecord::find()->where($condition)->all();
        } else {
            $relationClassRecords = RelationClassRecord::find()->all();
        }

        $result = [];
        foreach ($relationClassRecords as $relationClassRecord) {
            $result[$relationClassRecord['id']] = self::instantiateByARAndClassName($relationClassRecord);
        }

        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getRelationGroups(int $id)
    {
        $relationClassRecord = RelationClassRecord::findOne($id);
        $relationGroupRecords = $relationClassRecord->getRelationGroups()->all();
        $result = [];

        foreach ($relationGroupRecords as $relationGroupRecord) {
            $result[$relationGroupRecord['id']] = self::instantiateByARAndClassName($relationGroupRecord);
        }

        return $result;
    }

    /**
     * @param RelationClass $relationClass
     * @return bool
     */
    public function save(RelationClass $relationClass)
    {
        if (!$relationClassRecord = RelationClassRecord::findOne($relationClass->id)) {
            $relationClassRecord = new RelationClassRecord();
        }

        $relationClassRecord->setAttributes(self::arrayKeysCamelCase2Underscore($relationClass->attributes));
        $result = $relationClassRecord->save();

        if ($result) {
            $relationClass->setAttributes(self::arrayKeysUnderscore2CamelCase($relationClassRecord->attributes), false);
        } else {
            $relationClass->addErrors($relationClassRecord->getErrors());
        }

        return $result;
    }

    /**
     * @param $id
     * @return bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function deleteRelationClassById($id)
    {
        $model = RelationClassRecord::findOne($id);

        if ($model === null) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->beginTransaction();

        try {
            $relationGroups = $model->getRelationGroups()->all();
            /** @var RelationGroupRecord $relationGroup */
            foreach ($relationGroups as $relationGroup) {
                BaseCrudModel::deleteRows($relationGroup->getRelationHierarchies()->all());
                BaseCrudModel::deleteRows($relationGroup->getRelationInclusions()->all());
            }
            BaseCrudModel::deleteRows($relationGroups);
            BaseCrudModel::deleteRows($model->getElementClass2relationClasses()->all());
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
        return RelationClassRecord::primaryKey();
    }
}