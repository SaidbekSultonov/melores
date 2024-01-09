<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCompanyFiles */

$this->title = $company->title_uz.' ma`lumotlarini kiritish';
$this->params['breadcrumbs'][] = ['label' => 'Trade Company Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-company-files-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'company' => $company,
    ]) ?>

</div>
