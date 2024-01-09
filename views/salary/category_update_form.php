<div class="form-group">
    <label class="control-label" for="category_label">Nomi</label>
    <input type="text" id="category_title" class="form-control" value='<?php
    if (isset($category['title'])) {
        echo $category['title'];
    }
    ?>'>
    <br>
    <label class="control-label" for="category_label">Balans: <?= $category['balance'] ?></label>
    <input type="text" id="category_balance" class="form-control">
    <div class="help-block"></div>
</div>