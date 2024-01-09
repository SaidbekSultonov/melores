<label for="">Bo`lim nomi UZ</label>
<input type="text" class="form-control" id="services_types_name_uz">
<p class="p1"></p>

<label for="">Bo`lim nomi RU</label>
<input type="text" class="form-control" id="services_types_name_ru">
<p class="p2"></p>

<label for="">Yo'nalish</label>
<select id="services" class="form-control">
    <option value=""></option>
    <?php if (!empty($model)): ?>
        <?php foreach ($model as $key => $value): ?>
            <option value="<?php echo $value->id ?>">
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