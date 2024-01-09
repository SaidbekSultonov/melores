<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Clients */

$this->title = base64_decode($model->full_name);
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="clients-view">

    <h1><?=  Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('O`zgartirish', ['/index.php/clients/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('O`chirish', ['/index.php/clients/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Mijozni o`chirmoqchimisiz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'html',
                'label' => 'Familiya Ismi',
                'value' => function ($data) {
                    return base64_decode($data->full_name);
               },
            ],
            [
                'format' => 'html',
                'label' => 'Telefon raqami',
                'value' => function ($data) {
                    return base64_decode($data->phone_number);
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
                'format' => 'html',
                'label' => 'Filial',
                'value' => function ($data) {
                    return $data->branch->title;
               },
            ],
        ],
    ]) ?>

</div>
