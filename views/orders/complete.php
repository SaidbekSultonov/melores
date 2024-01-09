<div class="row">
	<div class="col-md-12">
		<h2>Bitgan buyurtmalar ro'yxati</h2>
		<hr>
	</div>
	<div class="col-md-12">
		<table class="table table-bordered table-striped" id="myTable">
			<tr>
				<th>#</th>
				<th>Buyurtma nomi</th>
				<th>Filial</th>
				<th>Mijoz ismi</th>
				<th>Boshlangan sana</th>
				<th>Bitgan sana</th>
				<th>OTK uchun</th>
				<th>Mijoz uchun</th>
			</tr>
			<?php if (!empty($model)): $i = 1; ?>
				<?php foreach ($model as $key => $value): ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $value->title ?></td>
						<td><?php echo $value->branch->title ?></td>
						<td><?php echo base64_decode((($value->client)) ? $value->client->full_name : '')?></td>
						<td><?php echo date("Y-m-d",strtotime(date($value->created_date))) ?></td>
						<td><?php echo $value->end_date ?></td>
						<td>
							<a href="<?php echo $master_bot->link.(($value->user) ? $value->user->phone_number : '') ?>">
								<?php echo $master_bot->link.(($value->user) ? $value->user->phone_number : '') ?>
							</a>
							
						</td>
						<td>
							<a href="<?php echo $client_bot->link."_".base64_decode((($value->client)) ? $value->client->phone_number : '')."_".$value->id ?>">
								<?php echo $client_bot->link."_".base64_decode((($value->client)) ? $value->client->phone_number : '')."_".$value->id ?>
							</a>
							
						</td>
					</tr>
				<?php $i++; endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan="8">Bitgan buyurtmalar mavjud emas!</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
</div>

<script>
	
</script>

<?php
$js = <<<JS
        $('#myTable').dataTable();
        

JS;


$this->registerJs($js);
?>