<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Majburiy Fayl';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="required-material-order-index">
    <div class="row">
        <div class="col-md-6">
            <h2>
                <?= Html::encode($this->title) ?>
            </h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Majburiy fayl qo`shish', ['index.php/required-material-order/create'], ['class' => 'btn btn-success  pull-right']) ?>
            </h2>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
            //'chat_id',
            //'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/required-material-order/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
