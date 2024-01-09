<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2;

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

    <div class="col-12" style="padding: 0 15px;">
        
<?php $form = ActiveForm::begin([
    'id' => 'brigada-form',
    'options' => ['class' => 'form-horizontal'],
]);

echo $form->field($model, 'id');
echo $form->field($model, 'title_uz');
echo $form->field($model, 'user_id')->widget(Select2::classname(), [
    'data' => $brigadirs,
    'options' => ['placeholder' => 'brigadir tanlang', 'value' => $brigadir],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
echo $form->field($model2, 'user_id')->widget(Select2::classname(), [
    'data' => $users,
    'options' => [
        'multiple' => true,
        'placeholder' => 'ishchi tanlang',
        'value' => $selected,
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);

?>
    </div>

    <div class="form-group">
        <div class="col-12 text-right">
            <?= Html::submitButton('Yangilash', ['class' => 'btn btn-success update-info']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<?php
    $js = <<<JS
        $("#users").select2({
            tags: true,
        })
JS;
    $this->registerJs($js); ?>

