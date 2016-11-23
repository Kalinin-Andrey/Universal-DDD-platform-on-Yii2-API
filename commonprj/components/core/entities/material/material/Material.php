<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 21.06.2016
 */

namespace commonprj\components\core\entities\material\material;

use commonprj\components\core\entities\common\element\Element;
use Yii;

/**
 * Class Material
 * @package commonprj\components\core\entities\material\material
 */
class Material extends Element
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->materialRepository;
    }
}