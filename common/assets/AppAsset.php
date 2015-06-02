<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\assets;

use Yii;
use yii\web\AssetBundle;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class AppAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $sourcePath = '@common/assets/assets';
    public $css = [
        'css/site.min.css',
        'css/waitMe.min.css',
        'css/ytLoad.jquery.min.css',
    ];
    public $js = [
        'js/main.min.js',
        'js/waitMe.min.js',
        'js/jquery.transit.min.js',
        'js/ytLoad.jquery.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapThemeAsset',
        'common\assets\FancyboxAsset',
        'common\assets\MouseWheelAsset',
    ];
    
    public function __construct($config = array()) {
        parent::__construct($config);
        if (Yii::$app->helper->isRtl()){
            $this->css[] = 'css/bootstrap-rtl.min.css';
        }
        if (Yii::$app->language == 'fa-IR'){
            $this->css[] = 'css/font.min.css';
        }
    }
}
