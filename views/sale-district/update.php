<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SaleDistrict */

$this->title = "O'zgartirish: " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sale Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sale-district-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
