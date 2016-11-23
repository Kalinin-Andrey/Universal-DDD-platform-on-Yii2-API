<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 20.09.2016
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 * @return string
 * @throws SmartyException
 * Плагин вызывает методы templateEngineHelper для отрисовки иерархий
 * Вызывается  с двумя обязательными параметрами:
 * - hierarchy - массив с иерархией;
 * - type - тип html верстки для вывода:
 *      - ul - вложенные маркированные списки
 */
function smarty_function_recursiveHierarchy(array $params, Smarty_Internal_Template &$smarty)
{
    if (empty($params['hierarchy']) || empty($params['type'])) {
        throw new SmartyException('Call recursiveHierarchy plugin with incorrect params ' . __FILE__ . ' ' . __LINE__);
    }
    $hierarchy = $params['hierarchy'];
    $htmlType = $params['type'];
    $html = '';

    switch ($htmlType) {
        case 'ul' : $html = Yii::$app->templateEngineHelper->getHtmlUlByRecursiveHierarchy($hierarchy);
    }

    return $html;
}