<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 6/27/2016
 * Time: 3:59 PM
 */

namespace backend\assets;
use yii\web\AssetBundle;

class ChatAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/style.css',
        'css/style1.css',
        'css/bootstrap-switch.css'
    ];
    public $js = [
        'js/app.js',
        'js/bootstrap-switch.js',
        'js/libs/stickerpipe/js/stickerpipe.js',
        'js/libs/stickerpipe/js/stickerpipe.min.js',
        'js/libs/jquery.timeago.js',
        'js/quickblox.min.js',
        'js/config.js',
        'js/connection.js',
        'js/messages.js',
        'js/stickerpipe.js',
        'js/ui_helpers.js',
        'js/dialogs.js',
        'js/users.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'common\assets\AdminLte',
        'common\assets\Html5shiv'
    ];
} ?>