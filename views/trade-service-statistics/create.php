<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeServiceStatistics */

$this->title = 'Create Trade Service Statistics';
$this->params['breadcrumbs'][] = ['label' => 'Trade Service Statistics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-service-statistics-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
