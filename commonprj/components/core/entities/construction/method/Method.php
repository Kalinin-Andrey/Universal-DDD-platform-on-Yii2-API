<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\construction\method;

use commonprj\components\core\entities\common\element\Element;
use Yii;

/**
 * Class Method
 * @package commonprj\components\core\entities\construction\method
 */
class Method extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->methodRepository;
    }
}