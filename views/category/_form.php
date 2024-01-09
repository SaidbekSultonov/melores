<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Branch;
use yii\helpers\ArrayHelper;
use app\models\BranchCategories;
$branchs = Branch::find()->all();


?>

<div class="category-form">
    <?php if (Yii::$app->session->hasFlash('danger')): ?>
        <div class="alert alert-danger alert-dismissable">
             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
             <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
             <?= Yii::$app->session->getFlash('danger') ?>
        </div>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <br>
    <label for="">Filiallar</label>
    <select name="Category[branchs][]" multiple="multiple" id="branch" class="form-control">
        <?php foreach ($branchs as $key => $value): $selected = ''; ?>
            <?php if (isset($model->id)): ?>
                
                <?php $find = BranchCategories::find()->where(['category_id' => $model->id, 'branch_id' => $value->id])->one(); ?>

                <?php if (isset($find)): ?>
                    <?php $selected = 'selected'; ?>
                <?php endif ?>

            <?php endif ?>
            <option <?php echo $selected; ?> value="<?php echo $value->id ?>">
                <?php echo $value->title ?>
            </option>
        <?php endforeach ?>
    </select>
    <br>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJs(
'
$(function(){
        $("#branch").select2();
    })
   

', yii\web\View::POS_READY); ?>

