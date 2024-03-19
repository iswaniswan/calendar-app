<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Subscription $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="subscription-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'end_point')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expiration_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'p256dh')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'raw')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'f_status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
