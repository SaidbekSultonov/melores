<?php 
use app\models\UsersSection;
?>
<div class="row">
	<div class="col-md-6">
		<h2>Kroy 3D boti foydalanuvchilari</h2>
	</div>
	<div class="col-md-6">
		<h2 class="btn btn-success pull-right" id="add_otk_bot"
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
		<th>Bo`limlari va ro`li</th>
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
					<?php $users_section = UsersSection::find()
					->where([
						'user_id' => $value['user_id']
					])
					->all(); ?>
					<?php if (!empty($users_section)): ?>
						<?php foreach ($users_section as $keys => $values): ?>
							<div class="input-group" style="margin-bottom: 2px">
								<div class="input-group-btn">
				                  	<button type="button" class="btn btn-info btn-sm">
				                  		<?php echo $values->section->title ?>
				                  	</button>
				                </div>
				                <input readonly disabled type="text" class="form-control input-sm" placeholder="Username" value="<?php echo $values->role($values->role); ?>">
				            </div>
						<?php endforeach ?>
					<?php else: ?>
						<span class="label label-default">Ma`lumot yo`q</span>
					<?php endif ?>
				</td>
				<td>
					<a href="<?php echo $bot->link.$value['phone_number'] ?>">
						<?php echo $bot->link.$value['phone_number'] ?>
					</a>
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
						<a class="delete_user" href="/index.php/bot/delete-bot-one?id=<?php echo $value['user_id'] ?>">
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
