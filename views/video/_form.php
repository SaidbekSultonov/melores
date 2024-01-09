<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="video-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(['photo' => 'Rasm', 'text' => 'Tekst', 'video'=>'Video', 'document'=>'Dakument'], ['class' => 'form-control select2']); ?>

    <?= $form->field($model, 'imageFile')->fileInput(['class' => 'form-control', 'multiple' => 'multiple'])->label(false)?>

    <?= $form->field($model, 'caption')->textArea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $('select').select2()
    
    $(document).on("change", "#video-type", function() {
        var val =  $(this).val();
        if (val == "text") {
            $("#video-imagefile").fadeOut()
            $(".field-video-caption").fadeIn()
            $(".field-video-caption").find("input,textarea").val('');
        }
        else if (val == "photo") {
            $("#video-imagefile").fadeIn()
            $(".field-video-caption").fadeIn()
            $(".field-video-caption").find("input,textarea").val('');
        }

        else if (val == "video") {
            $("#video-imagefile").fadeOut()
            $(".field-video-caption").fadeIn()
            $(".field-video-caption").find("input,textarea").val('');
        }
        
        else if (val == "document") {
            $("#video-imagefile").fadeIn()
            $(".field-video-caption").fadeIn()
            $(".field-video-caption").find("input,textarea").val('');
        }
    })
JS;

$this->registerJs($js);

?>