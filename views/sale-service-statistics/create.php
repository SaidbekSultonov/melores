<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SaleServiceStatistics */

$this->title = 'Create Sale Service Statistics';
$this->params['breadcrumbs'][] = ['label' => 'Sale Service Statistics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-service-statistics-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
