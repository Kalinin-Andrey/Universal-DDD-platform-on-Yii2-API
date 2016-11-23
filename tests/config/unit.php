<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 14.09.2016
 */

use yii\helpers\ArrayHelper;

return ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../../api/config/main.php'),
    require(__DIR__ . '/../../api/config/main-local.php'),
    require(__DIR__ . '/config.php')
);