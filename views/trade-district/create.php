<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeDistrict */

$this->title = 'Hudud qo`shish';
$this->params['breadcrumbs'][] = ['label' => 'Trade Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-district-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
