<?php

use app\models\Member;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MemberSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Members';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Member', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Name',
                'value' => function ($model) {
                    return strtoupper($model->name);
                }
            ],
            [
                'attribute' => 'id_team',
                'label' => 'Team',
                'value' => function ($model) {
                    return @$model->team ? strtoupper(@$model->team->name) : '-';
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
            // 'date_created',
            // 'date_updated',
            //'date_deleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Member $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
