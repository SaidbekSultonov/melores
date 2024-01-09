<div class="row">
	<div class="col-md-12">
		<h3>Hodimlar ro'yxati</h3>
	</div>
</div>
<hr>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Hodim ismi</th>
			<th>Ro'li</th>
			<th>Telefon raqami</th>
			<th>Tugatilmagan buyurtmalar soni</th>
			<th>Umumiy jarima miqdori</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($users)): ?>
			<?php $i = 1; foreach ($users as $key => $value): ?>
				<tr>
					<td><?php echo $i ?></td>
					<td><?php echo $value->second_name ?></td>
					<td>
						<?php switch ($value->type) {
                        case '1':
                            echo 'Admin';
                        break;
                        case '2':
                            echo 'Nazoratchi';
                        break;
                        case '3':
                            echo 'O`TK';
                        break;
                        case '4':
                            echo 'Sotuvchi';
                        break;
                        case '5':
                            echo 'Bo`lim boshlig`i';
                        break;
                        case '6':
                            echo 'Kroychi';
                        break;
                        case '7':
                            echo 'Bo`lim ishchisi';
                        break;
                        case '8':
                            echo 'HR';
                        break;
                        case '9':
                            echo 'Marketolog';
                        break;
                        case '10':
                            echo 'Brigadir';
                        break;
                    } ?>
                    	
                    </td>
					<td><?php echo $value->phone_number ?></td>
					<td><?php echo $value->orders_count ?></td>
					<td>
						<?php if ($value->total): ?>
							<a class="btn btn-danger btn-xs" href="/index.php/report/view?id=<?php echo $value->id ?>">
								<?php echo number_format($value->total,0,'',' ') ?>
							</a>
						<?php else: ?>
							0
						<?php endif ?>
					</td>
				</tr>
			<?php $i++; endforeach ?>
		<?php else: ?>
			<tr>
				<td colspan="6">Ma'lumot topilmadi</td>
			</tr>
		<?php endif ?>
	</tbody>
</table>