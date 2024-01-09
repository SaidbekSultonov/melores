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

    $selectTask = "SELECT * FROM tasks WHERE status = 0";
    $resultTask = pg_query($conn, $selectTask);

    if (pg_num_rows($resultTask) > 0){
        $sendTask = "SELECT t.id, t.task_fine, t.dead_line, tm.file_id, tm.caption, tm.type, ts.user_id  FROM tasks as t inner join task_materials as tm on t.id = tm.task_id INNER JOIN task_user AS ts ON t.id = ts.task_id where t.status = 0";
        $resultSendTask = pg_query($conn, $sendTask);
        if (pg_num_rows($resultSendTask) > 0){
            while($row = pg_fetch_assoc($resultSendTask)) {
                $task_fine = $row["task_fine"];
                $deadLine = $row["dead_line"];
                $caption = $row["caption"];
                $file_id = $row["file_id"];
                $type = $row["type"];
                $task_id = $row["id"];
                $user_chat_id = $row["chat_id"];
                $fineAndDeadLine = "*💰 Jarima:* ".$task_fine.PHP_EOL."*🕔 Vazifa tugatilish vaqti:* ".$deadLine;

                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Qabul qilish ✅",'callback_data' => 'confirmTask_'.$task_id],
                            ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                        ]
                    ]
                ]);

                $taskStatusUpdate = "UPDATE task_status SET status = 0 WHERE task_id = ".$task_id;
                $resultTaskStatus = pg_query($conn, $taskStatusUpdate);

                $statusUpdate = "UPDATE tasks SET status = 1 WHERE id = ".$task_id;
                $resultStatus = pg_query($conn, $statusUpdate);

                $stepUpdate = "UPDATE task_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                $resultStep = pg_query($conn, $stepUpdate);
                if ($resultStep == true and $resultStatus == true){
                    switch ($type) {
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $user_chat_id,
                                'photo' => $file_id,
                                'caption' => "♻️ *Ogohlantirish* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            bot('sendPhoto', [
                                'chat_id' => 1270367,
                                'photo' => $file_id,
                                'caption' => "♻️ *Ogohlantirish* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $user_chat_id,
                                'video' => $file_id,
                                'caption' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            bot('sendVideo', [
                                'chat_id' => 1270367,
                                'video' => $file_id,
                                'caption' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $user_chat_id,
                                'text' => "📃 *Tavsif:* ".$caption.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            bot('sendMessage', [
                                'chat_id' => 1270367,
                                'text' => "📃 *Tavsif:* ".$caption.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'voice':
                            bot('sendVoice', [
                                'chat_id' => $user_chat_id,
                                'voice' => $file_id,
                                'caption' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            bot('sendVoice', [
                                'chat_id' => 1270367,
                                'voice' => $file_id,
                                'caption' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'audio':
                            bot('sendAudio', [
                                'chat_id' => $user_chat_id,
                                'audio' => $file_id,
                                'caption' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            bot('sendAudio', [
                                'chat_id' => 1270367,
                                'audio' => $file_id,
                                'caption' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'video_note':
                            bot('sendVideoNote', [
                                'chat_id' => $user_chat_id,
                                'video_note' => $file_id,
                            ]);
                            bot('sendVideoNote', [
                                'chat_id' => 1270367,
                                'video_note' => $file_id,
                            ]);
                            bot('sendMessage', [
                                'chat_id' => $user_chat_id,
                                'text' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            bot('sendMessage', [
                                'chat_id' => 1270367,
                                'text' => "📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                    }
                }
            }
        }
    }

    $selectTaskConfrim = "SELECT ts.task_id, ts.user_id, t.task_fine, tm.file_id, tm.caption, tm.type, t.dead_line, u.chat_id FROM task_status AS ts
INNER JOIN task_materials AS tm ON ts.task_id = tm.task_id
INNER JOIN users AS u ON ts.user_id = u.id
INNER JOIN tasks AS t ON t.id = ts.task_id
WHERE ts.status = 1";
    $resultTaskConfirm = pg_query($conn, $selectTaskConfrim);

    if (pg_num_rows($resultTaskConfirm) > 0){
        while($row = pg_fetch_assoc($resultTaskConfirm)){
            $row = pg_fetch_assoc($resultTaskConfirm);
            $task_fine = $row["task_fine"];
            $deadLine = $row["dead_line"];
            $caption = base64_decode($row["caption"]);
            $file_id = $row["file_id"];
            $type = $row["type"];
            $task_id = $row["task_id"];
            $user_id = $row["user_id"];
            $user_chat_id = $row["chat_id"];

            $fineAndDeadLine = "*💰 Jarima:* ".$task_fine.PHP_EOL."*🕔 Vazifa tugatilish vaqti:* ".$deadLine;

            $confirmMaterial = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Ortga ↩️",'callback_data' => 'back'],
                        ['text' => "Bitdi ☑️",'callback_data' => 'done_'.$task_id],
                    ],
                ]
            ]);

            $stepUpdate = "UPDATE task_step SET step_2 = 51 WHERE chat_id = ".$user_chat_id;
            $resultStep = pg_query($conn, $stepUpdate);
            if ($resultStep == true){
                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $user_chat_id,
                            'photo' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        bot('sendPhoto', [
                            'chat_id' => 1270367,
                            'photo' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $user_chat_id,
                            'video' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        bot('sendVideo', [
                            'chat_id' => 1270367,
                            'video' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $user_chat_id,
                            'text' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        bot('sendMessage', [
                            'chat_id' => 1270367,
                            'text' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $user_chat_id,
                            'voice' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        bot('sendVoice', [
                            'chat_id' => 1270367,
                            'voice' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $user_chat_id,
                            'audio' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        bot('sendAudio', [
                            'chat_id' => 1270367,
                            'audio' => $file_id,
                            'caption' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $user_chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendVideoNote', [
                            'chat_id' => 1270367,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $user_chat_id,
                            'text' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        bot('sendMessage', [
                            'chat_id' => 1270367,
                            'text' => "♻️ *Eslatma* ♻️".PHP_EOL.PHP_EOL."📃 *Tavsif:* ".$caption.PHP_EOL.PHP_EOL.$fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
            }
        }
    }

?>