<?php  
	use yii\widgets\LinkPager;
?>
<h2>Mijoz baholari bo'yicha hisobot</h2>
<hr>

<div style="overflow-x: auto;">
	<table class="table table-bordered table-striped">
		<tr>
			<th>#</th>
			<th>Buyurtma nomi</th>
			<?php if (!empty($feedback_client)): ?>
				<?php foreach ($feedback_client as $key => $value): ?>
					<th><?php echo base64_decode($value->title) ?></th>
				<?php endforeach ?>
			<?php endif ?>
			<th>Jami</th>
		</tr>
		<?php if (!empty($orders)):   ?>
			<?php $i = 1; foreach ($orders as $key => $value): $total = 0; ?>
				<tr>
					<td><?php echo $i ?></td>
					<td><?php echo $value->title ?></td>
					<?php if (!empty($feedback_client)): ?>
						<?php foreach ($feedback_client as $keyu => $valueu): ?>
							<td><?php echo $valueu->client_ball($value->id,$valueu->id); 
							$total += intval($valueu->client_ball($value->id,$valueu->id)); ?></td>
						<?php endforeach ?>
					<?php endif ?>
					<td><?php echo $total ?></td>
				</tr>
			<?php $i++; endforeach; ?>
		<?php endif ?>
		<tr>
		</tr>
	</table>
	<?= LinkPager::widget([
		'pagination' => $pages,
	]); ?>
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
