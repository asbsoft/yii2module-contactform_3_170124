<?php

namespace asb\yii2\modules\contactform_3_170124\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author ASB <ab2014box@gmail.com>
 */
class AdminAsset extends AssetBundle
{
    public $css = [
        'contactform-admin.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset', // add only CSS - need to move up 'bootstrap.css' in <head>s of render HTML-results
    ];

    public function init() {
        parent::init();
        $this->sourcePath = __DIR__ . '/admin';
    }
}
