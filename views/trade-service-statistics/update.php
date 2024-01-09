<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeServiceStatistics */

$this->title = 'Update Trade Service Statistics: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trade Service Statistics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trade-service-statistics-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
