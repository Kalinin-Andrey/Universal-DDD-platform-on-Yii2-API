<?php
/**
 * Created by Bogachev.Petr
 * Date: 03.06.2016
 */

namespace commonprj\widgets\slimImage;

use yii\base\Widget;
use yii as Yii;

/**
 * Обёртка для Slim, Image Upload and Ratio Cropping Plugin
 * @see http://slim.pqina.nl/
 * 
 * Пример использования
 *
 * 1) Подключаем компонент image в конфиге приложения (main.php)
 * ```
 * 'components' => [
 *      ...
 *      'protectedBackImage' => [
 *          'class' => 'commonprj\extensions\CustomRestClient',
 *          'url' => 'http://protected-back-image.local',
 *      ],
 *      ...
 * ],
 * ```
 *
 * 2) В вьюхе запускаем виджет:
 * ```
 * <?= commonprj\widgets\slimImage\SlimImageWidget::widget(); ?>
 * ```
 * 
 * 3) Создаём экшен в приложении (по умолчанию image/upload).
 * @see  commonprj\widgets\slimImage\templates\ImageController.php
 *
 * Class SlimImageWidget
 * @package commonprj\widgets\slimImage
 */
class SlimImageWidget extends Widget
{
    const SERVICE                   = '/image/upload';
    const DEFAULT_INPUT_NAME        = 'image[]';
    const LABEL                     = 'Загрузить изображение';
    const BUTTON_EDIT_LABEL         = 'Изменить';
    const BUTTON_REMOVE_LABEL       = 'Убрать';
    const BUTTON_DOWNLOAD_LABEL     = 'Скачать';
    const BUTTON_UPLOAD_LABEL       = 'Загрузить';
    const BUTTON_CANCEL_LABEL       = 'Отмена';
    const BUTTON_CONFIRM_LABEL      = 'Ок';
    const STATUS_FILE_TYPE          = 'image/jpeg';
    const STATUS_NO_SUPPORT         = 'Ваш браузер не поддерживает работу с изображениями';
    const STATUS_CONTENT_LENGTH     = 'Скорее всего файл превышает допустимые размеры';
    const STATUS_INVALID_RESPONSE   = 'Серверная ошибка';
    const STATUS_UNKNOWN_RESPONSE   = 'Неизвестная ошибка';
    const STATUS_UPLOAD_SUCCESS     = 'Сохранено';
    const ON_SAVE                   = 'csrf';
    
    /**
     * @var string What ratio should the crop be in, default ratios are all supported
     * "16:10", "16:9", "5:3", "5:4", "4:3", "3:2" and "1:1"
     * Custom ratios can also be set, Slim will calculate the correct container size automatically. e.g. "14:2"
     * Set to "free" to allow the user to pick their own crop size.
     * Set the value to "input" to force the input image ratio.
     * The default value is "free"
     */
    public $dataRatio;
    /**
     * @var string Determine the target size of the resulting image.
     * For example "320,320" will scale down the image to fit within those dimensions.
     * The aspect ratio of the image will be respected.
     * By default Slim does not resize the output image.
     */
    public $dataSize;
    /**
     * @var string Determine minimum size of the crop in pixels
     * For example "640,480" will make sure the cropped image is at least 640 pixels by 480 pixels big.
     * By default Slims minimum size is 100 by 100 pixels this prevents the controls of the cropper from overlapping.
     */
    public $dataMinSize;
    /**
     * @var string When set, the cropped data will be sent to the set URL using AJAX.
     * Slim contains an example upload PHP file which is also used on this website.
     * An upload button will appear unless the data-push property is set to true.
     * The server can optionally return a JSON response to indicate a successful upload.
     * {
     * "status":"success",
     * "name":"uid_filename.jpg",
     * "path":"path/uid_filename.jpg"
     * }
     * By default data is posted to the server without AJAX.
     */
    public $dataService = self::SERVICE;
    /**
     * @var string When set to "true" will hide the upload button and will automatically upload cropped
     * images to the server.
     * Default is "false"
     */
    public $dataPush;
    /**
     * @var string What data package to send to the server.
     * The original input image, the cropped output image and/or the actions (crop position and size).
     * Can be set as a comma separated list "input, output, actions".
     * Note that when sending the input data along the resulting upload size can potentially get
     * two times as big as the original image (input + output).
     * Input and output meta data like filename, size, width and height will always be sent.
     * By default only the "output" and the user "actions" are sent.
     */
    public $dataPost;
    /**
     * @var string The name of the default input field.
     * The default value is in array format so multiple Slim cropper can post to the same input name.
     * This is only used if there's no hidden input to write to and no service url has been set.
     * By default this is set to "slim[]"
     */
    public $dataDefaultInputName = self::DEFAULT_INPUT_NAME;
    /**
     * @var string When set to "true" shows a button to download the cropped image.
     * Default is "false"
     */
    public $dataDownload;
    /**
     * @var string
     * When set to "false" will disable the edit button, effectively turning Slim into an auto cropping upload tool.
     * Default is "true"
     */
    public $dataEdit;
    /**
     * @var string The label shown in the drop area.
     * "Drop your image here"
     */
    public $dataLabel = self::LABEL;
    /**
     * @var string Set the label for the related button.
     * Note that the label is only shown on the cancel and confirm buttons in the image editor popup.
     * The other buttons feature an icon.
     */
    public $dataButtonEditLabel = self::BUTTON_EDIT_LABEL;
    /**
     * @var string Set the label for the related button.
     * Note that the label is only shown on the cancel and confirm buttons in the image editor popup.
     * The other buttons feature an icon.
     */
    public $dataButtonRemoveLabel = self::BUTTON_REMOVE_LABEL;
    /**
     * @var string Set the label for the related button.
     * Note that the label is only shown on the cancel and confirm buttons in the image editor popup.
     * The other buttons feature an icon.
     */
    public $dataButtonDownloadLabel = self::BUTTON_DOWNLOAD_LABEL;
    /**
     * @var string Set the label for the related button.
     * Note that the label is only shown on the cancel and confirm buttons in the image editor popup.
     * The other buttons feature an icon.
     */
    public $dataButtonUploadLabel = self::BUTTON_UPLOAD_LABEL;
    /**
     * @var string Set the label for the related button.
     * Note that the label is only shown on the cancel and confirm buttons in the image editor popup.
     * The other buttons feature an icon.
     */
    public $dataButtonCancelLabel = self::BUTTON_CANCEL_LABEL;
    /**
     * @var string Set the label for the related button.
     * Note that the label is only shown on the cancel and confirm buttons in the image editor popup.
     * The other buttons feature an icon.
     */
    public $dataButtonConfirmLabel = self::BUTTON_CONFIRM_LABEL;
    /**
     * @var string set the title for the related button.
     * By default the title contains the same value as the label
     */
    public $dataButtonEditTitle;
    /**
     * @var string set the title for the related button.
     * By default the title contains the same value as the label
     */
    public $dataButtonRemoveTitle;
    /**
     * @var string set the title for the related button.
     * By default the title contains the same value as the label
     */
    public $dataButtonDownloadTitle;
    /**
     * @var string set the title for the related button.
     * By default the title contains the same value as the label
     */
    public $dataButtonUploadTitle;
    /**
     * @var string set the title for the related button.
     * By default the title contains the same value as the label
     */
    public $dataButtonCancelTitle;
    /**
     * @var string set the title for the related button.
     * By default the title contains the same value as the label
     */
    public $dataButtonConfirmTitle;
    /**
     * @var string Useful for when you want to add additional class names to on of the action buttons.
     */
    public $dataButtonEditClassName;
    /**
     * @var string Useful for when you want to add additional class names to on of the action buttons.
     */
    public $dataButtonRemoveClassName;
    /**
     * @var string Useful for when you want to add additional class names to on of the action buttons.
     */
    public $dataButtonDownloadClassName;
    /**
     * @var string Useful for when you want to add additional class names to on of the action buttons.
     */
    public $dataButtonUploadClassName;
    /**
     * @var string Useful for when you want to add additional class names to on of the action buttons.
     */
    public $dataButtonCancelClassName;
    /**
     * @var string Useful for when you want to add additional class names to on of the action buttons.
     */
    public $dataButtonConfirmClassName;
    /**
     * @var string The maximum file size the user is allowed to upload in megabytes.
     * A value of 3.5 would limit the images to 3.5 megabytes.
     * Keep in mind that you'll also have to configure your server to accept certain file sizes.
     * By default no limit is set on file size
     */
    public $dataMaxFileSize;
    /**
     * @var string The status text shown when a user tries to upload a file that's too big.
     * The default is: "File is too big, maximum file size: $0 MB."
     * The $0 will be replaced by Slim with the value of the data-max-file-size attribute.
     */
    public $dataStatusFileSize;
    /**
     * @var string The status text shown when a user tries to upload an invalid file.
     * You can set the allowed file types using the accept attribute on the input element.
     * If it's not set, all generally supported image types are allowed (jpeg, png, gif and bmp).
     * <input type="file" accept="image/jpeg">
     * You can also supply multiple mime types by comma separating them: "image/jpeg, image/png"
     * The default text is as follows: "Invalid file type, expects: $0."
     * The $0 is replaced with the extensions of the mime types set in the accept property.
     */
    public $dataStatusFileType = self::STATUS_FILE_TYPE;
    /**
     * @var string The status text shown when the user is not running a modern web browser.
     * The following browsers and devices are supported.
     * Firefox
     * Chrome
     * Opera
     * Internet Explorer 10+
     * Safari OSX & iOS (Safari on Windows is no longer supported by Apple)
     * Android (Not all Android devices behave the same, should work on most modern Android devices)
     * On very old browsers (older than Internet Explorer 8), Slim won't load due to lack of JavaScript functionality.
     * The default text reads as follows: "Your browser does not support image cropping."
     */
    public $dataStatusNoSupport = self::STATUS_NO_SUPPORT;
    /**
     * @var string The status text shown when the user uploads a file that is too big for the server to handle.
     * Slim tries to interpret the error page the server returns if it contains the term "Content-Length"
     * it will assume it's because the image is too big.
     * Keep in mind that while the input file may fall below the limit of your server,
     * the output data could possibly be bigger (if you for example send both "input" and "output" to the server).
     * The default text reads as follows: "The file is probably too big"
     */
    public $dataStatusContentLength = self::STATUS_CONTENT_LENGTH;
    /**
     * @var string The error text shown when the server returns an invalid response,
     * in this case probably an invalid JSON format.
     * Default: "The server returned an invalid response"
     */
    public $dataStatusInvalidResponse = self::STATUS_INVALID_RESPONSE;
    /**
     * @var string The error text shown when the server returns an unknown response.
     * Default: "An unknown error occurred"
     */
    public $dataStatusUnknownResponse = self::STATUS_UNKNOWN_RESPONSE;
    /**
     * @var string The status text shown when the image is uploaded correctly.
     * Default: "Saved"
     */
    public $dataStatusUploadSuccess = self::STATUS_UPLOAD_SUCCESS;
    /**
     * @var string A callback method that when defined allows you to alter the image data after each transform.
     * Slim sends the following two parameters along: data and ready.
     * The data parameter contains the original data object.
     * The ready parameter is a function that when called will let Slim continue working on the data.
     * When calling the ready method you need to pass the altered (or original) data object along like so ready(data).
     * You could for example use this method to add watermarks to images, like shown in the demo below.
     *
     * <script>
     * function addWatermark(data, ready) {
     *
     * // get the drawing context for the output image
     * var ctx = data.output.image.getContext('2d');
     *
     * // draw our watermark on the center of the image
     * var size = data.output.width / 20
     * ctx.font = size + 'px sans-serif';
     *
     * var x = data.output.width * .5;
     * var y = data.output.height * .5;
     * var text = ctx.measureText('Slim is Awesome');
     * var w = text.width * 1.15;
     * var h = size * 1.75;
     *
     * ctx.fillStyle = 'rgba(0,0,0,.75)';
     * ctx.fillRect(
     * x - (w * .5),
     * y - (h * .5),
     * w, h
     * );
     * ctx.fillStyle = 'rgba(255,255,255,.9)';
     * ctx.fillText(
     * 'Slim is Awesome',
     * x - (text.width * .5),
     * y + (size * .35)
     * );
     *
     * // continue saving the data
     * ready(data);
     *
     * }
     * </script>
     */
    public $dataOnTransform;
    /**
     * @var string Similar to the data-on-transform attribute,
     * but data-on-save is only used just before saving the data and does not contain
     * the original canvas elements but only contains data uri's.
     */
    public $dataOnSave = self::ON_SAVE;

    public $dataDidUpload;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('slim', ['data' => $this->generateAttributes()]);
    }

    /**
     * Преобразование свойст класса в атрибуты тега "data-*"
     * @return string
     */
    private function generateAttributes(): string
    {
        $data = '';
        $params = get_object_vars($this);

        foreach ($params as $key => $value) {
            if (!is_null($value)) {
                $key = strtolower(preg_replace('/[A-Z]/', '-$0', $key));
                $data .= "\r\n" . $key . '="' . $value . '"';
            }
        }

        return $data;
    }
}


