<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Branch;

/* @var $this yii\web\View */
/* @var $model app\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'value' => base64_decode($model->full_name)]) ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true,'value' => base64_decode($model->phone_number)]) ?>

    <?php 
        $branchs = Branch::find()->all();
        $listData=ArrayHelper::map($branchs,'id', 'title');
        echo $form->field($model, 'branch_id')->dropDownList($listData, ['prompt'=>'Filial tanlang'])->label('Filial');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
