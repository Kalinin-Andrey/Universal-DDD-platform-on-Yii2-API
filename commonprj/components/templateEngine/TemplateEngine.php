<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 11.07.2016
 */

namespace commonprj\components\templateEngine;

use Yii;

/**
 * Class TemplateEngine
 * @package commonprj\components\templateEngine
 * Компонент TemplateEngine, используется плагином Smarty для вывода подшаблонов на месте соответственных плэйсхолдеров
 * Для работы нужно настроить компоненты в config:
 * 'templateEngineRest' => [
        'class' => 'commonprj\extensions\CustomRestClient',
        'url' => <API URL>,
        'authorization' => [
        'default' => [
            'login'     => 'login',
            'password'  => 'password',
            'method'    => 'basic',
            ],
        ]
    ],
    'templateEngine' => [
        'class' => 'commonprj\components\templateEngine\TemplateEngine',
    ]
 *  Настроить Smarty в компоненте приложения View
 * 'view' => [
    'renderers' => [
        'tpl' => [
            'class' => 'yii\smarty\ViewRenderer',
            'pluginDirs' => ['@commonprj/components/templateEngine/plugins'],
            //'cachePath' => '@runtime/Smarty/cache',
            ],
        ],
    ],
 *
 */
class TemplateEngine
{
    /**
     * @param string $sectionSysname
     * @param string $templateSysname
     * @param string $placeholderSysname
     * @return bool|string Возвращает путь к подшаблону, если хотя бы одна сущность не активна, возвращает false
     * Возвращает путь к подшаблону, если хотя бы одна сущность не активна, возвращает false
     */
    public function templateOutput(string $sectionSysname, string $templateSysname, string $placeholderSysname)
    {
        $result = false;
        $requestUri = 'section-layouts/' . $sectionSysname . '/' . $templateSysname . '/' . $placeholderSysname;
        $restResult = Yii::$app->templateEngineRest->get($requestUri);
        $status = Yii::$app->templateEngineRest->status();
        $arResult = json_decode($restResult, true);

        if ($status === 200) {
            $result = $arResult['subtemplate']['path'];
        } else {
            Yii::trace($status . ' ' . ($arResult['message'] ?? ''), __METHOD__);
        }

        return $result;
    }

}