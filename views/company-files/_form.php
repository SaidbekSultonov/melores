<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\ServicesTypes;
use app\models\District;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyFiles */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .ddd {
        display: none;
    }
</style>
<div class="company-files-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title_uz')->textInput(['maxlength' => true]) ?>
            <?php
            $categories = ServicesTypes::find()->all();
            $listData = ArrayHelper::map($categories, 'id', 'title_uz');
            echo $form->field($model, 'services_type_id')->dropDownList($listData, ['prompt' => 'Bo`lim tanlang']);
            ?>
            <?=
            $form->field($model, 'type')->dropDownList([
                'photo' => 'Rasm',
                'text' => 'Tekst',
                'location' => 'Lokatsiya',
                'video' => 'Video',
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
            <?php

            $categories = District::find()->all();
            $listData = ArrayHelper::map($categories, 'id', 'title_uz');
            echo $form->field($model, 'district_id')->dropDownList($listData, ['prompt' => 'Hudud tanlang']);

            ?>
            <div class="img">
                <label for="">Rasm yoki Video</label>
                <?= $form->field($model, 'imageFile')->fileInput(['class' => 'form-control', 'multiple' => ''])->label(false) ?>
            </div>

        </div>

        <div class="col-md-6 ddd">
            <?= $form->field($model, 'long')->textInput()->label('Longtitude') ?>
        </div>
        <div class="col-md-6 ddd">
            <?= $form->field($model, 'lat')->textInput()->label('Latitude') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>


    $(function () {
        $('select').select2()

        $(document).on('change', '#companyfiles-type', function () {
            let type = $(this).val()
            switch (type) {
                case 'location':
                    $('.ddd').fadeIn()
                    $('.img').css('opacity', '0')
                    break;
                case 'photo':
                    $('.ddd').fadeOut()
                    $('.img').css('opacity', '1')
                    break;
                case 'text':
                    $('.ddd').fadeOut()
                    $('.img').css('opacity', '0')
                    break;
                case 'video':
                    $('.ddd').fadeOut()
                    $('.img').css('opacity', '0')
                    break;

            }
        })
    })
</script>
