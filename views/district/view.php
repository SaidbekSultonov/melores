<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\District */

$this->title = $model->title_uz;
$this->params['breadcrumbs'][] = ['label' => 'Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="district-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('O`zgartirish', ['/index.php/district/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('O`chirish', ['/index.php/district/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title_uz',
            'title_ru',
            [
                'format' => 'html',
                'header' => 'Holati',
                'attribute' => 'status',
                'value' => function ($data) {
                    if ($data->status == 1){
                        return '<span class="label label-success">Faol</span>';
                    }
                    else {
                        return '<span class="label label-danger">Nofaol</span>';
                    }
                },
            ],
        ],
    ]) ?>

</div>
