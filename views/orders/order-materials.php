<div class="row">
	<div class="col-md-9">
		<h2>Buyurtma ma`lumotlari ro'yxati</h2>
	</div>
	
	<div class="col-md-12">
		<hr>
		<div class="parent">
			<table class="table table-bordered table-striped">
				<tr>
					<th>#</th>
					<th>Buyurtma nomi</th>
					<th>Buyurtma holati</th>
					<th>Filial</th>
					<th>Mijoz ismi</th>
					<th>Kategoriyalari</th>
					<th>Boshlangan sana</th>
					<th>Yulangan fayllar soni</th>
					<th>Batafsil</th>
				</tr>
				<?php if (!empty($model)): $i = 1; ?>
					<?php foreach ($model as $key => $value): ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $value->title ?></td>
							<td><?php echo (($value->status == 0) ? '<span class="label label-success">Bitgan<span>' : '<span class="label label-primary">Jarayonda<span>') ?></td>
							<td><?php echo $value->branch->title ?></td>
							<td><?php echo base64_decode((($value->client)) ? $value->client->full_name : '')?>
							</td>
							<td>
	        					<?php if(!empty($value->categories)): ?>
	                                <?php foreach ($value->categories as $keyv => $valuev): ?>
	                                    <span class="label label-default">
	                                        <?php echo $valuev->category->title ?>
	                                    </span>

	                                <?php endforeach ?>
	                            <?php endif; ?>
					        </td>
							</td>	
							<td>
								<?php echo date("Y-m-d",strtotime(date($value->created_date))) ?>
							</td>
							<td>
								<?php echo $value->files ?>
							</td>
							<td>
								<center>
									<a href="/index.php/orders/view-materials?id=<?php echo $value['id'] ?>">
										<i class="fa fa-eye"></i>
									</a>	
								</center>
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
</div>


<?php
$js = <<<JS

	

JS;
$this->registerJs($js);
?>