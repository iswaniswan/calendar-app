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
use rmrevin\yii\fontawesome\FA;


use app\assets\CalendarAsset;
CalendarAsset::register($this);

$this->title = 'Jadwal Kebersihan MIS - Lokasi LG1';
$this->params['breadcrumbs'][] = $this->title;

$style = <<<CSS
    #main .container {
        padding: 30px 15px 20px;
    }

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
    .event {
        text-wrap: wrap !important;
    }

    /* * custom color class
    .bgc-alpha {
        background-color: #000
    } */

    /** mobile view */
    @media (max-width: 991px) {
        .calendar.noselect.p-5 {
            padding-left: unset !important;
            padding-right: unset !important;
        }
    }

    .notification-bell {
        width: 32px;
        float: right;
        position: absolute;
        right: 3vw;
        top: .5vh;
        display: none;
    }
    .schedule-index {
        display: block;
        position: relative;
    }
CSS;

foreach ($dataSchedule as $arr) {
    $style .= "." . $arr['cls'] . "{ background-color: " . $arr['color_hex'] ." } " ;
}

$this->registerCss($style);

$dataSchdule = json_encode($dataSchedule);

?>

<div class="alert alert-danger" style="display:none" id="notification-wrapper">
    Akses notifikasi belum diizinkan. Klik untuk panduan akses notifikasi.
    <button data-bs-toggle="modal" data-bs-target="#modalPermission" class="btn btn-danger" style="position: absolute; top:8px; right:8px;">Show</button>
</div>
<div class="schedule-index">
    <h1 class="text-center text-secondary" style="font-family: cursive !important;"><?= Html::encode($this->title) ?></h1>

    <!-- conditional icon -->
    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#serviceEnable" class="notification-bell text-success">
        <i class="fa fa-bell" style="
                border: 1px solid rgba(var(--bs-success-rgb), var(--bs-text-opacity)) !important;
                padding: .5rem;
                border-radius: 100%;
                font-size: 1.5rem;
            "></i>
    </a>
    <a href="javascript:void(0)" class="notification-bell text-danger" onclick="isPushNotificationServiceEnable()">
        <i class="fa fa-bell-slash" style="
                border: 1px solid rgba(var(--bs-danger-rgb), var(--bs-text-opacity)) !important;
                padding: .5rem;
                border-radius: 100%;
                font-size: 1.5rem;
            "></i>
    </a>

    <div class="row">
        <div class="col-12">
            <div id="calendar"></div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalPermission" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelPermission">Petunjuk izin push notifikasi </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>Google Chrome</h3>
                <p>Ikuti langkah-langkah pada tautan berikut ini: 
                    <a href="https://support.google.com/chrome/answer/3220216?hl=id&co=GENIE.Platform%3DAndroid" target="_blank">Google Chrome</a>
                </p>
                <hr>
                <h3>Mozilla Firefox</h3>
                <p>Ikuti langkah-langkah pada tautan berikut ini: 
                    <a href="https://support.mozilla.org/id/kb/notifikasi-web-push-pada-firefox " target="_blank">Mozilla Firefox</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Enable Push Notification Service?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>
                Aktifkan layanan push notifikasi untuk mendapatkan informasi jadwal tugas kebersihan setiap hari pukul 16.00 WIB 
                <br/><br/>
                <span class="text-secondary">*senin - jumat diluar weekend & hari libur</span>
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id="btn-accept-notification-sevice" class="btn btn-primary">Accept</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal event-->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true" style="color:#fff;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="calendarHeader">
                <h5 class="modal-title" id="calendarModalLabel">Jadwal Kebersihan Hari ini</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="mix-blend-mode: lighten; background-color:#fff;"></button>
            </div>
            <div class="modal-body" id="calendarBody">
                <h3 id="calendarContent"></h3>
            </div>
        </div>
    </div>
</div>

<!-- Modal event-->
<div class="modal fade" id="serviceEnable" tabindex="-1" aria-hidden="true" style="color:#fff;">
    <div class="modal-dialog bg-white" style="border-radius: 8px;">
        <div class="modal-content bg-white text-center text-success">
            <div class="modal-body">
                <h5>Service is Active</h5>
            </div>
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
    // console.log(dataSchedule);    

    $(document).ready(function() {
        const cal = Calendar('calendar');
        const spr = Spinner('calendar');
        spr.renderSpinner().delay(0);
        cal.bindData(dataSchedule); 
        cal.render();

        const calendarContainers = $('#calendar .inside');
        calendarContainers.each(function() {
            const t = $(this);
            t.on('click', (event) => {
                // Retrieve the date from the data attribute
                const date = new Date(event.target.dataset.date);
                console.log('Clicked datex:', date);                
            });
        })
    })

JS;

$this->registerJs($script, yii\web\View::POS_LOAD);

?>
