<?php
/**
 * Created by Bogachev.Petr
 * Date: 03.06.2016
 */

namespace commonprj\widgets\slimImage\assets;

use yii\web\AssetBundle;

class SlimImageAsset extends AssetBundle
{
    public $sourcePath = '@commonprj/widgets/slimImage/src/slim';
    
    public $css = ['slim.css'];
    
    public $js = ['slim.global.js'];
}