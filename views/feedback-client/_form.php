<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feedback-client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'value' => base64_decode($model->title)]) ?>

    <?= $form->field($model, 'type')->dropDownList(['1' => 'O`zbekcha savol', '2' => 'Ruscha savol']); ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
