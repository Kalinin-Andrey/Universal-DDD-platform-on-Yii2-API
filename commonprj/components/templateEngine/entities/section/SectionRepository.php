<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\section;


use commonprj\components\templateEngine\entities\EntitiesRepository;
use commonprj\components\templateEngine\models\SectionRecord;
use commonprj\extendedStdComponents\BaseDBRepository;
use yii\web\NotFoundHttpException;

/**
 * Class SectionRepository
 * @package commonprj\components\templateEngine\entities\section
 */
class SectionRepository extends EntitiesRepository
{
    protected $entityClassName = 'commonprj\components\templateEngine\entities\section\Section';
    protected $recordClassName = 'commonprj\components\templateEngine\models\SectionRecord';

    /**
     * @param array|int $condition
     * @return Section
     * @throws NotFoundHttpException
     */
    public function findOne($condition)
    {
        $record = new SectionRecord();

        if(!is_array($condition)) $condition = ['id' => $condition];

        $result = $record->find()->where($condition)->with('template')->one();
        $entity = new Section();
        
        if (empty($result)) {
            throw new NotFoundHttpException(basename(__FILE__, '.php') . __LINE__);
        }

        $attributes = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->attributes);
        $entity->setAttributes($attributes, false);

        if (is_object($result->template)) {
            $entity->template = BaseDBRepository::arrayKeysUnderscore2CamelCase($result->template->attributes);
        }

        return $entity;
    }
}
