<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\placeholder;


use commonprj\components\templateEngine\entities\EntitiesRepository;
use commonprj\components\templateEngine\entities\subtemplate\Subtemplate;
use commonprj\components\templateEngine\models\PlaceholderRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use Exception;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class PlaceholderRepository
 * @package commonprj\components\templateEngine\entities\placeholder
 */
class PlaceholderRepository extends EntitiesRepository
{
    protected $entityClassName = 'commonprj\components\templateEngine\entities\placeholder\Placeholder';
    protected $recordClassName = 'commonprj\components\templateEngine\models\PlaceholderRecord';

    /**
     * @param BaseCrudModel $placeholder
     * @return false|int
     * @throws ServerErrorHttpException
     */
    public function delete(BaseCrudModel $placeholder)
    {
        $record = PlaceholderRecord::find()->where(['id' => $placeholder->id])->with(['sectionLayouts', 'template2placeholders'])->one();
        Yii::$app->db->beginTransaction();

        try {
            if ($record->sectionLayouts) {
                Subtemplate::deleteRows($record->sectionLayouts);
            }

            if ($record->template2placeholders) {
                Subtemplate::deleteRows($record->template2placeholders);
            }

            $result = $record->delete();
            Yii::$app->db->transaction->commit();

        } catch (Exception $e) {
            Yii::$app->db->transaction->rollBack();
            throw new ServerErrorHttpException(basename(__FILE__, '.php') . __LINE__ . ' Cant\'t delete data!');
        }

        return $result;
    }
}
