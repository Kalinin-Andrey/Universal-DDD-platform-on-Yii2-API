<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\template;


use commonprj\components\templateEngine\entities\Entities;
use commonprj\components\templateEngine\models\PlaceholderRecord;
use commonprj\components\templateEngine\models\SectionLayoutRecord;
use commonprj\components\templateEngine\models\Template2placeholderRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class Template
 * @package commonprj\components\templateEngine\entities\template
 */
class Template extends Entities
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
     * @property Template2placeholderRecord[] $template2placeholders
     * @property PlaceholderRecord[] $placeholders
     */
    /**
     * @param mixed $condition primary key value or a set of column values
     * @param bool|string $byClass
     * @return BaseCrudModel
     * @throws HttpException
     */
    public $id;
    public $sysname;
    public $name;
    public $description;
    public $path;
    public $isActive;
    public $sectionLayouts = [];
    public $template2placeholders = [];
    public $placeholders = [];

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->repository = Yii::$app->templateRepository;
        parent::__construct($config);
    }

}