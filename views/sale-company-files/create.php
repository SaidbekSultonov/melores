<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SaleCompanyFiles */

$this->title = $company->title_uz." ma'lumotlarini kiritish";
$this->params['breadcrumbs'][] = ['label' => 'Sale Company Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-company-files-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
