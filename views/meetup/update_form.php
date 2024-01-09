<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['id' => 'form-update']); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($quiz, 'question')->textInput(['maxlength' => true,'name' => 'Quiz[question]']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <label class="control-label" for="users_select">Javob beruvchilar</label>
            <select name="Quiz[users][]" multiple=true class="select222 form-control" style="width: 100%">
                <?php
                if (isset($users_select) && !empty($users_select)) {
                    foreach ($users_select as $value_select) {
                        ?>
                        <option <?php
                            if(isset($users) && !empty($users)) {
                                $select = '';
                                foreach ($users as $selected_val) {
                                    if ($value_select['id'] == $selected_val['user_id']) {
                                        $select = 'selected';
                                    }
                                }
                                echo $select;
                            }
                            $id = 0;
                            $title = '';
                            if (isset($value_select['id']) && !empty($value_select['id'])) {
                                $id = $value_select['id'];
                            }
                            if (isset($value_select['second_name']) && !empty($value_select['second_name'])) {
                                $title = $value_select['second_name'];
                            }
                        ?> value="<?= $id ?>"><?=$title; ?></option>                
                    <?php
                    }
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            $penalty_sum = 1000;
            if (isset($penalty['penalty']) && !empty($penalty['penalty'])) {
                $penalty_sum = $penalty['penalty'];
            }
            ?>
            <label class="control-label">Jarima</label>
            <input type='number' name="Quiz[penalty]" class="form-control" value='<?=$penalty_sum?>'>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
<?php

$js = <<<JS
	$(function(){  
        $(".select222").select2({
            placeholder: "Foydalanuvchi tanlang"
        });
    })

JS;

$this->registerJs($js);

?>