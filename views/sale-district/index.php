<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hududlar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a("Qo'shish", ['/index.php/sale-district/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>

    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title_uz',
            'title_ru',
            [
                'format' => 'html',
                'header' => 'Holati',
                'value' => function ($data) {
                    if ($data->status == 1){
                        return '<span class="label label-success">Faol</span>';
                    }
                    else {
                        return '<span class="label label-danger">Nofaol</span>';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['/index.php/sale-district/'.$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>
</div>