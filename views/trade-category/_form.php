<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trade-category-form">

    <?php $form = ActiveForm::begin(["id" => "trade_category_id"]); ?>
    <div class="row">
    	<div class="col-md-6">
   			<?= $form->field($model, 'title_uz')->textInput(['maxlength' => true]) ?>
    	</div>
    	<div class="col-md-6">
    		<?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
    	</div>
    </div>

    <div class="form-group text-right">
    	<button type="button" class="btn btn-default" data-dismiss="modal">Yopish</button>
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success','id' => 'add_trade_category']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
