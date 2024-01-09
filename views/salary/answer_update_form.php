<div class="form-group">
    <label class="control-label" for="answer_label">Nomi</label>
    <input type="text" id="answer_title" class="form-control" value='<?php
    if (isset($answer['title'])) {
        echo $answer['title'];
    }
    ?>'>
    <div class="help-block"></div>
</div>