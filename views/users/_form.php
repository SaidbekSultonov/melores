<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UsersBranch;


$items = [
    1 => 'Admin',
    2 => 'Nazoratchi',
    3 => 'OTK',
    4 => 'Sotuvchi',
    5 => "Bo`lim boshlig`i",
    6 => 'Kro`ychi',
    7 => 'Bo`lim ishchisi',
    8 => 'HR',
    9 => 'Marketolog',
    10 => 'Brigadir',
];

?>
<?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
         <?= Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif; ?>

<div class="users-form">


    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => 13, 'type' => 'text']) ?>
        </div>
        <div class="col-md-6">
            <label for="">Filiallar</label>
            <select name="Users[user_branch][]" class="form-control select2" multiple="multiple" data-placeholder="Filial tanlang">
                <?php foreach ($branchs as $key => $value): ?>
                    <?php if (isset($model->id)): ?>
                        <?php $user_branch = UsersBranch::find()
                        ->where([
                            'branch_id' => $value->id,
                        ])
                        ->one(); ?>
                    <?php endif ?>
                    
                    <option <?php echo ((isset($user_branch)) ? 'selected' : '') ?> value="<?php echo $value->id; ?>">
                        <?php echo $value->title; ?>      
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-6">
            <?php echo $form->field($model, 'type')
                ->dropDownList(
                $items,           // Flat array ('id'=>'label')
                ['prompt'=>'']    // options
            ); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




