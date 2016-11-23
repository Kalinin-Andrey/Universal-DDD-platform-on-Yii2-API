<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 27.06.2016
 */

namespace commonprj\components\core\entities\common\propertyRange;

use yii\base\Model;

/**
 * Class PropertyRange
 * @package commonprj\components\core\entities\common\propertyRange
 */
class PropertyRange extends Model
{
    public $id;
    public $name;
    public $propertyId;
    public $fromValueId;
    public $toValueId;
}
