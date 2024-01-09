<?php  
	$items = [
	'1' => 'Admin',
    '2' => 'Nazoratchi',
    '3' => 'OTK',
    '4' => 'Sotuvchi',
    '5' => 'Bo`lim boshlig`i',
    '6' => 'Kroychi',
    '7' => 'Bo`lim ishchisi'
];

?>

<label for="">Foydalanuvchilar</label>
<select class="form-control select2" id="add_otk" data-placeholder="Foydalanuvchi tanlang" style="width: 100%">
	<option value=""></option>
    <?php foreach ($model as $key => $value): ?>
        <option value="<?php echo $value['id']; ?>">
            <?php echo $value['second_name']; ?>      
        </option>
    <?php endforeach ?>
</select>
<br>
<br>
<label for="">Foydalanuvchi ro'li</label>
<select class="form-control select2" id="role" data-placeholder="Foydalanuvchi ro'lini tanlang" style="width: 100%">
	<option value=""></option>
    <?php foreach ($items as $key => $value): ?>
        <option value="<?php echo $key; ?>">
            <?php echo $value; ?>      
        </option>
    <?php endforeach ?>
</select>
<br>
<br>

<label for="">Bo`limlar</label>
<select class="form-control select2" id="section" data-placeholder="Bo`lim tanlang">
	<?php if (!empty($sections)): ?>
		<option value=""></option>
        <?php foreach ($sections as $key => $value): ?>
            <option value="<?php echo $value->id; ?>">
                <?php echo $value->title; ?>      
            </option>
        <?php endforeach ?>
	<?php endif ?>

</select>
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


    $(document).on("click",".save_otk_bot",function(){
    	let id = $("#add_otk").val()
    	let role = $("#role").val()
    	let section = $("#section").val()

    	if(id != "" && role != "" && section != "") {

	    	$.ajax({
	            url: "/index.php/bot/save-otk-user",
	            dataType: "json",
	            type: "GET",
	            data: {id: id, section: section,role: role},
	            success: function (response) {
	                if (response.status == "success") {
	                    window.location.reload()
	                }
	                if(response.status == "failure"){
	                	alert("Foydalanuvchi tanlang!")
	                }
	                if(response.status == "failure_role"){
	                	alert("Foydalanuvchi ro`lini tanlang!")
	                }
	                if(response.status == "failure_section"){
	                	alert("Foydalanuvchi ro`lini tanlang!")
	                }
	                if(response.status == "failure_exist"){
	                	alert("Foydalanuvchi tanlangan bo`limga avval ham shu ro`l bilan kiritilgan! Iltimos boshqa ro`l yoki bo`lim tanlang!")
	                }
	            }
	        });
	    }
	    else{
	    	alert("Barcha maydonlarni to`ldiring!")
	    }
    })

', yii\web\View::POS_READY); ?>