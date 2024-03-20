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

    /** mobile view */
    @media (max-width: 991px) {
        .calendar.noselect.p-5 {
            padding-left: unset !important;
            padding-right: unset !important;
        }
    }
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

<!-- Modal -->
<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Confirmation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h3>Enable Push Notification Service?</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id="btn-accept-notification-sevice" class="btn btn-primary">Accept</button>
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
    })

JS;

$this->registerJs($script, yii\web\View::POS_LOAD);

?>
