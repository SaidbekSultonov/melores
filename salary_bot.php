<?php
    $conn = pg_connect("host=localhost dbname=orginal_db user=postgres password=postgres");
    if ($conn) {
        echo "Success";
    }

    const TOKEN = '1777589219:AAGBRhMdDuU8rCZiGfiwMfkPizWE2eKUiiU';
    const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;
    const ADMIN = 398187848;

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
    }

    function deleteMessageUser($conn, $chat_id){
        $selectMessageId = "SELECT * FROM salary_message_id WHERE chat_id = ".$chat_id;
        $resultMessageId = pg_query($conn, $selectMessageId);
        if (pg_num_rows($resultMessageId) > 0){
            while($row = pg_fetch_assoc($resultMessageId)){
                $user_message_id = $row['message_id'];

                bot('deleteMessage',[
                    'chat_id' => $chat_id,
                    'message_id' => $user_message_id,
                ]);
            }
            $deleteMessageId = "DELETE FROM salary_message_id WHERE chat_id = ".$chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
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

    $remove_keyboard = array(
        'remove_keyboard' => true
    );
    $remove_keyboard = json_encode($remove_keyboard);

    if ($text == "/start") {
        $sql = "SELECT * FROM salary_step WHERE chat_id = ".$chat_id;
        $result = pg_query($conn, $sql);
        if (!pg_num_rows($result) > 0) {
            $addStepUser = "INSERT INTO salary_step (chat_id,step_1,step_2) VALUES (".$chat_id.",0,0)";
            $result = pg_query($conn, $addStepUser);
            $addLastId = "INSERT INTO salary_last_id (chat_id,last_id) VALUES (".$chat_id.",0)";
            $resultLastId = pg_query($conn, $addLastId);
        } else {
            $stepUpdate = "UPDATE salary_step SET step_2 = 0 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);
            $lastIdUpdate = "UPDATE salary_last_id SET last_id = 0 WHERE chat_id = ".$chat_id;
            $resultLastId = pg_query($conn, $lastIdUpdate);
        }

        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => "*Assalomu alaykum!".PHP_EOL."Raqamingizni jo'nating 📲*",
            'parse_mode' => 'markdown',
            'reply_markup' => $phone_number
        ]);
    }

    $sql = "SELECT * FROM salary_step WHERE chat_id = ".$chat_id;
    $result = pg_query($conn,$sql);
    $step_1 = 0;
    $step_2 = 0;
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $step_1 = $row["step_1"];
        $step_2 = $row["step_2"];
    }

    $sqlLastId = "SELECT * FROM salary_last_id WHERE chat_id = ".$chat_id;
    $resultLastId = pg_query($conn,$sqlLastId);
    if (pg_num_rows($resultLastId) > 0) {
        $row = pg_fetch_assoc($resultLastId);
        $last_id = $row["last_id"];
    }

    $selectChatId = "SELECT * FROM users WHERE chat_id = ".$chat_id;
    $resultChatId = pg_query($conn,$selectChatId);
    if (pg_num_rows($resultChatId) > 0) {
        $row = pg_fetch_assoc($resultChatId);
        $user_id = $row["id"];
    }

    if ($step_1 == 0 and $step_2 == 0 and $text != "/start"){
        if (isset($update->message->contact)){
            $number = $update->message->contact->phone_number;
            $number = str_replace("+","",$number);

            $phoneNumber = "select * from users where phone_number = '".$number."'";
            $resultNumber = pg_query($conn,$phoneNumber);

            $selectBalance = "SELECT * FROM salary_user_balance WHERE user_id = ".$chat_id;
            $resultBalance = pg_query($conn, $selectBalance);
            if (!pg_num_rows($resultBalance) > 0){
                $addUserBalance = "INSERT INTO salary_user_balance (user_id,balance) VALUES (".$chat_id.",0)";
                $resultBalance = pg_query($conn, $addUserBalance);
            }

            if (pg_num_rows($resultNumber) > 0) {
                $chatIdUpdate = "UPDATE users SET phone_number = '".$number."' WHERE chat_id = ".$chat_id;
                $resultChatId = pg_query($conn, $chatIdUpdate);
                $row = pg_fetch_assoc($resultNumber);
                $type = $row['type'];
                if ($type == 1){
                    $deleteSalary = "DELETE FROM salary_amount WHERE status = 10";
                    $resultDelete = pg_query($conn, $deleteSalary);

                    $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    $answer = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                            ],
                            [
                                ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                                ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                            ],
                            [
                                ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                            ]
                        ]
                    ]);

                    if ($result == true){
                        $res = bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "*Siz tekshiruvdan muvzaffaqiyatli o'tdingiz ✅*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $remove_keyboard
                        ]);

                        $message_id = $res->result->message_id;
                        $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                        $resultMessageId = pg_query($conn, $insertMessageId);

                        bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "*Kerakli bo'limni tanlang ⤵️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $answer
                        ]);
                    }
                } else {
                    $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    if ($result == true){
                        $menuUser = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                                ],
                                [
                                    ['text' => "Oylik olgan pullarim 📄",'callback_data' => 'month_money'],
                                ]
                            ]
                        ]);

                        $res = bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "*Siz tekshiruvdan muvaffaqiyatli o'tdingiz ✅*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $remove_keyboard
                        ]);

                        $message_id = $res->result->message_id;
                        $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                        $resultMessageId = pg_query($conn, $insertMessageId);

                        bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "*Kerakli bo'limni tanlang ⤵️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $menuUser
                        ]);
                    }
                }
            } else {
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Bu botdan 🤖 foydalana olmaysiz ☹️❗️*",
                    'parse_mode' => 'markdown'
                ]);
            }
        } else {
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "*Telefon raqam jo'natish 📲*"." tugamsini bosing❗️",
                'parse_mode' => 'markdown'
            ]);
        }
    }

    if ($step_1 == 0 and $step_2 == 1 and $text != "/start"){
        deleteMessageUser($conn, $chat_id);
        if ($data == "give_money"){
            $deleteSalary = "DELETE FROM salary_amount WHERE status = 10";
            $resultDelete = pg_query($conn, $deleteSalary);

            $selectCategory = "SELECT * FROM salary_category WHERE type != 2 ORDER BY id ASC";
            $resultCategory = pg_query($conn, $selectCategory);

            $addSalary = "INSERT INTO salary_amount (chat_id,type,status) VALUES (".$chat_id.",1,10)";
            $resultSalary = pg_query($conn, $addSalary);

            $stepUpdate = "UPDATE salary_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultCategory == true and $resultSalary == true and $resultStep == true){
                $selectLastSalaryId = "SELECT * FROM salary_amount ORDER BY id desc LIMIT 1";
                $resultLastSalaryId = pg_query($conn, $selectLastSalaryId);
                if (pg_num_rows($resultLastSalaryId) > 0){
                    $rowSalary = pg_fetch_assoc($resultLastSalaryId);
                    $salary_id = $rowSalary['id'];
                    $lastIdUpdate = "UPDATE salary_last_id SET last_id = ".$salary_id." WHERE chat_id = ".$chat_id;
                    $resultLastId = pg_query($conn, $lastIdUpdate);
                }
                $arr = [];
                if (pg_num_rows($resultCategory) > 0) {
                    while($row = pg_fetch_assoc($resultCategory)) {
                        $category_id = $row['id'];
                        $category_name = $row['title'];
                        $category_balance = $row['balance'];
                        $arr_uz[] = ["text" => "💰 Balans: ".$category_balance." | ".$category_name, "callback_data" => "category_".$category_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Kategoriyani tanlang 🔘*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $btnKey
                    ]);
                }
            }
        }
        else if ($data == "statistics"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);
            if ($resultStep == true){
                $month = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Yanvar",'callback_data' => 'month_31_01'],
                            ['text' => "Fevral",'callback_data' => 'month_28_02'],
                            ['text' => "Mart",'callback_data' => 'month_31_03'],
                            ['text' => "Aprel",'callback_data' => 'month_30_04'],
                        ],
                        [
                            ['text' => "May",'callback_data' => 'month_31_05'],
                            ['text' => "Iyun",'callback_data' => 'month_30_06'],
                            ['text' => "Iyul",'callback_data' => 'month_31_07'],
                            ['text' => "Avgust",'callback_data' => 'month_31_08'],
                        ],
                        [
                            ['text' => "Sentabr",'callback_data' => 'month_30_09'],
                            ['text' => "Oktabr",'callback_data' => 'month_31_10'],
                            ['text' => "Noyabr",'callback_data' => 'month_30_11'],
                            ['text' => "Dekabr",'callback_data' => 'month_31_12'],
                        ],
                        [
                            ['text' => "Ortga ↩️",'callback_data' => 'back'],
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Qaysi oyni hisobotini ko'rmoqchisiz ❓*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $month
                ]);
            }
        }
        else if ($data == "confirm_money"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
            $selectSalary = "SELECT sa.id, sa.price, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.status = 2 and sa.type = 2";
            $resultSalary = pg_query($conn, $selectSalary);

            if (pg_num_rows($resultSalary) > 0){
                $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$chat_id;
                $result = pg_query($conn, $stepUpdate);
                while ($row = pg_fetch_assoc($resultSalary)){
                    $salary_id = $row['id'];
                    $user_chat_id = $row['chat_id'];
                    $salary_date = $row['date'];
                    $salary_user = $row['second_name'];
                    $salary_price = $row['price'];
                    $salary_comment = base64_decode($row['comment']);
                    $salary_category = $row['title'];

                    $txtSend = "👤 | ".$salary_user.PHP_EOL."#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment.PHP_EOL."📅 | ".$salary_date;

                    $confirmMaterial = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Bekor qilish ❌",'callback_data' => 'cancel_'.$salary_id],
                                ['text' => "Tasdiqlash ✅",'callback_data' => 'confirm_'.$salary_id],
                            ],
                            [
                                ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                            ]
                        ]
                    ]);

                    $res = bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => $txtSend,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);

                    $message_id = $res->result->message_id;
                    $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                    $resultMessageId = pg_query($conn, $insertMessageId);
                }
            }
            else {
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
        else if ($data == "bonus"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectUserId = "SELECT * FROM users WHERE chat_id = ".$chat_id;
            $resultUserId = pg_query($conn, $selectUserId);
            if (pg_num_rows($resultUserId) > 0) {
                $row = pg_fetch_assoc($resultUserId);
                $user_id = $row['id'];
            }

            $date = date('Y-m-d H:i:s');

            $addSalary = "INSERT INTO salary_amount (chat_id,comment,date,type,status) VALUES (".$chat_id.",'".base64_encode('Bonus')."','".$date."',1,10)";
            $resultSalary = pg_query($conn, $addSalary);

            $selectLastSalaryId = "SELECT * FROM salary_amount ORDER BY id desc LIMIT 1";
            $resultLastSalaryId = pg_query($conn, $selectLastSalaryId);
            if (pg_num_rows($resultLastSalaryId) > 0) {
                $rowSalary = pg_fetch_assoc($resultLastSalaryId);
                $salary_id = $rowSalary['id'];
                $lastIdUpdate = "UPDATE salary_last_id SET last_id = " . $salary_id . " WHERE chat_id = " . $chat_id;
                $resultLastId = pg_query($conn, $lastIdUpdate);
            }

            if ($resultStep == true){
                $selectCategory = "SELECT * FROM salary_category WHERE type = 2 ORDER BY id ASC";
                $resultCategory = pg_query($conn, $selectCategory);

                if (pg_num_rows($resultCategory) > 0) {
                    while($row = pg_fetch_assoc($resultCategory)) {
                        $category_id = $row['id'];
                        $category_name = $row['title'];
                        $arr_uz[] = ["text" => $category_name, "callback_data" => "bonusCategory_".$category_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Kategoriyani tanlang 🔘*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $btnKey
                    ]);
                }
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 2 and $text != "/start"){
        $category_date = explode("_", $data);
        if ($category_date[0] == "category"){
            $updateCategory = "UPDATE salary_amount SET category_id = ".$category_date[1]." WHERE status = 10 and id = ".$last_id;
            $resultUpdateCategory = pg_query($conn, $updateCategory);

            $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultUpdateCategory == true and $resultStep == true){
                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'type_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'type_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'type_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'type_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'type_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'type_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_category"]
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
        }
        else if ($category_date[0] == "month"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
            $first_date = date('Y-'.$category_date[2].'-01');
            $last_date = date('Y-'.$category_date[2].'-t');
            $selectStatistics = "SELECT sa.price, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
    FROM salary_amount AS sa 
    INNER JOIN salary_category AS sc ON sa.category_id = sc.id
    INNER JOIN users AS u ON u.id = sa.user_id 
    WHERE sa.status != 10 and date > '".$first_date."' AND date < '".$last_date."'";
            $resultStatistics = pg_query($conn, $selectStatistics);

            if (pg_num_rows($resultStatistics) > 0){
                $a = 1;
                while($row = pg_fetch_assoc($resultStatistics)){
                    $back = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                            ],
                        ]
                    ]);

                    $salary_date = date('Y-m-d', strtotime($row['date']));
                    $salary_user = $row['second_name'];
                    $salary_price = $row['price'];
                    $salary_comment = base64_decode($row['comment']);
                    $salary_category = $row['title'];

                    $txtSend = "👤 | ".$salary_user.PHP_EOL."#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment.PHP_EOL."📅 | ".$salary_date;
                    if ($a == pg_num_rows($resultStatistics)){
                        $res = bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => $txtSend,
                            'parse_mode' => 'markdown',
                            'reply_markup' => $back
                        ]);
                    } else {
                        $res = bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => $txtSend,
                            'parse_mode' => 'markdown',
                        ]);
                    }

                    $message_id = $res->result->message_id;
                    $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                    $resultMessageId = pg_query($conn, $insertMessageId);
                    $a++;
                }
                $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);
            } else {
                $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                if ($resultStep == true){
                    bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "*Bu oy uchun statistika mavjud emas ❗️*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $answer
                    ]);
                }
            }
        }
        else if ($category_date[0] == "bonusCategory"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $salaryUpdate = "UPDATE salary_amount SET category_id = ".$category_date[1]." WHERE status = 10 AND id = ".$last_id;
            $resultSalary = pg_query($conn, $salaryUpdate);

            if ($resultStep == true){
                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'bonus_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'bonus_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'bonus_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'bonus_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'bonus_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'bonus_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_bonus"]
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
        }
        else if ($data == "back"){
            deleteMessageUser($conn, $chat_id);
            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $answer = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                    ],
                    [
                        ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                        ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                    ],
                    [
                        ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                    ]
                ]
            ]);

            if ($result == true){
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 3 and $text != "/start"){
        $user_type = explode("_", $data);
        if ($user_type[0] == "type"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 4 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $selectUsers = "SELECT * FROM users WHERE type = ".$user_type[1];
            $resultUsers = pg_query($conn,$selectUsers);

            if (pg_num_rows($resultUsers) > 0) {
                $arr_uz = [];
                $row_arr = [];
                while ($value = pg_fetch_assoc($resultUsers)) {
                    $userId = $value['id'];
                    $second_name = $value['second_name'];
                    $arr_uz[] = ["text" => "👤 | $second_name", "callback_data" => "user_".$userId];
                    $row_arr[] = $arr_uz;
                    $arr_uz = [];
                }
                $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back_category"]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $btnKey
                ]);
            } else {
                $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
                $result = pg_query($conn, $stepUpdate);

                bot('deleteMessage',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Bu bo'limda hali ma'lumot yo'q ❗️😕*",
                    'parse_mode' => 'markdown',
                ]);

                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'type_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'type_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'type_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'type_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'type_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'type_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_category"]
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
        }
        else if ($user_type[0] == "bonus"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 4 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $selectUsers = "SELECT * FROM users WHERE type = ".$user_type[1];
            $resultUsers = pg_query($conn,$selectUsers);

            if (pg_num_rows($resultUsers) > 0) {
                $arr_uz = [];
                $row_arr = [];
                while ($value = pg_fetch_assoc($resultUsers)) {
                    $userId = $value['id'];
                    $second_name = $value['second_name'];
                    $arr_uz[] = ["text" => "👤 | $second_name", "callback_data" => "bonusUser_".$userId];
                    $row_arr[] = $arr_uz;
                    $arr_uz = [];
                }

                $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back_bonus"]];

                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $btnKey
                ]);
            } else {
                $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
                $result = pg_query($conn, $stepUpdate);

                bot('deleteMessage',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Bu bo'limda hali ma'lumot yo'q ❗️😕*",
                    'parse_mode' => 'markdown',
                ]);

                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'bonus_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'bonus_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'bonus_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'bonus_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'bonus_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'bonus_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_bonus"]
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
        }
        else if ($user_type[0] == "back"){
            if ($user_type[1] == "category"){
                $stepUpdate = "UPDATE salary_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                $selectCategory = "SELECT * FROM salary_category WHERE type != 2 ORDER BY id ASC";
                $resultCategory = pg_query($conn, $selectCategory);

                if (pg_num_rows($resultCategory) > 0) {
                    while($row = pg_fetch_assoc($resultCategory)) {
                        $category_id = $row['id'];
                        $category_name = $row['title'];
                        $category_balance = $row['balance'];
                        $arr_uz[] = ["text" => "💰 Balans: ".$category_balance." | ".$category_name, "callback_data" => "category_".$category_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Kategoriyani tanlang 🔘*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $btnKey
                    ]);
                }
            }
            else if ($user_type[1] == "bonus"){
                $stepUpdate = "UPDATE salary_step SET step_2 = 2 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                $selectCategory = "SELECT * FROM salary_category WHERE type = 2 ORDER BY id ASC";
                $resultCategory = pg_query($conn, $selectCategory);

                if (pg_num_rows($resultCategory) > 0) {
                    while($row = pg_fetch_assoc($resultCategory)) {
                        $category_id = $row['id'];
                        $category_name = $row['title'];
                        $arr_uz[] = ["text" => $category_name, "callback_data" => "bonusCategory_".$category_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Kategoriyani tanlang 🔘*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $btnKey
                    ]);
                }
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 4 and $text != "/start"){
        $user_salary = explode("_", $data);
        if ($user_salary[0] == "user"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $updateCategory = "UPDATE salary_amount SET user_id = ".$user_salary[1]." WHERE status = 10 and id = ".$last_id;
            $resultUpdateCategory = pg_query($conn, $updateCategory);

            $stepUpdate = "UPDATE salary_step SET step_2 = 5 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultUpdateCategory == true and $resultStep == true){
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_category"]
                        ]
                    ]
                ]);

                $res = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Summa kriting 💲",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
        else if ($user_salary[0] == "bonusUser"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $updateUserId = "UPDATE salary_amount SET user_id = ".$user_salary[1]." WHERE status = 10 and id = ".$last_id;
            $resultUpdateUserId = pg_query($conn, $updateUserId);

            $stepUpdate = "UPDATE salary_step SET step_2 = 10 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultUpdateUserId == true and $resultStep == true){
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back"]
                        ]
                    ]
                ]);

                $res = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Summa kriting 💲",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
        else if ($user_salary[0] == "back"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);
            $deleteMessageId = "DELETE FROM salary_message_id WHERE chat_id = ".$chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
            if ($user_salary[1] == "category"){
                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'type_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'type_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'type_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'type_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'type_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'type_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_category"]
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
            else if ($user_salary[1] == "bonus"){
                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'bonus_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'bonus_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'bonus_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'bonus_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'bonus_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'bonus_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_bonus"]
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 5 and $text != "/start"){
        $back = explode("_", $data);
        if (ctype_digit($text) != false){

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            deleteMessageUser($conn, $chat_id);
            $updateCategory = "UPDATE salary_amount SET price = ".$text." WHERE status = 10 and id = ".$last_id;
            $resultUpdateCategory = pg_query($conn, $updateCategory);

            $stepUpdate = "UPDATE salary_step SET step_2 = 6 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultUpdateCategory == true and $resultStep == true){

                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back"]
                        ]
                    ]
                ]);

                $res = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Izoh kriting 📝",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
        else if ($back[0] == "back"){
            $deleteMessageId = "DELETE FROM salary_message_id WHERE chat_id = ".$chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
            if ($back[1] == "category"){
                $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
                $result = pg_query($conn, $stepUpdate);

                $types = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'type_2'],
                            ['text' => "🙍‍♂️ | OTK",'callback_data' => 'type_3'],
                            ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'type_4'],
                        ],
                        [
                            ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'type_5'],
                            ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'type_6'],
                            ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'type_7'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_category"]
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Ishchilarni tanlang 🔘",
                    'reply_markup' => $types
                ]);
            }
        }
        else {
            $res = bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "*Summani kritayotganingizda faqat sonlardan foydalaning ❗️*",
                'parse_mode' => 'markdown'
            ]);

            $message_id = $res->result->message_id;
            $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
            $resultMessageId = pg_query($conn, $insertMessageId);
        }
    }

    if ($step_1 == 0 and $step_2 == 10 and $text != "/start"){
        if (ctype_digit($text) != false){
            deleteMessageUser($conn, $chat_id);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $updateCategory = "UPDATE salary_amount SET price = ".$text." WHERE status = 10 and id = ".$last_id;
            $resultUpdateCategory = pg_query($conn, $updateCategory);

            $stepUpdate = "UPDATE salary_step SET step_2 = 6 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultUpdateCategory == true and $resultStep == true){
                $selectSalary = "SELECT sa.price, sa.comment, sa.date, u.second_name, sc.title 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.id = ".$last_id;
                $resultSalary = pg_query($conn, $selectSalary);
                if (pg_num_rows($resultSalary) > 0){
                    $row = pg_fetch_assoc($resultSalary);
                    $salary_date = date('Y-m-d H:i', strtotime($row['date']));
                    $salary_user = $row['second_name'];
                    $salary_price = $row['price'];
                    $salary_comment = base64_decode($row['comment']);
                    $salary_category = $row['title'];

                    $txtSend = "👤 | ".$salary_user.PHP_EOL."#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment.PHP_EOL."📅 | ".$salary_date;
                }
                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Bekor qilish ❌",'callback_data' => 'cancel'],
                            ['text' => "Tasdiqlash ✅",'callback_data' => 'confirm'],
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => $txtSend,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $confirmMaterial
                ]);
            }
        }
        else if ($data == "back"){
            $deleteMessageId = "DELETE FROM salary_message_id WHERE chat_id = ".$chat_id;
            $resultMessageId = pg_query($conn, $deleteMessageId);
            
            $stepUpdate = "UPDATE salary_step SET step_2 = 3 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $types = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "👨‍💼 | Nazoratchi",'callback_data' => 'bonus_2'],
                        ['text' => "🙍‍♂️ | OTK",'callback_data' => 'bonus_3'],
                        ['text' => "👨‍💻 | Sotuvchi",'callback_data' => 'bonus_4'],
                    ],
                    [
                        ['text' => "🧑‍💻 | Bo’lim boshlig’i",'callback_data' => 'bonus_5'],
                        ['text' => "👨‍🔧 | Kroychi",'callback_data' => 'bonus_6'],
                        ['text' => "👷‍♂️ | Ishchi",'callback_data' => 'bonus_7'],
                    ],
                    [
                        ["text" => "Ortga ↩️", "callback_data" => "back_bonus"]
                    ]
                ]
            ]);

            bot('editMessageText',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => "Ishchilarni tanlang 🔘",
                'reply_markup' => $types
            ]);
        }
        else {
            $res = bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "*Summani kritayotganingizda faqat sonlardan foydalaning ❗️*",
                'parse_mode' => 'markdown'
            ]);

            $message_id = $res->result->message_id;
            $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
            $resultMessageId = pg_query($conn, $insertMessageId);
        }
    }

    if ($step_1 == 0 and $step_2 == 6 and $text != "/start"){
        if (isset($text)){
            deleteMessageUser($conn, $chat_id);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $txt = base64_encode($text);
            $updateCategory = "UPDATE salary_amount SET comment = '".$txt."' WHERE status = 10 and id = ".$last_id;
            $resultUpdateCategory = pg_query($conn, $updateCategory);

            $stepUpdate = "UPDATE salary_step SET step_2 = 7 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultUpdateCategory == true and $resultStep == true){
                $month = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Yanvar",'callback_data' => 'month_31_01'],
                            ['text' => "Fevral",'callback_data' => 'month_28_02'],
                            ['text' => "Mart",'callback_data' => 'month_31_03'],
                            ['text' => "Aprel",'callback_data' => 'month_30_04'],
                        ],
                        [
                            ['text' => "May",'callback_data' => 'month_31_05'],
                            ['text' => "Iyun",'callback_data' => 'month_30_06'],
                            ['text' => "Iyul",'callback_data' => 'month_31_07'],
                            ['text' => "Avgust",'callback_data' => 'month_31_08'],
                        ],
                        [
                            ['text' => "Sentabr",'callback_data' => 'month_30_09'],
                            ['text' => "Oktabr",'callback_data' => 'month_31_10'],
                            ['text' => "Noyabr",'callback_data' => 'month_30_11'],
                            ['text' => "Dekabr",'callback_data' => 'month_31_12'],
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back"]
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Pul berish sanasini kriting ⌛️",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $month
                ]);
            }
        }
        else if ($data == "confirm"){
            $updateSalary = "UPDATE salary_amount SET status = 1 WHERE id = ".$last_id;
            $resultUpdateSalary = pg_query($conn, $updateSalary);

            $selectSalary = "SELECT sa.price, sa.user_id, sa.category_id, sc.title as category_name, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.type = 1 and sa.id = ".$last_id;
            $resultSalary = pg_query($conn, $selectSalary);

            if (pg_num_rows($resultSalary) > 0){
                $row = pg_fetch_assoc($resultSalary);
                $salary_price = $row['price'];
                $user_chat_id = $row['chat_id'];
                $user_second_name = $row['second_name'];
                $salary_category_name = $row['category_name'];
                $salary_user_id = $row['user_id'];
                $category_id = $row['category_id'];
            }

            $selectChatId = "SELECT * FROM users WHERE id = ".$salary_user_id;
            $resultChatId = pg_query($conn, $selectChatId);
            if (pg_num_rows($resultChatId) > 0){
                $row = pg_fetch_assoc($resultChatId);
                $salary_chat_id = $row['chat_id'];

                $addEvent = "INSERT INTO salary_event_balance (user_id,receiver,quantity,category_id,date,type) VALUES (".$user_id.",".$salary_chat_id.",".$salary_price.",".$category_id.",'".date('Y-m-d H:i:s')."',1)";
                $resultEvent = pg_query($conn, $addEvent);
            }


            $confirmMaterial = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Bosh menu 🏠",'callback_data' => 'home_user'],
                    ]
                ]
            ]);

            $selectManager = "SELECT * FROM users WHERE type = 2";
            $resultManager = pg_query($conn, $selectManager);

            $current_date = date('Y-m-d H:i');
            if (pg_num_rows($resultManager) > 0){
                while($row = pg_fetch_assoc($resultManager)){
                    $manager_chat_id = $row['chat_id'];

                    $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$manager_chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    bot('sendMessage',[
                        'chat_id' => $manager_chat_id,
                        'text' => "*Bonus berildi ⤵️*".PHP_EOL."👤 | ".$user_second_name.PHP_EOL."#️⃣ | ".$salary_category_name.PHP_EOL."💰 | ".$salary_price.PHP_EOL."📅 | ".$current_date,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);
                }
            }

            bot('sendMessage',[
                'chat_id' => $user_chat_id,
                'text' => "*🤩 Sizga yangilik 🎉*

Sizga *".$salary_price."* miqdorida bonus taqdim etildi 💰",
                'parse_mode' => 'markdown',
                'reply_markup' => $confirmMaterial
            ]);

            $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
            $resultUserStep = pg_query($conn, $stepUpdate);

            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            if ($result == true and $resultUserStep == true){
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Bonus yuborildi ✅*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
        else if ($data == "cancel"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $deleteSalary = "DELETE FROM salary_amount WHERE id = ".$last_id;
            $resultDelete = pg_query($conn, $deleteSalary);

            if ($resultDelete == true and $result == true){
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
        else if ($data == "back"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 5 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ["text" => "Ortga ↩️", "callback_data" => "back_category"]
                    ]
                ]
            ]);

            bot('editMessageText',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => "Summa kriting 💲",
                'reply_markup' => $back
            ]);
        }
    }

    if ($step_1 == 0 and $step_2 == 7 and $text != "/start"){
        $month = explode("_", $data);
        if ($month[0] == "month"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 8 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $select_date = date('Y-'.$month[2]);

            $salaryUpdate = "UPDATE salary_amount SET date = '".$select_date."' WHERE status = 10 AND id = ".$last_id;
            $resultSalary = pg_query($conn, $salaryUpdate);

            $arr_uz = [];
            $row_arr = [];
            $k = 1;
            for ($i = 1; $i <= $month[1]; $i++){
                $arr_uz[] = ["text" => $i, "callback_data" => "day_".$i];
                if($k%5 == 0)
                {
                    $row_arr[] = $arr_uz;
                    $arr_uz = [];
                }
                $k++;
            }
            $row_arr[] = $arr_uz;
            $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back"]];
            $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            bot('editMessageText',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => 'Vazifa tugash sanasini kriting 📅',
                'reply_markup' => $btnKey,
            ]);
        }
        else if ($data == "back"){
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            deleteMessageUser($conn, $chat_id);

            $stepUpdate = "UPDATE salary_step SET step_2 = 6 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultStep == true){

                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back"]
                        ]
                    ]
                ]);

                $res = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Izoh kriting 📝",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 8 and $text != "/start"){
        $day = explode("_", $data);
        if ($day[0] == "day"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 9 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $selectSalary = "SELECT * FROM salary_amount WHERE id = ".$last_id;
            $resultSalary = pg_query($conn,$selectSalary);

            if (pg_num_rows($resultSalary) > 0) {
                $row = pg_fetch_assoc($resultSalary);
                $date = $row['date'];
            }
            if ($day[1] <= 9){
                $select_date = $date."-0".$day[1];
            } else {
                $select_date = $date."-".$day[1];
            }

            $salaryUpdate = "UPDATE salary_amount SET date = '".$select_date."' WHERE id = ".$last_id;
            $resultUpdateSalary = pg_query($conn, $salaryUpdate);

            if ($resultUpdateSalary == true){
                $selectSalary = "SELECT sa.price, sa.comment, sa.date, u.second_name, sc.title 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.id = ".$last_id;
                $resultSalary = pg_query($conn, $selectSalary);
                if (pg_num_rows($resultSalary) > 0){
                    $row = pg_fetch_assoc($resultSalary);
                    $salary_date = $row['date'];
                    $salary_user = $row['second_name'];
                    $salary_price = $row['price'];
                    $salary_comment = base64_decode($row['comment']);
                    $salary_category = $row['title'];

                    $txtSend = "👤 | ".$salary_user.PHP_EOL."#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment.PHP_EOL."📅 | ".$salary_date;
                }
                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Bekor qilish ❌",'callback_data' => 'cancel'],
                            ['text' => "Tasdiqlash ✅",'callback_data' => 'confirm'],
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $txtSend,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $confirmMaterial
                ]);
            }
        }
        else if ($data == "back"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 7 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultStep == true) {
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
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back"]
                        ]
                    ]
                ]);

                bot('editMessageText', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Pul berish sanasini kriting ⌛️",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $month
                ]);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 9 and $text != "/start"){
        if ($data == "confirm"){
            $selectSalary = "SELECT sa.price, sa.user_id, sa.category_id, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.type = 1 and sa.id = ".$last_id;
            $resultSalary = pg_query($conn, $selectSalary);

            if (pg_num_rows($resultSalary) > 0){
                $row = pg_fetch_assoc($resultSalary);
                $user_chat_id = $row['chat_id'];
                $salary_date = $row['date'];
                $salary_user = $row['second_name'];
                $salary_price = $row['price'];
                $salary_comment = base64_decode($row['comment']);
                $salary_category = $row['title'];
                $category_id = $row['category_id'];
                $salary_user_id = $row['user_id'];

                $txtSend = "#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment.PHP_EOL."📅 | ".$salary_date;
            }

            $selectBalance = "SELECT * FROM salary_category WHERE id = ".$category_id;
            $resultBalance = pg_query($conn, $selectBalance);

            if (pg_num_rows($resultBalance) > 0){
                $row = pg_fetch_assoc($resultBalance);
                $balance = $row['balance'];
                if ($balance >= $salary_price){
                    $minus_balance = $balance - $salary_price;

                    $updateCategoryBalance = "UPDATE salary_category SET balance = ".$minus_balance." WHERE id = ".$category_id;
                    $resultUpdateBalance = pg_query($conn, $updateCategoryBalance);

                    $updateSalary = "UPDATE salary_amount SET status = 1 WHERE id = ".$last_id;
                    $resultUpdateSalary = pg_query($conn, $updateSalary);

                    $selectChatId = "SELECT * FROM users WHERE id = ".$salary_user_id;
                    $resultChatId = pg_query($conn, $selectChatId);
                    if (pg_num_rows($resultChatId) > 0){
                        $row = pg_fetch_assoc($resultChatId);
                        $salary_chat_id = $row['chat_id'];

                        $addEvent = "INSERT INTO salary_event_balance (user_id,receiver,quantity,category_id,date,type) VALUES (".$user_id.",".$salary_chat_id.",".$salary_price.",".$category_id.",'".date('Y-m-d H:i:s')."',1)";
                        $resultEvent = pg_query($conn, $addEvent);
                    }

                    $confirmMaterial = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Bosh menu 🏠",'callback_data' => 'home_user'],
                            ]
                        ]
                    ]);

                    bot('sendMessage',[
                        'chat_id' => $user_chat_id,
                        'text' => "*Sizga pul o'tkazildi ⤵️*".PHP_EOL.$txtSend,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);

                    $selectManager = "SELECT * FROM users WHERE type = 2";
                    $resultManager = pg_query($conn, $selectManager);

                    if (pg_num_rows($resultManager) > 0){
                        while($row = pg_fetch_assoc($resultManager)){
                            $manager_chat_id = $row['chat_id'];

                            $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$manager_chat_id;
                            $result = pg_query($conn, $stepUpdate);

                            bot('sendMessage',[
                                'chat_id' => $manager_chat_id,
                                'text' => "*Pul o'tkazildi ⤵️*".PHP_EOL."👤 | ".$salary_user.PHP_EOL.$txtSend,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                        }
                    }


                    $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    if ($result == true){
                        $answer = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                                ],
                                [
                                    ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                                    ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                                ],
                                [
                                    ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                                ]
                            ]
                        ]);

                        bot('editMessageText',[
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => "*Pul yuborildi ✅*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $answer
                        ]);
                    }
                } else {
                    $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    if ($result == true){
                        $answer = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                                ],
                                [
                                    ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                                    ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                                ],
                                [
                                    ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                                ]
                            ]
                        ]);

                        bot('editMessageText',[
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => "*Balansingizda mablag' yetarli emas ❗️*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $answer
                        ]);
                    }
                }
            }
        }
        else if ($data == "cancel"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $deleteSalary = "DELETE FROM salary_amount WHERE id = ".$last_id;
            $resultDelete = pg_query($conn, $deleteSalary);

            if ($resultDelete == true and $result == true){
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
    }


    if ($step_1 == 0 and $step_2 == 50 and $text != "/start"){
        deleteMessageUser($conn, $chat_id);
        if ($data == "ask_money") {
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $stepUpdate = "UPDATE salary_step SET step_2 = 51 WHERE chat_id = " . $chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $selectUserId = "SELECT * FROM users WHERE chat_id = " . $chat_id;
            $resultUserId = pg_query($conn, $selectUserId);
            if (pg_num_rows($resultUserId) > 0) {
                $row = pg_fetch_assoc($resultUserId);
                $user_id = $row['id'];
            }

            $date = date('Y-m-d');
            $addSalary = "INSERT INTO salary_amount (user_id,chat_id,date,type,status) VALUES (" . $user_id . "," . $chat_id . ",'".$date."',2,10)";
            $resultSalary = pg_query($conn, $addSalary);

            $selectLastSalaryId = "SELECT * FROM salary_amount ORDER BY id desc LIMIT 1";
            $resultLastSalaryId = pg_query($conn, $selectLastSalaryId);
            if (pg_num_rows($resultLastSalaryId) > 0) {
                $rowSalary = pg_fetch_assoc($resultLastSalaryId);
                $salary_id = $rowSalary['id'];
                $lastIdUpdate = "UPDATE salary_last_id SET last_id = " . $salary_id . " WHERE chat_id = " . $chat_id;
                $resultLastId = pg_query($conn, $lastIdUpdate);
            }

            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ["text" => "Ortga ↩️", "callback_data" => "back_ask"]
                    ]
                ]
            ]);

            if ($resultStep == true) {
                $res = bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Qancha pul so'ramoqchisiz ❓",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }

        }
        else if ($data == "month_money") {
            $stepUpdate = "UPDATE salary_step SET step_2 = 51 WHERE chat_id = " . $chat_id;
            $resultStep = pg_query($conn, $stepUpdate);
            if ($resultStep == true) {
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
                        ],
                        [
                            ["text" => "Ortga ↩️", "callback_data" => "back_history"]
                        ]
                    ]
                ]);

                bot('editMessageText', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Qaysi oyni hisobotini ko'rmoqchisiz ❓*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $month
                ]);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 51 and $text != "/start"){
        deleteMessageUser($conn, $chat_id);
        $month = explode("_", $data);
        if (ctype_digit($text) != false){
            deleteMessageUser($conn, $chat_id);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $stepUpdate = "UPDATE salary_step SET step_2 = 52 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $salaryUpdate = "UPDATE salary_amount SET price = ".$text." WHERE status = 10 AND id = ".$last_id;
            $resultSalary = pg_query($conn, $salaryUpdate);

            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ["text" => "Ortga ↩️", "callback_data" => "back"]
                    ]
                ]
            ]);

            if ($resultStep == true and $resultSalary == true){
                $res = bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "Izoh kriting ✍️",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
        else if ($month[0] == "month"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
            $first_date = date('Y-'.$month[2].'-01');
            $last_date = date('Y-'.$month[2].'-t');
            $selectStatistics = "SELECT sa.price, sa.comment, sa.date, u.second_name, sc.id, sc.title, u.chat_id 
    FROM salary_amount AS sa 
    INNER JOIN salary_category AS sc ON sa.category_id = sc.id
    INNER JOIN users AS u ON u.id = sa.user_id 
    WHERE sa.user_id = ".$user_id." and sa.status = 1 AND date > '".$first_date."' AND date < '".$last_date."'";
            $resultStatistics = pg_query($conn, $selectStatistics);

            if (pg_num_rows($resultStatistics) > 0){
                $all_price = 0;
                $admin_task = 0;
                $admin_deadline_task = 0;
                $back = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Bosh menu 🏠",'callback_data' => 'back_history'],
                        ],
                    ]
                ]);
                while($row = pg_fetch_assoc($resultStatistics)){
                    $salary_date = date('Y-m-d', strtotime($row['date']));
                    $salary_price = $row['price'];
                    $salary_comment = base64_decode($row['comment']);
                    $salary_category = $row['title'];
                    $salary_category_id = $row['id'];

                    if ($salary_category_id == 3){
                        $admin_task = $admin_task + $salary_price;
                    }
                    else if ($salary_category_id == 4){
                        $admin_deadline_task = $admin_deadline_task + $salary_price;
                    } else {
                        $all_price = $all_price + $salary_price;
                    }
                    $txtSend = "#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment.PHP_EOL."📅 | ".$salary_date;

                    $res = bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => $txtSend,
                        'parse_mode' => 'markdown',
                    ]);

                    $message_id = $res->result->message_id;
                    $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                    $resultMessageId = pg_query($conn, $insertMessageId);
                }

                $all_summ = $all_price - ($admin_task + $admin_deadline_task);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Hisobot 📑*".PHP_EOL.PHP_EOL."*Shu oyda olgan umumiy mablag'ingiz:* ".$all_price." ➕".PHP_EOL."*Shu oyda vazifani o'z vaqtida bajarmaganingiz uchun jarima: *".$admin_task."➖".PHP_EOL."*Shu oyda vazifani o'z vaqtida qabul qilmaganingiz uchun jarima: *".$admin_deadline_task."➖".PHP_EOL."*Jami: *".$all_summ." 💵",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $back
                ]);
            } else {
                $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
                $resultStep = pg_query($conn, $stepUpdate);

                if ($resultStep == true){
                    $menuUser = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                            ],
                            [
                                ['text' => "Oylik olgan pullarim 📄",'callback_data' => 'month_money'],
                            ]
                        ]
                    ]);

                    bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "*Bu oy uchun statistika mavjud emas ❗️*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $menuUser
                    ]);
                }
            }
        }
        else if ($month[0] == "back"){
            if ($month[1] == "ask"){
                $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
                $result = pg_query($conn, $stepUpdate);

                if ($result == true){
                    $menuUser = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                            ],
                            [
                                ['text' => "Oylik olgan pullarim 📄",'callback_data' => 'month_money'],
                            ]
                        ]
                    ]);

                    bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "*Kerakli bo'limni tanlang ⤵️*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $menuUser
                    ]);
                }
            }
            else if ($month[1] == "history"){
                $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
                $result = pg_query($conn, $stepUpdate);

                if ($result == true){
                    $menuUser = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                            ],
                            [
                                ['text' => "Oylik olgan pullarim 📄",'callback_data' => 'month_money'],
                            ]
                        ]
                    ]);

                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Kerakli bo'limni tanlang ⤵️*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $menuUser
                    ]);
                }
            }
        }
        else {
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $res = bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "*Summani kiritayotganingizda faqat sonlardan foydalaning*",
                'parse_mode' => 'markdown'
            ]);

            $message_id = $res->result->message_id;
            $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
            $resultMessageId = pg_query($conn, $insertMessageId);
        }
    }

    if ($step_1 == 0 and $step_2 == 52 and $text != "/start"){
        if (isset($text)){
            deleteMessageUser($conn, $chat_id);

            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $txt = base64_encode($text);
            $stepUpdate = "UPDATE salary_step SET step_2 = 53 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $salaryUpdate = "UPDATE salary_amount SET comment = '".$txt."' WHERE status = 10 AND id = ".$last_id;
            $resultSalary = pg_query($conn, $salaryUpdate);

            if ($resultStep == true and $resultSalary == true){
                $selectCategory = "SELECT * FROM salary_category WHERE type = 1 ORDER BY id ASC";
                $resultCategory = pg_query($conn, $selectCategory);

                if (pg_num_rows($resultCategory) > 0) {
                    while($row = pg_fetch_assoc($resultCategory)) {
                        $category_id = $row['id'];
                        $category_name = $row['title'];
                        $arr_uz[] = ["text" => $category_name, "callback_data" => "category_".$category_id];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }

                    $row_arr[] = [["text" => "Ortga ↩️", "callback_data" => "back"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => "*Kategoriyani tanlang 🔘*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $btnKey
                    ]);
                }
            }
        }
        else if ($data == "back"){
            deleteMessageUser($conn, $chat_id);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $stepUpdate = "UPDATE salary_step SET step_2 = 51 WHERE chat_id = " . $chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ["text" => "Ortga ↩️", "callback_data" => "back_ask"]
                    ]
                ]
            ]);

            if ($resultStep == true){
                $res = bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Qancha pul so'ramoqchisiz ❓",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 53 and $text != "/start"){
        $category = explode("_", $data);
        if ($category[0] == "category"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 54 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            $updateCategory = "UPDATE salary_amount SET category_id = ".$category[1]." WHERE status = 10 and id = ".$last_id;
            $resultUpdateCategory = pg_query($conn, $updateCategory);

            $selectSalary = "SELECT sa.price, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.id = ".$last_id." and sa.type = 2";
            $resultSalary = pg_query($conn, $selectSalary);

            if (pg_num_rows($resultSalary) > 0){
                $row = pg_fetch_assoc($resultSalary);
                $salary_price = $row['price'];
                $salary_comment = base64_decode($row['comment']);
                $salary_category = $row['title'];

                $txtSend = "#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment;
            }

            $confirmMaterial = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Bekor qilish ❌",'callback_data' => 'cancel'],
                        ['text' => "Tasdiqlash ✅",'callback_data' => 'confirm'],
                    ]
                ]
            ]);

            bot('editMessageText',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => "*Sizning arizangiz ⤵️*".PHP_EOL.$txtSend,
                'parse_mode' => 'markdown',
                'reply_markup' => $confirmMaterial
            ]);
        }
        else if ($data == "back"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 52 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $back = json_encode([
                'inline_keyboard' => [
                    [
                        ["text" => "Ortga ↩️", "callback_data" => "back"]
                    ]
                ]
            ]);

            if ($result == true){
                $res = bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "Izoh kriting ✍️",
                    'reply_markup' => $back
                ]);

                $message_id = $res->result->message_id;
                $insertMessageId = "INSERT INTO salary_message_id (chat_id,message_id) VALUES (".$chat_id.",".$message_id.")";
                $resultMessageId = pg_query($conn, $insertMessageId);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 54 and $text != "/start"){
        if ($data == "confirm"){
            $updateSalary = "UPDATE salary_amount SET status = 2 WHERE id = ".$last_id;
            $resultUpdateSalary = pg_query($conn, $updateSalary);

            $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
            $resultStep = pg_query($conn, $stepUpdate);

            if ($resultStep == true and $resultUpdateSalary == true){
                $selectSalary = "SELECT sa.price, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.id = ".$last_id." and sa.type = 2";
                $resultSalary = pg_query($conn, $selectSalary);

                if (pg_num_rows($resultSalary) > 0){
                    $row = pg_fetch_assoc($resultSalary);
                    $salary_user = $row['second_name'];
                    $salary_price = $row['price'];
                    $salary_comment = base64_decode($row['comment']);
                    $salary_category = $row['title'];

                    $txtSend = "👤 | ".$salary_user.PHP_EOL."#️⃣ | ".$salary_category.PHP_EOL."💰 | ".$salary_price.PHP_EOL."💬 | ".$salary_comment;

                    $selectAllAdmin = "SELECT * FROM users WHERE type = 1";
                    $resultAdmin = pg_query($conn, $selectAllAdmin);

                    if (pg_num_rows($resultAdmin) > 0){
                        while($rowAdmin = pg_fetch_assoc($resultAdmin)){
                            $admin_chat_id = $rowAdmin['chat_id'];
                            $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$admin_chat_id;
                            $resultStep = pg_query($conn, $stepUpdate);
                            $confirmMaterial = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Bekor qilish ❌",'callback_data' => 'cancel_'.$last_id],
                                        ['text' => "Tasdiqlash ✅",'callback_data' => 'confirm_'.$last_id],
                                    ],
                                    [
                                        ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                                    ]
                                ]
                            ]);

                            bot('sendMessage',[
                                'chat_id' => $admin_chat_id,
                                'text' => $txtSend,
                                'parse_mode' => 'markdown',
                                'reply_markup' => $confirmMaterial
                            ]);
                        }
                    }

                    $menuUser = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                            ],
                            [
                                ['text' => "Oylik olgan pullarim 📄",'callback_data' => 'month_money'],
                            ]
                        ]
                    ]);

                    bot('editMessageText',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Ariza yuborildi ✅*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $menuUser
                    ]);
                }
            }
        }
        else if ($data == "cancel"){
            $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $deleteSalary = "DELETE FROM salary_amount WHERE id = ".$last_id;
            $resultDelete = pg_query($conn, $deleteSalary);

            if ($resultDelete == true and $result == true){
                $menuUser = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                        ],
                        [
                            ['text' => "Oylik olgan pullarim",'callback_data' => 'month_money'],
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Ariza bekor qilindi ❌*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $menuUser
                ]);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 100 and $text != "/start"){
        $salary_id = explode("_", $data);
        if ($salary_id[0] == "confirm"){
            deleteMessageUser($conn, $chat_id);

            $selectSalary = "SELECT sa.price, sa.user_id, sa.category_id, sa.comment, sa.date, u.second_name, sc.title, u.chat_id 
        FROM salary_amount AS sa 
        INNER JOIN salary_category AS sc ON sa.category_id = sc.id
        INNER JOIN users AS u ON u.id = sa.user_id 
        WHERE sa.id = ".$salary_id[1];
            $resultSalary = pg_query($conn, $selectSalary);

            if (pg_num_rows($resultSalary) > 0){
                $row = pg_fetch_assoc($resultSalary);
                $user_chat_id = $row['chat_id'];
                $salary_user_id = $row['user_id'];
                $salary_price = $row['price'];
                $category_id = $row['category_id'];
            }

            $selectBalance = "SELECT * FROM salary_category WHERE id = ".$category_id;
            $resultBalance = pg_query($conn, $selectBalance);

            if (pg_num_rows($resultBalance) > 0) {
                $row = pg_fetch_assoc($resultBalance);
                $balance = $row['balance'];
                if ($balance >= $salary_price) {
                    bot('deleteMessage',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                    ]);

                    $minus_balance = $balance - $salary_price;

                    $updateSalary = "UPDATE salary_amount SET status = 1 WHERE id = ".$salary_id[1];
                    $resultUpdateSalary = pg_query($conn, $updateSalary);

                    $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    $selectChatId = "SELECT * FROM users WHERE id = ".$salary_user_id;
                    $resultChatId = pg_query($conn, $selectChatId);
                    if (pg_num_rows($resultChatId) > 0){
                        $row = pg_fetch_assoc($resultChatId);
                        $salary_chat_id = $row['chat_id'];

                        $addEvent = "INSERT INTO salary_event_balance (user_id,receiver,quantity,category_id,date,type) VALUES (".$user_id.",".$salary_chat_id.",".$salary_price.",".$category_id.",'".date('Y-m-d H:i:s')."',1)";
                        $resultEvent = pg_query($conn, $addEvent);
                    }

                    $confirmMaterial = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "Bosh menu 🏠",'callback_data' => 'home_user'],
                            ]
                        ]
                    ]);

                    bot('sendMessage',[
                        'chat_id' => $user_chat_id,
                        'text' => "*Sizning arizangiz admin tomonidan tasdiqlandi ✅*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $confirmMaterial
                    ]);

                    $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    if ($result == true){
                        $answer = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                                ],
                                [
                                    ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                                    ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                                ],
                                [
                                    ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                                ]
                            ]
                        ]);

                        bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "*Pul yuborildi ✅*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $answer
                        ]);
                    }
                } else {
                    bot('deleteMessage',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                    ]);

                    $updateSalary = "UPDATE salary_amount SET status = 2 WHERE id = ".$salary_id[1];
                    $resultUpdateSalary = pg_query($conn, $updateSalary);

                    $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
                    $result = pg_query($conn, $stepUpdate);

                    if ($result == true){
                        $answer = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                                ],
                                [
                                    ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                                    ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                                ],
                                [
                                    ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                                ]
                            ]
                        ]);

                        bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "*Balansingizda mablag' yetarli emas ❗️*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $answer
                        ]);
                    }
                }
            }

        }
        else if ($salary_id[0] == "cancel"){
            deleteMessageUser($conn, $chat_id);

            $stepUpdate = "UPDATE salary_step SET step_2 = 101 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $stepUpdate = "UPDATE salary_last_id SET last_id = ".$salary_id[1]." WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            $selectAnswer = "SELECT * FROM salary_answer WHERE status = 1";
            $resultAnswer = pg_query($conn, $selectAnswer);

            $arr = [];
            if (pg_num_rows($resultAnswer) > 0) {
                while($row = pg_fetch_assoc($resultAnswer)) {
                    $answer_id = $row['id'];
                    $answer_name = $row['title'];
                    $arr_uz[] = ["text" => $answer_name, "callback_data" => "answer_".$answer_id."_".$salary_id[1]];
                    $row_arr[] = $arr_uz;
                    $arr_uz = [];
                }

                $row_arr[] = [["text" => "Boshqa javob kiritish ✍️", "callback_data" => "other"]];
                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Arizani rad etish sababini tanlang 🔘*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $btnKey
                ]);
            }
        }
        else if ($data == "home"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
            deleteMessageUser($conn, $chat_id);

            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            if ($result == true){
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
        else if ($data == "home_user"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $stepUpdate = "UPDATE salary_step SET step_2 = 50 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            if ($result == true){
                $menuUser = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul so'rash 💸",'callback_data' => 'ask_money'],
                        ],
                        [
                            ['text' => "Oylik olgan pullarim 📄",'callback_data' => 'month_money'],
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $menuUser
                ]);
            }
        }
    }

    if ($step_1 == 0 and $step_2 == 101 and $text != "/start"){
        $answer_id = explode("_", $data);
        if ($answer_id[0] == "answer"){
            $selectUser = "SELECT * FROM salary_amount AS sa 
    INNER JOIN users AS u ON sa.user_id = u.id
    WHERE sa.id = ".$answer_id[2];
            $resultSalary = pg_query($conn, $selectUser);

            if (pg_num_rows($resultSalary) > 0){
                $row = pg_fetch_assoc($resultSalary);
                $user_chat_id = $row['chat_id'];

                $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                $result = pg_query($conn, $stepUpdate);

                $deleteSalary = "DELETE FROM salary_amount WHERE id = ".$answer_id[2];
                $resultDelete = pg_query($conn, $deleteSalary);

                $selectAnswer = "SELECT * FROM salary_answer WHERE id = ".$answer_id[1];
                $resultAnswer = pg_query($conn, $selectAnswer);

                if (pg_num_rows($resultAnswer) > 0){
                    $rowAnswer = pg_fetch_assoc($resultAnswer);
                    $answer = $rowAnswer['title'];
                }

                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Bosh menu 🏠",'callback_data' => 'home'],
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $user_chat_id,
                    'text' => "*Sizning arizangiz admin tomonidan bekor qilindi ❗️*".PHP_EOL."*Sababi:* ".$answer,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $confirmMaterial
                ]);
            }

            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            if ($result == true){
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('editMessageText',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => "*Ariza bekor qilindi ❌*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
        else if ($answer_id[0] == "other"){
            bot('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);

            $stepUpdate = "UPDATE salary_step SET step_2 = 102 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => "Javobingizni yozing ⤵️",
                'parse_mode' => 'markdown',
            ]);
        }
    }

    if ($step_1 == 0 and $step_2 == 102 and $text != "/start"){
        if (isset($text)){
            $selectUser = "SELECT sa.chat_id FROM salary_amount AS sa 
    INNER JOIN users AS u ON sa.user_id = u.id
    WHERE sa.id = ".$last_id;
            $resultSalary = pg_query($conn, $selectUser);

            if (pg_num_rows($resultSalary) > 0){
                $row = pg_fetch_assoc($resultSalary);
                $user_chat_id = $row['chat_id'];

                $stepUpdate = "UPDATE salary_step SET step_2 = 100 WHERE chat_id = ".$user_chat_id;
                $result = pg_query($conn, $stepUpdate);

                $deleteSalary = "DELETE FROM salary_amount WHERE id = ".$last_id;
                $resultDelete = pg_query($conn, $deleteSalary);

                $confirmMaterial = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Bosh menu 🏠",'callback_data' => 'home_user'],
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $user_chat_id,
                    'text' => "*Sizning arizangiz admin tomonidan bekor qilindi ❗️*".PHP_EOL."*Sababi:* ".$text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $confirmMaterial
                ]);
            }

            $stepUpdate = "UPDATE salary_step SET step_2 = 1 WHERE chat_id = ".$chat_id;
            $result = pg_query($conn, $stepUpdate);

            if ($result == true){
                $answer = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "Pul berish ➕",'callback_data' => 'give_money'],
                        ],
                        [
                            ['text' => "Hisobot 📊",'callback_data' => 'statistics'],
                            ['text' => "Tasdiqlash kutayotgan summalar 📄",'callback_data' => 'confirm_money'],
                        ],
                        [
                            ['text' => "Bonus berish 💰",'callback_data' => 'bonus']
                        ]
                    ]
                ]);

                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => "*Ariza bekor qilindi ❌*".PHP_EOL."*Kerakli bo'limni tanlang ⤵️*",
                    'parse_mode' => 'markdown',
                    'reply_markup' => $answer
                ]);
            }
        }
    }
?>