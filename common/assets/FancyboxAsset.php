<?php

namespace common\assets;

use yii\web\AssetBundle ;

class FancyboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox/source';
    public $css = [
        'jquery.fancybox.css',
    ];
    public $js = [
        'jquery.fancybox.pack.js',
    ];
}
