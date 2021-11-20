<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main application asset bundle
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/main.css',
        'css/accordion.css'
    ];
    public $js = [
        'js/accordion.js',
        'js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function registerAssetFiles($view) {

        parent::registerAssetFiles($view);

        $manager = $view->getAssetManager();
        $view->registerJsFile(
            $manager->getAssetUrl(
                $this,
                'js/html5shiv.min.js'
            ),
            [
                'condition' => 'lte IE9',
                'position'=>View::POS_HEAD
            ]
        );
        $view->registerJsFile(
            $manager->getAssetUrl(
                $this,
                'js/respond.min.js'
            ),
            [
                'condition' => 'lte IE9',
                'position'=>View::POS_HEAD
            ]
        );
    }
}
