<?php 
	use kartik\datetime\DateTimePicker;
?>
<div class="row data_<?php echo $i ?>">
	<div class="col-md-8">
		<label for="">Bo`lim</label>
		<select name="Section[Order_<?php echo $i ?>][sections]" class="form-control select2 section" id="section" data-placeholder="Bo`lim tanlang" style="width: 100%">
	        <option value=""></option>
            <?php foreach ($sections as $key => $value): ?>
            	<?php if ($section_id != $value->id): ?>
	                <option value="<?php echo $value->id; ?>">
	                    <?php echo $value->title; ?>      
	                </option>
            	<?php endif ?>
            <?php endforeach ?>
		</select>
		<br>
	</div>
	<div class="col-md-4">
		<label for="">Ish vaqti</label>
			<input type="text" class="form-control time" name="Section[Order_<?php echo $i ?>][times]">
		<br>
	</div>
</div>

<?php $this->registerJs(
'
    $(".section").select2();

', yii\web\View::POS_READY); ?>