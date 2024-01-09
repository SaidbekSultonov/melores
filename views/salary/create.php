<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SalaryAmount */

$this->title = 'Create Salary Amount';
$this->params['breadcrumbs'][] = ['label' => 'Salary Amounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salary-amount-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
