<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\template;


use commonprj\components\templateEngine\entities\EntitiesRepository;
use commonprj\components\templateEngine\models\TemplateRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;

/**
 * Class TemplateRepository
 * @package commonprj\components\templateEngine\entities\template
 */
class TemplateRepository extends EntitiesRepository
{

    protected $entityClassName = 'commonprj\components\templateEngine\entities\template\Template';
    protected $recordClassName = 'commonprj\components\templateEngine\models\TemplateRecord';

    /**
     * @param BaseCrudModel $template
     * @return false|int
     * @throws ServerErrorHttpException
     */
    public function delete(BaseCrudModel $template)
    {
        $record = TemplateRecord::find()->where(['id' => $template->id])->with(['sectionLayouts', 'template2placeholders'])->one();
        Yii::$app->db->beginTransaction();

        try {
            if ($record->sectionLayouts) {
                Template::deleteRows($record->sectionLayouts);
            }

            if ($record->template2placeholders) {
                Template::deleteRows($record->template2placeholders);
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
