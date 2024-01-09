<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sections */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sections-view">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
    </div>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'created_date',
            [
                'format' => 'html',
                'label' => 'Ish vaqti',
                'value' => function ($data) {
                    return $data->current_time;
               },
            ],
        ],
    ]) ?>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h3>Javobgar foydalanuvchilar</h3>
        </div>
        <div class="col-md-6">
            <?php if (empty($section_minimal)): ?>
                <h3>
                    <span class="btn btn-success pull-right open-modal" data-id="<?php echo $model->id ?>" data-toggle="modal" data-target="#modal-default">
                        Javobgar shaxs biriktirish
                    </span>
                </h3>
            <?php endif ?>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-striped">
        <tr>
            <th>#</th>
            <th>Foydalanuvchilar</th>
            <th>Bonus</th>
            <th>Jarima</th>
            <th>O'chirish</th>
        </tr>
        <?php if (!empty($section_minimal)): ?>
            <?php $i = 1; foreach ($section_minimal as $key => $value): ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td>
                        <?php echo (($value->user) ? $value->user->second_name : '') ?>
                    </td>
                    <td>
                        <span class="label label-success">
                            <?php echo number_format($value->minimal->bonus_sum,0,'',' ') ?>    
                        </span>
                    </td>
                    <td>
                        <span class="label label-danger">
                            <?php echo number_format($value->minimal->penalty_summ,0,'',' ')?>    
                        </span>
                    </td>
                    <td>
                        <a class="btn">
                            <i class="fa fa-trash delete-user" data-id="<?php echo $value->id ?>" section-id="<?php echo $model->id ?>"></i>
                        </a>
                    </td>
                </tr>
            <?php $i++; endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="4">
                    <center>
                        Ma'lumot mavjud emas!
                    </center>
                </td>
            </tr>
        <?php endif ?>
    </table>
    <hr>
    <?php if (!empty($brigadas)): ?>
        <div class="row">
            <div class="col-md-6">
                <h3>Javobgar shaxsning brigadalari</h3>
            </div>
            <div class="col-md-6">
                <h3>
                    <span class="btn btn-success pull-right open-brigada-modal" data-id="<?php 
                    $secMin = end($section_minimal);
                    echo $secMin['user_id'];
                    ?>" data-toggle="modal" data-target="#modal-default">
                        Javobgar shaxsga brigada qo'shish
                    </span>
                </h3>        
            </div>
        </div>
        <br>
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>Brigada nomi uz</th>
                <th>Brigadiri</th>
                <th>Brigada holati</th>
                <th>O'chirish</th>
            </tr>   
            <?php if (!empty($brigadas)): ?>
                <?php $i = 1; foreach ($brigadas as $key => $value): ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td>
                            <?php echo $value['title_uz']; ?>
                        </td>
                        <td>
                            <?php 
                                $connection = Yii::$app->getDb();
                                $command = $connection->createCommand("SELECT second_name FROM users 
                                    WHERE id = :id", [':id' => $value['user_id']]);
                                $result = $command->queryAll();
                                if (!empty($result[0])) {
                                    echo $result[0]['second_name'];
                                } else {
                                    echo "Brigadir ismi mavjud emas!";
                                }
                             ?>
                        </td>
                        <td>
                            <?php if ($value['status'] == 1) {
                                echo "Faol";
                            } else {
                                echo "Nofoal";
                            } ?>
                        </td>
                        <td>
                            <a class="btn">
                                <i class="fa fa-trash delete-user-brigada" data-id="<?php echo $value['id'] ?>"></i>
                            </a>
                        </td>
                    </tr>
                <?php $i++; endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <center>
                            Ma'lumot mavjud emas!
                        </center>
                    </td>
                </tr>
            <?php endif ?>
        </table>
        <hr>
    <?php elseif (!empty($employees)): ?>
        <div class="row">
            <div class="col-md-6">
                <h3>Javobgar shaxsning ishchilari</h3>
            </div>
            <div class="col-md-6">
                <h3>
                    <span class="btn btn-success pull-right open-employee-modal" data-section="<?php echo $model->id ?>" data-id="<?php 
                    $secMin = end($section_minimal);
                    echo $secMin['user_id'];
                    ?>" data-toggle="modal" data-target="#modal-default">
                        Javobgar shaxsga ishchi qo'shish
                    </span>
                </h3>        
            </div>
        </div>
        <br>
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>Ishchi ismi</th>
                <th>Ishchining holati</th>
                <th>O'chirish</th>
            </tr>   
            <?php if (!empty($employees)): ?>
                <?php $i = 1; foreach ($employees as $key => $value): ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td>
                            <?php echo $value['second_name']; ?>
                        </td>
                        <td>
                            <?php if ($value['status'] == 1) {
                                echo "Faol";
                            } else {
                                echo "Nofoal";
                            } ?>
                        </td>
                        <td>
                            <a class="btn">
                                <i class="fa fa-trash delete-user-employee" data-id="<?php echo $value['id'] ?>" section-id="<?php echo $model->id ?>"></i>
                            </a>
                        </td>
                    </tr>
                <?php $i++; endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <center>
                            Ma'lumot mavjud emas!
                        </center>
                    </td>
                </tr>
            <?php endif ?>
        </table>
        <hr>
    <?php else: ?>
        <div class="row">
            <div class="col-md-6">
                <h3>Javobgar shaxsning brigadalari</h3>
            </div>
            <div class="col-md-6">
                <h3>
                    <span class="btn btn-success pull-right open-brigada-modal" data-id="<?php 
                    $secMin = end($section_minimal);
                    echo $secMin['user_id'];
                    ?>" data-toggle="modal" data-target="#modal-default">
                        Javobgar shaxsga brigada qo'shish
                    </span>
                </h3>        
            </div>
        </div>
        <br>
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>Brigada nomi uz</th>
                <th>Brigadiri</th>
                <th>Brigada holati</th>
                <th>O'chirish</th>
            </tr>   
            <?php if (!empty($brigadas)): ?>
                <?php $i = 1; foreach ($brigadas as $key => $value): ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td>
                            <?php echo $value['title_uz']; ?>
                        </td>
                        <td>
                            <?php 
                                $connection = Yii::$app->getDb();
                                $command = $connection->createCommand("SELECT second_name FROM users 
                                    WHERE id = :id", [':id' => $value['user_id']]);
                                $result = $command->queryAll();
                                if (!empty($result[0])) {
                                    echo $result[0]['second_name'];
                                } else {
                                    echo "Brigadir ismi mavjud emas!";
                                }
                             ?>
                        </td>
                        <td>
                            <?php if ($value['status'] == 1) {
                                echo "Faol";
                            } else {
                                echo "Nofoal";
                            } ?>
                        </td>
                        <td>
                            <a class="btn">
                                <i class="fa fa-trash delete-user-brigada" data-id="<?php echo $value['id'] ?>"></i>
                            </a>
                        </td>
                    </tr>
                <?php $i++; endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <center>
                            Ma'lumot mavjud emas!
                        </center>
                    </td>
                </tr>
            <?php endif ?>
        </table>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h3>Javobgar shaxsning ishchilari</h3>
            </div>
            <div class="col-md-6">
                <h3>
                    <span class="btn btn-success pull-right open-employee-modal" data-section="<?php echo $model->id ?>" data-id="<?php 
                    $secMin = end($section_minimal);
                    echo $secMin['user_id'];
                    ?>" data-toggle="modal" data-target="#modal-default">
                        Javobgar shaxsga ishchi qo'shish
                    </span>
                </h3>        
            </div>
        </div>
        <br>
        <table class="table table-bordered table-striped">
            <tr>
                <th>#</th>
                <th>Ishchi ismi</th>
                <th>Ishchining holati</th>
                <th>O'chirish</th>
            </tr>   
            <?php if (!empty($employees)): ?>
                <?php $i = 1; foreach ($employees as $key => $value): ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td>
                            <?php echo $value['second_name']; ?>
                        </td>
                        <td>
                            <?php if ($value['status'] == 1) {
                                echo "Faol";
                            } else {
                                echo "Nofoal";
                            } ?>
                        </td>
                        <td>
                            <a class="btn">
                                <i class="fa fa-trash delete-user-employee" data-id="<?php echo $value['id'] ?>"></i>
                            </a>
                        </td>
                    </tr>
                <?php $i++; endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <center>
                            Ma'lumot mavjud emas!
                        </center>
                    </td>
                </tr>
            <?php endif ?>
        </table>
        <hr>
    <?php endif ?>
    <h3><?php echo $model->title ?> bo`limining ish vaqtlari</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>#</th>
            <th>Ish soati</th>
            <th>Boshlanish sanasi</th>
            <th>Tugash sanasi</th>
            <th>Jarayon</th>
        </tr>
        <?php $i = 1; if (!empty($model->times)): ?>
            <?php foreach ($model->times as $key => $value): ?>
                <tr>
                    <td>
                        <?php echo $i; ?>    
                    </td>
                    <td>
                        <?php echo $value->work_time ?>
                    </td>
                    <td>
                        <?php echo $value->start_date ?>
                    </td>
                    <td>
                        <?php if ($value->end_date != '9999-12-31'): ?>
                            <?php echo $value->end_date ?>    
                        <?php else: ?>
                            <span class="label label-success">Tugash sanasi belgilanmagan</span>
                        <?php endif ?>
                        
                    </td>
                    <td>
                        <?php if ($value->end_date == '9999-12-31'): ?>
                        <a class="fa fa-pencil update_time" 
                            data-toggle="modal" 
                            data-target="#modal-default"
                            time-id="<?php echo $value->id ?>" section-id="<?php echo $model->id ?>"></a>
                            <a class="fa fa-trash delete_time"
                            time-id="<?php echo $value->id ?>" section-id="<?php echo $model->id ?>"></a>
                        <?php endif ?>
                    </td>

                </tr>
            <?php $i++; endforeach ?>
            
        <?php else: ?>
            <tr>
                <td colspan="5">
                    Ish vaqti qo'shilmagan
                </td>
            </tr>
        <?php endif ?>
    </table>
</div>

<?php


$js = <<<JS

    $("#start_date").attr("autocomplete", "off");

    $(document).on('click','.delete_time',function(){

        if(confirm('Ish vaqtini o`chirmoqchimisiz ?')){
            let id = $(this).attr('time-id')
            let section_id = $(this).attr('section-id')

            $.ajax({
                url: '/index.php/sections/delete-time',
                dataType: 'json',
                type: 'GET',
                data: {id: id,section_id: section_id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()
                    }   
                    if(response.status == 'failure_not_time'){
                        alert('Bu vaqtni o`chira olmaysiz!')
                    }
                    if(response.status == 'failure'){
                        alert('Ma`lumot o`chirishda xatolik!')
                    }
                }
            });
        }
    })


    $(document).on('click','.update_time',function(){

        let id = $(this).attr('time-id')
        let section_id = $(this).attr('section-id')

        $.ajax({
            url: '/index.php/sections/open-modal-time',
            dataType: 'json',
            type: 'GET',
            data: {id: id,section_id: section_id},
            success: function (response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-header').html(response.header);
                    $('#modal-default .modal-body').html(response.content);

                    $('#modal-default .modal-footer .btn-primary').addClass('save_time');
                    $('.save_time').attr('time-id', id);
                    $('.save_time').attr('section-id', section_id);

                }   
                
            }
        });
    })


    $(document).on('click','.save_time',function(){
        let time = $('#time').val();
        let start_date = $('#start_date').val();
        let id = $(this).attr('time-id');
        let section_id = $(this).attr('section-id');


        if(time <= 0 && start_date <= 0){
            alert('Maydonlar bo`sh bo`lishi mumkin emas!');
        }
        else{
            $.ajax({
                url: '/index.php/sections/save-time',
                dataType: 'json',
                type: 'GET',
                data: {
                    time: time,
                    start_date: start_date, 
                    id: id,
                    section_id: section_id,
                },
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()

                    }
                    else{
                        alert('Ma`lumot saqlashda xatolik!')
                    }   
                    
                }
            });     
        }
    })

    $(document).on('click','.open-modal',function(){
        const id = $(this).attr('data-id')

        $.ajax({
            url: '/index.php/sections/open-modal',
            dataType: 'json',
            type: 'GET',
            data: {id: id},
            success: function (response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-header').html(response.header)
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-footer .btn-primary').addClass('save-info')
                }   
            }
        });
    })
    // ADD BRIGADA FOR USER
    $(document).on('click','.open-brigada-modal',function(){
        const id = $(this).attr('data-id')

        $.ajax({
            url: '/index.php/sections/open-brigada-modal',
            dataType: 'json',
            type: 'GET',
            data: {id: id},
            success: function (response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-header').html(response.header)
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-footer .btn-primary').addClass('save-brigada-info')
                }   
            }
        });
    })

    // ADD EMPLOYEES FOR USER
    $(document).on('click','.open-employee-modal',function(){
        const id = $(this).attr('data-id')
        const section_id = $(this).attr('data-section')

        $.ajax({
            url: '/index.php/sections/open-employee-modal',
            dataType: 'json',
            type: 'GET',
            data: {id: id, section_id: section_id},
            success: function (response) {
                if (response.status == 'success') {
                    $('#modal-default .modal-header').html(response.header)
                    $('#modal-default .modal-body').html(response.content)
                    $('#modal-default .modal-footer .btn-primary').addClass('save-employee-info')
                }   
            }
        });
    })

    $(document).on('click','.save-info',function(){
        const id = $('#users').attr('data-id')
        const bonus = $('#bonus').val()
        const penalty = $('#penalty').val()
        const users = $('#users').val()

        if(users != '' && bonus != '' && penalty != ''){
            $.ajax({
                url: '/index.php/sections/save-users',
                dataType: 'json',
                type: 'GET',
                data: {
                    id: id,
                    bonus: bonus,
                    penalty: penalty,
                    users: users,
                },
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }   
                }
            });
        }
        else{
            alert("Barcha maydonlar to'ldirilishi shart!");    
        }
    })
    // SAVE USERS' BRIGADAS
    $(document).on('click','.save-brigada-info',function(event){
        event.preventDefault();
        $.ajax({
            url: 'save-brigada-user',
            dataType: 'json',
            type: 'POST',
            data: $("#person-brigada-form").serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }   
            }
        });
    })

    // SAVE USERS' EMPLOYEES
    $(document).on('click','.save-employee-info',function(event){
        event.preventDefault();
        $.ajax({
            url: 'save-employee-user',
            dataType: 'json',
            type: 'POST',
            data: $("#person-employee-form").serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }   
            }
        });
    })

    // delete-user
    $(document).on('click','.delete-user',function(){
        const id = $(this).attr('data-id')
        const section_id = $(this).attr('section-id')

        if(confirm("Javobgar shaxsni o'chirmoqchimisiz ?")){
            $.ajax({
                url: '/index.php/sections/delete-user',
                dataType: 'json',
                type: 'GET',
                data: {id: id, section_id: section_id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    } 
                    else{
                        alert("Xatolik")
                    }  
                }
            });
        }
    })

    // DELETE USERS' BRIGADA
    $(document).on('click','.delete-user-brigada',function(){
        const id = $(this).attr('data-id')

        if(confirm("Javobgar shaxsni brigadasini o'chirmoqchimisiz ?")){
            $.ajax({
                url: '/index.php/sections/delete-user-brigada',
                dataType: 'json',
                type: 'GET',
                data: {id: id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    } 
                    else{
                        alert("Xatolik")
                    }  
                }
            });
        }
    })

    // DELETE USERS' EMPLOYEES
    $(document).on('click','.delete-user-employee',function(){
        var id = $(this).attr('data-id')
        var section_id = $(this).attr('section-id')

        if(confirm("Javobgar shaxsni ishchisini o'chirmoqchimisiz ?")){
            $.ajax({
                url: '/index.php/sections/delete-user-employee',
                dataType: 'json',
                type: 'GET',
                data: {id: id, section_id: section_id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    } 
                    else{
                        alert("Xatolik")
                    }  
                }
            });
        }
    })
JS;
$this->registerJs($js);
?>
