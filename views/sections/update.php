<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sections */

$this->title = 'O`zgartirish: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sections-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'section_time' => $section_time,
    ]) ?>

</div>
