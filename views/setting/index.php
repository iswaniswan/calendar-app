<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Team;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;

?>
<h1 class="mb-5">Settings</h1>

<div class="setting-generate-schedule">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Generate Schedule</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['schedule/generate'],
                        'method' => 'post',
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>

                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($schedule, 'work_date_start')->widget(DatePicker::classname(), [
                                'name' => 'work_date_start',
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
                            ])->label('Date Start') ?> 
                        </div>
                        <div class="col-6">
                            <?= $form->field($schedule, 'work_date_end')->widget(DatePicker::classname(), [
                                'name' => 'work_date_end',
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
                            ])->label('Date End') ?> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($schedule, 'id_team')->widget(Select2::class, [
                                'data' => Team::getListWithColor(),
                                'options' => [
                                    'placeholder' => '- Pilih Team Awal Sesuai Urutan -',
                                ],
                                'pluginOptions' => [
                                    'templateResult' => new JsExpression('function(data) {
                                        const _arr = data.text.split(";");
                                        const [_text, _color] = _arr;
                                        return "<div><span class=\'me-3\' style=\'width:10%; display:inline-flex; height:2vh; background-color:"+_color+"\'></span>"+_text+"</div>";
                                    }'),
                                    'templateSelection' => new JsExpression('function(data) {
                                        const _arr = data.text.split(";");
                                        const [_text, _color] = _arr;
                                        return _text;
                                    }'),
                                    'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
                                    'allowClear' => false
                                ],
                            ])->label('Initial team start') ?>    
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <?= Html::submitButton('Generate', ['class' => 'btn btn-primary']) ?>                            
                        </div>
                    </div>
                    
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>