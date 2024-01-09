<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use conquer\select2\Select2Widget;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use yii\jui\DatePicker;

?>

<style>
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
    .field-orders-id {
        display: none;
    }
</style>

<div class="col-12" style="padding: 0 15px;">
    <?php $form = ActiveForm::begin([
        'id' => 'order-graph-form',
        'options' => ['class' => 'form-horizontal'],
    ]);

    $currentDate = date("Y-m-d H:i:s");

    echo $form->field($model, 'id')->hiddenInput();
    echo $form->field($model, 'title')->textInput(['class' => 'form-control', 'disabled' => true])->label('Buyurtma nomi');
    echo $form->field($branch, 'title')->textInput(['class' => 'form-control', 'disabled' => true])->label('Branch nomi');
    echo $form->field($model, 'user_id', [
            'inputOptions' => [
                'value' => $responsible,
            ],
        ])->textInput(['class' => 'form-control', 'disabled' => true])->label('Ma`sul shaxs');
    echo $form->field($model, 'dead_line')->widget(
        DateTimePicker::class, 
        [
            'options' => ['placeholder' => 'bitish sanasi'],
            'pluginOptions' => [
                'autoclose' => true,
                'startDate' => $currentDate,
                'format' => 'dd-mm-yyyy hh:ii:ss',
                'autocomplete' => 'off',
            ]
        ])->label('Buyurtma bitish sanasi');

    if (isset($brigadas) && !empty($brigadas)) {
        echo $form->field($model, 'feedback_user')->widget(Select2::classname(), [
            'data' => $brigadas,
            'options' => ['value' => $chosenone],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Buyurtmaga ma`sul brigada');
    }
    ?>
</div>

<div class="form-group" style="padding: 10px 0 0 0;">
    <div class="col-12 text-right">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success save-order-graph']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>