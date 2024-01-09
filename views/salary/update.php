<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SalaryAmount */

$this->title = 'Update Salary Amount: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Salary Amounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="salary-amount-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
