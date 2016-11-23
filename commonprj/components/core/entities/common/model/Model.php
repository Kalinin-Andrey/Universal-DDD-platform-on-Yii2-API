<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 27.06.2016
 */

namespace commonprj\components\core\entities\common\model;
use commonprj\components\core\entities\common\element\Element;

/**
 * Class Model
 * @package commonprj\components\core\entities\common\model
 */
class Model extends \yii\base\Model
{
    public $id;
    public $elementId;
    public $data;
    public $entity;

    /**
     * @return Element
     */
    public function getElement()
    {
        // todo реализовать метод
        return new Element();
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $modelRepository = new ModelDBRepository();
        $modelRepository->deleteElementById($this->id);

        return true;
    }
}
