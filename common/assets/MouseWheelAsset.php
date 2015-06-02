<?php

namespace common\assets;

use yii\web\AssetBundle ;

class MouseWheelAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-mousewheel';
    public $css = [
        
    ];
    public $js = [
        'jquery.mousewheel.min.js',
    ];
}
