<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Team */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="team-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('O`zgartirish', ['/index.php/team/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('O`chirish', ['/index.php/team/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Brigadani o`chirmoqchimisiz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'html',
                'label' => 'Nomi',
                'value' => function ($data) {
                    return base64_decode($data->title);
               },
            ],
            [
                'format' => 'html',
                'label' => 'Holati',
                'value' => function ($data) {
                    return (($data->status == 1) ? '<span class="label label-success">Faol</span>' : '<span class="label label-danger">Arxiv</span>');
               },
            ],
        ],
    ]) ?>

</div>
