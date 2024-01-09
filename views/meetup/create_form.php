<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

?>
<link rel="stylesheet" href="../../../web/css/select2.min.css">

<div class="quiz-form container">
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-12">
            <h2><b><?= Html::encode($this->title) ?></b></h2>

        </div>

        <div class="col-md-12">
            <?= $form->field($question, 'penalty')->textInput(['type' => 'number','required'=>true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($question, 'question')->textarea(['rows' => 2,'required'=>true]) ?>
        </div>

        <div class="col-md-6">
            <div class="form-group field-quiz-users">
                <label class="control-label" for="users_select">Javob beruvchilar</label>
                <select name="Quiz[users_id][]" multiple="multiple" class="select2 form-control" required id="users_select" style="width: 100%">
                    <?php
                    if (isset($users) && !empty($users)) {
                        foreach ($users as $key => $value_select) {
                            ?>
                            <option <?php
                                if(isset($select_list) && !empty($select_list)) {
                                    $select = '';
                                    foreach ($select_list as $selected_val) {
                                        if ($key == $selected_val) {
                                            $select = 'selected';
                                        }
                                    }
                                    echo $select;
                                }
                            ?> value="<?php echo $key; ?>">
                            <?php echo $value_select; ?>      
                            </option>                
                        <?php
                        }
                    }
                    ?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success pull-right']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php


$js = <<<JS
	$(function(){    
        $("#users_select").select2({
            // tags: true,
            placeholder: "Javob beruvchini tanlang"
        });
    })

JS;


$this->registerJs($js);
?>