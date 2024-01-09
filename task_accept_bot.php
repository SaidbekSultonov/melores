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

    if (date('H:i') > "09:00" and date('H:i') < '20:00'){
        $selectTask = "SELECT * FROM task_status WHERE status = 0";
        $resultTask = pg_query($conn, $selectTask);

        if (pg_num_rows($resultTask) > 0){
            $sendTask = "SELECT ts.id, ts.step_cron, ts.task_id, ts.user_id, tm.file_id, tm.caption, tm.type, t.task_fine, t.deadline_fine, t.dead_line, u.chat_id FROM task_status AS ts
INNER JOIN tasks AS t ON t.id = ts.task_id
INNER JOIN task_materials AS tm ON ts.task_id = tm.task_id
INNER JOIN users AS u ON ts.user_id = u.id  WHERE ts.status = 0";
            $resultSendTask = pg_query($conn, $sendTask);
            if (pg_num_rows($resultSendTask) > 0){
                while($row = pg_fetch_assoc($resultSendTask)) {
                    $task_fine = $row["task_fine"];
                    $deadline_fine = $row["deadline_fine"];
                    $deadLine = $row["dead_line"];
                    $caption = base64_decode($row["caption"]);
                    $file_id = $row["file_id"];
                    $type = $row["type"];
                    $task_id = $row["task_id"];
                    $user_id = $row["user_id"];
                    $user_chat_id = $row["chat_id"];
                    if($step_cron = $row["step_cron"] < 6){
                        $fineAndDeadLine = "*💰 Jarima:* ".$deadline_fine.PHP_EOL."*💲 Deadline jarimasi:* ".$task_fine.PHP_EOL."*🕔 Vazifa tugatilish vaqti:* ".$deadLine;

                        $confirmMaterial = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Qabul qilish ✅",'callback_data' => 'confirmTask_'.$task_id],
                                    ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                                ]
                            ]
                        ]);

                        $step_cron = $row["step_cron"] + 1;
                        $cronUpdate = "UPDATE task_status SET step_cron = ".$step_cron." WHERE task_id = ".$task_id." and user_id = ".$user_id;
                        $resultCron = pg_query($conn, $cronUpdate);

                        $stepUpdate = "UPDATE task_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                        $resultStep = pg_query($conn, $stepUpdate);
                        if ($resultStep == true and $resultCron == true){
                            switch ($type) {
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $user_chat_id,
                                        'photo' => $file_id,
                                        'caption' => "🔔 *Ogohlantirish* 🔔".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $confirmMaterial
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $user_chat_id,
                                        'video' => $file_id,
                                        'caption' => "🔔 *Ogohlantirish* 🔔".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $confirmMaterial
                                    ]);
                                    break;
                                case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $user_chat_id,
                                        'text' => "🔔 *Ogohlantirish* 🔔".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.$fineAndDeadLine,
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $confirmMaterial
                                    ]);
                                    break;
                                case 'voice':
                                    bot('sendVoice', [
                                        'chat_id' => $user_chat_id,
                                        'voice' => $file_id,
                                        'caption' => "🔔 *Ogohlantirish* 🔔".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $confirmMaterial
                                    ]);
                                    break;
                                case 'audio':
                                    bot('sendAudio', [
                                        'chat_id' => $user_chat_id,
                                        'audio' => $file_id,
                                        'caption' => "🔔 *Ogohlantirish* 🔔".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $confirmMaterial
                                    ]);
                                    break;
                                case 'video_note':
                                    bot('sendVideoNote', [
                                        'chat_id' => $user_chat_id,
                                        'video_note' => $file_id,
                                    ]);
                                    bot('sendMessage', [
                                        'chat_id' => $user_chat_id,
                                        'text' => "🔔 *Ogohlantirish* 🔔".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $confirmMaterial
                                    ]);
                                    break;
                            }
                        }
                    }
                    else if ($step_cron = $row["step_cron"] == 6){
                        $insertFine = "INSERT INTO task_fine_deadline (price,task_id,user_id) VALUES (".$deadline_fine.",".$task_id.",".$user_id.")";
                        $resultFine = pg_query($conn, $insertFine);

                        $selectChatId = "SELECT * FROM users WHERE id = ".$user_id;
                        $resultChatId = pg_query($conn, $selectChatId);
                        if (pg_num_rows($resultChatId) > 0){
                            $row = pg_fetch_assoc($resultChatId);
                            $user_chat_id = $row['chat_id'];
                        }

                        $addSalary = "INSERT INTO salary_amount (chat_id,category_id,price,comment,date,type,status,user_id,task_id) VALUES (".$user_chat_id.",4,".$deadline_fine.",'".base64_encode('Vazifa qabul qilinmagani uchun')."','".date('Y-m-d')."',3,1,".$user_id.",".$task_id.")";
                        $resultSalary = pg_query($conn, $addSalary);

                        $selectCategoryBalance = "SELECT * FROM salary_category WHERE id = 4";
                        $resultCategoryBalance= pg_query($conn, $selectCategoryBalance);
                        if (pg_num_rows($resultCategoryBalance) > 0){
                            $row = pg_fetch_assoc($resultCategoryBalance);
                            $balance = $row['balance'];
                            $plus_balance = $balance + $deadline_fine;
                            $updateCategoryBalance = "UPDATE salary_category SET balance = ".$plus_balance." WHERE id = 4";
                            $resultCategoryBalance = pg_query($conn, $updateCategoryBalance);
                        }

                        $selectUserBalance = "SELECT * FROM salary_user_balance WHERE user_id = ".$user_chat_id;
                        $resultUserBalance = pg_query($conn, $selectUserBalance);
                        if (pg_num_rows($resultUserBalance) > 0){
                            $row = pg_fetch_assoc($resultUserBalance);
                            $user_balance = $row['balance'];

                            $minus_balance = $user_balance - $deadline_fine;
                            $updateUserBalance = "UPDATE salary_user_balace SET balance = ".$minus_balance." WHERE user_id = ".$user_chat_id;
                            $resultUserBalance = pg_query($conn, $updateUserBalance);
                        } else {
                            $addUserBalance = "INSERT INTO salary_user_balance (user_id,balance) VALUES (".$user_chat_id.",-".$deadline_fine.")";
                            $resultBalance = pg_query($conn, $addUserBalance);
                        }

                        $addEvent = "INSERT INTO salary_event_balance (user_id,receiver,quantity,category_id,date,type) VALUES (".$user_id.",1270367,".$deadline_fine.",4,'".date('Y-m-d H:i:s')."',2)";
                        $resultEvent = pg_query($conn, $addEvent);

                        $cronUpdate = "UPDATE task_status SET step_cron = 7 WHERE task_id = ".$task_id." and user_id = ".$user_id;
                        $resultCron = pg_query($conn, $cronUpdate);

                        $stepUpdate = "UPDATE task_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                        $resultStep = pg_query($conn, $stepUpdate);

                        $confirmMaterial = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                                ]
                            ]
                        ]);
                        if ($resultFine == true and $resultCron == true and $resultStep == true){
                            bot('sendMessage', [
                                'chat_id' => $user_chat_id,
                                'text' => "Siz vazifani qabul qilmadizngiz sizga *".$deadline_fine."* miqdorida jarima yozildi ❗️",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                        }
                    }
                }
            }
        }
    }

?>