<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\section;


use commonprj\components\templateEngine\entities\Entities;
use commonprj\components\templateEngine\models\SectionLayoutRecord;
use commonprj\components\templateEngine\models\TemplateRecord;
use Yii;

/**
 * Class Section
 * @package commonprj\components\templateEngine\entities\section
 */
class Section extends Entities
{
    /**
     * @property integer $id
     * @property string $sysname
     * @property string $name
     * @property string $description
     * @property integer $templateId
     * @property boolean $isActive
     *
     * @property TemplateRecord $template
     * @property SectionLayoutRecord[] $sectionLayouts
     */
    public $id;
    public $sysname;
    public $name;
    public $description;
    public $templateId;
    public $isActive;
    public $template;
    public $sectionLayouts = [];

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->repository = Yii::$app->sectionRepository;
        parent::__construct($config);
    }

}