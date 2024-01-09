<?php 
	use kartik\date\DatePicker;
?>

<div class="row">
	<div class="col-md-12">
		<label for="">Bo`lim ish vaqti</label>
		<input type="text" class="form-control" id="time" value="<?php echo $model->work_time ?>">
		<br>

		<label for="">O`zgarishning boshlanish sanasi</label>
		<?php 
			echo DatePicker::widget([
			'name' => 'start_date',
			'id' => 'start_date',
			'value' => $model->start_date,
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'startDate' => $model->start_date,
				'autoclose' => true,
				'autocomplete' => 'off', 

			]
		]);
		?>
		<br>
	</div>
</div>