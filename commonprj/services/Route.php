<?php
/**
 * Created by daSilva.Rodrigues
 * Date: 09.08.2016
 */

namespace commonprj\services;

use yii\base\InvalidParamException;

/**
 * Class Route
 * @package commonprj\components\core\services
 */
class Route
{
    public $subsystemNames;

    /**
     * @param string $subsystemName
     */
    public function subsystemName2subsystemSysname(string $subsystemName)
    {
        if (in_array($subsystemName, array_keys($this->subsystemNames))) {
            return $this->subsystemNames[$subsystemName];
        }

        throw new InvalidParamException('Wrong parameter given to subsystemName2subsystemSysname');
    }

    /**
     * @param string $subsystemSysname
     * @return mixed
     */
    public function subsystemSysname2subsystemName(string $subsystemSysname)
    {
        $result = array_search($subsystemSysname, $this->subsystemNames);

        if (!$result) {
            throw new InvalidParamException('Wrong parameter given to subsystemSysname2subsystemName');
        }
        
        return $result;
    }

    /**
     * @param string $subsystemSysname
     * @param string $className
     * @param int $id
     * @param bool $isForNodeName
     * @return string
     */
    public function params2Uid(string $subsystemSysname, string $className, int $id, bool $isForNodeName = false)
    {
        $className = $this->classNameUrlEncode($className, $isForNodeName);
        $result = "{$subsystemSysname}_{$className}_{$id}";

        return $result;
    }

    /**
     * @param string $className
     * @param bool $isForNodeName
     * @return mixed
     */
    public function classNameUrlEncode(string $className, bool $isForNodeName = false)
    {
        if (substr_count($className, '\\') > 1) {
            preg_match('/(\w+)\\\\(\w+)\\\\\w+$/', $className, $match);
            $className = $match[1] . '\\' . $match[2];
        }

        return $isForNodeName ? preg_replace('/\//', '.', $className) : preg_replace('/\\\\/', '.', $className);
    }

    /**
     * @param string $uid
     * @param bool $isForNodeName
     * @return string
     */
    public function uid2params(string $uid, bool $isForNodeName = false)
    {
        $exploded = explode('_', $uid);
        $exploded[1] = $this->classNameUrlDecode($exploded[1], $isForNodeName);

        if ($exploded[1] === null || count($exploded) > 3 || count($exploded) < 2) {
            throw new InvalidParamException('Parameter $uid is invalid!');
        }

        $result['subsystemSysname'] = $exploded[0];
        $result['className'] = $exploded[1];
        $result['id'] = $exploded[2] ?? '';

        return $result;
    }

    /**
     * Метод заменяет во входящей строке точку на прямой или обратный слеш, и возвращает измененную строку.
     * @param string $className - Входящая строка.
     * @param bool $isForNodeName - Если true, то заменит точку на прямой слэш, если false (по умолчанию), то на обратный.
     * @return string
     */
    public function classNameUrlDecode(string $className, bool $isForNodeName = false)
    {
        preg_match('/^[^\.]+(\.)[^\.]+$/', $className, $match);

        if (!isset($match[1])) {
            throw new InvalidParamException('Parameter $uid is invalid!');
        }

        preg_match('/([^\.A-Za-z])/', $className, $match);

        if (isset($match[1])) {
            throw new InvalidParamException('Parameter $uid is invalid!');
        }

        return $isForNodeName ? preg_replace('/\./', '/', $className) : preg_replace('/\./', '\\', $className);
    }
}
