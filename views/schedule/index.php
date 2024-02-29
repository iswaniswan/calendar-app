<?php

use app\models\Schedule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ScheduleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Schedules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Schedule', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'work_date',
            // 'date_created',
            // 'date_updated',
            // 'date_deleted',
            //'id_team',
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
            [
                'attribute' => 'id_team',
                'label' => 'Color',
                'format' => 'raw',
                'value' => function ($model) {
                    $color = '#f1f1f1';
                    if (@$model->team) {
                        $color = $model->team->color_hex;
                    }
                    $html = <<<HTML
                        <div style="background:$color; width: 100%; min-height: 24px">

                        </div>
                    HTML;
                    return $html;
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Schedule $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
