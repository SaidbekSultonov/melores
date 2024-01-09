<label for="">Kategoriya turi nomi UZ</label>
<input type="text" class="form-control" id="services_name_uz" value="<?php echo $model->title_uz ?>">
<p class="p1"></p>

<label for="">Kategoriya turi nomi RU</label>
<input type="text" class="form-control" id="services_name_ru" value="<?php echo $model->title_ru ?>">
<p class="p2"></p>

<label for="">Kategoriya</label>
<select id="services" class="form-control">
    <option value=""></option>
    <?php if (!empty($services)): ?>
        <?php foreach ($services as $key => $value): ?>
            <option <?php echo (($model->services_id == $value->id) ? 'selected' : '') ?> value="<?php echo $value->id ?>">
                <?php echo $value->title_uz ?>
            </option>
        <?php endforeach ?>
    <?php endif ?>
</select>
<p class="p3"></p>

<?php $this->registerJs(
    '    $(function(){
        $("#services").select2({
            placeholder: "Yo`nalish tanlang"
        });
    })

', yii\web\View::POS_READY); ?>