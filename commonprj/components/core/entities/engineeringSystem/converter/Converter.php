<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.06.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\converter;

use commonprj\components\core\entities\engineeringSystem\element\Element;
use Yii;

/**
 * Class Converter
 * @package commonprj\components\core\entities\engineeringSystem\converter
 */
class Converter extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->converterRepository;
    }
}
