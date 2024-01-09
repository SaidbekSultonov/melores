<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RequiredMaterialOrder */

$this->title = 'Majburiy Fayllar qo`shish';
$this->params['breadcrumbs'][] = ['label' => 'Majburiy Fayllar qo`shish', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="required-material-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
