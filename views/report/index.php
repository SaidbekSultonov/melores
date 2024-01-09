
<?php
	use app\models\Sections;
	use app\models\Orders;
	use app\models\OrderStep;
	use app\models\SectionOrders;

	function diff_date($date1,$date2,$limit){

		if ($date2 != NULL) {
			$t1 = strtotime($date1); //kottasi
			$t2 = strtotime($date2);
			$diff = $t2 - $t1;
			$hours = $diff / (60*60);

			return round($hours);
		}
		else{
			$t1 = strtotime($date1); //kottasi
			$t2 = strtotime(date('Y-m-d H:i:s'));
			$diff = $t2 - $t1;
			$hours = $diff / ( 60 * 60 );

			return round($hours);
		}
        
    }
?>

<style>
	.table > tbody > tr > td, .table > tfoot > tr > td {
    	padding: 4px;
	}
	
	.fixed{
		/*width: 95.23%;*/
  		position: fixed;
		top: -100%;
		transition: .5s;
	}
</style>
<h2>Buyurtmalar bo'yicha hisobot</h2>
<hr>
<div class="overflow">
	<table class="table table-bordered fixed">
		<tr class="bg-info" >
			<th rowspan="2" class="ddd">Buyurtmalar</th>
			<?php if (!empty($sections)): ?>
				<?php $i = 1; foreach ($sections as $key => $value): 
					if ($i == 1 || $i == 2 || $i == 13) {
						$width = '74px';
					}
					if ($i >= 3 && $i <= 8) {
						$width = '80px';
					}
					if ($i == 15) {
						$width = '72px';
					}
					if ($i == 11) {
						$width = '79px';
					}
					if ($i == 12 || $i == 14) {
						$width = '67px';
					}
				?>
					<th width="<?php echo $width ?>" colspan="2" style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
						<?php switch ($value->title) {
							case "Kroy bo'limi ":
								echo "Kry";
							break;
							case "Kroy nazorati ":
								echo "Kr.N";
							break;
							case "Material yeg'ish va kroy sehgacha ":
								echo "Ma.Yi";
							break;
							case "Taminot: Olingan materialni tekshirish":
								echo "Ta.Ol";
							break;
							case "Korpus seh ":
								echo "Ko.S";
							break;
							case "Shpon seh ":
								echo "Shp.s";
							break;
							case "Raspil":
								echo "Ras";
							break;
							case "Kroy bo'limi ":
								echo "Kr";
							break;

							case "Shkurka seh ":
								echo "Shk.s";
							break;
							case "Kraska seh":
								echo "Kra.s";
							break;

							case "Qurish  va o'rash xonasi ":
								echo "Qu.O'r";
							break;
							case "Logistika (dostavka)":
								echo "Logs";
							break;
							case "Ustanovka ":
								echo "Ust";
							break;
							case "Tosh qo'yish va OTK ":
								echo "To.OTK";
							break;
							case "Sifat nazorati tekshiruvi ":
								echo "Sif.T";
							break;
							case "Video va rasm olish ":
								echo "Vi.Ol";
							break;
							case "Chala ishla ":
								echo "Chal.I";
							break;
						} ?>
					</th>		
				<?php $i++; endforeach ?>
			<?php endif ?>
		</tr>
		<tr class="bg-info">
			<?php if (!empty($sections)): ?>
				<?php $i = 1; foreach ($sections as $key => $value): ?>
					<th style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
						B
					</th>
					<th style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
						J
					</th>		
				<?php $i++; endforeach; ?>
			<?php endif ?>
		</tr>
	</table>
	<table class="table table-bordered vvv">
		<tr class="bg-info" >
			<th rowspan="2" class="sss">Buyurtmalar</th>
			<?php if (!empty($sections)): ?>
				<?php $i = 1; foreach ($sections as $key => $value): ?>
					<th colspan="2" style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
						<?php switch ($value->title) {
							case "Kroy bo'limi ":
								echo "Kry";
							break;
							case "Kroy nazorati ":
								echo "Kr.N";
							break;
							case "Material yeg'ish va kroy sehgacha ":
								echo "Ma.Yi";
							break;
							case "Taminot: Olingan materialni tekshirish":
								echo "Ta.Ol";
							break;
							case "Raspil":
								echo "Ras";
							break;
							case "Korpus seh ":
								echo "Ko.S";
							break;
							case "Shpon seh ":
								echo "Shp.s";
							break;
							case "Kroy bo'limi ":
								echo "Kr";
							break;

							case "Shkurka seh ":
								echo "Shk.s";
							break;
							case "Kraska seh":
								echo "Kra.s";
							break;

							case "Qurish  va o'rash xonasi ":
								echo "Qu.O'r";
							break;
							case "Logistika (dostavka)":
								echo "Logs";
							break;
							case "Ustanovka ":
								echo "Ust";
							break;
							case "Tosh qo'yish va OTK ":
								echo "To.OTK";
							break;
							case "Sifat nazorati tekshiruvi ":
								echo "Sif.T";
							break;
							case "Video va rasm olish ":
								echo "Vi.Ol";
							break;
							case "Chala ishla ":
								echo "Chal.I";
							break;
						} ?>
					</th>		
				<?php $i++; endforeach ?>
			<?php endif ?>
		</tr>
		<tr class="bg-info">
			<?php if (!empty($sections)): ?>
				<?php $i = 1; foreach ($sections as $key => $value): ?>
					<th style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
						B
					</th>
					<th style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
						J
					</th>		
				<?php $i++; endforeach; ?>
			<?php endif ?>
		</tr>

		<?php if (!empty($orders)): ?>
			<?php foreach ($orders as $key => $value): ?>
				<tr>
					<td style>
						<?php echo $value->title; ?>
					</td>
					<?php if (!empty($sections)): ?>
					<?php $i = 1; foreach ($sections as $keys => $values): ?>
						<?php 
							$order_step = OrderStep::find()->where(['order_id' => $value->id, 'section_id' => $values->id ])->one();  
							
							$section_order = SectionOrders::find()->where(['order_id' => $value->id, 'section_id' => $values->id ])->one();

							if (isset($order_step) && isset($section_order)) {
								$hour = diff_date($order_step->deadline,$section_order->exit_date,$section_order->enter_date);

								
							}
							else{
								$hour = false;
							}
						?>
						<?php if ($hour <= 0 && $hour): ?>
							<td style="text-align: center;<?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
								<span class="label label-success">
									<?php echo ltrim($hour, '-'); ?>
								</span>
							</td>
							<td style="text-align: center;<?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
								0
							</td>
							
						<?php elseif($hour > 0 && $hour): ?>
							<td style="text-align: center;<?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
								0
							</td>
							<td style="text-align: center;<?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">
								<span class="label label-danger">
									<?php echo $hour ?>
								</span>
							</td>
						<?php else: ?>
							<td style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">-</td>
							<td style="text-align: center; <?php echo (($i%2 == 0) ? 'background: #e1e1e1' : '') ?>">-</td>
						<?php endif ?>
					<?php $i++; endforeach ?>
				<?php endif ?>
				</tr>
			<?php  endforeach ?>
		<?php endif ?>
	</table>
	
</div>

<style>
	.overflow{
		overflow-x: auto;
		/*position: relative !important;*/
	}
</style>
<?php

$js = <<<JS
    $(function(){
        $(".sidebar-toggle").trigger("click");

        let sss = $(".sss").width()
        $(".ddd").width(sss)

        let vvv = $(".vvv").width()
        $(".fixed").width(vvv)

        let a = $('.pagination li').length
        for(let i = 1; i <= a; i++){
        	let d = $('.pagination li').eq(i).find('a').attr('href')
        	$('.pagination li').eq(i).find('a').attr('href','/index.php'+d)
        }
        let dd = $('.pagination .prev').find('a').attr('href')
        $('.pagination .prev').find('a').attr('href','/index.php'+dd)

        
    })

    $(window).scroll(function (event) {
	    var scroll = $(window).scrollTop();
	    if(scroll >= 220){
	    	$('.fixed').css('top',0)
	    }
	    else{
	    	$('.fixed').css('top','-100%')
	    }
	});

JS;


$this->registerJs($js);
?>

