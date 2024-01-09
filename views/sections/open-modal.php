<label for="select">Foydalanuvchilar</label>
<select name="select" id="users" class="form-control" data-id="<?php echo $id ?>">
	<?php if (!empty($users)): ?>
		<?php foreach ($users as $key => $value): ?>
			<?php if (!empty($value->second_name)): ?>
				<option value="<?php echo $value->id ?>">
					<?php echo $value->second_name; ?>
				</option>
			<?php endif ?>
		<?php endforeach ?>
	<?php endif ?>	
</select>
<br>
<br>
<label for="bonus">Bonus summasi</label>
<input min="1" type="number" class="form-control" id="bonus">
<br>
<label for="bonus">Jarima summasi</label>
<input min="1" type="number" class="form-control" id="penalty">


<script>
	$(function(){
		$('.select2').select2()
	})
</script>