<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.10.2016
 */

namespace commonprj\components\core\entities\common\relation;

use commonprj\components\core\models\RelationRecord;
use commonprj\extendedStdComponents\BaseDBRepository;

/**
 * Class RelationDBRepository
 * @package commonprj\components\core\entities\common\relation
 */
class RelationDBRepository extends BaseDBRepository
{
    public $activeRecord = 'commonprj\components\core\models\RelationRecord';

    public function find($condition)
    {
        if (!empty($condition['condition'])) {
            $relationRecords = RelationRecord::find()->where($condition['condition']);
        } else {
            $relationRecords = RelationRecord::find();
        }

        if (!empty($condition['with'])) {
            $relationRecords->with($condition['with']);
        }

        $relationRecords = $relationRecords->all();
        $result = [];

        foreach ($relationRecords as $relationRecord) {
            $result[$relationRecord->getAttribute('id')] = self::instantiateByARAndClassName($relationRecord);
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function primaryKey()
    {
        return RelationRecord::primaryKey();
    }

    public function getChildren($id)
    {
        $relationRecord = RelationRecord::findOne($id);
        $childElements = $relationRecord->getChildElement()->all();
        $result = [];

        foreach ($childElements as $childElement) {
            $result[] = self::instantiateByARAndClassName($childElement);
        }

        return $result;
    }
}