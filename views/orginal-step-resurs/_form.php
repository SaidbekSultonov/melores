<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalStepResurs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orginal-step-resurs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(['photo' => 'Rasm', 'text' => 'Tekst', 'video'=>'Video']); ?>

    <?= $form->field($model, 'lang')->dropDownList(['1' => "O'zbekcha", '2' => 'Ruscha']); ?>

    <?= $form->field($model, 'file_id')->fileInput(['maxlength' => true])->label('Media') ?>

    <?= $form->field($model, 'category')->dropDownList(['1' => "Биз ҳақимизда", '3' => 'Ижтимоий тармоқлар', '8' => "Манзил", '11' => 'Бизнинг ишлар', '12' => "Фойдали маслаҳатлар", '101' => 'MY SHOP', '102' => 'SKETCH SHOU', '103' => 'ABU VINES']); ?>

    <?= $form->field($model, 'caption')->textarea(['rows' => 6, 'value' => base64_decode($model->caption)]) ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).on("change", "#orginalstepresurs-type", function() {
        var val =  $(this).val();
        if (val == "text") {
            $(".field-orginalstepresurs-file_id").fadeOut()
            $(".field-orginalstepresurs-caption").find("input,textarea").val('');
        }
        else if (val == "video") {
            $(".field-orginalstepresurs-file_id").fadeIn()
            $(".field-orginalstepresurs-caption").find("input,textarea").val('');
        }

        else if (val == "photo") {
            $(".field-orginalstepresurs-file_id").fadeIn()
            $(".field-orginalstepresurs-caption").find("input,textarea").val('');
        }
    })
</script>