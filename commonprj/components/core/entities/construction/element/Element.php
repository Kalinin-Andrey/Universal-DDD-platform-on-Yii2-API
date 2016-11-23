<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.06.2016
 */

namespace commonprj\components\core\entities\construction\element;

use commonprj\components\core\entities\material\material\Material;
use Yii;

/**
 * Class Element
 * @package commonprj\components\core\entities\construction\element
 */
class Element extends Material
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->conElementRepository;
    }
}