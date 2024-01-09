<style type="text/css">
	.container-fluid{
		font-size: 10px !important;
	}
	.d-flex{
		display: flex;
	}
	.col{
		padding: 5px;
		width: 100%;
		border: 1px #ccc solid;
		border-left: none;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.d-flex:first-child{
		border-left: 1px solid #ccc;
	}
	.td_box{
		width: 100%;
		height: 50px;
		/*position: absolute;*/
		top: 0;
		left: 0;

	}
	.td_click{
		cursor: pointer;
	}
</style>
<div class="container-fluid">
	<div class="row text-center" style="display: flex;justify-content: space-between;padding: 15px 0;">
		<?php foreach ($months as $key => $value): ?>
			<?php if ($key == $month): ?>
				<a href="/index.php/order-graph/detail?month=<?php echo $key ?>" style="color: #000; width: 100%;font-size: 20px; text-decoration: underline;">
					<?php echo $value ?>
				</a>	
			<?php else: ?>
				<a href="/index.php/order-graph/detail?month=<?php echo $key ?>" style="color: grey; width: 100%; text-decoration: none;font-size: 20px;">
					<?php echo $value ?>
				</a>
			<?php endif ?>
			
			
		<?php endforeach ?>
		<hr>
	</div>

	<div class="row">
		<div class="d-flex">
			<?php if (!empty($sections)): ?>
				<?php foreach ($sections as $key => $value): ?>
					<div class="col" style="background: <?php echo $value->color ?>">
						<?php echo $value->title ?>
					</div>
				<?php endforeach ?>
			<?php endif ?>
		</div>
		<br>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th style="width: 250px;">Buyurtmalar</th>
					<?php if (!empty($arr_days)): ?>
						<?php foreach ($arr_days as $value): ?>
							<th style="width: 250px;">
								<?php echo $value; ?>
							</th>
						<?php endforeach ?>
					<?php endif ?>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($model)): ?>
          <?php foreach ($model as $key => $value): ?>
            


            <tr>
              <th><?php echo $value->title ?></th>
              <?php if (!empty($arr_days)): ?>
                <?php foreach ($arr_days as $values): ?>

                  <?php if ($value->order_step_one($value->id,25) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,25)->deadline))): ?>
                      <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="25" style="background: #1d691430; border-left: none !important;border-right: none !important;"></td>
                  <?php elseif($value->order_step_one($value->id,39) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,39)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="39" style="background: #e6c2bf87; border-left: none !important;border-right: none !important;"></td>
                  <?php elseif($value->order_step_one($value->id,26) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,26)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="26" style="background: #ffef3333; border-left: none !important;border-right: none !important;"></td>
                  <?php elseif($value->order_step_one($value->id,27) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,27)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="27" style="background: #29d0d036; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,42) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,42)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="42" style="background: #E9DFBB; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,28) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,28)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="28" style="background: #2a4bd736; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,29) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,29)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="29" style="background: #e9dfbb78; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,30) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,30)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="30" style="background: #814a194a; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,31) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,31)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="31" style="background: #29d0d080; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,32) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,32)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="32" style="background: #ffcdf34d; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,34) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,34)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="34" style="background: #9dafff4f; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,35) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,35)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="35" style="background: #57575745; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,36) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,36)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="36" style="background: #9dafff33; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,37) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,37)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="37" style="background: #ff59651f; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,38) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,38)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="38" style="background: #5547814a; border-left: none !important;border-right: none !important;"></td>

                  <?php elseif($value->order_step_one($value->id,40) !== NULL && date('Y-m-'.$values) <= date('Y-m-d',strtotime($value->order_step_one($value->id,40)->deadline))): ?>
                    <td data-target="#modal-default3" data-toggle="modal" class="td_click" data-order="<?php echo $value->id ?>" data-section="40" style="background: #E6C2BF; border-left: none !important;border-right: none !important;"></td>

                  <?php else: ?>
                    <td></td>
                  <?php endif ?>
                  
                  
                <?php endforeach ?>
              <?php endif ?>
              
            </tr>
          <?php endforeach ?>
        <?php endif ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	
	$(document).on('click','.td_click',function(){
		var section_id = $(this).attr('data-section')
		var order_id = $(this).attr('data-order')

		$.ajax({
	        url:'/index.php/order-graph/open-modal', 
	        dataType: 'JSON',  
	        type: 'get',
	        data: {
	        	section_id: section_id,
				order_id: order_id,
			},
	        success: function(response){
	            if (response.status == 'success') {
	            	$('#modal-default3 .modal-title').html(response.header)
	            	$('#modal-default3 .modal-body').html(response.content)
	            	$('#end_date').attr('autocomplete','off')
	            	$('#modal-default3 .modal-footer .btn-success').removeClass().addClass('btn btn-success save-update')
	            	$('#modal-default3 .modal-footer .save-update').attr('data-order',order_id)
	            	$('#modal-default3 .modal-footer .save-update').attr('data-section',section_id)
	            }
	        }
	    });
	})


	$(document).on('click','.save-update',function(){
		var date = $('#end_date').val()
		var order_id = $(this).attr('data-order')
		var section_id = $(this).attr('data-section')
		var _this = $(this)

		if (date != '' && confirm("Rejadagi chiqish vaqti o'zgartirmoqchimisiz ?")) {
			_this.prop('disabled',true)
			$.ajax({
		        url:'/index.php/order-graph/save-update', 
		        dataType: 'JSON',  
		        type: 'get',
		        data: {
		        	section_id: section_id,
					order_id: order_id,
					date: date,
				},
		        success: function(response){
		            if (response.status == 'success') {
		            	window.location.reload()
		            }
		            else{
		            	_this.prop('disabled',false)
		            }
		        }
		    });
		}
		else{
			$('#modal-default3').modal('toggle')
		}

		

	})
</script>



















