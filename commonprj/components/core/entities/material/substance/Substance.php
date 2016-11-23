<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 21.06.2016
 */

namespace commonprj\components\core\entities\material\substance;

use commonprj\components\core\entities\common\element\Element;
use Yii;

/**
 * Class Substance
 * @package commonprj\components\core\entities\material\substance
 */
class Substance extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->substanceRepository;
    }
}