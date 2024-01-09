<label for="select">Kategoriyalar</label>
<select class="form-control" id="category_id" name="select">
	<?php foreach ($category as $key => $value): ?>
		<option value="<?= $value->id ?>"><?= $value->title_uz ?></option>
    <?php endforeach ?>
</select>

<label for="">Kategoriya nomi UZ</label>
<input type="text" class="form-control" id="services_name_uz">
<p class="p1"></p>

<label for="">Kategoriya nomi RU</label>
<input type="text" class="form-control" id="services_name_ru">
<p class="p2"></p>
