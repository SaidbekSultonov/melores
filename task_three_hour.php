<?php
$conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
if ($conn) {
    echo "Success";
}

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

$selectTask = "SELECT * FROM task_status WHERE status = 1 and step_deadline = 13";
$resultTask = pg_query($conn, $selectTask);
if (pg_num_rows($resultTask) > 0){
    while($row = pg_fetch_assoc($resultTask)) {
        $dead_line_time = $row['task_deadline_date'];
        $user_id = $row['user_id'];
        $task_id = $row['task_id'];
        $deadLineTime = date('Y-m-d H:i', strtotime($dead_line_time));
        if (date('Y-m-d H:i') == $deadLineTime){
            $selectChatId = "SELECT * FROM task_status AS ts
INNER JOIN users AS u ON  ts.user_id = u.id
INNER JOIN tasks AS t ON t.id = ts.task_id
WHERE ts.task_id = ".$task_id." AND ts.user_id = ".$user_id;
            $resultChatId = pg_query($conn, $selectChatId);
            if (pg_num_rows($resultChatId) > 0){
                $row_user = pg_fetch_assoc($resultChatId);
                $user_chat_id = $row_user['chat_id'];
                $task_fine = $row_user['task_fine'];
                $task_deadline_date = $row_user['task_deadline_date'];
                $dead_line_time = date("Y-m-d H:i:s", strtotime('+1 hours', strtotime($task_deadline_date)));
                $updateDeadLineTime = "UPDATE task_status SET task_deadline_date = '".$dead_line_time."' WHERE task_id = ".$task_id." and user_id = ".$user_id;
                $resultDeadLineTime = pg_query($conn, $updateDeadLineTime);

                if ($resultDeadLineTime == true){
                    $select_fine = "SELECT * FROM task_fine_deadline WHERE user_id = ".$user_id." and task_id = ".$task_id;
                    $resultFine = pg_query($conn, $select_fine);

                    $selectChatId = "SELECT * FROM users WHERE id = ".$user_id;
                    $resultChatId = pg_query($conn, $selectChatId);
                    if (pg_num_rows($resultChatId) > 0){
                        $row = pg_fetch_assoc($resultChatId);
                        $user_chat_id = $row['chat_id'];
                    }
                    $addSalary = "INSERT INTO salary_amount (chat_id,category_id,price,comment,date,type,status,user_id) VALUES (".$user_chat_id.",3,".$task_fine.",'".base64_encode('Vazifa vaqtida bajarilmaganligi uchun')."','".date('Y-m-d')."',3,1,".$user_id.")";
                    $resultSalary = pg_query($conn, $addSalary);

                    $addEvent = "INSERT INTO salary_event_balance (user_id,receiver,quantity,category_id,date,type) VALUES (".$user_id.",1270367,".$task_fine.",3,'".date('Y-m-d H:i:s')."',2)";
                    $resultEvent = pg_query($conn, $addEvent);

                    if (!pg_num_rows($resultFine) > 0) {
                        $addTaskFine = "INSERT INTO task_fine_deadline (price,task_id,user_id) VALUES (".$task_fine.",".$task_id.",".$user_id.")";
                        $resultAddFine = pg_query($conn, $addTaskFine);

                        bot('sendMessage', [
                            'chat_id' => $user_chat_id,
                            'text' => "Siz vazifani vaqtida bajarmaganingiz uchun *".$task_fine."* miqdorida jarima yozildi ❗️".PHP_EOL."*Jami: *".$task_fine,
                            'parse_mode' => 'markdown',
                        ]);
                    } else {
                        $row_fine = pg_fetch_assoc($resultFine);
                        $price = $row_fine['price'];
                        $all_price = $price + $task_fine;
                        $updateTaskFine = "UPDATE task_fine_deadline SET price = ".$all_price." WHERE task_id = ".$task_id." and user_id = ".$user_id;
                        $resultTaskFine = pg_query($conn, $updateTaskFine);

                        bot('sendMessage', [
                            'chat_id' => $user_chat_id,
                            'text' => "Siz vazifani vaqtida bajarmaganingiz uchun *".$task_fine."* miqdorida jarima yozildi ❗️".PHP_EOL."*Jami: *".$all_price,
                            'parse_mode' => 'markdown',
                        ]);
                    }
                }
            }
        }
    }
}