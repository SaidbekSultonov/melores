<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCategory */

$this->title = $model->title_uz;
$this->params['breadcrumbs'][] = ['label' => 'Trade Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="trade-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a("O'zgartirish", ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <? if ($model->status == 1) {
            echo Html::a("O'chirish", ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Ushbu kategoriyani o`chirishni istaysizmi?',
                    'method' => 'post',
                ],
            ]);
        } else {
            echo Html::a("Faollashtirish", ['activate', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Ushbu kategoriyani faollashtirishni istaysizmi?',
                    'method' => 'post',
                ],
            ]);
        } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title_uz',
            'title_ru',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->status == 1) {
                        return '<span class="label label-success">Faol</span>';
                    } else {
                        return '<span class="label label-danger">Nofaol</span>';
                    }
                }
            ],
        ],
    ]) ?>

</div>
