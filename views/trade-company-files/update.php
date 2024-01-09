<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCompanyFiles */

$this->title = 'Update Trade Company Files: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trade Company Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trade-company-files-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
