<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 20.06.2016
 */

namespace commonprj\components\core\entities\engineeringSystem\model;

use commonprj\components\core\helpers\ClassAndContextHelper;
use commonprj\components\core\models\Element2elementClassRecord;
use commonprj\extendedStdComponents\BaseCrudModel;
use Yii;
use yii\web\HttpException;

/**
 * Class Model
 * @package commonprj\components\core\entities\engineeringSystem\model
 */
class Model extends BaseCrudModel
{
    public $id;
    public $elementId;
    public $data;

    /**
     * Присвоение свойству доменного слоя $repository - соответствующего компонента
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->repository = Yii::$app->esModelRepository;
    }

    /**
     * Найти модель по id или другому условию.
     * @param mixed $condition - id (обычный или composite) записи или ассоциативный массив условий для WHERE.
     * @return BaseCrudModel - Возвращает класс доменного слоя, наследуемый от BaseCrudModel.
     */
    public function findOne($condition)
    {
        return $this->repository->findOne($condition);
    }

    /**
     * Удаляет запись текущего инстанса вместе со всеми зависимостями.
     */
    function delete()
    {
        $this->findOne($this->id);
        $elementTypeRepository = new ModelDBRepository();
        $elementTypeRepository->deleteModelById($this->id);

        return true;
    }

    /**
     * @return BaseCrudModel
     * @throws HttpException
     */
    public function update()
    {
        return $this->save();
    }

    /**
     * @param bool $condition
     * @return array|BaseCrudModel
     */
    public function find($condition = false)
    {
        $condition = true;
        $modelRepository = new ModelDBRepository();
        if ($condition) {
            $elementClassId = ClassAndContextHelper::getClassId(get_class());
            $elementsByClass = Element2elementClassRecord::find()
                ->where(['element_class_id' => $elementClassId])
                ->asArray()
                ->all();
            $elementIds = [];
            if (empty($elementsByClass)) {
                return [];
            } else {
                foreach ($elementsByClass as $element) {
                    $elementIds[] = $element['element_id'];
                }

                return $modelRepository->find(['element_id' => $elementIds]);
            }
        } else {
            return $modelRepository->find();
        }
    }

    /**
     * @return BaseCrudModel
     */
    public function save()
    {
        $model = new ModelDBRepository();

        return $model->save($this);
    }
}
