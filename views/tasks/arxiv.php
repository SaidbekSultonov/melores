<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>

    <table class="table table-bordered table-striped sort_table" style="margin-top: 10px;">
        <th>#</th>
        <th>Vazifa tavsif</th>
        <th>Bajaruvchilar</th>
        <th>Vaifa berilgan sana</th>
        <th>Vazifa bitgan vaqti</th>
        <th>Holati</th>
        <th>O'chirish</th>

        <?php
        if (isset($command) and !empty($command)){
            $i = 1;
            foreach ($command as $key => $value) {
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".base64_decode($value['caption'])."</td>";
                $selectUsers = \app\models\TaskUser::find()->where(['task_id' => $value['task_id']])->all();
                if (isset($selectUsers) and !empty($selectUsers)){
                    $span = '';
                    foreach ($selectUsers as $key2 => $value2){
                        $user_id = $value2['user_id'];
                        $selectSecondName = \app\models\Users::find()->where(['id' => $user_id])->one();
                        if (isset($selectSecondName) and !empty($selectSecondName)){
                            $span = $span." <span class='label label-primary'>".$selectSecondName->second_name."</span>";
                        }
                    }
                    echo "<td>".$span."</td>";
                }
                echo "<td>".date('Y-m-d H:i', strtotime($value['end_date']))."</td>";
                echo "<td>".date('Y-m-d H:i', strtotime($value['create_date']))."</td>";
                echo "<td>";
                if ($value['status'] == 4){
                    echo "<span class='label label-success'>Bajarildi</span>";
                }
                else if ($value['status'] == 0) {
                    echo "<span class='label label-danger'>Vazifa qabul qilinmagan</span>";
                }
                else if ($value['status'] == 1 or $value['user_status'] == 2) {
                    echo "<span class='label label-warning'>Qabul qilingan</span>";
                }
                echo "</td>";
                echo "<td><a href='/index.php/tasks/statusdelete?id=".$value['task_id']."' data-confirm='Shu savol kategoriyani ochirish istaysizmi ?' data-method='post'><i class ='fa fa-trash'></i></a></td>";
                echo "</tr>";
                $i++;
            }
        } else {
            echo "<tr>";
            echo  "<td class='text-center' colspan='7'>Ma'lumot yo'q</td>";
            echo "</tr>";
        }
        ?>
    </table>