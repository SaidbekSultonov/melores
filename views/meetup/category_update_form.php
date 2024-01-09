<div class="form-group field-quiz_category_title">
<label class="control-label" for="quiz_category_title">Nomi</label>
<input type="text" id="quiz_category_title" class="form-control" name="QuizCategory[title]" value='<?php
    if (isset($category['title'])) {
        echo $category['title'];
    }
?>'>

<div class="help-block"></div>
</div>