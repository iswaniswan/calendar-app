<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SubscriptionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="subscription-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'end_point') ?>

    <?= $form->field($model, 'expiration_time') ?>

    <?= $form->field($model, 'auth') ?>

    <?= $form->field($model, 'p256dh') ?>

    <?php // echo $form->field($model, 'raw') ?>

    <?php // echo $form->field($model, 'f_status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
