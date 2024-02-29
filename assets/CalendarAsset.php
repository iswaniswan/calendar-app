<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CalendarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/library';
    public $css = [
        'dynamic-event-calendar-bootstrap/css/style.css',
        'dynamic-event-calendar-bootstrap/css/theme.css',
        'dynamic-event-calendar-bootstrap/css/calendar.css',
        'dynamic-event-calendar-bootstrap/css/spinner.css',
    ];
    public $js = [
        'moment/moment.min.js',
        'dynamic-event-calendar-bootstrap/js/spinner.js',
        'dynamic-event-calendar-bootstrap/js/calendar.js',
    ];
    public $depends = [       
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];

}
