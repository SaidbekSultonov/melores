<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Filiallar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Filial qo`shish', ['index.php/branch/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'format' => 'html',
                'label' => 'Sotuvchi',
                'value' => function ($data) {
                    return (($data->user) ? $data->user->second_name : '');
               },
            ],
            [
                'format' => 'html',
                'label' => 'Holati',
                'value' => function ($data) {
                    return (($data->status == 1) ? '<span class="label label-success">Faol</span>' : '<span class="label label-danger">Arxiv</span>');
               },
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/branch/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>