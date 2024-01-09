<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SaleDistrict */

$this->title = "Hudud qo'shish";
$this->params['breadcrumbs'][] = ['label' => 'Sale Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-district-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
