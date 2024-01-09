<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCategory */

$this->title = "O'zgartirish: " . $model->title_uz;
$this->params['breadcrumbs'][] = ['label' => 'Trade Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trade-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
