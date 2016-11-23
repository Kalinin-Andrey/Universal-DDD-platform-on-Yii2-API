<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 29.07.2016
 */

namespace commonprj\components\templateEngine\entities;


use commonprj\extendedStdComponents\BaseCrudModel;
use yii\web\HttpException;

/**
 * Class Entities
 * @package commonprj\components\templateEngine\entities
 * В этот класс вынесены общие для всех сущностей методы и свойства
 */
class Entities extends BaseCrudModel
{
    /** @var $entitiesRepository EntitiesRepository */
    private $entitiesRepository;

    /**
     * Entities constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->entitiesRepository = $this->repository;
    }

    /**
     * @param bool $byClass
     * @return array
     */
    public function find($byClass = false)
    {
        return $this->entitiesRepository->find($byClass);
    }

    /**
     * @inheritdoc
     */
    public function findOne($condition, $byClass = false)
    {
        return $this->entitiesRepository->findOne($condition, $byClass);
    }

    /**
     * @return mixed
     */
    function delete()
    {
        return $this->entitiesRepository->delete($this);
    }

    /**
     * @return mixed
     */
    public function primaryKey()
    {
        return $this->entitiesRepository->primaryKey();
    }

    /**
     * @return bool
     * @throws HttpException
     */
    public function update()
    {
        return $this->entitiesRepository->update($this);
    }

    /**
     * @return mixed
     */
    public function save()
    {
        return $this->entitiesRepository->save($this);
    }
}
