<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalStepKatalog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orginal-step-katalog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lang')->dropDownList(['1' => 'O`zbekcha', '2' => 'Ruscha']); ?>

    <?= $form->field($model, 'title')->textArea(['rows' => 6, 'value' => base64_decode($model->title)]) ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
