<?php

use yii\helpers\Html;

$this->title = 'Ozgartirish';

?>
<style>
    .margin-bottom{
        margin-bottom:20px;
    }
    .margin_left{
        margin-left:30px;
    }
    .padding_magin_badge{
        margin-left:3px;
        margin-right:3px;
        padding-left:7px;
        padding-right:7px;
    }
</style>
<div class="quiz-update">
    <form action="/index.php/meetup/update?id=<?=$_GET['id']?>" method="post" id='form_update'>
    <input type="hidden" name='Quiz[type]' value='<?=$_GET['type']?>' id='users_type'>
        <div class="container">
            <div class="row margin-bottom">
                <h2><b>Ozgartirsh</b></h2>
                <div class="col-md-12">
                    <label class="control-label" for="quiz-question">Savol</label>
                    <input type="hidden" name="Quiz[id]" value='<?=$_GET['id']?>'>
                    <textarea id="quiz-question" class="form-control" name="Quiz[question]" rows="2" required="" aria-required="true"><?php
                        if (isset($question) && !empty($question)) {
                            echo $question->question;
                        }
                    ?></textarea>
                </div>
            </div>
            <div class="row margin-bottom">
                <div class="col-md-10"></div>
                <div class="col-md-1">
                    <span type="button" class="btn btn-info pull-right" id='add_users'><i class="fa fa-plus"></i></span>
                    <input type="hidden" name="Quiz[count]" id='count_users' value='<?php
                        if (isset($users) && !empty($users)) {
                            $count = 0;
                            foreach ($users as $user_count) {
                                $count++;
                            }   
                            echo $count;
                        }
                    ?>'>
                </div>
                <div class="col-md-1">
                    <span type="button" class="btn btn-success pull-right" id='save_updates' data-after-save-url=''>Saqlash</span>
                </div>
            </div>
            <table class='table table-bordered table-striped'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Javob beruvchi</th>
                        <th>Jarima</th>
                        <th>Holait</th>
                        <th>Jarayon</th>
                    </tr>
                </thead>
                <tbody id='table_body'>
                    <?php
                    if (isset($users) && !empty($users)) {
                        $num = 0;
                        foreach ($users as $users_value) {
                            $num++;
                            ?>
                            <tr>
                                <td><?=$num ?>
                                    <input type="hidden" name="Quiz[list][<?=$num?>][users]" value='<?=$users_value['user_id']?>'>
                                </td>
                                <td><?=$users_value['second_name'] ?></td>
                                <td><input type="number" name="Quiz[list][<?=$num?>][penalty]"  class='form-control' value='<?php
                                    if (isset($users_value['sum']) && !empty($users_value['sum'])) {
                                        echo $users_value['sum'];
                                    }
                                ?>'></td>
                                <td class='status'><?php
                                    echo (($users_value['status'] == 1) ? '<span class="label label-success">Faol</span>' : '<span class="label label-danger">Arxiv</span>');
                                ?></td>
                                <td>                            
                                    <?php
                                    if ($users_value['status'] == 1) {
                                        ?>
                                        <a class='delete_row' data-status='0' data-send_quiz='<?=$users_value['id']?>'><span class="glyphicon glyphicon-trash"></span></a>
                                        <?php
                                    }else {
                                        ?>
                                        <a class='delete_row' data-status='1' data-send_quiz='<?=$users_value['id']?>'><span class="glyphicon glyphicon-trash"></span></a>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>
</div>
<?php

$js = <<<JS

$(document).on("click", '#add_users', function() {
    var form = $('#form_update');
    $.ajax({
        url: '/index.php/meetup/add-update',
        data: form.serialize(),
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status == "success") {
                $("#count_users").val(parseInt($("#count_users").val())+ 1);
                $('#table_body').append(response.content);
            }
        }
    })
});

$(document).on("click", '#save_updates', function() {
    var form = $('#form_update');
    $.ajax({
        url: '/index.php/meetup/save-updates',
        data: form.serialize(),
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status == "success") {
                window.location.href = '/index.php/meetup/index';
            }else if(response.status == "error"){
                var error = 'Xato: '
                if (response.errors['user'] == true) {
                    error += 'Bitta javob beruvchi takroran bitta savolga javob beromidi       ';
                }
                if (response.errors['penalty'] == true) {
                    error += 'Jarima summa 0 dan kichik bolish mumkun emas            ';
                }
                alert(error);
            }
        }
    })
});

$(document).on("change", '.user_select', function() {
    var val = $(this).find('option:selected').attr('data-sum');
    $(this).parents('tr').children('.sum').children('input').val(val)
});

$(document).on("click", '.delete_add_row', function() {
    $("#count_users").val(parseInt($("#count_users").val()) - 1);
    $(this).parents('tr').remove();
});

$(document).on("click", '.delete_row', function() {
    if (confirm('Arxiv qilish istaysimiz ?')) {
        var _this = $(this);
        var send_quiz_id = $(this).attr('data-send_quiz');
        var send_status = $(this).attr('data-status');
        $.ajax({
            url: '/index.php/meetup/delete-send-quiz',
            data: {
                id: send_quiz_id,
                status: send_status
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status == "success") {
                    if (response.change_status == 0) {
                        _this.parents('tr').children('.status').children('span').attr('class','label label-danger');
                        _this.parents('tr').children('.status').children('span').text('Arxiv');
                        _this.attr('data-status',1);
                        console.log(response);
                    }else if(response.change_status == 1){
                        _this.parents('tr').children('.status').children('span').attr('class','label label-success');
                        _this.parents('tr').children('.status').children('span').text('Faol');
                        _this.attr('data-status',0);
                    }
                }
            }
        })
    }
});


JS;

$this->registerJs($js);

?>
