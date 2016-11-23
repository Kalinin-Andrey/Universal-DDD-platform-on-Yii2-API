<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\sectionLayout;


use commonprj\components\templateEngine\entities\Entities;
use commonprj\components\templateEngine\models\PlaceholderRecord;
use commonprj\components\templateEngine\models\SectionRecord;
use commonprj\components\templateEngine\models\SubtemplateRecord;
use commonprj\components\templateEngine\models\TemplateRecord;
use Yii;

/**
 * Class SectionLayout
 * @package commonprj\components\templateEngine\entities\sectionLayout
 */
class SectionLayout extends Entities
{

    /**
     * This is the model class for table "section_layout".
     *
     * @property integer $id
     * @property integer $sectionId
     * @property integer $templateId
     * @property integer $placeholderId
     * @property integer $subtemplateId
     *
     * @property PlaceholderRecord $placeholder
     * @property SectionRecord $section
     * @property SubtemplateRecord $subtemplate
     * @property TemplateRecord $template
     */
    public $id;
    public $sectionId;
    public $templateId;
    public $placeholderId;
    public $subtemplateId;

    public $placeholder;
    public $section;
    public $subtemplate;
    public $template;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->repository = Yii::$app->sectionLayoutRepository;
        parent::__construct($config);
    }
}