	<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Branch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map($users, 'id', 'second_name'), ['prompt' => Yii::t('app', \Yii::t("app","Sotuvchi tanlang")), 'class' => 'form-control select2'])->label(\Yii::t("app","Sotuvchilar")) ?>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
    $('.select2').select2();

JS;
$this->registerJs($js);
?> 