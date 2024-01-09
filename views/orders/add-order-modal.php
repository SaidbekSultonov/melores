<?php 
	use kartik\datetime\DateTimePicker;
?>
<form class="row" id="form">
	<input type="text" class="hidden" name="section" value="<?php echo $section_id ?>">
	<div class="col-md-6">
		<label for="">Buyurtma nomi <small class="error_title hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
		<input name="title" type="text" class="form-control" id="order_title">

	</div>
	<div class="col-md-6">
		<label for="">Filial <small class="error_branch hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
		<select name="branch" class="form-control select2" id="branch" data-placeholder="Filial tanlang" style="width: 100%">
	        <option value=""></option>
            <?php foreach ($branchs as $key => $value): ?>
                <option value="<?php echo $value->id; ?>">
                    <?php echo $value->title; ?>      
                </option>
            <?php endforeach ?>
		</select>
	</div>
	<div class="col-md-4">
		<br>
		<label for="">Mijoz <small class="error_client hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
		<select name="client" class="form-control select2" disabled id="client" data-placeholder="Mijoz tanlang" style="width: 100%">
	        
		</select>
	</div>
	<div class="col-md-4">
		<br>
		<label for="">Kategoriyalar <small class="error_category hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
		<select name="category[]" multiple="multiple" class="form-control select2" disabled id="category" data-placeholder="Kategoriya tanlang" style="width: 100%">
	        
		</select>
	</div>
	<div class="col-md-4">
		<br>
		<label for="">Buyurtma bitish sanasi <small class="error_end hidden text-danger">( Maydon bo`sh bo'lishi mumkin emas! )</small></label>
		<?php  
			echo DateTimePicker::widget([
		    'name' => 'end_date',
		    'id' => 'end_date',
		    'options' => ['placeholder' => 'Bitish sanansi'],
		    'pluginOptions' => [
		        'autoclose'=>true,
				'format' => 'dd-mm-yyyy hh:ii',
				'autocomplete' => 'off',
		    ]
		]);
		?>
	</div>

	<div class="col-md-12">
		<br>
		<label for="">Qo'shimcha izoh</label>
		<textarea name="description" class="form-control"></textarea>
	</div>

	<div class="col-md-12">
		<br>
		<label for="">Majburiy tovarlar</label><br>

			<?php if (!empty($required_mat)): ?>
				<div class="row">
					<?php foreach ($required_mat as $key => $value): ?>
							<div class="col-md-6">
								<label for="required_<?php echo $value->id ?>" style="border:solid 1px #ccc;display: flex;align-items: center; padding: 6px 12px">
									<input type="checkbox" id="required_<?php echo $value->id ?>" name="required[<?php echo $value->id ?>]" value="<?= $value->id ?>" style="margin-top: 0">
									<span style="margin-left: 10px">
					            		<?php echo $value->title; ?>	
					            	</span>
								</label><br>								
							</div>
					<?php endforeach ?>
				</div>					
			<?php endif ?>	

	</div>

	<div class="col-md-12">
		<hr>
		<div class="row">
			<div class="col-md-6">
				<h4>Buyurtma bo`limlari</h4>
			</div>
			<div class="col-md-6 text-right">
				<label id="parralel" class="btn btn-default btn-sm">
					<b>
						Shponli buyurtma	
					</b>
					<input type="checkbox" class="paralel" name="parralel" value="1">
				</label>
			</div>
		</div>
	</div>
	<div class="col-md-12" id="sections_order">
		<?php $i = 0; foreach ($sections as $key => $value): 
			$block = 'display: flex';
			$class = '';
			if ($value->id == 29 || $value->id == 30 || $value->id == 31) {
				$block = 'display: none';
				$class = 'check';
			}
		?>
		    <?php if ($section_id != $value->id): ?>
				<div class="row <?php echo $class ?>" style="<?php echo $block; ?>;padding: 5px 0;">
					<div class="col-md-8">
			            <label style="border:solid 1px #ccc;display: flex;align-items: center; padding: 6px 12px">
			            	<input type="checkbox" name="Section[Order_<?php echo $i ?>][sections]" value="<?php echo $value->id ?>" class="flat-red" style="margin-top: 0">
			            	<span style="margin-left: 10px">
			            		<?php echo $value->title; ?>	
			            	</span>
			            	
			            </label>
					</div>
					<div class="col-md-4">
						<input type="text" value="<?php echo $value->current_time ?>" class="form-control time" name="Section[Order_<?php echo $i ?>][times]">
					</div>
				</div>
		    <?php endif ?>
		<?php $i++; endforeach ?>
	</div>
	
</form>

<?php $this->registerJs(
'
    $("#branch").select2();
    $("#client").select2();
    $("#category").select2({
    	tags: true,
    });
    $("#required").select2({
    	tags: true,
    });
    $("#section").select2();
    $("#end_date").attr("autocomplete", "off");

    // flat-red
    $(document).on("change", ".flat-red", function(){
    	if($(this).is(":checked")){
    		$(this).parent().css("background","#3c8dbc52")
    		$(this).parent().parent().siblings(".col-md-4").find("input").css("background","#3c8dbc52")
    	}
    	else{
    		$(this).parent().css("background","#fff")
    		$(this).parent().parent().siblings(".col-md-4").find("input").css("background","#fff")
    	}
    })

    $(document).on("change",".paralel",function(){
    	if($(this).is(":checked")){
    		$(".check").css({
    			"display":"flex",
    			"background" : "lightblue"
    		})
    	}
    	else{
    		$(".check input").prop("checked", false); 
    		$(".check").css("display","none")

    	}
    })


', yii\web\View::POS_READY); ?>