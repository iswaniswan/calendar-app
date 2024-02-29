<?php

use app\models\Schedule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ScheduleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

rmrevin\yii\fontawesome\AssetBundle::register($this);


use app\assets\CalendarAsset;
CalendarAsset::register($this);

$this->title = 'Calendar App';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-danger" style="display:none" id="notification-wrapper">
    Notifikasi belum diizinkan. Klik untuk memperbolehkan notifikasi.
    <button id="btn-req-notification" class="btn btn-danger" style="position: absolute; top:8px; right:8px;">Allow</button>
</div>
<div class="schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-12">
            <div id="calendar"></div>
        </div>
    </div>

</div>


<?php 

$style = <<<CSS
    .calendar .day-names {
        border-bottom: 1px solid #aaa;
    }
    .calendar .days {
        border-bottom: 1px solid #aaa;
    }
CSS;

$this->registerCss($style);

$script = <<<JS

    const mockData = [
        {
            time:'2024-02-13T06:00:00 Z',
            cls:'bg-orange-alt',
            desc:'ISWANTO<br/>ADITYA'
        },
        {
            time:'2024-02-14T06:00:00 Z',
            cls:'bg-green-alt',
            desc:'ALGHIFFARY<br/>FAHMI'
        },
    ];

    function initNotification() {
        Notification.requestPermission().then(perm => {
            if (perm !== 'granted') {
                $('#notification-wrapper').fadeIn("slow");
            } else {
                console.log('permission granted');
                showNotification('Calendar App', 'Thank You ;D');
            }
        })
    }

    $('#btn-req-notification').on('click', function() {
        Notification.requestPermission().then(function(permission) {
            // Handle the user's decision
            if (permission === "granted") {
                $('#notification-wrapper').fadeOut('slow');                
            } else {
                window.open('chrome://settings/content/notifications')
            }
        });  
        console.log('req notification');
    });

    function showNotification(title, message) {
        const notification = new Notification(title, {
            body: message,
            icon: "your-icon.png", // Optional: provide a custom icon path
        });
    }

    $(document).ready(function() {
        const cal = Calendar('calendar');
        const spr = Spinner('calendar');
        spr.renderSpinner().delay(0);
        cal.bindData(mockData); 
        cal.render();

        setTimeout(() => {
            initNotification();            
        }, 1000);
    })

    console.log(mockData);


JS;


$this->registerJs($script, yii\web\View::POS_LOAD);
?>