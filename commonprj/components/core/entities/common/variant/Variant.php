<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.09.2016
 */

namespace commonprj\components\core\entities\common\variant;

use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\base\Model;

/**
 * Class Variant
 * @package commonprj\components\core\entities\common\variant
 */
class Variant extends Model
{
    const VARIANT_TYPES = [
        1 => 'PropertyVariant',
        2 => 'RelationVariant',
    ];
    public $repository;
    public $variantTypeId;
    public $element;
    public $elementType;
    public $entity;

    /**
     * @param array|null $condition
     * @return BaseCrudModel[]
     */
    public function find($condition = null)
    {
        $propertyVariant = Yii::$app->propertyVariantRepository->find();
        $relationVariant = Yii::$app->relationVariantRepository->find();
        $result = [];

        if ($propertyVariant) {
            $result['variantsByTypeId'][1] = Yii::$app->propertyVariantRepository->find()['variantsByTypeId'][1];
        }

        if ($relationVariant) {
            $result['variantsByTypeId'][2] = Yii::$app->relationVariantRepository->find()['variantsByTypeId'][2];
        }

        return $result;
    }
}