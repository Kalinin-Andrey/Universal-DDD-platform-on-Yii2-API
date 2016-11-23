<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\construction\tool;

use commonprj\components\core\entities\common\element\Element;
use Yii;

/**
 * Class Tool
 * @package commonprj\components\core\entities\construction\tool
 */
class Tool extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->toolRepository;
    }
}