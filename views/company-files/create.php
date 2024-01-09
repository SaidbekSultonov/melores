<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyFiles */

$this->title = $company->title_uz.' ma`lumotlarini kiritish';
$this->params['breadcrumbs'][] = ['label' => 'Company Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-files-create">

    <h2><?= Html::encode($this->title) ?></h2>
    <hr>
    <?= $this->render('_form', [
        'model' => $model,
        'company' => $company,
    ]) ?>

</div>
