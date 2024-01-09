<?php 
	use kartik\datetime\DateTimePicker;
?>
<table class="table table-striped table-bordered">
	<tr>
		<th>Buyurtma nomi</th>
		<td><?php echo $order_step->order->title ?></td>
	</tr>
	<tr>
		<th>Rejadagi chiqish vaqti</th>
		<td><?php echo date('d-m-Y H:i',strtotime($order_step->deadline)) ?></td>
	</tr>
	<tr>
		<th>Realdagi kirish vaqti</th>
		<td>
			<?php if (isset($section_order)): ?>
				<?php echo date('d-m-Y H:i',strtotime($section_order->enter_date)) ?>		
			<?php else: ?>
				<span class="label label-default">Bu bo'limga hali yetib kelmagan</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Realdagi chiqish vaqti</th>
		<td>
			<?php if (isset($section_order) && $section_order->exit_date !== NULL): ?>
				<?php echo date('d-m-Y H:i',strtotime($section_order->exit_date)) ?>		
			<?php else: ?>
				<span class="label label-success">Hozircha chiqib ketmagan</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Masul shaxs</th>
		<td>
			<?php echo $order_step->section_minimal->user->second_name ?>
		</td>
	</tr>
	<tr>
		<th>Brigadir</th>
		<td>
			<?php if (isset($order_responsibles)): ?>
				<?php echo $order_responsibles->user->second_name ?>
			<?php else: ?>
				<span class="label label-success">Brigadaga berilmagan yoki Masul shaxs o'zi brigadir</span>
			<?php endif ?>
		</td>
	</tr>
</table>

<?php $min_date = date("Y-m-d H:i:s", strtotime('-'.$order_step->work_hour.' hours', strtotime($order_step->deadline))); ?>
<hr>
<h4>Bo'lim vaqtini o'zgartirish</h4>
<div class="row">
	<div class="col-md-6">
		<?php  
			echo DateTimePicker::widget([
		    'name' => 'end_date',
		    'id' => 'end_date',
		    'options' => ['placeholder' => 'Bitish sanansi'],
		    'pluginOptions' => [
		        'autoclose'=>true,
				'format' => 'dd-mm-yyyy hh:ii',
				'autocomplete' => 'off',
				'startDate' => $min_date,
		    ]
		]);
		?>
	</div>
	<div class="col-md-6"></div>
</div>
