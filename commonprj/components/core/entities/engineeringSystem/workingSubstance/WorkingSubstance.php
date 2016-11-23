<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.06.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\workingSubstance;

use commonprj\components\core\entities\material\substance\Substance;
use Yii;

/**
 * Class WorkingSubstance
 * @package commonprj\components\core\entities\engineeringSystem\workingSubstance
 */
class WorkingSubstance extends Substance
{
    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->workingSubstanceRepository;
    }
}