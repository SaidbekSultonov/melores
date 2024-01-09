<?php
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // bunga tema Abdulaziz ---
    date_default_timezone_set('Asia/Tashkent');
    // ---
    
    $conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
    if ($conn) {
        echo "Success";
    }
    


    const TOKEN = '1594810052:AAHQEku5Q3tslozhq7uGiUpEB6oGUtzUXfs';
    const TOKEN_ADMIN = '1777589219:AAGBRhMdDuU8rCZiGfiwMfkPizWE2eKUiiU';
    const BASE_URL = 'https://api.telegram.org/bot' . TOKEN;
    const BASE_URL_ADMIN = 'https://api.telegram.org/bot' . TOKEN_ADMIN;
// ===================================================================== FUNCTIONS AND CLASSES
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
    
   
    function botAdmin($method, $data = [])
    {
        $url = BASE_URL_ADMIN . '/' . $method;
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

    // ob_start();

    function typing($ch)
    {
        return bot('sendChatAction', [
            'chat_id' => $ch,
            'action' => 'typing'
        ]);
    }

    function differenceInHours($startdate, $enddate)
    {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp) / 3600;
        return $difference;
    }

    $update = file_get_contents('php://input');
    $update = json_decode($update);
    $message_id = "";
    $text = "";
    $chat_id = "";
    $name = "";
    $username = "";
    $surname = "";
    $data = "";
    $audio = "";
    if (isset($update->message)) {
        $message = $update->message;
        $message_id = $message->message_id;
        $text = $message->text;
        $chat_id = $message->chat->id;
        $name = $message->chat->first_name;
        $username = $message->chat->username;
        $audio = $message->audio->file_id;
        if (isset($message->chat->last_name)) {
            $surname = $message->chat->last_name;
        }
    } else if (isset($update->callback_query)) {
        $data = $update->callback_query->data;
        $chat_id = $update->callback_query->message->chat->id;
        $message_id = $update->callback_query->message->message_id;
    }

    if (isset($text)) {
        typing($chat_id);
    }
    $txtArr = explode(" ", $text);
    $number = 'xatolik';
    if (!empty($txtArr)) {
        $start = (isset($txtArr[0])) ? $txtArr[0] : ' xatolik';
        $number = (isset($txtArr[1])) ? $txtArr[1] : ' xatolik';
    }
    
    
// ============================================================================================ TEXT ACTIONS
    $sqlDelete = "SELECT * FROM delete_messages WHERE chat_id = " . $chat_id . " ORDER BY id DESC";
    $resultDelete = pg_query($conn, $sqlDelete);
    if (pg_num_rows($resultDelete) > 0) {
        while ($rowDelete = pg_fetch_assoc($resultDelete)) {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $rowDelete["message_id"]
            ]);
        }
    }
    $drop = "DELETE FROM delete_messages WHERE chat_id = " . $chat_id;
    pg_query($conn, $drop);
    if ($text == "/start") { // birinchi startga tekshiriladi keyin ichida referal linkga
        $chechIsUserActive = "SELECT * FROM users WHERE chat_id = $chat_id AND status = 1";
        $resultIsUserActive = pg_query($conn, $chechIsUserActive);
        if (pg_num_rows($resultIsUserActive) > 0) {
            $row = pg_fetch_assoc($resultIsUserActive);
            $type = $row["type"];
            if ($type == 1) {
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        [
                            ["text" => "Barcha buyurtmalar"],
                            ["text" => "Mijozlar qo`shish ➕"]
                        ],
                        [
                            ["text" => "Mening buyurtmalarim"],
                            ["text" => "✅ Bitgan buyurtmalar"]
                        ],
                    ],
                    'resize_keyboard' => true
                ]);
            } else if ($type == 2 or $type == 4) {
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        [
                            ["text" => "Barcha buyurtmalar"],
                            ["text" => "Mijozlar qo`shish ➕"],
                        ],
                        ["Mening buyurtmalarim"],
                    ],
                    'resize_keyboard' => true
                ]);
            } else {
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        ["Barcha buyurtmalar"],
                        ["Mening buyurtmalarim"]
                    ],
                    'resize_keyboard' => true
                ]);
            }

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Asosiy bo'limga xush kelibsiz!",
                'reply_markup' => $bekor
            ]);
            $sqlUpdate = "UPDATE step_order SET step_2 = 1 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        } else {
            $sqlStep = "SELECT * FROM step_order WHERE chat_id = " . $chat_id;
            $resultStep = pg_query($conn, $sqlStep);
            if (!pg_num_rows($resultStep) > 0) {
                
                
                
                $sqlInsertStep = "INSERT INTO step_order (chat_id,step_1,step_2) VALUES (" . $chat_id . ",0,0)";
                $res = pg_query($sqlInsertStep);
                
                
                $sqlInsertLastId = "INSERT INTO last_id_order (chat_id,last_id) VALUES (" . $chat_id . ",0)";
                pg_query($conn, $sqlInsertLastId);
            } else {
                $sqlUpdateStep = "UPDATE step_order SET step_1 = 0, step_2 = 0 WHERE chat_id = $chat_id";
                pg_query($conn, $sqlUpdateStep);
                $sqlUpdateLastId = "UPDATE last_id_order SET last_id = 0 WHERE chat_id = $chat_id";
                pg_query($conn, $sqlUpdateLastId);
            }

            $replyMarkup = array(
                'keyboard' => array(
                    array(
                        array(
                            'text' => "📞Telefon raqam qoldirish",
                            'request_contact' => true
                        )
                    ),
                ),
                'one_time_keyboard' => true,
                'resize_keyboard' => true
            );
            $encodedMarkup = json_encode($replyMarkup);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Iltimos teleforn raqamingizni kiriting!",
                'reply_markup' => $encodedMarkup
            ]);
        }
    }
    $remove_keyboard = array(
        'remove_keyboard' => true
    );
    $sqlUpdate = "select * from last_id_order where chat_id = " . $chat_id;
    $resultSql = pg_query($conn, $sqlUpdate);
    if (pg_num_rows($resultSql) > 0) {
        $row = pg_fetch_assoc($resultSql);
        $deleteId = $row["message_id"];
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $deleteId
        ]);
    }

    $remove_keyboard = json_encode($remove_keyboard);
    $sqlStep = "SELECT * FROM step_order WHERE chat_id = " . $chat_id;
    $result = pg_query($conn, $sqlStep);
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $step_1 = (isset($row["step_1"])) ? $row["step_1"] : ' xatolik';
        $step_2 = (isset($row["step_2"])) ? $row["step_2"] : ' xatolik';
    }
    $menu = json_encode([
        'keyboard' => [
            ["🏘 Bosh menu"]
        ],
        'resize_keyboard' => true,
    ]);
    $menuAndback = json_encode([
        'keyboard' => [
            [
                ['text' => "⬅️ Ortga"], ['text' => "🏘 Bosh menu"]
            ]
        ],
        'resize_keyboard' => true,
    ]);
    $OnlyBack = json_encode([
        'keyboard' => [
            ["⬅️ Ortga"]
        ],
        'resize_keyboard' => true,
    ]);
    if ($text == "🏘 Bosh menu") {
        $sql = "select * from users where status = 1 and chat_id = " . $chat_id;
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $type = $row["type"];
            if ($type == 1) {
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        [
                            ["text" => "Barcha buyurtmalar"],
                        ],
                        [
                            ["text" => "Mijozlar qo`shish ➕"],
                            ["text" => "Mening buyurtmalarim"],
                        ],
                        ["✅ Bitgan buyurtmalar"]
                    ],
                    'resize_keyboard' => true
                ]);
            } else if ($type == 2 or $type == 4) {
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        [
                            ["text" => "Barcha buyurtmalar"],
                            ["text" => "Mijozlar qo`shish ➕"],
                        ],
                        ["Mening buyurtmalarim"]
                    ],
                    'resize_keyboard' => true
                ]);
            } else {
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        ["Barcha buyurtmalar"],
                        ["Mening buyurtmalarim"]
                    ],
                    'resize_keyboard' => true
                ]);
            }
            $sqlStep = "select * from step_order where chat_id = " . $chat_id;
            $resultStep = pg_query($conn, $sqlStep);
            if (!pg_num_rows($resultStep) > 0) {
                $sqlInsertStep = "insert into step_order (chat_id,step_1,step_2) values (" . $chat_id . ",0,0)";
                pg_query($sqlInsertStep);
                $sqlInsertLastId = "insert into last_id_order (chat_id,last_id) values (" . $chat_id . ",0)";
                pg_query($conn, $sqlInsertLastId);
            } else {
                $sqlUpdate = "update step_order step_2 = 0 where = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            }
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Orginal-Mebel botiga xush kelibsiz!",
                'reply_markup' => $bekor
            ]);
            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = " . $chat_id;
            pg_query($conn, $sqlUpdate);
        }
        $sqlDrop = "delete from clients where status = 0 and chat_id = " . $chat_id;
        pg_query($conn, $sqlDrop);
    }
    if ($step_1 == 0 and $text != "/start") {
        if (isset($update->message->contact)) {
            $number = $update->message->contact->phone_number;
            $number = str_replace("+", "", $number);

            $sql = "SELECT * FROM users WHERE status = 0 and phone_number = '" . $number . "'";
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                $sqlUpdate = "UPDATE users SET status = 1, chat_id = $chat_id WHERE phone_number = '" . $number . "' ";
                pg_query($conn, $sqlUpdate);
                $row = pg_fetch_assoc($result);
                $type = $row["type"];
                if ($type == 1) {
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            ["🔍 Buyurtmalarni qidirish"],
                            [
                                ["text" => "Barcha buyurtmalar"],
                                ["text" => "Mijozlar qo`shish ➕"]
                            ],
                            [
                                ["text" => "Mening buyurtmalarim"],
                                ["text" => "✅ Bitgan buyurtmalar"]
                            ],
                        ],
                        'resize_keyboard' => true
                    ]);
                } else if ($type == 2 or $type == 4) {
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            ["🔍 Buyurtmalarni qidirish"],
                            [
                                ["text" => "Barcha buyurtmalar"],
                                ["text" => "Mijozlar qo`shish ➕"],
                            ],
                            ["Mening buyurtmalarim"],
                        ],
                        'resize_keyboard' => true
                    ]);
                } else {
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            ["🔍 Buyurtmalarni qidirish"],
                            ["Barcha buyurtmalar"],
                            ["Mening buyurtmalarim"]
                        ],
                        'resize_keyboard' => true
                    ]);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Tekshiruvdan muvaffaqiyatli o`tingiz!",
                    'reply_markup' => $bekor
                ]);
                $sqlUpdate = "UPDATE step_order SET step_2 = 1 WHERE chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            } else {
                $sql = "SELECT * FROM users WHERE status = 1 and chat_id = $chat_id and phone_number = '" . $number . "'";
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    $row = pg_fetch_assoc($result);
                    $type = $row["type"];
                    if ($type == 1) {
                        $bekor = json_encode($bekor = [
                            'keyboard' => [
                                ["🔍 Buyurtmalarni qidirish"],
                                [
                                    ["text" => "Barcha buyurtmalar"],
                                    ["text" => "Mijozlar qo`shish ➕"]
                                ],
                                [
                                    ["text" => "Mening buyurtmalarim"],
                                    ["text" => "✅ Bitgan buyurtmalar"]
                                ],
                            ],
                            'resize_keyboard' => true
                        ]);
                    } else if ($type == 2 or $type == 4) {
                        $bekor = json_encode($bekor = [
                            'keyboard' => [
                                ["🔍 Buyurtmalarni qidirish"],
                                [
                                    ["text" => "Barcha buyurtmalar"],
                                    ["text" => "Mijozlar qo`shish ➕"],
                                ],
                                ["Mening buyurtmalarim"],
                            ],
                            'resize_keyboard' => true
                        ]);
                    } else {
                        $bekor = json_encode($bekor = [
                            'keyboard' => [
                                ["🔍 Buyurtmalarni qidirish"],
                                ["Barcha buyurtmalar"],
                                ["Mening buyurtmalarim"]
                            ],
                            'resize_keyboard' => true
                        ]);
                    }

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Botda faol ishlatuvchisiz!",
                        'reply_markup' => $bekor
                    ]);
                    $sqlUpdate = "UPDATE step_order SET step_2 = 1 WHERE chat_id = " . $chat_id;
                    $result = pg_query($conn, $sqlUpdate);
                } else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Siz bu raqam orqali yuqoridagi linkni ishlata olmaysiz"
                    ]);
                }
            }
        }
    }
    if ($step_2 == 1 and $text != "/start") {
        if ($text == "Barcha buyurtmalar") {
            // $user_title = Branch::find()->where(["status" => 1])->all();
            $sql = "select * from branch where status = 1";
            $result = pg_query($conn, $sql);
            $arr_uz = [];
            $row_arr = [];
            while ($value = pg_fetch_assoc($result)) {
                $branch_id = $value['id'];
                $branchTitle = $value['title'];
                $datacl = $branch_id . "_branch";
                $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $datacl];
                $row_arr[] = $arr_uz;
                $arr_uz = [];
            }
            $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
            $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            $getResult = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Filiallar bilan tanishing",
                'reply_markup' => $remove_keyboard
            ]);
            $questionMessageId = $getResult->result->message_id;
            $sqlUpdate = "update last_id_order set message_id = " . $questionMessageId . " where chat_id =  " . $chat_id;
            $resultUpdate = pg_query($conn, $sqlUpdate);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "<b>Filiallardan birini tanlang!</b>",
                'parse_mode' => 'html',
                'reply_markup' => $btnKey
            ]);
            $sqlUpdate = "update step_order set step_2 = 6 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        } else if ($text == "Mijozlar qo`shish ➕") {
            $sqlUpdate = "update step_order set step_2 = 2 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
            $sql = "select * from branch where status = 1";
            $result = pg_query($conn, $sql);
            $arr_uz = [];
            $row_arr = [];
            while ($value = pg_fetch_assoc($result)) {
                $branch_id = $value['id'];
                $branchTitle = $value['title'];
                $datacl = $branch_id . "_branch";
                $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $datacl];
                $row_arr[] = $arr_uz;
                $arr_uz = [];
            }
            $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
            $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Filiallar bilan tanishing",
                'reply_markup' => $remove_keyboard
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "<b>Filiallardan birini tanlang!</b>",
                'parse_mode' => 'html',
                'reply_markup' => $btnKey
            ]);
        } else if ($text == "Mening buyurtmalarim") {


            $findRole = "SELECT type FROM users WHERE chat_id = ".$chat_id;
            $resultRole = pg_query($conn, $findRole);
            if (pg_num_rows($resultRole) > 0) {
                $row = pg_fetch_assoc($resultRole);
                $requestRole = $row["type"];
            }

            
            if ($requestRole == 5) {
                $getId = "SELECT u.id FROM users AS u
                            INNER JOIN order_responsibles AS ors
                            ON u.id = ors.user_id
                            WHERE u.chat_id = ". $chat_id;
                $resultId = pg_query($conn, $getId);
                if (pg_num_rows($resultId) > 0) {
                    $wow = pg_fetch_assoc($resultId);
                    $brigadirId = $wow["id"];
                }

                $sql = "SELECT
                            o.id, 
                            o.user_id AS order_user_id, 
                            sm.user_id AS section_user_id, 
                            o.title, 
                            o.created_date, 
                            o.dead_line, 
                            sm.section_id, 
                            cl.full_name, 
                            s.title AS section_title, 
                            os.deadline AS deadline_of_section,
                            b.title AS branch_title,
                            u.type, 
                            o.description, 
                            ctg.title AS category_name
                            FROM users AS u
                            LEFT JOIN section_minimal AS sm ON u.id = sm.user_id
                            LEFT JOIN sections AS s ON s.id = sm.section_id
                            LEFT JOIN section_orders AS so ON so.section_id = s.id
                            LEFT JOIN orders AS o ON so.order_id = o.id
                            LEFT JOIN order_step AS os ON o.id = os.order_id AND s.id = os.section_id
                            LEFT JOIN branch AS b ON o.branch_id = b.id
                            LEFT JOIN clients AS cl ON o.client_id = cl.id
                            LEFT JOIN order_categories AS oc ON o.id = oc.order_id
                            LEFT JOIN category AS ctg ON oc.category_id = ctg.id
                            WHERE o.pause = 0 AND u.chat_id = $chat_id AND so.exit_date IS NULL ORDER BY o.id ASC";

                

            } else if ($requestRole == 10) {
                $getId = "SELECT u.id FROM users AS u
                            INNER JOIN order_responsibles AS ors
                            ON u.id = ors.user_id
                            WHERE u.chat_id = ". $chat_id;
                $resultId = pg_query($conn, $getId);
                if (pg_num_rows($resultId) > 0) {
                    $wow = pg_fetch_assoc($resultId);
                    $brigadirId = $wow["id"];
                    $workerOrderId = $wow['order_id'];
                } else {
                    $brigadirId = "Sizda buyurtmalar yo`q!";
                }

                $sql = "SELECT 
                            o.id, 
                            o.user_id AS order_user_id, 
                            sm.user_id AS section_user_id,
                            ors.section_id,
                            o.title, 
                            o.created_date, 
                            o.dead_line,
                            cl.full_name, 
                            s.title AS section_title, 
                            os.deadline AS deadline_of_section,
                            b.title AS branch_title,
                            u.type, 
                            o.description, 
                            ctg.title AS category_name
                            FROM users AS u
                            INNER JOIN order_responsibles AS ors ON u.id = ors.user_id
                            INNER JOIN sections AS s ON s.id = ors.section_id
                            INNER JOIN section_minimal AS sm ON sm.section_id = ors.section_id
                            INNER JOIN orders AS o ON o.id = ors.order_id
                            INNER JOIN order_step AS os ON o.id = os.order_id AND os.section_id = ors.section_id
                            INNER JOIN branch AS b ON o.branch_id = b.id
                            INNER JOIN clients AS cl ON o.client_id = cl.id
                            INNER JOIN order_categories AS oc ON o.id = oc.order_id
                            INNER JOIN category AS ctg ON oc.category_id = ctg.id
                            WHERE ors.user_id = $brigadirId AND o.pause = 0 AND o.end_date IS NULL";
            } else if ($requestRole == 7 || $requestRole == 2 || $requestRole == 6) {
                

                $getId = "SELECT u.id, ors.order_id FROM users AS u
                            INNER JOIN order_responsibles AS ors
                            ON u.id = ors.user_id
                            WHERE u.chat_id = ". $chat_id;
                $resultId = pg_query($conn, $getId);
                if (pg_num_rows($resultId) > 0) {
                    $wow = pg_fetch_assoc($resultId);
                    $brigadirId = $wow["id"];
                    $workerOrderId = $wow['order_id'];
                } else {
                    $brigadirId = "Sizda buyurtmalar yo`q!";
                }

                $sql = "SELECT 
                            o.id, 
                            o.user_id AS order_user_id, 
                            sm.user_id AS section_user_id,
                            ors.section_id,
                            o.title, 
                            o.created_date, 
                            o.dead_line, 
                            cl.full_name, 
                            s.title AS section_title, 
                            os.deadline AS deadline_of_section,
                            b.title AS branch_title,
                            u.type, 
                            o.description, 
                            ctg.title AS category_name
                            FROM users AS u
                            INNER JOIN order_responsibles AS ors ON u.id = ors.user_id
                            INNER JOIN sections AS s ON s.id = ors.section_id
                            INNER JOIN section_minimal AS sm ON sm.section_id = ors.section_id
                            INNER JOIN orders AS o ON o.id = ors.order_id
                            INNER JOIN order_step AS os ON o.id = os.order_id AND os.section_id = ors.section_id
                            INNER JOIN branch AS b ON o.branch_id = b.id
                            INNER JOIN clients AS cl ON o.client_id = cl.id
                            INNER JOIN order_categories AS oc ON o.id = oc.order_id
                            INNER JOIN category AS ctg ON oc.category_id = ctg.id
                            WHERE ors.user_id = $brigadirId AND o.pause = 0 AND o.end_date IS NULL";

                            // bot('sendMessage', [
                            //     'chat_id' => $chat_id,
                            //     'text' => $sql,
                            // ]);
                            // die();
            } else if ($requestRole == 1) {



                
                // $sql = "SELECT 
                //         o.id,
                //         o.user_id AS order_user_id,
                //         us.user_id AS section_user_id,
                //         o.title,
                //         o.created_date,
                //         o.dead_line,
                //         us.section_id,
                //         cl.full_name,
                //         s.title AS section_title,
                //         os.deadline AS deadline_of_section,
                //         b.title AS branch_title,
                //         us.role,
                //         o.description,
                //         ctg.title AS category_name
                //         FROM users AS u
                //         INNER JOIN users_section AS us ON u.id = us.user_id
                //         INNER JOIN sections AS s ON us.section_id = s.id
                //         INNER JOIN section_orders AS so ON s.id = so.section_id
                //         INNER JOIN orders AS o ON so.order_id = o.id
                //         INNER JOIN order_step AS os ON o.id = os.order_id AND s.id = os.section_id
                //         INNER JOIN branch AS b ON o.branch_id = b.id    
                //         INNER JOIN clients AS cl ON o.client_id = cl.id
                //         INNER JOIN order_categories AS oc ON o.id = oc.order_id
                //         INNER JOIN category AS ctg ON oc.category_id = ctg.id
                //         WHERE o.pause = 0 AND u.chat_id = 1270367 AND so.exit_date is null";

                $sql = " 

                    SELECT 
                         o.id,
                        o.user_id AS order_user_id,
                        u.user_id AS section_user_id,
                        o.title,
                        o.created_date,
                        o.dead_line,
                        or_res.section_id,
                        cl.full_name,
                        s.title AS section_title,
                        os.deadline AS deadline_of_section,
                        b.title AS branch_title,
                        u.type,
                        o.description,
                        ctg.title AS category_name 
                        FROM users AS u
                        INNER JOIN order_responsibles AS or_res ON or_res.user_id = u.id
                        INNER JOIN sections AS s ON or_res.section_id = s.id
                        INNER JOIN orders AS o ON or_res.order_id = o.id
                        INNER JOIN order_step AS os ON o.id = os.order_id AND s.id = os.section_id
                        INNER JOIN branch AS b ON o.branch_id = b.id    
                        INNER JOIN clients AS cl ON o.client_id = cl.id
                        INNER JOIN order_categories AS oc ON o.id = oc.order_id
                        INNER JOIN category AS ctg ON oc.category_id = ctg.id
                        WHERE u.chat_id = $chat_id  AND o.pause = 0 

                ";

            } else {
                return false;
            }


            

            $result = pg_query($conn, $sql);
            if (pg_num_rows($result)) {

                // bot('sendMessage', [
                //     'chat_id' => $chat_id,
                //     'text' => $sql,
                //     'parse_mode' => 'html',
                // ]);

                // return true;

                while ($row = pg_fetch_assoc($result)) {
                    $orderId = $row['id'];
                    $title = $row["title"];
                    $section_id = $row["section_id"];
                    $endDate = $row["dead_line"];
                    $stepOrderDeadline = $row["deadline_of_section"];
                    $crDate = $row["created_date"];
                    $sectionTitle = $row["section_title"];
                    $branch_title = $row["branch_title"];
                    $category_name = $row["category_name"];
                    $order_user_id = $row["order_user_id"];
                    $section_user_id = $row["section_user_id"];
                    $crDateNew = date("Y-m-d H:i", strtotime(date($crDate)));
                    $endDateNew = date("Y-m-d H:i", strtotime(date($endDate)));
                    $stepOrderDeadlineNew = date("Y-m-d H:i", strtotime(date($stepOrderDeadline)));
                    $phone_number = base64_decode($row["phone_number"]);
                    $clientName = base64_decode($row["full_name"]);
                    $camment = $row["description"];
                    if (!empty($camment)) {
                        $camment = $camment;
                    } else {
                        $camment = "";
                    }
                    $role = $row["type"];
                    $dataInfo = $row["id"] . "_info";
                    $dataAdd = $row["id"] . "_addWorker";
                    $dataReady = $row["id"] . "_ready_" . $section_id;

                    // ALTER
                    if ($requestRole == 5 || $requestRole == 1) {
                        $checkUserStatus = "SELECT * FROM brigada_leader WHERE user_id = $brigadirId";
                        $resultUserStatus = pg_query($conn, $checkUserStatus);
                        if (pg_num_rows($resultUserStatus) > 0) {
                            $checkStatus = "SELECT * FROM order_responsibles 
                                            WHERE user_id != $brigadirId AND order_id = $orderId
                                            AND id > (SELECT id FROM order_responsibles WHERE user_id = $brigadirId AND order_id = $orderId)";
                            $resultStatus = pg_query($conn, $checkStatus);
                            if (pg_num_rows($resultStatus) > 0) {
                                $readyInfoBtn = json_encode([
                                    'inline_keyboard' => [
                                        [
                                            ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                        ],
                                    ]
                                ]);
                            } else {
                                $checkIsBrigada = "SELECT * FROM brigada_leader WHERE user_id = $brigadirId";
                                $resultIsBrigada = pg_query($conn, $checkIsBrigada);
                                
                                if (pg_num_rows($resultIsBrigada) > 0) {
                                    $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 5";
                                    $resultPersonId = pg_query($conn, $getPersonId);
                                    $mow = pg_fetch_assoc($resultPersonId);
                                    $personId = $mow['id'];

                                    $personId = $personId."_brigadir_".$orderId;
                                    

                                    if ($mow['section_id'] == 35) {
                                        $readyInfoBtn = json_encode([
                                            'inline_keyboard' => [
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                                ],
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                                ],
                                            ]
                                        ]);
                                    }
                                    else{
                                        $readyInfoBtn = json_encode([
                                            'inline_keyboard' => [
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                                ],
                                                [
                                                    ['callback_data' => $personId, 'text' => "➡️ Brigadirga yuborish"]
                                                ],
                                            ]
                                        ]);
                                    }
                                } else {
                                    $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 5";
                                    $resultPersonId = pg_query($conn, $getPersonId);
                                    $mow = pg_fetch_assoc($resultPersonId);
                                    $personId = $mow['id'];

                                    $personId = $personId."_brigadir_".$orderId;
                                    $readyInfoBtn = json_encode([
                                        'inline_keyboard' => [
                                            [
                                                ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                            ],
                                        ]
                                    ]);
                                }
                            }
                        } else {
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                    ],
                                ]
                            ]);
                        }
                    } 
                    else if ($requestRole == 10) {
                        $checkStatus = "SELECT * FROM order_responsibles 
                                        WHERE user_id != $brigadirId AND order_id = $orderId
                                        AND id > (SELECT id FROM order_responsibles WHERE user_id = $brigadirId AND order_id = $orderId)";
                        $resultStatus = pg_query($conn, $checkStatus);
                        if (pg_num_rows($resultStatus) > 0) {
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                    ],
                                ]
                            ]);
                        } else {
                            $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 10";
                            $resultPersonId = pg_query($conn, $getPersonId);
                            $mow = pg_fetch_assoc($resultPersonId);
                            $personId = $mow['id'];

                            $personId = $personId."_".$orderId;

                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                    ],
                                    [
                                        ['callback_data' => $personId, 'text' => "➡️ Brigadaga jo`natish"]
                                    ]
                                ]
                            ]);
                        }                        
                    } 
                    else {

                        

                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                ],
                            ]
                        ]);
                    }



                    $txtSend = "#" . $title . " \n👨‍💻 Buyurtmachi: <b>" . $clientName . "</b>\n🏢 Buyurtma filiali: <b>" . $branch_title . "</b>\n🕓 Boshlangan Vaqti: <b>" . $crDateNew . "</b>\n⏳ Tugash vaqti: <b>" . $endDateNew . "</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle . "</b>\n⏳ Bo'limdan chiqish vaqti: <b>" . $stepOrderDeadlineNew . "</b>\n⚒ Kategoriya: <b>" . $category_name . "</b>\n💬 Izoh:<b> " . $camment . "</b>";
                    $getResult = bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $txtSend,
                        'parse_mode' => "html",
                        'reply_markup' => $readyInfoBtn
                    ]);
                    $deleteMessageId = $getResult->result->message_id;
                    $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
                    pg_query($conn, $sqlGo);
                }
            }



            $getResult = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Buyurtmalar barchasi shulardan iborat",
                'reply_markup' => $menu
            ]);
            $deleteMessageId = $getResult->result->message_id;
            $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
            pg_query($conn, $sqlGo);
            if ($requestRole == 10) {
                $sqlUpdate = "UPDATE step_order SET step_2 = 55 WHERE chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            } else {
                $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            }
        } else if ($text == "✅ Bitgan buyurtmalar") {
            $sql = "select * from branch where status = 1";
            $result = pg_query($conn, $sql);
            $arr_uz = [];
            $row_arr = [];
            while ($value = pg_fetch_assoc($result)) {
                $branch_id = $value['id'];
                $branchTitle = $value['title'];
                $datacl = $branch_id . "_branch";
                $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $datacl];
                $row_arr[] = $arr_uz;
                $arr_uz = [];
            }
            $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
            $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            $getResult = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Filiallar bilan tanishing",
                'reply_markup' => $remove_keyboard
            ]);
            $questionMessageId = $getResult->result->message_id;
            $sqlUpdate = "update last_id_order set message_id = " . $questionMessageId . " where chat_id =  " . $chat_id;
            $resultUpdate = pg_query($conn, $sqlUpdate);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "<b>Filiallardan birini tanlang!</b>",
                'parse_mode' => 'html',
                'reply_markup' => $btnKey
            ]);
            $sqlUpdate = "update step_order set step_2 = 200 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        } else if ($text == "🔍 Buyurtmalarni qidirish") {
            $sqlUpdate = "update step_order set step_2 = 210 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => 'Buyurtmani qidirsh uchun Buyurtma nomi yokida Mijoz ismini kiritng!'
            ]);
        }
    }
    if ($step_2 == 3 and $text != "/start") {
        if (isset($text)) {
            $txt = base64_encode($text);
            $sqlUpdate = "update clients set full_name = '" . $txt . "' where status = 0 and chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
            // $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
            $sqlUpdate = "update step_order set step_2 = 4 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Iltimos Buyurtma beruvchini Telefon raqamini kiritng!\n<b>(Masalan: 998941234567)</b>",
                'parse_mode' => "html"
            ]);
        }
    }
    if ($step_2 == 4 and $text != "/start") {
        if (preg_match("/^[0-9+]*$/", $text)) {
            $countNumber = strlen($text);
            if ($countNumber == 12) {
                $txt = base64_encode($text);
                $sqlUpdate = "update clients set phone_number = '" . $txt . "' where status = 0 and chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);

                $sqlUpdate = "update step_order set step_2 = 5 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
                $sql = "select * from clients as cl inner join branch as b on cl.branch_id = b.id where cl.status = 0 and cl.chat_id = " . $chat_id;
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    $row = pg_fetch_assoc($result);
                    $name = base64_decode($row["full_name"]);
                    $phone_number = base64_decode($row["phone_number"]);
                    $branchTitle = $row["title"];
                    $txtSend = "Malumotlarni tasdiqlang:\nIsmi " . $name . "\nRaqami: " . $phone_number . "\nFiliali: " . $branchTitle;
                    $deleteAndCheckBtn = json_encode([
                        'inline_keyboard' => [
                            [
                                ['callback_data' => "cancel", 'text' => "❌ Bekor qilish"], ['callback_data' => "confirm", 'text' => "✅ Tasdiqlash"]

                            ]
                        ]
                    ]);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $txtSend,
                        'reply_markup' => $deleteAndCheckBtn
                    ]);
                } else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "xatolik"
                    ]);
                }
            } else {
                $txtSend = "Iltimos telefon raqamni to`g`ri kiritng!\nMasalan: 998941234567";
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $txtSend
                ]);
            }
        } else {
            $txtSend = "Iltimos telefon raqamni to`g`ri kiritng!\nMasalan: 998941234567";
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $txtSend
            ]);
        }
    }   
    if ($step_2 == 10 and $text != "/start" and $text != "🏘 Bosh menu") {
        if ($text == "⬅️ Ortga") {
            $sql = "select 
                             o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
                             s.title as section_title , os.deadline as deadline_of_section,
                             b.title as branch_title , us.role , o.description , ctg.title as category_name
                             from users as u
                             inner join users_section as us on u.id = us.user_id
                             inner join sections as s on us.section_id = s.id
                             inner join section_orders as so on s.id = so.section_id
                             inner join orders as o on so.order_id = o.id
                             inner join order_step as os on o.id = os.order_id and s.id = os.section_id
                             inner join branch as b on o.branch_id = b.id    
                             inner join clients as cl on o.client_id = cl.id
                             inner join order_categories as oc on o.id = oc.order_id
                             inner join category as ctg on oc.category_id = ctg.id
                         where o.pause = 0 u.chat_id = " . $chat_id . " and so.exit_date is null";

            $result = pg_query($conn, $sql);
            if (pg_num_rows($result)) {
                while ($row = pg_fetch_assoc($result)) {
                    $title = $row["title"];
                    $section_id = $row["section_id"];
                    $endDate = $row["dead_line"];
                    $stepOrderDeadline = $row["deadline_of_section"];
                    $crDate = $row["created_date"];
                    $sectionTitle = $row["section_title"];
                    $branch_title = $row["branch_title"];
                    $category_name = $row["category_name"];
                    $order_user_id = $row["order_user_id"];
                    $section_user_id = $row["section_user_id"];
                    $crDateNew = date("Y-m-d H:i", strtotime(date($crDate)));
                    $endDateNew = date("Y-m-d H:i", strtotime(date($endDate)));
                    $stepOrderDeadlineNew = date("Y-m-d H:i", strtotime(date($stepOrderDeadline)));
                    $phone_number = base64_decode($row["phone_number"]);
                    $clientName = base64_decode($row["full_name"]);
                    $camment = $row["description"];
                    if (!empty($camment)) {
                        $camment = $camment;
                    } else {
                        $camment = "";
                    }
                    $role = $row["role"];
                    $dataInfo = $row["id"] . "_info";
                    $dataAdd = $row["id"] . "_addWorker";
                    $dataReady = $row["id"] . "_ready_" . $section_id;

                    if (($role == 5 or $role == 6) and $section_id != 35 or ($section_user_id == $order_user_id)) {
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]

                                ],
                            ]
                        ]);
                    } else if ($section_id == 35 and $order_user_id == 1) {
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataAdd, 'text' => "➕ Ishchi Biriktirish"]

                                ],
                            ]
                        ]);
                    } else {
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]

                                ],
                            ]
                        ]);
                    }
                    $txtSend = "#" . $title . " \n👨‍💻 Buyurtmachi: <b>" . $clientName . "</b>\n🏢 Buyurtma filiali :<b>" . $branch_title . "</b>\n🕓 Boshlangan Vaqti: <b>" . $crDateNew . "</b>\n⏳ Tugash vaqti: <b>" . $endDateNew . "</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle . "</b>\n⏳ Bo'limdan chiqish vaqti: <b>" . $stepOrderDeadlineNew . "</b>\n⚒ Kategoriya: <b>" . $category_name . "</b>\n💬 Izoh:<b> " . $camment . "</b>";
                    $getResult = bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $txtSend,
                        'parse_mode' => "html",
                        'reply_markup' => $readyInfoBtn
                    ]);
                    $deleteMessageId = $getResult->result->message_id;
                    $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
                    pg_query($conn, $sqlGo);
                }
            }

            $getResult = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Buyurtmalar barchasi shulardan iborat",
                'reply_markup' => $menu
            ]);
            $deleteMessageId = $getResult->result->message_id;
            $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
            pg_query($conn, $sqlGo);
            $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        } else {
            if (!isset($update->callback_query)) {
                $sqlLastOrder = "select us.id , lio.last_id from users as us
                             inner join last_id_order as lio on us.chat_id = lio.chat_id
                             where us.chat_id = " . $chat_id;

                $resultOrderLast = pg_query($conn, $sqlLastOrder);
                if (pg_num_rows($resultOrderLast) > 0) {
                    $row = pg_fetch_assoc($resultOrderLast);
                    $userId = $row["id"];
                    $orderId = $row["last_id"];
                }
                $sqlUsers = "SELECT u.type FROM section_orders AS so
                                    INNER JOIN order_responsibles AS ors ON so.order_id = ors.order_id
                                    INNER JOIN users AS u ON u.id = ors.user_id
                                    WHERE so.order_id = $orderId AND so.exit_date IS NULL AND u.chat_id = $chat_id";
                $resultStep = pg_query($conn, $sqlUsers);
                if (pg_num_rows($resultStep) > 0) {
                    $row = pg_fetch_assoc($resultStep);
                    $role = $row["type"];
                }
                if ($role == 5 || $role == 6 || $role == 10 || $role == 1 || $chat_id == 1967580 || $chat_id == 1270367 || $chat_id == 284914591 || $chat_id == 5669838528) {
                    
                    // bot('sendMessage', [
                    //     'chat_id' => $chat_id,
                    //     'text' => pg_num_rows($resultStep)
                    // ]);
                    
                    // return true;
                    
                    if (isset($message->text) and $text != "⬅️ Ortga") {
                        $file_id = base64_encode($text);
                        $type = "text";
                        $caption = base64_encode($text);
                    }
                    if (isset($message->photo)) {
                        if (isset($message->photo[2])) {
                            $file_id = $message->photo[2]->file_id;
                        } else if (isset($message->photo[1])) {
                            $file_id = $message->photo[1]->file_id;
                        } else {
                            $file_id = $message->photo[0]->file_id;
                        }
                        $caption = base64_encode($message->caption);
                        $type = "photo";
                    }

                    if (isset($message->video)) {
                        $file_id = $message->video->file_id;
                        $caption = base64_encode($message->caption);
                        $type = "video";
                    }
                    if (isset($message->document)) {
                        $file_id = $message->document->file_id;
                        $caption = base64_encode($message->caption);
                        $type = "document";
                    }
                    if (isset($message->video_note)) {
                        $file_id = $message->video_note->file_id;
                        $caption = "null";
                        $type = "video_note";
                    }
                    if (isset($message->audio)) {
                        $file_id = $message->audio->file_id;
                        $caption = base64_encode($message->caption);
                        $type = "audio";
                    }
                    if (isset($message->voice)) {
                        $file_id = $message->voice->file_id;
                        $caption = base64_encode($message->caption);
                        $type = "voice";
                    }
                    $dateformat = date("Y-m-d H:i:s");
                    $getResult = bot("copyMessage", [
                        'chat_id' => -1001444743477,
                        'from_chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $questionMessageId = $getResult->result->message_id;

                    if (isset($message->location)) {
                        $location = $message->location;
                        $lang = $location->longitude;
                        $lat = $location->latitude;
                        $type = "loc";
                        $sqlMaterials = "insert into order_materials (long,lat,type,order_id,created_date,status,chat_id,copy_message_id,user_id) values ('" . $lang . "','" . $lat . "','" . $type . "'," . $orderId . ",'" . $dateformat . "',1," . $chat_id . "," . $questionMessageId . "," . $userId . ")";
                    } else {
                        $sqlMaterials = "insert into order_materials (file,type,order_id,created_date,status,chat_id,copy_message_id,user_id) values ('" . $file_id . "','" . $type . "'," . $orderId . ",'" . $dateformat . "',1," . $chat_id . "," . $questionMessageId . "," . $userId . ")";
                    }

                    $result = pg_query($conn, $sqlMaterials);
                    if ($result == true) {
                        $sql = "select * from orders where id = " . $orderId;
                        $resultOrder = pg_query($conn, $sql);
                        $row = pg_fetch_assoc($resultOrder);
                        $order_name = $row["title"];
                        $sqlUpdate = "update step_order set step_2 = 10 where chat_id = " . $chat_id;
                        $result = pg_query($conn, $sqlUpdate);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "<b><i>" . $order_name . "</i></b> Yana ma'lumot qo'shishingiz mumkin!",
                            'parse_mode' => "html",
                            'reply_markup' => $OnlyBack
                        ]);

                    }
                } else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Siz buyurtmalarga ma'lumotlar kirita olmaysiz! 🚫"
                    ]);
                }
            }
        }
    }
    // =============================================================== ADD REQUIRED MATERIALS
    if ($step_2 == 15 and $text != "/start" and $text != "🏘 Bosh menu") {
        if ($text == "⬅️ Ortga") {
            $sql = "select 
                         o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
                         s.title as section_title , os.deadline as deadline_of_section,
                         b.title as branch_title , us.role , o.description, ctg.title as category_name
                         from users as u
                         inner join users_section as us on u.id = us.user_id
                         inner join sections as s on us.section_id = s.id
                         inner join section_orders as so on s.id = so.section_id
                         inner join orders as o on so.order_id = o.id
                         inner join order_step as os on o.id = os.order_id and s.id = os.section_id
                         inner join branch as b on o.branch_id = b.id    
                         inner join clients as cl on o.client_id = cl.id 
                         inner join order_categories as oc on o.id = oc.order_id
                         inner join category as ctg on oc.category_id = ctg.id
                     where o.pause = 0 and  u.chat_id = " . $chat_id . " and so.exit_date is null";

            $result = pg_query($conn, $sql);
            if (pg_num_rows($result)) {
                while ($row = pg_fetch_assoc($result)) {
                    $title = $row["title"];
                    $section_id = $row["section_id"];
                    $endDate = $row["dead_line"];
                    $stepOrderDeadline = $row["deadline_of_section"];
                    $crDate = $row["created_date"];
                    $sectionTitle = $row["section_title"];
                    $branch_title = $row["branch_title"];
                    $category_name = $row["category_name"];
                    $order_user_id = $row["order_user_id"];
                    $section_user_id = $row["section_user_id"];
                    $crDateNew = date("Y-m-d H:i", strtotime(date($crDate)));
                    $endDateNew = date("Y-m-d H:i", strtotime(date($endDate)));
                    $stepOrderDeadlineNew = date("Y-m-d H:i", strtotime(date($stepOrderDeadline)));
                    $phone_number = base64_decode($row["phone_number"]);
                    $clientName = base64_decode($row["full_name"]);
                    $camment = $row["description"];
                    if (!empty($camment)) {
                        $camment = $camment;
                    } else {
                        $camment = "";
                    }
                    $role = $row["role"];
                    $dataInfo = $row["id"] . "_info";
                    $dataAdd = $row["id"] . "_addWorker";
                    $dataReady = $row["id"] . "_ready_" . $section_id;

                    if (($role == 5 or $role == 6) and $section_id != 35 or ($section_user_id == $order_user_id)) {
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]

                                ],
                            ]
                        ]);
                    } else if ($section_id == 35 and $order_user_id == 1) {
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataAdd, 'text' => "➕ Ishchi Biriktirish"]

                                ],
                            ]
                        ]);
                    } else {
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]

                                ],
                            ]
                        ]);
                    }


                    $txtSend = "#" . $title . " \n👨‍💻 Buyurtmachi: <b>" . $clientName . "</b>\n🏢 Buyurtma filiali :<b>" . $branch_title . "</b>\n🕓 Boshlangan Vaqti: <b>" . $crDateNew . "</b>\n⏳ Tugash vaqti: <b>" . $endDateNew . "</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle . "</b>\n⏳ Bo'limdan chiqish vaqti: <b>" . $stepOrderDeadlineNew . "</b>\n⚒ Kategoriya: <b>" . $category_name . "</b>\n💬 Izoh:<b> " . $camment . "</b>";
                    $getResult = bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $txtSend,
                        'parse_mode' => "html",
                        'reply_markup' => $readyInfoBtn
                    ]);
                    $deleteMessageId = $getResult->result->message_id;
                    $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
                    pg_query($conn, $sqlGo);
                }
            }

            $getResult = bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Buyurtmalar barchasi shulardan iborat",
                'reply_markup' => $menu
            ]);
            $deleteMessageId = $getResult->result->message_id;
            $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
            pg_query($conn, $sqlGo);
            $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        } else {
            if (!isset($update->callback_query)) {
                if (isset($message->text)) {
                    $file_id = base64_encode($text);
                    $type = "text";
                    $caption = base64_encode($text);
                }
                if (isset($message->photo)) {
                    if (isset($message->photo[2])) {
                        $file_id = $message->photo[2]->file_id;
                    } else if (isset($message->photo[1])) {
                        $file_id = $message->photo[1]->file_id;
                    } else {
                        $file_id = $message->photo[0]->file_id;
                    }
                    $caption = base64_encode($message->caption);
                    $type = "photo";
                }

                if (isset($message->video)) {
                    $file_id = $message->video->file_id;
                    $caption = base64_encode($message->caption);
                    $type = "video";
                }
                if (isset($message->document)) {
                    $file_id = $message->document->file_id;
                    $caption = base64_encode($message->caption);
                    $type = "document";
                }
                if (isset($message->video_note)) {
                    $file_id = $message->video_note->file_id;
                    $caption = "null";
                    $type = "video_note";
                }
                if (isset($message->audio)) {
                    $file_id = $message->audio->file_id;
                    $caption = base64_encode($message->caption);
                    $type = "audio";
                }
                if (isset($message->voice)) {
                    $file_id = $message->voice->file_id;
                    $caption = base64_encode($message->caption);
                    $type = "voice";
                }
                $sqlLastOrder = "select us.id , lio.last_id,lio.type from users as us
                     inner join last_id_order as lio on us.chat_id = lio.chat_id
                     where us.chat_id = " . $chat_id;
                $resultOrderLast = pg_query($conn, $sqlLastOrder);
                if (pg_num_rows($resultOrderLast) > 0) {
                    $row = pg_fetch_assoc($resultOrderLast);
                    $MaterialId = $row["last_id"];
                    $orderId = $row["type"];
                }

                $dateformat = date("Y-m-d H:i:s");
                // $file_id = base64_encode($file_id);
                $getResult = bot("copyMessage", [
                    'chat_id' => -1001444743477,
                    'from_chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
                $questionMessageId = $getResult->result->message_id;
                $sqlMaterials = "update required_material_order set status = 1, file = '" . $file_id . "', chat_id = " . $chat_id . ", type = '" . $type . "',text = '" . $caption . "',message_id = " . $questionMessageId . " where id = " . $MaterialId;
                $result = pg_query($conn, $sqlMaterials);
                $sqlOrderMaterials = "select * from required_material_order WHERE id = " . $MaterialId;
                $resultOrderMaterials = pg_query($conn, $sqlOrderMaterials);
                $sqlUser = "SELECT id FROM users where chat_id = " . $chat_id;
                $resultUser = pg_query($conn, $sqlUser);
                $rowUser = pg_fetch_assoc($resultUser);
                $IdUser = $rowUser["id"];
                if (pg_num_rows($resultOrderMaterials) > 0) {
                    $rowMaterials = pg_fetch_assoc($resultOrderMaterials);
                    $file = $rowMaterials["file"];
                    $type = $rowMaterials["type"];
                    $order_idUser = $rowMaterials["order_id"];
                    $status = 1;
                    $created_date = date("Y-m-d H:s:i");

                    $sqlInsertOrder = "INSERT INTO order_materials (file,type,order_id,user_id,created_date,status,chat_id,copy_message_id) values('" . $file . "','" . $type . "'," . $order_idUser . "," . $IdUser . ",'" . $created_date . "'," . $status . "," . $chat_id . "," . $questionMessageId . ")";
                    pg_query($conn, $sqlInsertOrder);
                }
                $sqlGetRequiredMaterials = "SELECT r.title, o.title as order_name ,r.id FROM required_material_order as r 
                                                 INNER JOIN orders AS o ON r.order_id = o.id
                                             WHERE r.status = 0 and r.order_id = " . $orderId . " limit 1";
                $resultRequiredMaterials = pg_query($conn, $sqlGetRequiredMaterials);
                if (pg_num_rows($resultRequiredMaterials) > 0) {
                    $rowRequiredMaterials = pg_fetch_assoc($resultRequiredMaterials);
                    $idLast = $rowRequiredMaterials["id"];
                    $order_name = $rowRequiredMaterials["order_name"];
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b><i>" . $order_name . "</i></b> buyurtmasiga  <b>" . $rowRequiredMaterials["title"] . "</b>ni kiriting!\n",
                        'parse_mode' => "html",
                        'reply_markup' => $OnlyBack
                    ]);
                    $sqlUpdateLastId = "update last_id_order set last_id = " . $idLast . " where chat_id = " . $chat_id;
                    $resultStep = pg_query($conn, $sqlUpdateLastId);
                } else {
                    $sql = "select * from orders where id = " . $orderId;
                    $resultOrder = pg_query($conn, $sql);
                    $row = pg_fetch_assoc($resultOrder);
                    $order_name = $row["title"];
                    $sqlUpdate = "update step_order set step_2 = 10 where chat_id = " . $chat_id;
                    $result = pg_query($conn, $sqlUpdate);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b><i>" . $order_name . "</i></b> Yana ma'lumot qo'shishingiz mumkin!",
                        'parse_mode' => "html",
                        'reply_markup' => $OnlyBack
                    ]);

                    $sqlUpdateLastId = "update last_id_order set last_id = " . $orderId . " where chat_id = " . $chat_id;
                    $resultStep = pg_query($conn, $sqlUpdateLastId);
                }
            }
        }
    }
    if ($step_2 == 101 and $text != "/start" and $text != "🏘 Bosh menu") {
        if ($text == "⬅️ Ortga") {
            $sql = "select * from last_id_order WHERE chat_id = " . $chat_id;
            $resultLastId = pg_query($conn, $sql);
            if (pg_num_rows($resultLastId) > 0) {
                $row = pg_fetch_assoc($resultLastId);
                $last_id = $row["last_id"];
            }
            $sqlOrders = "select
                     ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title
                     from orders as ord
                     inner join clients as cl on ord.client_id = cl.id
                     inner join section_orders as so on ord.id = so.order_id
                     inner join sections as sec on so.section_id = sec.id
                     inner join order_categories as oc on ord.id = oc.order_id
                     inner join category as ctg on oc.category_id = ctg.id
                 where ord.id = " . $last_id;
            $resultOrders = pg_query($conn, $sqlOrders);
            if (pg_num_rows($resultOrders) > 0) {
                while ($row = pg_fetch_assoc($resultOrders)) {
                    $title = $row["title"];
                    $endDate = $row["dead_line"];
                    $crDate = $row["created_date"];
                    $sectionTitle = $row["section_title"];
                    $category_name = $row["category_name"];
                    $phone_number = base64_decode($row["phone_number"]);
                    $clientName = base64_decode($row["full_name"]);

                    $dataInfo = $row["id"] . "_info";
                    $dataReady = $row["id"] . "_ready_" . $keyThirst;
                    $readyInfoBtn = json_encode([
                        'inline_keyboard' => [
                            [
                                ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]

                            ],
                            [
                                ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                            ]
                        ]
                    ]);

                    $txtSend = "#" . $title . " \nIsmi: <b>" . $clientName . "</b>\nBoshlangan Vaqti: 🕓 <b>" . $crDate . "</b>\nTugash vaqti: ⏳ <b>" . $endDate . "</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle . "</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name . "</b>\nTelefon raqami: 📞  +" . $phone_number;
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => $txtSend,
                        'parse_mode' => "html",
                        'reply_markup' => $readyInfoBtn
                    ]);

                }
            }
            $sqlUpdate = "update step_order set step_2 = 100 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        }
    }
// =============================================================================================== CALLBACK ACTIONS
    if (isset($update->callback_query)) {
        $dataArr = explode("_", $data);
        $key = (!empty($dataArr[0])) ? $dataArr[0] : ' xatolik';
        $keySecond = (!empty($dataArr[1])) ? $dataArr[1] : ' xatolik';
        $keyThirst = (!empty($dataArr[2])) ? $dataArr[2] : ' xatolik';
        $branch = $key . "_branch";
        $category = $key . "_category";
        $clients = $key . "_clients";
        $info = $key . "_info";
        $brigadir = $key . "_brigadir_".$keyThirst;
        $ready = $key . "_ready_" . $keyThirst;
        $realyDone = $key . "_confirm_" . $keyThirst;
        $cancelDone = $key . "_cancelReady_" . $keyThirst;
        $ordersdata = $key . "_" . $keySecond . "_" . $keyThirst . "_orders";
        $lookOrders = $key . "_lookOrders_" . $keyThirst;
        $checkWorkersData = $key . "_workerId_" . $keyThirst;
        $checkWorkers = $key . "_confirmWokers_" . $keyThirst;
        $cancelWorkers = $key . "_cancelWorkers_" . $keyThirst;
        $addWorker = $key . "_addWorker";
        $nextClData = $key . "_nextClient";
        $backClData = $key . "_backClient";

        if ($data == "home") {
            $sql = "select * from users where status = 1 and chat_id = " . $chat_id;
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                $row = pg_fetch_assoc($result);
                $type = $row["type"];
                if ($type == 1) {
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            ["🔍 Buyurtmalarni qidirish"],
                            [
                                ["text" => "Barcha buyurtmalar"],
                                ["text" => "Mijozlar qo`shish ➕"],
                            ],
                            [
                                ["text" => "Mening buyurtmalarim"],
                                ["text" => "✅ Bitgan buyurtmalar"],
                            ]
                        ],
                        'resize_keyboard' => true
                    ]);
                } else if ($type == 2 or $type == 4) {
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            ["🔍 Buyurtmalarni qidirish"],
                            [
                                ["text" => "Barcha buyurtmalar"],
                                ["text" => "Mijozlar qo`shish ➕"],
                            ],
                            ["Mening buyurtmalarim"]
                        ],
                        'resize_keyboard' => true
                    ]);
                } else {
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            ["🔍 Buyurtmalarni qidirish"],
                            ["Barcha buyurtmalar"],
                            ["Mening buyurtmalarim"],
                        ],
                        'resize_keyboard' => true
                    ]);
                }
                $sqlStep = "select * from step_order where chat_id = " . $chat_id;
                $resultStep = pg_query($conn, $sqlStep);
                if (!pg_num_rows($resultStep) > 0) {
                    $sqlInsertStep = "insert into step_order (chat_id,step_1,step_2) values (" . $chat_id . ",0,0)";
                    pg_query($sqlInsertStep);
                    $sqlInsertLastId = "insert into last_id_order (chat_id,last_id) values (" . $chat_id . ",0)";
                    pg_query($conn, $sqlInsertLastId);
                } else {
                    $sqlUpdate = "update step_order step_2 = 0 where = " . $chat_id;
                    $result = pg_query($conn, $sqlUpdate);
                }
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Orginal Mebel botiga xush kelibsiz!",
                    'reply_markup' => $bekor
                ]);
                $sqlUpdate = "update step_order set step_2 = 1 where chat_id = " . $chat_id;
                pg_query($conn, $sqlUpdate);
            }
            $sqlDrop = "delete from clients where status = 0 and chat_id = " . $chat_id;
            pg_query($conn, $sqlDrop);
        }

        if ($step_2 == 2) {
            if ($data == $branch) {
                $sqlUpdate = "update step_order set step_2 = 3 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
                $sqlInsert = "insert into clients (chat_id,status,full_name,phone_number,branch_id) values (" . $chat_id . ",0,'test','12345'," . $key . ")";
                $result = pg_query($conn, $sqlInsert);

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Iltimos Buyurtma beruvchini Ismini kiritng!"
                ]);
            }
        }

        if ($step_2 == 5) {
            if ($data == "confirm") {
                $sql = "update clients set status = 1 where status = 0 and chat_id = " . $chat_id;
                $result = pg_query($conn, $sql);
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        [
                            ["text" => "Barcha buyurtmalar"],
                            ["text" => "Mijozlar qo`shish ➕"]
                        ]
                    ],
                    'resize_keyboard' => true
                ]);
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Foydalanuvchi muvaffaqiyatli qo`shildi!,Marhamat",
                    'reply_markup' => $bekor
                ]);
                $sqlUpdate = "update step_order set step_2 = 1 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            }
            if ($data == "cancel") {
                $sqlDrop = "delete from clients status = 0 and chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlDrop);
                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        [
                            ["text" => "Barcha buyurtmalar"],
                            ["text" => "Mijozlar qo`shish ➕"]
                        ]
                    ],
                    'resize_keyboard' => true
                ]);
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Foydalanuvchi Qo`shilmadi!,Marhamat",
                    'reply_markup' => $bekor
                ]);
                $sqlUpdate = "update step_order set step_2 = 1 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            }
        }

        if ($step_2 == 6) {
            if ($data == $branch) {
                $sqlUpdateLast = "update last_id_order set order_type = 0 where chat_id = " . $chat_id;
                $resultUpdateLast = pg_query($conn, $sqlUpdateLast);

                $sqlUpdate = "update step_order set step_2 = 7 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);

                $sql = "select * from clients where status = 1 and branch_id = " . $key . " limit 20";
                $result = pg_query($conn, $sql);
                $sqlSecond = "select * from clients where status = 1 and branch_id = " . $key;
                $resultSecond = pg_query($conn, $sqlSecond);
                // $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
                $arr_uz = [];
                $row_arr = [];
                if (pg_num_rows($result) > 0) {
                    while ($value = pg_fetch_assoc($result)) {
                        $client_id = $value['id'];
                        $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = " . $client_id;
                        $resultOrder = pg_query($conn, $sqlOrder);
                        $branchTitle = base64_decode($value['full_name']);
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id . "_clients"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $nextClients = $client_id . "_nextClient";
                    if (pg_num_rows($resultSecond) > 20) {
                        $row_arr[] = [["text" => "➡️ Boshqa Mijozlar", "callback_data" => $nextClients]];
                    }
                    $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);

                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Mijozlardan birini tanlang!",
                        'reply_markup' => $btnKey
                    ]);
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
                        'reply_markup' => $menu
                    ]);
                }
            }
        }

        if ($step_2 == 7) {
            if ($data == $clients) {
                $sqlOrderType = "SELECT * FROM last_id_order WHERE chat_id = " . $chat_id;
                $resultOrderType = pg_query($conn, $sqlOrderType);
                $rowOrderType = pg_fetch_assoc($resultOrderType);
                $order_type = $rowOrderType["order_type"];
                if ($order_type == 1) {
                    $sqlOrders = "
                    select
                      ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number, ctg.title as category_name
                    from orders as ord
                    inner join clients as cl on ord.client_id = cl.id
                    inner join section_orders as so on ord.id = so.order_id
                    inner join sections as sec on so.section_id = sec.id
                    inner join order_categories as oc on ord.id = oc.order_id
                    inner join category as ctg on oc.category_id = ctg.id
                    where ord.pause = 0 and ord.status = 1 and  ord.client_id = " . $key . " group by 
                    ord.id,
                    ord.title,
                    ord.created_date,
                    ord.dead_line,
                    cl.full_name,
                    cl.phone_number,
                    ctg.title";
                } else if ($order_type == 0) {
                    $sqlOrders = "select
                      ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number, ctg.title as category_name
                    from orders as ord
                    inner join clients as cl on ord.client_id = cl.id
                    inner join section_orders as so on ord.id = so.order_id
                    inner join sections as sec on so.section_id = sec.id
                    inner join order_categories as oc on ord.id = oc.order_id
                    inner join category as ctg on oc.category_id = ctg.id
                    where ord.pause = 0 and ord.status = 0 and  ord.client_id = " . $key . " group by 
                    ord.id,
                    ord.title,
                    ord.created_date,
                    ord.dead_line,
                    cl.full_name,
                    cl.phone_number,
                    ctg.title";
                }

                $resultOrders = pg_query($conn, $sqlOrders);
                if (pg_num_rows($resultOrders) > 0) {
                    while ($row = pg_fetch_assoc($resultOrders)) {

                        $title = $row["title"];
                        $endDate = $row["dead_line"];
                        $crDate = $row["created_date"];
                        $category_name = $row["category_name"];
                        $sectionTitle = $row["section_title"];
                        $phone_number = base64_decode($row["phone_number"]);
                        $clientName = base64_decode($row["full_name"]);

                        $dataInfo = $row["id"] . "_info";
                        $dataReady = $row["id"] . "_ready_" . $keyThirst;
                        $readyInfoBtn = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]

                                ],
                                [
                                    ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                                ]
                            ]
                        ]);

                        $txtSend = "#" . $title . " \nIsmi: <b>" . $clientName . "</b>\nBoshlangan Vaqti: 🕓 <b>" . $crDate . "</b>\nTugash vaqti: ⏳ <b>" . $endDate . "</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name . "</b>\nTelefon raqami: 📞  +" . $phone_number;

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => $txtSend,
                            'parse_mode' => "html",
                            'reply_markup' => $readyInfoBtn
                        ]);

                    }
                }
                $sqlUpdate = "update step_order set step_2 = 100 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);

            } else if ($data == $nextClData) {
                $sqlGetBranchId = "select * from clients where id = " . $key;
                $resultGetBranchid = pg_query($conn, $sqlGetBranchId);
                $rowGetBranchId = pg_fetch_assoc($resultGetBranchid);
                $branchId = $rowGetBranchId["branch_id"];
                $sql = "select * from clients where status = 1 and branch_id = " . $branchId . " and id > " . $key . " limit 20";
                $result = pg_query($conn, $sql);
                $sqlSecond = "select * from clients where status = 1 and  id > " . $key . " and branch_id = " . $branchId;
                $resultSecond = pg_query($conn, $sqlSecond);

                $arr_uz = [];
                $row_arr = [];
                if (pg_num_rows($result) > 0) {
                    while ($value = pg_fetch_assoc($result)) {
                        $client_id = $value['id'];
                        $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = " . $client_id;
                        $resultOrder = pg_query($conn, $sqlOrder);
                        $branchTitle = base64_decode($value['full_name']);
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id . "_clients"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $nextClients = $client_id . "_nextClient";
                    $backClients = $client_id . "_backClient";
                    if (pg_num_rows($resultSecond) > 20) {
                        $row_arr[] = [
                            ["text" => "⬅️ Oldingi Mijozlar", "callback_data" => $backClients],
                            ["text" => "➡️ Boshqa Mijozlar", "callback_data" => $nextClients]
                        ];
                    } else {
                        $row_arr[] = [
                            ["text" => "⬅️ Oldingi Mijozlar", "callback_data" => $backClients]
                        ];
                    }
                    $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);

                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Mijozlardan birini tanlang!",
                        'reply_markup' => $btnKey
                    ]);
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
                        'reply_markup' => $menu
                    ]);
                }
            } else if ($data == $backClData) {
                $sqlGetBranchId = "select * from clients where id = " . $key;
                $resultGetBranchid = pg_query($conn, $sqlGetBranchId);
                $rowGetBranchId = pg_fetch_assoc($resultGetBranchid);
                $branchId = $rowGetBranchId["branch_id"];
                $sql = "select * from clients where status = 1 and branch_id = " . $branchId . " and id < " . $key . " limit 20";
                $result = pg_query($conn, $sql);
                $sqlSecond = "select * from clients where status = 1 and  id < " . $key . " and branch_id = " . $key;
                $resultSecond = pg_query($conn, $sqlSecond);
                // $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
                $arr_uz = [];
                $row_arr = [];
                // bot('sendMessage',[
                //   'chat_id' => $chat_id,
                //   'text' => "Success \n".$sql.PHP_EOL.$sqlSecond
                // ]);
                if (pg_num_rows($result) > 0) {
                    while ($value = pg_fetch_assoc($result)) {
                        $client_id = $value['id'];
                        $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = " . $client_id;
                        $resultOrder = pg_query($conn, $sqlOrder);
                        $branchTitle = base64_decode($value['full_name']);
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id . "_clients"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $nextClients = $client_id . "_nextClient";
                    $backClients = $client_id . "_backlient";
                    if (pg_num_rows($resultSecond) > 20) {
                        $row_arr[] = [
                            ["text" => "⬅️ Oldingi Mijozlar", "callback_data" => $backClients],
                            ["text" => "➡️ Boshqa Mijozlar", "callback_data" => $nextClients]
                        ];
                    } else {
                        $row_arr[] = [
                            ["text" => "➡️ Boshqa Mijozlar", "callback_data" => $nextClients]
                        ];
                    }
                    $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);

                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Mijozlardan birini tanlang!",
                        'reply_markup' => $btnKey
                    ]);
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
                        'reply_markup' => $menu
                    ]);
                }
            }
        }

        if ($step_2 == 8) {
            if ($data == $ordersdata) {
                $sqlUpdate = "update step_order set step_2 = 9 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
                $sqlUsers = "select * from users where chat_id = " . $chat_id;
                $resultStep = pg_query($conn, $sqlUsers);
                if (pg_num_rows($resultStep) > 0) {
                    $row = pg_fetch_assoc($resultStep);
                    $type = $row["type"];
                }
                $sqlUpdateTwo = "update last_id_order set last_id = " . $keySecond . ",message_id = " . $keyThirst . " where chat_id = " . $chat_id;
                $pg_query = pg_query($conn, $sqlUpdateTwo);
                if ($type != 5 and $type != 6 and $type != 7) {
                    $sqlOrders = "select
                         ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title,
                         ctg.title as category_name
                         from orders as ord 
                         inner join clients as cl on ord.client_id = cl.id
                         inner join section_orders as so on ord.id = so.order_id
                         inner join sections as sec on so.section_id = sec.id
                         inner join order_categories as oc on o.id = oc.order_id
                         inner join category as ctg on oc.category_id = ctg.id
                     where ord.client_id = " . $keyThirst . " and so.order_id = " . $keySecond;
                    $resultOrders = pg_query($conn, $sqlOrders);
                    if (pg_num_rows($resultOrders) > 0) {
                        while ($row = pg_fetch_assoc($resultOrders)) {

                            $title = $row["title"];
                            $endDate = $row["dead_line"];
                            $category_name = $row["category_name"];
                            $crDate = $row["created_date"];
                            $sectionTitle = $row["section_title"];
                            $phone_number = base64_decode($row["phone_number"]);
                            $clientName = base64_decode($row["full_name"]);

                            $dataInfo = $row["id"] . "_info";
                            $dataReady = $row["id"] . "_ready_" . $keyThirst;
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]

                                    ],
                                    [
                                        ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                                    ]
                                ]
                            ]);

                            $txtSend = "#" . $title . " \nIsmi: <b>" . $clientName . "</b>\nBoshlangan Vaqti: 🕓 <b>" . $crDate . "</b>\nTugash vaqti: ⏳ <b>" . $endDate . "</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle . "</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name . "</b>\nTelefon raqami: 📞  +" . $phone_number;
                            if ($type != 4) {
                                bot('editMessageText', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id,
                                    'text' => $txtSend,
                                    'parse_mode' => "html",
                                    'reply_markup' => $readyInfoBtn
                                ]);
                            } else if ($type == 4) {
                                bot('editMessageText', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id,
                                    'text' => $txtSend,
                                    'parse_mode' => "html"
                                ]);
                            }
                        }
                    }
                } else if ($type == 5 or $type == 7 or $type == 6) {

                    $sqlOrders = "select 
                           ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title , os.deadline as deadline_of_section, ctg.title as category_name
                         from orders as ord 
                         inner join clients as cl on ord.client_id = cl.id
                         
                         inner join section_orders as so on ord.id = so.order_id
                         inner join sections as sec on so.section_id = sec.id
                         inner join order_step as os on os.order_id = ord.id and os.section_id = sec.id
                         inner join users_section as us on sec.id = us.section_id
                         inner join users as use on us.user_id = use.id
                         inner join order_categories as oc on ord.id = oc.order_id
                         inner join category as ctg on oc.category_id = ctg.id
                         where ord.id = " . $keySecond . " and ord.client_id = " . $keyThirst . " and use.chat_id = " . $chat_id . " and so.exit_date is null";
                    $resultOrders = pg_query($conn, $sqlOrders);
                    if (pg_num_rows($resultOrders) > 0) {
                        while ($row = pg_fetch_assoc($resultOrders)) {
                            $title = $row["title"];
                            $endDate = $row["dead_line"];
                            $crDate = $row["created_date"];
                            $sectionTitle = $row["section_title"];
                            $deadline_of_section = $row["deadline_of_section"];
                            $phone_number = base64_decode($row["phone_number"]);
                            $clientName = base64_decode($row["full_name"]);

                            $dataInfo = $row["id"] . "_info";
                            $dataReady = $row["id"] . "_ready_" . $keyThirst;
                            if ($type == 5 or $type == 6) {
                                $readyInfoBtn = json_encode([
                                    'inline_keyboard' => [
                                        [
                                            ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]

                                        ],
                                        [
                                            ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                                        ]
                                    ]
                                ]);
                            } else if ($type == 7) {
                                $readyInfoBtn = json_encode([
                                    'inline_keyboard' => [
                                        [
                                            ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                                        ],
                                    ]
                                ]);
                            }

                            $txtSend = "#" . $title . " \nIsmi: <b>" . $clientName . "</b>\nBoshlangan Vaqti: 🕓 <b>" . $crDate . "</b>\nTugash vaqti: ⏳ <b>" . $endDate . "</b>\nBo`lim tugalash vaqti <b>" . $deadline_of_section . "</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle . "</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name . "</b>\nTelefon raqami: 📞  +" . $phone_number;
                            if ($type != 4) {
                                bot('editMessageText', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id,
                                    'text' => $txtSend,
                                    'parse_mode' => "html",
                                    'reply_markup' => $readyInfoBtn
                                ]);
                            } else if ($type == 4) {
                                bot('editMessageText', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id,
                                    'text' => $txtSend,
                                    'parse_mode' => "html"
                                ]);
                            }
                        }
                    } else {
                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Sizning bo`limingizda buyurtmalar yo`q 🙃!",
                            "reply_markup" => $menu
                        ]);
                    }
                }
            }
        }

        if ($step_2 == 9) {
            if ($data != $brigadir) {
                if ($data == $info) {
                    $sqlUsers = "SELECT u.type FROM section_orders AS so
                                    INNER JOIN order_responsibles AS ors ON so.order_id = ors.order_id
                                    INNER JOIN users AS u ON u.id = ors.user_id
                                    WHERE so.order_id = $key AND so.exit_date IS NULL AND u.chat_id = $chat_id";
                    $resultStep = pg_query($conn, $sqlUsers);
                    if (pg_num_rows($resultStep) > 0) {
                        $row = pg_fetch_assoc($resultStep);
                        $role = $row["type"];
                    }
                    $sqlGetRequiredMaterials = "SELECT 
                                                 r.title, 
                                                 o.title as order_name,
                                                 r.id FROM required_material_order as r 
                                                 INNER JOIN orders AS o ON r.order_id = o.id
                                                 WHERE r.status = 0 and r.order_id = " . $key . " limit 1";
                    $resultRequiredMaterials = pg_query($conn, $sqlGetRequiredMaterials);
                    if (pg_num_rows($resultRequiredMaterials) > 0 and $role == 6) {

                        $sqlUpdate = "update step_order set step_2 = 15 where chat_id = " . $chat_id;
                        $result = pg_query($conn, $sqlUpdate);
                        $rowRequiredMaterials = pg_fetch_assoc($resultRequiredMaterials);
                        $idLast = $rowRequiredMaterials["id"];
                        $order_name = $rowRequiredMaterials["order_name"];
                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "<b><i>" . $order_name . "</i></b> buyurtmasiga  <b>" . $rowRequiredMaterials["title"] . "</b>ni kiriting!\n",
                            'parse_mode' => "html",
                            'reply_markup' => $OnlyBack
                        ]);
                        $sqlUpdateLastId = "update last_id_order set last_id = " . $idLast . ",type=" . $key . " where chat_id = " . $chat_id;
                        $resultStep = pg_query($conn, $sqlUpdateLastId);
                    } else {
                        $sqlUpdate = "UPDATE step_order SET step_2 = 10 WHERE chat_id = " . $chat_id;
                        $result = pg_query($conn, $sqlUpdate);

                        $sql = "SELECT * FROM order_materials WHERE order_id = " . $key;
                        $resultOrder = pg_query($conn, $sql);
                        $sqlUpdateLastId = "UPDATE last_id_order SET last_id = $key WHERE chat_id = " . $chat_id;
                        $resultStep = pg_query($conn, $sqlUpdateLastId);

                        $requiredMaterials = "SELECT * FROM required_material_order where order_id = " . $key;
                        $resultMaterials = pg_query($conn, $requiredMaterials);
                        if (pg_num_rows($resultOrder) > 0 or pg_num_rows($resultMaterials) > 0) {
                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);
                            while ($row = pg_fetch_assoc($resultOrder)) {
                                $getResult = bot("copyMessage", [
                                    'chat_id' => $chat_id,
                                    'from_chat_id' => -1001444743477,
                                    'message_id' => $row["copy_message_id"]
                                ]);
                            }
                            while ($rowTwo = pg_fetch_assoc($resultMaterials)) {
                                $getResult = bot("copyMessage", [
                                    'chat_id' => $chat_id,
                                    'from_chat_id' => -1001444743477,
                                    'message_id' => $rowTwo["copy_message_id"]
                                ]);
                            }
                            if ($role == 6) {
                                $sql = "select * from orders where id = " . $key;
                                $resultOrder = pg_query($conn, $sql);
                                $row = pg_fetch_assoc($resultOrder);
                                $order_name = $row["title"];
                                $sqlUpdate = "update step_order set step_2 = 10 where chat_id = " . $chat_id;
                                $result = pg_query($conn, $sqlUpdate);
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "<b><i>" . $order_name . "</i></b> Yana ma'lumot qo'shishingiz mumkin!",
                                    'parse_mode' => "html",
                                    'reply_markup' => $OnlyBack
                                ]);

                            } else {
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Buyurtmadagi ma'lumotlar shulardan iborat edi!",
                                    'reply_markup' => $OnlyBack
                                ]);
                            }
                        } else {
                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);
                            if ($role == 6) {
                                $sql = "SELECT * FROM orders WHERE id = " . $key;
                                $resultOrder = pg_query($conn, $sql);
                                $row = pg_fetch_assoc($resultOrder);
                                $order_name = $row["title"];
                                $sqlUpdate = "UPDATE step_order SET step_2 = 10 WHERE chat_id = " . $chat_id;
                                $result = pg_query($conn, $sqlUpdate);
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Hozircha <b><i>" . $order_name . "</i></b> nomli  buyurtmada malumotlar mavjud emas,\nHohlasangiz ma'lumotlar kiritishingiz mumkin! 🙃",
                                    'parse_mode' => "html",
                                    'reply_markup' => $menu
                                ]);
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Hozircha Buyurtma malumotlari mavjud emas,\nHohlasangiz ma'lumotlar kiritishingiz mumkin! 🙃",
                                    'reply_markup' => $OnlyBack
                                ]);
                            } else {
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Hozircha Buyurtma malumotlari mavjud emas! 🙃",
                                    'reply_markup' => $OnlyBack
                                ]);
                            }
                        }
                    }
                } else if ($data == $ready) {
                    $sql = "UPDATE step_order SET step_2 = 20 where chat_id = " . $chat_id;
                    pg_query($conn, $sql);
                    $dataCancel = $key . "_cancelReady_" . $keyThirst;
                    $dataConfirm = $key . "_confirm_" . $keyThirst;
                    $readyInfoBtn = json_encode([
                        'inline_keyboard' => [
                            [
                                ['callback_data' => $dataCancel, 'text' => "Bekor qilish 🚫"], ['callback_data' => $dataConfirm, 'text' => "✅ Tasdiqlayman"]

                            ],
                            [
                                ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                            ]
                        ]
                    ]);
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Buyurtma bitganini tasdiqlang! ✅",
                        'reply_markup' => $readyInfoBtn
                    ]);
                } else if ($data == $addWorker) {
                    $sql = "SELECT 
                                 us.user_id,
                                 u.second_name
                             FROM users_section as us 
                             inner join users as u  on us.user_id = u.id 
                             where us.section_id = 35 and us.role = 7";
                    $result = pg_query($conn, $sql);
                    $arr_uz = [];
                    $row_arr = [];
                    if (pg_num_rows($result) > 0) {
                        while ($row = pg_fetch_assoc($result)) {
                            $user_id = $row['user_id'];
                            $worker_name = $row['second_name'];
                            $dataWorkers = $user_id . "_workerId_" . $key;
                            $arr_uz[] = ["text" => $worker_name, "callback_data" => $dataWorkers];
                            $row_arr[] = $arr_uz;
                            $arr_uz = [];

                        }
                        $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                        $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Bo'lim ishchilaridan birini tanlang!",
                            'reply_markup' => $btnKey
                        ]);
                        $sqlUpdate = "update step_order set step_2 = 30 where chat_id = " . $chat_id;
                        $result = pg_query($conn, $sqlUpdate);
                    }
                }
            }
        }

        // ALTER
        if ($step_2 == 20) {
            if ($data == $realyDone) {
                
                $dateRightNow = date("Y-m-d H:i:s");
                $sql = "SELECT so.enter_date, os.order_column, os.section_id, os.order_id, os.id
                        FROM section_orders AS so
                        INNER JOIN order_step AS os ON so.section_id = os.section_id
                        WHERE so.order_id = $key AND os.order_id = $key AND os.section_id = $keyThirst AND so.exit_date IS NULL ORDER BY so.id DESC";

                // $sql = "SELECT 
                // s.title,
                // ord.title,
                // ord.client_id, 
                // cl.full_name,
                // cl.chat_id,
                // so.enter_date, 
                // os.order_column, 
                // os.section_id, 
                // os.order_id, os.id
                // FROM section_orders AS so
                // INNER JOIN order_step AS os ON so.section_id = os.section_id
                // LEFT JOIN orders AS ord ON ord.id = so.order_id
                // LEFT JOIN clients AS cl ON cl.id = ord.client_id
                // LEFT JOIN sections AS s ON s.id = so.section_id
                // WHERE so.order_id = $key AND os.order_id = $key AND os.section_id = 39 AND so.exit_date IS NULL ORDER BY so.id DESC";

                // bot('sendMessage', [
                //     'chat_id' => $chat_id,
                //     'text' => $sql
                // ]);

                // die();
                $result = pg_query($conn, $sql);
                if (pg_num_rows($result) > 0) {
                    $row = pg_fetch_assoc($result);
                    $orderColumn = $row["order_column"];
                    $order_id = $row["order_id"];
                    $enter_date = $row["enter_date"];
                    $idOrderStep = $row["id"];
                    $section_id = $row["section_id"];
                    $sqlUpdateStatus = "UPDATE order_step 
                                        SET status = 1 
                                        WHERE order_id = $order_id AND order_column = $orderColumn";
                    pg_query($conn, $sqlUpdateStatus);
                    $sqlOrders = "SELECT title, parralel 
                                    FROM orders WHERE id = ".$key;
                    $resultOrder = pg_query($conn, $sqlOrders);
                    if (pg_num_rows($resultOrder) > 0) {
                        $rowOrder = pg_fetch_assoc($resultOrder);
                        $titleOrder = $rowOrder["title"];
                        $parralel = $rowOrder["parralel"];
                    }

                    

                    $sqlColumn = "SELECT * FROM order_step 
                                  WHERE order_column > $orderColumn 
                                  AND order_id = $key OR order_column = $orderColumn 
                                  AND order_id = $key 
                                  AND id > $idOrderStep ORDER BY order_column ASC LIMIT 1";
                    $resultColumn = pg_query($conn, $sqlColumn);
                    if (pg_num_rows($resultColumn) > 0) {
                        $rowColumnTwo = pg_fetch_assoc($resultColumn);
                        $sectionIdTwo = $rowColumnTwo["section_id"];
                        $orderColumnNext = $rowColumnTwo["order_column"];

                        $checkNextRespon = "SELECT *, u.id AS person_id FROM section_minimal AS sm
                                LEFT JOIN users AS u ON sm.user_id = u.id
                                WHERE sm.section_id = $sectionIdTwo AND u.type = 5";
                        $resultNextRespon = pg_query($conn, $checkNextRespon);
                        if (pg_num_rows($resultNextRespon) > 0) {
                            $bmw = pg_fetch_assoc($resultNextRespon);
                            $respon_chat_id = $bmw['chat_id'];
                            $respon_id = $bmw['person_id'];
                            
                            $insertNextSectionOrder = "INSERT INTO order_responsibles (section_id, order_id, user_id) VALUES ($sectionIdTwo, $key, $respon_id)";
                            pg_query($conn, $insertNextSectionOrder);

                            $checkHasEmployee = "SELECT u.id, u.chat_id FROM leader_employees AS le
                                            LEFT JOIN users AS u ON le.employee_id = u.id
                                            WHERE le.leader_id = $respon_id AND u.status = 1";
                            $resultHasEmployee = pg_query($conn, $checkHasEmployee);

                            $checkHasBrigada = "SELECT * FROM brigada_leader WHERE user_id = $respon_id";
                            $resultHasBrigada = pg_query($conn, $checkHasBrigada);

                            if (pg_num_rows($resultHasBrigada) > 0) {
                                $personId = $respon_id."_brigadir_".$key;
                                $lookOrders = json_encode([
                                    'inline_keyboard' =>[
                                        [
                                            ['callback_data' => $personId,'text'=>"Brigadir tanlash"]
                                        ],
                                    ]
                                ]);
                            } else {
                                $lookOrders = $key."_lookOrders_".$sectionIdTwo;
                                $lookOrders = json_encode([
                                    'inline_keyboard' =>[
                                        [
                                            ['callback_data' => $lookOrders,'text'=>"Buyurtmani ko'rish"]
                                        ],
                                    ]
                                ]);

                                if (pg_num_rows($resultHasEmployee) > 0) {
                                    while ($mers = pg_fetch_assoc($resultHasEmployee)) {
                                        $employeeId = $mers['id'];
                                        $employeeChatId = $mers['chat_id'];

                                        $insertEmployeeOrder = "INSERT INTO order_responsibles (section_id, order_id, user_id) VALUES ($sectionIdTwo, $key, $employeeId)";
                                        pg_query($conn, $insertEmployeeOrder);

                                        bot('sendMessage', [
                                            'chat_id' => $employeeChatId,
                                            'text' => "📌<b><i>".$titleOrder."</i></b> nomli buyurtma sizga berildi",
                                            'parse_mode' => 'html',
                                            'reply_markup' => $lookOrders
                                        ]);
                                    }
                                }
                            }
                        }

                        $updateResponStep = "UPDATE step_order SET step_2 = 1 WHERE chat_id = $respon_chat_id";
                        pg_query($conn, $updateResponStep);

                        bot('sendMessage', [
                            'chat_id' => $respon_chat_id,
                            'text' => "📌<b><i>".$titleOrder."</i></b> nomli buyurtma sizning bo`limingizga o'tdi",
                            'parse_mode' => 'html',
                            'reply_markup' => $lookOrders
                        ]);

                        if ($sectionIdTwo == 42 and $parralel == 1 and isset($parralel)) {
                            $selectOrderParralel = "
                            SELECT 
                              os.order_id,
                              os.section_id,
                              s.title,
                              os.status
                            FROM order_step as os 
                            INNER JOIN sections as s ON os.section_id = s.id where (os.section_id = 42 or os.section_id = 29) and os.order_id =" . $key;
                            $resultParralel = pg_query($conn, $selectOrderParralel);
                            if (pg_num_rows($resultParralel) > 0) {

                                $btn_row = [];
                                $btn_column = [];
                                while ($rowParralel = pg_fetch_assoc($resultParralel)) {
                                    if ($rowParralel["status"] == 0) {
                                        $order_id = $rowParralel["order_id"];
                                        $section_id = $rowParralel["section_id"];
                                        $title = $rowParralel["title"];
                                        $clb_shpon = $order_id . "_" . $section_id . "_shpon_" . $keyThirst;
                                        $btn_column[] = ["callback_data" => $clb_shpon, 'text' => $title];
                                    }
                                }
                                $btn_row[] = $btn_column;
                                $update_sql = "UPDATE step_order SET step_2 = 150 WHERE chat_id = " . $chat_id;
                                $result_update = pg_query($conn, $update_sql);
                                if ($result_update == true) {
                                    $shpon_korpus = json_encode([
                                        'inline_keyboard' => $btn_row
                                    ]);
                                    bot('deleteMessage', [
                                        'chat_id' => $chat_id,
                                        'message_id' => $message_id
                                    ]);
                                    bot('sendMessage', [
                                        "chat_id" => $chat_id,
                                        'text' => "Buyurmani qaysi bo`limga o`tkazmoqchisiz ?",
                                        'reply_markup' => $shpon_korpus
                                    ]);
                                    die();
                                }
                            }
                        } else if ($keyThirst != 28 or !isset($parralel)) {

                            $insertOrderSection = "INSERT INTO section_orders (order_id,section_id,enter_date) VALUES (" . $order_id . "," . $sectionIdTwo . ",'" . $dateRightNow . "') ";

                            $insertResult = pg_query($conn, $insertOrderSection);
                        }
                        $sqlUpdate = "update section_orders set step = 1, exit_date = '" . $dateRightNow . "' where exit_date is null and order_id = " . $order_id . " and section_id = " . $keyThirst;

                        $resultUpdate = pg_query($conn, $sqlUpdate);

                        // STATUS MUST BE 1
                        if ($sectionIdTwo != 35) {
                            $sqlSection = "select u.chat_id from users_section as us inner join users as u on us.user_id = u.id where  us.section_id = " . $sectionIdTwo;
                        } else if ($sectionIdTwo == 35) {
                            $sqlSection = "select u.chat_id from users_section as us inner join users as u on us.user_id = u.id where us.role = 5 and us.section_id = " . $sectionIdTwo;
                        }
                        $resultSection = pg_query($conn, $sqlSection);
                        bot("deleteMessage", [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Siz buyurtmani keyingi bo`limga o`tqazib yubordingiz!",
                            'reply_markup' => $menu
                        ]);

                        if (pg_num_rows($resultSection) > 0) {
                            if ($keyThirst != 28 or !isset($parralel)) {
                                while ($rowSection = pg_fetch_assoc($resultSection)) {
                                    $OtdelChat_id = $rowSection["chat_id"];
                                    $lookOrders = $key . "_lookOrders_" . $keyThirst;
                                    $lookOrders = json_encode([
                                        'inline_keyboard' => [
                                            [
                                                ['callback_data' => $lookOrders, 'text' => "Buyurtmani ko`rish"]

                                            ],
                                        ]
                                    ]);
                                    bot('sendMessage', [
                                        'chat_id' => $OtdelChat_id,
                                        'text' => "📌<b><i>" . $titleOrder . "</i></b> nomli buyurtma sizning bo`limingizga o'tdi",
                                        'parse_mode' => "html",
                                        'reply_markup' => $lookOrders
                                    ]);
                                }
                            }
                        }
                        $ClientSql = "SELECT sec.title AS section,clp.chat_id as chat_id,ord.title as order,clp.step_1 AS lang, u.chat_id AS user_chat_id FROM orders AS ord
                            LEFT JOIN client_step AS clp ON clp.client_id = ord.client_id
                            LEFT JOIN section_orders AS sec_ord ON sec_ord.order_id = ord.id
                            LEFT JOIN sections AS sec ON sec_ord.section_id = sec.id
                            INNER JOIN branch AS br ON br.id = ord.branch_id
                            LEFT JOIN users AS u ON br.user_id = u.id
                            WHERE ord.id = " . $key . " AND sec_ord.exit_date IS NULL
                            LIMIT 1";

                        // bot('sendMessage', [
                        //     'chat_id' => $chat_id,
                        //     'text' => $ClientSql
                        // ]);
                        // aaaaaaaaaaa
                        // die();
                        $resultClient = pg_query($conn, $ClientSql);
                        if (pg_num_rows($resultClient) == 1) {
                            $rowClient = pg_fetch_assoc($resultClient);
                            if ($rowClient['lang'] == 1) {
                                $text = "📌<b><i>" . $rowClient['order'] . "</i></b> nomli buyurtmangiz <b><i>" . $rowClient['section'] . "</i></b>  bolimga otdi";
                            } elseif ($rowClient['lang'] == 2) {
                                $text = "📌 Ваш заказ под названием <b><i>" . $rowClient['order'] . "</i></b> перемешен в раздел <b><i>" . $rowClient['section'] . "</i></b>";
                            } else {
                                $text = "🇷🇺 | 📌 Ваш заказ под названием <b><i>" . $rowClient['order'] . "</i></b> перемешен в раздел <b><i>" . $rowClient['section'] . "</i></b>" . PHP_EOL . "🇺🇿 | 📌<b><i>" . $rowClient['order'] . "</i></b> nomli buyurtmangiz <b><i>" . $rowClient['section'] . "</i></b>  bolimga o'tdi";
                            }
                            $testSendUrl = 'https://api.telegram.org/bot1576388332:AAG2d9KptV4wICUw6BMujGc3aYEvo1wzQNs/sendMessage';
                            $chOne = curl_init();
                            curl_setopt($chOne, CURLOPT_URL, $testSendUrl);
                            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($chOne, CURLOPT_POSTFIELDS, [
                                'chat_id' => $rowClient['chat_id'],
                                'text' => $text,
                                'parse_mode' => "html"
                            ]);
                            $resOne = curl_exec($chOne);

                            if (!empty($rowClient['user_chat_id'])) {
                                $token = '1576388332:AAG2d9KptV4wICUw6BMujGc3aYEvo1wzQNs';
                                $msg = "📌<b><i>" . $rowClient['order'] . "</i></b> nomli buyurtma <b><i>" . $rowClient['section'] . "</i></b>  bolimga otdi";
                                
                                $arr = [
                                      'chat_id' => $rowClient['user_chat_id'],
                                      'text' => $msg,
                                      'parse_mode' => "html"
                                    ];
                                $url = 'https://api.telegram.org/bot' . $token . '/sendMessage?' . http_build_query($arr);
                                file_get_contents($url);    
                            }
                            
                        }
                    } else {
                        $dateRightNow = date("Y-m-d H:i:s");
                        $sqlUpdate = "UPDATE section_orders SET status = 1, exit_date = '".$dateRightNow."' WHERE exit_date is null AND order_id = ".$order_id;
                        $sqlUpdate = pg_query($conn, $sqlUpdate);
                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Siz malumotni buyurtma boyicha oxirgi bolinma edingiz.Buyurtma tugatildi!",
                            'reply_markup' => $menu
                        ]);
                    }
                }

                $sql = "SELECT * FROM users WHERE type = 1 or type = 2";
                $result = pg_query($conn, $sql);
                $admins = [];
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        if (isset($row["chat_id"]) and !empty($row["chat_id"])) {
                            $admins[] = $row["chat_id"];
                        }
                    }
                }

                // $sql_bonus = "SELECT 
                //                 st.section_id,
                //                 st.work_time,
                //                 us.chat_id,
                //                 sm.user_id,
                //                 mm.penalty_summ,
                //                 mm.bonus_sum,
                //                 ord.title AS order_names,
                //                 sec.title AS section_name,
                //                 us.second_name,
                //                 us.chat_id
                //                 FROM section_times AS st 
                //                 INNER JOIN section_minimal AS sm ON sm.section_id = st.section_id
                //                 INNER JOIN users AS us ON sm.user_id = us.id
                //                 INNER JOIN minimal AS mm ON sm.minimal_id = mm.id
                //                 INNER JOIN orders AS ord ON ord.id = $key
                //                 INNER JOIN sections AS sec ON sm.section_id = sec.id
                //                 WHERE st.section_id = ".$keyThirst;

                $sql_bonus = "SELECT 
                                st.section_id,
                                st.work_time,
                                us.chat_id,
                                sm.user_id,
                                mm.penalty_summ,
                                mm.bonus_sum,
                                ord.title AS order_names,
                                sec.title AS section_name,
                                us.second_name,
                                us.chat_id,
                                usb.type,
                                usb.second_name AS brigadir
                                FROM section_times AS st 
                                INNER JOIN section_minimal AS sm ON sm.section_id = st.section_id
                                INNER JOIN users AS us ON sm.user_id = us.id 
                                INNER JOIN minimal AS mm ON sm.minimal_id = mm.id
                                INNER JOIN orders AS ord ON ord.id = ".$key."
                                INNER JOIN sections AS sec ON sm.section_id = sec.id
                                INNER JOIN order_responsibles AS ord_res ON ord_res.order_id = ".$key." AND ord_res.section_id = ".$keyThirst."
                                INNER JOIN users AS usb ON ord_res.user_id = usb.id 
                                WHERE st.section_id = ".$keyThirst;
                $resultTwo = pg_query($conn, $sql_bonus);
                if (pg_num_rows($resultTwo) > 0) {
                    $true = true;
                    $responsible = '';
                    $users_res = '';
                    $us_chat_id = [];
                    while ($rowThree = pg_fetch_assoc($resultTwo)) {
                        $work_time = $rowThree["work_time"];
                        $us_chat_id[] = $rowThree["chat_id"];
                        $dead_line_time = date("Y-m-d H:i:s", strtotime('+' . $work_time . ' hours', strtotime($enter_date)));
                        if ($dateRightNow > $dead_line_time) {
                            $type = 2;
                            $differce_time = differenceInHours($dateRightNow, $dead_line_time);
                            $round_time = round($differce_time);
                        } else if ($dateRightNow < $dead_line_time) {
                            $type = 1;
                            $differce_time = differenceInHours($dateRightNow, $dead_line_time);
                            $round_time = round($differce_time);
                        }
                        $lose_time = differenceInHours($enter_date, $dateRightNow);
                        $lose_time = round($lose_time);

                        $bonus_sum = $rowThree["bonus_sum"];
                        $penalty_summ = $rowThree["penalty_summ"];

                        if ($type == 1) {
                            $quantity = $round_time * $bonus_sum;
                            $txt_end = "<b>💴 Bonus summasi</b>: ";
                            $user_id = 93;
                            $receiver = $rowThree["chat_id"];
                            $title_msg = "<b>✅ Bonus yozildi</b>!";
                        } else if ($type == 2) {
                            $quantity = $round_time * $penalty_summ;
                            $txt_end = "<b>💴 Jarima summasi</b>: ";
                            $user_id = $rowThree["user_id"];
                            $receiver = 284914591; //1270367;
                            $title_msg = "<b>❌ Jarima yozildi</b>!";
                            $sql_update = "UPDATE salary_category SET balance = balance + " . $quantity . " WHERE id = 31";
                            // bot('sendMessage',[
                            //   'chat_id' => $chat_id,
                            //   'text' => "Sql = ".$sql_update
                            // ]);
                            pg_query($conn, $sql_update);
                        }

                        $quantity_sum = number_format("$quantity", 0, " ", " ");
                        $quantity_sum = $quantity_sum . " so'm";
                        $order_names = $rowThree["order_names"];
                        $section_name = $rowThree["section_name"];
                        $responsible = $responsible . $rowThree["brigadir"] . ", ";
                        $category_id = 21;
                        if ($i != 1) {
                            $users_res = $users_res . PHP_EOL . "👤 <b>" . $rowThree["brigadir"] . "</b>: " . $quantity_sum;
                        } else {
                            $users_res = $users_res . "👤 <b>" . $rowThree["brigadir"] . "</b>: " . $quantity_sum;

                        }
                        $sql_insert_salary = "
                      INSERT INTO salary_event_balance (user_id, receiver, quantity, category_id, date,type) 
                        VALUES 
                      (" . $user_id . "," . $receiver . "," . $quantity . "," . $category_id . ",'" . $dateRightNow . "',3);
                     ";

                        $result_bonus = pg_query($conn, $sql_insert_salary);
                        if ($result_bonus == false and $true == TRUE) {
                            $true = false;
                        }
                    }
                    $txtSend = $title_msg . "\n<b>📦 Buyurtma nomi</b>:" . $order_names . "\n<b>🏭 Bo`lim nomi</b>: " . $section_name . "\n<b>👤 Ma`sullar</b>: " . $responsible . PHP_EOL . $txt_end . $users_res . PHP_EOL . "🕔 <b>Ish vaqti</b>: " . $lose_time." soat";
                    // $key
                    // keyThirst

                    
                    foreach ($admins as $key => $value) {
                        botAdmin('sendMessage', [
                            'chat_id' => $value,
                            // 'chat_id' => 284914591,
                            'text' => $txtSend,
                            'parse_mode' => 'html'
                        ]);
                    }

                    $deleteFromRespon = "DELETE FROM order_responsibles WHERE order_id = ".$dataArr[0]." AND section_id = ".$keyThirst;
                    pg_query($conn, $deleteFromRespon);

                    if (isset($us_chat_id) and !empty($us_chat_id)) {
                        $count_us = count($us_chat_id);
                        for ($i = 0; $i < $count_us; $i++) {
                            if ($type == 1) {
                                // code...
                                bot('sendMessage', [
                                    'chat_id' => $us_chat_id[$i],
                                    'text' => "💰 Sizga Bonus yozildi.\n⏳ Bonusni rahbar ko`rib chiqadi!"
                                ]);
                            } else {
                                bot('sendMessage', [
                                    'chat_id' => $us_chat_id[$i],
                                    'text' => "💰 Sizga Jarima yozildi.\n⏳ Jarimani rahbar ko`rib chiqadi!"
                                ]);
                            }
                        }
                    } else {
                        bot('sendMessage',[
                            'chat_id' => $chat_id,
                            'text' => "Fail"
                        ]);
                    }
                }
            } else if ($data == $cancelDone) {
                $sql = "select 
                       o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
                       s.title as section_title , os.deadline as deadline_of_section,
                       b.title as branch_title , us.role , o.description, ctg.title as category_name
                       from users as u
                       inner join users_section as us on u.id = us.user_id
                       inner join sections as s on us.section_id = s.id
                       inner join section_orders as so on s.id = so.section_id
                       inner join orders as o on so.order_id = o.id
                       inner join order_step as os on o.id = os.order_id and s.id = os.section_id
                       inner join branch as b on o.branch_id = b.id    
                       inner join clients as cl on o.client_id = cl.id
                       inner join order_categories as oc on o.id = oc.order_id
                       inner join category as ctg on oc.category_id = ctg.id
                   where o.pause = 0 and u.chat_id = " . $chat_id . " and so.exit_date is null";

                $result = pg_query($conn, $sql);
                if (pg_num_rows($result)) {
                    while ($row = pg_fetch_assoc($result)) {
                        $title = $row["title"];
                        $section_id = $row["section_id"];
                        $endDate = $row["dead_line"];
                        $stepOrderDeadline = $row["deadline_of_section"];
                        $crDate = $row["created_date"];
                        $sectionTitle = $row["section_title"];
                        $branch_title = $row["branch_title"];
                        $category_name = $row["category_name"];
                        $order_user_id = $row["order_user_id"];
                        $section_user_id = $row["section_user_id"];
                        $crDateNew = date("Y-m-d H:i", strtotime(date($crDate)));
                        $endDateNew = date("Y-m-d H:i", strtotime(date($endDate)));
                        $stepOrderDeadlineNew = date("Y-m-d H:i", strtotime(date($stepOrderDeadline)));
                        $phone_number = base64_decode($row["phone_number"]);
                        $clientName = base64_decode($row["full_name"]);
                        $camment = $row["description"];
                        if (!empty($camment)) {
                            $camment = $camment;
                        } else {
                            $camment = "";
                        }
                        $role = $row["role"];
                        $dataInfo = $row["id"] . "_info";
                        $dataAdd = $row["id"] . "_addWorker";
                        $dataReady = $row["id"] . "_ready_" . $section_id;

                        if (($role == 5 or $role == 6) and $section_id != 35 or ($section_user_id == $order_user_id)) {
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]

                                    ],
                                ]
                            ]);
                        } else if ($section_id == 35 and $order_user_id == 1) {
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataAdd, 'text' => "➕ Ishchi Biriktirish"]

                                    ],
                                ]
                            ]);
                        } else {
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]

                                    ],
                                ]
                            ]);
                        }


                        $txtSend = "#" . $title . " \n👨‍💻 Buyurtmachi: <b>" . $clientName . "</b>\n🏢 Buyurtma filiali :<b>" . $branch_title . "</b>\n🕓 Boshlangan Vaqti: <b>" . $crDateNew . "</b>\n⏳ Tugash vaqti: <b>" . $endDateNew . "</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle . "</b>\n⏳ Bo'limdan chiqish vaqti: <b>" . $stepOrderDeadlineNew . "</b>\n⚒ Kategoriya: <b>" . $category_name . "</b>\n💬 Izoh:<b> " . $camment . "</b>";
                        $getResult = bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $txtSend,
                            'parse_mode' => "html",
                            'reply_markup' => $readyInfoBtn
                        ]);
                        $deleteMessageId = $getResult->result->message_id;
                        $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
                        pg_query($conn, $sqlGo);
                    }
                }

                $getResult = bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Buyurtmalar barchasi shulardan iborat",
                    'reply_markup' => $menu
                ]);
                $deleteMessageId = $getResult->result->message_id;
                $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (" . $chat_id . "," . $deleteMessageId . ")";
                pg_query($conn, $sqlGo);
                $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);
            }
        }

        if ($data == $lookOrders) {
            $sqlUsers = "SELECT * FROM users WHERE chat_id = $chat_id";
            $resultStep = pg_query($conn, $sqlUsers);
            if (pg_num_rows($resultStep) > 0) {
                $row = pg_fetch_assoc($resultStep);
                $role = $row["type"];
            }
            $sqlUpdateTwo = "UPDATE last_id_order SET last_id = " . $keySecond . ", message_id = " . $keyThirst . " WHERE chat_id = " . $chat_id;
            $pg_query = pg_query($conn, $sqlUpdateTwo);
            $sqlOrders = "SELECT 
                            ord.id,
                            ord.title,
                            ord.created_date,
                            ord.dead_line,
                            cl.full_name,
                            cl.phone_number,
                            s.title AS section_title, 
                            os.deadline AS deadline_of_section,
                            u.type,
                            ors.section_id,
                            ctg.title AS category_name
                            FROM users AS u
                            INNER JOIN order_responsibles AS ors ON ors.user_id = u.id
                            INNER JOIN orders AS ord ON ord.id = ors.order_id
                            INNER JOIN clients AS cl ON cl.id = ord.client_id
                            INNER JOIN section_orders AS so ON so.order_id = ord.id
                            INNER JOIN sections AS s ON s.id = so.section_id
                            INNER JOIN order_step AS os ON os.order_id = ord.id
                            INNER JOIN order_categories AS oc ON ord.id = oc.order_id
                            INNER JOIN category AS ctg ON oc.category_id = ctg.id
                            WHERE ord.id = $key AND so.exit_date IS NULL AND u.chat_id = $chat_id";
            $resultOrders = pg_query($conn, $sqlOrders);

            

            if (pg_num_rows($resultOrders) > 0) {
                while ($row = pg_fetch_assoc($resultOrders)) {
                    $orderId = $row["id"];
                    $title = $row["title"];
                    $endDate = $row["dead_line"];
                    $category_name = $row["category_name"];
                    $section_id = $row["section_id"];
                    if ($section_id == 35) {
                        if ($role == 5) {
                            $sql = "SELECT 
                                     us.user_id,
                                     u.second_name
                                 FROM users_section as us 
                                 inner join users as u  on us.user_id = u.id 
                                 where us.section_id = 35 and us.role = 7";
                            $result = pg_query($conn, $sql);
                            $arr_uz = [];
                            $row_arr = [];
                            if (pg_num_rows($result) > 0) {
                                while ($row = pg_fetch_assoc($result)) {
                                    $user_id = $row['user_id'];
                                    $worker_name = $row['second_name'];
                                    $dataWorkers = $user_id . "_workerId_" . $key;
                                    $arr_uz[] = ["text" => $worker_name, "callback_data" => $dataWorkers];
                                    $row_arr[] = $arr_uz;
                                    $arr_uz = [];

                                }
                                $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                                bot('editMessageText', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id,
                                    'text' => "Bo'lim ishchilaridan birini tanlang!",
                                    'reply_markup' => $btnKey
                                ]);
                                $sqlUpdate = "update step_order set step_2 = 30 where chat_id = " . $chat_id;
                                $result = pg_query($conn, $sqlUpdate);
                                die();
                            }
                        } else if ($role == 7 || $role == 1) {
                            $crDate = $row["created_date"];
                            $sectionTitle = $row["section_title"];
                            $deadline_of_section = $row["deadline_of_section"];
                            $phone_number = base64_decode($row["phone_number"]);
                            $clientName = base64_decode($row["full_name"]);

                            $dataInfo = $row["id"] . "_info";
                            $dataReady = $row["id"] . "_ready_" . $section_id;
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]

                                    ],
                                    [
                                        ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                                    ]
                                ]
                            ]);

                            $getIdUser = "SELECT * FROM users WHERE chat_id = $chat_id";
                            $resultIdUser = pg_query($conn, $getIdUser);
                            $user = pg_fetch_assoc($resultIdUser);
                            $brigadirId = $user['id'];

                            $checkStatus = "SELECT * FROM order_responsibles 
                                        WHERE user_id != $brigadirId AND order_id = $orderId
                                        AND id > (SELECT id FROM order_responsibles WHERE user_id = $brigadirId AND order_id = $orderId)";
                            $resultStatus = pg_query($conn, $checkStatus);
                            if (pg_num_rows($resultStatus) > 0) {
                                $readyInfoBtn = json_encode([
                                    'inline_keyboard' => [
                                        [
                                            ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                        ],
                                    ]
                                ]);
                            } else {
                                $checkIsBrigada = "SELECT * FROM brigada_leader WHERE user_id = $brigadirId";
                                $resultIsBrigada = pg_query($conn, $checkIsBrigada);
                                if (pg_num_rows($resultIsBrigada) > 0) {
                                    $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 5";
                                    $resultPersonId = pg_query($conn, $getPersonId);
                                    $mow = pg_fetch_assoc($resultPersonId);
                                    $personId = $mow['id'];

                                    $personId = $personId."_brigadir_".$orderId;
                                    if ($mow['section_id'] == 35) {
                                        $readyInfoBtn = json_encode([
                                            'inline_keyboard' => [
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                                ],
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                                ],
                                            ]
                                        ]);
                                    }
                                    else{
                                        $readyInfoBtn = json_encode([
                                            'inline_keyboard' => [
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                                ],
                                                [
                                                    ['callback_data' => $personId, 'text' => "➡️ Brigadirga yuborish"]
                                                ],
                                            ]
                                        ]);    
                                    }
                                    
                                } else {
                                    $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 5";
                                    $resultPersonId = pg_query($conn, $getPersonId);
                                    $mow = pg_fetch_assoc($resultPersonId);
                                    $personId = $mow['id'];

                                    $personId = $personId."_brigadir_".$orderId;
                                    $readyInfoBtn = json_encode([
                                        'inline_keyboard' => [
                                            [
                                                ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                            ],
                                        ]
                                    ]);
                                }
                            }
                            $txtSend = "#" . $title . " \nIsmi: <b>" . $clientName . "</b>\nBoshlangan Vaqti: 🕓 <b>" . $crDate . "</b>\nTugash vaqti: ⏳ <b>" . $endDate . "</b>\nBo`lim tugalash vaqti <b>" . $deadline_of_section . "</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle . "</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name . "</b>\nTelefon raqami: 📞  +" . $phone_number;
                            bot('editMessageText', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id,
                                'text' => $txtSend,
                                'parse_mode' => "html",
                                'reply_markup' => $readyInfoBtn
                            ]);
                        }
                    } else {

                        $crDate = $row["created_date"];
                        $sectionTitle = $row["section_title"];
                        $deadline_of_section = $row["deadline_of_section"];
                        $phone_number = base64_decode($row["phone_number"]);
                        $clientName = base64_decode($row["full_name"]);

                        $dataInfo = $row["id"] . "_info";
                        $dataReady = $row["id"] . "_ready_" . $section_id;
                        if ($role == 5 || $role == 1) {
                            $getUserId = "SELECT * FROM users WHERE chat_id = $chat_id";
                            $resultUserId = pg_query($conn, $getUserId);
                            $self = pg_fetch_assoc($resultUserId);
                            $brigadirId = $self['id'];

                           $checkUserStatus = "SELECT * FROM brigada_leader WHERE user_id = $brigadirId";
                            $resultUserStatus = pg_query($conn, $checkUserStatus);
                            if (pg_num_rows($resultUserStatus) > 0) {
                                $checkStatus = "SELECT * FROM order_responsibles 
                                                WHERE user_id != $brigadirId AND order_id = $orderId
                                                AND id > (SELECT id FROM order_responsibles WHERE user_id = $brigadirId AND order_id = $orderId)";
                                $resultStatus = pg_query($conn, $checkStatus);
                                if (pg_num_rows($resultStatus) > 0) {
                                    $readyInfoBtn = json_encode([
                                        'inline_keyboard' => [
                                            [
                                                ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                            ],
                                        ]
                                    ]);
                                } else {
                                    $checkIsBrigada = "SELECT * FROM brigada_leader WHERE user_id = $brigadirId";
                                    $resultIsBrigada = pg_query($conn, $checkIsBrigada);
                                    if (pg_num_rows($resultIsBrigada) > 0) {
                                        $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 5";
                                        $resultPersonId = pg_query($conn, $getPersonId);
                                        $mow = pg_fetch_assoc($resultPersonId);
                                        $personId = $mow['id'];

                                        $personId = $personId."_brigadir_".$orderId;

                                        if ($mow['section_id'] == 35) {
                                            $readyInfoBtn = json_encode([
                                                'inline_keyboard' => [
                                                    [
                                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                                    ],
                                                    [
                                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                                    ],
                                                ]
                                            ]);
                                        }
                                        else{
                                            $readyInfoBtn = json_encode([
                                                'inline_keyboard' => [
                                                    [
                                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"]
                                                    ],
                                                    [
                                                        ['callback_data' => $personId, 'text' => "➡️ Brigadirga yuborish"]
                                                    ],
                                                ]
                                            ]);
                                            
                                        }

                                    } else {
                                        $getPersonId = "SELECT id FROM users WHERE chat_id = $chat_id AND type = 5";
                                        $resultPersonId = pg_query($conn, $getPersonId);
                                        $mow = pg_fetch_assoc($resultPersonId);
                                        $personId = $mow['id'];

                                        $personId = $personId."_brigadir_".$orderId;
                                        $readyInfoBtn = json_encode([
                                            'inline_keyboard' => [
                                                [
                                                    ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                                ],
                                            ]
                                        ]);
                                    }
                                }
                            } else {
                                $readyInfoBtn = json_encode([
                                    'inline_keyboard' => [
                                        [
                                            ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                                        ],
                                    ]
                                ]);
                            }
                        } else {
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['callback_data' => $dataInfo, 'text' => "ℹ️ Ma'lumotlar"], ['callback_data' => "home", 'text' => "🏘 Bosh menu"]
                                    ],
                                ]
                            ]);
                        }
                        $txtSend = "#" . $title . " \nIsmi: <b>" . $clientName . "</b>\nBoshlangan Vaqti: 🕓 <b>" . $crDate . "</b>\nTugash vaqti: ⏳ <b>" . $endDate . "</b>\nBo`lim tugalash vaqti <b>" . $deadline_of_section . "</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle . "</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name . "</b>\nTelefon raqami: 📞  +" . $phone_number;
                        bot('editMessageText', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id,
                            'text' => $txtSend,
                            'parse_mode' => "html",
                            'reply_markup' => $readyInfoBtn
                        ]);
                    }
                }
            }
            $sqlUpdate = "update step_order set step_2 = 9 where chat_id = " . $chat_id;
            $result = pg_query($conn, $sqlUpdate);
        }
        // START CHANGES
        if ($data == $brigadir) {
            $sql = "SELECT max(u.id) AS leader_id, max(u.name) AS NAME, max(uu.second_name) AS brigadir_name, max(b.user_id) AS brigadir_id 
                    FROM users AS u
                    INNER JOIN brigada_leader AS bl
                    ON u.id = bl.user_id
                    LEFT JOIN brigada AS b ON b.id = bl.brigada_id
                    INNER JOIN users AS uu ON b.user_id = uu.id 
                    WHERE u.id = $key AND u.type = 5
                    GROUP BY b.user_id";
            $result = pg_query($conn, $sql);
            if (pg_num_rows($result) > 0) {
                $i = 1;
                $menu = [];
                $arr = [];
                while ($row = pg_fetch_assoc($result)) {
                    $brigadirId = $row['brigadir_id'];
                    $brigadirName = $row['brigadir_name'];
                    $newData = $brigadirId."_".$keyThirst;

                    $menu[] = ['callback_data' => $newData,'text'=> "$brigadirName"];

                    if (pg_num_rows($result) == 5 || pg_num_rows($result) == 10) {
                        if($i % 5 == 0) {
                            $arr[] = $menu;
                            $menu = [];
                        }
                    } else if (pg_num_rows($result) == 4 || pg_num_rows($result) == 8) {
                        if($i % 4 == 0) {
                            $arr[] = $menu;
                            $menu = [];
                        }
                    } else if (pg_num_rows($result) == 3 || pg_num_rows($result) == 6) {
                        if($i % 3 == 0) {
                            $arr[] = $menu;
                            $menu = [];
                        }
                    } else if ($i % 2 == 0) {
                        $arr[] = $menu;
                        $menu = [];
                    } else {
                        if($i % 2 == 1) {
                            $arr[] = $menu;
                            $menu = [];
                        }
                    }

                    $i++;
                }

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id 
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>Brigadirlar ro'yxati</b>\n\n<i>Qaysi brigadirga yuborishni tanlang</i>",
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $arr
                    ])
                ]);

                $sqlUpdate = "UPDATE step_order SET step_2 = 50 WHERE chat_id = " . $chat_id;
                pg_query($conn, $sqlUpdate);
            }
        }

        if ($step_2 == 50) {
            if (isset($data)) {
                $dataArray = explode("_", $data);
                $userId = $dataArray[0];
                $orderId = $dataArray[1];

                $getUserSectionId = "SELECT * FROM order_responsibles WHERE order_id = $orderId ORDER BY id ASC LIMIT 1";
                $resultUserSectionId = pg_query($conn, $getUserSectionId);
                $won = pg_fetch_assoc($resultUserSectionId);
                $sectionId = $won['section_id'];

                $respon = "INSERT INTO order_responsibles (order_id, user_id, section_id) VALUES (".$orderId.", ".$userId.", ".$sectionId.")";
                pg_query($conn, $respon);

                $sql = "SELECT * FROM users WHERE id = $userId";
                $result = pg_query($conn, $sql);
                $wow = pg_fetch_assoc($result);
                $brigadir_id = $wow['chat_id'];

                $sqlOrders = "SELECT
                                    o.id,
                                    o.title,
                                    o.created_date,
                                    o.dead_line,
                                    cl.full_name,
                                    cl.phone_number,
                                    s.title AS section_title, 
                                    u.type,
                                    ors.section_id,
                                    ctg.title AS category_name
                                FROM users AS u
                                LEFT JOIN order_responsibles AS ors ON ors.user_id = u.id
                                LEFT JOIN orders AS o ON o.id = ors.order_id
                                LEFT JOIN sections AS s ON ors.section_id = s.id
                                LEFT JOIN section_orders AS so ON so.order_id = o.id
                                INNER JOIN order_categories AS oc ON o.id = oc.order_id
                                INNER JOIN category AS ctg ON oc.category_id = ctg.id
                                INNER JOIN clients AS cl ON cl.id = o.client_id
                                WHERE u.chat_id = $brigadir_id AND ors.order_id = $orderId AND so.exit_date IS NULL";
                $resultOrders = pg_query($conn, $sqlOrders);

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                if (pg_num_rows($resultOrders) > 0) {
                    $row = pg_fetch_assoc($resultOrders);
                    $title = $row["title"];
                    $endDate = $row["dead_line"];
                    $category_name = $row["category_name"];
                    $section_name = $row["section_title"];
                    $sectionId = $row["section_id"];
                    $indiv = base64_decode($row['full_name']);
                    $dataReady = $row["id"] . "_ready_" . $sectionId;

                    $textSend = "<b>#$title</b>\n<b>Mijoz:</b> $indiv\n<b>Buyurtma:</b> $category_name\n<b>Muddati:</b> $endDate\n<b>Bo'limi:</b> $section_name";

                    $readyInfoBtn = json_encode([
                        'inline_keyboard' => [
                            [
                                ['callback_data' => $dataReady, 'text' => "✅ Bitdi"]
                            ],
                            [
                                ['callback_data' => $data, 'text' => "Brigada tanlash"]
                            ]
                        ]
                    ]);

                    $lookOrders = $orderId."_lookOrders_".$sectionId;
                    $lookOrders = json_encode([
                        'inline_keyboard' =>[
                            [
                                ['callback_data' => $lookOrders, 'text'=>"Buyurtmani ko`rish"]
                            ],
                        ]
                    ]);
                }

                bot('sendMessage', [
                    'chat_id' => $brigadir_id,
                    'text' => $textSend,
                    'parse_mode' =>  "html",
                    'reply_markup' => $readyInfoBtn
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "📌<b><i>".$title."</i></b> nomli buyurtma sizning bo`limingizga o'tdi",
                    'parse_mode' => "html",
                    'reply_markup' => $lookOrders
                ]);

                $sqlUpdate = "UPDATE step_order SET step_2 = 55 WHERE chat_id = " . $brigadir_id; // $chat_id = $brigadir_id
                pg_query($conn, $sqlUpdate);

                $sqlUpdatee = "UPDATE step_order SET step_2 = 1 WHERE chat_id = " . $chat_id;
                pg_query($conn, $sqlUpdatee);
            }
        }

        if ($step_2 == 55) {
            if (isset($data)) {
                if ($data == $ready) {
                    $sql = "UPDATE step_order SET step_2 = 20 where chat_id = " . $chat_id;
                    pg_query($conn, $sql);
                    $dataCancel = $key . "_cancelReady_" . $keyThirst;
                    $dataConfirm = $key . "_confirm_" . $keyThirst;
                    $readyInfoBtn = json_encode([
                        'inline_keyboard' => [
                            [
                                ['callback_data' => $dataCancel, 'text' => "Bekor qilish 🚫"], ['callback_data' => $dataConfirm, 'text' => "✅ Tasdiqlayman"]

                            ],
                            [
                                ['callback_data' => "home", 'text' => "🏘 Bosh menu"]

                            ]
                        ]
                    ]);
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Buyurtma bitganini tasdiqlang! ✅",
                        'reply_markup' => $readyInfoBtn
                    ]);
                } else if ($data == $info) {
                    $sqlUsers = "SELECT * FROM users WHERE chat_id = $chat_id";
                    $resultStep = pg_query($conn, $sqlUsers);
                    if (pg_num_rows($resultStep) > 0) {
                        $row = pg_fetch_assoc($resultStep);
                        $role = $row["type"];
                    }
                    $sqlGetRequiredMaterials = "SELECT 
                                                 r.title, 
                                                 o.title as order_name,
                                                 r.id FROM required_material_order as r 
                                                 INNER JOIN orders AS o ON r.order_id = o.id
                                                 WHERE r.status = 0 and r.order_id = " . $key . " limit 1";
                    $resultRequiredMaterials = pg_query($conn, $sqlGetRequiredMaterials);
                    if (pg_num_rows($resultRequiredMaterials) > 0 and $role == 6) {

                        $sqlUpdate = "update step_order set step_2 = 15 where chat_id = " . $chat_id;
                        $result = pg_query($conn, $sqlUpdate);
                        $rowRequiredMaterials = pg_fetch_assoc($resultRequiredMaterials);
                        $idLast = $rowRequiredMaterials["id"];
                        $order_name = $rowRequiredMaterials["order_name"];
                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "<b><i>" . $order_name . "</i></b> buyurtmasiga  <b>" . $rowRequiredMaterials["title"] . "</b>ni kiriting!\n",
                            'parse_mode' => "html",
                            'reply_markup' => $OnlyBack
                        ]);
                        $sqlUpdateLastId = "update last_id_order set last_id = " . $idLast . ",type=" . $key . " where chat_id = " . $chat_id;
                        $resultStep = pg_query($conn, $sqlUpdateLastId);
                    } else {

                        $sqlUpdate = "update step_order set step_2 = 10 where chat_id = " . $chat_id;
                        $result = pg_query($conn, $sqlUpdate);

                        $sql = "select * from order_materials where order_id = " . $key;
                        $resultOrder = pg_query($conn, $sql);
                        $sqlUpdateLastId = "update last_id_order set last_id = " . $key . " where chat_id = " . $chat_id;
                        $resultStep = pg_query($conn, $sqlUpdateLastId);

                        $requiredMaterials = "SELECT * FROM required_material_order where order_id = " . $key;
                        $resultMaterials = pg_query($conn, $requiredMaterials);
                        if (pg_num_rows($resultOrder) > 0 or pg_num_rows($resultMaterials) > 0) {
                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);
                            while ($row = pg_fetch_assoc($resultOrder)) {
                                $getResult = bot("copyMessage", [
                                    'chat_id' => $chat_id,
                                    'from_chat_id' => -1001444743477,
                                    'message_id' => $row["copy_message_id"]
                                ]);
                            }
                            while ($rowTwo = pg_fetch_assoc($resultMaterials)) {
                                $getResult = bot("copyMessage", [
                                    'chat_id' => $chat_id,
                                    'from_chat_id' => -1001444743477,
                                    'message_id' => $rowTwo["copy_message_id"]
                                ]);
                            }
                            if ($role == 6) {
                                $sql = "select * from orders where id = " . $key;
                                $resultOrder = pg_query($conn, $sql);
                                $row = pg_fetch_assoc($resultOrder);
                                $order_name = $row["title"];
                                $sqlUpdate = "update step_order set step_2 = 10 where chat_id = " . $chat_id;
                                $result = pg_query($conn, $sqlUpdate);
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "<b><i>" . $order_name . "</i></b> Yana ma'lumot qo'shishingiz mumkin!",
                                    'parse_mode' => "html",
                                    'reply_markup' => $OnlyBack
                                ]);

                            } else {
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Buyurtmadagi ma'lumotlar shulardan iborat edi!",
                                    'reply_markup' => $OnlyBack
                                ]);
                            }
                        } else {
                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);
                            if ($typeOFUser == 6) {
                                $sql = "select * from orders where id = " . $key;
                                $resultOrder = pg_query($conn, $sql);
                                $row = pg_fetch_assoc($resultOrder);
                                $order_name = $row["title"];
                                $sqlUpdate = "update step_order set step_2 = 10 where chat_id = " . $chat_id;
                                $result = pg_query($conn, $sqlUpdate);
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Hozircha <b><i>" . $order_name . "</i></b> nomli  buyurtmada malumotlar mavjud emas,\nHohlasangiz ma'lumotlar kiritishingiz mumkin! 🙃",
                                    'parse_mode' => "html",
                                    'reply_markup' => $menu
                                ]);
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Hozircha Buyurtma malumotlari mavjud emas,\nHohlasangiz ma'lumotlar kiritishingiz mumkin! 🙃",
                                    'reply_markup' => $OnlyBack
                                ]);
                            } else {
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => "Hozircha Buyurtma malumotlari mavjud emas! 🙃",
                                    'reply_markup' => $OnlyBack
                                ]);
                            }
                        }
                    }
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);

                    $dataArray = explode("_", $data);
                    $userId = $dataArray[0];
                    $orderId = $dataArray[1];

                    $sql = "SELECT b.id, b.title_uz FROM brigada AS b
                            INNER JOIN brigada_users AS bu ON bu.brigada_id = b.id
                            WHERE b.user_id = $userId";
                    $result = pg_query($conn, $sql);
                    if (pg_num_rows($result) > 0) {
                        $i = 1;
                        $menu = [];
                        $arr = [];
                        while ($row = pg_fetch_assoc($result)) {
                            $brigadaId = $row['id'];
                            $brigadaName = $row['title_uz'];

                            $newData = $brigadaId."_".$orderId;
                            $menu[] = ['callback_data' => $newData,'text'=> "$brigadaName"];

                            if (pg_num_rows($result) == 5 || pg_num_rows($result) == 10) {
                                if($i % 5 == 0) {
                                    $arr[] = $menu;
                                    $menu = [];
                                }
                            } else if (pg_num_rows($result) == 4 || pg_num_rows($result) == 8) {
                                if($i % 4 == 0) {
                                    $arr[] = $menu;
                                    $menu = [];
                                }
                            } else if (pg_num_rows($result) == 3 || pg_num_rows($result) == 6) {
                                if($i % 3 == 0) {
                                    $arr[] = $menu;
                                    $menu = [];
                                }
                            } else if ($i % 2 == 0) {
                                $arr[] = $menu;
                                $menu = [];
                            } else {
                                if($i % 2 == 1) {
                                    $arr[] = $menu;
                                    $menu = [];
                                }
                            }
                        }

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "<b>Brigadalar ro'yxati</b>\n\n<i>Qaysi brigadaga yuborishni tanlang</i>",
                            'parse_mode' => 'html',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $arr
                            ])
                        ]);

                        $sqlUpdate = "UPDATE step_order SET step_2 = 60 WHERE chat_id = " . $chat_id;
                        pg_query($conn, $sqlUpdate);
                    }
                }
            }
        }

        if ($step_2 == 60) {
            if (isset($data)) {
                $dataArray = explode("_", $data);
                $userId = $dataArray[0];
                $orderId = $dataArray[1];

                $sqlOrders = "SELECT 
                                o.id,
                                o.title,
                                o.created_date,
                                o.dead_line,
                                cl.full_name,
                                cl.phone_number,
                                s.title AS section_title, 
                                u.type,
                                ors.section_id,
                                ctg.title AS category_name
                                FROM users AS u
                                LEFT JOIN order_responsibles AS ors ON ors.user_id = u.id
                                LEFT JOIN orders AS o ON o.id = ors.order_id
                                LEFT JOIN sections AS s ON ors.section_id = s.id
                                LEFT JOIN section_orders AS so ON so.order_id = o.id
                                INNER JOIN order_categories AS oc ON o.id = oc.order_id
                                INNER JOIN category AS ctg ON oc.category_id = ctg.id
                                INNER JOIN clients AS cl ON cl.id = o.client_id
                                WHERE u.chat_id = $chat_id AND ors.order_id = $orderId AND so.exit_date IS NULL";
                $resultOrders = pg_query($conn, $sqlOrders);
                if (pg_num_rows($resultOrders) > 0) {
                    $row = pg_fetch_assoc($resultOrders);
                    $title = $row["title"];
                    $endDate = $row["dead_line"];
                    $category_name = $row["category_name"];
                    $section_name = $row["section_title"];
                    $indiv = base64_decode($row['full_name']);

                    $textSend = "<b>#$title</b>\n<b>Mijoz:</b> $indiv\n<b>Buyurtma:</b> $category_name\n<b>Muddati:</b> $endDate\n<b>Bo'limi:</b> $section_name";

                    $newsql = "SELECT u.id, u.chat_id FROM brigada_users AS bu
                            LEFT JOIN users AS u
                            ON bu.user_id = u.id
                            WHERE bu.brigada_id = $userId";
                    $result = pg_query($conn, $newsql);
                    if (pg_num_rows($result) > 0) {
                        $getUserSectionId = "SELECT * FROM order_responsibles WHERE order_id = $orderId ORDER BY id ASC LIMIT 1";
                        $resultUserSectionId = pg_query($conn, $getUserSectionId);
                        $won = pg_fetch_assoc($resultUserSectionId);
                        $sectionId = $won['section_id'];
                       
                        while ($wow = pg_fetch_assoc($result)) {
                            $workerId = $wow['id'];
                            $send_id = $wow['chat_id'];

                            $respon = "INSERT INTO order_responsibles (order_id, user_id, section_id) VALUES (".$orderId.", ".$workerId.", ".$sectionId.")";
                            pg_query($conn, $respon);

                            bot('sendMessage', [
                                'chat_id' => $send_id,
                                'text' => $textSend,
                                'parse_mode' =>  "html",
                            ]);
                        }
                    }
                }

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                $bekor = json_encode($bekor = [
                    'keyboard' => [
                        ["🔍 Buyurtmalarni qidirish"],
                        ["Barcha buyurtmalar"],
                        ["Mening buyurtmalarim"]
                    ],
                    'resize_keyboard' => true
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<i>Brigada ishchilariga yuborildi</i>",
                    'parse_mode' => "html",
                    'reply_markup' => $bekor
                ]);

                $sqlUpdate = "UPDATE step_order SET step_2 = 1 WHERE chat_id = " . $chat_id;
                pg_query($conn, $sqlUpdate);
            }
        }
        // END CHANGES
        if ($step_2 == 30) {
            if ($data == $checkWorkersData) {
                $sql = "UPDATE step_order SET step_2 = 31 where chat_id = " . $chat_id;
                pg_query($conn, $sql);
                $dataCancel = $key . "_cancelWorkers_" . $keyThirst;
                $dataConfirm = $key . "_confirmWokers_" . $keyThirst;
                $readyInfoBtn = json_encode([
                    'inline_keyboard' => [
                        [
                            ['callback_data' => $dataCancel, 'text' => "Bekor qilish 🚫"], ['callback_data' => $dataConfirm, 'text' => "✅ Tasdiqlayman"]
                        ],
                        [
                            ['callback_data' => "home", 'text' => "🏘 Bosh menu"]
                        ]
                    ]
                ]);
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Bo'lim ichisini tasdiqlang! ✅",
                    'reply_markup' => $readyInfoBtn
                ]);
            }
        }

        if ($step_2 == 31) {
            if ($data == $checkWorkers) {
                $update = "UPDATE orders SET user_id = " . $key . " where id = " . $keyThirst;
                $result = pg_query($conn, $update);
                if ($result == true) {
                    $sqlOrder = "select title from orders  where id = " . $keyThirst;
                    $resultSql = pg_query($conn, $sqlOrder);
                    $rowOrder = pg_fetch_assoc($resultSql);
                    $titleOrder = $rowOrder["title"];

                    $sql = "SELECT chat_id FROM users where id = " . $key;
                    $result = pg_query($conn, $sql);
                    if (pg_num_rows($result) > 0) {
                        $row = pg_fetch_assoc($result);
                        $WorkerChatId = $row["chat_id"];
                        $lookOrders = $keyThirst . "_lookOrders_35";
                        $lookOrders = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['callback_data' => $lookOrders, 'text' => "Buyurtmani ko`rish"]

                                ],
                            ]
                        ]);
                        bot('sendMessage', [
                            'chat_id' => $WorkerChatId,
                            'text' => "📌<b><i>" . $titleOrder . "</i></b> nomli buyurtmani o'rnatish sizga topshirildi",
                            'parse_mode' => "html",
                            'reply_markup' => $lookOrders
                        ]);
                    }
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Siz buyurtmani Bo'lim ichisiga biriktrib yubordingiz!",
                        'reply_markup' => $menu
                    ]);
                }
            }
            if ($data == $cancelWorkers) {
                $sql = "SELECT 
                             us.user_id,
                             u.second_name
                         FROM users_section as us 
                         inner join users as u  on us.user_id = u.id 
                         where us.section_id = 35 and us.role = 7";
                $result = pg_query($conn, $sql);
                $arr_uz = [];
                $row_arr = [];
                if (pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $user_id = $row['user_id'];
                        $worker_name = $row['second_name'];
                        $dataWorkers = $user_id . "_workerId_" . $key;
                        $arr_uz[] = ["text" => $worker_name, "callback_data" => $dataWorkers];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];

                    }
                    $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Bo'lim ishchilaridan birini tanlang!",
                        'reply_markup' => $btnKey
                    ]);
                    $sqlUpdate = "update step_order set step_2 = 30 where chat_id = " . $chat_id;
                    $result = pg_query($conn, $sqlUpdate);
                    die();
                }
            }
        }

        if ($step_2 == 100) {
            if ($data == $info) {
                $sqlGetRequiredMaterials = "SELECT 
                     r.title, 
                     o.title as order_name,
                     r.id FROM required_material_order as r 
                     INNER JOIN orders AS o ON r.order_id = o.id
                     WHERE r.status = 0 and r.order_id = " . $key . " limit 1";
                $resultRequiredMaterials = pg_query($conn, $sqlGetRequiredMaterials);


                $sqlUpdate = "update step_order set step_2 = 101 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);

                $sql = "select * from order_materials where order_id = " . $key;
                $resultOrder = pg_query($conn, $sql);
                $sqlUpdateLastId = "update last_id_order set last_id = " . $key . " where chat_id = " . $chat_id;
                $resultStep = pg_query($conn, $sqlUpdateLastId);

                $requiredMaterials = "SELECT * FROM required_material_order where order_id = " . $key;
                $resultMaterials = pg_query($conn, $requiredMaterials);
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
                if (pg_num_rows($resultOrder) > 0 or pg_num_rows($resultMaterials) > 0) {
                    while ($row = pg_fetch_assoc($resultOrder)) {
                        $getResult = bot("copyMessage", [
                            'chat_id' => $chat_id,
                            'from_chat_id' => -1001444743477,
                            'message_id' => $row["copy_message_id"]
                        ]);
                    }
                    while ($rowTwo = pg_fetch_assoc($resultMaterials)) {
                        $getResult = bot("copyMessage", [
                            'chat_id' => $chat_id,
                            'from_chat_id' => -1001444743477,
                            'message_id' => $rowTwo["copy_message_id"]
                        ]);
                    }
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Buyurtmadagi ma'lumotlar shulardan iborat edi!",
                        'reply_markup' => $OnlyBack
                    ]);
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Hozircha Buyurtma malumotlari mavjud emas! 🙃",
                        'reply_markup' => $OnlyBack
                    ]);
                }
            }
        }

        if ($step_2 == 150) {
            
            $text_for_client = '';
            
            if ($dataArr[2] == "shpon") {
                $dateRightNow = date("Y-m-d H:i:s");
                $explode_data = explode("_", $data);
                $order_id = $explode_data[0];
                $section_id = $explode_data[1];
                $current_section_id = $explode_data[3];

                $insertOrderSection = "INSERT INTO section_orders (order_id,section_id,enter_date) VALUES (" . $order_id . "," . $section_id . ",'" . $dateRightNow . "') ";
                $result_update_step = pg_query($conn, $insertOrderSection);

                $update_order_step = "UPDATE order_step SET status = 1 WHERE order_id = " . $order_id . " and section_id = " . $section_id;
                $result_update_step = pg_query($conn, $update_order_step);
                
                // send sms to client
                $sql_client = "SELECT ord.title, cl.chat_id FROM orders AS ord 
                INNER JOIN clients AS cl ON cl.id = ord.client_id
                WHERE ord.id = ".$order_id;
                
                $result_find_client = pg_query($conn, $sql_client);
                $row_client = pg_fetch_assoc($result_find_client);
               

                if ($section_id == 42) {
                    $txt = "Siz buyurtmani Raspil sehiga o`tkazdingiz!";
                    
                    if(!empty($row_client)){
                        
                        $token = '1576388332:AAG2d9KptV4wICUw6BMujGc3aYEvo1wzQNs';
                        $msg = '📌 <i>'.$row_client['title'].'</i> nomli buyurtmangiz:'.PHP_EOL.'<b>Raspil</b>  bo`limga o`tdi';
                        
                        $arr = [
                              'chat_id' => $row_client['chat_id'],
                              'text' => $msg,
                              'parse_mode' => "html"
                            ];
                        $url = 'https://api.telegram.org/bot' . $token . '/sendMessage?' . http_build_query($arr);
                        file_get_contents($url); 
                    }
                    
                    
                } else if ($section_id == 29) {
                    $txt = "Siz buyurtmani Shpon sehiga o`tkazdingiz!";
                    
                    
                    if(!empty($row_client)){
                        $token = '1576388332:AAG2d9KptV4wICUw6BMujGc3aYEvo1wzQNs';
                        $msg = '📌 <i>'.$row_client['title'].'</i> nomli buyurtmangiz:'.PHP_EOL.'<b>Shpon</b>  bo`limga o`tdi';
                        
                        $arr = [
                              'chat_id' => $row_client['chat_id'],
                              'text' => $msg,
                              'parse_mode' => "html"
                            ];
                        $url = 'https://api.telegram.org/bot' . $token . '/sendMessage?' . http_build_query($arr);
                        file_get_contents($url); 
                    }
                }
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $txt,
                    'reply_markup' => $menu
                ]);
                
                
                
                
                
                
                
                
                
                
                    


                $sql_step = "select * from order_step where status = 0 and order_id = " . $order_id . " and (section_id = 42 or  section_id = 29)";
                $result_step = pg_query($conn, $sql_step);
                if (!pg_num_rows($result_step) > 0) {
                    $sqlUpdate = "update section_orders set step = 1, exit_date = '" . $dateRightNow . "' where exit_date is null and order_id = " . $order_id . " and section_id = " . $current_section_id;
                    $resultUpdate = pg_query($conn, $sqlUpdate);


                    $sql = "SELECT * FROM users WHERE type = 1 or type = 2";
                    $result = pg_query($conn, $sql);
                    $admins = [];
                    if (pg_num_rows($result) > 0) {
                        while ($row = pg_fetch_assoc($result)) {
                            if (isset($row["chat_id"]) and !empty($row["chat_id"])) {
                                $admins[] = $row["chat_id"];
                            }
                        }
                    }

                //     $sql_bonus = "
                //   SELECT 
                //     st.section_id,
                //     st.work_time,
                //     us.chat_id,
                //     sm.user_id,
                //     mm.penalty_summ,
                //     mm.bonus_sum,
                //     ord.title as order_names,
                //     sec.title as section_name,
                //     us.second_name,
                //     us.chat_id,
                //     so.enter_date

                //   FROM section_times as st 
                //   INNER JOIN section_minimal as sm on sm.section_id = st.section_id
                //   INNER JOIN users as us on sm.user_id = us.id
                //   INNER JOIN minimal as mm on sm.minimal_id = mm.id
                //   INNER JOIN orders as ord on ord.id = " . $order_id . "
                //   INNER JOIN sections as sec on sm.section_id = sec.id
                //   INNER JOIN section_orders  as so on sec.id = so.section_id and ord.id = so.order_id
                //   WHERE st.section_id =       
                // " . $current_section_id;

                    $sql_bonus = "SELECT 
                                st.section_id,
                                st.work_time,
                                us.chat_id,
                                sm.user_id,
                                mm.penalty_summ,
                                mm.bonus_sum,
                                ord.title AS order_names,
                                sec.title AS section_name,
                                us.second_name,
                                us.chat_id,
                                usb.type,
                                usb.second_name AS brigadir
                                FROM section_times AS st 
                                INNER JOIN section_minimal AS sm ON sm.section_id = st.section_id
                                INNER JOIN users AS us ON sm.user_id = us.id 
                                INNER JOIN minimal AS mm ON sm.minimal_id = mm.id
                                INNER JOIN orders AS ord ON ord.id = ".$order_id."
                                INNER JOIN sections AS sec ON sm.section_id = sec.id
                                INNER JOIN order_responsibles AS ord_res ON ord_res.order_id = ".$order_id." AND ord_res.section_id = ".$current_section_id."
                                INNER JOIN users AS usb ON ord_res.user_id = usb.id 
                                WHERE st.section_id = ".$current_section_id;
                    $resultTwo = pg_query($conn, $sql_bonus);
                    if (pg_num_rows($resultTwo) > 0) {
                        $true = true;
                        $responsible = '';
                        $users_res = '';
                        $us_chat_id = [];
                        while ($rowThree = pg_fetch_assoc($resultTwo)) {
                            $us_chat_id[] = $rowTwo["chat_id"];
                            $work_time = $rowThree["work_time"];
                            $enter_date = $rowThree["enter_date"];
                            $dead_line_time = date("Y-m-d H:i:s", strtotime('+' . $work_time . ' hours', strtotime($enter_date)));

                            if ($dateRightNow > $dead_line_time) {
                                $type = 2;
                                $differce_time = differenceInHours($dateRightNow, $dead_line_time);
                                $round_time = round($differce_time);
                            } else if ($dateRightNow < $dead_line_time) {
                                $type = 1;
                                $differce_time = differenceInHours($dateRightNow, $dead_line_time);
                                $round_time = round($differce_time);
                            }
                            $lose_time = differenceInHours($enter_date, $dateRightNow);
                            $lose_time = round($lose_time);


                            $bonus_sum = $rowThree["bonus_sum"];
                            $penalty_summ = $rowThree["penalty_summ"];

                            if ($type == 1) {
                                $quantity = $round_time * $bonus_sum;
                                $txt_end = "<b>💴 Bonus summasi</b>: ";
                                $user_id = 93;
                                $receiver = $rowThree["chat_id"];
                                $title_msg = "<b>✅ Bonus yozildi</b>!";
                                $category_id = 21;
                            } else if ($type == 2) {
                                $quantity = $round_time * $penalty_summ;
                                $txt_end = "<b>💴 Jarima summasi</b>: ";
                                $user_id = $rowThree["user_id"];
                                $receiver = 284914591;//1270367;
                                $title_msg = "<b>❌ Jarima yozildi</b>!";
                                $sql_update = "UPDATE salary_category SET balance = balance + " . $quantity . " WHERE id = 31";
                                pg_query($conn, $sql_update);
                                $category_id = 31;
                            }

                            $quantity_sum = number_format("$quantity", 0, " ", " ");
                            $quantity_sum = $quantity_sum . " so'm";
                            $order_names = $rowThree["order_names"];
                            $section_name = $rowThree["section_name"];
                            $responsible = $responsible . $rowThree["brigadir"] . ", ";

                            if ($i != 1) {
                                $users_res = $users_res . PHP_EOL . "👤 <b>" . $rowThree["brigadir"] . "</b>: " . $quantity_sum;
                            } else {
                                $users_res = $users_res . "👤 <b>" . $rowThree["brigadir"] . "</b>: " . $quantity_sum;
                            }

                            $sql_insert_salary = "
                      INSERT INTO salary_event_balance (user_id, receiver, quantity, category_id, date,type) 
                        VALUES 
                      (" . $user_id . "," . $receiver . "," . $quantity . "," . $category_id . ",'" . $dateRightNow . "',3);
                    ";
                            // bot('sendMessage',[
                            //   'chat_id' => $chat_id,
                            //   'text' => "sql  =  ".$sql_insert_salary
                            // ]);
                            $result_bonus = pg_query($conn, $sql_insert_salary);

                        }
                        $txtSend = $title_msg . "\n<b>📦 Buyurtma nomi</b>:" . $order_names . "\n<b>🏭 Bo`lim nomi</b>: " . $section_name . "\n<b>👤 Ma`sullar</b>: " . $responsible . PHP_EOL . $txt_end . $users_res . PHP_EOL . "🕔 <b>Ish vaqti</b>: " . $lose_time." soat";

                        foreach ($admins as $key => $value) {
                            botAdmin('sendMessage', [
                                'chat_id' => $value,
                                // 'chat_id' => 284914591,
                                'text' => $txtSend,
                                'parse_mode' => 'html'
                            ]);
                        }

                        $deleteFromRespon = "DELETE FROM order_responsibles WHERE order_id = ".$order_id." AND section_id = ".$current_section_id;
                        pg_query($conn, $deleteFromRespon);

                        $count_us = count($us_chat_id);
                        for ($i = 0; $i < $count_us; $i++) {
                            if ($type == 1) {
                                // code...
                                bot('sendMessage', [
                                    'chat_id' => $us_chat_id[$i],
                                    'text' => "💰 Sizga Bonus yozildi.\n⏳ Bonusni rahbar ko`rib chiqadi!"
                                ]);
                            } else {
                                bot('sendMessage', [
                                    'chat_id' => $us_chat_id[$i],
                                    'text' => "💰 Sizga Jarima yozildi.\n⏳ Jarimani rahbar ko`rib chiqadi!"
                                ]);
                            }
                        }
                    } else {
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Sql 1" 
                        ]);
                    }
                }


            }
        }

        if ($step_2 == 200) {
            if ($data == $branch) {
                $sqlUpdateLast = "update last_id_order set order_type = 1 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdateLast);

                $sqlUpdate = "update step_order set step_2 = 7 where chat_id = " . $chat_id;
                $result = pg_query($conn, $sqlUpdate);

                $sql = "select * from clients where status = 1 and branch_id = " . $key . " limit 20";
                $result = pg_query($conn, $sql);
                $sqlSecond = "select * from clients where status = 1 and branch_id = " . $key;
                $resultSecond = pg_query($conn, $sqlSecond);
                // $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
                $arr_uz = [];
                $row_arr = [];
                if (pg_num_rows($result) > 0) {
                    while ($value = pg_fetch_assoc($result)) {
                        $client_id = $value['id'];
                        $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = " . $client_id;
                        $resultOrder = pg_query($conn, $sqlOrder);
                        $branchTitle = base64_decode($value['full_name']);
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id . "_clients"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $nextClients = $client_id . "_nextClient";
                    if (pg_num_rows($resultSecond) > 20) {
                        $row_arr[] = [["text" => "➡️ Boshqa Mijozlar", "callback_data" => $nextClients]];
                    }
                    $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);

                    bot('editMessageText', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Mijozlardan birini tanlang!",
                        'reply_markup' => $btnKey
                    ]);
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
                        'reply_markup' => $menu
                    ]);
                }
            }
        }
    }
    include 'search_bot.php';