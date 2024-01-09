<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrginalStepKatalog;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalKatalog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orginal-katalog-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'type')->dropDownList(['photo' => 'Rasm', 'text' => 'Tekst', 'video'=>'Video']); ?>

    <?= $form->field($model, 'lang')->dropDownList(['1' => "O'zbekcha", '2' => 'Ruscha']); ?>

    <?php
        $selectCategory = "SELECT a.id, convert_from(DECODE(a.title, 'base64'), 'UTF8') as title FROM orginal_step_katalog as a";
        $category = Yii::$app->db->createCommand($selectCategory)->queryAll();
        $listData=ArrayHelper::map($category,'id', 'title');
        echo $form->field($model, 'category')->dropDownList($listData)->label('Kategoriya');
    ?>

    <?= $form->field($model, 'file_id')->fileInput(['maxlength' => true])->label('Media') ?>

    <?= $form->field($model, 'caption')->textarea(['rows' => 6, 'value' => base64_decode($model->caption)]) ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).on("change", "#orginalkatalog-type", function() {
        var val =  $(this).val();
        if (val == "text") {
            $(".field-orginalkatalog-file_id").fadeOut()
            $(".field-orginalkatalog-caption").find("input,textarea").val('');
        }
        else if (val == "video") {
            $(".field-orginalkatalog-file_id").fadeIn()
            $(".field-orginalkatalog-caption").find("input,textarea").val('');
        }

        else if (val == "photo") {
            $(".field-orginalkatalog-file_id").fadeIn()
            $(".field-orginalkatalog-caption").find("input,textarea").val('');
        }
    })
</script>
