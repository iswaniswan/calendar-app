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

$this->title = 'Jadwal Kebersihan MIS - Lokasi LG1';
$this->params['breadcrumbs'][] = $this->title;

$style = <<<CSS
    .calendar .day-names {
        border-bottom: 1px solid #aaa;
    }
    .calendar .days {
        border-bottom: 1px solid #aaa;
    }
    /* hide navbar and breadcrumb */
    #w0, .breadcrumb, #footer {
        display: none !important;
    }

    /* * custom color class
    .bgc-alpha {
        background-color: #000
    } */
CSS;

foreach ($dataSchedule as $arr) {
    $style .= "." . $arr['cls'] . "{ background-color: " . $arr['color_hex'] ." } " ;
}

$this->registerCss($style);

$dataSchdule = json_encode($dataSchedule);

?>

<div class="alert alert-danger" style="display:none" id="notification-wrapper">
    Notifikasi belum diizinkan. Klik untuk memperbolehkan notifikasi.
    <button id="btn-req-notification" class="btn btn-danger" style="position: absolute; top:8px; right:8px;">Allow</button>
</div>
<div class="schedule-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-12">
            <div id="calendar"></div>
        </div>
    </div>

</div>


<?php 

$script = <<<JS

    const mockData = [
        {
            time:'2024-03-13',
            desc:'ISWANTO<br/>ADITYA',
            cls: 'bgc-alpha'
        },
        {
            time:'2024-03-14T06:00:00 Z',
            cls:'bg-green-alt',
            desc:'ALGHIFFARY<br/>FAHMI'
        },
    ];

    const dataSchedule = $dataSchdule;
    console.log(dataSchedule);

    function initNotification() {
        Notification.requestPermission().then(perm => {
            if (perm !== 'granted') {
                $('#notification-wrapper').fadeIn("slow");
            } else {
                console.log('permission granted');
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
        cal.bindData(dataSchedule); 
        cal.render();
    })

    // console.log(mockData);

JS;


$this->registerJs($script, yii\web\View::POS_LOAD);
?>