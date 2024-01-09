<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalKatalog */

$this->title = "O'zgartirish: " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orginal Katalogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orginal-katalog-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
