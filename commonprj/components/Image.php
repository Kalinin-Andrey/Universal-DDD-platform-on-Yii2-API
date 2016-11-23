<?php
/**
 * Created by Bogachev.Petr
 * Date: 07.06.2016
 *
 * Компонент - связка между виджетом commonprj\widgets\slimImage и Image Server
 */

namespace commonprj\components;

use yii\base\Component;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class Image
 * @package commonprj\components
 */
class Image extends Component
{
    /**
     * Получение пути из ID изображения
     *
     * Пример
     * для 1.jpg путь будет:
     * 000/000/000/000/000/000/1.jpg
     *
     * для 99999999.jpg:
     * 000/000/000/009/999/999/9.jpg
     *
     * @param int $id
     * @param bool $createDirs создание физических директорий
     * @return string
     * @throws ServerErrorHttpException
     */
    protected function getPathById(int $id, bool $createDirs = false): string
    {
        $filename = str_pad($id, 19, 0, STR_PAD_LEFT);
        $path = Yii::$app->params['pathToSaveImage'] . DIRECTORY_SEPARATOR;

        for ($i = 0; $i <= 15; $i += 3) {
            $path .= substr($filename, $i, 3);

            if ($createDirs) {

                if (!is_dir($path)) {

                    try {
                        mkdir($path, 0755);
                    } catch (\Exception $e) {
                        throw new ServerErrorHttpException(['msg' => $e->getMessage(), 'path' => $path]);
                    }
                }
            }
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path . substr($id, -1) . '.jpg';
    }

    /**
     * Получение урла по ID изображения
     *
     * @param int $id
     * @return string
     * @throws ServerErrorHttpException
     */
    protected function getUrlById(int $id): string
    {
        return Yii::$app->params['imageHost'] . DIRECTORY_SEPARATOR . $this->getPathById($id);
    }


    /**
     * Strips the "data:image..." part of the base64 data string so PHP can save the string as a file
     * @param $data
     * @return string
     */
    protected static function getBase64Data($data)
    {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
    }
}