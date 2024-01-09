<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackUser */

$this->title = 'Savolni o`zgartirish: ' . base64_decode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Feedback Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="feedback-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
