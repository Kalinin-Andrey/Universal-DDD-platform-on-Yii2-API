<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 15.08.2016
 */

namespace commonprj\components\core\entities\common\model;

use commonprj\components\core\models\ModelRecord;
use yii\web\HttpException;

/**
 * Class ModelRepository
 * @package commonprj\components\core\entities\common\model
 */
class ModelDBRepository
{
    /**
     * @param $id
     * @throws HttpException
     */
    public function deleteElementById($id)
    {
        $modelRecord = ModelRecord::findOne($id);
        if (is_null($modelRecord) || !$modelRecord->delete()) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }
    }
}