<div class="row">
    <div class="col-md-6">
        <h2>Usta boti foydalanuvchilari</h2>
    </div>
    <div class="col-md-6">
        <h2 class="btn btn-success pull-right" id="add_master_bot"
        data-target="#modal-default" data-toggle="modal">
            Foydalanuvchi qo`shish
        </h2>
    </div>
</div>
<hr>
<table class="table table-bordered table-striped">
    <tr>
        <th>#</th>
        <th>Foydalanuvchi Ismi</th>
        <th>Telefon raqami</th>
        <th>Link</th>
        <th>Holati</th>
        <th>O`chirish</th>
    </tr>
    <?php if (!empty($model)): ?>
        <?php $i = 1; foreach ($model as $key => $value): ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value['second_name'] ?></td>
                <td><?php echo $value['phone_number'] ?></td>
                <td>
                    <a href="<?php echo $value['link'] ?>"><?php echo $value['link'].$value['phone_number'] ?></a>
                </td>
                <td>
                    <?php if ($value['status'] == 1):?>
                        <span class="label label-success">Faol</span>
                    <?php else: ?>
                        <span class="label label-danger">
                            Nofaol
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <center>
                        <a class="delete_user" href="/index.php/bot/delete-master-bot?id=<?php echo $value['bot_user_id'] ?>">
                            <i class="fa fa-trash"></i>
                        </a>
                    </center>
                    
                    
                </td>               
            </tr>
        <?php $i++; endforeach ?>
        
    <?php else: ?>
        <tr>
            <td colspan="6">
                Ma'lumot mavjud emas!
            </td>
        </tr>
    <?php endif ?>
</table>


<?php $this->registerJs(
'
    $(document).on("click",".delete_user",function(){
        if(!confirm("Foydalanuvchini botdan o`chirmoqchimisiz ?")){
            event.preventDefault();
        }
    })

', yii\web\View::POS_READY); ?>
