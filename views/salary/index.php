<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Users;
use yii\helpers\Url;
use app\models\SalaryCategory;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oylik-avans';
$this->params['breadcrumbs'][] = $this->title;
?>

<ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
    <li class="nav-item active">
        <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Oylik-avans</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-content-above-answer-tab" data-toggle="pill" href="#custom-content-above-answer" role="tab" aria-controls="custom-content-above-answer" aria-selected="false">Kategoriyalar</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-content-above-penlty-tab" data-toggle="pill" href="#custom-content-penlty-answer" role="tab" aria-controls="custom-content-penlty-answer" aria-selected="false">Javob varyantlar</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-content-above-event-tab" data-toggle="pill" href="#custom-content-event-answer" role="tab" aria-controls="custom-content-event-answer" aria-selected="false">O'tkazmalar</a>
    </li>
</ul>

<div class="tab-content" id="custom-content-above-tabContent">
    <div class="tab-pane active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
        <div class="salary-amount-index">
            <h1><?= Html::encode($this->title) ?></h1>
            <form class="row" style="margin-bottom: 17px">
                <div id="salom" class="col-md-3">
                    <select name="branch" class="form-control select2" id="month" data-placeholder="Oyni tanlang" style="width: 100%">
                        <option></option>
                        <option  value="<?= date('Y-01-01')?>">Yanvar</option>
                        <option  value="<?= date('Y-02-01')?>">Fevral</option>
                        <option  value="<?= date('Y-03-01')?>">Mart</option>
                        <option  value="<?= date('Y-04-01')?>">Aprel</option>
                        <option  value="<?= date('Y-05-01')?>">May</option>
                        <option  value="<?= date('Y-06-01')?>">Iyun</option>
                        <option  value="<?= date('Y-07-01')?>">Iyul</option>
                        <option  value="<?= date('Y-08-01')?>">Avgust</option>
                        <option  value="<?= date('Y-09-01')?>">Sentabr</option>
                        <option  value="<?= date('Y-10-01')?>">Oktabr</option>
                        <option  value="<?= date('Y-11-01')?>">Noyabr</option>
                        <option  value="<?= date('Y-12-01')?>">Dekabr</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <?php
                        
                        if (isset($selectUsers) and !empty($selectUsers)){
                            ?>
                                <select name="user" class="form-control select2" id="user" data-placeholder="Ishchi tanlang" style="width: 100%">
                                    <option value=""></option>
                                    <?php foreach ($selectUsers as $key => $value): ?>
                                        <option value="<?php echo $value->id; ?>">
                                            <?php echo $value->second_name; ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            <?php
                        }
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    if (isset($selectCategory) and !empty($selectCategory)){
                        ?>
                        <select name="category" class="form-control select2" id="category" data-placeholder="Kategoriya tanlang" style="width: 100%">
                            <option value=""></option>
                            <?php foreach ($selectCategory as $key => $value): ?>
                                <option value="<?php echo $value->id; ?>">
                                    <?php echo $value->title; ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-3">
                    <button type="button" class="filter btn btn-primary">Ko'rish</button>
                </div>
            </form>

            <table class="table table-bordered table-striped sort_table2" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ishchi</th>
                        <th>Kategoriya</th>
                        <th>Summa</th>
                        <th>Sana</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    if (isset($model) and !empty($model)){
                        $i = 1;
                        foreach ($model as $key => $value) {
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$value['second_name']."</td>";
                            echo "<td>".$value['title']."</td>";
                            echo "<td>".$value['price']."</td>";
                            echo "<td>".$value['date']."</td>";
                            echo "<td>";
                            ?>
                            <?php
                                $id = 0;
                                if(isset($value['id']) && !empty($value['id'])){
                                    $id = $value['id'];
                                }
                            ?>
<!--                            <a class='update' data-target="#modal-default" data-toggle="modal" title="Update" data-update='--><?//=$id?><!--' aria-label="Update"><span class="fa fa-trash"></span></a>-->
                            <?php
                            echo "</td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr>";
                        echo  "<td class='text-center' colspan='3'>Ma'lumot yo'q</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>


        </div>
    </div>

    <div class="tab-pane fade" id="custom-content-above-answer" role="tabpanel" aria-labelledby="custom-content-above-answer-tab">
        <div class="row">
            <div class="col-md-6">
                <h2>Kategoriya</h2>
            </div>
            <div class="col-md-6">
                <?php
                    if (Yii::$app->user->can('Admin')){ ?>
                        <button id='add_category' class="btn btn-primary pull-right" data-target="#modal-default" data-toggle="modal" style="margin-top: 20px">Kategoriya qo'shish</button>
                    <?php }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped sort_table2" style="margin-top: 10px;">
                    <th>#</th>
                    <th>Kategoriya nomi</th>
                    <th>Balans</th>
                    <th>O'zgartirish</th>

                    <?php
                    if (isset($category) and !empty($category)){
                        $i = 1;
                        foreach ($category as $key => $value) {
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$value['title']."</td>";
                            echo "<td>".$value['balance']."</td>";
                            echo "<td>";
                            ?>
                            <?php
                                $id = 0;
                                if(isset($value['id']) && !empty($value['id'])){
                                    $id = $value['id'];
                                }
                                if (Yii::$app->user->can('Admin')){ ?>
                                    <a class='update' data-target="#modal-default" data-toggle="modal" title="Update" data-update='<?=$id?>' aria-label="Update"><span class="glyphicon glyphicon-pencil"></span></a>
                                <?php } ?>
                            <?php
                            echo "</td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr>";
                        echo  "<td class='text-center' colspan='3'>Ma'lumot yo'q</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="custom-content-penlty-answer" role="tabpanel" aria-labelledby="custom-content-penlty-answer-tab">
        <div class="row">
            <div class="col-md-6">
                <h2>Javob varyantlar</h2>
            </div>
            <div class="col-md-6">
                <?php
                    if (Yii::$app->user->can('Admin')){ ?>
                        <button id='add_answer' class="btn btn-primary pull-right" data-target="#modal-default" data-toggle="modal" style="margin-top: 20px">Javob varyanti qo'shish</button>
                    <?php }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped sort_table3" style="margin-top: 10px;">
                    <th>#</th>
                    <th>Javob</th>
                    <th>O'zgartirish</th>

                    <?php
                    if (isset($answer) and !empty($answer)){
                        $i = 1;
                        foreach ($answer as $key => $value) {
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$value['title']."</td>";
                            echo "<td>";
                            ?>
                            <?php
                                $id = 0;
                                if(isset($value['id']) && !empty($value['id'])){
                                    $id = $value['id'];
                                }
                            ?>
                            <?php
                            if (Yii::$app->user->can('Admin')){ ?>
                                <a class='update_a' data-target="#modal-default" data-toggle="modal" title="Update" data-update='<?=$id?>' aria-label="Update"><span class="glyphicon glyphicon-pencil"></span></a>
                            <?php } ?>

                            <?php
                            echo "</td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr>";
                        echo  "<td class='text-center' colspan='3'>Ma'lumot yo'q</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="custom-content-event-answer" role="tabpanel" aria-labelledby="custom-content-event-answer-tab">
        <div class="row">
            <div class="col-md-6">
                <h2>O'tkazmalar</h2>
            </div>
        </div>
        <form class="row" style="margin-bottom: 17px">
            <div class="col-md-3">
                <?php
                $selectUsers = Users::find()->all();
                if (isset($selectUsers) and !empty($selectUsers)){
                    ?>
                    <select name="user" class="form-control select2" id="user_balance" data-placeholder="Ishchi tanlang" style="width: 100%">
                        <option value=""></option>
                        <?php foreach ($selectUsers as $key => $value): ?>
                            <option value="<?php echo $value->id; ?>">
                                <?php echo $value->second_name; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <?php
                }
                ?>
            </div>
            <div class="col-md-3">
                <?php
                echo DatePicker::widget([
                    'name' => 'salary_date',
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
                <button type="button" class="balance_filter btn btn-primary">Ko'rish</button>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped balance_table" style="margin-top: 10px;">
                    <thead>
                        <th>#</th>
                        <th>O'tkazuvchi</th>
                        <th>Qabul qilivchi</th>
                        <th>Summa</th>
                        <th>Kategoriya</th>
                        <th>Sana</th>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($event) and !empty($event)){
                            $i = 1;
                            foreach ($event as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$i."</td>";
                                $selectUsers = Users::find()->where(['id' => $value->user_id])->one();
                                if (isset($selectUsers) and !empty($selectUsers)){
                                    echo "<td>".$selectUsers->second_name."</td>";
                                }
                                $selectUserSecond = Users::find()->where(['chat_id' => $value->receiver])->one();
                                if (isset($selectUserSecond) and !empty($selectUserSecond)){
                                    echo "<td>".$selectUserSecond->second_name."</td>";
                                }
                                echo "<td>".$value['quantity']."</td>";
                                $selectCategory = SalaryCategory::find()->where(['id' => $value->category_id])->one();
                                if (isset($selectCategory) and !empty($selectCategory)){
                                    echo "<td>".$selectCategory->title."</td>";
                                }
                                echo "<td>".date('Y-m-d H:i', strtotime($value['date']))."</td>";
                                echo "</tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>";
                            echo  "<td class='text-center' colspan='5'>Ma'lumot yo'q</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    
    $(function(){
        $('.table').dataTable({
            "paging": false
        } );
    })

    $(document).on('click', '#add_category', function(){
        $.ajax({
            url: 'category',
            data: {
                cat: false,
            },
            dataType: 'json',
            type: 'get',
            success: function(response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-header').html("Kategoriya qo'shish")
                    $('#modal-default .modal-footer .btn-primary').addClass('save_category')
                }
            }
        })
    })
    
    $(document).on('click', '.save_category', function(){
        var category_title = $('#category_title').val()
        var category_balance = $('#category_balance').val()
        if (category_title.trim().length != 0) {
            if (category_balance.trim().length != 0){
                if ($.isNumeric($('#category_balance').val())){
                    if ($(this).attr('disabled') != 'disabled') {
                        $(this).attr('disabled',true)
                        $(this).attr('onlyread',true)
                        $.ajax({
                            url: 'category',
                            data: {
                                cat: true,
                                category: category_title,
                                category_balance: category_balance
                            },
                            dataType: 'json',
                            type: 'get',
                            success: function(response) {
                                if (response.status == 'success') {
                                    $(this).attr('onlyread',true)  
                                    location.reload()
                                }
                                if (response.status == 'null') {
                                    alert("Balans 0 ga teng bo'lishi mumkin emas!")   
                                }
                            }
                        })
                    }
                } else {
                    alert('Balans kiritayotganingzda sonlardan foydalaning!')    
                }
            } else {
                alert('Balans kiritmadingiz!')   
            }
        }else{
            alert('Kategoriya nomini kiritmadingiz!')
        }
    })
    
    $(document).on('click', '.update', function(){
        var id = $(this).attr('data-update')
        $.ajax({
            url: 'update-category',
            data: {
                edit: false,
                id: id,
            },
            dataType: 'json',
            type: 'get',
            success: function(response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-header').html("Kategoriya o'zgartirish")
                    $('#modal-default .modal-footer .btn-primary').addClass('update_category')
                    $('#modal-default .modal-footer .btn-primary').attr('data-update',id)

                }
            }
        })
    })
    
    $(document).on('click', '.update_category', function(){
        var category_title = $('#category_title').val()
        var category_balance = $('#category_balance').val()
        var id = $(this).attr('data-update')
        if (category_title.trim().length != 0) {
            if (category_balance.trim().length != 0){
                if ($.isNumeric($('#category_balance').val())){
                    if ($(this).attr('disabled') != 'disabled') {
                        $(this).attr('disabled',true)
                        $(this).attr('onlyread',true)
                        $.ajax({
                            url: 'update-category',
                            data: {
                                edit: true,
                                category: category_title,
                                category_balance: category_balance,
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
                } else {
                    alert('Balans kiritayotganingzda sonlardan foydalaning!')       
                }
            } else {
                alert('Balans kiritmadingiz!')    
            }
        }else{
            alert('Kategoriya nomini kiritmadingiz!')
        }
    })
    
    $(document).on('click', '#add_answer', function(){
        $.ajax({
            url: 'answer',
            data: {
                ans: false,
            },
            dataType: 'json',
            type: 'get',
            success: function(response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-header').html("Javob varyanti qo'shish")
                    $('#modal-default .modal-footer .btn-primary').addClass('save_answer')
                }
            }
        })
    })
    
    $(document).on('click', '.save_answer', function(){
        var answer_title = $('#answer_title').val()
        if (answer_title.trim().length != 0) {
            if ($(this).attr('disabled') != 'disabled') {
                $(this).attr('disabled',true)
                $(this).attr('onlyread',true)
                $.ajax({
                    url: 'answer',
                    data: {
                        ans: true,
                        answer: answer_title
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
            alert('Javob kiritmadingiz!')
        }
    })
    
    $(document).on('click', '.update_a', function(){
        var id = $(this).attr('data-update')
        $.ajax({
            url: 'update-answer',
            data: {
                edit: false,
                id: id,
            },
            dataType: 'json',
            type: 'get',
            success: function(response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-header').html("Javob varyantini o'zgartirish")
                    $('#modal-default .modal-footer .btn-primary').addClass('update_answer')
                    $('#modal-default .modal-footer .btn-primary').attr('data-update',id)
                }
            }
        })
    })
    
    $(document).on('click', '.update_answer', function(){
        var answer_title = $('#answer_title').val()
        var id = $(this).attr('data-update')
        if (answer_title.trim().length != 0) {
            if ($(this).attr('disabled') != 'disabled') {
                $(this).attr('disabled',true)
                $(this).attr('onlyread',true)
                $.ajax({
                    url: 'update-answer',
                    data: {
                        edit: true,
                        answer: answer_title,
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
            alert('Javob kiritmadingiz!')
        }
    })
    
    $(document).on("click", ".filter", function(){
        var date =  $("#month").val();
        var user =  $("#user").val();
        var category =  $("#category").val();
        $.ajax({
            url: "salary",
            data: {
                date: date,
                user: user,
                category: category
            },
            dataType: "json",
            type: "get",
            success: function(response) {
                if (response.status == "success") {
                    $("#tablee tbody").html(response.content)
                }            
            },
            error: function(error){
                console.log(error)
            }
        })
    })
    
    $(document).on("click", ".balance_filter", function(){
        var balance_user =  $("#user_balance").val();
        var balance_date =  $("#date-filter").val();
        $.ajax({
            url: "balance",
            data: {
                balance_user: balance_user,
                balance_date: balance_date
            },
            dataType: "json",
            type: "get",
            success: function(response) {
                if (response.status == "success") {
                    $(".balance_table tbody").html(response.content)
                }            
            },
            error: function(error){
                console.log(error)
            }
        })
    })
JS;
$this->registerJs($js);
?>