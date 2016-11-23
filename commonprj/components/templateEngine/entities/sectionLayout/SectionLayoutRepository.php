<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\sectionLayout;


use commonprj\components\templateEngine\entities\EntitiesRepository;
use commonprj\components\templateEngine\models\SectionLayoutRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseDBRepository;
use yii\web\NotFoundHttpException;

/**
 * Class SectionLayoutRepository
 * @package commonprj\components\templateEngine\entities\layout
 */
class SectionLayoutRepository extends EntitiesRepository
{
    protected $entityClassName = 'commonprj\components\templateEngine\entities\sectionLayout\SectionLayout';
    protected $recordClassName = 'commonprj\components\templateEngine\models\SectionLayoutRecord';

    /**
     * @param bool $byClass
     * @return array
     */
    public function find($byClass=false)
    {
        $records = SectionLayoutRecord::find()->with('section', 'template', 'placeholder', 'subtemplate')->all();

        $arResult = [];

        foreach ($records as $record) {
            $entity = new SectionLayout();
            $attributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->attributes);
            $entity->setAttributes($attributes, false);
            $entity->section = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->section->attributes);
            $entity->template = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->template->attributes);
            $entity->subtemplate = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->subtemplate->attributes);
            $entity->placeholder = BaseDBRepository::arrayKeysUnderscore2CamelCase($record->placeholder->attributes);
            $arResult[] = $entity;
        }

        return $arResult;
    }

    /**
     * @param int|array $condition
     * @return SectionLayout
     * @throws NotFoundHttpException
     */
    public function findOne($condition)
    {
        $record = new SectionLayoutRecord();

        if (!is_array($condition)) {
            $condition = ['id' => $condition];
        }
        $result = $record->find()->where($condition)->with('section', 'template', 'placeholder', 'subtemplate')->one();
        $entity = new SectionLayout();
        
        if (empty($result)) {
            throw new NotFoundHttpException(basename(__FILE__, '.php') . __LINE__);
        }

        $attributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->attributes);
        $entity->setAttributes($attributes, false);
        $entity->section = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->section->attributes);
        $entity->template = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->template->attributes);
        $entity->subtemplate = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->subtemplate->attributes);
        $entity->placeholder = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->placeholder->attributes);

        return $entity;
    }

    /**
     * @param BaseCrudModel $entity
     * @return false|int
     */
    public function delete(BaseCrudModel $entity)
    {
        $record = SectionLayoutRecord::findOne(['id' => $entity->id]);
        return $record->delete();
    }
}
