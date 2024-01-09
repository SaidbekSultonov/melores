<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2;

?>

<div class="col-12">

	<style>
		.form-group {
			width: 100%;
			margin: 0!important;
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
        
<?php $form = ActiveForm::begin([
    'id' => 'person-brigada-form',
    'options' => ['class' => 'form-horizontal'],
]); ?>

<input type="text" value="<?php echo $person['second_name'] ?>" class="form-control" disabled>

<?php
echo $form->field($model, 'user_id')->hiddenInput(['value' => $person['id']])->label(false);
echo $form->field($model, 'brigada_id')->widget(Select2::classname(), [
    'data' => $brigada,
    'options' => ['multiple' => true, 'placeholder' => 'brigada tanlang'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);

?>
    </div>
<?php ActiveForm::end(); ?>

<?php
    $js = <<<JS
        $("#birgada").select2({
            tags: true,
        })
JS;
    $this->registerJs($js); ?>