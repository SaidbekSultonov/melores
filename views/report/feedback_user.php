<h2>OTK baholari bo'yicha hisobot</h2>
<hr>

<div style="overflow-x: auto;">
	<table class="table table-bordered table-striped">
	<tr>
		<th>#</th>
		<th>Brigada</th>
		<th>Buyurtma nomi</th>
		<?php if (!empty($feedback_user)): ?>
			<?php foreach ($feedback_user as $key => $value): ?>
				<th><?php echo base64_decode($value->title) ?></th>
			<?php endforeach ?>
		<?php endif ?>
		<th>Ish haqi</th>
		<th>Jami</th>
	</tr>
	<?php if (!empty($orders)):   ?>
		<?php $i = 1; foreach ($orders as $key => $value): $total = 0; $total2 = 0; ?>
			<tr>
				<td><?php echo $i ?></td>
				<td><?php echo (($value->team) ? base64_decode($value->team->title) : '') ?></td>
				<td><?php echo $value->title ?></td>
				<?php if (!empty($feedback_user)): ?>
					<?php foreach ($feedback_user as $keyu => $valueu): ?>
						<td>
							<?php echo $valueu->user_ball($value->id,$valueu->id); 
							$total += intval($valueu->user_ball($value->id,$valueu->id)); 
							if($valueu->user_ball($value->id,$valueu->id) != NULL){
								$total2++;
							}?>
						</td>
					<?php endforeach ?>
				<?php endif ?>
				<td><?php echo (($value->salary) ? $value->salary->quantity : '') ?></td>
				<td><?php echo (($total != 0 && $total2 != 0) ? round($total/$total2) : 0) ?></td>
			</tr>
		<?php $i++; endforeach; ?>
	<?php endif ?>
	<tr>
	</tr>
</table>

</div>

<?php

$js = <<<JS
    $(function(){
        $(".sidebar-toggle").trigger("click");

        let a = $('.pagination li').length
        for(let i = 1; i <= a; i++){
        	let d = $('.pagination li').eq(i).find('a').attr('href')
        	$('.pagination li').eq(i).find('a').attr('href','/index.php'+d)
        }
        let dd = $('.pagination .prev').find('a').attr('href')
        $('.pagination .prev').find('a').attr('href','/index.php'+dd)

        
    })

JS;


$this->registerJs($js);
?>
