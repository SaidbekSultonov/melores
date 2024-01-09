<?php
//    date_default_timezone_set('Asia/Tashkent');
//    ini_set('display_errors', true);
    // get bilan bugungi kun keladi, har kuni 1 marta
    // --------------

    $conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
    if ($conn) {
        echo "Success";
    }

    const TOKEN = '1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4';
    const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;
    const ADMIN = 1270367;
    const ADMIN_2 = 1890763284;
    const ADMIN_3 = 1409757777;
    const ADMIN_4 = 1112495028;
    const ADMIN_5 = 284914591;


    function bot($method, $data = []) {
        $url = BASE_URL.'/'.$method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);

        if(curl_error($ch))
        {
            var_dump(curl_error($ch));
        }
        else{
            return json_decode($res);
        }
    }

    $update = file_get_contents('php://input');
    $update = json_decode($update);

    if (isset($update->message)) {
        $message = $update->message;
        $message_id = $message->message_id;
        $text = $message->text;
        $chat_id = $message->chat->id;
        $name = $message->chat->first_name;
        $username = $message->chat->username;
        $audio = $message->audio->file_id;
        if(isset($message->chat->last_name)){
            $surname = $message->chat->last_name;
        }
    } else if (isset($update->callback_query)) {
        $data = $update->callback_query->data;
        $chat_id = $update->callback_query->message->chat->id;
        $message_id = $update->callback_query->message->message_id;
    }

    function deleteMessageUser($conn, $chat_id){
        $selectMessageId = "SELECT * FROM orginal_message_id WHERE chat_id = ".$chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0){
            while($row = pg_fetch_assoc($resultMessageId)){
                $user_message_id = $row['message_id'];

                bot('deleteMessage',[
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM orginal_message_id WHERE chat_id = ".$chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
        }
    }

    function insertMessageId($response,$conn, $chat_id){
        $message_id = $response->result->message_id;
        $insertMessageId = "INSERT INTO orginal_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
        $resultMessageId = pg_query($conn, $insertMessageId);
    }

    $remove_keyboard = array(
        'remove_keyboard' => true
    );
    $remove_keyboard = json_encode($remove_keyboard);

    $keyboard_admin = json_encode($keyboard = [
        'keyboard' => [
            [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
            [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
            [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
            [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
            [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
            ['🔝 Бош саҳифа'],
            ['📥 Видео юклаш']
        ],
        'resize_keyboard' => true,
    ]);

    $keyboard_user = json_encode($keyboard = [
        'keyboard' => [
            [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
            [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
            [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
            [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
            [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
            ['🔝 Бош саҳифа']
        ],
        'resize_keyboard' => true,
    ]);
    

// --- SEND VIDEO START ---

    if (date('H:i') == '16:00') {
        $updateIsSend = "UPDATE send_video SET is_send = 0";
        $resultIsSend = pg_query($conn, $updateIsSend);

        $selectUser = "SELECT * FROM send_video";
        $result = pg_query($conn, $selectUser);

        if (pg_num_rows($result) > 0) {
            $a = 1;
            $send_arr = [];
            $chat_id_arr = [];
            while ($row = pg_fetch_assoc($result)) {
                $videoId = $row['video_id'];
                $videoChatId = $row['chat_id'];
                if($videoChatId == ADMIN or $videoChatId == ADMIN_2 or $videoChatId == ADMIN_3 or $videoChatId == ADMIN_4 or $videoChatId == ADMIN_5){
                    $keyboard = $keyboard_admin;
                } else {
                    $keyboard = $keyboard_user;
                }

                if (!in_array($videoChatId, $chat_id_arr)) {
                    $chat_id_arr[] = $videoChatId;
                    $selectVideo = "SELECT * FROM video WHERE id > ".$videoId." ORDER BY id ASC LIMIT 1";
                    $resultSecond = pg_query($conn, $selectVideo);

                    if (pg_num_rows($resultSecond) > 0) {
                        $rowSecond = pg_fetch_assoc($resultSecond);
                        $fileId = $rowSecond['file_id'];
                        $caption = $rowSecond["caption"];
                        $sendId = $rowSecond['id'];
                        $type = $rowSecond['type'];

                        switch ($type) {
                            case 'photo':
                                $res = bot('sendPhoto', [
                                    'chat_id' => $videoChatId,
                                    'photo' => $fileId,
                                    'reply_markup' => $keyboard
                                ]);
                                break;
                            case 'video':
                                if (isset($caption) and !empty($caption)) {
                                    $res = bot('sendVideo', [
                                        'chat_id' => $videoChatId,
                                        'video' => $fileId,
                                        'caption' => $caption,
                                        'reply_markup' => $keyboard
                                    ]);
                                } else {
                                    $res = bot('sendVideo', [
                                        'chat_id' => $videoChatId,
                                        'video' => $fileId,
                                        'reply_markup' => $keyboard
                                    ]);
                                }
                                break;
                            case 'text':
                                $res = bot('sendMessage', [
                                    'chat_id' => $videoChatId,
                                    'text' => $fileId,
                                    'reply_markup' => $keyboard
                                ]);
                                break;
                        }

                        $responseSend = $res->ok;
                        if ($responseSend){
                            $updateSql = "UPDATE send_video SET video_id = ".$sendId." WHERE chat_id = ".$videoChatId;
                            $resultVideo = pg_query($conn, $updateSql);

                            $send_arr[] = $videoChatId;
                        }

                        $a++;
                    }
                }

                if ($a == 30){
                    $a = 1;
                    sleep(5);
                }
            }
        }
        foreach ($send_arr as $value){
            $updateSendStatus = "UPDATE send_video SET is_send = 1 WHERE chat_id = ".$value;
            $result = pg_query($conn, $updateSendStatus);
        }
    }

    // --- SEND VIDEO END ---

    if ($text == '/start' || $text == '🔝 Бош саҳифа' || $text == '🔝 Домой') {
        $sql = 'SELECT * FROM orginal_step WHERE chat_id = '.$chat_id;
        $result = pg_query($conn, $sql);

        if (pg_num_rows($result) > 0) {
            $sql = "UPDATE orginal_step SET step_1 = 0, step_2 = 0 WHERE chat_id = ".$chat_id;
            pg_query($conn, $sql);
            $sqlLastId = "UPDATE orginal_last_id SET last_id = 0 WHERE chat_id = ".$chat_id;
            pg_query($conn, $sqlLastId);
        }
        else {
            $sql = "INSERT INTO orginal_step (chat_id, name, username, step_1, step_2) VALUES ('".$chat_id."', '".$name."', '@".$username."', 0, 0)";
            pg_query($conn, $sql);

            $insertInto = "INSERT INTO orginal_last_id (chat_id, last_id) VALUES ('".$chat_id."', 0)";
            pg_query($conn, $insertInto);
        }

        $sendDb = pg_connect("host=localhost dbname=orginal_db user=postgres password=postgres");

        $sql = 'SELECT * FROM send_video WHERE chat_id = '.$chat_id;
        $resultSendVideo = pg_query($sendDb, $sql);

        if (!pg_num_rows($resultSendVideo) > 0) {
            $first_name = base64_encode($name);
            $sql = "INSERT INTO send_video (first_name, user_name, video_id, chat_id) VALUES ('".$first_name."', '@".$username."', 0, ".$chat_id.")";
            pg_query($sendDb, $sql);
        }


        $keyboard = json_encode($keyboard = [
            'keyboard' => [
                [['text' => "Ўзбек тили 🇺🇿"],['text' => 'Русский язык 🇷🇺']]
            ],
            'resize_keyboard' => true,
        ]);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => '👇 Тилни танланг'.PHP_EOL.'👇 Выберите язык',
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }

    $step_1 = 0;
    $step_2 = 0;
    $sql = "SELECT * FROM orginal_step WHERE chat_id = ".$chat_id;
    $result = pg_query($conn,$sql);
    if (pg_num_rows($result) > 0) {
        while($row = pg_fetch_assoc($result)) {
            // $admin = $row['admin'];
            $step_1 = $row['step_1'];
            $step_2 = $row['step_2'];
        }
    }

    $sql = "SELECT * FROM orginal_last_id WHERE chat_id = ".$chat_id;
    $result = pg_query($conn,$sql);
    if (pg_num_rows($result) > 0) {
        while($row = pg_fetch_assoc($result)) {
            $last_id = $row['last_id'];
        }
    }

    $ADMIN = 2791349;

    $sql = "SELECT * FROM orginal_step_katalog WHERE lang = 1";
    $result = pg_query($conn, $sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
                $row_arr_a[] = $row['id'];
                $row_arr_2[] = $row['title'];
            if ($text == $row['title'] || $data == $row['id'] || $step_2 == 7 .$row['id']) {
                $row_arr1[] = $row['id'];
                $row_arr2[] = $row['title'];
            }
        }
    }
    $sql = "SELECT * FROM orginal_lon WHERE id = 1";
    $result = pg_query($conn, $sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $row_arr[] = $row['title'];
        }
    }
    $sql = 'SELECT * FROM question_answer WHERE lang = '.$step_1;
    $result = pg_query($conn, $sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $answer[] = $row['answer'];
            $question_step[] = $row['question'];
            $category_id = $row['id'];
            if ($text == "❓ ".$row['question']) {
                $question_id[] = $row['id'];
                $question[] = $row['question'];
            }
        }
    }
    $sql = 'SELECT * FROM step_id WHERE lang = '.$step_1;
    $result = pg_query($conn, $sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $step_id[] = $row['title'];
        }
    }
    $sql = 'SELECT * FROM orginal_katalog WHERE lang = '.$step_1;
    $result = pg_query($conn, $sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $category_katalog[] = $row['category'];
        }
    }

    if ($text == "Ўзбек тили 🇺🇿" && $step_2 == 0) {
        $sql = "UPDATE orginal_step SET step_1 = 1 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
            $keyboard = json_encode($keyboard = [
                'keyboard' => [
                    [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                    [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                    [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                    [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                    [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                    ['🔝 Бош саҳифа'],
                    ['📥 Видео юклаш']
                ],
                'resize_keyboard' => true,
            ]);
        } else {
            $keyboard = json_encode($keyboard = [
                'keyboard' => [
                    [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                    [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                    [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                    [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                    [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                    ['🔝 Бош саҳифа']
                ],
                'resize_keyboard' => true,
            ]);
        }

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Асосий бўлим',
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }
    if ($text == 'Русский язык 🇷🇺' && $step_2 == 0) {

        $sql = "UPDATE orginal_step SET step_1 = 2 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $keyboard = json_encode($keyboard = [
            'keyboard' => [
                [['text' => '📚 О нас'], ['text' => '🔖 Заказ']],
                [['text' => '🌐 Социальная сеть'], ['text' => '🎁 Акции']],
                [['text' => '📖 Каталог'], ['text' => '📍 Расположение']],
                [['text' => '❓ Вопросы и ответы'], ['text' => '🖥 Оригинал мебел ТВ']],
                [['text' => '👷🏻‍♂️ Наша работа'], ['text' => '📌 Полезные советы']],
                ['🔝 Домой']
            ],
            'resize_keyboard' => true,
        ]);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Добро пожаловать в меню',
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }

    if ($text == '🔙 Орқага') {
        if ($step_2 == 4 || $step_2 == 6 || $step_2 == 7 || $step_2 == 10 || $step_2 == 101 || $step_2 == 102 || $step_2 == 103 || $step_2 == 17) {
            $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 0 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);


            if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
                $keyboard = json_encode($keyboard = [
                    'keyboard' => [
                        [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                        [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                        [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                        [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                        [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                        ['🔝 Бош саҳифа'],
                        ['📥 Видео юклаш']
                    ],
                    'resize_keyboard' => true,
                ]);
            } else {
                $keyboard = json_encode($keyboard = [
                    'keyboard' => [
                        [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                        [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                        [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                        [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                        [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                        ['🔝 Бош саҳифа']
                    ],
                    'resize_keyboard' => true,
                ]);
            }
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Асосий бўлим',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        }
        if ($step_2 >= 70 && $step_2 < 80) {
            $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 7 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);

            $keyboard = json_encode([
                'keyboard' => [
                    [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']]
                ],
                'resize_keyboard' => true,
            ]);

            if ($chat_id == $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Katalog bilan ishlash.',
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard
                ]);
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Siz agar <b>(Knopkalar bilan ishlash)</b>ni bossangiz katalog menyusi bilan ishlaysiz, \n Agar siz <b>(Ma'lumot bilan ishlash)</b>ni bosangiz unda siz katalog menyusidagi ma'lumotlar bilan ishlaysiz",
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                          [['text' => "Knopkalar bilan ishlash", 'callback_data' => "katalog"], ['text' => "Ma'lumotlar bilan ishlash", 'callback_data' => "pradukt"]],
                        ]
                    ])
                ]);
            }
            else {
                $sql = 'SELECT * FROM step_katalog';
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $row_arr[] = $row['id'];
                    }
                    if (count($row_arr) % 2 == 1) {
                        $b = 0;
                    }
                    if (count($row_arr) % 2 == 0) {
                        $b = 1;
                    }
                }
                $sql = 'SELECT * FROM step_katalog';
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    bot ('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => 'Каталоглар рўйҳати',
                        'parse_mode' => 'html',
                        'reply_markup' => $keyboard
                    ]);
                    $menu = [];
                    $arr_row1 = [];
                    $arr_row2 = [];
                    while ($row = pg_fetch_assoc($result)) {
                        $category_id = $row['id'];
                        $category_title = $row['title'];
                        $menu = ['text'=> $category_title];
                        $arr_row1[] = $menu;
                        if ($b % 2 == 0) {
                            $arr_row2[] = $arr_row1;
                            $arr_row1 = [];
                        }
                        $b++;
                    }
                    $arr_row2[] = [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']];
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => 'Каталоглар рўйҳати',
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'keyboard' => $arr_row2,
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
                else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Бу бўлимда ҳозирча маълумот йўқ.",
                        'reply_markup' => $keyboard
                    ]);
                }
            }
        }
        if ($step_2 == 41) {
            $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 4 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);

            $select_cat = "SELECT * FROM orginal_katalog WHERE status = 1 AND lang = 1";
            $result = pg_query($conn, $select_cat);
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $delete_messages_a = $row['id']."malumot_olitashlaw_aksiya";
                    $del_id = $row['id'];
                    if ($chat_id == $ADMIN) {
                        switch ($row['type']) {
                                case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages_a,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages_a,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages_a,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                            }
                    }
                    else {
                        switch ($row['type']) {
                                 case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row['caption'],
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                            }
                    }
                    if ($data == $delete_messages_a) {
                        $sql = "UPDATE orginal_katalog SET status = 0 WHERE lang = 1 AND id = ".$del_id;
                            pg_query($conn, $sql);
                         bot ('editMessageText', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => "Olibtashlandi"
                        ]);
                    }
                }
            }
            else {
                if ($chat_id != $ADMIN) {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => 'Маълумот юкланмаган',
                    ]);
                }
            }
            if ($chat_id == $ADMIN) {
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'text' => "Aksiya qo`shmoqchimisiz?",
                    'message_id' => $message_id,
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [['callback_data' => "aksiya_qoshish",'text'=> "🛒 AKSIYA QOSHISH"]]
                        ]
                    ])
                ]);
            }
        }

    }
    if ($text == '🔙 Назад') {
        if ($step_2 == 4 || $step_2 == 6 || $step_2 == 7 || $step_2 == 10 || $step_2 == 17) {
            $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 0 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);

            $keyboard = json_encode($keyboard = [
                'keyboard' => [
                    [['text' => '📚 О нас'], ['text' => '🔖 Заказ']],
                    [['text' => '🌐 Социальная сеть'], ['text' => '🎁 Акции']],
                    [['text' => '📖 Каталог'], ['text' => '📍 Расположение']],
                    [['text' => '❓ Вопросы и ответы'], ['text' => '🖥 Оригинал мебел ТВ']],
                    [['text' => '👷🏻‍♂️ Наша работа'], ['text' => '📌 Полезные советы']],
                    ['🔝 Домой']
                ],
                'resize_keyboard' => true,
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Добро пожаловать в меню',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        }
    }

    if (isset($data)) {
        $sql = "SELECT * FROM orginal_step_resurs WHERE lang = ".$step_1." AND category = ".$step_2;
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $del_id = $row['id'];
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda";
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);

                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Olibtashlandi"
                    ]);
                }
            }
        }
    }

    if ($text == '📚 Биз ҳақимизда') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 1 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 1';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $row_caption = base64_decode($row['caption']);
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN)
                {
                    switch ($row['type'])
                    {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else{
                    switch ($row['type']) {
                        case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_caption,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
            }
        }
        else {
            if ($chat_id != $ADMIN)
            {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Маълумот юкланмаган"
                ]);
            }
        }
    }
    if ($text == '📚 О нас') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 1 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 2 AND category = 1';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda_ru";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else
                {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "удален"
                    ]);
                }
            }
        }
    }

    if ($text == '🔖 Буюртма бериш') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 2 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Маълумот учун тел: 712006664'.PHP_EOL.PHP_EOL.'👨🏻‍💻 Менеджер # 1' .PHP_EOL. '📞 +99890 1686664 ' .PHP_EOL. '📲 @Original_mebel_712006664'
        ]);
    }
    if ($text == '🔖 Заказ') {
        $sql = "UPDATE orginal_step SET step_2 = 2 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Телефон для информации: 712006664' .PHP_EOL.PHP_EOL. '👨🏻‍💻 Менеджер # 1' .PHP_EOL. '📞 +99890 1686664' .PHP_EOL. '📲 @original_mebel_712006664'
        ]);
    }

    if ($text == '🌐 Ижтимоий тармоқлар') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 3 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => '📌 Web-site: www.originalmebel.uz'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Facebook: fb.com/originalmebel.uz'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Instagram: instagram.com/originalmebel.uz'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Telegram:  @originalmebel'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Youtube: youtube.com/channel/UCfPqhVBB78OgOlWYJPsIFTg'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.''.PHP_EOL
        ]);

        // $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 3';
        // $result = pg_query($conn, $sql);
        // if (pg_num_rows($result) > 0) {
        //     while ($row = pg_fetch_assoc($result)) {
        //         $row_text = base64_decode($row['file_id']);
        //         $row_caption = base64_decode($row['caption']);
        //         $del_id = $row['id'];
        //         $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda";
        //         if ($chat_id == $ADMIN) {
        //             switch ($row['type']) {
        //                     case 'text':
        //                         bot('sendMessage', [
        //                             'chat_id' => $chat_id,
        //                             'text' => $row_text,
        //                             'parse_mode' => 'html',
        //                             'reply_markup' => json_encode([
        //                             'inline_keyboard' => [
        //                                     [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
        //                                 ]
        //                             ])
        //                         ]);
        //                         break;
        //                     case 'photo':
        //                         bot('sendPhoto', [
        //                             'chat_id' => $chat_id,
        //                             'photo' => $row['file_id'],
        //                             'caption' => $row_caption,
        //                             'parse_mode' => 'html',
        //                             'reply_markup' => json_encode([
        //                             'inline_keyboard' => [
        //                                     [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
        //                                 ]
        //                             ])
        //                         ]);
        //                         break;
        //                     case 'video':
        //                         bot('sendVideo', [
        //                             'chat_id' => $chat_id,
        //                             'video' => $row['file_id'],
        //                             'caption' => $row_caption,
        //                             'parse_mode' => 'html',
        //                             'reply_markup' => json_encode([
        //                             'inline_keyboard' => [
        //                                     [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
        //                                 ]
        //                             ])
        //                         ]);
        //                         break;
        //             }
        //         }
        //         else
        //         {
        //             switch ($row['type'])
        //             {
        //                 case 'text':
        //                         bot('sendMessage', [
        //                             'chat_id' => $chat_id,
        //                             'text' => $row_text,
        //                             'parse_mode' => 'html',
        //                         ]);
        //                         break;
        //                 case 'photo':
        //                     bot('sendPhoto', [
        //                         'chat_id' => $chat_id,
        //                         'photo' => $row['file_id'],
        //                         'caption' => $row_caption,
        //                         'parse_mode' => 'html',
        //                     ]);
        //                     break;
        //                 case 'video':
        //                     bot('sendVideo', [
        //                         'chat_id' => $chat_id,
        //                         'video' => $row['file_id'],
        //                         'caption' => $row_caption,
        //                         'parse_mode' => 'html',
        //                     ]);
        //                     break;
        //             }
        //         }
        //         if ($data == $delete_messages) {
        //             $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
        //             pg_query($conn, $sql);
        //              bot ('editMessageText', [
        //                 'chat_id' => $chat_id,
        //                 'message_id' => $message_id,
        //                 'text' => "Olibtashlandi"
        //             ]);
        //         }
        //     }
        // }
    }
    if ($text == '🌐 Социальная сеть') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 3 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => '📌 Веб-сайт: www.originalmebel.uz'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Facebook: fb.com/originalmebel.uz'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL. '📌 Instagram: instagram.com/originalmebel.uz'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Телеграмма: @originalmebel'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.'📌 Youtube: youtube.com/channel/UCfPqhVBB78OgOlWYJPsIFTg'.PHP_EOL.'➖➖➖➖➖'.PHP_EOL.PHP_EOL
        ]);
        // $sql = "SELECT * FROM orginal_step_resurs WHERE lang = 2 AND category = 3";
        // $result = pg_query($conn, $sql);
        // if (pg_num_rows($result) > 0) {
        //     while ($row = pg_fetch_assoc($result)) {
        //         $row_text = base64_decode($row['file_id']);
        //         $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda_ru";
        //         $del_id = $row['id'];
        //         if ($chat_id == $ADMIN) {
        //             switch ($row['type']) {
        //                 case 'text':
        //                     bot('sendMessage', [
        //                         'chat_id' => $chat_id,
        //                         'text' => $row_text,
        //                         'parse_mode' => 'html',
        //                         'reply_markup' => json_encode([
        //                             'inline_keyboard' => [
        //                                 [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
        //                             ]
        //                         ])
        //                     ]);
        //                     break;
        //                 case 'photo':
        //                     bot('sendPhoto', [
        //                         'chat_id' => $chat_id,
        //                         'photo' => $row['file_id'],
        //                         'caption' => $row['caption'],
        //                         'parse_mode' => 'html',
        //                         'reply_markup' => json_encode([
        //                             'inline_keyboard' => [
        //                                 [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
        //                             ]
        //                         ])
        //                     ]);
        //                     break;
        //                 case 'video':
        //                     bot('sendVideo', [
        //                         'chat_id' => $chat_id,
        //                         'video' => $row['file_id'],
        //                         'caption' => $row['caption'],
        //                         'parse_mode' => 'html',
        //                         'reply_markup' => json_encode([
        //                             'inline_keyboard' => [
        //                                 [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
        //                             ]
        //                         ])
        //                     ]);
        //                     break;
        //             }
        //         }
        //         else
        //         {
        //             switch ($row['type']) {
        //                 case 'text':
        //                     bot('sendMessage', [
        //                         'chat_id' => $chat_id,
        //                         'text' => $row_text,
        //                         'parse_mode' => 'html',
        //                     ]);
        //                     break;
        //                 case 'photo':
        //                     bot('sendPhoto', [
        //                         'chat_id' => $chat_id,
        //                         'photo' => $row['file_id'],
        //                         'caption' => $row['caption'],
        //                         'parse_mode' => 'html',
        //                     ]);
        //                     break;
        //                 case 'video':
        //                     bot('sendVideo', [
        //                         'chat_id' => $chat_id,
        //                         'video' => $row['file_id'],
        //                         'caption' => $row['caption'],
        //                         'parse_mode' => 'html',
        //                     ]);
        //                     break;
        //             }
        //         }
        //         if ($data == $delete_messages) {
        //             $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
        //             pg_query($conn, $sql);
        //              bot ('editMessageText', [
        //                 'chat_id' => $chat_id,
        //                 'message_id' => $message_id,
        //                 'text' => "удален"
        //             ]);
        //         }
        //     }
        // }
    }

    if ($text == '🎁 Акциялар') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 4 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

         $keyboard = json_encode([
            'keyboard' => [
                [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']]
            ],
            'resize_keyboard' => true,
        ]);

        $select_cat = "SELECT * FROM orginal_katalog WHERE status = 1 AND lang = 1";
        $result = pg_query($conn, $select_cat);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $delete_messages_a = $row['id']."malumot_olitashlaw_aksiya";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN)
                {
                    switch ($row['type'])
                    {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                        [['callback_data' => $delete_messages_a,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                        [['callback_data' => $delete_messages_a,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                        [['callback_data' => $delete_messages_a,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else
                {
                    switch ($row['type'])
                    {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages_a) {
                    $sql = "UPDATE orginal_katalog SET status = 0 WHERE lang = 1 AND id = ".$del_id;
                        pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Olibtashlandi"
                    ]);
                }
            }
        }
        else
        {
            if ($chat_id != $ADMIN)
            {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Маълумот юкланмаган',
                    'reply_markup' => $keyboard
                ]);
            }
        }

        if ($chat_id == $ADMIN) {
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Aksiya qoshing",
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Aksiya qoshmoqchimisiz?!",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['callback_data' => "aksiya_qoshish",'text'=> "🛒 AKSIYA QOSHISH"]]
                    ]
                ])
            ]);
        }
    }
    if ($data == 'aksiya_qoshish') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 41 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM step_katalog';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_arr[] = $row['id'];
            }
            if (count($row_arr) % 2 == 1) {
                $b = 0;
            }
            if (count($row_arr) % 2 == 0) {
                $b = 1;
            }
            $sql = 'SELECT * FROM step_katalog';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                $menu = [];
                $arr_row1 = [];
                $arr_row2 = [];
                while ($row = pg_fetch_assoc($result)) {
                    $category_id = $row['id'];
                    $category_title = base64_decode($row['title']);
                    $menu = ['callback_data' => $category_id,'text'=> $category_title];
                    $arr_row1[] = $menu;
                    if ($b % 2 == 0) {
                        $arr_row2[] = $arr_row1;
                        $arr_row1 = [];
                    }
                    $b++;
                }
                bot('editMessageText', [
                    'chat_id' => $chat_id,
                    'text' => 'Aksiyaga qoshiladigan mebellar.',
                    'message_id' => $message_id,
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $arr_row2
                    ])
                ]);
            }
        }
    }
    if (isset($data) && $step_2 == 41 && $step_1 == 1) {
        for ($i = 0; $i < count($row_arr_a); $i++){
            if ($data == $row_arr_a[$i] && $step_2 == 41) {
                $sql = 'SELECT * FROM orginal_katalog WHERE lang = 1 AND category = '.$row_arr_a[$i].' AND status = 0';
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $delete_messages = $row['id']."malumot_olitashlaw_aksiya_data_1";
                        $del_id = $row['id'];
                        if ($chat_id == $ADMIN) {
                            switch ($row['type']) {
                                case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                            }
                        }
                        else {
                            switch ($row['type']) {
                                case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                            ]
                                        ])
                                    ]);
                                break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row['caption'],
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                            }
                        }
                    }
                }
                else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Ma`lumot qolmagan"
                    ]);
                }
            }
            $sql = 'SELECT * FROM orginal_katalog WHERE lang = 1 AND category = '.$row_arr_a[$i].' AND status = 0';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $delete_messages = $row['id']."malumot_olitashlaw_aksiya_data_1";
                    $del_id = $row['id'];
                    if ($data == $delete_messages) {
                        if ($chat_id == $ADMIN) {
                            switch ($row['type']) {
                                    case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                    case 'photo':
                                        bot('sendPhoto', [
                                            'chat_id' => $chat_id,
                                            'photo' => $row['file_id'],
                                            'caption' => $row['caption'],
                                            'parse_mode' => 'html',
                                            'reply_markup' => json_encode([
                                            'inline_keyboard' => [
                                                    [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                                ]
                                            ])
                                        ]);
                                        break;
                                    case 'video':
                                        bot('sendVideo', [
                                            'chat_id' => $chat_id,
                                            'video' => $row['file_id'],
                                            'caption' => $row['caption'],
                                            'parse_mode' => 'html',
                                            'reply_markup' => json_encode([
                                            'inline_keyboard' => [
                                                    [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                ]
                                            ])
                                        ]);
                                        break;
                                }
                        }
                        else {
                            switch ($row['type']) {
                                    case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row['caption'],
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                    case 'photo':
                                        bot('sendPhoto', [
                                            'chat_id' => $chat_id,
                                            'photo' => $row['file_id'],
                                            'caption' => $row['caption'],
                                            'parse_mode' => 'html',
                                        ]);
                                        break;
                                    case 'video':
                                        bot('sendVideo', [
                                            'chat_id' => $chat_id,
                                            'video' => $row['file_id'],
                                            'caption' => $row['caption'],
                                            'parse_mode' => 'html',
                                        ]);
                                        break;
                                }
                        }
                        $sql = "UPDATE orginal_katalog SET status = 1 WHERE lang = 1 AND id = ".$del_id;
                        pg_query($conn, $sql);
                        bot ('editMessageText', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => 'Ma`lumot qo`shildi'
                        ]);
                    }
                }
            }
        }
    }

    if ($text == '🎁 Акции') {
        $step_2 = 4;
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 4 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $select_cat = "SELECT * FROM orginal_katalog WHERE status = 1 AND lang = 2";
        $result = pg_query($conn, $select_cat);

        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $delete_messages_a = $row['id']."malumot_olitashlaw_aksiya";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                            case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row['caption'],
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages_a,'text'=> "📤 удаляет"]]
                                        ]
                                    ])
                                ]);
                                break;
                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $row['file_id'],
                                    'caption' => $row['caption'],
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages_a,'text'=> "📤 удаляет"]]
                                        ]
                                    ])
                                ]);
                                break;
                            case 'video':
                                bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $row['file_id'],
                                    'caption' => $row['caption'],
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages_a,'text'=> "📤 удаляет"]]
                                        ]
                                    ])
                                ]);
                                break;
                        }
                }
                else {
                    switch ($row['type']) {
                             case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row['caption'],
                                    'parse_mode' => 'html',
                                ]);
                                break;
                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $row['file_id'],
                                    'caption' => $row['caption'],
                                    'parse_mode' => 'html',
                                ]);
                                break;
                            case 'video':
                                bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $row['file_id'],
                                    'caption' => $row['caption'],
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        }
                }
                if ($data == $delete_messages_a) {
                    $sql = "UPDATE orginal_katalog SET status = 0 WHERE lang = 2 AND id = ".$del_id;
                        pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "удален"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Данные не загружены',
                ]);
            }
        }

        if ($chat_id == $ADMIN) {
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Акции",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['callback_data' => "aksiya_qoshish_ru",'text'=> "🛒 АКСИЙА ДОБАВИТЬ"]]
                    ]
                ])
            ]);
        }
    }
    if ($data == 'aksiya_qoshish_ru') {
        $select_cat = "SELECT * FROM orginal_katalog WHERE lang = 2";
        $result = pg_query($conn, $select_cat);
        $collect_tit_1 = [];
        if(pg_num_rows($result) > 0)  {
            while($row = pg_fetch_assoc($result)) {
                $mebel_id = $row['id'];
                $decodeText = $row['caption'];
                $collect_tit_1[] = [['callback_data' => "$mebel_id",'text'=> "$decodeText"]];
            }
            bot ('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => 'Вот вам меню каталога',
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                      [['text' => "Кухонная мебель", 'callback_data' => "2oshxona_mebeli"]],
                      [['text' => "Офисная мебель", 'callback_data' => "2ofis_mebeli"], ['text' => "Мебель для спальни", 'callback_data' => "2yotohona_mebellari"]],
                      [['text' => "Мягкая мебель", 'callback_data' => "2yumshoq_mebellari"], ['text' => "Материал или кровать", 'callback_data' => "2material_yoki_toshak"]],
                      [['text' => "Детская мебель", 'callback_data' => "2bolalar_mebellari"], ['text' => "ТВ шкаф", 'callback_data' => "2tv_jovoni"]],
                      [['text' => "Журнал таблицы", 'callback_data' => "2jadval_jurnali"], ['text' => "Шкафы", 'callback_data' => "2shkaflar"]],
                    ]
                ])
            ]);
        }
    }
    if (isset($data) && $step_2 == 41 && $step_1 == 2) {
        for ($i = 0; $i < count($row_arr_a); $i++){
            if ($data == $row_arr_a[$i] && $step_2 == 4) {
                $sql = 'SELECT * FROM orginal_katalog WHERE lang = 2 AND category = '.$row_arr_a[$i].' AND status = 0';
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $delete_messages = $row['id']."malumot_olitashlaw_aksiya_data_1";
                        $del_id = $row['id'];
                        if ($chat_id == $ADMIN) {
                            switch ($row['type']) {
                                        case 'text':
                                        bot('sendMessage', [
                                            'chat_id' => $chat_id,
                                            'text' => $row['caption'],
                                            'parse_mode' => 'html',
                                            'reply_markup' => json_encode([
                                            'inline_keyboard' => [
                                                    [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                ]
                                            ])
                                        ]);
                                        break;
                                        case 'photo':
                                            bot('sendPhoto', [
                                                'chat_id' => $chat_id,
                                                'photo' => $row['file_id'],
                                                'caption' => $row['caption'],
                                                'parse_mode' => 'html',
                                                'reply_markup' => json_encode([
                                                'inline_keyboard' => [
                                                        [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                    ]
                                                ])
                                            ]);
                                            break;
                                        case 'video':
                                            bot('sendVideo', [
                                                'chat_id' => $chat_id,
                                                'video' => $row['file_id'],
                                                'caption' => $row['caption'],
                                                'parse_mode' => 'html',
                                                'reply_markup' => json_encode([
                                                'inline_keyboard' => [
                                                        [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                    ]
                                                ])
                                            ]);
                                            break;
                                    }
                        }
                        else {
                            switch ($row['type']) {
                                        case 'text':
                                        bot('sendMessage', [
                                            'chat_id' => $chat_id,
                                            'text' => $row['caption'],
                                            'parse_mode' => 'html',
                                            'reply_markup' => json_encode([
                                            'inline_keyboard' => [
                                                    [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                ]
                                            ])
                                        ]);
                                        break;
                                        case 'photo':
                                            bot('sendPhoto', [
                                                'chat_id' => $chat_id,
                                                'photo' => $row['file_id'],
                                                'caption' => $row['caption'],
                                                'parse_mode' => 'html',
                                            ]);
                                            break;
                                        case 'video':
                                            bot('sendVideo', [
                                                'chat_id' => $chat_id,
                                                'video' => $row['file_id'],
                                                'caption' => $row['caption'],
                                                'parse_mode' => 'html',
                                            ]);
                                            break;
                                    }
                        }
                    }
                }
                else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Ma`lumot qolmagan"
                    ]);
                }
            }
            $sql = 'SELECT * FROM orginal_katalog WHERE lang = 2 AND category = '.$row_arr_a[$i].' AND status = 0';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $delete_messages = $row['id']."malumot_olitashlaw_aksiya_data_1";
                        $del_id = $row['id'];
                        if ($data == $delete_messages) {
                                    if ($chat_id == $ADMIN) {
                                        switch ($row['type']) {
                                                case 'text':
                                                bot('sendMessage', [
                                                    'chat_id' => $chat_id,
                                                    'text' => $row['caption'],
                                                    'parse_mode' => 'html',
                                                    'reply_markup' => json_encode([
                                                    'inline_keyboard' => [
                                                            [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                        ]
                                                    ])
                                                ]);
                                                break;
                                                case 'photo':
                                                    bot('sendPhoto', [
                                                        'chat_id' => $chat_id,
                                                        'photo' => $row['file_id'],
                                                        'caption' => $row['caption'],
                                                        'parse_mode' => 'html',
                                                        'reply_markup' => json_encode([
                                                        'inline_keyboard' => [
                                                                [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                                            ]
                                                        ])
                                                    ]);
                                                    break;
                                                case 'video':
                                                    bot('sendVideo', [
                                                        'chat_id' => $chat_id,
                                                        'video' => $row['file_id'],
                                                        'caption' => $row['caption'],
                                                        'parse_mode' => 'html',
                                                        'reply_markup' => json_encode([
                                                        'inline_keyboard' => [
                                                                [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                            ]
                                                        ])
                                                    ]);
                                                    break;
                                            }
                                    }
                                    else {
                                        switch ($row['type']) {
                                                case 'text':
                                                bot('sendMessage', [
                                                    'chat_id' => $chat_id,
                                                    'text' => $row['caption'],
                                                    'parse_mode' => 'html',
                                                    'reply_markup' => json_encode([
                                                    'inline_keyboard' => [
                                                            [['callback_data' => $delete_messages,'text'=> "MALUMOT QOSHISH"]]
                                                        ]
                                                    ])
                                                ]);
                                                break;
                                                case 'photo':
                                                    bot('sendPhoto', [
                                                        'chat_id' => $chat_id,
                                                        'photo' => $row['file_id'],
                                                        'caption' => $row['caption'],
                                                        'parse_mode' => 'html',
                                                    ]);
                                                    break;
                                                case 'video':
                                                    bot('sendVideo', [
                                                        'chat_id' => $chat_id,
                                                        'video' => $row['file_id'],
                                                        'caption' => $row['caption'],
                                                        'parse_mode' => 'html',
                                                    ]);
                                                    break;
                                            }
                                    }
                                    $sql = "UPDATE orginal_katalog SET status = 1 WHERE lang = 2 AND id = ".$del_id;
                                    pg_query($conn, $sql);
                                    bot ('editMessageText', [
                                        'chat_id' => $chat_id,
                                        'message_id' => $message_id,
                                        'text' => 'Ma`lumot qo`shildi'
                                    ]);
                        }
                    }
                }
        }
    }

    if ($text == '👨🏻‍💻 Menejerlar') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 5 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        bot ('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Malamot uchun tel: 712006664'.PHP_EOL.'👨🏻‍💻 menejeri # 1' .PHP_EOL. '📞 +99890 1686664 ' .PHP_EOL. '📲 @Original_mebel_712006664'
        ]);
    }
    if ($text == '👨🏻‍💻 Менеджеры') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 5 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        bot ('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Телефон для информации: 712006664' .PHP_EOL. '👨🏻‍💻 Менеджер # 1' .PHP_EOL. '📞 +99890 1686664' .PHP_EOL. '📲 @original_mebel_712006664'
        ]);
    }

    if ($text == '❓ Савол ва жавоблар') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 6 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

            $keyboard = json_encode([
                'keyboard' => [
                    ['❓ Ошхона мебель баландлигини хоҳлаган ўлчамда ясаб берасизларми ?'],
                    ['❓ Кўриниши қандай қулайликда жойлаштирсам бўлади ?'],
                    ['❓ Қандай материал яхшироқ ?'],
                    ['❓ Шпон ва Акрилни фарқи нимада ?'],
                    ['❓ Қайси бири сифатлироқ ?'],
                    ['❓ Акрил қандай бўлади ?'],
                    ['❓ Шпон қандай бўлади ?'],
                    ['❓ Шпон турларини фарқи нимада ?'],
                    ['❓ ЛМДФ материалидан фойдаланасизларми ёки ЛДСП данми ?'],
                    ['❓ ЛМДФ билан ЛДСП ни фарқи нимада ?'],
                    [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']]
                ],
                'resize_keyboard' => true,
            ]);
            bot ('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Саволларингизга жавоб топасиз деган умиддамиз',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
    }
    if ($text == '❓ Вопросы и ответы') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 6 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $keyboard = json_encode([
            'keyboard' => [
                ['❓ Можете ли вы сделать кухонную мебель нужного вам размера?'],
                ['❓ Насколько удобно это разместить?'],
                ['❓ Какой материал лучше?'],
                ['❓ Какая разница между шпоном и акрилом?'],
                ['❓ Какой лучше?'],
                ['❓ Как выглядит акрил?'],
                ['❓ Как шпон?'],
                ['❓ В чем разница между типами шпона?'],
                ['❓ Используют ли они материал LMDF или ЛДСП?'],
                ['❓ В чем разница между LMDF и LDSP?'],
                [['text' => '🔙 Назад'],['text' => '🔝 Домой']]

            ],
            'resize_keyboard' => true,
        ]);

        bot ('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'Надеемся, вы найдете ответы на свои вопросы',
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }

    if ($step_1 == 1 && $step_2 == 6) {
        if ($text == '❓ Ошхона мебель баландлигини хоҳлаган ўлчамда ясаб берасизларми ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Одатда бизнинг стандарт ўлчамларимиз  мебелнинг тепа қисми 70-90 см ташкил этади.

Албатта хоҳлаган ўлчамда ясаб бериш имкониятимиз мавжуд лекин бу нарсада мебелингиз жойлашуви катта аҳамият касб этади. Масалан сизга классик турдаги ошхона мебели керак ва сиз шифтгача қилмоқчисиз лекин сизнинг улчамларингиз дизайн ва қулайлик тарафлама бунга имконият бермаслиги мумкин. Шу холатларда бизнинг мутахассислар сизга бунинг яҳшироқ ечимини таклиф қилишлари мумкин.",
            ]);
        }
        if ($text == '❓ Кўриниши қандай қулайликда жойлаштирсам бўлади ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Бу масалада албатта бизга мурожат қилганингиз бу сизнинг ютуғингиздир. Бизнинг асосий фаолиятимиз сизнинг ўлчамларингизга қараб мебель ясаб беришдан иборат. Сшундай экан бу ишни мутахассисларимизга ишонинг ва биз сизга оптимал ечим таклиф қиламиз.

Бу қандай амалга оширилади?

- Жойингиз ўлчамлари олинади.

- Ўлчамлар асосида 3Д дизайн маделли ясалади ва сизга тақдим қилинади.

- Жойлашув мақул кўрилса 3Д вариант берилади.  У ерда ранглар аниқ кўришингиз мумкин бўлади.

- Ҳаммаси мақул келса шартнома тузилади.",
            ]);
        }
        if ($text == '❓ Қандай материал яхшироқ ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Ҳамма бутловчи ва фасад қисмлари ҳар хил хусусиятга эга бўлиб ҳаммасини ўзига яраша устунликлари бор. Асосан бизда АКРИЛ, ШПОН МДФ ва Табиий ёғочлардан маҳсулотлар тайёрлаймиз. Бу борада доконларимизга ташриф буюрсангиз мутахассисларимиз сизга янада чуқурроқ малумот беришади.",
            ]);
        }
        if ($text == '❓ Шпон ва Акрилни фарқи нимада ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Шпонированний МДФ  краска қилинади ва уни хоҳлаган ранг, хоҳлаган стилингизда қилишингиз мумкин.

Акрил эса тайёр панель хисобланади ва ранглар чегараланган. Дизайн масаласида эса модерн ва ҳи-теч юналишига тушади.",
            ]);
        }
        if ($text == '❓ Қайси бири сифатлироқ ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Сифат даражасидан ҳаммаси ўзига яраша хисобланади ва бу иккисини солиштириш но ўрин деб ўйлаймиз. Шундай бўлса ҳам фарқини чунтириб кетамиз.

АКРИЛ -  уст қисми акрил плёнкадан иборат. Ич қисми МДФ лик паннелдан иборат.

Шпон -  бу ёғочни сарёгдек юпқа қилиб кесилган вариант. У МДФга ёпиштирилади ва буялади.  

Иккала турни ҳам хоҳлаган вариантда ишлатсангиз бўлади. Бу уйингизни дизайнига кўпроқ боғлиқ.",
            ]);
        }
        if ($text == '❓ Акрил қандай бўлади ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "АКРИЛ -  уст қисми акрил плёнкадан иборат. Ич қисми МДФ лик паннелдан иборат.

Хозирги кунда акриллар асосан Тошкентни ўзида босилади ва Қозоғистон  ҳамда Туркиядан кириб келади.",
            ]);
        }
        if ($text == '❓ Шпон қандай бўлади ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Шпон -  бу ёғочни сарёгдак юпқа қилиб кесилган варианти . У МДФга ёпиштирилади ва бўялади.",
            ]);
        }
        if ($text == '❓ Шпон турларини фарқи нимада ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Шпон турларига туҳталадиган бўлсак дарахтларни тури қанчалик кўп бўлса шпон тури ҳам шунчалик деп айта оламиз

Асосий ишлатиладиган турлари булар ЁНҒОҚ, ДУБ, ЯСИН.

ЁНҒОҚ – асосан тўқ рангли фасадларга ишлатилинади. Табиий ранги ҳам тўқ лекин очроқ турлари ҳам мавжуд.

ДУБ –асосан оч ранглик фасадларга тўғри келади.  Илдиз йўллари зич бўлгани учун бошқа замонавий рангларда ҳам асосан дуб ишлатилинади.

ЯСИН – у ҳам худди дубга ухшаб асосан оч рангларда ишлатилинади лекин ундан фарқи илдизлари йирикроқ ва ораси очиқроқ бўлади."
            ]);
        }
        if ($text == '❓ ЛМДФ материалидан фойдаланасизларми ёки ЛДСП данми ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Ошхона мебеллари учун ЛМДФ дан фойдаланамиз. Шкафлар учун эса ЛДСП дан.",
            ]);
        }
        if ($text == '❓ ЛМДФ билан ЛДСП ни фарқи нимада ?') {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "ЛМДФ - Ўрта зичликдаги толали панель. Бу инглиз атамасининг русча версияси. Транслитерация (ўрта зичликдаги толали тахта) каби товушлар. МДФ ишлаб чиқариш қуйидагича тузилган:

Нозик бўлинган ёғоч чиплари (ёғоч чиплари) юқори босим ва ҳарорат таъсирида босилади. Боғловчи компонент сифатида фақат табиий моддалар ишлатилади: 

Лингин (ёғоч қатрони) ёки керосин.

Ишлаб чиқариш жараёнининг тавсифидан аллақачон аён бўладики, МДФнинг асосий устунлиги экологик тоза. Ва бу ҳақиқатан ҳам шундай! Иш пайтида МДФ одамлар ва ҳайвонлар учун зарарли аралашмалар чиқармайди. Бу бир вақтнинг ўзида юқори зичликка эга бардошли ёғочга асосланган мебель материалидир. Бунинг ёрдамида пластинка ўрнатиш мосламасини ишончли ушлаб туради ва тешиклар қулаб тушмайди. МДФ билан ишлов бериш осон. Ундан сиз эгилган жабҳалар ясашингиз ва ҳар қандай материал мақтана олмайдиган фигуралик панель қилишингиз мумкин.

ЛДСП - ёғоч заррачалар тахтаси, унинг учун хом ашё  ёғоч қирғичлари. формалдегид қатронлар боғловчи таркибий қисм сифатида ишлайди. Партислебоард мебелда кенг қўлланилади ва ўзини яхши томонларида намоён қилади. Албатта, агар у юқори сифат стандартларига жавоб берса бу мебель ишлаб чиқаришда ишлатиладиган энг машҳур материаллардан биридир. Дунёдаги мебелларнинг қарийб 80% ламинатланган плиталаридан ясалган. Ундан ҳам эконом класс, ҳам премиум классдаги мебеллар тайёрланади. Ламинацияланган плиталаридан ясалган мебелларга юқори талаб юқори истеъмол хусусиятлари, ёқимли ташқи кўриниши ва арзон нархлари билан изоҳланади. Ранглар ва тўқималарнинг хилма-хиллиги интерер учун мебелни ҳар қандай услуб йўналиши ва ранг схемасида ясашга имкон беради.",
            ]);
        }
    }
    if ($step_1 == 2 && $step_2 == 6) {
        if ($text == '❓ Можете ли вы сделать кухонную мебель нужного вам размера?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Обычно наши стандартные размеры составляют 70-90 см в верхней части мебели.
Конечно, у нас есть возможность сделать это в любом размере, но в этом случае расположение вашей мебели очень важно. Например, вам нужен классический тип кухонной мебели, и вы хотите сделать ее до потолка, но ваш размерный дизайн и удобство могут не допустить этого, и в этом случае наши специалисты могут предложить вам лучшее решение.",
        ]);
        }
        if ($text == '❓ Насколько удобно это разместить?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Это ваше достижение, что вы определенно связались с нами по этому вопросу. Наша основная деятельность заключается в изготовлении мебели в соответствии с вашими размерами, поэтому доверьте эту работу нашим специалистам, и мы предложим вам оптимальное решение.
Как это сделано;
Размеры вашего пространства будут приняты
На основании размеров будет создана и представлена   вам 2D модель дизайна.
Если местоположение предпочтительнее, будет предоставлена   опция 3D, где вы сможете четко видеть цвета
И если все пойдет хорошо, сделка будет заключена.",
        ]);
        }
        if ($text == '❓ Какой материал лучше?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Все компоненты и части фасада имеют разные свойства и имеют свои преимущества. В основном мы производим продукцию из ACRYL, SHPON MDF и NATURAL WOOD. Если вы посетите наши магазины, наши специалисты предоставят вам более подробную информацию.",
        ]);
        }
        if ($text == '❓ Какая разница между шпоном и акрилом?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Наносится фанерованная краска МДФ, и вы можете построить ее на любом уровне, в любом стиле.".PHP_EOL.PHP_EOL."Акрил и отделка ограничены. Современный и высокотехнологичный униал от Масаласид Китай.",
        ]);
        }
        if ($text == '❓ Какой лучше?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Исходя из уровня качества, все рассчитывается по-своему, и мы думаем, что сравнивать их - нурин, поэтому мы можем восполнить разницу.
ACRYL - верхняя часть выполнена из акриловой пленки, внутренняя часть из панели MDF
Фанера - это вариант, в котором древесина режется тонкой, как масло, приклеивается к МДФ и окрашивается.
Вы можете использовать оба типа в любом варианте, это зависит больше от дизайна вашего дома.",
        ]);
        }
        if ($text == '❓ Как выглядит акрил?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "ACRYL - верхняя часть выполнена из акриловой пленки, внутренняя часть из панели MDF
В настоящее время акрил в основном печатается в Ташкенте и импортируется из Казахстана и Турции.",
        ]);
        }
        if ($text == '❓ Как шпон?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Шпон - это вариант, в котором древесина режется тонкой, как масло, приклеивается к МДФ и окрашивается.",
        ]);
        }
        if ($text == '❓ В чем разница между типами шпона?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Если говорить о типах шпона, то можно сказать, что чем больше видов шпона, тем больше видов шпона
основной
Используемые типы: грецкий орех, дуб, ясень
ЭТИКЕТКА - в основном используется для окрашенных фасадов.
DUB - в основном для светлых фасадов. Благодаря плотной корневой системе дуб также используется в других современных цветах.
Ясин - как дуб, в основном используется в светлых тонах, но разница в том, что корни больше, а щели более четкие.",
        ]);
        }
        if ($text == '❓ Используют ли они материал LMDF или ЛДСП?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Мы используем LMDF для кухонной мебели
Для шкафов, из ЛДСП.",
        ]);
        }
        if ($text == '❓ В чем разница между LMDF и LDSP?') {
        bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "LMDF - ДВП средней плотности. Это русская версия английского термина. Звучит как транслитерация «ДВП средней плотности». Производство МДФ строится следующим образом: мелкодисперсная щепа (щепа) прессуется под воздействием высокого давления и температуры. В качестве связующего компонента используются только натуральные вещества: лингин (древесная смола) или парафин.
Из описания производственного процесса уже ясно, что основным преимуществом МДФ является экологичность. И это действительно так! Во время работы МДФ не выделяет вредных соединений людям и животным. Это прочный древесный мебельный материал с высокой плотностью одновременно. Это позволяет пластине надежно удерживать монтажное устройство, и отверстия не будут сжиматься. С МДФ легко работать. Из него вы можете сделать изогнутые фасады и сделать фигурную панель, которой не может похвастаться ни один материал.

ЛДСП - древесно-стружечная плита, сырьем для которой являются древесные скребки. формальдегидная смола действует как связующий ингредиент. ДСП широко используется в мебели и проявляет себя с хороших сторон. Конечно, если это соответствует высоким стандартам качества
это один из самых популярных материалов, используемых при изготовлении мебели. Около 80% мебели в мире изготовлено из ламинированных плит. Используется для изготовления как эконом-класса, так и мебели премиум-класса. Высокий спрос на мебель из ламинированных плит объясняется высокими потребительскими свойствами, приятным внешним видом и низкой стоимостью. Разнообразие цветов и фактур позволяет создавать мебель для интерьера в любом стилевом направлении и цветовой гамме.",
        ]);
        }
    }

    if ($step_2 == 18) {
        if (isset($message->photo[2]->file_id))
            $photo = $message->photo[2]->file_id;
        else if (isset($message->photo[1]->file_id))
            $photo = $message->photo[1]->file_id;
        else
            $photo = $message->photo[0]->file_id;

        if (isset($message->photo)) {
            $sql = "INSERT INTO katalog (file_id, caption, lang, category, type) VALUES ('".$photo."', NULL, 1, 15, 'photo')";
            $result = pg_query($conn, $sql);
        }
    }

    if ($step_2 == 17) {
        $t = base64_encode($text);
        $sql = "SELECT * FROM orginal_step_katalog where lang = ".$step_1." AND title like '%".$t."%' ";
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while($row = pg_fetch_assoc($result)) {
                $iddd = $row['id'];
            }

            if($iddd == 16)
                $iddd = 7;

            if($iddd == 17)
                $iddd = 8;

            if($iddd == 18)
                $iddd = 9;

            if($iddd == 19)
                $iddd = 10;

            if($iddd == 20)
                $iddd = 11;

            if($iddd == 21)
                $iddd = 12;

            if($iddd == 22)
                $iddd = 13;

            if($iddd == 23)
                $iddd = 14;

            if($iddd == 24)
                $iddd = 15;

            $sql = "SELECT * FROM orginal_katalog where category = ".$iddd;
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                        break;

                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                        break;

                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                        break;
                    }
                }
            }else{

            }
        }else{

        }
    }

    if($step_2 == 19)
    {
        // if(isset($message->video))
        // {
        //     $file_id = $message->video->file_id;
        //     if(isset($message->caption))
        //         $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$file_id."', 'video', 1, 12, '".base64_encode($message->caption)."')";
        //     else
        //         $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$file_id."', 'video', 1, 12, '')";

        //     // pg_query($conn, $sql);
        //     if(pg_query($conn, $sql) === TRUE)
        //     {
        //         bot ('sendMessage', [
        //             'chat_id' => 2791349,
        //             'text' => '11',
        //         ]);
        //     }else{
        //         bot ('sendMessage', [
        //             'chat_id' => 2791349,
        //             'text' => '22',
        //         ]);
        //     }
        // }

        // if(isset($message->photo))
        // {
        //     $file_id = $message->photo[2]->file_id;
        //     if(isset($message->caption))
        //         $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$file_id."', 'photo', 2, 12, '".base64_encode($message->caption)."')";
        //     else
        //         $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$file_id."', 'photo', 2, 12, '')";

        //     // pg_query($conn, $sql);
        //     if(pg_query($conn, $sql) === TRUE)
        //     {
        //         bot ('sendMessage', [
        //             'chat_id' => 2791349,
        //             'text' => '11',
        //         ]);
        //     }else{
        //         bot ('sendMessage', [
        //             'chat_id' => 2791349,
        //             'text' => '22',
        //         ]);
        //     }
        // }

        if(isset($message->text))
        {
            $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('text', 'text', 2, 1, '".base64_encode($message->text)."')";
            if(pg_query($conn, $sql) === TRUE)
            {
                bot ('sendMessage', [
                    'chat_id' => 2791349,
                    'text' => '1122',
                ]);
            }else{
                bot ('sendMessage', [
                    'chat_id' => 2791349,
                    'text' => '2233',
                ]);
            }
        }
    }

    if ($text == '📖 Каталоглар') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 17 WHERE chat_id = ".$chat_id;
        pg_query($conn, $sql);

        $keyboard = json_encode([
            'keyboard' => [
                [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']]
            ],
            'resize_keyboard' => true,
        ]);

        if ($chat_id == $ADMIN) {
            bot ('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Katalog bilan ishlash.',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
            bot ('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Siz agar <b>(Kinopkalar bilan ishlash)</b>ni bossangiz katalog menyusi bilan ishlaysiz, \n Agar siz <b>(Ma'lumot bilan ishlash)</b>ni bosangiz unda siz katalog menyusidagi ma'lumotlar bilan ishlaysiz",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                      [['text' => "Knopkalar bilan ishlash", 'callback_data' => "katalog"], ['text' => "Ma'lumotlar bilan ishlash", 'callback_data' => "pradukt"]],
                    ]
                ])
            ]);
        }
        else {
            $sql = 'SELECT * FROM orginal_step_katalog where lang = 1';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) % 2 == 1) {
                $b = 0;
            }
            if (pg_num_rows($result) % 2 == 0) {
                $b = 1;
            }
            if (pg_num_rows($result) > 0) {
                $menu = [];
                $arr_row1 = [];
                $arr_row2 = [];
                while ($row = pg_fetch_assoc($result)) {
                    $category_id = $row['id'];
                    $category_title = base64_decode($row['title']);
                    $menu = ['text'=> $category_title];
                    $arr_row1[] = $menu;
                    if ($b % 2 == 0) {
                        $arr_row2[] = $arr_row1;
                        $arr_row1 = [];
                    }
                    $b++;
                }
                $arr_row2[] = [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']];
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Каталоглар рўйҳати',
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'keyboard' => $arr_row2,
                        'resize_keyboard' => true,
                    ])
                ]);
            }
            else {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Бу бўлимда ҳозирча маълумот йўқ.",
                    'reply_markup' => $keyboard
                ]);
            }
        }
    }
    if ($data == 'katalog') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 70 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => 'Knopkalar bilan bemalol ishlay vering.'
        ]);

        $sql = 'SELECT * FROM orginal_step_katalog WHERE lang = 1';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) % 2 == 1) {
            $b = 0;
        }
        if (pg_num_rows($result) % 2 == 0) {
            $b = 1;
        }
        if (pg_num_rows($result) > 0) {
            $menu = [];
            $arr_row1 = [];
            $arr_row2 = [];
            while ($row = pg_fetch_assoc($result)) {
                $category_id_1 = $row['id']."katalog";
                $category_title = base64_decode($row['title']);
                $menu = ['callback_data' => $category_id_1,'text'=> $category_title];
                $arr_row1[] = $menu;
                if ($b % 2 == 0) {
                    $arr_row2[] = $arr_row1;
                    $arr_row1 = [];
                }
                $b++;
            }
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Mana sizga katalog menyusi',
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $arr_row2
                ])
            ]);
        }
        else {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Маълумот юкланмаган"
            ]);
        }
    }
    if ($data == 'pradukt') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 75 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => 'Katalogdagi ma`lumotlar sizga yoqadidegan umitdamiz',
        ]);

        $sql = 'SELECT * FROM orginal_step_katalog WHERE lang = 1';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) % 2 == 1) {
            $b = 0;
        }
        if (pg_num_rows($result) % 2 == 0) {
            $b = 1;
        }
        if (pg_num_rows($result) > 0) {
            $menu = [];
            $arr_row1 = [];
            $arr_row2 = [];
            while ($row = pg_fetch_assoc($result)) {
                $category_id_2 = $row['id']."pradukt";
                $category_title = base64_decode($row['title']);
                $menu = ['callback_data' => $category_id_2,'text'=> $category_title];
                $arr_row1[] = $menu;
                if ($b % 2 == 0) {
                    $arr_row2[] = $arr_row1;
                    $arr_row1 = [];
                }
                $b++;
            }
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Mana sizga katalog menyusi',
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $arr_row2
                ])
            ]);
        }
        else {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Маълумот юкланмаган"
            ]);
        }
    }
    if ($text == '📖 Каталог') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 17 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        if ($chat_id == $ADMIN) {
            $keyboard = json_encode([
                'keyboard' => [
                    [['text' => '🔙 Назад'],['text' => '🔝 Домой']]
                ],
                'resize_keyboard' => true,
            ]);
            bot ('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Надеемся, вам понравится каталог',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
            bot ('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Вот вам меню каталога',
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "Кухонная мебель", 'callback_data' => "oshxona_mebeli_ru"]],
                        [['text' => "Офисная мебель", 'callback_data' => "ofis_mebeli_ru"], ['text' => "Мебель для спальни", 'callback_data' => "yotohona_mebellari_ru"]],
                        [['text' => "Мягкая мебель", 'callback_data' => "yumshoq_mebellari_ru"], ['text' => "Материал или постельные принадлежности", 'callback_data' => "material_yoki_toshak_ru"]],
                        [['text' => "Детская мебель", 'callback_data' => "bolalar_mebellari_ru"], ['text' => "TV мебель", 'callback_data' => "tv_jovoni_ru"]],
                        [['text' => "Журнал мебель", 'callback_data' => "jadval_jurnali_ru"], ['text' => "Шкафы", 'callback_data' => "shkaflar_ru"]]
                    ]
                ])
            ]);
        }
        else
        {
            $sql = 'SELECT * FROM orginal_step_katalog where lang = 2';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) % 2 == 1) {
                $b = 0;
            }
            if (pg_num_rows($result) % 2 == 0) {
                $b = 1;
            }
            if (pg_num_rows($result) > 0) {
                $menu = [];
                $arr_row1 = [];
                $arr_row2 = [];
                while ($row = pg_fetch_assoc($result)) {
                    $category_id = $row['id'];
                    $category_title = base64_decode($row['title']);
                    $menu = ['text'=> $category_title];
                    $arr_row1[] = $menu;
                    if ($b % 2 == 0) {
                        $arr_row2[] = $arr_row1;
                        $arr_row1 = [];
                    }
                    $b++;
                }
                $arr_row2[] = [['text' => '🔙 Назад'], ['text' => '🔝 Домой']];

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Список каталогов',
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'keyboard' => $arr_row2,
                        'resize_keyboard' => true,
                    ])
                ]);
            }
            else {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "В этом разделе пока нет информации.",
                    'reply_markup' => $keyboard
                ]);
            }
        }
    }

    if ($step_2 == 7 || $step_2 >= 70 && $step_2 < 80) {
        for ($i = 0; $i < count($row_arr_a); $i++) {
            if (($step_2 == 70 || $step_2 == 72) && $text != '🔙 Орқага' && $text != '🔝 Бош саҳифа' && $text != '/start') {
                    $text = base64_encode($text);
                    $sql = "UPDATE orginal_step_katalog SET title = '".$text."' WHERE id = ".$row_arr[$i];
                    pg_query($conn, $sql);

                    $sql = 'SELECT * FROM orginal_step_katalog WHERE lang = 1';
                    $result = pg_query($conn, $sql);
                    if (pg_num_rows($result) % 2 == 1) {
                        $b = 0;
                    }
                    if (pg_num_rows($result) % 2 == 0) {
                        $b = 1;
                    }
                    if (pg_num_rows($result) > 0) {
                        $menu = [];
                        $arr_row1 = [];
                        $arr_row2 = [];
                        while ($row = pg_fetch_assoc($result)) {
                            $category_id = $row['id']."katalog";
                            $category_title = base64_decode($row['title']);
                            $menu = ['callback_data' => $category_id,'text'=> $category_title];
                            $arr_row1[] = $menu;
                            if ($b % 2 == 0) {
                                $arr_row2[] = $arr_row1;
                                $arr_row1 = [];
                            }
                            $b++;
                        }
                        bot('sendMessageText', [
                            'chat_id' => $chat_id,
                            'messgae_id' => $message_id,
                            'text' => 'Mana sizga katalog menyusi',
                            'parse_mode' => 'html',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $arr_row2
                            ])
                        ]);
                        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 71 WHERE chat_id =".$chat_id;
                        pg_query($conn, $sql);
                    }
                }
            if ($data == $row_arr_a[$i]."katalog") {
                $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 71 WHERE chat_id = ".$chat_id;
                pg_query($conn, $sql);
                $sql = "UPDATE orginal_lon SET lang = 1, title = ".$row_arr_a[$i]." WHERE id = 1";
                pg_query($conn, $sql);

                $sql = 'SELECT * FROM orginal_step_katalog WHERE lang = 1 AND id = '.$row_arr_a[$i];
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $profile_title = base64_decode($row['title']);
                        bot('editMessageText', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => "Mana sizga kinopka ma'lumoti o'zgartirsangiz bo'ladi.\n<b>".$profile_title."</b>",
                            'parse_mode' => 'html',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => [
                                    [['callback_data' => "pit_del",'text'=> "🗑 O'chirish"], ['callback_data' => "pit_change",'text'=> "🛠 O'zgartirish"]]
                                ]
                            ])
                        ]);

                    }
                }
                else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Маълумот юкланмаган",
                        'parse_mode' => 'html'
                    ]);
                }
            }
            if ($data == 'pit_change') {
                $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 72 WHERE chat_id = ".$chat_id;
                pg_query($conn, $sql);
                // bot("deleteMessage", [
                //     'chat_id' => $chat_id,
                //     'message_id' => $message_id+1,
                // ]);

                bot("editMessageText", [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ma'lumotni o'zgartirsangiz boladi."
                ]);
            }
            if ($data == 'pit_del') {
                $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 73 WHERE chat_id = ".$chat_id;
                pg_query($conn, $sql);
                bot('editMessageText', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ma'lumotni o'chirasizmi.",
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [['callback_data' => "delete",'text'=> "✅ Xa albatta"],['callback_data' => "not",'text'=> "❌ Aslo yo'q"]]
                        ]
                    ])
                ]);
            }
            // bot('sendMessage', [
            //     'chat_id' => $chat_id,
            //     'text' => $data."\n".$category_katalog[$i]
            // ]);
            if ($data == $row_arr_a[$i]."pradukt") {
                $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 76 WHERE chat_id = ".$chat_id;
                pg_query($conn, $sql);
                $sql = "UPDATE orginal_lon SET lang = 1, title = ".$row_arr_a[$i]." WHERE id = 1";
                pg_query($conn, $sql);

                $sql = "SELECT * FROM orginal_katalog WHERE lang = 1 AND category = ".$row_arr_a[$i];
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $row_caption = base64_decode($row['caption']);
                        $category = $row['category'];
                        if ($chat_id == $ADMIN) {
                            switch ($row['type']) {
                                case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row_caption,
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => "pit_del_".$category,'text'=> "🗑 O'chirish"], ['callback_data' => "pit_change_".$category,'text'=> "🛠 O'zgartirish"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row_caption,
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => "pit_del_".$category,'text'=> "🗑 O'chirish"], ['callback_data' => "pit_change_".$category,'text'=> "🛠 O'zgartirish"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row_caption,
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => "pit_del_".$category,'text'=> "🗑 O'chirish"], ['callback_data' => "pit_change_".$category,'text'=> "🛠 O'zgartirish"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                            }
                        }
                        else {
                            switch ($row['type']) {
                                case 'text':
                                        bot('sendMessage', [
                                            'chat_id' => $chat_id,
                                            'text' => $row_caption,
                                            'parse_mode' => 'html',
                                        ]);
                                        break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row_caption,
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row_caption,
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                            }
                        }
                    }
                }
                else {
                    if ($chat_id != $ADMIN) {
                        bot ('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Маълумот юкланмаган"
                        ]);
                    }
                }
            }
            if ($data == 'pit_change_'.$row_arr_a[$i] && $step_2 == 76) {
                $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 77 WHERE chat_id = ".$chat_id;
                pg_query($conn, $sql);

                bot("editMessageText", [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ma'lumotni o'zgartirsangiz boladi."
                ]);
            }
            if ($data == 'pit_del_'.$row_arr_a[$i]) {
                bot('editMessageText', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ma'lumotni o'chirasizmi.",
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [['callback_data' => "delete",'text'=> "✅ Xa albatta"],['callback_data' => "not",'text'=> "❌ Aslo yo'q"]]
                        ]
                    ])
                ]);
            }

            if ($data == 'delete') {
                $sql = 'DELETE FROM `orginal_step_katalog` WHERE id = '.$row_arr[$i];
                pg_query($conn, $sql);

                $sql = 'SELECT * FROM orginal_step_katalog WHERE lang = 1';
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) % 2 == 1) {
                    $b = 0;
                }
                if (pg_num_rows($result) % 2 == 0) {
                    $b = 1;
                }
                if (pg_num_rows($result) > 0) {
                    $menu = [];
                    $arr_row1 = [];
                    $arr_row2 = [];
                    while ($row = pg_fetch_assoc($result)) {
                        $category_id = $row['id']."katalog";
                        $category_title = base64_decode($row['title']);
                        $menu = ['callback_data' => $category_id,'text'=> $category_title];
                        $arr_row1[] = $menu;
                        if ($b % 2 == 0) {
                            $arr_row2[] = $arr_row1;
                            $arr_row1 = [];
                        }
                        $b++;
                    }
                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => 'Mana sizga katalog menyusi',
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $arr_row2
                        ])
                    ]);
                }
                else {
                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Маълумот юкланмаган"
                    ]);
                }
            }
            if ($data == 'not') {
                $sql = 'SELECT * FROM orginal_step_katalog WHERE lang = 1';
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) % 2 == 1) {
                    $b = 0;
                }
                if (pg_num_rows($result) % 2 == 0) {
                    $b = 1;
                }
                if (pg_num_rows($result) > 0) {
                    $menu = [];
                    $arr_row1 = [];
                    $arr_row2 = [];
                    while ($row = pg_fetch_assoc($result)) {
                        $category_id = $row['id']."katalog";
                        $category_title = base64_decode($row['title']);
                        $menu = ['callback_data' => $category_id,'text'=> $category_title];
                        $arr_row1[] = $menu;
                        if ($b % 2 == 0) {
                            $arr_row2[] = $arr_row1;
                            $arr_row1 = [];
                        }
                        $b++;
                    }
                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => 'Mana sizga katalog menyusi',
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $arr_row2
                        ])
                    ]);

                }
            }
        }
        $step_1 = 0;
        $step_2 = 0;
        $sql = "SELECT * FROM orginal_step WHERE chat_id = ".$chat_id;
        $result = pg_query($conn,$sql);
        if (pg_num_rows($result) > 0) {
            while($row = pg_fetch_assoc($result)) {
                // $admin = $row['admin'];
                $step_1 = $row['step_1'];
                $step_2 = $row['step_2'];
            }
        }
        if (isset($message->photo[2]->file_id))
            $photo = $message->photo[2]->file_id;
        else if (isset($message->photo[1]->file_id))
            $photo = $message->photo[1]->file_id;
        else
            $photo = $message->photo[0]->file_id;
        $caption = $message->caption;
        $video = $message->video->file_id;
        if ($step_2 == 76 && $chat_id == $ADMIN && $text != '/start' && $text != '🔙 Орқага' && $text != '🔝 Бош саҳифа' && $step_1 == 1) {
                $text = base64_encode($text);
                $caption = base64_encode($caption);
                $chek = false;
                for ($i = 0; $i < count($row_arr_a); $i++) {
                    if ($chek === false) {
                        if (isset($message->text)) {
                            $sql = "INSERT INTO katalog (caption, lang, category, type) VALUES ('".$text."', '".$step_1."', '".$row_arr[$i]."', 'text')";
                            $result = pg_query($conn, $sql);
                        }
                        if (isset($message->photo)) {
                            $sql = "INSERT INTO katalog (file_id, caption, lang, category, type) VALUES ('".$photo."', '".$caption."', '".$step_1."', '".$row_arr[$i]."', 'photo')";
                            $result = pg_query($conn, $sql);
                        }
                        if (isset($message->video)) {
                            $sql = "INSERT INTO katalog (file_id, caption, lang, category, type) VALUES ('".$video."', '".$caption."', '".$step_1."', '".$row_arr[$i]."', 'video')";
                            $result = pg_query($conn, $sql);
                        }
                    }
                }
                if ($result === true) {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Ma'lumotingiz yuklandi, marhamat yana ma`lumot kirgizing bo'ladi."
                    ]);
                }
                if ($result === false) {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Ma'lumot yuklamadingiz, ma'lumot kiriting."
                    ]);
                }
            }
    }

    if ($text == '📍 Манзил') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 8 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 8';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $row_caption = base64_decode($row['caption']);
                $del_id = $row['id'];
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda";
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                            case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                        ]
                                    ])
                                ]);
                                break;
                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $row['file_id'],
                                    'caption' => $row_caption,
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                        ]
                                    ])
                                ]);
                                break;
                            case 'video':
                                bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $row['file_id'],
                                    'caption' => $row_caption,
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                        ]
                                    ])
                                ]);
                                break;
                        }
                }
                else {
                    switch ($row['type']) {
                        case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Olibtashlandi"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Маълумот юкланмаган"
                ]);
            }
        }
    }
    if ($text == '📍 Расположение') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 8 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 2 AND category = 8';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda_ru";
                // $row_caption = base64_decode($row['caption']);
                $del_id = $row['id'];
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "удален"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'карта не загружена'
                ]);
            }
        }
    }

    if ($text == '🏭 Fabrikamiz') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 9 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
            $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 9';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $row_text = base64_decode($row['file_id']);
                    $row_caption = base64_decode($row['caption']);
                    $del_id = $row['id'];
                    $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda";
                    if ($chat_id == $ADMIN) {
                        switch ($row['type']) {
                                case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row_text,
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'photo':
                                    bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $row['file_id'],
                                        'caption' => $row_caption,
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                                case 'video':
                                    bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $row['file_id'],
                                        'caption' => $row_caption,
                                        'parse_mode' => 'html',
                                        'reply_markup' => json_encode([
                                        'inline_keyboard' => [
                                                [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                            ]
                                        ])
                                    ]);
                                    break;
                            }
                    }
                    else {
                        switch ($row['type']) {
                            case 'text':
                                    bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $row_text,
                                        'parse_mode' => 'html',
                                    ]);
                                    break;
                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $row['file_id'],
                                    'caption' => $row_caption,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                            case 'video':
                                bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $row['file_id'],
                                    'caption' => $row_caption,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        }
                    }
                    if ($data == $delete_messages) {
                        $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                        pg_query($conn, $sql);
                         bot ('editMessageText', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => "Olibtashlandi"
                        ]);
                    }
                }
            }
            else {
                if ($chat_id != $ADMIN) {
                    bot ('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Маълумот юкланмаган"
                    ]);
                }
            }
        }
    if ($text == '🏭 Наша фабрика') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 9 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 2 AND category = 9';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda_ru";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "удален"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'карта не загружена'
                ]);
            }
        }
    }

    if ($text == '🖥 Оригинал мебель ТВ да') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 10 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $keyboard = json_encode([
            'keyboard' => [
                ["MY SHOP"],
                ['SKETCH SHOU'],
                ['ABU VINES'],
                [['text' => '🔙 Орқага'],['text' => '🔝 Бош саҳифа']]
            ],
            'resize_keyboard' => true,
        ]);
        bot ('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'ORGINAL MEBEL TV',
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }
    if ($text == '🖥 Оригинал мебел ТВ') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 10 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

       $keyboard = json_encode([
            'keyboard' => [
                ["MY SHOP"],
                ['SKETCH SHOU'],
                ['ABU VINES'],
                [['text' => '🔙 Назад'],['text' => '🔝 Домой']]
            ],
            'resize_keyboard' => true,
        ]);
        bot ('sendMessage', [
            'chat_id' => $chat_id,
            'text' => 'ORGINAL MEBEL ТВ канал',
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }

    if ($step_2 == 10) {
        if ($text == "MY SHOP") {
            $sql = 'SELECT * FROM orginal_step_resurs WHERE category = 101';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0)
            {
                while ($row = pg_fetch_assoc($result))
                {
                    $row_text = base64_decode($row['file_id']);
                    $row_caption = base64_decode($row['caption']);
                    switch ($row['type'])
                    {
                        case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
            } else {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Маълумот юкланмаган'
                ]);
            }
        }

        if ($text == "SKETCH SHOU") {
            $sql = 'SELECT * FROM orginal_step_resurs WHERE category = 102';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0)
            {
                while ($row = pg_fetch_assoc($result))
                {
                    $row_text = base64_decode($row['file_id']);
                    $row_caption = base64_decode($row['caption']);
                    switch ($row['type'])
                    {
                        case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
            } else {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Маълумот юкланмаган'
                ]);
            }
        }

        if ($text == "ABU VINES") {
            $sql = 'SELECT * FROM orginal_step_resurs WHERE category = 103';
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0)
            {
                while ($row = pg_fetch_assoc($result))
                {
                    $row_text = base64_decode($row['file_id']);
                    $row_caption = base64_decode($row['caption']);
                    switch ($row['type'])
                    {
                        case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
            } else {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'Маълумот юкланмаган'
                ]);
            }
        }
    }

    if ($text == '👷🏻‍♂️ Бизнинг ишлар') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 11 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 11';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $row_caption = base64_decode($row['caption']);
                $del_id = $row['id'];
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda";
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                            case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                        ]
                                    ])
                                ]);
                                break;
                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $row['file_id'],
                                    'caption' => $row_caption,
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                        ]
                                    ])
                                ]);
                                break;
                            case 'video':
                                bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $row['file_id'],
                                    'caption' => $row_caption,
                                    'parse_mode' => 'html',
                                    'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                            [['callback_data' => $delete_messages,'text'=> "📤 MALUMOT OLIBTASHLASH"]]
                                        ]
                                    ])
                                ]);
                                break;
                        }
                }
                else {
                    switch ($row['type']) {
                        case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $row_text,
                                    'parse_mode' => 'html',
                                ]);
                                break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row_caption,
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Olibtashlandi"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Маълумот юкланмаган"
                ]);
            }
        }
    }
    if ($text == '👷🏻‍♂️ Наша работа') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 11 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 11';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $row_text = base64_decode($row['file_id']);
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda_ru";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => base64_decode($row_text),
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "удален"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'карта не загружена'
                ]);
            }
        }
    }

    if ($text == '📌 Фойдали маслаҳатлар') {
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 12 WHERE chat_id = ".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 12';
        $result = pg_query($conn, $sql);


        if (pg_num_rows($result) > 0)
        {
            while ($row = pg_fetch_assoc($result))
            {
                $row_text = base64_encode($row['file_id']);
                $row_caption = base64_decode($row['caption']);
                switch ($row['type'])
                {
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $row_text,
                            'parse_mode' => 'html',
                        ]);
                    break;

                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => $row_text,
                            'caption' => $row_caption,
                            'parse_mode' => 'html',
                        ]);
                    break;

                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $row['file_id'],
                            'caption' => $row_caption,
                            'parse_mode' => 'html',
                        ]);
                        break;
                }
            }
        } else {
            bot ('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Маълумот юкланмаган"
            ]);
        }
    }

    if ($text == '📌 Полезные советы') {
        $sql = "UPDATE orginal_step SET step_1 = 2, step_2 = 12 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);
        $sql = 'SELECT * FROM orginal_step_resurs WHERE lang = 1 AND category = 12';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0)
        {
            while ($row = pg_fetch_assoc($result))
            {
                $row_text = base64_decode($row['file_id']);
                $delete_messages = $row['id']."malumot_olitashlash_biz_haqimizda_ru";
                $del_id = $row['id'];
                if ($chat_id == $ADMIN) {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $row_text,
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => $row['caption'],
                                'parse_mode' => 'html',
                                'reply_markup' => json_encode([
                                    'inline_keyboard' => [
                                        [['callback_data' => $delete_messages,'text'=> "📤 Удалить"]]
                                    ]
                                ])
                            ]);
                            break;
                    }
                }
                else {
                    switch ($row['type']) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => base64_decode($row_text),
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $row['file_id'],
                                'caption' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $row['file_id'],
                                'caption' => base64_decode($row['caption']),
                                'parse_mode' => 'html',
                            ]);
                            break;
                    }
                }
                if ($data == $delete_messages) {
                    $sql = "DELETE FROM `orginal_step_resurs` WHERE id = ".$del_id;
                    pg_query($conn, $sql);
                     bot ('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "удален"
                    ]);
                }
            }
        }
        else {
            if ($chat_id != $ADMIN) {
                bot ('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => 'карта не загружена'
                ]);
            }
        }
    }

    if ($text == "📥 Видео юклаш"){
        $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 100 WHERE chat_id =".$chat_id;
        pg_query($conn, $sql);

        $sql = 'SELECT * FROM orginal_last_id WHERE chat_id = '.$chat_id;
        $result = pg_query($conn, $sql);

        if (pg_num_rows($result) > 0) {
            $sqlLastId = "UPDATE orginal_last_id SET last_id = 0 WHERE chat_id = ".$chat_id;
            pg_query($conn, $sqlLastId);
        }
        else {
            $insertInto = "INSERT INTO orginal_last_id (chat_id, last_id) VALUES ('".$chat_id."', 0)";
            pg_query($conn, $insertInto);
        }

        $selectVideo = "SELECT * FROM video WHERE status = 1 and TYPE = 'video' and file_id is null";
        $resultVideo = pg_query($conn, $selectVideo);

        if (pg_num_rows($resultVideo) > 0){
            $a = 1;
            $response = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "*Видео юклаш учун тавсифлардан бирини танланг ⤵️*",
                'parse_mode' => 'markdown',
                'reply_markup' => $remove_keyboard
            ]);
            insertMessageId($response, $conn, $chat_id);
            while($row = pg_fetch_assoc($resultVideo)){
                $id = $row['id'];
                $caption = $row['caption'];


                if ($a == pg_num_rows($resultVideo)){
                    $keyboard_video = json_encode([
                        'inline_keyboard' => [
                            [
                                ["text" => "📥 Видео юклаш",'callback_data' => "video_".$id],
                            ],
                            [
                                ["text" => "🔙 Ортга",'callback_data' => "back"],
                            ]
                        ]
                    ]);
                } else {
                    $keyboard_video = json_encode([
                        'inline_keyboard' => [
                            [
                                ["text" => "📥 Видео юклаш",'callback_data' => "video_".$id],
                            ]
                        ]
                    ]);
                }
                $response = bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $caption,
                    'reply_markup' => $keyboard_video
                ]);
                insertMessageId($response, $conn, $chat_id);
                $a++;
            }
        } else {
            $keyboard = json_encode($keyboard = [
                'keyboard' => [
                    ['🔙 Ортга']
                ],
                'resize_keyboard' => true,
            ]);

            $response = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => '*Маълумот йўқ ❗️*',
                'parse_mode' => 'markdown',
                'reply_markup' => $keyboard
            ]);

            insertMessageId($response, $conn, $chat_id);
        }
    }

    if ($step_1 == 1 and $step_2 == 100 and $text != "/start"){
        $explode_video = explode("_", $data);
        if ($explode_video[0] == "video"){
            deleteMessageUser($conn, $chat_id);
            $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 101 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);

            $updateLastId = "UPDATE orginal_last_id SET last_id = ".$explode_video[1]." WHERE chat_id =".$chat_id;
            pg_query($conn, $updateLastId);

            $keyboard = json_encode($keyboard = [
                'keyboard' => [
                    ['🔝 Бош саҳифа']
                ],
                'resize_keyboard' => true,
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Видео юборинг ⤵️',
                'reply_markup' => $keyboard
            ]);
        }
        else if ($data == "back" or $text == "🔙 Ортга"){
            deleteMessageUser($conn, $chat_id);
            $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 0 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);

            $keyboard = json_encode($keyboard = [
                'keyboard' => [
                    [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                    [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                    [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                    [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                    [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                    ['🔝 Бош саҳифа'],
                    ['📥 Видео юклаш']
                ],
                'resize_keyboard' => true,
            ]);
            
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Асосий бўлим',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        }
        else {
            bot('deleteMessage',[
                'cha_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }
    
    if($step_1 == 1 and $step_2 == 101 and $text != "/start"){
        if (isset($message->video)) {
            $file_id = $message->video->file_id;

            $updateFileId = "UPDATE video SET file_id = '".$file_id."' WHERE id = ".$last_id;
            $result = pg_query($conn, $updateFileId);

            $sql = "UPDATE orginal_step SET step_1 = 1, step_2 = 0 WHERE chat_id =".$chat_id;
            pg_query($conn, $sql);

            if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
                $keyboard = json_encode($keyboard = [
                    'keyboard' => [
                        [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                        [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                        [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                        [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                        [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                        ['🔝 Бош саҳифа'],
                        ['📥 Видео юклаш']
                    ],
                    'resize_keyboard' => true,
                ]);
            } else {
                $keyboard = json_encode($keyboard = [
                    'keyboard' => [
                        [['text' => '📚 Биз ҳақимизда'],['text' => '🔖 Буюртма бериш']],
                        [['text' => '🌐 Ижтимоий тармоқлар'],['text' => '🎁 Акциялар']],
                        [['text' => '📖 Каталоглар'],['text' => '📍 Манзил']],
                        [['text' => '❓ Савол ва жавоблар'],['text' => '🖥 Оригинал мебель ТВ да']],
                        [['text' => '👷🏻‍♂️ Бизнинг ишлар'],['text' => '📌 Фойдали маслаҳатлар']],
                        ['🔝 Бош саҳифа']
                    ],
                    'resize_keyboard' => true,
                ]);
            }
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Видео юкланди ✅'.PHP_EOL.'Асосий бўлим',
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }

    if ($chat_id == $ADMIN) {
        if (($text != '🔖 Буюртма бериш' && $text != '🎁 Акциялар' && $text != '👨🏻‍💻 Menejerlar' && $text != '❓ Савол ва жавоблар' && $text != '🖥 Оригинал мебель ТВ да' && $text != '📖 Каталоглар' && $text != '🔙 Орқага' && $text != '🔝 Бош саҳифа') && ($text != '🔖 Заказ' && $text != '🎁 Акции' && $text != '👨🏻‍💻 Менеджеры' && $text != '❓ Вопросы и ответы' && $text != '🖥 Оригинал мебел ТВ' && $text != '📖 Каталог'&& $text != '🔙 Назад' && $text != '🔝 Домой') && $text != '/user' && $text != '/admin' && $text != '/start' && $text != "Ўзбек тили 🇺🇿" && $text != 'Русский язык 🇷🇺' || ($step_2 >= 70 && $step_2 < 80)) {

            if (isset($message->photo[2]->file_id))
                $photo = $message->photo[2]->file_id;
            else if (isset($message->photo[1]->file_id))
                $photo = $message->photo[1]->file_id;
            else
                $photo = $message->photo[0]->file_id;
            $caption = $message->caption;
            $video = $message->video->file_id;

            $sql = "SELECT * FROM orginal_step WHERE chat_id = ".$chat_id;
            $result = pg_query($conn,$sql);
            if (pg_num_rows($result) > 0) {
                while($row = pg_fetch_assoc($result)) {
                    $step_1 = $row['step_1'];
                    $step_2 = $row['step_2'];
                }
            }

            if ($text != '📚 Биз ҳақимизда'&&  $text != '🌐 Ижтимоий тармоқлар' && $text != '📖 Каталоглар' &&  $text != '📍 Манзил'&&  $text != '🏭 Fabrikamiz'&&  $text != '👷🏻‍♂️ Бизнинг ишлар' && $text != '📌 Фойдали маслаҳатлар' && $text != '📚 О нас' && $text != '🌐 Социальная сеть' && $text != '📖 Каталог' && $text != '📍 Манзил' && $text != '🏭 Наша фабрика' && $text != '👷🏻‍♂️ Наша работа' && $text != '📌 Полезные советы' && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && isset($text)) {
                $text = base64_encode($text);
            }

            $caption = base64_encode($caption);
            if (($step_1 == 1 && $step_2 == 1) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 1) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 3) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 3) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 70) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_katalog (lang, title) VALUES (".$step_1.", '".$text."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                if ($result === false)
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 70) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_katalog (lang, title) VALUES (".$step_1.", '".$text."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                if ($result === false)
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 8) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 8) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 9) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 9) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 101) && $text != '/start'){
                if (isset($text) && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES") {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 1 && $step_2 == 102) && $text != '/start'){
                if (isset($text) && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES") {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 1 && $step_2 == 103) && $text != '/start'){
                if (isset($text) && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES") {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 2 && $step_2 == 101) && $text != '/start'){
                if (isset($message->text) && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES") {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 102) && $text != '/start'){
                if (isset($message->text) && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES") {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 103) && $text != '/start'){
                if (isset($message->text) && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES" && $text != "MY SHOP" && $text != "SKETCH SHOU" && $text != "ABU VINES") {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 11) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;

            }
            if (($step_1 == 2 && $step_2 == 11) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if (($step_1 == 1 && $step_2 == 12) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }
            if (($step_1 == 2 && $step_2 == 12) && $text != '/start'){
                if (isset($message->text)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category) VALUES ('".$text."', 'text', '".$step_1."', '".$step_2."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->photo)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$photo."','photo', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if (isset($message->video)) {
                    $sql = "INSERT INTO step_resurs (file_id, type, lang, category, caption) VALUES ('".$video."','video', '".$step_1."', '".$step_2."', '".$caption."')";
                    $result = pg_query($conn, $sql);
                }
                if ($result === true)
                    $conn_test = true;
                else
                    $conn_test = false;
            }

            if ($conn_test === false && $step_1 == 1) {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Ma'lumot yuklamadingiz, ma'lumot kiriting."
                ]);
            }
            if ($conn_test === true && $step_1 == 1) {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Ma'lumotingiz yuklandi, marhamat yana ma'lumot qo'shsangiz bo'ladi."
                ]);
            }
         if ($conn_test === false && $step_1 == 2) {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Вы не загрузили информацию, введите информацию."
                ]);
            }
            if ($conn_test === true && $step_1 == 2) {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Ваша информация загружена, введите карту еще раз."
                ]);
            }
        }
    }

?>