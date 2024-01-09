<?php  
	use yii\helpers\Html;

	function bot($method, $data = []) {
	    $url = 'https://api.telegram.org/bot1594810052:AAHQEku5Q3tslozhq7uGiUpEB6oGUtzUXfs/'.$method;
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

	function botFile($method, $data = []) {
	    $url = 'https://api.telegram.org/bot1594810052:AAHQEku5Q3tslozhq7uGiUpEB6oGUtzUXfs/'.$method;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, count($data));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    $res = curl_exec($ch);
	    curl_close($ch);
	    
	    if(curl_error($ch)) {
	        var_dump(curl_error($ch));
	    } else {
	        return json_decode($res);
	    }
	}

?>

<div class="row">
	<div class="col-md-12">
		<h3>
			<b>
				<?php echo $order->title ?>
			</b> buyurtmasiga yuklangan fayllar ro`yxati
		</h3>
	</div>
</div>
<hr>
<table class="table table-bordered table-striped">
	<tr>
		<th>#</th>
		<th>Fayl</th>
		<th>Fayl turi</th>
		<th>Foydalanuvchi</th>
		<th>Kiritilgan vaqti</th>
		<th>
			<center>
				O`chirish	
			</center>
		</th>
	</tr>
	<?php if (!empty($model)): $i = 1; ?>
		<?php foreach ($model as $key => $value): ?>
			<tr>
				<td><?php echo $i ?></td>
				<td>
					<?php  

					if (!is_null($value->file)){
                        $imageFileId = bot('getFile', [
                            'file_id' => $value->file
                        ]);

                         if(isset($imageFileId) and $value->type != "text") {
                            $file = 'https://api.telegram.org/file/bot1594810052:AAHQEku5Q3tslozhq7uGiUpEB6oGUtzUXfs/'.$imageFileId->result->file_path;
                            }

                        switch($value->type){
                            case "photo":
                                    echo html::img($file,
                                        ['style' => 'width: 80px; height: 80px']
                                    );
                                break;
                            case "video":
                                    echo "<video controls width='250px' height=' 150px'  src=".$file."></video>";
                                break;
                            case "text":
                                    echo base64_decode($value->file);
                                break;

                            case "document":
                                echo "<a download href='".$file."'>Document fayl</a>";
                            break;

                            case "voice":
                                echo "<audio controls src=".$file."></audio>";;
                            break;
                        }
                    } else {
                        echo '';
                    }

					?>
				</td>
				<td><?php echo $value->type ?></td>
				<td><?php echo $value->user->second_name ?></td>
				<td><?php echo date("Y-m-d H:i", strtotime(date($value->created_date))) ?></td>
				<td>
					<center>
					    <a class="delete_file" href="/index.php/orders/delete-file?id=<?php echo $value->id ?>">
							<i class="fa fa-trash"></i>
						</a>	
					</center>
					
				</td>
			</tr>
		<?php $i++; endforeach ?>
	<?php else: ?>
		<tr>
			<td colspan="5">
				Ma`lumot yuklanmagan!
			</td>
		</tr>
	<?php endif ?>
</table>

<?php
$js = <<<JS

	$(document).on('click','.delete_file', function(){
		if(!confirm('Faylni buyurtmadan o`chirmoqchimisiz ?')){
			event.preventDefault();
		}
	})


JS;
$this->registerJs($js);
?>