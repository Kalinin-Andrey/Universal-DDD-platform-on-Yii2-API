<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 18.08.2016
 */

namespace commonprj\components\core\entities\common\abstractPropertyValue;

use commonprj\extendedStdComponents\BaseCrudModel;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class AbstractPropertyValue
 * @package commonprj\components\core\entities\common\abstractPropertyValue
 */
class AbstractPropertyValue extends Model
{
    public $id;
    public $multiplicityId;
    public $propertyId;
    public $label;
    public $value;
    public $name;
    public $fromValue;
    public $toValue;
    public $values;
    public $property;
    public $elements;
    public $elementTypes;
    public $entity;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['multiplicityId', 'propertyId'], 'required'],
            [['label', 'name'], 'string'],
            [['id', 'multiplicityId', 'propertyId'], 'integer'],
        ];
    }
}