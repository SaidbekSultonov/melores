<div class="row">
	<div class="col-md-12">
		<h3><?php echo $user->second_name ?></h3>
	</div>
</div>
<hr>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Buyurtma nomi</th>
			<th>Mijoz ismi</th>
			<th>Bitish muddati</th>
			<th>Kechikish muddati</th>
			<th>Umumiy jarima miqdori</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($user_penalties)): ?>
			<?php $i = 1; foreach ($user_penalties as $key => $value): ?>
				<tr>
					<td><?php echo $i ?></td>
					<td>
						<?php echo $value->order->title ?>
					</td>
					<td>
						<?php echo base64_decode($value->order->client->full_name) ?>
					</td>
					<td>
						<?php echo date('d.m.Y H:i',strtotime($value->order->dead_line)) ?>
					</td>
					<td>
						<?php echo number_format($value->delay_day_count,0,'',' ') ?>
					</td>
					<td>
						<?php echo number_format($value->sum,0,'',' ') ?>
					</td>
				</tr>
			<?php $i++; endforeach ?>
		<?php else: ?>
			<tr>
				<td colspan="5">Ma'lumot topilmadi</td>
			</tr>
		<?php endif ?>
	</tbody>
</table>