<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2;

?>

<div class="col-12" style="padding: 0 15px;">
        
<?php $form = ActiveForm::begin([
    'id' => 'edit-respon-form',
    'options' => ['class' => 'form-horizontal'],
]);

echo $form->field($model2, 'user_id')->widget(Select2::classname(), [
    'data' => $users,
    'options' => [
        'placeholder' => 'ma`sul shaxs tanlang',
        'value' => $selected,
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);


?>
	<style>
        .field-brigada-id {
            display: none;
        }
		.form-group {
			margin-bottom: 0;
		}
        input {
            border-radius: 4px!important;
            width: 100%!important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc;
            border-color: #367fa9;
            padding: 1px 10px;
            color: #fff;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,0.7);
        }
	</style>

    <div class="form-group">
        <div class="col-12 text-right">
            <?= Html::submitButton('Yangilash', ['class' => 'btn btn-success update-info']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>