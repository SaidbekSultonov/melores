<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderGraph */

$this->title = 'Create Order Graph';
$this->params['breadcrumbs'][] = ['label' => 'Order Graphs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-graph-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
