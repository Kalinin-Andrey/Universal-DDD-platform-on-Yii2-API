<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\placeholder;


use commonprj\components\templateEngine\entities\Entities;
use commonprj\components\templateEngine\models\SectionLayoutRecord;
use commonprj\components\templateEngine\models\Template2placeholderRecord;
use commonprj\components\templateEngine\models\TemplateRecord;
use Yii;

/**
 * Class Placeholder
 * @package commonprj\components\templateEngine\entities\placeholder
 */
class Placeholder extends Entities
{
    /**
     * @property integer $id
     * @property string $sysname
     * @property string $name
     * @property boolean $isActive
     *
     * @property SectionLayoutRecord[] $sectionLayouts
     * @property Template2placeholderRecord[] $template2placeholders
     * @property TemplateRecord[] $templates
     */
    public $id;
    public $sysname;
    public $name;
    public $isActive;
    public $sectionLayouts = [];
    public $template2placeholders = [];
    public $templates = [];

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->repository = Yii::$app->placeholderRepository;
        parent::__construct($config);
    }
}