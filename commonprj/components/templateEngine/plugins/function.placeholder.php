<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 11.07.2016
 * Плагин-функция Smarty Placeholder
 * Вызывается из шаблона вставкой {placeholder sysname="sysname"}
 * Параметр sysname обязателен
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 * @return string
 * @throws Exception
 * @throws SmartyException
 */
function smarty_function_placeholder(array $params, Smarty_Internal_Template &$smarty)
{
    //Стили для обозначения плэйсхолдеров и подшаблонов
    $placeholderCss = 'border:3px red dashed; min-height: 100px; position:relative;" class="clearfix';
    $placeholderNameCss = 'border-right:3px red dashed; border-bottom:3px red dashed;position:absolute; left:0; top:0; padding: 10px; color: red; background:rgba(255,255,255,0.6);font-size:12px !important';
    $subtemplateNameCss = 'border-left:3px blue dashed; border-bottom:3px blue dashed;position:absolute; right:0; top:0; padding: 10px; color: blue; background:rgba(255,255,255,0.6);font-size:12px !important';

    if (!isset($params['sysname'])) {
        throw new SmartyException('Placeholder with out sysname!');
    }
    //Передадим в смарти переменные Yii
    $smarty->assignGlobal('app', $smarty->tpl_vars['app']->value);
    $smarty->assignGlobal('this', $smarty->tpl_vars['this']->value);
    //Если в шаблон пришли данные переменные, значит выводим для админки
    if (isset($smarty->tpl_vars['subtemplates']->value) && isset($smarty->tpl_vars['placeholders']->value)) {
        //Получаем переданные из AjaxController/Preview переменные
        $subtemplates = $smarty->tpl_vars['subtemplates']->value;
        $placeholders = $smarty->tpl_vars['placeholders']->value;
        //Получаем имя плэйсхолдера
        $placeName = $placeholders[$params['sysname']] ?? $params['sysname'];
        //Рисуем границы плэйсхолдера
        $output = '<div style="' . $placeholderCss . '">';
        //Пишем имя плэйсхолдера
        $output .= '<div style="' . $placeholderNameCss . '">Placeholder::' . $placeName . '</div>';
        //Если к плэйсхолдеру привязан подшаблон, выводим
        if (isset($subtemplates[$params['sysname']])) {
            $output .= '<div style="' . $subtemplateNameCss . '">Subtemplate::' . $subtemplates[$params['sysname']]['subName'] . '</div>';
            //Отдаем подшаблон Smarty и получаем строку для вывода
            $output .= $smarty->fetch('@' . $subtemplates[$params['sysname']]['subPath']);
        }
        $output .= '</div>';
    } else {
        //Иначе вывод для фронта
        if (!isset($smarty->tpl_vars['sectionSysname']->value)) {
            throw new SmartyException('Section sysname does not exist!');
        }
        $sectionSysname = $smarty->tpl_vars['sectionSysname']->value;
        //Получаем template.sysname
        preg_match('~/{1}([A-Za-z0-9]+)\.tpl$~', $smarty->source->filepath, $match);

        if (!isset($match[1])) {
            throw new SmartyException('Template sysname does not resolve!');
        }
        $templateSysname = $match[1];
        //Получаем placeholder.sysname
        $placeholderSysname = strip_tags($params['sysname']);
        //Получаем путь к подшаблону
        $result = Yii::$app->templateEngine->templateOutput($sectionSysname, $templateSysname, $placeholderSysname);
        $output = '';

        if ($result) {
            $compile_id = '';

            if (!empty(Yii::$app->request->queryParams['className'])) {
                $compile_id = md5(Yii::$app->request->queryParams['className']);
            }
            //Отдаем подшаблон Smarty и получаем строку для вывода
            $output = $smarty->fetch('@' . $result, $compile_id, $compile_id);
        }
    }

    return $output;
}