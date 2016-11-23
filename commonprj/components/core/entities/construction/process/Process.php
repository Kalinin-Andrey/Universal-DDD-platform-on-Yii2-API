<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\construction\process;

use commonprj\components\core\entities\common\element\Element;
use Yii;

/**
 * Class Process
 * @package commonprj\components\core\entities\construction\process
 */
class Process extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->processRepository;
    }
}