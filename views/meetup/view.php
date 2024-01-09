<?php

use yii\helpers\Html;

$title_section = 'Savollar';
if (isset($title['title'])) {
    $title_section = $title['title'];
}
$this->title = $title_section;

?>
<style>
    .margin-bottom{
        margin-bottom:10px;
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
    td a .glyphicon{
        padding-left:15px;
    }
</style>
<div class="body">
<?php if(Yii::$app->session->hasFlash('error')):?>

    <div class="info">

    <?php Yii::$app->session->getFlash('error'); ?>

    </div>

<?php endif; ?>
    <h2><?=$title_section?></h2>
    <div class="sections-form">
        <form id='quiz-form'>
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="control-label">Bolimlar</label>
                    <select style="width: 100%" class='form-control select21' id="sections-select">
                        <option></option>
                        <?php
                        if (isset($sections) && !empty($sections)) {
                            foreach ($sections as $sections_value) {
                                $title = 0;
                                $id = 0;
                                if (isset($sections_value['title'])) {
                                    $title = $sections_value['title'];
                                }
                                if (isset($sections_value['id'])) {
                                    $id = $sections_value['id'];
                                }
                                ?>
                                <option value="<?=$id?>"><?=$title?></option>
                                <?php
                            }   
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Ishchilar</label>
                    <select name="Quiz[users][]"  style="width: 100%" multiple=true class='form-control select22' id="users-select">
                        <option></option>
                        <?php
                        if (isset($select_users) && !empty($select_users)) {
                            foreach ($select_users as $select_users_value) {
                                $title = 0;
                                $id = 0;
                                if (isset($select_users_value['second_name'])) {
                                    $title = $select_users_value['second_name'];
                                }
                                if (isset($select_users_value['id'])) {
                                    $id = $select_users_value['id'];
                                }
                                ?>
                                <option value="<?=$id?>"><?=$title?></option>
                                <?php
                            }   
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-8">
                    <label class="control-label">Savol</label>
                    <textarea class='form-control' required=true name="Quiz[question]" rows="3"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="control-label">Jarima</label>
                    <input type="number" name="Quiz[penalty]" required=true min='1' value='1000' class='form-control'>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6 form-check">
                    <input class="form-check-input" type="checkbox" name="Quiz[qestion_type]" >
                    <label class="form-check-label" >Sonlar bilan javob berish</label>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-success pull-right" id='add-quiz' data-cat=<?=$_GET['id']?>>Qoshish</a>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <table class='table table-bordered table-striped'>
        <thead>
            <tr>
                <th>Savollar</th>
                <th>Javob beruvchilar</th>
                <th>Link</th>
                <th>Savol Jarimasi</th>
                <th>Savol turi</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($quiz) && !empty($quiz)) {
                foreach ($quiz as $quiz_value) {
            ?>        
            <tr>
                <td><?php
                if (isset($quiz_value['question']) && !empty($quiz_value['question'])) {
                    echo $quiz_value['question'];   
                }
                ?></td>
                <td><?php
                if (isset($quiz_value['id']) && !empty($quiz_value['id']) && isset($users) && !empty($users)) {
                    foreach ($users as $users_value) {
                        if ($quiz_value['id'] == $users_value['quiz_id']) {
                            echo '<span class="badge padding_magin_badge">'.$users_value['second_name'].'</span><br>';          
                        }
                    }
                }
                ?>
                </td>
                <td><?php
                if (isset($quiz_value['id']) && !empty($quiz_value['id'])) {
                    if (isset($users) && !empty($users)) {
                        foreach ($users as $link_users_value) {
                            if ($quiz_value['id'] == $link_users_value['quiz_id']) {
                                echo '<a href="https://t.me/originalmebel_meetup_bot?start='.$link_users_value['phone_number'].'">
                                https://t.me/originalmebel_meetup_bot?start='.$link_users_value['phone_number'].'</a><br>';          
                            }
                        }
                    }
                }
                ?>
                </td>
                <td><?php
                    if (isset($quiz_value['sum'])) {
                        if ($quiz_value['sum'] > 0) {
                            echo $quiz_value['sum'];
                        }else {
                            echo 0;
                        }
                    }else {
                        echo 0;
                    }
                ?></td>
                <td><?php
                    if (isset($quiz_value['type'])) {
                        if ($quiz_value['type'] == 1) {
                            echo 'Sanoqli savol';
                        }else {
                            echo 'Oddiy savol';
                        }
                    }
                ?></td>
                <td>
                    <?php
                        $id = 0;
                        if (isset($quiz_value['id']) && !empty($quiz_value['id'])) {
                            $id = $quiz_value['id'];
                        }
                    ?>
                    <a title="Update" class='update' data-toggle="modal" data-target="#modal-default" data-update='<?=$id?>' aria-label="Update"><span class="glyphicon glyphicon-pencil"></span></a> 
                    <a title="Delete" class='delete' data-delete='<?=$id?>'><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php

$js = <<<JS
	$(function(){  
        
        $(document).on("change", '#sections-select', function() {
            var sections_id = $('#sections-select').val();
            $.ajax({
                url: 'sections-changes',
                data: {
                    sections_id: sections_id,
                },
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if (response.status == "success") {
                        document.getElementById('users-select').options.length = 0;
                        $('#users-select').html(response.content)
                    }
                }
            })
        });

        $(document).on("click", '#add-quiz', function() {
            var form = $('#quiz-form');
            var vat_id = $(this).attr('data-cat')
            if ($(this).attr('disabled') != 'disabled') {
                $(this).attr('disabled',true)
                $(this).attr('onlyread',true)
                $.ajax({
                    url: 'create',
                    data: form.serialize()+'&cat='+vat_id,
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        $(this).attr('disabled',true)
                        $(this).attr('onlyread',true)
                        if (response.status == "success") {
                            location.reload();
                        }else if (response.status == "error"){
                            location.reload();
                        }
                    }
                })
            }
        });

        $(document).on("click", '.update', function() {
            var update_question = $(this).attr('data-update')
            $.ajax({
                url: 'update-question',
                data: {
                    update_question: update_question,
                    save: false
                },
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if (response.status == "success") {
                        $('#modal-default .modal-body').html(response.content)
                        $('#modal-default .modal-header').html('Savol Ozgartirish')
                        $('#modal-default .modal-footer .btn-primary').addClass('update_quiz')
                        $('#modal-default .modal-footer .btn-primary').attr('data-update',update_question)
                    }
                }
            })
        });

        $(document).on("click", '.update_quiz', function() {
            var form = $('#form-update');
            var vat_id = $(this).attr('data-update')
            $.ajax({
                url: 'update-question',
                data: form.serialize()+'&save=true&update_question='+vat_id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if (response.status == "success") {
                        location.reload();
                    }else if (response.status == "error"){
                        location.reload();
                    }
                }
            })
        });
        
        $(document).on('click', '.delete', function(){
            if (confirm('Shu savol ochirish istaysizmi ?')) {
                var id = $(this).attr('data-delete')
                $.ajax({
                    url: 'delete-quiz',
                    data: {
                        id: id,
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function(response) {
                        if (response.status == 'success') {
                            location.reload()
                        }
                    }
                })
            }
        })


        $(".select21").select2({
            placeholder: "Bolim tanlang"
        });
        $(".select22").select2({
            placeholder: "Javob beruvchini tanlang"
        });
    })

JS;

$this->registerJs($js);
?>