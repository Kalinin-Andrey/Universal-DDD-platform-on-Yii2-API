<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.06.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\element;

use Yii;

/**
 * Class Element
 * @package commonprj\components\core\entities\engineeringSystem\element
 */
class Element extends \commonprj\components\core\entities\common\element\Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->esElementRepository;
    }
}
