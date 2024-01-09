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


//$created_date = '21-05-2021 18:29:00';
//$dead_line = '22-05-2021 10:00:00';
//
//$c_day = date('d', strtotime($created_date));
//$d_day = date('d', strtotime($dead_line));
//
//if ($c_day == $d_day){
//    $minus_date = abs(date('H', strtotime($created_date)) - date('H', strtotime($dead_line)));
//    if (date('i', strtotime($created_date)) < 30){
//        if ($minus_date == 1){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-30 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 2){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-90 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 3){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-150 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 4){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-210 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 5){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-270 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 6){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-330 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        } else {
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-360 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//    } else {
//        if ($minus_date == 1){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('+0 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 2){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-60 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 3){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-120 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 4){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-180 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 5){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-240 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//        else if ($minus_date == 6){
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-300 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        } else {
//            $time = new DateTime($dead_line);
//            $minus_6 = $time->modify('-360 minutes')->format('Y-m-d H:i:s');
//            echo $minus_6;
//        }
//    }
//}
//else if ($c_day < $d_day){
//    $yesterday_minus = abs('20:00' - date('H:i', strtotime($created_date)));
//    $yesterday_minus_2 = abs('09:00' - date('H:i', strtotime($dead_line)));
//    $plus_hours = $yesterday_minus + $yesterday_minus_2;
//    echo "<br>".$plus_hours."<br>";
//    if ($plus_hours < 6){
//        if (date('i', strtotime($created_date)) < 30){
//            if ($plus_hours == 1){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-30 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 2){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-90 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 3){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-150 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 4){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-210 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 5){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-270 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":30:s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//        } else {
//            if ($plus_hours == 1){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('+0 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 2){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-60 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 3){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-120 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 4){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-180 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//            else if ($plus_hours == 5){
//                $time = new DateTime($dead_line);
//                $minus_6 = $time->modify('-240 minutes')->format('H:i');
//                if ($minus_6 < '09:00'){
//                    $minus_dead = '09:00' - $minus_6;
//                    $all = '20:00' - $minus_dead;
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                }
//                else if ($minus_6 > '20:00'){
//                    $minus_dead = $minus_6 - '20:00';
//                    $all = $minus_dead - '09:00';
//                    $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//                    $time = new DateTime($dead_line_str);
//                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//                    echo "day ------- ".$minus_day."<br>";
//                    echo "<br>"."all - ".$all;
//                } else {
//                    $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//                    echo $dead_line_str;
//                }
//            }
//        }
//    } else {
//        $time = new DateTime($dead_line);
//        $minus_6 = $time->modify('-6 hour')->format('H:i');
//
//        if ($minus_6 < '09:00'){
//            $minus_dead = '09:00' - $minus_6;
//            $all = '20:00' - $minus_dead;
//            $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//            $time = new DateTime($dead_line_str);
//            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//            echo "day ------- ".$minus_day."<br>";
//            echo "<br>"."all - ".$all;
//        }
//        else if ($minus_6 > '20:00'){
//            $minus_dead = $minus_6 - '20:00';
//            $all = $minus_dead - '09:00';
//            $dead_line_str = date('Y-m-d '.$all.":s", strtotime($dead_line));
//            $time = new DateTime($dead_line_str);
//            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
//            echo "day ------- ".$minus_day."<br>";
//            echo "<br>"."all - ".$all;
//        } else {
//            $dead_line_str = date('Y-m-d '.$minus_6.":s", strtotime($dead_line));
//            echo $dead_line_str;
//        }
//    }
//}
    if (date('H:i') > "09:00" and date('H:i') < '20:00'){
        $selectTask = "SELECT * FROM task_status WHERE status = 1";
        $resultTask = pg_query($conn, $selectTask);
        if (pg_num_rows($resultTask) > 0){
            while($row = pg_fetch_assoc($resultTask)) {
                $end_date = $row['task_end_date'];
                $id = $row['id'];
                $step_deadline = $row['step_deadline'];
                if (date('Y-m-d H:i') == date('Y-m-d H:i', strtotime($end_date))){
                    if ($step_deadline >= 0 and $step_deadline < 12){
                        $sendTask = "SELECT ts.id, ts.step_deadline, ts.task_end_date, ts.step_cron, t.deadline_fine, ts.task_id, ts.user_id, tm.file_id, tm.caption, tm.type, t.task_fine, t.dead_line, u.chat_id
                                    FROM task_status AS ts
                                    INNER JOIN tasks AS t ON t.id = ts.task_id
                                    INNER JOIN task_materials AS tm ON ts.task_id = tm.task_id
                                    INNER JOIN users AS u ON ts.user_id = u.id
                                    WHERE ts.status = 1 and ts.id = ".$id;
                        $resultSendTask = pg_query($conn, $sendTask);

                        if (pg_num_rows($resultSendTask) > 0){
                            $row = pg_fetch_assoc($resultSendTask);
                            $deadLine = $row["dead_line"];
                            $deadline_fine = $row["deadline_fine"];
                            $task_fine = $row["task_fine"];
                            $caption = base64_decode($row["caption"]);
                            $file_id = $row["file_id"];
                            $type = $row["type"];
                            $task_id = $row["task_id"];
                            $user_id = $row["user_id"];
                            $user_chat_id = $row["chat_id"];
                            $step_deadline = $row["step_deadline"] + 1;
                            $task_end_date = $row['task_end_date'];

                            $time = new DateTime($task_end_date);
                            $plus_time = $time->modify('+30 minutes')->format('Y-m-d H:i:s');
                            if (date('H:i', strtotime($plus_time)) >= '09:00' and date('H:i', strtotime($plus_time)) <= '20:00'){
                                $updateTaskEndDate = "UPDATE task_status SET task_end_date = '".$plus_time."' WHERE task_id = ".$task_id." AND user_id = ".$user_id;
                                $resultTaskEndDate = pg_query($conn, $updateTaskEndDate);
                            } else {
                                if (date('H:i', strtotime($plus_time)) > '20:00'){
                                    $time = new DateTime($plus_time);
                                    $plus_day = $time->modify('+1 day')->format('Y-m-d');
                                    $updateTaskEndDate = "UPDATE task_status SET task_end_date = '".$plus_day." 09:00:00' WHERE task_id = ".$task_id." AND user_id = ".$user_id;
                                    $resultTaskEndDate = pg_query($conn, $updateTaskEndDate);
                                }
                            }

                            $cronUpdate = "UPDATE task_status SET step_deadline = ".$step_deadline." WHERE task_id = ".$task_id." and user_id = ".$user_id;
                            $resultCron = pg_query($conn, $cronUpdate);

                            $fineAndDeadLine = "*💰 Jarima:* ".$deadline_fine.PHP_EOL."*💲 Deadline jarimasi:* ".$task_fine.PHP_EOL."*🕔 Vazifa tugatilish vaqti:* ".$deadLine;

                            $confirmMaterial = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Ortga ↩️",'callback_data' => 'back'],
                                        ['text' => "Bitdi ✔️",'callback_data' => 'done_'.$task_id],
                                    ],
                                ]
                            ]);

                            $stepUpdate = "UPDATE task_step SET step_2 = 51 WHERE chat_id = ".$user_chat_id;
                            $resultStep = pg_query($conn, $stepUpdate);
                            if ($resultStep == true and $resultCron == true){
                                switch ($type) {
                                    case 'photo':
                                        bot('sendPhoto', [
                                            'chat_id' => $user_chat_id,
                                            'photo' => $file_id,
                                            'caption' => "🛑 *Ogohlantirish* 🛑".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                            'parse_mode' => 'markdown',
                                            'reply_markup' => $confirmMaterial
                                        ]);
                                        break;
                                    case 'video':
                                        bot('sendVideo', [
                                            'chat_id' => $user_chat_id,
                                            'video' => $file_id,
                                            'caption' => "🛑 *Ogohlantirish* 🛑".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                            'parse_mode' => 'markdown',
                                            'reply_markup' => $confirmMaterial
                                        ]);
                                        break;
                                    case 'text':
                                        bot('sendMessage', [
                                            'chat_id' => $user_chat_id,
                                            'text' => "🛑 *Ogohlantirish* 🛑".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.$fineAndDeadLine,
                                            'parse_mode' => 'markdown',
                                            'reply_markup' => $confirmMaterial
                                        ]);
                                        break;
                                    case 'voice':
                                        bot('sendVoice', [
                                            'chat_id' => $user_chat_id,
                                            'voice' => $file_id,
                                            'caption' => "🛑 *Ogohlantirish* 🛑".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                            'parse_mode' => 'markdown',
                                            'reply_markup' => $confirmMaterial
                                        ]);
                                        break;
                                    case 'audio':
                                        bot('sendAudio', [
                                            'chat_id' => $user_chat_id,
                                            'audio' => $file_id,
                                            'caption' => "🛑 *Ogohlantirish* 🛑".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
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
                                            'text' => "🛑 *Ogohlantirish* 🛑".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                            'parse_mode' => 'markdown',
                                            'reply_markup' => $confirmMaterial
                                        ]);
                                        break;
                                }
                            }
                        }
                    }
                    else if($step_deadline == 12){
                        $sendTask = "SELECT ts.id, ts.task_end_date, t.deadline_fine, ts.task_id, ts.user_id, tm.type, t.task_fine, t.dead_line, u.chat_id
                                    FROM task_status AS ts
                                    INNER JOIN tasks AS t ON t.id = ts.task_id
                                    INNER JOIN task_materials AS tm ON ts.task_id = tm.task_id
                                    INNER JOIN users AS u ON ts.user_id = u.id
                                    WHERE ts.status = 1 and ts.id = ".$id;
                        $resultSendTask = pg_query($conn, $sendTask);

                        if (pg_num_rows($resultSendTask) > 0) {
                            $row = pg_fetch_assoc($resultSendTask);
                            $deadLine = $row["dead_line"];
                            $deadline_fine = $row["deadline_fine"];
                            $task_fine = $row["task_fine"];
                            $type = $row["type"];
                            $task_id = $row["task_id"];
                            $user_id = $row["user_id"];
                            $user_chat_id = $row["chat_id"];
                            $task_end_date = $row['task_end_date'];
                        }
                        $cronUpdate = "UPDATE task_status SET step_deadline = 13 WHERE task_id = ".$task_id." and user_id = ".$user_id;
                        $resultCron = pg_query($conn, $cronUpdate);

                        $dead_line_time = date("Y-m-d H:i:s", strtotime('+1 hours', strtotime($task_end_date)));
                        $deadLineTimeUpdate = "UPDATE task_status SET task_deadline_date = '".$dead_line_time."' WHERE task_id = ".$task_id." and user_id = ".$user_id;
                        $resultDeadLineTime = pg_query($conn, $deadLineTimeUpdate);

                        $insertFine = "INSERT INTO task_fine_deadline (price,task_id,user_id) VALUES (".$task_fine.",".$task_id.",".$user_id.")";
                        $resultFine = pg_query($conn, $insertFine);

                        $created_date = date('Y-m-d H:i:s');
                        $insertFine = "INSERT INTO task_fine (price,task_id,user_id,created_date) VALUES (".$task_fine.",".$task_id.",".$user_id.",'".$created_date."')";
                        $resultFine = pg_query($conn, $insertFine);

                        $selectChatId = "SELECT * FROM users WHERE id = ".$user_id;
                        $resultChatId = pg_query($conn, $selectChatId);
                        if (pg_num_rows($resultChatId) > 0){
                            $row = pg_fetch_assoc($resultChatId);
                            $user_chat_id = $row['chat_id'];
                        }

                        $addSalary = "INSERT INTO salary_amount (chat_id,category_id,price,comment,date,type,status,user_id,task_id) VALUES (".$user_chat_id.",3,".$task_fine.",'".base64_encode('Vazifa vaqtida bajarilmaganligi uchun')."','".date('Y-m-d')."',3,1,".$user_id.",".$task_id.")";
                        $resultSalary = pg_query($conn, $addSalary);

                        $selectCategoryBalance = "SELECT * FROM salary_category WHERE id = 3";
                        $resultCategoryBalance= pg_query($conn, $selectCategoryBalance);
                        if (pg_num_rows($resultCategoryBalance) > 0){
                            $row = pg_fetch_assoc($resultCategoryBalance);
                            $balance = $row['balance'];
                            $plus_balance = $balance + $task_fine;
                            $updateCategoryBalance = "UPDATE salary_category SET balance = ".$plus_balance." WHERE id = 3";
                            $resultCategoryBalance = pg_query($conn, $updateCategoryBalance);
                        }
                        
                        $selectUserBalance = "SELECT * FROM salary_user_balance WHERE user_id = ".$user_chat_id;
                        $resultUserBalance = pg_query($conn, $selectUserBalance);
                        if (pg_num_rows($resultUserBalance) > 0){
                            $row = pg_fetch_assoc($resultUserBalance);
                            $user_balance = $row['balance'];
                            
                            $minus_balance = $user_balance - $task_fine;
                            $updateUserBalance = "UPDATE salary_user_balace SET balance = ".$minus_balance." WHERE user_id = ".$user_chat_id;
                            $resultUserBalance = pg_query($conn, $updateUserBalance);
                        } else {
                            $addUserBalance = "INSERT INTO salary_user_balance (user_id,balance) VALUES (".$user_chat_id.",-".$task_fine.")";
                            $resultBalance = pg_query($conn, $addUserBalance);
                        }

                        $addEvent = "INSERT INTO salary_event_balance (user_id,receiver,quantity,category_id,date,type) VALUES (".$user_id.",1270367,".$task_fine.",3,'".date('Y-m-d H:i:s')."',2)";
                        $resultEvent = pg_query($conn, $addEvent);

                        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = ".$user_chat_id;
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
                                'text' => "Siz vazifani vaqtida bajarmadingiz sizga *".$task_fine."* miqdorida jarima yozildi ❗️",
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