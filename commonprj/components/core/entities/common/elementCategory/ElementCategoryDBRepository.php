<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 27.06.2016
 */

namespace commonprj\components\core\entities\common\elementCategory;

use commonprj\components\core\models\ElementCategoryRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ElementCategoryRepository
 * @package commonprj\components\core\entities\common\elementCategory
 */
class ElementCategoryDBRepository extends BaseDBRepository implements ElementCategoryRepository
{
    public $activeRecord = 'commonprj\components\core\models\ElementCategoryRecord';

    /**
     * @param mixed $condition
     * @return array
     */
    public function find($condition = null)
    {
        if (ArrayHelper::isAssociative($condition)) {
            $elementCategoryRecords = ElementCategoryRecord::find()->where($condition)->all();
        } else {
            $elementCategoryRecords = ElementCategoryRecord::find()->all();
        }

        $result = [];
        /** @var ActiveRecord $elementCategoryRecord */
        foreach ($elementCategoryRecords as $elementCategoryRecord) {
            $result[$elementCategoryRecord['id']] = self::instantiateByARAndClassName($elementCategoryRecord);
        }

        return $result;
    }

    /**
     * @param $id
     * @return ElementCategory[]
     */
    public function getChildrenById($id)
    {
        $elementCategoryRecords = ElementCategoryRecord::find()->where(['parent_id' => $id])->andWhere(['!=', 'id', $id])->all();
        $result = [];
        foreach ($elementCategoryRecords as $elementCategoryRecord) {
            $result[$elementCategoryRecord['id']] = self::instantiateByARAndClassName($elementCategoryRecord);
        }

        return $result;
    }

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws HttpException
     */
    public function getParentByChildId($id)
    {
        $parentId = Yii::$app->elementCategoryRepository->getParentIdByChildId($id);

        if (!is_int($parentId)) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        $elementCategoryRecord = ElementCategoryRecord::find()->where(['id' => $parentId])->andWhere(['!=', 'id', $id])->one();
        $result = [];

        if (!is_null($elementCategoryRecord)) {
            $result = $this->instantiateByARAndClassName($elementCategoryRecord, get_called_class());
        }

        return $result;
    }

    /**
     * @param int $id
     * @return bool|string
     */
    public function getIsParent(int $id)
    {
        return ElementCategoryRecord::find()->select('is_parent')->where(['id' => $id])->scalar();
    }

    /**
     * @param int $id
     * @return ElementCategory
     */
    public function getRootById(int $id)
    {
        $elementCategoryRecord = ElementCategoryRecord::find()->where(['id' => $id])->one();

        return self::instantiateByARAndClassName($elementCategoryRecord);

    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRoots()
    {
        $rootIds = ElementCategoryRecord::find()->select('root_id')->distinct(['root_id'])->asArray()->all();
        $result = [];
        foreach ($rootIds as $rootId) {
            $elementCategoryRecord = ElementCategoryRecord::findOne($rootId['root_id']);
            $result[$rootId['root_id']] = self::instantiateByARAndClassName($elementCategoryRecord);
        }

        return $result;
    }

    /**
     * @param $rootElementCategoryId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getHierarchyByRootId($rootElementCategoryId)
    {
        $elementCategoryRecords = ElementCategoryRecord::find()->where(['root_id' => $rootElementCategoryId])->all();

        $result = [];
        foreach ($elementCategoryRecords as $elementCategoryRecord) {
            $result[$elementCategoryRecord['id']] = self::instantiateByARAndClassName($elementCategoryRecord);
        }

        return $result;
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function getParentIdByChildId($id)
    {
        return ElementCategoryRecord::find()->select(['parent_id'])->where(['id' => $id])->scalar();
    }

    /**
     * @param $id
     * @throws HttpException
     */
    public function deleteElementCategoryById($id)
    {
        $rows = ElementCategoryRecord::find()->where(['id' => $id])->all();
        Yii::$app->getDb()->beginTransaction();

        try {
            BaseCrudModel::deleteRows($rows);
        } catch (ServerErrorHttpException $e) {
            Yii::$app->getDb()->getTransaction()->rollBack();
            throw new HttpException(500, 'Failed to delete the object for unknown reason. ' . basename(__FILE__, '.php') . __LINE__);
        }

        Yii::$app->getDb()->getTransaction()->commit();
    }

    /**
     * @param ElementCategory $elementCategory
     * @return bool
     */
    public function save(ElementCategory $elementCategory)
    {
        if (!$elementTypeRecord = ElementCategoryRecord::findOne($elementCategory->id)) {
            $elementTypeRecord = new ElementCategoryRecord();
        }

        $elementTypeRecord->setAttributes(self::arrayKeysCamelCase2Underscore($elementCategory->attributes));
        $result = $elementTypeRecord->save();

        if ($result) {
            $elementCategory->setAttributes(self::arrayKeysUnderscore2CamelCase($elementTypeRecord->attributes), false);
        } else {
            $elementCategory->addErrors($elementTypeRecord->getErrors());
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return ElementCategoryRecord::primaryKey();
    }
}