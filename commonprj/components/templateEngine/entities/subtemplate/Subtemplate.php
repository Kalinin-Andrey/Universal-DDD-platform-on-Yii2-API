<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\subtemplate;


use commonprj\components\templateEngine\entities\Entities;
use commonprj\components\templateEngine\models\SectionLayoutRecord;
use Yii;

/**
 * Class Subtemplate
 * @package commonprj\components\templateEngine\entities\subtemplate
 */
class Subtemplate extends Entities
{
    /**
     * @property integer $id
     * @property string $sysname
     * @property string $name
     * @property string $description
     * @property string $path
     * @property boolean $isActive
     *
     * @property SectionLayoutRecord[] $sectionLayouts
     */
    public $id;
    public $sysname;
    public $name;
    public $description;
    public $path;
    public $isActive;
    public $sectionLayouts = [];

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->repository = Yii::$app->subtemplateRepository;
        parent::__construct($config);
    }

}