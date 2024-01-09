<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RequiredMaterialOrder */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Majburiy fayllar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="required-material-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('O`zgartirish', ['index.php/required-material-order/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('O`chirish', ['index.php/required-material-order/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Faylni o`chirmoqchimisiz ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title:ntext',
        ],
    ]) ?>

</div>
