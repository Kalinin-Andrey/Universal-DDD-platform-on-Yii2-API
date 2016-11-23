<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 29.08.2016
 */

namespace commonprj\components\core\entities\common\property;

use commonprj\extendedStdComponents\BaseCrudModel;
use commonprj\extendedStdComponents\BaseServiceRepository;

/**
 * Class PropertyServiceRepository
 * @package commonprj\components\core\entities\common\property
 */
class PropertyServiceRepository extends BaseServiceRepository
{
    /**
     * @param null|array $condition
     * @return Property[]
     */
    public function find($condition = null)
    {
        $this->requestUri = 'common/property';

        if (!empty($condition)) {
            $this->requestParams = $condition;
        }
        $arModel = $this->getAndCheckApiData();

        return $this->getArrayOfModels($arModel);
    }

    /**
     * @param $condition
     * @return BaseCrudModel
     */
    public function findOne($condition)
    {
        $this->requestUri = 'common/property/' . $condition['id'];
        unset($condition['id']);
        $this->requestParams = $condition;
        $arModel = $this->getAndCheckApiData();

        return $this->getOneModel($arModel);
    }

    /**
     * @param int $propertyId
     * @param null|int $multiplicityId
     * @return array
     */
    public function getValues(int $propertyId, $multiplicityId = null)
    {
        $this->requestUri = 'common/property/' . $propertyId . '/property-values';
        $this->requestParams = ['multiplicityId' => $multiplicityId];
        $arModel = $this->getAndCheckApiData();
        $modelClass = $this->getFullModelClassName('common\AbstractPropertyValue');

        if ($arModel) {
            foreach ($arModel['propertyValuesByMultiplicityId'] as $key => $item) {
                $arModel['propertyValuesByMultiplicityId'][$key] = $this->getArrayOfModels($item, $modelClass);
            }
        }

        return $arModel;
    }

    /**
     * @param string $propertyTypeName
     * @return bool|int
     * Метод возвращает id типа свойства по его названию
     */
    public function getPropertyTypeIdByName(string $propertyTypeName)
    {
        $result = false;
        $this->requestUri = 'common/property-type';
        $propertyTypes = $this->getAndCheckApiData();

        foreach ($propertyTypes as $type) {

            if ($type['name'] === $propertyTypeName) {
                $result = $type['id'];
                break;
            }
        }

        return $result;
    }

    /**
     * @param $propertyId
     * @return array
     */
    public function getPropertyUnitById($propertyId)
    {
        $this->requestUri = 'common/property/' . $propertyId . '/property-unit';

        return $this->getAndCheckApiData();
    }
}