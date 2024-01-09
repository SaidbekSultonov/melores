<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Users;

function bot($method, $data = []) {
    $url = 'https://api.telegram.org/bot1719174456:AAGL3fQYq5LiAhvyEuD915EYFhSXPmrkVMo/'.$method;
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
    $url = 'https://api.telegram.org/bot1719174456:AAGL3fQYq5LiAhvyEuD915EYFhSXPmrkVMo/'.$method;
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


/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('O`chirish', ['/index.php/tasks/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Vazifani o`chirmoqchimisiz?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'task_fine',
            'deadline_fine',
            [
                'format' => 'html',
                'label' => 'Holati',
                'value' => function ($data) {
                    if ($data->status == 2){
                        return '<span class="label label-warning">Bajarilmoqda</span>';
                    }
                    else if ($data->status == 3 or $data->status == 4){
                        return '<span class="label label-success">Bitdi</span>';
                    }
                    else {
                        return '<span class="label label-danger">Qabul qilinmagan</span>';
                    }
                },
            ],
            [
                'format' => 'html',
                'label' => 'Vazifa berilgan sana',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    return date('Y-m-d H:i', strtotime($data->created_date));
                },
            ],
            'dead_line'
        ],
    ]) ?>

    <table class="table table-bordered table-striped sort_table" style="margin-top: 10px;">
        <th>#</th>
        <th>Bajaruvchi</th>
        <th>Holati</th>
        <th>Vazifa qabul vaqti</th>
        <th>Vazifa bitgan vaqti</th>
        <th>File</th>
        <th>Jarima</th>
        <th>Deadline jarima</th>
        <th>O'chirish</th>

        <?php
        $sql = "SELECT 
	u.second_name AS user_name, 
	t.id as task_id, 
	tu.user_id as user_id, 
	tf.price as fine, 
    td.price as deadline_fine,
	ts.enter_date as enter_date, 
	ts.end_date as end_date, 
	tm.file_id as file_id, 
	tm.type as TYPE,
	ts.status as user_status 
	FROM tasks AS t 
LEFT JOIN task_user_materials AS tm ON t.id = tm.task_id 
INNER JOIN task_user AS tu ON t.id = tu.task_id
INNER JOIN users AS u ON tu.user_id = u.id
INNER JOIN task_status AS ts ON t.id = ts.task_id AND u.id = ts.user_id
LEFT JOIN task_fine AS tf ON tu.user_id = tf.user_id AND t.id = tf.task_id
LEFT JOIN task_fine_deadline AS td ON tu.user_id = td.user_id AND t.id = td.task_id
WHERE t.id = ".$model->id;

        $command = Yii::$app->db->createCommand($sql)->queryAll();

        if (isset($command) and !empty($command)){
            $i = 1;
            foreach ($command as $key => $value) {
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$value['user_name']."</td>";
                echo "<td>";
                if ($value['user_status'] == 3){
                    echo "<span class='label label-success'>Bajarildi</span>";
                }
                else if ($value['user_status'] == 0) {
                    echo "<span class='label label-danger'>Vazifa qabul qilinmagan</span>";
                }
                else if ($value['user_status'] == 1) {
                    echo "<span class='label label-warning'>Qabul qilingan</span>";
                }
                else if ($value['user_status'] == 2) {
                    echo "<span class='label label-warning'>Admin tomonidan tasdiqlanmagan</span>";
                }
                echo "</td>";
                if ($value['user_status'] == 1 or $value['user_status'] == 2 or $value['user_status'] == 3){
                    echo "<td>".date('Y-m-d H:i', strtotime($value['enter_date']))."</td>";
                } else {
                    echo "<td><span class='label label-danger'>Vazifa qabul qilinmagan</span></td>";
                }
                if ($value['user_status'] == 3 or $value['user_status'] == 2){
                    echo "<td>".date('Y-m-d H:i', strtotime($value['end_date']))."</td>";
                } else {
                    echo "<td><span class='label label-warning'>Vazifa tugatilmagan</span></td>";
                }
                echo "<td>";
                if (!is_null($value['file_id'])){
                    $imageFileId = bot('getFile', [
                        'file_id' => $value['file_id']
                    ]);

                    if(isset($imageFileId) and $value['type'] != "text") {
                        $file = 'https://api.telegram.org/file/bot1719174456:AAGL3fQYq5LiAhvyEuD915EYFhSXPmrkVMo/'.$imageFileId->result->file_path;
                    }

                    switch($value['type']){
                        case "photo":
                            echo html::img($file,
                                ['style' => 'width: 80px; height: 80px']
                            );
                            break;
                        case "video":
                            echo "<video controls width='250px' height=' 150px'  src=".$file."></video>";
                            break;
                        case "text":
                            echo ($value['file_id']);
                            break;

                        case "document":
                            echo "<a download href='".$file."'>Document fayl</a>";
                            break;

                        case "voice":
                            echo "<audio controls src=".$file."></audio>";;
                            break;
                    }
                }
                echo "</td>";
                if ($value['fine'] == NULL){
                    echo "<td><span class='label label-primary'>Jarima yo'q</span></td>";
                } else {
                    echo "<td>".$value['fine']."</td>";
                }
                if ($value['deadline_fine'] == NULL){
                    echo "<td><span class='label label-primary'>Jarima yo'q</span></td>";
                } else {
                    echo "<td>".$value['deadline_fine']."</td>";
                }
                echo "<td><a href='/index.php/tasks/taskdelete?id=".$value['task_id']."&user_id=".$value['user_id']."' data-confirm='O`chirishni istaysizmi?' data-method='post'><i class ='fa fa-trash'></i></a></td>";
                echo "</tr>";
                $i++;
            }
        } else {
            echo "<tr>";
            echo  "<td class='text-center' colspan='9'>Ma'lumot yo'q</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>