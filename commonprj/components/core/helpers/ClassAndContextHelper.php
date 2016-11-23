<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 17.08.2016
 */

namespace commonprj\components\core\helpers;

use commonprj\components\core\models\ContextRecord;
use commonprj\components\core\models\ElementClassRecord;
use yii\web\HttpException;

/**
 * Class BaseCrudModel
 * @package commonprj\components\core\entities
 */
class ClassAndContextHelper
{
    /**
     * Получение id из таблицы element_class по имени класса.
     * @param string $fullClassName - Имя класса включая неймспейс.
     * @return int
     */
    public static function getClassId(string $fullClassName)
    {
        $entityClassName = preg_replace('/DBRepository/', '', $fullClassName);
        $contextAndClassName = self::getContextAndClassName($entityClassName);

        return self::getElementClassIdByClassAndContextName($contextAndClassName['className']);
    }

    /**
     * @param string $fullClassName
     * @return array
     */
    public static function getContextAndClassName(string $fullClassName)
    {
        preg_match('/(\w+)\\\\(\w+)\\\\\w+$/U', $fullClassName, $match);
        $contextName = $match[1];
        $className = ucfirst($match[2]);
        $fullClassName = "{$contextName}\\{$className}";

        return [
            'contextName' => $contextName,
            'className'   => $fullClassName,
        ];
    }

    /**
     * Метод для получения id из таблицы element_class по имени класса и контекста.
     * @param string $className
     * @return int
     * @throws HttpException
     */
    public static function getElementClassIdByClassAndContextName(string $className)
    {
        $elementClassRecord = ElementClassRecord::findOne(['name' => $className]);

        if (!$elementClassRecord) {
            throw new HttpException(404, basename(__FILE__, '.php') . __LINE__);
        }

        return $elementClassRecord['id'];
    }
}