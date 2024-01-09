<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User savollari';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-user-index">

     <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a("Savol qo'shish", ['/index.php/feedback-user/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>        
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'label' => 'Savol',
                'value' => function ($data) {
                    return base64_decode($data->title);
               },
            ],
            [
                'format' => 'html',
                'label' => 'Savol turi',
                'value' => function ($data) {
                    if ($data->type == 1) {
                        return 'Akril uchun savol';
                    } else if($data->type == 2) {
                        return 'Shpon uchun savol';
                    } else if ($data->type == 3) {
                        return 'Barcha mahsuot turi uchun savol';
                    }
               },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/feedback-user/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
