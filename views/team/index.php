<?php

use app\models\Team;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TeamSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Team', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'order',
                'format' => 'raw',
                'label' => 'Order',
                'headerOptions' => ['style' => 'width:4%'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:16%'],
                'value' => function($model) {
                    return strtoupper(@$model->name);
                },
            ],
            [
                'attribute' => 'color_hex',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:8%'],
                'value' => function($model) {
                    $html = <<<HTML
                        <div style="background:$model->color_hex; width: 100%; min-height: 24px">

                        </div>
                    HTML;
                    return $html;
                },
            ],
            [
                'attribute' => 'id',
                'format' => 'raw',
                'label' => 'Member',
                'headerOptions' => ['style' => 'width:auto'],
                'value' => function($model) {
                    $allMember = @$model->allMember;
                    $arr = [];
                    if ($allMember != null) {
                        foreach ($allMember as $member) {
                            $arr[] = $member->name;
                        }

                        return join(", ", $arr);
                    }
                    return '-';
                },
            ],
            // 'date_created',
            // 'date_updated',
            //'date_deleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Team $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
