<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\construction\construction;

use commonprj\components\core\entities\construction\element\Element;
use Yii;

/**
 * Class Construction
 * @package commonprj\components\core\entities\construction\construction
 */
class Construction extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->constructionRepository;
    }
}