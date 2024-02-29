<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Schedule $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="schedule-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'work_date',
            // 'date_created',
            // 'date_updated',
            // 'date_deleted',
            [
                'attribute' => 'id_team',
                'format' => 'raw',
                'label' => 'Team',
                'value' => function ($model) {
                    return @$model->team ? strtoupper($model->team->name) : '-';
                }
            ],
            [
                'attribute' => 'id_team',
                'format' => 'raw',
                'label' => 'Member',
                'value' => function ($model) {
                    if (@$model->allMember != null) {
                        $members = [];
                        foreach ($model->allMember as $member) {
                            $members[] = $member->name;
                        }
                        
                        return join(", ", $members);
                    }
                    return '-';
                }
            ],
        ],
    ]) ?>
    

</div>
