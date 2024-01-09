<?php
$conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
if ($conn) {
    echo "Success";
}

const TOKEN = '1719174456:AAGL3fQYq5LiAhvyEuD915EYFhSXPmrkVMo';
const BASE_URL = 'https://api.telegram.org/bot' . TOKEN;
const ADMIN = 398187848;

function bot($method, $data = [])
{
    $url = BASE_URL . '/' . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec($ch);

    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}



$update = file_get_contents('php://input');
$update = json_decode($update);
$message = $update->message;
$text = $message->text;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$first_name = $message->chat->first_name;
$user_name = $message->chat->username;
$last_name = $message->chat->last_name;
if (isset($update->callback_query)) {
    $data = $update->callback_query->data;
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
}


function deleteMessageUser($conn, $chat_id)
{
    $selectMessageId = "SELECT * FROM task_message_id WHERE chat_id = " . $chat_id;
    $resultMessageId = pg_query($conn, $selectMessageId);
    if (pg_num_rows($resultMessageId) > 0) {
        while ($row = pg_fetch_assoc($resultMessageId)) {
            $user_message_id = $row['message_id'];

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $user_message_id,
            ]);
        }
        $deleteMessageId = "DELETE FROM task_message_id WHERE chat_id = " . $chat_id;
        pg_query($conn, $deleteMessageId);
    }
}

function deleteMessage($chat_id, $message_id)
{
    bot('deleteMessage', [
        'chat_id' => $chat_id,
        'message_id' => $message_id
    ]);
}

$language = json_encode([
    'keyboard' => [
        [
            ['text' => "🇺🇿 O'zbekcha"]
        ],
        [
            ['text' => "🇷🇺 Русский"]
        ],
    ],
    'resize_keyboard' => true
]);
$phone_number = json_encode([
    'keyboard' => [
        [
            ['text' => "Telefon raqam jo'natish 📲", 'request_contact' => true]
        ],
    ],
    'resize_keyboard' => true
]);

$back = json_encode([
    'inline_keyboard' => [
        [
            ['text' => "Ortga ↩️", 'callback_data' => 'back'],
        ]
    ]
]);

$menuAdmin = json_encode([
    'inline_keyboard' => [
        [
            ['text' => "Vazifa berish ➕", 'callback_data' => 'task_user'],
        ],
        [
            ['text' => "Berilgan vazifalar 🧾", 'callback_data' => 'not_finished'],
            ['text' => "Bitgan vazifalar ✅", 'callback_data' => 'confirm'],
        ]
    ]
]);

$userTypes = json_encode([
    'inline_keyboard' => [
        [
            ['text' => "👨‍💼 | Nazoratchi", 'callback_data' => 'type_2'],
            ['text' => "🙍‍♂️ | OTK", 'callback_data' => 'type_3'],
            ['text' => "👨‍💻 | Sotuvchi", 'callback_data' => 'type_4'],
        ],
        [
            ['text' => "🧑‍💻 | Bo’lim boshlig’i", 'callback_data' => 'type_5'],
            ['text' => "👨‍🔧 | Kroychi", 'callback_data' => 'type_6'],
            ['text' => "👷‍♂️ | Ishchi", 'callback_data' => 'type_7'],
        ]
    ]
]);

$menuUser = json_encode([
    'inline_keyboard' => [
        [
            ['text' => "Tasdiqlash kutayotgan vazifalar ☑️", 'callback_data' => 'confirm_task'],
        ],
        [
            ['text' => "Mening vazifalarim 📑", 'callback_data' => 'user_task'],
        ]
    ]
]);

$remove_keyboard = array(
    'remove_keyboard' => true
);
$remove_keyboard = json_encode($remove_keyboard);

if ($text == "/start") {
    $sql = "SELECT * FROM task_step WHERE chat_id = " . $chat_id;
    $result = pg_query($conn, $sql);
    if (!pg_num_rows($result) > 0) {
        $addStepUser = "INSERT INTO task_step (chat_id,step_1,step_2) VALUES (" . $chat_id . ",0,0)";
        $result = pg_query($conn, $addStepUser);
        $addLastId = "INSERT INTO task_last_id (chat_id,last_id) VALUES (" . $chat_id . ",0)";
        $resultLastId = pg_query($conn, $addLastId);
    } else {
        $stepUpdate = "UPDATE task_step SET step_2 = 0 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);
        $lastIdUpdate = "UPDATE task_last_id SET last_id = 0 WHERE chat_id = " . $chat_id;
        $resultLastId = pg_query($conn, $lastIdUpdate);
    }

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "*Assalomu alaykum!" . PHP_EOL . "Raqamingizni jo'nating 📲*",
        'parse_mode' => 'markdown',
        'reply_markup' => $phone_number
    ]);
}

$sql = "SELECT * FROM task_step WHERE chat_id = " . $chat_id;
$result = pg_query($conn, $sql);
$step_1 = 0;
$step_2 = 0;
if (pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $step_1 = $row["step_1"];
    $step_2 = $row["step_2"];
}

$sqlLastId = "SELECT * FROM task_last_id WHERE chat_id = " . $chat_id;
$resultLastId = pg_query($conn, $sqlLastId);
if (pg_num_rows($resultLastId) > 0) {
    $row = pg_fetch_assoc($resultLastId);
    $last_id = $row["last_id"];
}

$selectChatId = "SELECT * FROM users WHERE chat_id = " . $chat_id;
$resultChatId = pg_query($conn, $selectChatId);
if (pg_num_rows($resultChatId) > 0) {
    $row = pg_fetch_assoc($resultChatId);
    $user_id = $row["id"];
}

$selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
$resultTask = pg_query($conn, $selectTask);
if (pg_num_rows($resultTask) > 0) {
    $row = pg_fetch_assoc($resultTask);
    $taskId = $row["id"];
}

if ($step_1 == 0 and $step_2 == 0 and $text != "/start") {
    if (isset($update->message->contact)) {
        $number = $update->message->contact->phone_number;
        $number = str_replace("+", "", $number);

        $phoneNumber = "select * from users where phone_number = '" . $number . "'";
        $resultNumber = pg_query($conn, $phoneNumber);

        if (pg_num_rows($resultNumber) > 0) {
            $chatIdUpdate = "UPDATE users SET chat_id = " . $chat_id . " WHERE phone_number = '" . $number . "'";
            $resultChatId = pg_query($conn, $chatIdUpdate);
            $row = pg_fetch_assoc($resultNumber);
            $type = $row['type'];

            $res = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Siz tekshiruvdan muvaffaqiyatli o'tdingiz ✅*",
                'parse_mode' => 'markdown',
                'reply_markup' => $remove_keyboard
            ]);

            $message_id = $res->result->message_id;
            $insertMessageId = "INSERT INTO task_message_id (chat_id,message_id) VALUES (" . $chat_id . "," . $message_id . ")";
            $resultMessageId = pg_query($conn, $insertMessageId);

            if ($type == 1 or $type == 2) {
                $step = 1;

                $selectStatusTask = "SELECT * FROM tasks WHERE status = 10";
                $resultStatus = pg_query($conn, $selectStatusTask);
                if (pg_num_rows($resultStatus) > 0) {
                    while ($row = pg_fetch_assoc($resultStatus)) {
                        $delete_task_id = $row['id'];

                        $deleteTask = "DELETE FROM task_materials WHERE task_id = " . $delete_task_id;
                        $resultDelete = pg_query($conn, $deleteTask);

                        $deleteTaskUser = "DELETE FROM task_user WHERE task_id = " . $delete_task_id;
                        $resultDeleteUser = pg_query($conn, $deleteTaskUser);

                        $deleteTaskLast = "DELETE FROM tasks WHERE id = " . $delete_task_id;
                        $resultDeleteLast = pg_query($conn, $deleteTaskLast);
                    }
                }
                $menuButton = $menuAdmin;
            } else {
                $step = 50;
                $menuButton = $menuUser;

            }
            $stepUpdate = "UPDATE task_step SET step_2 = " . $step . " WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $menuButton
            ]);
        } else {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Bu botdan 🤖 foydalana olmaysiz ☹️❗️*",
                'parse_mode' => 'markdown'
            ]);
        }
    } else {
        deleteMessage($chat_id, $message_id);
    }
}

if ($step_1 == 0 and $step_2 == 1 and $text != "/start") {
    deleteMessageUser($conn, $chat_id);
    $adminTask = explode("_", $data);
    if ($data == "task_user") {
        $stepUpdate = "UPDATE task_step SET step_2 = 2 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectAdminId = "select * from users where status = 1 and chat_id = " . $chat_id;
        $resultAdmin = pg_query($conn, $selectAdminId);


        $date = (date('H:i') < '09:00' or date('H:i') > '20:00') ? date('Y-m-d 09:00:00') : date('Y-m-d H:i:s');

        $addTask = "INSERT INTO tasks (admin_id,status,created_date) VALUES (" . $chat_id . ",10,'" . $date . "')";
        $resultTask = pg_query($conn, $addTask);

        if ($result == true and $resultTask == true) {
            $file = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Ortga ↩️", 'callback_data' => 'back'],
                        ['text' => "Keyingisi ➡️ ", 'callback_data' => 'next'],
                    ],
                ]
            ]);

            bot('editMessageText', [
                'chat_id' => $chat_id,
                'text' => "Ma'lumot yuklang 📥",
                'message_id' => $message_id,
                'reply_markup' => $file
            ]);
        }
    } else if ($data == "not_finished") {
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
        $selectUsersAllSecond = "
                SELECT * 
                FROM tasks 
                INNER JOIN task_materials ON tasks.id = task_materials.task_id 
                WHERE tasks.status = 1 or tasks.status = 2 AND admin_id = " . $chat_id . "  ORDER BY tasks.id asc";
        $resultUserAllSecond = pg_query($conn, $selectUsersAllSecond);

        if (pg_num_rows($resultUserAllSecond) > 0) {
            $a = 1;
            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                    ],
                ]
            ]);
            while ($row = pg_fetch_assoc($resultUserAllSecond)) {
                $task_id = $row['task_id'];
                $selectUsersAll1 = "SELECT second_name FROM task_user INNER JOIN users ON task_user.user_id = users.id WHERE task_id = " . $task_id;
                $resultUserAll1 = pg_query($conn, $selectUsersAll1);
                $txtSend = '';
                while ($row1 = pg_fetch_assoc($resultUserAll1)) {
                    $second_name = $row1['second_name'];
                    $txtSend = $txtSend . "*👤 | *" . $second_name . PHP_EOL;
                }

                $task_fine = $row['task_fine'];
                $dead_line = $row['dead_line'];
                $deadline_fine = $row['deadline_fine'];
                $fineAndDeadLine = "*💰 Jarima:* " . $deadline_fine . PHP_EOL . "*💲 Deadline jarimasi:* " . $task_fine . PHP_EOL . "*🕔 Vazifa tugatilish vaqti:* " . $dead_line;

                $file_id = $row['file_id'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
                if ($a == pg_num_rows($resultUserAllSecond)) {
                    switch ($type) {
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $back
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $back
                            ]);
                            break;
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $back

                            ]);
                            break;
                        case 'voice':
                            bot('sendVoice', [
                                'chat_id' => $chat_id,
                                'voice' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $back
                            ]);
                            break;
                        case 'audio':
                            bot('sendAudio', [
                                'chat_id' => $chat_id,
                                'audio' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $back
                            ]);
                            break;
                        case 'video_note':
                            bot('sendVideoNote', [
                                'chat_id' => $chat_id,
                                'video_note' => $file_id,
                            ]);
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $back
                            ]);
                            break;
                    }
                } else {
                    switch ($type) {
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                            ]);
                            break;
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                            ]);
                            break;
                        case 'voice':
                            bot('sendVoice', [
                                'chat_id' => $chat_id,
                                'voice' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                            ]);
                            break;
                        case 'audio':
                            bot('sendAudio', [
                                'chat_id' => $chat_id,
                                'audio' => $file_id,
                                'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                            ]);
                            break;
                        case 'video_note':
                            bot('sendVideoNote', [
                                'chat_id' => $chat_id,
                                'video_note' => $file_id,
                            ]);
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                                'parse_mode' => 'markdown',
                            ]);
                            break;
                    }
                }
                $message_id = $message_id + 1;
                $insertMessageId = "INSERT INTO task_message_id (chat_id,message_id) VALUES (" . $chat_id . "," . $message_id . ")";
                $resultMessageId = pg_query($conn, $insertMessageId);
                $a++;
            }
        }
    } else if ($data == "confirm") {
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
        $selectTask = "SELECT * FROM tasks AS t INNER JOIN task_materials AS ts ON t.id = ts.task_id WHERE t.status = 3";
        $resultTask = pg_query($conn, $selectTask);
        if (pg_num_rows($resultTask) > 0) {
            while ($row = pg_fetch_assoc($resultTask)) {
                $file_id = $row['file_id'];
                $task_id = $row['task_id'];
                $created_date = date('Y-m-d H:i', strtotime($row['created_date']));
                $dead_line = $row['dead_line'];
                $task_fine = $row['task_fine'];
                $deadline_fine = $row['deadline_fine'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
                $fineAndDeadLine = "*💰 Jarima:* " . $deadline_fine . PHP_EOL . "*💲 Deadline jarimasi:* " . $task_fine . PHP_EOL . "*📆 Vazifa berilgan vaqti: *" . $created_date . PHP_EOL . "*🕔 Vazifa tugatilish vaqti:* " . $dead_line;
                $selectUsersAll = "SELECT * FROM task_status WHERE task_id = " . $task_id;
                $resultUserAll = pg_query($conn, $selectUsersAll);
                $txtSend = '';
                while ($row = pg_fetch_assoc($resultUserAll)) {
                    $status = $row['status'];
                    $user_id = $row['user_id'];
                    $selectSecondName = "SELECT * FROM users WHERE id = " . $user_id;
                    $resultSecondName = pg_query($conn, $selectSecondName);
                    if (pg_num_rows($resultSecondName) > 0) {
                        $row = pg_fetch_assoc($resultSecondName);
                        $second_name = $row["second_name"];
                    }
                    if ($status == 3) {
                        $txtSend = $txtSend . "*👤 | *" . $second_name . " ✅" . PHP_EOL;
                    } else {
                        $txtSend = $txtSend . "*👤 | *" . $second_name . " ❌" . PHP_EOL;
                    }
                }
                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Vazifani ko'rish 📂", 'callback_data' => 'admin_' . $task_id],
                        ],
                        [
                            ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                        ]
                    ]
                ]);
                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $chat_id,
                            'voice' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $chat_id,
                            'audio' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
                $message_id = $message_id + 1;
                $insertMessageId = "INSERT INTO task_message_id (chat_id,message_id) VALUES (" . $chat_id . "," . $message_id . ")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        } else {
            $answer = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Vazifa berish ➕", 'callback_data' => 'task_user'],
                    ],
                    [
                        ['text' => "Berilgan vazifalar 🧾", 'callback_data' => 'not_finished'],
                        ['text' => "Bitgan vazifalar ✅", 'callback_data' => 'confirm'],
                    ]
                ]
            ]);

            $res = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Hali bitgan vazifalar yo'q",
            ]);

            $message_id = $res->result->message_id;
            $insertMessageId = "INSERT INTO task_message_id (chat_id,message_id) VALUES (" . $chat_id . "," . $message_id . ")";
            $resultMessageId = pg_query($conn, $insertMessageId);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $answer
            ]);
        }
    } else if ($adminTask[0] == "admin") {
        $selectMessageId = "SELECT * FROM task_message_id WHERE chat_id = " . $chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0) {
            while ($row = pg_fetch_assoc($resultMessageId)) {
                $user_message_id = $row['message_id'];

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM task_message_id WHERE chat_id = " . $chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
        }

        $selectFile = "
                SELECT 
                       tm.task_id, 
                       tm.user_id, 
                       tm.file_id, 
                       tm.type, 
                       t.admin_id, 
                       t.created_date, 
                       t.dead_line, 
                       t.task_fine, 
                       t.status, 
                       users.second_name 
                FROM task_user_materials AS tm 
                INNER JOIN tasks AS t ON tm.task_id = t.id 
                INNER JOIN users ON tm.user_id = users.id 
                WHERE task_id = " . $adminTask[1];
        $resultFile = pg_query($conn, $selectFile);

        if (pg_num_rows($resultFile) > 0) {
            while ($row = pg_fetch_assoc($resultFile)) {
                $file_id = $row['file_id'];
                $type = $row['type'];
                $task_id = $row['task_id'];
                $user_id = $row['user_id'];
                $createdDate = $row['created_date'];
                $created_date = date('Y-m-d H:i', strtotime($createdDate));
                $second_name = $row['second_name'];
                $taskDate = date('Y-m-d H:i');

                $txtSend = "*👤 Vazifa kim tomonidan bajarildi: *" . $second_name . PHP_EOL . PHP_EOL . "*📅 Vazifa berilgan sana: *" . $created_date . PHP_EOL . "*📆 Vazifa bitgan sana:* " . $taskDate;

                $endDateUpdate = "UPDATE task_status SET end_date = '" . $taskDate . ":s' WHERE user_id = " . $user_id . " and task_id = " . $last_id;
                $resultEndDate = pg_query($conn, $endDateUpdate);

                $selectStatus = "SELECT * FROM task_status WHERE task_id = " . $task_id . " AND user_id = " . $user_id;
                $resultSelectStatus = pg_query($conn, $selectStatus);
                if (pg_num_rows($resultSelectStatus) > 0) {
                    $row = pg_fetch_assoc($resultSelectStatus);
                    $status = $row['status'];

                    if ($status == 2) {
                        $stepUpdate = "UPDATE task_step SET step_2 = 12 WHERE chat_id = " . $chat_id;
                        $result = pg_query($conn, $stepUpdate);

                        $confirmMaterial = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Bekor qilish ❌", 'callback_data' => 'cancel_' . $task_id . "_" . $user_id],
                                    ['text' => "Tasdiqlash ✅", 'callback_data' => 'confirm_' . $task_id . "_" . $user_id],
                                ],
                                [
                                    ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                                ]
                            ]
                        ]);
                    } else {
                        $confirmMaterial = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                                ]
                            ]
                        ]);
                    }
                }

                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "💬 | " . $file_id . PHP_EOL . $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $chat_id,
                            'voice' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $chat_id,
                            'audio' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
            }
        }
    } else if ($data == "home") {
        $selectMessageId = "SELECT * FROM task_message_id WHERE chat_id = " . $chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0) {
            while ($row = pg_fetch_assoc($resultMessageId)) {
                $user_message_id = $row['message_id'];

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM task_message_id WHERE chat_id = " . $chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
        }

        $answer = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => "Vazifa berish ➕", 'callback_data' => 'task_user'],
                ],
                [
                    ['text' => "Berilgan vazifalar 🧾", 'callback_data' => 'not_finished'],
                    ['text' => "Bitgan vazifalar ✅", 'callback_data' => 'confirm'],
                ]
            ]
        ]);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $answer
        ]);
    } else {
        deleteMessage($chat_id, $message_id);
    }
}

if ($step_1 == 0 and $step_2 == 2 and $text != "/start") {
    if (isset($message->photo) || isset($message->video) || isset($message->video_note) || isset($message->voice) || isset($message->audio)) {
        if (isset($message->photo)) {
            if (isset($message->photo[2])) {
                $file_id = $message->photo[2]->file_id;
            } else if (isset($message->photo[1])) {
                $file_id = $message->photo[1]->file_id;
            } else {
                $file_id = $message->photo[0]->file_id;
            }
            $type = "photo";
        } else if (isset($message->video)) {
            $file_id = $message->video->file_id;
            $type = "video";
        } else if (isset($message->video_note)) {
            $file_id = $message->video_note->file_id;
            $type = "video_note";
        } else if (isset($message->voice)) {
            $file_id = $message->voice->file_id;
            $type = "voice";
        } else if (isset($message->audio)) {
            $file_id = $message->audio->file_id;
            $type = "audio";
        }

        if (isset($file_id) && !empty($file_id)) {
            $addMaterial = "INSERT INTO task_materials (task_id,file_id,type) VALUES (" . $taskId . ",'" . $file_id . "','" . $type . "')";
            $resultMaterial = pg_query($conn, $addMaterial);

            $stepUpdate = "UPDATE task_step SET step_2 = 3 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Faylga tavsif kriting ✍️",
                'reply_markup' => $back
            ]);
        }
    } else if ($data == "next") {
        $stepUpdate = "UPDATE task_step SET step_2 = 3 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
        $resultTask = pg_query($conn, $selectTask);

        if (pg_num_rows($resultTask) > 0) {
            $row = pg_fetch_assoc($resultTask);
            $taskId = $row["id"];
        }

        $addMaterial = "INSERT INTO task_materials (task_id,type) VALUES (" . $taskId . ",'text')";
        $resultMaterial = pg_query($conn, $addMaterial);

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'text' => "Matn kriting ✍️",
            'message_id' => $message_id,
            'reply_markup' => $back
        ]);
    } else if ($data == "back") {
        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $deleteMaterial = "DELETE FROM tasks WHERE status = 0 and admin_id = " . $chat_id . " and id = " . $taskId;
        $resultMaterial = pg_query($conn, $deleteMaterial);

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuAdmin
        ]);
    } else {
        deleteMessage($chat_id, $message_id);
    }
}

if ($step_1 == 0 and $step_2 == 3 and $text != "/start") {
    if (isset($text)) {
        $stepUpdate = "UPDATE task_step SET step_2 = 4 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $txt = base64_encode($text);
        $selectTaskType = "SELECT * FROM task_materials WHERE task_id = " . $taskId;
        $resultTaskType = pg_query($conn, $selectTaskType);
        if (pg_num_rows($resultTaskType) > 0) {
            $row = pg_fetch_assoc($resultTaskType);
            $taskType = $row["type"];
        }

        $taskUpdate = "UPDATE task_materials SET caption = '" . $txt . "' WHERE task_id = " . $taskId;
        $resultTask = pg_query($conn, $taskUpdate);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "Ishchilarni tanlang 🔘",
            'reply_markup' => $userTypes
        ]);
    } else if ($data == "back") {
        $stepUpdate = "UPDATE task_step SET step_2 = 2 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $deleteMaterial = "DELETE FROM task_materials WHERE type = 'text' and task_id = " . $taskId;
        $resultMaterial = pg_query($conn, $deleteMaterial);

        $file = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => "Ortga ↩️", 'callback_data' => 'back'],
                    ['text' => "Keyingisi ➡️ ", 'callback_data' => 'next'],
                ]
            ]
        ]);

        if ($result == true) {
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'text' => "Ma'lumot yuklang 📥",
                'message_id' => $message_id,
                'reply_markup' => $file
            ]);
        }

    }
}

if ($step_1 == 0 and $step_2 == 4 and $text != "/start") {
    $user_type = explode("_", $data);
    if ($user_type[0] == "type") {
        $stepUpdate = "UPDATE task_step SET step_2 = 5 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $lastIdUpdate = "UPDATE task_last_id SET last_id = " . $user_type[1] . " WHERE chat_id = " . $chat_id;
        $resultLastId = pg_query($conn, $lastIdUpdate);

        $sqlLastId = "SELECT * FROM task_last_id WHERE chat_id = " . $chat_id;
        $resultLastId = pg_query($conn, $sqlLastId);
        if (pg_num_rows($resultLastId) > 0) {
            $row = pg_fetch_assoc($resultLastId);
            $last_id = $row["last_id"];
        }

        $selectUsers = "SELECT * FROM users WHERE type = " . $last_id;
        $resultUsers = pg_query($conn, $selectUsers);

        if (pg_num_rows($resultUsers) > 0) {
            $arr_uz = [];
            $row_arr = [];
            while ($value = pg_fetch_assoc($resultUsers)) {
                $user_id = $value['id'];
                $second_name = $value['second_name'];
                $arr_uz[] = ["text" => "👤 | $second_name", "callback_data" => "user_" . $user_id];
                $row_arr[] = $arr_uz;
                $arr_uz = [];
            }

            $row_arr[] = [["text" => "Keyingisi ➡️ ", "callback_data" => "next"]];
            $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => "Ishchilarni tanlang 🔘",
                'reply_markup' => $btnKey
            ]);
        } else {
            $stepUpdate = "UPDATE task_step SET step_2 = 4 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Bu bo'limda hali ma'lumot yo'q ❗️😕*",
                'parse_mode' => 'markdown',
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Ishchilarni tanlang 🔘",
                'reply_markup' => $userTypes
            ]);
        }
    }
}

if ($step_1 == 0 and $step_2 == 5 and $text != "/start") {
    $user_date = explode("_", $data);
    if ($user_date[0] == "user" and $data != "next") {
        $selectTaskUser = "SELECT * FROM task_user WHERE task_id = " . $taskId . " and user_id = " . $user_date[1];
        $resultUser = pg_query($conn, $selectTaskUser);

        if ((!pg_num_rows($resultUser)) > 0) {
            $insertUser = "insert into task_user (task_id,user_id) values (" . $taskId . "," . $user_date[1] . ")";
            pg_query($insertUser);

            $insertStepUser = "insert into task_status (task_id,user_id,status,step_cron,step_deadline) values (" . $taskId . "," . $user_date[1] . ",10,0,0)";
            pg_query($conn, $insertStepUser);
        } else {
            $deleteUser = "DELETE FROM task_user where task_id = " . $taskId . " and user_id = " . $user_date[1];
            $result = pg_query($conn, $deleteUser);

            $deleteStepUser = "DELETE FROM task_status where task_id = " . $taskId . " and user_id = " . $user_date[1];
            $resultStepUser = pg_query($conn, $deleteStepUser);
        }

        $selectUsers = "SELECT * FROM users WHERE type = " . $last_id;
        $resultUsers = pg_query($conn, $selectUsers);
        $arr_uz = [];
        $row_arr = [];
        if (pg_num_rows($resultUsers) > 0) {
            while ($value = pg_fetch_assoc($resultUsers)) {
                $user_id = $value['id'];
                $second_name = $value['second_name'];

                $checkUsers = "SELECT * FROM task_user where user_id = " . $user_id . " and task_id = " . $taskId;
                $resultCheck = pg_query($conn, $checkUsers);

                if (pg_num_rows($resultCheck) > 0) {
                    $arr_uz[] = ["text" => "👤 | $second_name" . " ✅", "callback_data" => "user_" . $user_id];
                    $row_arr[] = $arr_uz;
                    $arr_uz = [];
                } else {
                    $arr_uz[] = ["text" => "👤 | $second_name", "callback_data" => "user_" . $user_id];
                    $row_arr[] = $arr_uz;
                    $arr_uz = [];
                }
            }

            $row_arr[] = [["text" => "Keyingisi ➡️ ", "callback_data" => "next"]];
            $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => "Ishchilarni tanlang 🔘",
                'reply_markup' => $btnKey
            ]);
        }
    } else if ($data == "next") {
        $selectTaskUser = "SELECT * FROM task_user WHERE task_id = " . $taskId;
        $resultUser = pg_query($conn, $selectTaskUser);

        if (!pg_num_rows($resultUser) > 0) {
            $selectUsers = "SELECT * FROM users WHERE type = " . $last_id;
            $resultUsers = pg_query($conn, $selectUsers);
            $arr_uz = [];
            $row_arr = [];
            if (pg_num_rows($resultUsers) > 0) {
                while ($value = pg_fetch_assoc($resultUsers)) {
                    $user_id = $value['id'];
                    $second_name = $value['second_name'];

                    $checkUsers = "SELECT * FROM task_user where user_id = " . $user_id . " and task_id = " . $taskId;
                    $resultCheck = pg_query($conn, $checkUsers);

                    if (pg_num_rows($resultCheck) > 0) {
                        $arr_uz[] = ["text" => "👤 | $second_name" . " ✅", "callback_data" => "user_" . $user_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    } else {
                        $arr_uz[] = ["text" => "👤 | $second_name", "callback_data" => "user_" . $user_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                }

                $row_arr[] = [["text" => "Keyingisi ➡️ ", "callback_data" => "next"]];
                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Vazifaga ishchi briktirmadizngiz ❗️😕*" . PHP_EOL . PHP_EOL . "Ishchilarni tanlang 🔘",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $btnKey
                ]);
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
            $stepUpdate = "UPDATE task_step SET step_2 = 6 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            if ($result == true) {
                $month = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Yanvar", 'callback_data' => 'month_31_01'],
                            ['text' => "Fevral", 'callback_data' => 'month_28_02'],
                            ['text' => "Mart", 'callback_data' => 'month_31_03'],
                            ['text' => "Aprel", 'callback_data' => 'month_30_04'],
                        ],
                        [
                            ['text' => "May", 'callback_data' => 'month_31_05'],
                            ['text' => "Iyun", 'callback_data' => 'month_30_06'],
                            ['text' => "Iyul", 'callback_data' => 'month_31_07'],
                            ['text' => "Avgust", 'callback_data' => 'month_31_08'],
                        ],
                        [
                            ['text' => "Sentabr", 'callback_data' => 'month_30_09'],
                            ['text' => "Oktabr", 'callback_data' => 'month_31_10'],
                            ['text' => "Noyabr", 'callback_data' => 'month_30_11'],
                            ['text' => "Dekabr", 'callback_data' => 'month_31_12'],
                        ]
                    ]
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Vazifa tugash oyini kriting ⌛️",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $month
                ]);
            }
        }
    }
}

if ($step_1 == 0 and $step_2 == 6 and $text != "/start") {
    $month = explode("_", $data);
    if ($month[0] == "month") {
        $stepUpdate = "UPDATE task_step SET step_2 = 7 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $select_date = date('Y-' . $month[2]);

        $taskUpdate = "UPDATE tasks SET dead_line = '" . $select_date . "' WHERE admin_id = " . $chat_id . " AND id = " . $taskId;
        $resultTaskDeadLine = pg_query($conn, $taskUpdate);

        $arr_uz = [];
        $row_arr = [];
        $k = 1;
        for ($i = 1; $i <= $month[1]; $i++) {
            $arr_uz[] = ["text" => $i, "callback_data" => "day_" . $i];
            if ($k % 5 == 0) {
                $row_arr[] = $arr_uz;
                $arr_uz = [];
            }
            $k++;
        }
        $row_arr[] = $arr_uz;
        $btnKey = json_encode(['inline_keyboard' => $row_arr]);
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => 'Vazifa tugash sanasini kriting 📅',
            'reply_markup' => $btnKey,
        ]);
    }
}

if ($step_1 == 0 and $step_2 == 7 and $text != "/start") {
    $day = explode("_", $data);
    if ($day[0] == "day") {
        $stepUpdate = "UPDATE task_step SET step_2 = 8 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
        $resultTask = pg_query($conn, $selectTask);

        if (pg_num_rows($resultTask) > 0) {
            $row = pg_fetch_assoc($resultTask);
            $taskId = $row["id"];
            $dead_line = $row['dead_line'];
        }
        if ($day[1] <= 9) {
            $select_date = $dead_line . "-0" . $day[1];
        } else {
            $select_date = $dead_line . "-" . $day[1];
        }

        $taskUpdate = "UPDATE tasks SET dead_line = '" . $select_date . "' WHERE admin_id = " . $chat_id . " AND id = " . $taskId;
        $resultTaskDeadLine = pg_query($conn, $taskUpdate);

        if ($resultTaskDeadLine == true) {
            $month = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "09:00", 'callback_data' => 'time_09:00'],
                        ['text' => "10:00", 'callback_data' => 'time_10:00'],
                        ['text' => "11:00", 'callback_data' => 'time_11:00'],
                        ['text' => "12:00", 'callback_data' => 'time_12:00'],
                    ],
                    [
                        ['text' => "13:00", 'callback_data' => 'time_13:00'],
                        ['text' => "14:00", 'callback_data' => 'time_14:00'],
                        ['text' => "15:00", 'callback_data' => 'time_15:00'],
                        ['text' => "16:00", 'callback_data' => 'time_16:00'],
                    ],
                    [
                        ['text' => "17:00", 'callback_data' => 'time_17:00'],
                        ['text' => "18:00", 'callback_data' => 'time_18:00'],
                        ['text' => "19:00", 'callback_data' => 'time_19:00'],
                        ['text' => "20:00", 'callback_data' => 'time_20:00'],
                    ]
                ]
            ]);

            bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => "Vazifa tugash vaqtini kriting 🕔",
                'parse_mode' => 'markdown',
                'reply_markup' => $month
            ]);
        }
    }
}

if ($step_1 == 0 and $step_2 == 8 and $text != "/start") {
    $time = explode("_", $data);
    if ($time[0] == "time") {
        $stepUpdate = "UPDATE task_step SET step_2 = 9 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
        $resultTask = pg_query($conn, $selectTask);

        if (pg_num_rows($resultTask) > 0) {
            $row = pg_fetch_assoc($resultTask);
            $taskId = $row["id"];
            $dead_line = $row['dead_line'];
            $created_date = $row['dead_line'];
        }

        $select_date = $dead_line . " " . $time[1];
        $dead_line_time = date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($select_date)));

        $taskUpdate = "UPDATE tasks SET dead_line = '" . $select_date . "' WHERE admin_id = " . $chat_id . " AND id = " . $taskId;
        $resultTaskDeadLine = pg_query($conn, $taskUpdate);

        $selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
        $resultTask = pg_query($conn, $selectTask);

        if (pg_num_rows($resultTask) > 0) {
            $row = pg_fetch_assoc($resultTask);
            $taskId = $row["id"];
            $dead_line = $row['dead_line'];
            $created_date = $row['created_date'];
        }


        $c_day = date('d', strtotime($created_date));
        $d_day = date('d', strtotime($dead_line));

        if ($c_day == $d_day) {
            $minus_date = abs(date('H', strtotime($created_date)) - date('H', strtotime($dead_line)));
            if (date('i', strtotime($created_date)) < 30) {
                if ($minus_date == 1) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-30 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 11 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 2) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-90 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 10 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 3) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-150 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 9 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 4) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-210 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 8 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 5) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-270 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 7 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 6) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-330 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 6 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-360 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "' WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                }
            } else {
                if ($minus_date == 1) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('+0 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 10 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 2) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-60 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 9 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 3) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-120 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 8 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 4) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-180 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 7 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 5) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-240 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 6 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_date == 6) {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-300 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "', step_deadline = 5 WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else {
                    $time = new DateTime($dead_line);
                    $minus_6 = $time->modify('-360 minutes')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_6 . "' WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                }
            }
        } else if ($c_day < $d_day) {
            $yesterday_minus = abs('20:00' - date('H:i', strtotime($created_date)));
            $yesterday_minus_2 = abs('09:00' - date('H:i', strtotime($dead_line)));
            $plus_hours = $yesterday_minus + $yesterday_minus_2;
            if ($plus_hours <= 6) {
                if (date('i', strtotime($created_date)) < 30) {
                    if ($plus_hours == 1) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-30 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 10 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 10 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 10 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 2) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-90 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 8 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 8 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 8 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 3) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-150 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 6 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 6 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 6 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 4) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-210 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 4 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 4 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 4 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 5) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-270 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 6) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-330 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":30:s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    }
                } else {
                    if ($plus_hours == 1) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('+0 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 10 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 10 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 10 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 2) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-60 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 8 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 8 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 8 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 3) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-120 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 6 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 6 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 6 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 4) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-180 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 4 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 4 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 4 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 5) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-240 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    } else if ($plus_hours == 6) {
                        $time = new DateTime($dead_line);
                        $minus_6 = $time->modify('-300 minutes')->format('H:i');
                        if ($minus_6 < '09:00') {
                            $minus_dead = '09:00' - $minus_6;
                            $all = '20:00' - $minus_dead;
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else if ($minus_6 > '20:00') {
                            $minus_dead = $minus_6 - '20:00';
                            $all = $minus_dead - '09:00';
                            $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                            $time = new DateTime($dead_line_str);
                            $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        } else {
                            $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                            $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "', step_deadline = 2 WHERE task_id = " . $taskId;
                            $resultEndDate = pg_query($conn, $endDateUpdate);
                        }
                    }
                }
            } else {
                $time = new DateTime($dead_line);
                $minus_6 = $time->modify('-6 hour')->format('H:i');

                if ($minus_6 < '09:00') {
                    $minus_dead = '09:00' - $minus_6;
                    $all = '20:00' - $minus_dead;
                    $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                    $time = new DateTime($dead_line_str);
                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "' WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else if ($minus_6 > '20:00') {
                    $minus_dead = $minus_6 - '20:00';
                    $all = $minus_dead - '09:00';
                    $dead_line_str = date('Y-m-d ' . $all . ":s", strtotime($dead_line));
                    $time = new DateTime($dead_line_str);
                    $minus_day = $time->modify('-1 day')->format('Y-m-d H:i:s');
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $minus_day . "' WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                } else {
                    $dead_line_str = date('Y-m-d ' . $minus_6 . ":s", strtotime($dead_line));
                    $endDateUpdate = "UPDATE task_status SET task_end_date = '" . $dead_line_str . "' WHERE task_id = " . $taskId;
                    $resultEndDate = pg_query($conn, $endDateUpdate);
                }
            }
        }

        if ($resultTaskDeadLine == true) {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Vaqtida bitmaganlik uchun jarima summasini kiriting 💲",
            ]);
        }

    }
}

if ($step_1 == 0 and $step_2 == 9 and $text != "/start") {
    if (ctype_digit($text) != false) {
        $stepUpdate = "UPDATE task_step SET step_2 = 15 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $taskUpdate = "UPDATE tasks SET task_fine = " . $text . " WHERE admin_id = " . $chat_id . " AND id = " . $taskId;
        $resultTaskFine = pg_query($conn, $taskUpdate);

        if ($result == true and $resultTaskFine == true) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Vaqtida qabul qilmaganligi uchun jarima summasini kiriting 💲",
                'parse_mode' => 'markdown'
            ]);
        }
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Iltimos jarima summasini kritayotganingizda faqat raqamlardan foydalaning!*",
            'parse_mode' => 'markdown'
        ]);
    }
}

if ($step_1 == 0 and $step_2 == 15 and $text != "/start") {
    if (ctype_digit($text) != false) {
        $stepUpdate = "UPDATE task_step SET step_2 = 10 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectUsersAll = "SELECT second_name FROM task_user INNER JOIN users ON task_user.user_id = users.id WHERE task_id = " . $taskId;
        $resultUserAll = pg_query($conn, $selectUsersAll);
        $txtSend = '';
        while ($row = pg_fetch_assoc($resultUserAll)) {
            $second_name = $row['second_name'];
            $txtSend = $txtSend . "*👤 | *" . $second_name . PHP_EOL;
        }

        $taskUpdate = "UPDATE tasks SET deadline_fine = " . $text . " WHERE admin_id = " . $chat_id . " AND id = " . $taskId;
        $resultTaskFine = pg_query($conn, $taskUpdate);

        if ($result == true and $resultTaskFine == true) {
            $selectMaterials = "SELECT * FROM task_materials WHERE task_id = " . $taskId . " ORDER BY id DESC LIMIT 1";
            $resultMaterialFileId = pg_query($conn, $selectMaterials);

            $selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
            $resultTask = pg_query($conn, $selectTask);
            if (pg_num_rows($resultTask) > 0) {
                $row = pg_fetch_assoc($resultTask);
                $taskId = $row["id"];
                $deadLine = $row["dead_line"];
                $task_fine = $row["task_fine"];
                $deadline_fine = $row["deadline_fine"];
            }
            $fineAndDeadLine = "*💰 Jarima:* " . $deadline_fine . PHP_EOL . "*💲 Deadline jarimasi:* " . $task_fine . PHP_EOL . "*🕔 Vazifa tugatilish vaqti:* " . $deadLine;

            $taskMaterial = "SELECT * FROM task_materials WHERE task_id = " . $taskId;
            $resultTaskMaterial = pg_query($conn, $taskMaterial);

            $confirmMaterial = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Bekor qilish ❌", 'callback_data' => 'cancel'],
                        ['text' => "Tasdiqlash ✅", 'callback_data' => 'confirm'],
                    ]
                ]
            ]);

            while ($row = pg_fetch_assoc($resultMaterialFileId)) {
                $file_id = $row['file_id'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $chat_id,
                            'voice' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $chat_id,
                            'audio' => $file_id,
                            'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
            }
        }
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Iltimos jarima summasini kritayotganingizda faqat raqamlardan foydalaning!*",
            'parse_mode' => 'markdown'
        ]);
    }
}

if ($step_1 == 0 and $step_2 == 10 and $text != "/start") {
    if ($data == "confirm") {
        $selectTask = "SELECT * FROM tasks WHERE admin_id = " . $chat_id . " ORDER BY id DESC limit 1";
        $resultTask = pg_query($conn, $selectTask);

        if (pg_num_rows($resultTask) > 0) {
            $row = pg_fetch_assoc($resultTask);
            $taskId = $row["id"];
            $deadLine = $row["dead_line"];
            $task_fine = $row["task_fine"];
            $deadline_fine = $row["deadline_fine"];
        }
        $fineAndDeadLine = "*💰 Jarima:* " . $deadline_fine . PHP_EOL . "*💲 Deadline jarimasi:* " . $task_fine . PHP_EOL . "*🕔 Vazifa tugatilish vaqti:* " . $deadLine;

        if (date('H:i') > "09:00" and date('H:i') < '20:00') {
            $statusUpdate = "UPDATE tasks SET status = 1 WHERE admin_id = " . $chat_id . " and id = " . $taskId;
            $resultStatus = pg_query($conn, $statusUpdate);

            $taskStatusUpdate = "UPDATE task_status SET status = 0 WHERE task_id = " . $taskId;
            $resultTaskStatus = pg_query($conn, $taskStatusUpdate);

            $selectMaterials = "SELECT * FROM task_materials WHERE task_id = " . $taskId . " ORDER BY id DESC LIMIT 1";
            $resultMaterialFileId = pg_query($conn, $selectMaterials);
            if (pg_num_rows($resultTask) > 0) {
                $row = pg_fetch_assoc($resultMaterialFileId);
                $file_id = $row['file_id'];
                $task_id = $row['task_id'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
            }

            $confirmMaterial = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Qabul qilish ✅", 'callback_data' => 'confirmTask_' . $task_id],
                        ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                    ]
                ]
            ]);

            $selectTask = "SELECT * FROM task_user WHERE task_id = " . $taskId;
            $resultTaskUser = pg_query($conn, $selectTask);

            while ($row = pg_fetch_assoc($resultTaskUser)) {
                $user_id = $row["user_id"];

                $taskUserId = "SELECT * FROM users WHERE id = " . $user_id;
                $resultUserId = pg_query($conn, $taskUserId);
                if (pg_num_rows($resultUserId) > 0) {
                    $row = pg_fetch_assoc($resultUserId);
                    $user_chat_id = $row['chat_id'];
                }
                $stepUpdate = "UPDATE task_step SET step_2 = 100 WHERE chat_id = " . $user_chat_id;
                $resultStep = pg_query($conn, $stepUpdate);
                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $user_chat_id,
                            'photo' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $user_chat_id,
                            'video' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $user_chat_id,
                            'text' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $user_chat_id,
                            'voice' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $user_chat_id,
                            'audio' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . PHP_EOL . $fineAndDeadLine,
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
                            'text' => "📃 *Tavsif:* " . $caption . PHP_EOL . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
            }
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Vazifa ishchilarga jo'natildi ☑️*",
                'parse_mode' => 'markdown',
            ]);
        } else {
            $statusUpdate = "UPDATE tasks SET status = 0 WHERE admin_id = " . $chat_id . " and status = 0 and id = " . $taskId;
            $resultStatus = pg_query($conn, $statusUpdate);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
        }

        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuAdmin
        ]);
    } else if ($data == "cancel") {
        $deleteAnswer = "DELETE FROM tasks WHERE admin_id = " . $chat_id . " and status = 0 and id = " . $taskId;
        $resultDelete = pg_query($conn, $deleteAnswer);

        $deleteWorker = "DELETE FROM task_user WHERE task_id = " . $taskId;
        $resultWorker = pg_query($conn, $deleteWorker);

        $deleteStatus = "DELETE FROM task_status WHERE task_id = " . $taskId;
        $resultStatus = pg_query($conn, $deleteStatus);

        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuAdmin
        ]);

    } else {
        deleteMessage($chat_id, $message_id);
    }
}

if ($step_1 == 0 and $step_2 == 11 and $text != "/start") {
    $check = explode("_", $data);
    if ($check[0] == "check") {
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
        $stepUpdate = "UPDATE task_step SET step_2 = 12 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectFile = "SELECT tm.task_id, tm.user_id, tm.file_id, tm.type, t.admin_id, t.created_date, t.dead_line, t.task_fine, t.status, users.second_name FROM task_user_materials AS tm INNER JOIN tasks AS t ON tm.task_id = t.id INNER JOIN users ON tm.user_id = users.id WHERE task_id = " . $check[1];
        $resultFile = pg_query($conn, $selectFile);

        if (pg_num_rows($resultFile) > 0) {
            while ($row = pg_fetch_assoc($resultFile)) {
                $file_id = $row['file_id'];
                $type = $row['type'];
                $task_id = $row['task_id'];
                $user_id = $row['user_id'];
                $createdDate = $row['created_date'];
                $created_date = date('Y-m-d H:i', strtotime($createdDate));
                $second_name = $row['second_name'];
                $taskDate = date('Y-m-d H:i');

                $txtSend = "*👤 Vazifa kim tomonidan bajarildi: *" . $second_name . PHP_EOL . PHP_EOL . "*📅 Vazifa berilgan sana: *" . $created_date . PHP_EOL . "*📆 Vazifa bitgan sana:* " . $taskDate;

                $endDateUpdate = "UPDATE task_status SET end_date = '" . $taskDate . ":s' WHERE user_id = " . $user_id . " and task_id = " . $last_id;
                $resultEndDate = pg_query($conn, $endDateUpdate);

                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Bekor qilish ❌", 'callback_data' => 'cancel_' . $task_id . "_" . $user_id],
                            ['text' => "Tasdiqlash ✅", 'callback_data' => 'confirm_' . $task_id . "_" . $user_id],
                        ],
                        [
                            ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                        ]
                    ]
                ]);

                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "💬 | " . $file_id . PHP_EOL . $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $chat_id,
                            'voice' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $chat_id,
                            'audio' => $file_id,
                            'caption' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
            }
        }
    } else if ($data == "home") {
        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuAdmin
        ]);
    }
}

if ($step_1 == 0 and $step_2 == 12 and $text != "/start") {
    $confirmTask = explode("_", $data);
    if ($confirmTask[0] == "home") {
        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuAdmin
        ]);
    } else if ($confirmTask[0] == "confirm") {
        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $statusUpdate = "UPDATE task_status SET status = 3 WHERE task_id = " . $confirmTask[1] . " AND user_id = " . $confirmTask[2];
        $resultStatus = pg_query($conn, $statusUpdate);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Bajarilgan vazifa qabul qilindi ✅*",
            'parse_mode' => 'markdown',
        ]);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuAdmin
        ]);

        $selectAllUser = "
            SELECT * FROM tasks AS t 
            INNER JOIN task_status AS ts ON t.id = ts.task_id 
            WHERE t.id = " . $confirmTask[1] . " AND ts.status != 3";
        $resultAllUser = pg_query($conn, $selectAllUser);

        if ((!pg_num_rows($resultAllUser)) > 0) {
            $taskUpdateAdmin = "UPDATE tasks SET status = 4 WHERE id = " . $confirmTask[1];
        } else {
            $taskUpdateAdmin = "UPDATE tasks SET status = 3 WHERE id = " . $confirmTask[1];
        }
        $resultTaskAdmin = pg_query($conn, $taskUpdateAdmin);
    } else if ($confirmTask[0] == "cancel") {
        $stepUpdate = "UPDATE task_step SET step_2 = 1 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $statusUpdate = "UPDATE task_status SET status = 1 WHERE task_id = " . $confirmTask[1] . " AND user_id = " . $confirmTask[2];
        $resultStatus = pg_query($conn, $statusUpdate);

        $deleteUserMaterial = "DELETE FROM task_user_materials WHERE user_id = " . $confirmTask[2] . " and task_id = " . $confirmTask[1];
        $resultUserMaterial = pg_query($conn, $deleteUserMaterial);

        $selectChatId = "SELECT * FROM users where id = " . $confirmTask[2];
        $resultChatId = pg_query($conn, $selectChatId);

        if ($result == true and $resultStatus == true and $resultUserMaterial == true and $resultChatId == true) {
            $row = pg_fetch_assoc($resultChatId);
            $user_chat_id = $row['chat_id'];
            bot('sendMessage', [
                'chat_id' => $user_chat_id,
                'text' => "*Bajargan vazifangiz qabul qilinmadi ❌*",
                'parse_mode' => 'markdown',
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Bajarilgan vazifa qabul qilinmadi ❌*",
                'parse_mode' => 'markdown',
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $menuAdmin
            ]);
        }
    }
}

if ($step_1 == 0 and $step_2 == 50 and $text != "/start") {
    if ($data == "confirm_task") {
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
        $stepUpdate = "UPDATE task_step SET step_2 = 51 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectTaskUser = "SELECT * FROM task_status AS ts INNER JOIN task_materials ON ts.task_id = task_materials.task_id WHERE user_id = " . $user_id . " AND status = 0";
        $resultTaskUser = pg_query($conn, $selectTaskUser);

        if (pg_num_rows($resultTaskUser) > 0) {
            while ($row = pg_fetch_assoc($resultTaskUser)) {
                $file_id = $row['file_id'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
                $task_id = $row['task_id'];

                $selectTaskId = "SELECT * FROM tasks WHERE id = " . $task_id;
                $resultTaskId = pg_query($conn, $selectTaskId);
                if (pg_num_rows($resultTaskId) > 0) {
                    $row = pg_fetch_assoc($resultTaskId);
                    $deadLine = $row["dead_line"];
                    $task_fine = $row["task_fine"];
                    $deadline_fine = $row["deadline_fine"];
                }

                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Ortga ↩️", 'callback_data' => 'back'],
                            ['text' => "Qabul qilish ✅", 'callback_data' => 'confirmUserTask_' . $task_id],
                        ]
                    ]
                ]);

                $fineAndDeadLine = "*💰 Jarima:* " . $deadline_fine . PHP_EOL . "*💲 Deadline jarimasi:* " . $task_fine . PHP_EOL . "*🕔 Vazifa tugatilish vaqti:* " . $deadLine;
                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $chat_id,
                            'voice' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $chat_id,
                            'audio' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
                $message_id = $message_id + 1;
                $insertMessageId = "INSERT INTO task_message_id (chat_id,message_id) VALUES (" . $chat_id . "," . $message_id . ")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        } else {
            $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Tasdiqlash kutayotgan vazifalar mavjud emas ❗️*",
                'parse_mode' => 'markdown'
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $menuUser
            ]);
        }
    } else if ($data == "user_task") {
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
        $stepUpdate = "UPDATE task_step SET step_2 = 51 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $selectChatId = "SELECT * FROM users WHERE chat_id = " . $chat_id;
        $resultChatId = pg_query($conn, $selectChatId);
        if (pg_num_rows($resultChatId) > 0) {
            $row = pg_fetch_assoc($resultChatId);
            $user_id = $row["id"];
        }

        $selectTaskUser = "SELECT * FROM task_status 
                                INNER JOIN users ON users.id =  task_status.user_id
                                INNER JOIN task_materials ON task_materials.task_id = task_status.task_id
                                WHERE task_status.status = 1 AND task_status.user_id = " . $user_id;
        $resultTaskUser = pg_query($conn, $selectTaskUser);

        if (pg_num_rows($resultTaskUser) > 0) {
            while ($row = pg_fetch_assoc($resultTaskUser)) {
                $file_id = $row['file_id'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
                $task_id = $row['task_id'];

                $selectTaskId = "SELECT * FROM tasks WHERE id = " . $task_id;
                $resultTaskId = pg_query($conn, $selectTaskId);
                if (pg_num_rows($resultTaskId) > 0) {
                    $row = pg_fetch_assoc($resultTaskId);
                    $deadLine = $row["dead_line"];
                    $task_fine = $row["task_fine"];
                    $deadline_fine = $row["deadline_fine"];
                }

                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Ortga ↩️", 'callback_data' => 'back'],
                            ['text' => "Bitdi ☑️", 'callback_data' => 'done_' . $task_id],
                        ],
                    ]
                ]);

                $fineAndDeadLine = "*💰 Jarima:* " . $deadline_fine . PHP_EOL . "*💲 Deadline jarimasi:* " . $task_fine . PHP_EOL . "*🕔 Vazifa tugatilish vaqti:* " . $deadLine;
                switch ($type) {
                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'voice':
                        bot('sendVoice', [
                            'chat_id' => $chat_id,
                            'voice' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'audio':
                        bot('sendAudio', [
                            'chat_id' => $chat_id,
                            'audio' => $file_id,
                            'caption' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                    case 'video_note':
                        bot('sendVideoNote', [
                            'chat_id' => $chat_id,
                            'video_note' => $file_id,
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $confirmMaterial
                        ]);
                        break;
                }
                $message_id = $message_id + 1;
                $insertMessageId = "INSERT INTO task_message_id (chat_id,message_id) VALUES (" . $chat_id . "," . $message_id . ")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        } else {
            $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Vazifalar mavjud emas ❗️*",
                'parse_mode' => 'markdown'
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $menuUser
            ]);
        }
    } else {
        deleteMessage($chat_id, $message_id);
    }
}

if ($step_1 == 0 and $step_2 == 51 and $text != "/start") {
    $confirm_user_task = explode("_", $data);
    if ($confirm_user_task[0] == "confirmUserTask") {
        $selectMessageId = "SELECT * FROM task_message_id WHERE chat_id = " . $chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0) {
            while ($row = pg_fetch_assoc($resultMessageId)) {
                $user_message_id = $row['message_id'];

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM task_message_id WHERE chat_id = " . $chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
        }
        $enter_date = date('Y-m-d H:i:s');
        $taskUpdate = "UPDATE task_status SET status = 1, enter_date = '" . $enter_date . "', step_cron = 7 WHERE task_id = " . $confirm_user_task[1] . " and user_id = " . $user_id;
        $resultTask = pg_query($conn, $taskUpdate);

        $selectAllConfirmTask = "
                SELECT * FROM tasks AS t 
                INNER JOIN task_status AS ts ON t.id = ts.task_id 
                WHERE ts.task_id = " . $confirm_user_task[1] . " AND ts.status = 0";
        $resultAllConfirmTask = pg_query($conn, $selectAllConfirmTask);

        if ((!pg_num_rows($resultAllConfirmTask)) > 0) {
            $statusUpdate = "UPDATE tasks SET status = 2 WHERE id = " . $confirm_user_task[1];
            $resultStatus = pg_query($conn, $statusUpdate);
        }

        if ($resultTask == true) {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);

            $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Vazifa qabul qilindi ❗️* " . PHP_EOL . "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $menuUser
            ]);
        }

    } else if ($confirm_user_task[0] == "done") {
        $selectMessageId = "SELECT * FROM task_message_id WHERE chat_id = " . $chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0) {
            while ($row = pg_fetch_assoc($resultMessageId)) {
                $user_message_id = $row['message_id'];

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM task_message_id WHERE chat_id = " . $chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
        }

        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);

        $stepUpdate = "UPDATE task_step SET step_2 = 52 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $lastIdUpdate = "UPDATE task_last_id SET last_id = " . $confirm_user_task[1] . " WHERE chat_id = " . $chat_id;
        $resultLastId = pg_query($conn, $lastIdUpdate);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Vazifa bitganligini tasdiqlovchi fayl yoki tekst briktiring 📌*",
            'parse_mode' => 'markdown',
        ]);
    } else if ($data == "back") {
        $selectMessageId = "SELECT * FROM task_message_id WHERE chat_id = " . $chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0) {
            while ($row = pg_fetch_assoc($resultMessageId)) {
                $user_message_id = $row['message_id'];

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM task_message_id WHERE chat_id = " . $chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
        }

        $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuUser
        ]);
    } else if ($data == "home") {
        $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuUser
        ]);
    }
}

if ($step_1 == 0 and $step_2 == 52 and $text != "/start") {
    if (isset($message->photo) || isset($message->video) || isset($message->video_note) || isset($message->voice) || isset($message->audio) || isset($text)) {
        if (isset($message->photo)) {
            if (isset($message->photo[2])) {
                $file_id = $message->photo[2]->file_id;
            } else if (isset($message->photo[1])) {
                $file_id = $message->photo[1]->file_id;
            } else {
                $file_id = $message->photo[0]->file_id;
            }
            $type = "photo";
        }

        if (isset($message->video)) {
            $file_id = $message->video->file_id;
            $type = "video";
        }

        if (isset($message->video_note)) {
            $file_id = $message->video_note->file_id;
            $type = "video_note";
        }

        if (isset($message->voice)) {
            $file_id = $message->voice->file_id;
            $type = "voice";
        }

        if (isset($message->audio)) {
            $file_id = $message->audio->file_id;
            $type = "audio";
        }

        if (isset($text)) {
            $file_id = $text;
            $type = "text";
        }

        if (isset($file_id) and !empty($file_id)) {
            $selectUser = "SELECT * FROM users WHERE chat_id = " . $chat_id;
            $resultUser = pg_query($conn, $selectUser);
            if (pg_num_rows($resultUser) > 0) {
                $row = pg_fetch_assoc($resultUser);
                $user_id = $row["id"];
            }
            $addMaterial = "INSERT INTO task_user_materials (task_id,user_id,file_id,type) VALUES (" . $last_id . "," . $user_id . ",'" . $file_id . "','" . $type . "')";
            $resultMaterial = pg_query($conn, $addMaterial);

            $stepUpdate = "UPDATE task_step SET step_2 = 53 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            $fileCaption = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Ortga ↩️", 'callback_data' => 'back'],
                    ]
                ]
            ]);

            $selectFile = "SELECT * FROM task_user_materials WHERE task_id = " . $last_id . " and user_id = " . $user_id . " ORDER BY id DESC LIMIT 1";
            $resultFile = pg_query($conn, $selectFile);

            if ($resultMaterial == true and $result == true and $resultFile == true) {
                if (pg_num_rows($resultFile) > 0) {
                    $row = pg_fetch_assoc($resultFile);
                    $file_id = $row['file_id'];
                    $type = $row['type'];

                    $confirmMaterial = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Bekor qilish ❌", 'callback_data' => 'cancel'],
                                ['text' => "Tasdiqlash ✅", 'callback_data' => 'confirm'],
                            ],
                        ]
                    ]);

                    $taskDate = date('Y-m-d H:i');

                    $endDateUpdate = "UPDATE task_status SET end_date = '" . $taskDate . ":s' WHERE user_id = " . $user_id . " and task_id = " . $last_id;
                    $resultEndDate = pg_query($conn, $endDateUpdate);

                    switch ($type) {
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $file_id,
                                'caption' => "*📆 Vazifa bitgan sana:* " . $taskDate,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $file_id,
                                'caption' => "*📆 Vazifa bitgan sana:* " . $taskDate,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => "💬 | " . $file_id . PHP_EOL . "*📆 Vazifa bitgan sana:* " . $taskDate,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'voice':
                            bot('sendVoice', [
                                'chat_id' => $chat_id,
                                'voice' => $file_id,
                                'caption' => "*📆 Vazifa bitgan sana:* " . $taskDate,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'audio':
                            bot('sendAudio', [
                                'chat_id' => $chat_id,
                                'audio' => $file_id,
                                'caption' => "*📆 Vazifa bitgan sana:* " . $taskDate,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                        case 'video_note':
                            bot('sendVideoNote', [
                                'chat_id' => $chat_id,
                                'video_note' => $file_id,
                            ]);
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => "*📆 Vazifa bitgan sana:* " . $taskDate,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                            break;
                    }
                }
            }
        } else {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Ma'lumot yuklang 📥",
            ]);
        }
    }
}

if ($step_1 == 0 and $step_2 == 53 and $text != "/start") {
    if ($data == "confirm") {
        $taskUpdate = "UPDATE task_status SET status = 2 WHERE task_id = " . $last_id . " and user_id = " . $user_id;
        $resultTask = pg_query($conn, $taskUpdate);

        $stepStatusUpdate = "UPDATE task_status SET step_cron = 7 WHERE task_id = " . $last_id . " and user_id = " . $user_id;
        $resultTaskStep = pg_query($conn, $stepStatusUpdate);

        $taskUpdateAdmin = "UPDATE tasks SET status = 3 WHERE id = " . $last_id;
        $resultTaskAdmin = pg_query($conn, $taskUpdateAdmin);

        $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Vazifa yakunlandi ❗️*",
            'parse_mode' => 'markdown'
        ]);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuUser
        ]);

        $selectUsersAll = "SELECT * FROM task_status WHERE task_id = " . $last_id;
        $resultUserAll = pg_query($conn, $selectUsersAll);
        $txtSend = '';
        while ($row = pg_fetch_assoc($resultUserAll)) {
            $status = $row['status'];
            $user_id = $row['user_id'];
            $selectSecondName = "SELECT * FROM users WHERE id = " . $user_id;
            $resultSecondName = pg_query($conn, $selectSecondName);
            if (pg_num_rows($resultSecondName) > 0) {
                $row = pg_fetch_assoc($resultSecondName);
                $second_name = $row["second_name"];
            }
            if ($status == 3) {
                $txtSend = $txtSend . "*👤 | *" . $second_name . " ✅" . PHP_EOL;
            } else {
                $txtSend = $txtSend . "*👤 | *" . $second_name . " ❌" . PHP_EOL;
            }
        }

        $selectAdmin = "SELECT * FROM users WHERE type = 1 or type = 2";
        $resultAdmin = pg_query($conn, $selectAdmin);

        $confirmMaterial = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => "Vazifani ko'rish 📂", 'callback_data' => 'check_' . $last_id],
                ],
                [
                    ['text' => "Bosh menu 🏠", 'callback_data' => 'home'],
                ]
            ]
        ]);

        while ($row = pg_fetch_assoc($resultAdmin)) {
            $chat_id = $row['chat_id'];
            $stepUpdate = "UPDATE task_step SET step_2 = 11 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $stepUpdate);

            $selectTaskConfirm = "SELECT * FROM task_materials WHERE task_id = " . $last_id;
            $resultTaskConfirm = pg_query($conn, $selectTaskConfirm);
            if (pg_num_rows($resultTaskConfirm) > 0) {
                $row = pg_fetch_assoc($resultTaskConfirm);
                $file_id = $row['file_id'];
                $caption = base64_decode($row['caption']);
                $type = $row['type'];
            }
            switch ($type) {
                case 'photo':
                    bot('sendPhoto', [
                        'chat_id' => $chat_id,
                        'photo' => $file_id,
                        'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                    break;
                case 'video':
                    bot('sendVideo', [
                        'chat_id' => $chat_id,
                        'video' => $file_id,
                        'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                    break;
                case 'text':
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                    break;
                case 'voice':
                    bot('sendVoice', [
                        'chat_id' => $chat_id,
                        'voice' => $file_id,
                        'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                    break;
                case 'audio':
                    bot('sendAudio', [
                        'chat_id' => $chat_id,
                        'audio' => $file_id,
                        'caption' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                    break;
                case 'video_note':
                    bot('sendVideoNote', [
                        'chat_id' => $chat_id,
                        'video_note' => $file_id,
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $txtSend . PHP_EOL . "📃 *Tavsif:* " . $caption . PHP_EOL . $fineAndDeadLine,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                    break;
            }
        }
    } else if ($data == "cancel") {
        $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        $deleteMaterial = "DELETE FROM task_user_materials WHERE user_id = " . $user_id . " and task_id = " . $last_id;
        $resultMaterial = pg_query($conn, $deleteMaterial);


        bot('sendMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuUser
        ]);
    }
}

if ($step_1 == 0 and $step_2 == 100 and $text != "/start") {
    $confirm_user_task = explode("_", $data);
    if ($confirm_user_task[0] == "confirmTask") {
        $enter_date = date('Y-m-d H:i:s');
        $taskUpdate = "UPDATE task_status SET status = 1, enter_date = '" . $enter_date . "', step_cron = 7 WHERE task_id = " . $confirm_user_task[1] . " and user_id = " . $user_id;
        $resultTask = pg_query($conn, $taskUpdate);

        $selectAllConfirmTask = "
            SELECT * FROM tasks AS t 
            INNER JOIN task_status AS ts ON t.id = ts.task_id 
            WHERE ts.task_id = " . $confirm_user_task[1] . " AND ts.status = 0";
        $resultAllConfirmTask = pg_query($conn, $selectAllConfirmTask);

        if ((!pg_num_rows($resultAllConfirmTask)) > 0) {
            $statusUpdate = "UPDATE tasks SET status = 2 WHERE id = " . $confirm_user_task[1];
            $resultStatus = pg_query($conn, $statusUpdate);
        }

        $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        if ($resultTask == true and $result == true) {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Vazifa qabul qilindi ❗️",
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Kerakli bo'limni tanlang ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $menuUser
            ]);
        }
    } else if ($data == "home") {
        $stepUpdate = "UPDATE task_step SET step_2 = 50 WHERE chat_id = " . $chat_id;
        $result = pg_query($conn, $stepUpdate);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "*Kerakli bo'limni tanlang ⤵️*",
            'parse_mode' => 'markdown',
            'reply_markup' => $menuUser
        ]);
    } else {
        deleteMessage($chat_id, $message_id);
    }
}