<?php
    $conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
    if ($conn) {
        echo "Success";
    }

    const TOKEN = '1809516633:AAEF1HkG0udnOAkqWaeHelAjcb0SUi0xGDI';
    const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;
    const ADMIN = 1270367;
    const ADMIN_2 = 1890763284;
    const ADMIN_3 = 1409757777;
    const ADMIN_4 = 1112495028;
    const ADMIN_5 = 284914591;

    function bot($method, $data = []){
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
    $message = $update->message;
    $text = $message->text;
    $chat_id = $message->chat->id;
    $message_id = $message->message_id;
    $first_name = $message->chat->first_name;
    $user_name = $message->chat->username;
    $last_name = $message->chat->last_name;
    if (isset($update->callback_query)){
        $data = $update->callback_query->data;
        $chat_id = $update->callback_query->message->chat->id;
        $message_id = $update->callback_query->message->message_id;
        $callback_id = $update->callback_query->id;
    }

    // function deleteMessageUser($conn, $chat_id){
    //     $selectMessageId = "SELECT * FROM trade_message_id WHERE chat_id = ".$chat_id;
    //     $resultMessageId = pg_query($conn, $selectMessageId);
    //     if (pg_num_rows($resultMessageId) > 0){
    //         while($row = pg_fetch_assoc($resultMessageId)){
    //             $user_message_id = $row['message_id'];

    //             bot('deleteMessage',[
    //                 'chat_id' => $chat_id,
    //                 'message_id' => $user_message_id,
    //             ]);
    //         }
    //         $deleteMessageId = "DELETE FROM trade_message_id WHERE chat_id = ".$chat_id;
    //         $resultMessageId = pg_query($conn, $deleteMessageId);
    //     }
    // }

    function insertMessageId($response,$conn, $chat_id){
        $message_id = $response->result->message_id;
        $insertMessageId = "INSERT INTO trade_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
        $resultMessageId = pg_query($conn, $insertMessageId);
    }

    function insertStatistics($section,$button_id,$chat_id,$conn){
        $selectClick = "SELECT * FROM trade_service_statistics WHERE type = '".$section."' AND button_id = ".$button_id." AND chat_id = ".$chat_id;
        $resultClick = pg_query($conn, $selectClick);

        if (pg_num_rows($resultClick) > 0){
            $row = pg_fetch_assoc($resultClick);
            $click_count = $row['click_count'] + 1;

            $updateClick = "UPDATE trade_service_statistics SET click_count = ".$click_count." WHERE type = '".$section."' AND button_id = ".$button_id." AND chat_id = ".$chat_id;
            $resultUpdateClick = pg_query($conn, $updateClick);
        } else {
            $addClickUser = "INSERT INTO trade_service_statistics (chat_id, type, button_id, click_count) VALUES (".$chat_id.", '".$section."', ".$button_id.", 1)";
            $resultAddClickUser = pg_query($conn, $addClickUser);
        }
    }

    $phone_number = json_encode([
        'keyboard' => [
            [
                ['text'=>"Telefon raqam jo'natish 📲", 'request_contact' => true]
            ],
        ],
        'resize_keyboard' => true
    ]);

    $phone_number_ru = json_encode([
        'keyboard' => [
            [
                ['text'=>"Отправить номер телефон 📲", 'request_contact' => true]
            ],
        ],
        'resize_keyboard' => true
    ]);

    $menu = json_encode([
        'inline_keyboard' => [
            [
                ['text' => "🔠 Kategoriyalar",'callback_data' => 'directions'],
                ['text' => "🤝 Biz bilan hamkorlik",'callback_data' => 'cooperation'],
            ],
            [
                ['text' => "🇷🇺 Tilni o'zgartirish",'callback_data' => 'change'],
            ],

        ]
    ]);

    $menu_ru = json_encode([
        'inline_keyboard' => [
            [
                ['text' => "🔠 Категории",'callback_data' => 'directions'],
                ['text' => "🤝 Сотрудничество с нами",'callback_data' => 'cooperation'],
            ],
            [
                ['text' => "🇺🇿 Изменить язык",'callback_data' => 'change'],
            ],

        ]
    ]);

    $menu_video = json_encode([
        'inline_keyboard' => [
            [
                ['text' => "🔠 Kategoriyalar",'callback_data' => 'directions'],
                ['text' => "🤝 Biz bilan hamkorlik",'callback_data' => 'cooperation'],
            ],
            [
                ['text' => "🇷🇺 Tilni o'zgartirish",'callback_data' => 'change'],
            ],
            [
                ['text' => "📥 Video yuklash",'callback_data' => 'video'],
            ],

        ]
    ]);

    $remove_keyboard = array(
        'remove_keyboard' => true
    );
    $remove_keyboard = json_encode($remove_keyboard);

    if ($text == "/start") {
        $checkUser = "SELECT * FROM trade_users WHERE chat_id = ".$chat_id;
        $resultCheck = pg_query($conn, $checkUser);
        if (!pg_num_rows($resultCheck) > 0) {
            $addUser = "INSERT INTO trade_users (chat_id,username) VALUES (".$chat_id.",'".$user_name."')";
            $resultAddUser = pg_query($conn, $addUser);
            $addStep = "INSERT INTO trade_step (chat_id,step_1, step_2) VALUES (".$chat_id.",0,0)";
            $resultStep = pg_query($conn, $addStep);
            $addLastId = "INSERT INTO trade_last_id (chat_id,last_id) VALUES (".$chat_id.",0)";
            $resultLastId = pg_query($conn, $addLastId);
        } else {
            $stepUpdate = "UPDATE trade_step SET step_1 = 0, step_2 = 0 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);
            $updateLastId = "UPDATE trade_last_id SET last_id = 0 WHERE chat_id = ".$chat_id;
            $resultLastId = pg_query($conn, $updateLastId);
        }

        $language = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => "🇺🇿 O'zbekcha",'callback_data' => 'uz'],
                    ['text' => "🇷🇺 Русский",'callback_data' => 'ru'],
                ],

            ]
        ]);

        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => "🇺🇿 *Assalomu alaykum, tilni tanlang*".PHP_EOL."🇷🇺 *Здравствуйте, выбрать язык*",
            'parse_mode' => 'markdown',
            'reply_markup' => $language
        ]);
    }

    $sql = "SELECT * FROM trade_step WHERE chat_id = ".$chat_id;
    $result = pg_query($conn,$sql);
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $step_1 = $row["step_1"];
        $step_2 = $row["step_2"];
    }

    $lastId = "SELECT * FROM trade_last_id WHERE chat_id = ".$chat_id;
    $result = pg_query($conn,$lastId);
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $last_id = $row["last_id"];
    }

    if ($step_1 == 0 and $step_2 == 0 and $text != "/start"){
        bot('deleteMessage',[
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
        if ($data == "uz"){
            $stepUpdate = "UPDATE trade_step SET step_1 = 1 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $text_phone_number = "*Telefon raqam jo'natish* tugmasini bosing 🔘🤏";
            $keyboard_phone_number = $phone_number;
        }
        else if ($data == "ru"){
            $stepUpdate = "UPDATE trade_step SET step_1 = 2 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $text_phone_number = "Нажмите кнопку *отправить номер телефон* 🔘🤏";
            $keyboard_phone_number = $phone_number_ru;
        }
        $response = bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => $text_phone_number,
            'parse_mode' => 'markdown',
            'reply_markup' => $keyboard_phone_number
        ]);

        insertMessageId($response, $conn, $chat_id);
    }

    if ($step_2 == 0 and $text != "/start"){
        if (isset($update->message->contact)){
            $number = $update->message->contact->phone_number;
            $number = str_replace("+","",$number);

            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
            $stepUpdate = "UPDATE trade_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $addPhoneNumber = "UPDATE trade_users SET phone_number = '".$number."' WHERE chat_id = ".$chat_id;
            $resultPhoneNumber = pg_query($conn, $addPhoneNumber);

            if ($resultPhoneNumber == true and $resultStep == true){
                if ($step_1 == 1){
                    $confirm_phone_number = "*Raqamingiz qabul qilindi ✅*";
                    $text_menu = "Kerakli bo'limni tanlang ⤵️";
                    if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
                        $keyboard_menu = $menu_video;
                    } else{
                        $keyboard_menu = $menu;
                    }
                }
                else if ($step_1 == 2){
                    $confirm_phone_number = "*Ваш номер был принят ✅*";
                    $text_menu = "Выберите нужный раздел ⤵️";
                    $keyboard_menu = $menu_ru;
                }
                $response = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => $confirm_phone_number,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $remove_keyboard
                ]);

                insertMessageId($response, $conn, $chat_id);
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => $text_menu,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $keyboard_menu
                ]);
            }
        } else {
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }

    if ($step_2 == 1 and $text != "/start"){
        if ($data == "directions"){
            $stepUpdate = "UPDATE trade_step SET step_2 = 77 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectService = "SELECT * FROM trade_category WHERE status = 1";
            $resultService = pg_query($conn, $selectService);

            if ($step_1 == 1){
                $text_section = "Asosiy kategoriyalardan birini tanlang ⤵️";
                $keyboard_back = "Ortga ↩️";
                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
            }
            else if ($step_1 == 2){
                $text_section = "Выберите одну из категорий ⤵️";
                $keyboard_back = "Назад ↩️";
                $empty_section_text = "*В этом разделе нет информации ❗️*";
            }

            if (pg_num_rows($resultService) > 0){
                $k = 1;
                while($row = pg_fetch_assoc($resultService)) {
                    $service_id = $row['id'];
                    if ($step_1 == 1){
                        $service_name = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $service_name = $row['title_ru'];
                    }
                    $arr_uz[] = ["text" => $service_name, "callback_data" => $service_id];
                    if($k%2 == 0)
                    {
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $k++;
                }

                if(count($arr_uz) == 1)
                    $row_arr[] = $arr_uz;

                $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back"]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_section,
                    'reply_markup' => $btnKey
                ]);
            } else {
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back,'callback_data' => "back"],
                        ],

                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $empty_section_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            }
        }
        else if ($data == "cooperation"){
            $stepUpdate = "UPDATE trade_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultStep == true){
                if ($step_1 == 1){
                    $keyboard_back = "Ortga ↩️";
                    $text_cooperation = "*Hamkorlik uchun ⤵️*".PHP_EOL."@Original858";
                }
                else if ($step_1 == 2){
                    $keyboard_back = "Назад ↩️";
                    $text_cooperation = "*Для сотрудничества ⤵️*".PHP_EOL."@Original858";

                }
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => $keyboard_back, "callback_data" => "back"],
                        ],
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_cooperation,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            }
        }
        else if ($data == "change"){
            if ($step_1 == 1){
                $stepUpdate = "UPDATE trade_step SET step_1 = 2 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                if ($resultStep == true){
                    $keyboard_menu = $menu_ru;
                    $text_menu = "Выберите нужный раздел ⤵️";
                }
            }
            else if ($step_1 == 2){
                $stepUpdate = "UPDATE trade_step SET step_1 = 1 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                if ($resultStep == true){
                    if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
                        $keyboard_menu = $menu_video;
                    } else{
                        $keyboard_menu = $menu;
                    }
                    $text_menu = "Kerakli bo'limni tanlang ⤵️";
                }
            }
            bot('editMessageText',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => $text_menu,
                'parse_mode' => 'markdown',
                'reply_markup' => $keyboard_menu
            ]);
        }
        else if ($data == "video"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
            $stepUpdate = "UPDATE trade_step SET step_2 = 100 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $sql = 'SELECT * FROM trade_last_id WHERE chat_id = '.$chat_id;
            $result = pg_query($conn, $sql);

            if (pg_num_rows($result) > 0) {
                $sqlLastId = "UPDATE trade_last_id SET last_id = 0 WHERE chat_id = ".$chat_id;
                pg_query($conn, $sqlLastId);
            }
            else {
                $insertInto = "INSERT INTO trade_last_id (chat_id, last_id) VALUES (".$chat_id.", 0)";
                pg_query($conn, $insertInto);
            }

            $selectVideo = "SELECT * FROM trade_company_files WHERE status = 1 and type = 'video' and file_id is null";
            $resultVideo = pg_query($conn, $selectVideo);
            if (pg_num_rows($resultVideo) > 0){
                $a = 1;
                while($row = pg_fetch_assoc($resultVideo)){
                    $id = $row['id'];
                    $caption = $row['title_uz'];

                    if ($a == pg_num_rows($resultVideo)){
                        $keyboard_video = json_encode([
                            'inline_keyboard' => [
                                [
                                    ["text" => "Video yuklash 📥",'callback_data' => "video_".$id],
                                ],
                                [
                                    ["text" => "Ortga ↩️",'callback_data' => "back"],
                                ]
                            ]
                        ]);
                    } else {
                        $keyboard_video = json_encode([
                            'inline_keyboard' => [
                                [
                                    ["text" => "Video yuklash 📥",'callback_data' => "video_".$id],
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
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Ortga ↩️",'callback_data' => 'back'],
                        ],

                    ]
                ]);

                $response = bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "*Ma'lumot yo'q ❗️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);

                insertMessageId($response, $conn, $chat_id);
            }
        }
        else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }

    if ($step_2 == 2 and $text != "/start"){
        $service = explode("_", $data);
        if ($service[0] == "service"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);

            $stepUpdate = "UPDATE trade_step SET step_2 = 6 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectDistrict = "SELECT * FROM trade_company_files WHERE services_type_id = $service[1] ORDER BY id ASC LIMIT 10";
            $resultDistrict = pg_query($conn, $selectDistrict);

            if ($step_1 == 1){
                $keyboard_back = "Ortga ↩️";
                $prevText = "⬅️ Oldingisi";
                $nextText = "➡️ Keyingisi";
                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
            }
            else if ($step_1 == 2){
                $keyboard_back = "Назад ↩️";
                $prevText = "⬅️ Предыдущий";
                $nextText = "➡️ Далее";
                $empty_section_text = "*В этом разделе нет информации ❗️*";
            }

            if (pg_num_rows($resultDistrict) > 0){
                $a = 1;
                
                $countFiles = "SELECT * FROM trade_company_files WHERE services_type_id = $service[1] ORDER BY id ASC";
                $resultCountFiles = pg_query($conn, $countFiles);
                $count = pg_num_rows($resultCountFiles);

                while($row = pg_fetch_assoc($resultDistrict)) {
                    $id = $row['id'];
                    $type = $row['type'];
                    $file_id = $row['file_id'];
                    $lat = $row['lat'];
                    $long = $row['long'];
                    if ($step_1 == 1){
                        $caption = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $caption = $row['title_ru'];
                    }

                    switch ($type) {
                        case 'photo':
                            $response = bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => $file_id,
                                'caption' => $caption,
                            ]);
                        break;
                        case 'video':
                            $response = bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => $file_id,
                                'caption' => $caption,
                            ]);
                        break;
                        case 'text':
                            $response = bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $caption,
                            ]);
                        break;
                    }
                    insertMessageId($response, $conn, $chat_id);

                    if ($count > 10) {
                        if ($a == 1) {
                            $prev = "last_".$id."_".$service[1];
                        } else {
                            $next = "next_".$id."_".$service[1];
                        }
                    }

                    $a++;
                }

                if ($count > 10) {
                    $back = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => $prevText, 'callback_data' => $prev],['text' => $nextText, 'callback_data' => $next]
                            ],
                            [
                                ['text' => $keyboard_back, 'callback_data' => 'back_service'],
                            ],
                        ]
                    ]);
                } else {
                    $back = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => $keyboard_back, 'callback_data' => 'back_service'],
                            ],
                        ]
                    ]);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Sizga dastlabki 10 mahsulot taqdim etildi!",
                    'parse_mode' => "html",
                    'reply_markup' => $back
                ]);
            } else {
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back, 'callback_data' => 'back_service'],
                        ],

                    ]
                ]);

                $response = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => $empty_section_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);

                insertMessageId($response, $conn, $chat_id);
            }
        }
        else if ($data == "back"){
            $stepUpdate = "UPDATE trade_step SET step_2 = 77 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectService = "SELECT * FROM trade_category WHERE status = 1";
            $resultService = pg_query($conn, $selectService);

            if ($step_1 == 1){
                $text_section = "Asosiy kategoriyalardan birini tanlang ⤵️";
                $keyboard_back = "Ortga ↩️";
                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
            }
            else if ($step_1 == 2){
                $text_section = "Выберите одну из категорий ⤵️";
                $keyboard_back = "Назад ↩️";
                $empty_section_text = "*В этом разделе нет информации ❗️*";
            }

            if (pg_num_rows($resultService) > 0){
                $k = 1;
                while($row = pg_fetch_assoc($resultService)) {
                    $service_id = $row['id'];
                    if ($step_1 == 1){
                        $service_name = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $service_name = $row['title_ru'];
                    }
                    $arr_uz[] = ["text" => $service_name, "callback_data" => $service_id];
                    if($k%2 == 0)
                    {
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $k++;
                }

                if(count($arr_uz) == 1)
                    $row_arr[] = $arr_uz;

                $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back"]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_section,
                    'reply_markup' => $btnKey
                ]);
            } else {
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back,'callback_data' => "back"],
                        ],

                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $empty_section_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            }
        }
    }

    // DOES NOT WORK
    if ($step_2 == 3 and $text != "/start"){
        $serviceType = explode("_", $data);
        if ($serviceType[0] == "serviceType"){
            $data_service_id = $serviceType[1];
            $data_service_type_id = $serviceType[2];

            insertStatistics('service_type', $data_service_type_id, $chat_id, $conn);

            $clickService = "SELECT * FROM trade_services_types WHERE id = ".$data_service_type_id;
            $resultClickService = pg_query($conn, $clickService);

            if ($resultClickService > 0){
                $row = pg_fetch_assoc($resultClickService);
                $click_count = $row['click_count'] + 1;
                $clickCountUpdate = "UPDATE trade_services_types SET click_count = ".$click_count." WHERE id = ".$data_service_type_id;
                $resultUpdateClickCount = pg_query($conn, $clickCountUpdate);
            }

            $stepUpdate = "UPDATE trade_step SET step_2 = 4 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectCompany = "
                        SELECT 
                               c.id, 
                               cst.services_type_id, 
                               c.title_uz, 
                               c.title_ru 
                        FROM trade_company_services_type AS cst
                        INNER JOIN trade_company AS c ON cst.company_id = c.id
                        WHERE cst.services_type_id = ".$data_service_type_id." ORDER BY c.order_column ASC";
            $resultCompany = pg_query($conn, $selectCompany);

            if ($step_1 == 1){
                $keyboard_back = "Ortga ↩️";
                $text_company_type = "Firmalar birini tanlang ⤵️";
                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
            }
            else if ($step_1 == 2){
                $keyboard_back = "Назад ↩️";
                $text_company_type = "Выберите одну из фирм ⤵️";
                $empty_section_text = "*В этом разделе нет информации ❗️*";
            }

            if (pg_num_rows($resultCompany) > 0){
                $k = 1;
                while($row = pg_fetch_assoc($resultCompany)) {
                    $company_id = $row['id'];
                    if ($step_1 == 1){
                        $company_name = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $company_name = $row['title_ru'];
                    }
                    $arr_uz[] = ["text" => $company_name, "callback_data" => "company_".$data_service_id."_".$data_service_type_id."_".$company_id];
                    if($k%2 == 0)
                    {
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $k++;
                }

                if(count($arr_uz) == 1)
                    $row_arr[] = $arr_uz;

                $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back_".$data_service_id."_".$data_service_type_id]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_company_type,
                    'reply_markup' => $btnKey
                ]);
            } else {
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back,'callback_data' => "back_".$data_service_id."_".$data_service_type_id],
                        ],

                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $empty_section_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            }
        }
        else if ($data == "back"){
            $stepUpdate = "UPDATE trade_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($stepUpdate == true){
                if ($step_1 == 1){
                    if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
                        $keyboard_menu = $menu_video;
                    } else{
                        $keyboard_menu = $menu;
                    }
                    $text_menu = "Kerakli bo'limni tanlang ⤵️";
                }
                else if ($step_1 == 2){
                    $keyboard_menu = $menu_ru;
                    $text_menu = "Выберите нужный раздел ⤵️";
                }
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_menu,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $keyboard_menu
                ]);
            }
        }
    }
    // DOES NOT WORK
    if ($step_2 == 4 and $text != "/start"){
        $company = explode("_", $data);
        if ($company[0] == "company"){
            $data_service_id = $company[1];
            $data_service_type_id = $company[2];
            $data_company_id = $company[3];

            insertStatistics('company', $data_company_id, $chat_id, $conn);

            $clickService = "SELECT * FROM trade_company WHERE id = ".$data_company_id;
            $resultClickService = pg_query($conn, $clickService);

            if ($resultClickService > 0){
                $row = pg_fetch_assoc($resultClickService);
                $click_count = $row['click_count'] + 1;
                $clickCountUpdate = "UPDATE trade_company SET click_count = ".$click_count." WHERE id = ".$data_company_id;
                $resultUpdateClickCount = pg_query($conn, $clickCountUpdate);
            }

            $stepUpdate = "UPDATE trade_step SET step_2 = 5 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectDistrict = "
                SELECT 
                       district_id AS id, 
                       td.title_uz, 
                       td.title_ru 
                FROM trade_company_files AS tcf 
                INNER JOIN trade_district AS td ON tcf.district_id = td.id
                WHERE company_id = ".$data_company_id." 
                  AND services_type_id = ".$data_service_type_id." 
                GROUP BY district_id, td.title_uz, td.title_ru";
            $resultDistrict = pg_query($conn, $selectDistrict);

            if ($step_1 == 1){
                $keyboard_back = "Ortga ↩️";
                $text_company_type = "Hududlardan birini tanlang ⤵️";
                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
            }
            else if ($step_1 == 2){
                $keyboard_back = "Назад ↩️";
                $text_company_type = "Выберите один из регионов ⤵️";
                $empty_section_text = "*В этом разделе нет информации ❗️*";
            }

            if (pg_num_rows($resultDistrict) > 0){
                $k = 1;
                while($row = pg_fetch_assoc($resultDistrict)) {
                    $district_id = $row['id'];
                    if ($step_1 == 1){
                        $district_name = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $district_name = $row['title_ru'];
                    }
                    $arr_uz[] = ["text" => $district_name, "callback_data" => "district_".$data_service_id."_".$data_service_type_id."_".$data_company_id."_".$district_id];
                    if($k%2 == 0)
                    {
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $k++;
                }

                if(count($arr_uz) == 1)
                    $row_arr[] = $arr_uz;

                $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back_".$data_service_id."_".$data_service_type_id]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_company_type,
                    'reply_markup' => $btnKey
                ]);
            } else {
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back,'callback_data' => "back_".$data_service_id."_".$data_service_type_id],
                        ],

                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $empty_section_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            }
        }
        else if ($company[0] == "back"){
            $data_service_id = $company[1];
            $stepUpdate = "UPDATE trade_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectService = "SELECT * FROM trade_services_types WHERE status = 1 AND services_id = ".$data_service_id." ORDER BY order_column ASC";
            $resultService = pg_query($conn, $selectService);

            if ($resultStep == true and pg_num_rows($resultService) > 0){
                $k = 1;
                while($row = pg_fetch_assoc($resultService)) {
                    $service_type_id = $row['id'];
                    if ($step_1 == 1){
                        $service_name = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $service_name = $row['title_ru'];
                    }
                    $arr_uz[] = ["text" => $service_name, "callback_data" => "serviceType_".$data_service_id."_".$service_type_id];
                    if($k%2 == 0)
                    {
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $k++;
                }

                if(count($arr_uz) == 1)
                    $row_arr[] = $arr_uz;

                if ($step_1 == 1){
                    $keyboard_back = "Ortga ↩️";
                    $text_company_type = "Kategoriya turlaridan birini tanlang ⤵️";
                }
                else if ($step_1 == 2){
                    $keyboard_back = "Назад ↩️";
                    $text_company_type = "Выберите один из типов категорий ⤵️";
                }

                $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back"]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_company_type,
                    'reply_markup' => $btnKey
                ]);
            }
        }
    }
    // DOES NOT WORK
    if ($step_2 == 5 and $text != "/start"){
        $district = explode("_", $data);
        if ($district[0] == "district"){
            $data_service_id = $district[1];
            $data_service_type_id = $district[2];
            $data_company_id = $district[3];
            $data_district_id = $district[4];
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);

            insertStatistics('district', $data_district_id, $chat_id, $conn);

            $clickService = "SELECT * FROM trade_district WHERE id = ".$data_district_id;
            $resultClickService = pg_query($conn, $clickService);

            if ($resultClickService > 0){
                $row = pg_fetch_assoc($resultClickService);
                $click_count = $row['click_count'] + 1;
                $clickCountUpdate = "UPDATE trade_district SET click_count = ".$click_count." WHERE id = ".$data_district_id;
                $resultUpdateClickCount = pg_query($conn, $clickCountUpdate);
            }

            $stepUpdate = "UPDATE trade_step SET step_2 = 6 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectDistrict = "
                        SELECT * 
                        FROM trade_company_files AS cf
                        WHERE cf.company_id = ".$data_company_id." 
                            AND cf.services_type_id = ".$data_service_type_id." 
                            AND cf.district_id = ".$data_district_id."
                        ORDER BY id ASC";
            $resultDistrict = pg_query($conn, $selectDistrict);

            if ($step_1 == 1){
                $keyboard_back = "Ortga ↩️";
                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
            }
            else if ($step_1 == 2){
                $keyboard_back = "Назад ↩️";
                $empty_section_text = "*В этом разделе нет информации ❗️*";
            }

            if (pg_num_rows($resultDistrict) > 0){
                $a = 1;
                $count = pg_num_rows($resultDistrict);
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back, 'callback_data' => 'back_'.$data_service_id."_".$data_service_type_id."_".$data_company_id],
                        ],

                    ]
                ]);
                while($row = pg_fetch_assoc($resultDistrict)) {
                    $type = $row['type'];
                    $file_id = $row['file_id'];
                    $lat = $row['lat'];
                    $long = $row['long'];
                    if ($step_1 == 1){
                        $caption = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $caption = $row['title_ru'];
                    }
                    if ($a == $count){
                        switch ($type) {
                            case 'photo':
                                $response = bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $file_id,
                                    'caption' => $caption,
                                    'reply_markup' => $back
                                ]);
                                break;
                            case 'video':
                                $response = bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $file_id,
                                    'caption' => $caption,
                                    'reply_markup' => $back
                                ]);
                                break;
                            case 'location':
                                $response = bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $caption,
                                ]);
                                insertMessageId($response, $conn, $chat_id);
                                $response = bot('sendLocation', [
                                    'chat_id' => $chat_id,
                                    'latitude' => $lat,
                                    'longitude' => $long,
                                    'reply_markup' => $back
                                ]);
                                break;
                            case 'text':
                                $response = bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $caption,
                                    'reply_markup' => $back
                                ]);
                                break;
                        }
                    } else {
                        switch ($type) {
                            case 'photo':
                                $response = bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => $file_id,
                                    'caption' => $caption,
                                ]);
                                break;
                            case 'video':
                                $response = bot('sendVideo', [
                                    'chat_id' => $chat_id,
                                    'video' => $file_id,
                                    'caption' => $caption,
                                ]);
                                break;
                            case 'text':
                                $response = bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $caption,
                                ]);
                                break;
                        }
                    }
                    insertMessageId($response, $conn, $chat_id);
                    $a++;
                }
            } else {
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $keyboard_back, 'callback_data' => 'back_'.$data_service_id."_".$data_service_type_id."_".$data_company_id],
                        ],

                    ]
                ]);

                $response = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => $empty_section_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);

                insertMessageId($response, $conn, $chat_id);
            }
        }
        else if ($district[0] == "back"){
            $data_service_id = $district[1];
            $data_service_type_id = $district[2];
            $stepUpdate = "UPDATE trade_step SET step_2 = 4 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectCompany = "
                        SELECT 
                               c.id, 
                               cst.services_type_id, 
                               c.title_uz, 
                               c.title_ru 
                        FROM trade_company_services_type AS cst
                        INNER JOIN trade_company AS c ON cst.company_id = c.id
                        WHERE cst.services_type_id = ".$data_service_type_id." ORDER BY c.order_column ASC";
            $resultCompany = pg_query($conn, $selectCompany);

            if ($resultStep == true and pg_num_rows($resultCompany) > 0){
                $k = 1;
                while($row = pg_fetch_assoc($resultCompany)) {
                    $company_id = $row['id'];
                    if ($step_1 == 1){
                        $company_name = $row['title_uz'];
                    }
                    else if ($step_1 == 2){
                        $company_name = $row['title_ru'];
                    }
                    $arr_uz[] = ["text" => $company_name, "callback_data" => "company_".$data_service_id."_".$data_service_type_id."_".$company_id];
                    if($k%2 == 0)
                    {
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $k++;
                }

                if(count($arr_uz) == 1)
                    $row_arr[] = $arr_uz;

                if ($step_1 == 1){
                    $keyboard_back = "Ortga ↩️";
                    $text_company_type = "Firmalar birini tanlang ⤵️";
                }
                else if ($step_1 == 2){
                    $keyboard_back = "Назад ↩️";
                    $text_company_type = "Выберите одну из фирм ⤵️";
                }

                $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back_".$data_service_id."_".$data_service_type_id]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text_company_type,
                    'reply_markup' => $btnKey
                ]);
            }
        }
    }

    if ($step_2 == 6 and $text != "/start"){
        if(isset($update->callback_query)) {
            if ($data == "back_service") {
                $isCategory = "SELECT * FROM trade_log WHERE chat_id = $chat_id";
                $resultCategory = pg_query($isCategory);
                $mow = pg_fetch_assoc($resultCategory);
                $categoryId = $mow['category_id'];

                $stepUpdate = "UPDATE trade_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                $selectService = "SELECT * FROM trade_services WHERE type = 1 AND category_id = $categoryId ORDER BY order_column ASC";
                $resultService = pg_query($conn, $selectService);

                if ($step_1 == 1){
                    $text_section = "Kategoriyalardan birini tanlang ⤵️";
                    $keyboard_back = "Ortga ↩️";
                    $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
                }
                else if ($step_1 == 2){
                    $text_section = "Выберите одну из категорий ⤵️";
                    $keyboard_back = "Назад ↩️";
                    $empty_section_text = "*В этом разделе нет информации ❗️*";
                }

                if (pg_num_rows($resultService) > 0){
                    $k = 1;
                    while($row = pg_fetch_assoc($resultService)) {
                        $service_id = $row['id'];
                        if ($step_1 == 1){
                            $service_name = $row['title_uz'];
                        }
                        else if ($step_1 == 2){
                            $service_name = $row['title_ru'];
                        }
                        $arr_uz[] = ["text" => $service_name, "callback_data" => "service_".$service_id];
                        if($k%2 == 0)
                        {
                            $row_arr[] = $arr_uz;
                            $arr_uz = [];
                        }
                        $k++;
                    }

                    if(count($arr_uz) == 1)
                        $row_arr[] = $arr_uz;

                    $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => $text_section,
                        'reply_markup' => $btnKey
                    ]);
                } else {
                    $back = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => $keyboard_back,'callback_data' => "back"],
                            ],

                        ]
                    ]);

                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => $empty_section_text,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $back
                    ]);
                }
            } else {
                $dataArr = explode("_", $data);
                $prev = "last_$dataArr[1]_$dataArr[2]";
                $next = "next_$dataArr[1]_$dataArr[2]";

                if ($step_1 == 1) {
                    $noAnyPrev = "Bundan oldingi mahsulot yo'q!";
                    $noAnyNext = "Bundan keyingi mahsulot yo'q!";
                } else {
                    $noAnyPrev = "Нет предыдущего продукта!";
                    $noAnyNext = "Товара больше нет!";
                }

                if ($data == $prev) {
                    $sql = "SELECT * FROM trade_company_files WHERE id < $dataArr[1] AND services_type_id = $dataArr[2] ORDER BY id ASC LIMIT 10";
                    $result = pg_query($conn, $sql);

                    if (pg_num_rows($result) > 0) {
                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);

                        if ($step_1 == 1){
                            $keyboard_back = "Ortga ↩️";
                            $prevText = "⬅️ Oldingisi";
                            $nextText = "➡️ Keyingisi";
                            $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
                            $fulfil_message = "Sizga navbatdagi 10 mahsulot taqdim etildi!";
                        }
                        else if ($step_1 == 2){
                            $keyboard_back = "Назад ↩️";
                            $prevText = "⬅️ Предыдущий";
                            $nextText = "➡️ Далее";
                            $empty_section_text = "*В этом разделе нет информации ❗️*";
                            $fulfil_message = "Вам были представлены следующие 10 продуктов!";
                        }

                        $a = 1;
                        $count = pg_num_rows($result);
                        while($row = pg_fetch_assoc($result)) {
                            $id = $row['id'];
                            $type = $row['type'];
                            $file_id = $row['file_id'];
                            $lat = $row['lat'];
                            $long = $row['long'];
                            if ($step_1 == 1){
                                $caption = $row['title_uz'];
                            }
                            else if ($step_1 == 2){
                                $caption = $row['title_ru'];
                            }

                            switch ($type) {
                                case 'photo':
                                    $response = bot('sendPhoto', [
                                        'chat_id' => $chat_id,
                                        'photo' => $file_id,
                                        'caption' => $caption,
                                    ]);
                                break;
                                case 'video':
                                    $response = bot('sendVideo', [
                                        'chat_id' => $chat_id,
                                        'video' => $file_id,
                                        'caption' => $caption,
                                    ]);
                                break;
                                case 'text':
                                    $response = bot('sendMessage', [
                                        'chat_id' => $chat_id,
                                        'text' => $caption,
                                    ]);
                                break;
                            }
                            insertMessageId($response, $conn, $chat_id);

                            if ($a == 1) {
                                $prev = "last_".$id."_".$dataArr[2];
                            } else {
                                $next = "next_".$id."_".$dataArr[2];
                            }

                            $a++;
                        }

                        $back = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => $prevText, 'callback_data' => $prev],['text' => $nextText, 'callback_data' => $next]
                                ],
                                [
                                    ['text' => $keyboard_back, 'callback_data' => 'back_service'],
                                ],
                            ]
                        ]);

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $fulfil_message,
                            'parse_mode' => "html",
                            'reply_markup' => $back
                        ]);
                    } else {
                        bot('answerCallbackQuery', [
                            'callback_query_id' => $callback_id,
                            'text' => $noAnyPrev
                        ]);
                    }
                } else {
                    if ($data == $next) {
                        $sql = "SELECT * FROM trade_company_files WHERE id > $dataArr[1] AND services_type_id = $dataArr[2] ORDER BY id ASC LIMIT 10";
                        $result = pg_query($conn, $sql);
                        if (pg_num_rows($result) > 0) {
                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);

                            if ($step_1 == 1){
                                $keyboard_back = "Ortga ↩️";
                                $prevText = "⬅️ Oldingisi";
                                $nextText = "➡️ Keyingisi";
                                $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
                                $fulfil_message = "Sizga navbatdagi 10 mahsulot taqdim etildi!";
                            }
                            else if ($step_1 == 2){
                                $keyboard_back = "Назад ↩️";
                                $prevText = "⬅️ Предыдущий";
                                $nextText = "➡️ Далее";
                                $empty_section_text = "*В этом разделе нет информации ❗️*";
                                $fulfil_message = "Вам были представлены следующие 10 продуктов!";
                            }

                            $a = 1;
                            $count = pg_num_rows($result);
                            while($row = pg_fetch_assoc($result)) {
                                $id = $row['id'];
                                $type = $row['type'];
                                $file_id = $row['file_id'];
                                $lat = $row['lat'];
                                $long = $row['long'];
                                if ($step_1 == 1){
                                    $caption = $row['title_uz'];
                                }
                                else if ($step_1 == 2){
                                    $caption = $row['title_ru'];
                                }

                                switch ($type) {
                                    case 'photo':
                                        $response = bot('sendPhoto', [
                                            'chat_id' => $chat_id,
                                            'photo' => $file_id,
                                            'caption' => $caption,
                                        ]);
                                    break;
                                    case 'video':
                                        $response = bot('sendVideo', [
                                            'chat_id' => $chat_id,
                                            'video' => $file_id,
                                            'caption' => $caption,
                                        ]);
                                    break;
                                    case 'text':
                                        $response = bot('sendMessage', [
                                            'chat_id' => $chat_id,
                                            'text' => $caption,
                                        ]);
                                    break;
                                }
                                insertMessageId($response, $conn, $chat_id);

                                if ($a == 1) {
                                    $prev = "last_".$id."_".$dataArr[2];
                                } else {
                                    $next = "next_".$id."_".$dataArr[2];
                                }

                                $a++;
                            }

                            $back = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => $prevText, 'callback_data' => $prev],['text' => $nextText, 'callback_data' => $next]
                                    ],
                                    [
                                        ['text' => $keyboard_back, 'callback_data' => 'back_service'],
                                    ],
                                ]
                            ]);

                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $fulfil_message,
                                'parse_mode' => "html",
                                'reply_markup' => $back
                            ]);
                        } else {
                            bot('answerCallbackQuery', [
                                'callback_query_id' => $callback_id,
                                'text' => $noAnyNext
                            ]);
                        }
                    }
                }
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }

    if ($step_2 == 77 and $text != "/start") {
        if (isset($update->callback_query)) {
            if ($data == "back"){
                $stepUpdate = "UPDATE trade_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                if ($stepUpdate == true){
                    if ($step_1 == 1){
                        if($chat_id == ADMIN or $chat_id == ADMIN_2 or $chat_id == ADMIN_3 or $chat_id == ADMIN_4 or $chat_id == ADMIN_5){
                            $keyboard_menu = $menu_video;
                        } else{
                            $keyboard_menu = $menu;
                        }
                        $text_menu = "Kerakli bo'limni tanlang ⤵️";
                    }
                    else if ($step_1 == 2){
                        $keyboard_menu = $menu_ru;
                        $text_menu = "Выберите нужный раздел ⤵️";
                    }
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => $text_menu,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $keyboard_menu
                    ]);
                }
            } else {
                $isCategory = "SELECT * FROM trade_log WHERE chat_id = $chat_id";
                $resultCategory = pg_query($isCategory);
                if (pg_num_rows($resultCategory) > 0) {
                    $userCat = "UPDATE trade_log SET category_id = $data WHERE chat_id = $chat_id";
                } else {
                    $userCat = "INSERT INTO trade_log (chat_id, category_id) VALUES ($chat_id, $data)";
                }
                pg_query($conn, $userCat);

                $stepUpdate = "UPDATE trade_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                $selectService = "SELECT * FROM trade_services WHERE type = 1 AND category_id = $data ORDER BY order_column ASC";
                $resultService = pg_query($conn, $selectService);

                if ($step_1 == 1){
                    $text_section = "Kategoriyalardan birini tanlang ⤵️";
                    $keyboard_back = "Ortga ↩️";
                    $empty_section_text = "*Bu bo'limda ma'lumot mavjud emas ❗️*";
                }
                else if ($step_1 == 2){
                    $text_section = "Выберите одну из категорий ⤵️";
                    $keyboard_back = "Назад ↩️";
                    $empty_section_text = "*В этом разделе нет информации ❗️*";
                }

                if (pg_num_rows($resultService) > 0){
                    $k = 1;
                    while($row = pg_fetch_assoc($resultService)) {
                        $service_id = $row['id'];
                        if ($step_1 == 1){
                            $service_name = $row['title_uz'];
                        }
                        else if ($step_1 == 2){
                            $service_name = $row['title_ru'];
                        }
                        $arr_uz[] = ["text" => $service_name, "callback_data" => "service_".$service_id];
                        if($k%2 == 0)
                        {
                            $row_arr[] = $arr_uz;
                            $arr_uz = [];
                        }
                        $k++;
                    }

                    if(count($arr_uz) == 1)
                        $row_arr[] = $arr_uz;

                    $row_arr[] = [["text" => $keyboard_back, "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => $text_section,
                        'reply_markup' => $btnKey
                    ]);
                } else {
                    $back = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => $keyboard_back,'callback_data' => "back"],
                            ],

                        ]
                    ]);

                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => $empty_section_text,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $back
                    ]);
                }
            }
        } else {
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }

    if ($step_2 == 100 and $text != "/start"){
        $explode_video = explode("_", $data);
        if ($explode_video[0] == "video"){
            $stepUpdate = "UPDATE trade_step SET step_2 = 101 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $updateLastId = "UPDATE trade_last_id SET last_id = ".$explode_video[1]." WHERE chat_id = ".$chat_id;
            $resultLastId = pg_query($conn, $updateLastId);

            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Ortga ↩️",'callback_data' => 'back'],
                    ],

                ]
            ]);

            $response = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Video yuboring ⤵️',
                'reply_markup' => $back
            ]);

            insertMessageId($response, $conn, $chat_id);
        }
        else if ($data == "back"){
            $stepUpdate = "UPDATE trade_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Kerakli bo'limni tanlang ⤵️",
                'reply_markup' => $menu_video
            ]);
        }
        else {
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }

    if ($step_2 == 101 and $text != "/start"){
        if (isset($message->video)) {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
            $file_id = $message->video->file_id;

            $updateFileId = "UPDATE trade_company_files SET file_id = '".$file_id."' WHERE id = ".$last_id;
            $result = pg_query($conn, $updateFileId);

            $stepUpdate = "UPDATE trade_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Video yuklandi ✅".PHP_EOL."Kerakli bo'limni tanlang ⤵️",
                'reply_markup' => $menu_video
            ]);
        }
        else if ($data == "back"){
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
            $stepUpdate = "UPDATE trade_step SET step_2 = 100 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectVideo = "SELECT * FROM trade_company_files WHERE status = 1 and type = 'video' and file_id is null";
            $resultVideo = pg_query($conn, $selectVideo);
            if (pg_num_rows($resultVideo) > 0){
                $a = 1;
                while($row = pg_fetch_assoc($resultVideo)){
                    $id = $row['id'];
                    $caption = $row['title_uz'];

                    if ($a == pg_num_rows($resultVideo)){
                        $keyboard_video = json_encode([
                            'inline_keyboard' => [
                                [
                                    ["text" => "📥 Video yuklash",'callback_data' => "video_".$id],
                                ],
                                [
                                    ["text" => "🔙 Ortga",'callback_data' => "back"],
                                ]
                            ]
                        ]);
                    } else {
                        $keyboard_video = json_encode([
                            'inline_keyboard' => [
                                [
                                    ["text" => "📥 Video yuklash",'callback_data' => "video_".$id],
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
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Ortga ↩️",'callback_data' => 'back'],
                        ],

                    ]
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "*Ma'lumot yo'q ❗️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            }
        }
        else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
        }
    }
?>