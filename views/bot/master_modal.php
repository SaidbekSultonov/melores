<label for="">Foydalanuvchilar</label>
<select class="form-control select2" id="add_master" data-placeholder="Foydalanuvchi tanlang" style="width: 100%">
	<option value=""></option>
    <?php foreach ($model as $key => $value): ?>
        <option value="<?php echo $value['id']; ?>">
            <?php echo $value['second_name']; ?>      
        </option>
    <?php endforeach ?>
</select>
<br>
<br>
<p style="display: none;">
	<label style="display: flex;align-items: center;">
		<input type="checkbox" class="active" style="width: 20px; height: 20px;margin: 0; margin-right: 5px"> 
			Bo'limlarga kirish huquqi
	</label>
</p>
<br>


<div class="hidden2">
	
</div>
<?php $this->registerJs(
'
    $(".select2").select2();

    $(document).on("change",".active",function(){
    	if(this.checked) {
	    	$.ajax({
	            url: "/index.php/bot/sections",
	            dataType: "json",
	            type: "GET",
	            success: function (response) {
	                if (response.status == "success") {
	                    $(".hidden2").html(response.content)
	                }
	            }
	        });
	    }
	    else{
	    	$(".hidden2").html("")
	    }
    })


    $(document).on("click",".save_master_bot",function(){
    	let id = $("#add_master").val()
    	let sections = $("#sections").val()

    	if(id != "") {

	    	$.ajax({
	            url: "/index.php/bot/save-master-user",
	            dataType: "json",
	            type: "GET",
	            data: {id: id, sections: sections},
	            success: function (response) {
	                if (response.status == "success") {
	                    window.location.reload()
	                }
	                if(response.status == "failure_user"){
	                	alert("Bu foydalanuvchi botga qo`shilgan")
	                }
	            }
	        });
	    }
	    else{
	    	alert("Foydalanuvchi tanlang!")
	    }
    })

', yii\web\View::POS_READY); ?>