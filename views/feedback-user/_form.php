<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feedback-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'value' => base64_decode($model->title)]) ?>

    <?= $form->field($model, 'type')->dropDownList(['1' => 'Akril uchun savol', '2' => 'Shpon uchun savol', '3' => 'Barcha mahsulot turi uchun savol']); ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
