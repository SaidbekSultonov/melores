<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\TeamSchedule;
use app\models\Team;
use app\models\Orders;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grafik';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
         <?= Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif; ?>
<div class="team-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Grafikga qo`shish', ['/index.php/team/schedule_form'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>        
        </div>
    </div>

    <?php 
		$model = TeamSchedule::find()->all();
     ?>
    <form class="row">
    	<div id="salom" class="col-md-2">
		    <select name="branch" class="form-control select2" id="month" data-placeholder="Oyni tanlang" style="width: 100%">
		    	<option></option>
		        <option <?php if(date('m') == '01') echo"selected"; ?> value="<?= date('Y-01-01')?>">Yanvar</option>
		        <option <?php if(date('m') == '02') echo"selected"; ?> value="<?= date('Y-02-01')?>">Fevral</option>
		        <option <?php if(date('m') == '03') echo"selected"; ?> value="<?= date('Y-03-01')?>">Mart</option>
		        <option <?php if(date('m') == '04') echo"selected"; ?> value="<?= date('Y-04-01')?>">Aprel</option>
		        <option <?php if(date('m') == '05') echo"selected"; ?> value="<?= date('Y-05-01')?>">May</option>
		        <option <?php if(date('m') == '06') echo"selected"; ?> value="<?= date('Y-06-01')?>">Iyun</option>
		        <option <?php if(date('m') == '07') echo"selected"; ?> value="<?= date('Y-07-01')?>">Iyul</option>
		        <option <?php if(date('m') == '08') echo"selected"; ?> value="<?= date('Y-08-01')?>">Avgust</option>
		        <option <?php if(date('m') == '09') echo"selected"; ?> value="<?= date('Y-09-01')?>">Sentabr</option>
		        <option <?php if(date('m') == '10') echo"selected"; ?> value="<?= date('Y-10-01')?>">Oktabr</option>
		        <option <?php if(date('m') == '11') echo"selected"; ?> value="<?= date('Y-11-01')?>">Noyabr</option>
		        <option <?php if(date('m') == '12') echo"selected"; ?> value="<?= date('Y-12-01')?>">Dekabr</option>
			</select>
    	</div>
    </form>
   
    <table class="table table-bordered" id="month_table" style="margin-top: 15px">
	<tr>
	    <th>Sana</th>
	    <?php 
	    	$arr = [];
	    	$model = Team::find()->all();
	    	foreach ($model as $key => $value) {
				echo "<th>".base64_decode($value->title)."</th>";	
				$arr[] = $value->id;     		
	    	}
	     ?>
	</tr>
        <?php 
            if (isset($command) && !empty($command)) {
	            foreach ($command as $key => $value) {
	                $i = 0;
	                echo "<tr>";
	                echo "<td>".date('d-m-Y', strtotime($key))."</td>";
	                $time = date('d-m-Y', strtotime($key));
	                if (!empty($value)) {
	                    $yes = 0;
	                    foreach ($value as $teamKey => $teamValue) {
	                        if (isset($teamKey) && isset($arr[$i]) && $teamKey == $arr[$i]) {
	                            $yes++;
	                            $i++;
	                            echo "<td>";
	                            foreach ($teamValue as $secondKey => $secondValue) {
	                                echo "<span class='label label-primary'>".$secondValue." | ".$time."</span>"."</br>";  
	                            }
	                            echo "</td>";
	                        } 
	                        else {
	                            $i = 0;
                                for ($t = 0; $t <= count($arr); $t++) { 
                                    $yes++;
                                    if (isset($teamKey) && isset($arr[$i]) && $teamKey == $arr[$i]) {
                                        echo "<td>";
                                        foreach ($teamValue as $secondKey => $secondValue) {
                                             echo "<span class='label label-primary'>".$secondValue." | ".$time."</span>"."</br>";    
                                        }
                                        echo "</td>";
                                    } else {
                                        if ($yes < count($arr)) {
                                                echo "<td></td>";
                                        }
                                    }
                                    $i++;
                                }
	                        }
	                    }
	                    if ($yes < count($arr)) {
	                        for ($y=$yes; $y < count($arr); $y++) { 
	                            echo "<td></td>";
	                        }
	                    }
	                } else {
	                    for ($t = 0; $t < count($arr); $t++) { 
	                        echo "<td></td>";
	                    }
	                }
	                echo "</tr>";
	            }
	        } else {
	            foreach ($command as $key => $value) {
	                echo "<tr>";
	                echo "<td>".date('d-m-Y', strtotime($key))."</td>";    
	                echo "</tr>";
	            }
	        }

         ?>	
	</table>
</div>

<?php $this->registerJs(
'
	$(function(){
        $(document).on("change", "#month", function(){
			var val =  $(this).val();
			$.ajax({
	            url: "month",
	            data: {
	                date: val,
	            },
	            dataType: "json",
	            type: "get",
	            success: function(response) {
					if (response.status == "success") {
		                  $("#month_table").empty()
		                  $("#month_table").html(response.content)
		            }            },
	            error: function(error){
	            	console.log(error)
	            }
	        })
		})
    })
   

', yii\web\View::POS_READY); ?>