<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 29.07.2016
 */

namespace commonprj\components\templateEngine\entities;

use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


/**
 * Class EntitiesRepository
 * @package commonprj\components\templateEngine\entities
 * В этот класс вынесены общие для всех репозиториев методы и свойства
 */
class EntitiesRepository
{
    /**
     * @var string
     * полное имя класса activeRecord
     */
    protected $recordClassName;
    /**
     * @var string
     * полное имя класса сущности
     */
    protected $entityClassName;

    /**
     * @param bool $byClass
     * @return array
     */
    public function find($byClass = false)
    {

        $recordObject = call_user_func("{$this->recordClassName}::find", $byClass);
        $records = $recordObject->all();

        $arResult = [];

        foreach ($records as $record) {
            /** @var BaseCrudModel $entity*/
            $entity = new $this->entityClassName();
            $attributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->attributes);
            $entity->setAttributes($attributes, false);
            $arResult[] = $entity;
        }

        return $arResult;
    }

    /**
     * @param int|array $condition
     * @param bool $byClass
     * @return BaseCrudModel
     * @throws NotFoundHttpException
     */
    public function findOne($condition, $byClass = false)
    {
        /** @var BaseCrudModel $entity*/
        $entity = new $this->entityClassName();

        $record = call_user_func("{$this->recordClassName}::findOne", $condition);

        if (empty($record)) {
            throw new NotFoundHttpException(basename(__FILE__, '.php') . __LINE__);
        }

        $attributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->attributes);
        $entity->setAttributes($attributes, false);

        return $entity;
    }

    /**
     * @param BaseCrudModel $entity
     * @return bool
     */
    public function update(BaseCrudModel $entity)
    {
        $record = call_user_func("{$this->recordClassName}::findOne", $entity->id);
        $entityAttributes = BaseDBRepository::arrayKeysCamelCase2Underscore($entity->getAttributes());
        $record->setAttributes($entityAttributes);

        if ($record->save()) {
            $recordAttributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->attributes);
            $entity->setAttributes($recordAttributes, false);

            return true;
        } else {
            $entity->addErrors($record->getErrors());

            return false;
        }
    }

    /**
     * @return mixed
     */
    public function primaryKey()
    {
        return call_user_func("{$this->recordClassName}::primaryKey");
    }

    /**
     * @param BaseCrudModel $entity
     * @return bool
     */
    public function save(BaseCrudModel $entity)
    {
        /** @var ActiveRecord $record*/
        $record = new $this->recordClassName();
        $entityAttributes = BaseDBRepository::arrayKeysCamelCase2Underscore($entity->getAttributes());
        $record->setAttributes($entityAttributes);

        if ($record->save()) {
            $recordAttributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->attributes);
            $entity->setAttributes($recordAttributes, false);

            return true;
        } else {
            $entity->addErrors($record->getErrors());

            return false;
        }

    }

    /**
     * @param BaseCrudModel $entity
     * @return false|int
     * @throws ServerErrorHttpException
     */
    public function delete(BaseCrudModel $entity)
    {

        $recordObject = call_user_func("{$this->recordClassName}::find");
        /** @var ActiveRecord $record */
        $record = $recordObject->where(['id' => $entity->id])->with('sectionLayouts')->one();

        Yii::$app->db->beginTransaction();

        try {
            if ($record->sectionLayouts) {
                BaseCrudModel::deleteRows($record->sectionLayouts);
            }

            $result = $record->delete();
            Yii::$app->db->transaction->commit();

        } catch (Exception $e) {
            Yii::$app->db->transaction->rollBack();
            throw new ServerErrorHttpException(basename(__FILE__, '.php') . __LINE__ . ' Cant\'t delete data!');
        }

        return $result;
    }

    /**
     * @param string $id
     * @param Entities $modelClass
     * @return Entities
     * @throws NotFoundHttpException
     */
    public function findModel($id, $modelClass):Entities
    {
        /** @var Entities $model */
        $model = new $modelClass;
        $keys = $model->primaryKey();

        if (count($keys) > 1) {
            $values = explode(',', $id);

            if (count($keys) === count($values)) {
                $model = $model->findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $model->findOne(['id' => $id]);
        }

        if (!isset($model)) {
            throw new NotFoundHttpException("Object not found: $id");
        }

        return $model;
    }
}
