<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCategory */

$this->title = 'Create Trade Category';
$this->params['breadcrumbs'][] = ['label' => 'Trade Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
