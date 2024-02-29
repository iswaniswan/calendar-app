<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use app\models\Team;

/** @var yii\web\View $this */
/** @var app\models\Member $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'id_team')->textInput() ?>

    <?= $form->field($model, 'id_team')->widget(Select2::class, [
            'data' => Team::getList(),
            'options' => [
                'placeholder' => '- Pilih Team -',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ])->label('Team') ?>

    <?php /*
    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_updated')->textInput() ?>

    <?= $form->field($model, 'date_deleted')->textInput() ?>
    */ ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
