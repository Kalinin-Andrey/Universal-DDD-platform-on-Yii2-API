<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.06.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\conductor;

use commonprj\components\core\entities\common\element\Element;
use Yii;

/**
 * Class Conductor
 * @package commonprj\components\core\entities\engineeringSystem\conductor
 */
class Conductor extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->conductorRepository;
    }
}