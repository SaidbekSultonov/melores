<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orginal Step Katalog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orginal-step-katalog-index">

    <div class="row">
        <div class="col-md-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Post yasash', ['/index.php/orginal-step-katalog/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'label' => 'Matn tili',
                'value' => function ($data) {
                    if ($data->lang == 1) {
                        return "O'zbekcha";
                    } else if($data->lang == 2) {
                        return 'Ruscha';
                    }
                },
            ],
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'label' => 'Matn',
                'value' => function ($data) {
                    return base64_decode($data->title);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/orginal-step-katalog/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
