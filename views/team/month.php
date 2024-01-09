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
 ?>
 <table class="table table-bordered" id="month_table">
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
        if (isset($team) && !empty($team)) {
            foreach ($team as $key => $value) {
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
            foreach ($team as $key => $value) {
                echo "<tr>";
                echo "<td>".date('d-m-Y', strtotime($key))."</td>";    
                echo "</tr>";
            }
        }
    ?> 
</table>
<script>
    $(document).on("change", "#month", function(){
        var val =  $(this).val();
        $.ajax({
            url: 'month',
            data: {
                date: val,
            },
            dataType: 'json',
            type: 'get',
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
</script>