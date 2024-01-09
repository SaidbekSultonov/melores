<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalStepKatalog */

$this->title = "O'zgartirish: " . base64_decode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Orginal Step Katalogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orginal-step-katalog-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
