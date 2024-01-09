<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;


$this->title = 'Meet Uplarga savolar';

function bot($method, $data = []) {
    $url = 'https://api.telegram.org/bot1656261809:AAFUxaShUltI6zG6KdDrdUQvLQYyp1x7BAU/'.$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec($ch);
    
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

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

<ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
    <li class="nav-item active">
        <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Barcha savollar</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-content-above-answer-tab" data-toggle="pill" href="#custom-content-above-answer" role="tab" aria-controls="custom-content-above-answer" aria-selected="false">Kunlik hisobotlar</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-content-above-penlty-tab" data-toggle="pill" href="#custom-content-penlty-answer" role="tab" aria-controls="custom-content-penlty-answer" aria-selected="false">Jarima summa</a>
    </li>
</ul>

<div class="tab-content" id="custom-content-above-tabContent">

    <div class="tab-pane active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
        <div class="row margin-bottom">
            <div class="col-md-6">
                <h2>Savollar Kategoriya</h2>
            </div>
            <div class="col-md-6">
                <button id='add_category_quiz' style='margin-top: 1.6em;' class="btn btn-primary pull-right" data-target="#modal-default" data-toggle="modal">Kategoriya qo’shish</button>
            </div>
        </div>
        <table class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Savol kategoriyalar</th>
                    <th>Javob beruvchilar soni</th>
                    <th>Savolar soni</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($category_quiz) && !empty($category_quiz)) {
                    foreach ($category_quiz as $category_val) {
                ?>
                <tr>
                    <td><?php
                        if (isset($category_val['title']) && !empty($category_val['title'])) {
                            echo $category_val['title'];
                        }
                    ?></td>
                    <td><?php
                        if (isset($users_count) && !empty($users_count)) {
                            $count = 0;
                            foreach ($users_count as $us_count_value) {
                                if ($us_count_value['category_id'] == $category_val['id']) {
                                    $count++;
                                }
                            }
                            echo $count;
                        }else {
                            echo 0;
                        }
                    ?></td>
                    <td><?php
                        if (isset($quiz_count) && !empty($quiz_count)) {
                            $count = 0;
                            foreach ($quiz_count as $qz_count_value) {
                                if ($qz_count_value['category_id'] == $category_val['id']) {
                                    $count = $qz_count_value['q_co'];
                                }
                            }
                            echo $count;
                        }else {
                            echo 0;
                        }
                    ?></td>
                    <td>
                        <?php
                        $id = 0;
                        if(isset($category_val['id']) && !empty($category_val['id'])){
                            $id = $category_val['id'];
                        }
                        ?>
                        <a href="/index.php/meetup/view?id=<?=$id?>" title="View" aria-label="View"><span class="glyphicon glyphicon-eye-open"></span></a> 
                        <a class='update' data-target="#modal-default" data-toggle="modal" title="Update" data-update='<?=$id?>' aria-label="Update"><span class="glyphicon glyphicon-pencil"></span></a> 
                        <a class='delete' title="Delete" data-delete='<?=$id?>'><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
                <?php
                    }
                }else {
                    ?>
                    <tr>
                        <td colspan="4">
                            <center class="empty">Hech qanday natija topilmadi.</center>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="custom-content-above-answer" role="tabpanel" aria-labelledby="custom-content-above-answer-tab">
        <?php if (Yii::$app->session->hasFlash('success1')): ?>
            <div class="alert alert-danger alert-dismissable div_error">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
                <?= Yii::$app->session->getFlash('success1') ?>
            </div>
        <?php endif; ?>
        <div class="row margin-bottom">
            <div class="col-md-6">
                <h2>Kunlik hisobotlar</h2>
            </div>
        </div>
        <form class='row margin-bottom'>
            <div id="salom" class="col-md-5">
                <select name="user" class="form-control select2" id="users-select" data-placeholder="Javob beruvchilarni tanlang" style="width: 100%">
                    <option value="0">Hamma foydalanuvchi</option>
                    <?php
                        if (isset($users) && !empty($users)) {
                            foreach ($users as  $us_select_value) {
                                ?>
                                <option value="<?php
                                    if (isset($us_select_value['id']) && !empty($us_select_value['id'])) {
                                        echo $us_select_value['id'];
                                    }
                                    ?>">
                                    <?=(isset($us_select_value['second_name']) && !empty($us_select_value['second_name'])) ? $us_select_value['second_name'] : 'Ismi yoq foydalanuvchi!';?> 
                                    <?php
                                        if (isset($us_select_value['type']) && !empty($us_select_value['type'])) {
                                            switch ($us_select_value['type']) {
                                                case '1':
                                                    echo ' ( Admin )';
                                                break;
                                                case '2':
                                                    echo ' ( Nazoratchi )';
                                                break;
                                                case '3':
                                                    echo ' ( O`TK )';
                                                break;
                                                case '4':
                                                    echo ' ( Sotuvchi )';
                                                break;
                                                case '5':
                                                    echo ' ( Bo`lim boshlig`i )';
                                                break;
                                                case '6':
                                                    echo ' ( Kroychi )';
                                                break;
                                                case '7':
                                                    echo ' ( Bo`lim ishchisi )';
                                                break;
                                            }
                                        }
                                    ?>
                                </option>
                                <?php
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <?php
                echo DatePicker::widget([
                    'name' => 'Section[date]',
                    'id' => 'date-filter',
                    'options' => ['placeholder' => 'Sana', 'autocomplete' => 'off'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose'=>true,
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-3">
                <button type="button" class="filter btn btn-primary">Ko'rish</button>
            </div>
        </form>
        <table class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ishchilar</th>
                    <th>Sana</th>
                    <th>Ro`li</th>
                    <th>Javobni yuklash</th>
                </tr>
            </thead>
            <tbody id='daily_answer'>
                <?php
                if (isset($daily_answer) && !empty($daily_answer)) {
                    $number = 0;
                    foreach ($daily_answer as $da_value) {
                        $number += 1;
                    ?>
                        <tr>
                            <td><?=$number ?></td>
                            <td><?php
                                if (isset($da_value['second_name']) && !empty($da_value['second_name'])) {
                                    echo $da_value['second_name'];
                                }else {
                                    echo 'Foydalanuvchi ismi yoq!';
                                }
                            ?></td>
                            <td><?php
                                if (isset($da_value['date']) && !empty($da_value['date'])) {
                                    echo $da_value['date'];
                                }else {
                                    echo 'Sana xato ketgan!';
                                }
                            ?></td>
                            <td><?php
                                if (isset($da_value['type']) && !empty($da_value['type'])) {
                                    switch ($da_value['type']) {
                                        case '1':
                                            echo 'Admin';
                                        break;
                                        case '2':
                                            echo 'Nazoratchi';
                                        break;
                                        case '3':
                                            echo 'O`TK';
                                        break;
                                        case '4':
                                            echo 'Sotuvchi';
                                        break;
                                        case '5':
                                            echo 'Bo`lim boshlig`i';
                                        break;
                                        case '6':
                                            echo 'Kroychi';
                                        break;
                                        case '7':
                                            echo 'Bo`lim ishchisi';
                                        break;
                                    }
                                }else {
                                    echo 'Foydalanuvchi ro`li yoq!';
                                }
                            ?></td>
                            <td><?php
                            if (isset($da_value['da_type']) && $da_value['da_type'] == 1) {
                                if (isset($da_value['file_id']) && preg_match('~^\d+$~',$da_value['file_id'])) {
                                    echo 'Jarima: '.$da_value['file_id'];
                                }else {
                                    echo 'Jarima: 0';
                                }
                            }elseif (isset($da_value['file_id']) && !empty($da_value['file_id'])) {
                                $pdfFile = bot('getFile', [
                                    'file_id' => $da_value['file_id']
                                ]);
                                if(isset($pdfFile) && !empty($pdfFile)) {
                                    $file = 'https://api.telegram.org/file/bot1656261809:AAFUxaShUltI6zG6KdDrdUQvLQYyp1x7BAU/'.$pdfFile->result->file_path;
                                    echo '<a download href=\''.$file.'\'>PDF</a>                        
                                    <a data-target="#modal-default" data-toggle="modal" class="view-pdf" data-url="'.$pdfFile->result->file_path.'" title="View" aria-label="View"><span class="glyphicon glyphicon-eye-open"></span></a>';
                                }else {
                                    echo 'Document topilmadi!!';
                                }
                            }else {
                                echo 'Document topilmadi!!';
                            }
                            ?></td>
                        </tr>
                    <?php
                    }
                }else {
                    ?>
                        <tr>
                            <td colspan="5">
                                <center class="empty">Hech qanday natija topilmadi.</center>
                            </td>
                        </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="custom-content-penlty-answer" role="tabpanel" aria-labelledby="custom-content-penlty-answer-tab">
        <div class="row margin-bottom">
            <div class="col-md-6">
                <h2>Jarimalar</h2>
            </div>
        </div>

        <table class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ishchilar</th>
                    <th>Ro`li</th>
                    <th>Umimiy jarima summa</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($penalty) && !empty($penalty)) {
                    $number = 0;
                    foreach ($penalty as $pe_value) {
                        $number += 1;
                    ?>
                        <tr>
                            <td><?=$number ?></td>
                            <td><?php
                                if (isset($pe_value['second_name']) && !empty($pe_value['second_name'])) {
                                    echo $pe_value['second_name'];
                                }else {
                                    echo 'Foydalanuvchi ismi yoq!';
                                }
                            ?></td>
                            <td><?php
                                if (isset($pe_value['type']) && !empty($pe_value['type'])) {
                                    switch ($pe_value['type']) {
                                        case '1':
                                            echo 'Admin';
                                        break;
                                        case '2':
                                            echo 'Nazoratchi';
                                        break;
                                        case '3':
                                            echo 'O`TK';
                                        break;
                                        case '4':
                                            echo 'Sotuvchi';
                                        break;
                                        case '5':
                                            echo 'Bo`lim boshlig`i';
                                        break;
                                        case '6':
                                            echo 'Kroychi';
                                        break;
                                        case '7':
                                            echo 'Bo`lim ishchisi';
                                        break;
                                    }
                                }else {
                                    echo 'Foydalanuvchi ro`li yoq!';
                                }
                            ?></td>
                            <td><?php
                                if (isset($pe_value['penalty_sum']) && (!empty($pe_value['penalty_sum']) || $pe_value['penalty_sum'] == 0) ) {
                                    echo $pe_value['penalty_sum'];
                                }else {
                                    echo 'Jarima summa yoq!';
                                }
                            ?></td>
                            <td>
                            <?php
                            $id = 0;
                            if (isset($pe_value['pu_id'])) {
                                $id = $pe_value['pu_id'];
                            }
                            ?>
                            <a class='pay-pentlie' data-pay='<?=$id?>' title="Tolash"  aria-label="Tolash"><i class="fa fa-credit-card"></i></a></td>
                        </tr>
                    <?php
                    }
                }else {
                    ?>
                        <tr>
                            <td colspan="5">
                                <center class="empty">Hech qanday natija topilmadi.</center>
                            </td>
                        </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php
$js = <<<JS
    $('select').select2()
    $(document).on('click', '#add_category_quiz', function(){
        $.ajax({
            url: 'category',
            data: {
                con: false,
            },
            dataType: 'json',
            type: 'get',
            success: function(response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-header').html('Kategoriya qoshish')
                    $('#modal-default .modal-footer .btn-primary').addClass('save_category')

                }
            }
        })
    })

    $(document).on('click', '.save_category', function(){
        var title_category = $('#quiz_category_title').val()
        if (title_category.trim().length != 0) {
            if ($(this).attr('disabled') != 'disabled') {
                $(this).attr('disabled',true)
                $(this).attr('onlyread',true)
                $.ajax({
                    url: 'category',
                    data: {
                        con: true,
                        title: title_category
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function(response) {
                        if (response.status == 'success') {
                            $(this).attr('disabled',true)
                            $(this).attr('onlyread',true)  
                            location.reload()
                        }
                    }
                })
            }
        }else{
            alert('Kategorya nomi bolish kerak')
        }
    })

    $(document).on('click', '.update', function(){
        var id = $(this).attr('data-update')
        $.ajax({
            url: 'update-category',
            data: {
                update: false,
                id: id,
            },
            dataType: 'json',
            type: 'get',
            success: function(response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-header').html('Kategoriya ozgartirish')
                    $('#modal-default .modal-footer .btn-primary').addClass('update_category')
                    $('#modal-default .modal-footer .btn-primary').attr('data-update',id)

                }
            }
        })
    })

    $(document).on('click', '.view-pdf', function(){
        var url = $(this).attr('data-url')
        var iframe = '<div style="height:500px;"><iframe src="https://docs.google.com/gview?url=https://api.telegram.org/file/bot1656261809:AAFUxaShUltI6zG6KdDrdUQvLQYyp1x7BAU/'+url+'&embedded=true" style="width:100%; height:100%;" frameborder="0"></iframe><div>'
        $('#modal-default .modal-body').html(iframe)
    })

    $(document).on('click', '.delete', function(){
        if (confirm('Shu savol kategoriyani ochirish istaysizmi ?')) {
            var id = $(this).attr('data-delete')
            $.ajax({
                url: 'delete-category',
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
    
    $(document).on('click', '.pay-pentlie', function(){
        if (confirm('Jarima tolash istaysizmi?')) {
            var id = $(this).attr('data-pay')
            $.ajax({
                url: 'pay-penalties',
                data: {
                    id: id,
                },
                dataType: 'json',
                type: 'get',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload()
                    }else{
                        location.reload()
                    }
                }
            })
        }
    })
    
    $(document).on('click', '.update_category', function(){
        var title_category = $('#quiz_category_title').val()
        var id = $(this).attr('data-update')
        if (title_category.trim().length != 0) {
            if ($(this).attr('disabled') != 'disabled') {
                $(this).attr('disabled',true)
                $(this).attr('onlyread',true)
                $.ajax({
                    url: 'update-category',
                    data: {
                        update: true,
                        title: title_category,
                        id: id
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function(response) {
                        $(this).attr('disabled',true)
                        $(this).attr('onlyread',true)
                        if (response.status == 'success') {
                            location.reload()
                        }
                    }
                })
            }
        }else{
            alert('Kategorya nomi bolish kerak')
        }
    })

JS;

$this->registerJs($js);

?>

<?php $this->registerJs(
    '
    $(document).on("click", ".filter", function(){
        var users =  $("#users-select").val();
        var date =  $("#date-filter").val();
        $.ajax({
            url: "filter",
            data: {
                users: users,
                date: date
            },
            dataType: "json",
            type: "POST",
            success: function(response) {
                if (response.status == "success") {
                    $("#daily_answer").html(response.content)
                }            
            },
            error: function(error){
                console.log(error)
            }
        })
    })   
    

    $(".div_error").delay(3000).fadeOut();
    
', yii\web\View::POS_READY); ?>