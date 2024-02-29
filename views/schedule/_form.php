<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Team;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Schedule $model */
/** @var yii\widgets\ActiveForm $form */
?>

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
