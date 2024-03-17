<?php

use app\models\Schedule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

use app\models\Team;
use kartik\select2\Select2;
use kartik\date\DatePicker;


/** @var yii\web\View $this */
/** @var app\models\ScheduleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

rmrevin\yii\fontawesome\AssetBundle::register($this);


use app\assets\CalendarAsset;
CalendarAsset::register($this);

$this->title = 'Generate';
$this->params['breadcrumbs'][] = $this->title;
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

<div class="schedule-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'work_date')->widget(DatePicker::classname(), [
        'name' => 'work_date',
        'type' => DatePicker::TYPE_INPUT,
        'value' => date('Y-m-d'),
        'options' => [
            'placeholder' => '- Pilih Tanggal -'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'todayBtn' => true,
        ]
    ]);    ?>

    <?php /*
    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_updated')->textInput() ?>

    <?= $form->field($model, 'date_deleted')->textInput() ?>
    */ ?>

    <?= $form->field($model, 'id_team')->widget(Select2::class, [
            'data' => Team::getList(),
            'options' => [
                'placeholder' => '- Pilih Team -',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ])->label('Team') ?>    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php 

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
CSS;

$this->registerCss($style);

$script = <<<JS

    


JS;


$this->registerJs($script, yii\web\View::POS_LOAD);
?>