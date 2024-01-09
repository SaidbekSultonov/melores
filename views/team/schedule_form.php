<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TeamSchedule;
use kartik\datetime\DateTimePicker;
use app\models\Team;
use app\models\Orders;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Team */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="team-form">
	<?php $form = ActiveForm::begin(); ?>

	<?php 
        $branchs = Orders::find()->all();
    ?>

	<label for="">Buyurtma <small class="error_branch hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
	<select name="TeamSchedule[order_id]" class="form-control select2" id="branch" data-placeholder="Buyurtmani tanlang" style="width: 100%">
        <option value=""></option>
        <?php foreach ($branchs as $key => $value): ?>
            <option value="<?php echo $value->id; ?>">
                <?php echo $value->title; ?>      
            </option>
        <?php endforeach ?>
	</select><br><br>

	<?php 
        $branchs = Team::find()->all();
    ?>

	<label for="">Brigada <small class="error_branch hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
	<select name="TeamSchedule[team_id]" class="form-control select2" id="branch" data-placeholder="Brigadani tanlang" style="width: 100%">
        <option value=""></option>
        <?php foreach ($branchs as $key => $value): ?>
            <option value="<?php echo $value->id; ?>">
                <?php echo base64_decode($value->title); ?>      
            </option>
        <?php endforeach ?>
	</select><br><br>

    <?php echo $form->field($model, 'date')->widget(
	    DateTimePicker::class, 
	    [
	    	'name' => 'Section[Order_1][times]',
		    'id' => 'w1',
	        'options' => ['placeholder' => 'Tugash vaqti'],
	        'pluginOptions' => [
	            'format' => 'dd-mm-yyyy hh:ii',
	            'autoclose'=>true,
	        ]
	    ]
	    );
	?>
	<br>

    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>