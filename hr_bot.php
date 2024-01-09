<?php
    // include 'db/db.php';

    const TOKEN = '1087756719:AAFhvNL9DyPc96Rd_zrSP0IHDdUU4AARANQ';
    const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;
    
    function bot($method, $data = []) {
        $url = BASE_URL.'/'.$method;
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
        $url = BASE_URL.'/'.$method;
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
    
    function typing($ch) {
        return bot('sendChatAction', [
            'chat_id' => $ch,
            'action' => 'typing'
        ]);
    }

    $update = file_get_contents('php://input');
    $update = json_decode($update);
    $message = $update->message;
    if (isset($update->callback_query)) {
        $chat_id = $update->callback_query->message->chat->id;
        $message_id = $update->callback_query->message->message_id;
        $realname = $update->callback_query->message->chat->first_name;
        $data = $update->callback_query->data;
        $nameuser = "";
    } else {
        $chat_id = $message->chat->id;
        $message_id = $message->message_id;
        $realname = $message->chat->first_name;
        $text = $message->text;
        $nameuser = $message->from->username;
    }

    if (isset($text)) {
        typing($chat_id);
    }
    
    // NOT NEED STEP LANG
        $langArray = array(
            array("🇺🇿 O'zbekcha", "🇷🇺 Русский"),
        );
        $uzLanguageArray = array(
            array("📋 Hujjat topshirish"),
            array("🔙 Orqaga")
        );
        $ruLanguageArray = array(
            array("📋 Сдавать документ"),
            array("🔙 Назад")
        );
        $uzafterRegister = array(
            array("®️ Biz haqimizda", "📋 Hujjat topshirish"),
            array("🔙 Orqaga")
        );
        $ruafterRegister = array(
            array("®️ О нас", "📋 Сдавать документ"),
            array("🔙 Назад")
        );

    // SEND MESSAGE IF STEP END
    function sendMessageEnd($stepLang, $uId, $chat_id, $conn) {
        $sql = "UPDATE actionreg SET step_1 = $stepLang, step_2 = 10000 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
        pg_query($conn, $sql);

        $replyMarkup = array(
            'remove_keyboard' => true 
        );
        $encodedMarkup = json_encode($replyMarkup);

        if ($stepLang == 1) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Siz barcha so'rovlarga javob berdingiz! Kottakon rahmat! Quyida sizning ma'lumotlaringiz 👇",
                'reply_markup' => $encodedMarkup
            ]);
        } else {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Вы ответили на все вопросы! Большое спасибо! Ниже ваши данные 👇",
                'reply_markup' => $encodedMarkup
            ]);
        }

        $getUserInfos = "SELECT * FROM userinfo WHERE id = $uId AND company_id = ".BOT_ID." AND chat_id = $chat_id";
        $resultUserInfos = pg_query($conn, $getUserInfos);

        if (pg_num_rows($resultUserInfos) > 0) {
            while ($row = pg_fetch_assoc($resultUserInfos)) {
                $userFull = $row['fullname'];
                $userFull = base64_decode($userFull);
                
                $userPhone = $row['phone'];

                $userMail = $row['mail'];
                $userMail = base64_decode($userMail);
                
                $userBirth = $row['birthday'];
                $userBirth = base64_decode($userBirth);
            }
            
            if ($stepLang == 1) {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>F-I-SH: </b><i>".$userFull."</i>\n<b>Telefon raqamingiz: </b><i>".$userPhone."</i>\n<b>Pochtangiz: </b><i>".$userMail."</i>\n<b>Tug'ilgan kuningiz: </b><i>".$userBirth."</i>\n\n<b>agar tasdiqlasangiz, xabar bizning korxonaga yuboriladi va siz bilan aloqaga chiqiladi!</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [["text" => "👍 Tasdiqlayman", "callback_data" => "yes_good"],["text" => "👎 Yo'q, Tasdiqlamayman", "callback_data" => "no_bad"]],
                        ]
                    ])
                ]);
            } else {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>Ф-И-Ш: </b><i>".$userFull."</i>\n<b>Ваш номер телефона: </b><i>".$userPhone."</i>\n<b>Твое письмо: </b><i>".$userMail."</i>\n<b>Твой день рождения: </b><i>".$userBirth."</i>\n\n<b>если вы подтвердите, сообщение будет отправлено в нашу компанию и мы свяжемся с вами!</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [["text" => "👍 Подтверждаю", "callback_data" => "yes_good"],["text" => "👎 Нет не подтверждаю", "callback_data" => "no_bad"]],
                        ]
                    ])
                ]);
            }
        }
    }

    // SEND MESSAGE FUNCTION
    function sendMessageNext($stepLang, $lastStep, $chat_id, $conn) {
        if ($stepLang == 1) {
            $cancelArray = array(
                array("❌ Bekor qilish")
            );
            $addToo = array(
                array("➕ Yana qo'shish"),
                array("📝 Keyingi so'rov"),
                array("❌ Bekor qilish")
            );
            $nextQuesProg = "📝 Keyingi so'rov";
        } else {
            $cancelArray = array(
                array("❌ Отменить")
            );
            $addToo = array(
                array("➕ Добавить еше"),
                array("📝 Следующий вопрос"),
                array("❌ Отменить")
            );
            $nextQuesProg = "📝 Следующий вопрос";
        }

        $getStepByOrder = "SELECT * FROM company_settings WHERE company_id = ".BOT_ID." AND order_step = ".$lastStep;
        $resultStepByOrder = pg_query($conn, $getStepByOrder);

        while ($row = pg_fetch_assoc($resultStepByOrder)) {
            $step = $row['step'];
        }

        switch ($step) {
            case 1:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 1 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 1 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 1 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                $getProfessionId = "SELECT * FROM company_profession WHERE status = 1 AND company_id = ".BOT_ID;
                $resultProfessionId = pg_query($conn, $getProfessionId);

                while ($row2 = pg_fetch_assoc($resultProfessionId)) {
                    $profId = $row2['profession_id'];

                    $getProfession = "SELECT * FROM profession WHERE id = ".$profId;
                    $resultProfession = pg_query($conn, $getProfession);

                    while ($row3 = pg_fetch_assoc($resultProfession)) {
                        $professionId = $row3['id'];
                        if ($stepLang == 1) {
                            $professionTitle = base64_decode($row3['title_uz']);
                        } else {
                            $professionTitle = base64_decode($row3['title_ru']);
                        }

                        $menu[] = [['callback_data' => "$professionId", 'text'=> "$professionTitle"]];
                    }
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $menu
                    ])
                ]);
            break;

            case 7:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 7 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 7 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 7 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "🏡 Hovlida", "callback_data" => "house"],["text" => "🏬 Ko'p qavatli uyda", "callback_data" => "flat"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "🏡 Во дворе", "callback_data" => "house"],["text" => "🏬 Доме", "callback_data" => "flat"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 8:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 8 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                    $textContact = "📞 Raqam jo'natish";
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 8 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                    $textContact = "📞 Отправить номер";
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => array(
                        array(
                            array( 
                                    'text' => $textContact,
                                    'request_contact' => true
                                )
                            ),
                            array(
                                array( 
                                        'text'=> $cancelArray[0][0],
                                    )
                                )
                            ),
                    'one_time_keyboard' => true,
                    'resize_keyboard' => true
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 8 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;

            case 10:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 10 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 10 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 10 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $messageText,
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "🧍‍♂️ Yolg'iz", "callback_data" => "single"],["text" => "👨‍👩‍👦 Oilaliy", "callback_data" => "merried"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "🧍‍♂️ Один", "callback_data" => "single"],["text" => "👨‍👩‍👦 Семья", "callback_data" => "merried"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 11:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 11 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 11 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 11 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "🥉 O'rta", "callback_data" => "down"],["text" => "🥈 O'rta maxsus", "callback_data" => "middle"],["text" => "🥇 Oliy", "callback_data" => "up"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "🥉 ", "callback_data" => "down"],["text" => "🥈 O'rta maxsus", "callback_data" => "middle"],["text" => "🥇 Oliy", "callback_data" => "up"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 15:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 15 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 15 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => $addToo,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 15 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;

            case 16:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 16 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 16 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 16 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "👍 Ha ishlaganman", "callback_data" => "yes_work"],["text" => "👎 Yo'q ishlamaganman", "callback_data" => "no_work"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "👍 Да работал", "callback_data" => "yes_work"],["text" => "👎 Нет не работал", "callback_data" => "no_work"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 21:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 21 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 21 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => $addToo,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 21 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;

            case 22:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 22 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 22 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 22 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "😎 Ha chiqqanman", "callback_data" => "yes_trip"],["text" => "🙃 Yo'q chiqmaganman", "callback_data" => "no_trip"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "😎 Да путешествовал", "callback_data" => "yes_trip"],["text" => "🙃 Не путешествовал", "callback_data" => "no_trip"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 26:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 26 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 26 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => $addToo,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 26 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;

            case 33:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 33 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 33 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 33 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "😕 Ha sudlangan", "callback_data" => "yes_court"],["text" => "😊 Yo'q sudlanmagan", "callback_data" => "no_court"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "😕 Да осужден", "callback_data" => "yes_court"],["text" => "😊 Нет убеждений", "callback_data" => "no_court"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 34:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 34 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 34 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => $addToo,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 34 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;

            case 38:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 38 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 38 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 38 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "😊 Ha bor", "callback_data" => "cardrive_yes"],["text" => "😕 Yo'q", "callback_data" => "cardrive_no"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "😊 Да есть", "callback_data" => "cardrive_yes"],["text" => "😕 Нет", "callback_data" => "cardrive_no"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 40:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 40 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 40 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 40 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "A", "callback_data" => "grade_a"],["text" => "B", "callback_data" => "grade_b"]],
                                [["text" => "C", "callback_data" => "grade_c"],["text" => "D", "callback_data" => "grade_d"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "A", "callback_data" => "grade_a"],["text" => "B", "callback_data" => "grade_b"]],
                                [["text" => "C", "callback_data" => "grade_c"],["text" => "D", "callback_data" => "grade_d"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 41:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 41 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 41 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 41 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);
                $row2 = pg_fetch_assoc($resultInfoReg);
                $userId = $row2['user_id'];

                $getLang = "SELECT * FROM company_language as cl LEFT JOIN user_lang as ul on ul.language = cl.language_id AND ul.user_id = $userId AND cl.company_id = ".BOT_ID." WHERE cl.status = 1 AND ul.id is NULL";
                $resultLang = pg_query($conn, $getLang);

                if (pg_num_rows($resultLang) > 0) {
                    while ($row3 = pg_fetch_assoc($resultLang)) {
                        $LId = $row3['language_id'];

                        $getLanguageText = "SELECT * FROM language WHERE id = ".$LId;
                        $resultLanguageText = pg_query($conn, $getLanguageText);

                        while ($row4 = pg_fetch_assoc($resultLanguageText)) {
                            $professionId = $row4['id'];
                            if ($stepLang == 1) {
                                $professionTitle = base64_decode($row4['title_uz']);
                            } else {
                                $professionTitle = base64_decode($row4['title_ru']);
                            }

                            $menu[] = [['callback_data' => "$professionId", 'text'=> "$professionTitle"]];
                        }
                    }

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $menu
                        ])
                    ]);
                } else {
                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 45 ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStepNew = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStepNew WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($stepLang, $lastStepNew, $chat_id, $conn);
                    }
                }
            break;

            case 42:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 42 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 42 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 42 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "20 %", "callback_data" => "20"],["text" => "40 %", "callback_data" => "40"],["text" => "60 %", "callback_data" => "60"],["text" => "80 %", "callback_data" => "80"],["text" => "100 %", "callback_data" => "100"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "20 %", "callback_data" => "20"],["text" => "40 %", "callback_data" => "40"],["text" => "60 %", "callback_data" => "60"],["text" => "80 %", "callback_data" => "80"],["text" => "100 %", "callback_data" => "100"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 43:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 43 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 43 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 43 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "20 %", "callback_data" => "20"],["text" => "40 %", "callback_data" => "40"],["text" => "60 %", "callback_data" => "60"],["text" => "80 %", "callback_data" => "80"],["text" => "100 %", "callback_data" => "100"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "20 %", "callback_data" => "20"],["text" => "40 %", "callback_data" => "40"],["text" => "60 %", "callback_data" => "60"],["text" => "80 %", "callback_data" => "80"],["text" => "100 %", "callback_data" => "100"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 44:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 44 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 44 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 44 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "20 %", "callback_data" => "20"],["text" => "40 %", "callback_data" => "40"],["text" => "60 %", "callback_data" => "60"],["text" => "80 %", "callback_data" => "80"],["text" => "100 %", "callback_data" => "100"]],
                            ]
                        ])
                    ]);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>$messageText</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [["text" => "20 %", "callback_data" => "20"],["text" => "40 %", "callback_data" => "40"],["text" => "60 %", "callback_data" => "60"],["text" => "80 %", "callback_data" => "80"],["text" => "100 %", "callback_data" => "100"]],
                            ]
                        ])
                    ]);
                }
            break;

            case 45:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 45 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 45 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => $addToo,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 45 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;

            case 46:
                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 46 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 46 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = 46 AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);
                $row2 = pg_fetch_assoc($resultInfoReg);
                $userId = $row2['user_id'];

                $getUserProfId = "SELECT * FROM userinfo WHERE id = $userId";
                $resultUserProfId = pg_query($conn, $getUserProfId);

                while ($row3 = pg_fetch_assoc($resultUserProfId)) {
                    $pId = $row3['profession_id'];

                    if ($pId > 0) {
                        $getProgramm = "SELECT * FROM company_programm AS cp LEFT JOIN programm AS p ON p.id = cp.programm_id AND p.profession_id = $pId AND cp.company_id = ".BOT_ID." WHERE cp.status = 1 AND p.profession_id = $pId";
                        $resultProgramm = pg_query($conn, $getProgramm);
                       
                        $i = 1;
                        $menu = [];
                        $arrCat = [];
                        while ($row4 = pg_fetch_assoc($resultProgramm)) {
                            $programmId = $row4['id'];
                            $programmTitle = $row4['title'];

                            $menu[] = ['callback_data' => "$programmId",'text'=> "$programmTitle"];

                            if($i % 2 == 0) {
                                $arrCat[] = $menu;
                                $menu = [];
                            }

                            $i++;
                        }
                        if(count($menu) == 1) {
                            $arrCat[] = $menu;
                        }
                        $arrCat[] = [['callback_data' => "nextto", 'text'=> "$nextQuesProg"]];

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "<b>$messageText</b>",
                            'parse_mode' => 'html',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $arrCat
                            ])
                        ]);
                    } else {
                        $getProgramm = "SELECT * FROM company_programm AS cp LEFT JOIN programm AS p ON p.id = cp.programm_id AND cp.company_id = ".BOT_ID." WHERE cp.status = 1";
                        $resultProgramm = pg_query($conn, $getProgramm);

                        if (pg_num_rows($resultProgramm) % 2 == 0) {
                            $i = 1;
                        } else {
                            $i = 0;
                        }
                        $menu = [];
                        $arrCat = [];
                        while ($row4 = pg_fetch_assoc($resultProgramm)) {
                            $programmId = $row4['id'];
                            $programmTitle = $row4['title'];

                            $menu[] = ['callback_data' => "$programmId",'text'=> "$programmTitle"];

                            if($i % 2 == 0) {
                                $arrCat[] = $menu;
                                $menu = [];
                            }

                            $i++;
                        }
                        $arrCat[] = [['callback_data' => "nextto", 'text'=> "$nextQuesProg"]];

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "<b>$messageText</b>",
                            'parse_mode' => 'html',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => $arrCat
                            ])
                        ]);
                    }
                }
            break;

            default:
                $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);
                $row2 = pg_fetch_assoc($resultInfoReg);
                $userId = $row2['user_id'];

                if ($stepLang == 1) {
                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = $step WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                } else {
                    $sql = "UPDATE actionreg SET step_1 = 2, step_2 = $step WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                }
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => $cancelArray,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                $getQuestionText = "SELECT * FROM company_settings WHERE step = $step AND company_id = ".BOT_ID;
                $resultQuestionText = pg_query($conn, $getQuestionText);

                $row = pg_fetch_assoc($resultQuestionText);
                if ($stepLang == 1) {
                    $messageText = base64_decode($row['title_uz']);
                } else {
                    $messageText = base64_decode($row['title_ru']);
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>$messageText</b>",
                    'parse_mode' => 'html',
                    'reply_markup' => $encodedMarkup
                ]);
            break;
        }
    }

    // START
    if ($text == "/start") {
        $sql = "SELECT * FROM actionreg WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
        $result = pg_query($conn, $sql);

        if (pg_num_rows($result) > 0) {
            $getUserInfo = "SELECT id FROM userinfo WHERE chat_id = $chat_id AND company_id = ".BOT_ID." AND is_active = 0";
            $resultUserInfo = pg_query($conn, $getUserInfo);
            $row2 = pg_fetch_assoc($resultUserInfo);
            $userId = $row2['id'];

            $deleteInfo = "DELETE FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo);
            
            $deleteInfo2 = "DELETE FROM user_grade WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo2);
            
            $deleteInfo3 = "DELETE FROM user_study WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo3);
            
            $deleteInfo4 = "DELETE FROM user_trip WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo4);
            
            $deleteInfo5 = "DELETE FROM user_work WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo5);
            
            $deleteInfo6 = "DELETE FROM user_prog WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo6);
            
            $deleteInfo7 = "DELETE FROM user_lang WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo7);
            
            $deleteInfo8 = "DELETE FROM user_family WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo8);
            
            $deleteInfo9 = "DELETE FROM user_ask WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo9);
            
            $deleteInfo10 = "DELETE FROM user_add WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo10);
            
            $deleteInfo11 = "DELETE FROM userinfo_last WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo11);
            
            $deleteInfo12 = "DELETE FROM ourwork WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo12);
            
            $deleteInfo13 = "DELETE FROM inforeg WHERE user_id = $userId AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo13);
            
            $deleteInfo14 = "DELETE FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo14);

            $deleteInfo15 = "DELETE FROM additional_aq WHERE user_id = $chat_id AND company_id = ".BOT_ID;
            pg_query($conn, $deleteInfo15);

            $sql = "UPDATE actionreg SET step_1 = 0, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);
        } else {
            $sql = "INSERT INTO actionreg (company_id, chat_id, step_1, step_2, order_num) VALUES (".BOT_ID.", ".$chat_id.", 0, 0, 0)";
            pg_query($conn, $sql);
        }

        $replyMarkup = array(
            'keyboard' => $langArray,
            'resize_keyboard' => true 
        );
        $encodedMarkup = json_encode($replyMarkup);
        
        $getCompanyInfo = "SELECT * FROM company_info WHERE company_id = ".BOT_ID." AND is_begin = 1 AND status = 1";
        $resultCompanyInfo = pg_query($conn, $getCompanyInfo);

        if (pg_num_rows($resultCompanyInfo) > 0) {
            while ($row = pg_fetch_assoc($resultCompanyInfo)) {
                $messageFileId = $row['file_id'];
                $messageText = $row['description'];
                $messageType = $row['type'];

                switch ($messageType) {
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $messageText,
                            'parse_mode' => 'html',
                            'reply_markup' => $encodedMarkup
                        ]);
                    break;

                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                            'caption' => $messageText,
                            'parse_mode' => 'html',
                            'reply_markup' => $encodedMarkup
                        ]);
                    break;

                    case 'video': 
                        bot('sendVideo', [ 
                            'chat_id' => $chat_id,
                            'video' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                            'caption' => $messageText, 
                            'parse_mode' => 'html',
                            'reply_markup' => $encodedMarkup
                        ]);
                    break; 
                } 
            } 
        } else { 
            bot('sendMessage', [ 
                'chat_id' => $chat_id, 
                'text' => "Assalomu aleykum. Bizning korxonamizda ishlamoqchimiz, unda tezroq shoshiling. Bizga o'zingiz haqingizda bizga yuboring. Ushbu bot orqali savollarga javob bering!",
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup 
            ]); 
        }
    }

    // USER STEP
    $sql = "SELECT step_1, step_2, order_num FROM actionreg WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
    $result = pg_query($conn, $sql);
    $step_1 = 0;
    $step_2 = 0;
    $orderId = 0;
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $step_1 = $row['step_1'];
            $step_2 = $row['step_2'];
            $orderId = $row['order_num'];
        }
    }

    // BASIC KEYBOARDS
    if ($step_1 == 1) {
        $documentArray = array(
            array("🔙 Orqaga", "🔝 Bosh sahifa")
        );
        $cancelArray = array(
            array("❌ Bekor qilish")
        );
        $addToo = array(
            array("➕ Yana qo'shish"),
            array("📝 Keyingi so'rov"),
            array("❌ Bekor qilish")
        );
        $afterQuesPart = "📝 Navbatdagi so'rovlar!";
        $againText = "Biladigan tillaringiz bo'lsa qo'shishingiz mumkin.";
        $nextQuesProg = "📝 Keyingi so'rov";
        $forMainText = "Bosh bo'limga qaytdingiz.";
        $discussionText = "Korxona bilan suhbatlashing.";
    } else {
        $documentArray = array(
            array("🔙 Назад", "🔝 На главную")
        );
        $cancelArray = array(
            array("❌ Отменить")
        );
        $addToo = array(
            array("➕ Добавить еше"),
            array("📝 Следующий вопрос"),
            array("❌ Отменить")
        );
        $afterQuesPart = "📝 Следующие запросы!";
        $againText = "Вы можете добавить, если у вас есть языки, которые вы знаете.";
        $nextQuesProg = "📝 Следующий вопрос";
        $forMainText = "Вы вернулись в основной раздел.";
        $discussionText = "Поговорите с компанией.";
    }

    // CHECK WHICH LANGUAGE CLIENT DO DOC
    if ($step_1 == 0 && $step_2 == 0) {
        if (isset($text)) {
            if ($text == $langArray[0][0]) {
                $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
                pg_query($conn, $sql);

                $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);
                $row2 = pg_fetch_assoc($resultInfoReg);
                $userId = $row2['user_id'];

                $getUserLast = "SELECT * FROM userinfo WHERE id = $userId AND is_active = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultUserLast = pg_query($conn, $getUserLast);

                if (pg_num_rows($resultUserLast) > 0) {
                    $replyMarkup = array(
                        'keyboard' => $uzafterRegister,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);   
                } else {
                    $replyMarkup = array(
                        'keyboard' => $uzLanguageArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);    
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "O'zbek tilini tanladingiz. Hujjatni o'zbek tilida to'ldiring. Hujjat to'ldirishni boshlash uchun <b><i>«Hujjat topshirish»</i></b> bo'limiga kiring.",
                    'parse_mode' => "html",
                    'reply_markup' => $encodedMarkup
                ]);
            } elseif ($text == $langArray[0][1]) {
                $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
                pg_query($conn, $sql);

                $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);
                $row2 = pg_fetch_assoc($resultInfoReg);
                $userId = $row2['user_id'];

                $getUserLast = "SELECT * FROM userinfo WHERE id = $userId AND is_active = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultUserLast = pg_query($conn, $getUserLast);

                if (pg_num_rows($resultUserLast) > 0) {
                    $replyMarkup = array(
                        'keyboard' => $ruafterRegister,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);   
                } else {
                    $replyMarkup = array(
                        'keyboard' => $ruLanguageArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);    
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Вы выбрали русский язык. Заполните документ на русском языке. Чтобы начать заполнение формы, перейдите в <b><i>«Сдавать документ»</i></b>.",
                    'parse_mode' => "html",
                    'reply_markup' => $encodedMarkup
                ]);
            } else {
                if ($text != "/start" && $text != $documentArray[0][1]) {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                    ]);
                }
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
        }
    }

    // CHECK MESSAGE - BACK 
    if ($text == $documentArray[0][0]) {
        // CHECK MESSAGE -  BACK FROM START STEP
        if ($step_1 == 1 && $step_2 == 0) {
            $sql = "UPDATE actionreg SET step_1 = 0, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);

            $replyMarkup = array(
                'keyboard' => $langArray,
                'resize_keyboard' => true 
            );
            $encodedMarkup = json_encode($replyMarkup);
            
            $getCompanyInfo = "SELECT * FROM company_info WHERE company_id = ".BOT_ID." AND is_begin = 1 AND status = 1";
            $resultCompanyInfo = pg_query($conn, $getCompanyInfo);

            if (pg_num_rows($resultCompanyInfo) > 0) {
                while ($row = pg_fetch_assoc($resultCompanyInfo)) {
                    $messageFileId = $row['file_id'];
                    $messageText = $row['description'];
                    $messageType = $row['type'];

                    switch ($messageType) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $messageText,
                                'parse_mode' => 'html',
                                'reply_markup' => $encodedMarkup
                            ]);
                        break;

                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                'caption' => $messageText,
                                'parse_mode' => 'html',
                                'reply_markup' => $encodedMarkup
                            ]);
                        break;

                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                'caption' => $messageText,
                                'parse_mode' => 'html',
                                'reply_markup' => $encodedMarkup
                            ]);
                        break;
                    }
                }
            } else {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Assalomu aleykum. Bizning korxonamizda ishlamoqchimiz, unda tezroq shoshiling. Bizga o'zingiz haqingizda bizga yuboring. Ushbu bot orqali savollarga javob bering!",
                    'parse_mode' => "html",
                    'reply_markup' => $encodedMarkup
                ]);
            }
        }
        if ($step_1 == 2 && $step_2 == 0) {
            $sql = "UPDATE actionreg SET step_1 = 0, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);
            
            $replyMarkup = array(
                'keyboard' => $langArray,
                'resize_keyboard' => true 
            );
            $encodedMarkup = json_encode($replyMarkup);
            
            $getCompanyInfo = "SELECT * FROM company_info WHERE company_id = ".BOT_ID." AND is_begin = 1 AND status = 1";
            $resultCompanyInfo = pg_query($conn, $getCompanyInfo);

            if (pg_num_rows($resultCompanyInfo) > 0) {
                while ($row = pg_fetch_assoc($resultCompanyInfo)) {
                    $messageFileId = $row['file_id'];
                    $messageText = $row['description'];
                    $messageType = $row['type'];

                    switch ($messageType) {
                        case 'text':
                            bot('sendMessage', [
                                'chat_id' => $chat_id,
                                'text' => $messageText,
                                'parse_mode' => 'html',
                                'reply_markup' => $encodedMarkup
                            ]);
                        break;

                        case 'photo':
                            bot('sendPhoto', [
                                'chat_id' => $chat_id,
                                'photo' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                'caption' => $messageText,
                                'parse_mode' => 'html',
                                'reply_markup' => $encodedMarkup
                            ]);
                        break;

                        case 'video':
                            bot('sendVideo', [
                                'chat_id' => $chat_id,
                                'video' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                'caption' => $messageText,
                                'parse_mode' => 'html',
                                'reply_markup' => $encodedMarkup
                            ]);
                        break;
                    }
                }
            } else {
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Assalomu aleykum. Bizning korxonamizda ishlamoqchimiz, unda tezroq shoshiling. Bizga o'zingiz haqingizda bizga yuboring. Ushbu bot orqali savollarga javob bering!",
                    'parse_mode' => "html",
                    'reply_markup' => $encodedMarkup
                ]);
            }
        }

        // CHECK MESSAGE - BACK FROM DOCUMENT START QUES
        if ($step_1 == 1 && $step_2 == 1) {
            $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);

            $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultInfoReg = pg_query($conn, $getInfoReg);
            $row2 = pg_fetch_assoc($resultInfoReg);
            $userId = $row2['user_id'];

            $getUserLast = "SELECT * FROM userinfo WHERE id = $userId AND is_active = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultUserLast = pg_query($conn, $getUserLast);

            if (pg_num_rows($resultUserLast) > 0) {
                $replyMarkup = array(
                    'keyboard' => $uzafterRegister,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);   
            } else {
                $replyMarkup = array(
                    'keyboard' => $uzLanguageArray,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);    
            }

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "O'zbek tilini tanladingiz. Hujjatni o'zbek tilida to'ldiring. Hujjat to'ldirishni boshlash uchun <b><i>«Hujjat topshirish»</i></b> bo'limiga kiring.",
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        }
        if ($step_1 == 2 && $step_2 == 1) {
            $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);

            $getInfoReg = "SELECT user_id FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultInfoReg = pg_query($conn, $getInfoReg);
            $row2 = pg_fetch_assoc($resultInfoReg);
            $userId = $row2['user_id'];

            $getUserLast = "SELECT * FROM userinfo WHERE id = $userId AND is_active = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultUserLast = pg_query($conn, $getUserLast);

            if (pg_num_rows($resultUserLast) > 0) {
                $replyMarkup = array(
                    'keyboard' => $ruafterRegister,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);   
            } else {
                $replyMarkup = array(
                    'keyboard' => $ruLanguageArray,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);    
            }

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Вы выбрали русский язык. Заполните документ на русском языке. Чтобы начать заполнение формы, перейдите в <b><i>«Сдавать документ»</i></b>.",
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        }

        // CHECK MESSAGE - BACK FROM ABOUT US
        if ($step_2 == 10001) {
            if ($step_1 == 1) {
                $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                $keyboard = $afterRegister;
            } else {
                $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                $keyboard = $afterRegister;
            }
            pg_query($conn, $sql);

            $replyMarkup = array(
                'keyboard' => $keyboard,
                'resize_keyboard' => true 
            );
            $encodedMarkup = json_encode($replyMarkup);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $forMainText,
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        }

        // CHECK MESSAGE - BACK FROM ABOUT US
        if ($step_2 == 10002) {
            if ($step_1 == 1) {
                $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                $keyboard = $uzafterRegister;
            } else {
                $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                $keyboard = $ruafterRegister;
            }
            pg_query($conn, $sql);

            $replyMarkup = array(
                'keyboard' => $keyboard,
                'resize_keyboard' => true 
            );
            $encodedMarkup = json_encode($replyMarkup);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $forMainText,
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        }
    }

    // CHECK MESSAGE - FROM MAIN
    if ($text == $documentArray[0][1]) {
        $sql = "UPDATE actionreg SET step_1 = 0, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
        pg_query($conn, $sql);

        $replyMarkup = array(
            'keyboard' => $langArray,
            'resize_keyboard' => true 
        );
        $encodedMarkup = json_encode($replyMarkup);
        
        $getCompanyInfo = "SELECT * FROM company_info WHERE company_id = ".BOT_ID." AND is_begin = 1 AND status = 1";
        $resultCompanyInfo = pg_query($conn, $getCompanyInfo);

        if (pg_num_rows($resultCompanyInfo) > 0) {
            while ($row = pg_fetch_assoc($resultCompanyInfo)) {
                $messageFileId = $row['file_id'];
                $messageText = $row['description'];
                $messageType = $row['type'];

                switch ($messageType) {
                    case 'text':
                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $messageText,
                            'parse_mode' => 'html',
                            'reply_markup' => $encodedMarkup
                        ]);
                    break;

                    case 'photo':
                        bot('sendPhoto', [
                            'chat_id' => $chat_id,
                            'photo' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                            'caption' => $messageText,
                            'parse_mode' => 'html',
                            'reply_markup' => $encodedMarkup
                        ]);
                    break;

                    case 'video':
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                            'caption' => $messageText,
                            'parse_mode' => 'html',
                            'reply_markup' => $encodedMarkup
                        ]);
                    break;
                }
            }
        } else {
            if ($step_1 == 1) {
                $message = "Assalomu aleykum. Bizning korxonamizda ishlamoqchimiz, unda tezroq shoshiling. Bizga o'zingiz haqingizda bizga yuboring. Ushbu bot orqali savollarga javob bering!";
            } else {
                if ($step_1 == 2) {
                    $message = "Ассалому алейкум. Мы хотим работать в нашей компании, тогда поторопитесь. Расскажите нам о себе. Отвечай на вопросы через этого бота!";
                }
            }

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        }
    }

    // CHECK MESSAGE - CANCEL DOCUMENT
    if ($text == $cancelArray[0][0]) {
        $getUserInfo = "SELECT id FROM userinfo WHERE chat_id = $chat_id AND company_id = ".BOT_ID." AND is_active = 0 OR is_active = NULL";
        $resultUserInfo = pg_query($conn, $getUserInfo);
        $row2 = pg_fetch_assoc($resultUserInfo);
        $userId = $row2['id'];

        $deleteInfo = "DELETE FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo);
        
        $deleteInfo2 = "DELETE FROM user_grade WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo2);
        
        $deleteInfo3 = "DELETE FROM user_study WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo3);
        
        $deleteInfo4 = "DELETE FROM user_trip WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo4);
        
        $deleteInfo5 = "DELETE FROM user_work WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo5);
        
        $deleteInfo6 = "DELETE FROM user_prog WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo6);
        
        $deleteInfo7 = "DELETE FROM user_lang WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo7);
        
        $deleteInfo8 = "DELETE FROM user_family WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo8);
        
        $deleteInfo9 = "DELETE FROM user_ask WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo9);
        
        $deleteInfo10 = "DELETE FROM user_add WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo10);
        
        $deleteInfo11 = "DELETE FROM userinfo_last WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo11);
        
        $deleteInfo12 = "DELETE FROM ourwork WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo12);
        
        $deleteInfo13 = "DELETE FROM inforeg WHERE user_id = $userId AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo13);
        
        $deleteInfo14 = "DELETE FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo14);

        $deleteInfo15 = "DELETE FROM additional_aq WHERE user_id = $chat_id AND company_id = ".BOT_ID;
        pg_query($conn, $deleteInfo15);
        
        if ($step_1 == 1) {
            $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);

            $replyMarkup = array(
                'keyboard' => $uzLanguageArray,
                'resize_keyboard' => true 
            );
            $encodedMarkup = json_encode($replyMarkup);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "O'zbek tilini tanladingiz. Hujjatni o'zbek tilida to'ldiring. Hujjat to'ldirishni boshlash uchun <b><i>«Hujjat topshirish»</i></b> bo'limiga kiring.",
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        } else {
            $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
            pg_query($conn, $sql);

            $replyMarkup = array(
                'keyboard' => $ruLanguageArray,
                'resize_keyboard' => true 
            );
            $encodedMarkup = json_encode($replyMarkup);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Вы выбрали русский язык. Заполните документ на русском языке. Чтобы начать заполнение формы, перейдите в <b><i>«Сдавать документ»</i></b>.",
                'parse_mode' => "html",
                'reply_markup' => $encodedMarkup
            ]);
        }
    }

    // CHECK MESSAGE - GO TO MENU
    if ($step_1 == 1 && $step_2 == 0) {
        if (isset($text)) {
            if ($text == $uzLanguageArray[0][0]) {
                $replyMarkup = array(
                    'keyboard' => $documentArray,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Hujjat topshirish bo'limiga kirdingiz. Ma'lumotlarni o'zbek tilida kiriting.",
                    'parse_mode' => "html",
                    'reply_markup' => $encodedMarkup
                ]);

                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$step_2." ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) { 
                        $lastStep = $row['order_step'];
                    }
                }

                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                pg_query($conn, $updateOrderNum);

                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
            } elseif ($text == $uzafterRegister[0][0]) {
                $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 10001 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                pg_query($conn, $sql);

                $getAboutInfo = "SELECT * FROM about_us WHERE status = 1 AND company_id = ".BOT_ID;
                $resultAboutInfo = pg_query($conn, $getAboutInfo);

                $replyMarkup = array(
                    'keyboard' => array(
                        array("🔙 Orqaga")
                    ),
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                if (pg_num_rows($resultAboutInfo) > 0) {
                    while ($row = pg_fetch_assoc($resultAboutInfo)) {
                        $messageFileId = $row['file'];
                        $messageText = $row['description'];
                        $messageType = $row['type'];

                        switch ($messageType) {
                            case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $messageText,
                                    'parse_mode' => 'html',
                                    'reply_markup' => $encodedMarkup
                                ]);
                            break;

                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                    'caption' => $messageText,
                                    'parse_mode' => 'html',
                                    'reply_markup' => $encodedMarkup
                                ]);
                            break;

                            case 'video': 
                                bot('sendVideo', [ 
                                    'chat_id' => $chat_id,
                                    'video' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                    'caption' => $messageText, 
                                    'parse_mode' => 'html',
                                    'reply_markup' => $encodedMarkup
                                ]);
                            break; 
                        }
                    }
                } else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bu bo'lim bo'sh!",
                        'reply_markup' => $encodedMarkup
                    ]);
                }
            } elseif ($text == $uzafterRegister[0][1]) {
                $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 10002 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => array(
                        array("🔙 Orqaga")
                    ),
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $discussionText,
                    'reply_markup' => $encodedMarkup
                ]);
            } else {
                if ($text != $uzLanguageArray[1][0] && $text != "/start" && $text != $uzafterRegister[0][0] && $text != $uzafterRegister[0][1]) {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
        }
    }
    if ($step_1 == 2 && $step_2 == 0) {
        if (isset($text)) {
            if ($text == $ruLanguageArray[0][0]) {
                $replyMarkup = array(
                    'keyboard' => $documentArray,
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "Вы вошли в раздел приложений. Введите информацию на русском языке.",
                    'parse_mode' => "html",
                    'reply_markup' => $encodedMarkup
                ]);

                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$step_2." ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['step'];
                    }
                }

                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                pg_query($conn, $updateOrderNum);

                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
            } elseif ($text == $ruafterRegister[0][0]) {
                $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 10001 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                pg_query($conn, $sql);

                $getAboutInfo = "SELECT * FROM about_us WHERE status = 1 AND company_id = ".BOT_ID;
                $resultAboutInfo = pg_query($conn, $getAboutInfo);

                $replyMarkup = array(
                    'keyboard' => array(
                        array("🔙 Назад")
                    ),
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);

                if (pg_num_rows($resultAboutInfo) > 0) {
                    while ($row = pg_fetch_assoc($resultAboutInfo)) {
                        $messageFileId = $row['file'];
                        $messageText = $row['description'];
                        $messageType = $row['type'];

                        switch ($messageType) {
                            case 'text':
                                bot('sendMessage', [
                                    'chat_id' => $chat_id,
                                    'text' => $messageText,
                                    'parse_mode' => 'html',
                                    'reply_markup' => $encodedMarkup
                                ]);
                            break;

                            case 'photo':
                                bot('sendPhoto', [
                                    'chat_id' => $chat_id,
                                    'photo' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                    'caption' => $messageText,
                                    'parse_mode' => 'html',
                                    'reply_markup' => $encodedMarkup
                                ]);
                            break;

                            case 'video': 
                                bot('sendVideo', [ 
                                    'chat_id' => $chat_id,
                                    'video' => "https://maxdov.uz/bot/itgo/".$messageFileId,
                                    'caption' => $messageText, 
                                    'parse_mode' => 'html',
                                    'reply_markup' => $encodedMarkup
                                ]);
                            break; 
                        }
                    }
                } else {
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "Bu bo'lim bo'sh!",
                        'reply_markup' => $encodedMarkup
                    ]);
                }
            } elseif ($text == $ruafterRegister[0][1]) {
                $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 10002 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                pg_query($conn, $sql);

                $replyMarkup = array(
                    'keyboard' => array(
                        array("🔙 Назад")
                    ),
                    'resize_keyboard' => true 
                );
                $encodedMarkup = json_encode($replyMarkup);
                
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $discussionText,
                    'reply_markup' => $encodedMarkup
                ]);
            } else {
                if ($text != $ruLanguageArray[1][0] && $text != "/start" && $text != $ruafterRegister[0][0] && $text != $ruafterRegister[0][1]) {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ]);
        }
    }

    // CHECK MESSAGE - USER INFO
    if ($step_2 >= 1 && $step_2 <= 10) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 2) {
                    $table = "fullname";
                    $nameReal = base64_encode($realname);
                    $userName = base64_encode($nameuser);
                    $answer = true;
                } elseif ($step_2 == 3) {
                    $table = "birthday";
                    $answer = true;
                } elseif ($step_2 == 4) {
                    $table = "nationality";
                    $answer = true;
                } elseif ($step_2 == 5) {
                    $table = "birth_place";
                    $answer = true;
                } elseif ($step_2 == 6) {
                    $table = "live_place";
                    $answer = true;
                } elseif ($step_2 == 8) {
                    $table = "phone";
                    $answer = true;
                } elseif ($step_2 == 9) {
                    $table = "mail";
                    $answer = true;
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $answer = false;
                }

                if ($answer == true) {
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);
                    $userText = base64_encode($text);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 1";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            if ($step_2 == 2) {
                                $postMessage = "INSERT INTO userinfo (company_id, chat_id, username, firstname, fullname) VALUES (".BOT_ID.", $chat_id, '$userName', '$nameReal', '$userText')";
                            } else {
                                $postMessage = "INSERT INTO userinfo (company_id, chat_id, $table) VALUES (".BOT_ID.", $chat_id, '$userText')";
                            }
                            $res = pg_query($conn, $postMessage);
                        	$userId = pg_last_oid($res);

                            $updateMessage = "UPDATE inforeg SET status = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            pg_query($conn, $updateMessage);

                            $postInfoReg = "INSERT INTO inforeg (company_id, user_id, chat_id, status) VALUES (".BOT_ID.", $userId, $chat_id, 1)";
                            pg_query($conn, $postInfoReg);
                            
                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        } else {
                            if ($step_2 == 2) {
                                $updateMessage = "UPDATE userinfo SET username = '$userName', firstName = '$nameReal', fullname = '$userText' WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                            } else {
                                $updateMessage = "UPDATE userinfo SET $table = '$userText' WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                            }
                            pg_query($conn, $updateMessage);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);

                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    } else {
                        if ($step_2 == 2) {
                            $postMessage = "INSERT INTO userinfo (company_id, chat_id, username, firstname, fullname) VALUES (".BOT_ID.", $chat_id, '$userName', '$nameReal', '$userText')";
                        } else {
                            $postMessage = "INSERT INTO userinfo (company_id, chat_id, $table) VALUES (".BOT_ID.", $chat_id, '$userText')";
                        }
                        $res = pg_query($conn, $postMessage);
                        $userId = pg_last_oid($res);

                        $postInfoReg = "INSERT INTO inforeg (company_id, user_id, chat_id, status) VALUES (".BOT_ID.", $userId, $chat_id, 1)";
                        pg_query($conn, $postInfoReg);

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $postInfoReg
                        ]);

                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                        $resultInfoStep = pg_query($conn, $getInfoStep);
                        
                        if (pg_num_rows($resultInfoStep) > 0) {
                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                $lastStep = $row['order_step'];
                            }

                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                            pg_query($conn, $updateOrderNum);

                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                        } else {
                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                        }
                    }
                }
            }
        } elseif (isset($update->callback_query)) {
            if ($step_2 == 1) {
                $table = "profession_id";
                $answer = true;
            } elseif ($step_2 == 7) {
                $table = "live_status";
                if ($data == "house") {
                    if ($step_1 == 1) {
                        $userText = base64_encode("Hovli");
                    } else {
                        $userText = base64_encode("Двор");
                    }
                } else {
                    if ($step_1 == 1) {
                        $userText = base64_encode("Ko'p qavatli uy");
                    } else {
                        $userText = base64_encode("Многоэтажный дом");
                    }
                }
                $answer = true;
            } elseif ($step_2 == 10) {
                $table = "marry";
                if ($data == "single") {
                    if ($step_1 == 1) {
                        $userText = base64_encode("Yolg'iz");
                    } else {
                        $userText = base64_encode("В одиночестве");
                    }
                } else {
                    if ($step_1 == 1) {
                        $userText = base64_encode("Oilaliy");
                    } else {
                        $userText = base64_encode("Семья");
                    }
                }
                $answer = true;
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
                $answer = false;
            }

            if ($answer == true) {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }

                    $getUserInfo = "SELECT * FROM userinfo WHERE id = ".$userId." AND company_id = ".BOT_ID." AND chat_id = ".$chat_id." AND is_active = 1";
                    $resultUserInfo = pg_query($conn, $getUserInfo);

                    if (pg_num_rows($resultUserInfo) > 0) {
                        if ($step_2 == 1) {
                            $postMessage = "INSERT INTO userinfo (company_id, $table, chat_id) VALUES (".BOT_ID.", $data, $chat_id)";
                        } else {
                            $postMessage = "INSERT INTO userinfo (company_id, $table, chat_id) VALUES (".BOT_ID.", '$userText', $chat_id)";
                        }
                        $res = pg_query($conn, $postMessage);
                        $userId = pg_last_oid($res);

                        $updateMessage = "UPDATE inforeg SET status = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                        pg_query($conn, $updateMessage);

                        $postInfoReg = "INSERT INTO inforeg (company_id, user_id, chat_id, status) VALUES (".BOT_ID.", $userId, $chat_id, 1)";
                        pg_query($conn, $postInfoReg);

                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);

                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                        $resultInfoStep = pg_query($conn, $getInfoStep);
                        
                        if (pg_num_rows($resultInfoStep) > 0) {
                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                $lastStep = $row['order_step'];
                            }

                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                            pg_query($conn, $updateOrderNum);

                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                        } else {
                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                        }
                    } else {
                        $getUserProfId = "SELECT $table FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserProfId = pg_query($conn, $getUserProfId);

                        $row2 = pg_fetch_assoc($resultUserProfId);
                        $pId = $row2[$table];

                        if ($pId == NULL || $pId == 0) {
                            if ($step_2 == 1) {
                                $updateMessage = "UPDATE userinfo SET $table = $data WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                            } else {
                                $updateMessage = "UPDATE userinfo SET $table = '$userText' WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                            }
                            pg_query($conn, $updateMessage);

                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    }
                } else {
                    if ($step_2 == 1) {
                        $postMessage = "INSERT INTO userinfo (company_id, $table, chat_id) VALUES (".BOT_ID.", $data, $chat_id) ";
                    } else {
                        $postMessage = "INSERT INTO userinfo (company_id, $table, chat_id) VALUES (".BOT_ID.", '$userText', $chat_id)";
                    }
                    $res = pg_query($conn, $postMessage);
                    $userId = pg_last_oid($res);

                    $postInfoReg = "INSERT INTO inforeg (company_id, user_id, chat_id, status) VALUES (".BOT_ID.", $userId, $chat_id, 1)";
                    pg_query($conn, $postInfoReg);

                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $postInfoReg
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                }
            }
        } elseif (isset($message->contact)) {
            if ($step_2 == 8) {
                $table = "phone";
            }

            $contact = $message->contact->phone_number;
            
            $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultInfoReg = pg_query($conn, $getInfoReg);

            if (pg_num_rows($resultInfoReg) > 0) {
                while ($row = pg_fetch_assoc($resultInfoReg)) {
                    $userId = $row['user_id'];
                }

                $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 1";
                $resultUserInfo = pg_query($conn, $getUserInfo);

                if (pg_num_rows($resultUserInfo) > 0) {
                    $postMessage = "INSERT INTO userinfo (company_id, chat_id, $table) VALUES (".BOT_ID.", $chat_id, '$contact')";
                    $res = pg_query($conn, $postMessage);
                    $userId = pg_last_oid($res);

                    $updateMessage = "UPDATE inforeg SET status = 0 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                    pg_query($conn, $updateMessage);

                    $postInfoReg = "INSERT INTO inforeg (company_id, user_id, chat_id, status) VALUES (".BOT_ID.", $userId, $chat_id, 1)";
                    pg_query($conn, $postInfoReg);
                    
                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                } else {
                    $getUserProfId = "SELECT $table FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                    $resultUserProfId = pg_query($conn, $getUserProfId);

                    $row2 = pg_fetch_assoc($resultUserProfId);
                    $phone = $row2[$table];

                    if ($phone == NULL) {
                        $updateMessage = "UPDATE userinfo SET $table = '$contact' WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        pg_query($conn, $updateMessage);

                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                        $resultInfoStep = pg_query($conn, $getInfoStep);
                        
                        if (pg_num_rows($resultInfoStep) > 0) {
                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                $lastStep = $row['order_step'];
                            }

                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                            pg_query($conn, $updateOrderNum);

                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                        } else {
                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                        }
                    }
                }
            } else {
                $postMessage = "INSERT INTO userinfo (company_id, chat_id, $table) VALUES (".BOT_ID.", $chat_id, '$contact')";
                $res = pg_query($conn, $postMessage);
                $userId = pg_last_oid($res);

                $postInfoReg = "INSERT INTO inforeg (company_id, user_id, chat_id, status) VALUES (".BOT_ID.", $userId, $chat_id, 1)";
                pg_query($conn, $postInfoReg);

                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['order_step'];
                    }

                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                    pg_query($conn, $updateOrderNum);

                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                } else {
                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    die();
    // ====================================================================== CHECK MESSAGE - USER STUDY
    if ($step_2 >= 11 && $step_2 <= 14) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 12) {
                    $table = "study_name";
                    $answer = true;
                } elseif ($step_2 == 13) {
                    $table = "study_year";
                    $answer = true;
                } elseif ($step_2 == 14) {
                    $table = "study_field";
                    $answer = true;
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $answer = false;
                }

                if ($answer == true) {
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);
                    $userText = base64_encode($text);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND type = 1 AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userLId = $row4['last_id'];
                                }
                                $getUserStudy = "SELECT * FROM user_study WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                $resultUserStudy = pg_query($conn, $getUserStudy);

                                if (pg_num_rows($resultUserStudy) > 0) {
                                    while ($row2 = pg_fetch_assoc($resultUserStudy)) {
                                        $uField = $row2[$table];
                                    }

                                    if ($uField == NULL) {
                                        $updateMessage = "UPDATE user_study SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    } else {
                                        $postMessage = "INSERT INTO user_study (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                        $res = pg_query($conn, $postMessage);
                        				$userLId = pg_last_oid($res);

                                        $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 1 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_study (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $res = pg_query($conn, $postMessage);
                        		$userLId = pg_last_oid($res);

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 1, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } elseif (isset($update->callback_query)) {
            if ($step_1 == 1) {
                if ($data == "down") { 
                    $userText = base64_encode("O'rta ta'lim");
                } elseif ($data == "middle") {
                    $userText = base64_encode("O'rta maxsus ta'lim");
                } else {
                    $userText = base64_encode("Oliy ta'lim");
                }
            } else {
                if ($data == "down") { 
                    $userText = base64_encode("Среднее образование");
                } elseif ($data == "middle") {
                    $userText = base64_encode("Среднее специальное образование");
                } else {
                    $userText = base64_encode("Высшее образование");
                }
            }

            $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultInfoReg = pg_query($conn, $getInfoReg);

            if (pg_num_rows($resultInfoReg) > 0) {
                while ($row = pg_fetch_assoc($resultInfoReg)) {
                    $userId = $row['user_id'];
                }

                $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                $resultUserInfo = pg_query($conn, $getUserInfo);

                if (pg_num_rows($resultUserInfo) > 0) {
                    $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND type = 1 AND company_id = ".BOT_ID;
                    $resultUserLog = pg_query($conn, $getUserLog);

                    if (pg_num_rows($resultUserLog) > 0) {
                        while ($row4 = pg_fetch_assoc($resultUserLog)) {
                            $userLId = $row4['last_id'];
                        }
                        $getUserStudy = "SELECT * FROM user_study WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                        $resultUserStudy = pg_query($conn, $getUserStudy);

                        if (pg_num_rows($resultUserStudy) > 0) {
                            while ($row2 = pg_fetch_assoc($resultUserStudy)) {
                                $uGrade = $row2['study_grade'];
                            }

                            if ($uGrade == NULL) {
                                $updateMessage = "UPDATE user_study SET study_grade = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                pg_query($conn, $updateMessage);

                                bot('deleteMessage', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id
                                ]);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            } else {
                                $postMessage = "INSERT INTO user_study (company_id, user_id, study_grade) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 1 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                pg_query($conn, $updateMessage);

                                bot('deleteMessage', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id
                                ]);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    } else {
                        $postMessage = "INSERT INTO user_study (company_id, user_id, study_grade) VALUES (".BOT_ID.", $userId, '$userText')";
                        pg_query($conn, $postMessage);
                        $result = pg_query($conn, $postMessage);
                        $insert_row = pg_fetch_row($result);
                        $userLId = $insert_row[0];

                        $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 1, $chat_id)";
                        pg_query($conn, $postMessage2);

                        bot('deleteMessage', [
                            'chat_id' => $chat_id,
                            'message_id' => $message_id
                        ]);

                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                        $resultInfoStep = pg_query($conn, $getInfoStep);
                        
                        if (pg_num_rows($resultInfoStep) > 0) {
                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                $lastStep = $row['order_step'];
                            }

                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                            pg_query($conn, $updateOrderNum);

                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                        } else {
                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // CHECK MESSAGE - USER AGAIN STUDY OR NEXT
    if ($step_2 == 15) {
        if (isset($text)) {
            switch ($text) {
                case $addToo[0][0]:
                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 11 AND step < 15 ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
                case $addToo[1][0]:
                    $replyMarkup = array(
                        'keyboard' => $cancelArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $afterQuesPart,
                        'reply_markup' => $encodedMarkup
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
            }
        }
    }
    
    // ====================================================================== CHECK MESSAGE - USER WORK
    if ($step_2 >= 16 && $step_2 <= 20) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 17) {
                    $table = "work_place";
                    $answer = true;
                } elseif ($step_2 == 18) {
                    $table = "work_year";
                    $answer = true;
                } elseif ($step_2 == 19) {
                    $table = "work_pos";
                    $answer = true;
                } elseif ($step_2 == 20) {
                    $table = "work_out";
                    $answer = true;
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $answer = false;
                }

                if ($answer == true) {
                    $userText = base64_encode($text);
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);
                    
                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 2) {
                                    $getUserWork = "SELECT * FROM user_work WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wLeft = $row2[$table];
                                        }

                                        if ($wLeft == NULL) {
                                            $updateMessage = "UPDATE user_work SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO user_work (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $res = pg_query($conn, $postMessage);
                        					$userLId = pg_last_oid($res);

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 2 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_work (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $res = pg_query($conn, $postMessage);
                        			$userLId = pg_last_oid($res);

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 2 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_work (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $res = pg_query($conn, $postMessage);
                        		$userLId = pg_last_oid($res);

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 2, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } elseif (isset($update->callback_query)) {
            if ($data == "yes_work") {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }

                    $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                    $resultUserInfo = pg_query($conn, $getUserInfo);

                    if (pg_num_rows($resultUserInfo) > 0) {
                        $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                        $resultUserLog = pg_query($conn, $getUserLog);

                        if (pg_num_rows($resultUserLog) > 0) {
                            while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                $userTId = $row4['type'];
                                $userLId = $row4['last_id'];
                            }
                            if ($userTId == 2) {
                                $getUserWork = "SELECT * FROM user_work WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                $resultUserStudy = pg_query($conn, $getUserStudy);

                                if (pg_num_rows($resultUserStudy) > 0) {
                                    while ($row2 = pg_fetch_assoc($resultUserStudy)) {
                                        $wUser = $row2['work'];
                                    }

                                    if ($wUser == NULL) {
                                        $updateMessage = "UPDATE user_work SET work = 1 WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        bot('deleteMessage', [
                                            'chat_id' => $chat_id,
                                            'message_id' => $message_id
                                        ]);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    } else {
                                        $postMessage = "INSERT INTO user_work (company_id, user_id, work) VALUES (".BOT_ID.", $userId, 1)";
                                        $result = pg_query($conn, $postMessage);
                                        $insert_row = pg_fetch_row($result);
                                        $userLId = $insert_row[0];

                                        $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 2 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        bot('deleteMessage', [
                                            'chat_id' => $chat_id,
                                            'message_id' => $message_id
                                        ]);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_work (company_id, user_id, work) VALUES (".BOT_ID.", $userId, 1)";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 2 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                pg_query($conn, $updateMessage);

                                bot('deleteMessage', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id
                                ]);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        } else {
                            $postMessage = "INSERT INTO user_work (company_id, user_id, work) VALUES (".BOT_ID.", $userId, 1)";
                            pg_query($conn, $postMessage);
                            $result = pg_query($conn, $postMessage);
                            $insert_row = pg_fetch_row($result);
                            $userLId = $insert_row[0];

                            $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 2, $chat_id)";
                            pg_query($conn, $postMessage2);

                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    }
                }
            }
            if ($data == "no_work") {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id, 
                ]);
                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 21 ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['order_step'];
                    }

                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                    pg_query($conn, $updateOrderNum);

                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                } else {
                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // CHECK MESSAGE - USER AGAIN WORK OR NEXT
    if ($step_2 == 21) {
        if (isset($text)) {
            switch ($text) {
                case $addToo[0][0]:
                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 16 AND step < 21 ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
                case $addToo[1][0]:
                    $replyMarkup = array(
                        'keyboard' => $cancelArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $afterQuesPart,
                        'reply_markup' => $encodedMarkup
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id 
            ]);
        }
    }

    // ====================================================================== CHECK MESSAGE - USER TRIP
    if ($step_2 >= 22 && $step_2 <= 25) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 23) {
                    $table = "trip_place";
                    $answer = true;
                } elseif ($step_2 == 24) {
                    $table = "trip_year";
                    $answer = true;
                } elseif ($step_2 == 25) {
                    $table = "trip_reason";
                    $answer = true;
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $answer = false;
                }

                if ($answer == true) {
                    $userText = base64_encode($text);
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 3) {
                                    $getUserWork = "SELECT * FROM user_trip WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wPlace = $row2[$table];
                                        }

                                        if ($wPlace == NULL) {
                                            $updateMessage = "UPDATE user_trip SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO user_trip (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $result = pg_query($conn, $postMessage);
                                            $insert_row = pg_fetch_row($result);
                                            $userLId = $insert_row[0];

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 3 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_trip (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 3 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_trip (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 3, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } elseif (isset($update->callback_query)) {
            if ($data == "yes_trip") {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }

                    $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                    $resultUserInfo = pg_query($conn, $getUserInfo);

                    if (pg_num_rows($resultUserInfo) > 0) {
                        $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                        $resultUserLog = pg_query($conn, $getUserLog);

                        if (pg_num_rows($resultUserLog) > 0) {
                            while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                $userTId = $row4['type'];
                                $userLId = $row4['last_id'];
                            }
                            if ($userTId == 3) {
                                $getUserWork = "SELECT * FROM user_trip WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                $resultUserStudy = pg_query($conn, $getUserStudy);

                                if (pg_num_rows($resultUserStudy) > 0) {
                                    while ($row2 = pg_fetch_assoc($resultUserStudy)) {
                                        $wUser = $row2['trip_status'];
                                    }

                                    if ($wUser == NULL) {
                                        $updateMessage = "UPDATE user_trip SET trip_status = 1 WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        bot('deleteMessage', [
                                            'chat_id' => $chat_id,
                                            'message_id' => $message_id
                                        ]);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    } else {
                                        $postMessage = "INSERT INTO user_trip (company_id, user_id, trip_status) VALUES (".BOT_ID.", $userId, 1)";
                                        $result = pg_query($conn, $postMessage);
                                        $insert_row = pg_fetch_row($result);
                                        $userLId = $insert_row[0];

                                        $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 3 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        bot('deleteMessage', [
                                            'chat_id' => $chat_id,
                                            'message_id' => $message_id
                                        ]);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_trip (company_id, user_id, trip_status) VALUES (".BOT_ID.", $userId, 1)";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 3 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                pg_query($conn, $updateMessage);

                                bot('deleteMessage', [
                                    'chat_id' => $chat_id,
                                    'message_id' => $message_id
                                ]);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        } else {
                            $postMessage = "INSERT INTO user_trip (company_id, user_id, trip_status) VALUES (".BOT_ID.", $userId, 1)";
                            $result = pg_query($conn, $postMessage);
                            $insert_row = pg_fetch_row($result);
                            $userLId = $insert_row[0];

                            $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 3, $chat_id)";
                            pg_query($conn, $postMessage2);

                            bot('deleteMessage', [
                                'chat_id' => $chat_id,
                                'message_id' => $message_id
                            ]);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > $orderId ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    }
                }
            }
            if ($data == "no_trip") {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id, 
                ]);
                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 26 ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['order_step'];
                    }

                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                    pg_query($conn, $updateOrderNum);

                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                } else {
                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // CHECK MESSAGE - USER AGAIN TRIP OR NEXT
    if ($step_2 == 26) {
        if (isset($text)) {
            switch ($text) {
                case $addToo[0][0]:
                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 22 AND step < 26 ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
                case $addToo[1][0]:
                    $replyMarkup = array(
                        'keyboard' => $cancelArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $afterQuesPart,
                        'reply_markup' => $encodedMarkup
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id 
            ]);
        }
    }

    // ====================================================================== CHECK MESSAGE - USER FAMILY
    if ($step_2 >= 27 && $step_2 <= 33) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 27) {
                    $table = "family_member";
                    $answer = true;
                } elseif ($step_2 == 28) {
                    $table = "member_name";
                    $answer = true;
                } elseif ($step_2 == 29) {
                    $table = "member_birth";
                    $answer = true;
                } elseif ($step_2 == 30) {
                    $table = "member_work";
                    $answer = true;
                } elseif ($step_2 == 31) {
                    $table = "member_phone";
                    $answer = true;
                } elseif ($step_2 == 32) {
                    $table = "member_live";
                    $answer = true;
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $answer = false;
                }

                if ($answer == true) {
                    $userText = base64_encode($text);
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 4) {
                                    $getUserWork = "SELECT * FROM user_family WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wPlace = $row2[$table];
                                        }

                                        if ($wPlace == NULL) {
                                            $updateMessage = "UPDATE user_family SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO user_family (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $result = pg_query($conn, $postMessage);
                                            $insert_row = pg_fetch_row($result);
                                            $userLId = $insert_row[0];

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 4 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_family (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 4 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_family (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 4, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } elseif (isset($update->callback_query)) {
            if ($data == "yes_court") {
                $userText = base64_encode("Sudlangan");
            } else {
                $userText = base64_encode("Sudlanmagan");
            }

            $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultInfoReg = pg_query($conn, $getInfoReg);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);

            if (pg_num_rows($resultInfoReg) > 0) {
                while ($row = pg_fetch_assoc($resultInfoReg)) {
                    $userId = $row['user_id'];
                }

                $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                $resultUserInfo = pg_query($conn, $getUserInfo);

                if (pg_num_rows($resultUserInfo) > 0) {
                    $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultUserLog = pg_query($conn, $getUserLog);

                    if (pg_num_rows($resultUserLog) > 0) {
                        while ($row4 = pg_fetch_assoc($resultUserLog)) {
                            $userTId = $row4['type'];
                            $userLId = $row4['last_id'];
                        }
                        if ($userTId == 4) {
                            $getUserWork = "SELECT * FROM user_family WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                            $resultUserWork = pg_query($conn, $getUserWork);

                            if (pg_num_rows($resultUserWork) > 0) {
                                while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                    $wPlace = $row2['member_court'];
                                }

                                if ($wPlace == NULL) {
                                    $updateMessage = "UPDATE user_family SET member_court = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_family (company_id, user_id, member_court) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 4 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            }
                        } else {
                            $postMessage = "INSERT INTO user_family (company_id, user_id, member_court) VALUES (".BOT_ID.", $userId, '$userText')";
                            $result = pg_query($conn, $postMessage);
                            $insert_row = pg_fetch_row($result);
                            $userLId = $insert_row[0];

                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 4 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            pg_query($conn, $updateMessage);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    } else {
                        $postMessage = "INSERT INTO user_family (company_id, user_id, member_court) VALUES (".BOT_ID.", $userId, '$userText')";
                        $result = pg_query($conn, $postMessage);
                        $insert_row = pg_fetch_row($result);
                        $userLId = $insert_row[0];

                        $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 4, $chat_id)";
                        pg_query($conn, $postMessage2);

                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                        $resultInfoStep = pg_query($conn, $getInfoStep);
                        
                        if (pg_num_rows($resultInfoStep) > 0) {
                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                $lastStep = $row['order_step'];
                            }

                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                            pg_query($conn, $updateOrderNum);

                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                        } else {
                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // CHECK MESSAGE - USER AGAIN TRIP OR NEXT
    if ($step_2 == 34) {
        if (isset($text)) {
            switch ($text) {
                case $addToo[0][0]:
                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 26 AND step < 34 ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
                case $addToo[1][0]:
                    $replyMarkup = array(
                        'keyboard' => $cancelArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $afterQuesPart,
                        'reply_markup' => $encodedMarkup
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id 
            ]);
        }
    }

    // ====================================================================== CHECK MESSAGE - USER ADD
    if (($step_2 >= 35 && $step_2 <= 40) && $step_2 != 38) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 35) {
                    $table = "work_trip";
                    $answer = true;
                } elseif ($step_2 == 36) {
                    $table = "user_army";
                    $answer = true;
                } elseif ($step_2 == 37) {
                    $table = "user_court";
                    $answer = true;
                } elseif ($step_2 == 39) {
                    $table = "car";
                    $answer = true;
                } else {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    $answer = false;
                }

                if ($answer == true) {
                    $userText = base64_encode($text);
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 5) {
                                    $getUserWork = "SELECT * FROM user_add WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wPlace = $row2[$table];
                                        }

                                        if ($wPlace == NULL) {
                                            $updateMessage = "UPDATE user_add SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO user_add (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $result = pg_query($conn, $postMessage);
                                            $insert_row = pg_fetch_row($result);
                                            $userLId = $insert_row[0];

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 5 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_add (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 5 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_add (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 5, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } elseif (isset($update->callback_query)) {
            if ($data == "grade_a") {
                $userText = "A";
            } elseif ($data == "grade_b") {
                $userText = "B";
            } elseif ($data == "grade_c") {
                $userText = "C";
            } else {
                $userText = "D";
            }

            $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
            $resultInfoReg = pg_query($conn, $getInfoReg);

            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id
            ]);

            if (pg_num_rows($resultInfoReg) > 0) {
                while ($row = pg_fetch_assoc($resultInfoReg)) {
                    $userId = $row['user_id'];
                }

                $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                $resultUserInfo = pg_query($conn, $getUserInfo);

                if (pg_num_rows($resultUserInfo) > 0) {
                    $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultUserLog = pg_query($conn, $getUserLog);

                    if (pg_num_rows($resultUserLog) > 0) {
                        while ($row4 = pg_fetch_assoc($resultUserLog)) {
                            $userTId = $row4['type'];
                            $userLId = $row4['last_id'];
                        }
                        if ($userTId == 5) {
                            $getUserWork = "SELECT * FROM user_add WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                            $resultUserWork = pg_query($conn, $getUserWork);

                            if (pg_num_rows($resultUserWork) > 0) {
                                while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                    $wPlace = $row2['car_grade'];
                                }

                                if ($wPlace == NULL) {
                                    $updateMessage = "UPDATE user_add SET car_grade = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_add (company_id, user_id, car_grade) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 5 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            }
                        } else {
                            $postMessage = "INSERT INTO user_add (company_id, user_id, car_grade) VALUES (".BOT_ID.", $userId, '$userText')";
                            $result = pg_query($conn, $postMessage);
                            $insert_row = pg_fetch_row($result);
                            $userLId = $insert_row[0];

                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 5 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            pg_query($conn, $updateMessage);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    } else {
                        $postMessage = "INSERT INTO user_add (company_id, user_id, car_grade) VALUES (".BOT_ID.", $userId, '$userText')";
                        $result = pg_query($conn, $postMessage);
                        $insert_row = pg_fetch_row($result);
                        $userLId = $insert_row[0];

                        $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 5, $chat_id)";
                        pg_query($conn, $postMessage2);

                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                        $resultInfoStep = pg_query($conn, $getInfoStep);
                        
                        if (pg_num_rows($resultInfoStep) > 0) {
                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                $lastStep = $row['order_step'];
                            }

                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                            pg_query($conn, $updateOrderNum);

                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                        } else {
                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // // CHECK MESSAGE - USER NEW WORK CAR LYCEN
    if ($step_2 == 38) {
        if (isset($update->callback_query)) {
            if ($data == "cardrive_yes") {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['order_step'];
                    }

                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                    pg_query($conn, $updateOrderNum);

                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                } else {
                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                }
            }
            if ($data == "cardrive_no") {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step > 40 ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['order_step'];
                    }

                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                    pg_query($conn, $updateOrderNum);

                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                } else {
                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0]) {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // ====================================================================== CHECK MESSAGE - USER lANGUAGE
    if ($step_2 >= 41 && $step_2 <= 44) {
        if (isset($update->callback_query)) {
            if ($step_2 == 41) {
                $table = "language";
                $answer = true;
            } elseif ($step_2 == 42) {
                $table = "user_speak";
                $answer = true;
            } elseif ($step_2 == 43) {
                $table = "user_write";
                $answer = true;
            } else {
                $table = "user_read";
                $answer = true;
            }

            if ($answer == true) {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }

                    $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                    $resultUserInfo = pg_query($conn, $getUserInfo);

                    if (pg_num_rows($resultUserInfo) > 0) {
                        $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                        $resultUserLog = pg_query($conn, $getUserLog);

                        if (pg_num_rows($resultUserLog) > 0) {
                            while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                $userTId = $row4['type'];
                                $userLId = $row4['last_id'];
                            }
                            if ($userTId == 6) {
                                $getUserWork = "SELECT * FROM user_lang WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                $resultUserWork = pg_query($conn, $getUserWork);

                                if (pg_num_rows($resultUserWork) > 0) {
                                    while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                        $wPlace = $row2[$table];
                                    }

                                    if ($wPlace == NULL) {
                                        $updateMessage = "UPDATE user_lang SET $table = $data WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    } else {
                                        $postMessage = "INSERT INTO user_lang (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, $data)";
                                        $result = pg_query($conn, $postMessage);
                                        $insert_row = pg_fetch_row($result);
                                        $userLId = $insert_row[0];

                                        $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 6 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                        pg_query($conn, $updateMessage);

                                        $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                        $resultInfoStep = pg_query($conn, $getInfoStep);
                                        
                                        if (pg_num_rows($resultInfoStep) > 0) {
                                            while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                $lastStep = $row['order_step'];
                                            }

                                            $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                            pg_query($conn, $updateOrderNum);

                                            sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                        } else {
                                            sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                        }
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_lang (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, $data)";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 6 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                pg_query($conn, $updateMessage);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        } else {
                            $postMessage = "INSERT INTO user_lang (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, $data)";
                            $result = pg_query($conn, $postMessage);
                            $insert_row = pg_fetch_row($result);
                            $userLId = $insert_row[0];

                            $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 6, $chat_id)";
                            pg_query($conn, $postMessage2);

                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                            $resultInfoStep = pg_query($conn, $getInfoStep);
                            
                            if (pg_num_rows($resultInfoStep) > 0) {
                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                    $lastStep = $row['order_step'];
                                }

                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                pg_query($conn, $updateOrderNum);

                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                            } else {
                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // CHECK MESSAGE - USER AGAIN LANGUAGE OR NEXT
    if ($step_2 == 45) {
        if (isset($text)) {
            switch ($text) {
                case $addToo[0][0]:
                    $replyMarkup = array(
                        'keyboard' => $cancelArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $againText,
                        'reply_markup' => $encodedMarkup
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND step = 41 ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
                case $addToo[1][0]:
                    $replyMarkup = array(
                        'keyboard' => $cancelArray,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $afterQuesPart,
                        'reply_markup' => $encodedMarkup
                    ]);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                    }
                break;
            }
        } else {
            bot('deleteMessage', [
                'chat_id' => $chat_id,
                'message_id' => $message_id 
            ]);
        }
    }

    // ====================================================================== CHECK MESSAGE - USER PROGRAMM
    if ($step_2 == 46) {
        if (isset($update->callback_query)) {
            if ($data == "nextto") {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                ]);

                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                $resultInfoStep = pg_query($conn, $getInfoStep);
                
                if (pg_num_rows($resultInfoStep) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                        $lastStep = $row['order_step'];
                    }

                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                    pg_query($conn, $updateOrderNum);

                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                } else {
                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                }
            } else {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }
                }

                $getSelectedProg = "SELECT * FROM user_prog WHERE program_id = $data AND user_id = $userId AND company_id = ".BOT_ID;
                $resultSelectedProg = pg_query($conn, $getSelectedProg);
                
                if (pg_num_rows($resultSelectedProg) > 0) {
                    $deleteMessage = "DELETE FROM user_prog WHERE user_id = $userId AND program_id = $data AND company_id = ".BOT_ID;
                    pg_query($conn, $deleteMessage);
                } else {
                    $postMessage = "INSERT INTO user_prog (company_id, user_id, program_id) VALUES (".BOT_ID.", $userId, $data)";
                    pg_query($conn, $postMessage);
                }
                
                $getAllProg = "SELECT * FROM company_programm AS cp INNER JOIN programm as p ON p.id = cp.programm_id LEFT JOIN user_prog AS up ON cp.company_id = up.company_id and cp.programm_id = up.program_id and up.user_id = $userId WHERE cp.status = 1";
                $resultProg = pg_query($conn, $getAllProg);
                
                if (pg_num_rows($resultProg) > 0) {
                    $i = 1;
                    $menu = [];
                    $arrCat = [];
                    while ($row4 = pg_fetch_assoc($resultProg)) {
                        $progamId = $row4['program_id'];
                        $progammId = $row4['programm_id'];
                        $progTit = $row4['title'];

                        if ($progamId != NULL) {
                            $progTit = $progTit." ☑️";
                        }

                        $menu[] = ["text" => "$progTit", "callback_data" => "$progammId"];

                        if($i % 2 == 0) {
                            $arrCat[] = $menu;
                            $menu = [];
                        }

                        $i++;
                    }
                    if(count($menu) == 1) {
                        $arrCat[] = $menu;
                    }
                    $arrCat[] = [['callback_data' => "nextto", 'text'=> "$nextQuesProg"]];

                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => "<b>Qaysi dasturlarni bilasiz?</b>",
                        'parse_mode' => 'html',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => $arrCat
                        ])
                    ]);
                }
            }
        }
    }

    // ====================================================================== CHECK MESSAGE - USER OURWORK
    if ($step_2 >= 47 && $step_2 <= 50) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 47) {
                    $table = "about_us";
                    $answer = true;
                } elseif ($step_2 == 48) {
                    $table = "human_name";
                    $answer = true;
                } elseif ($step_2 == 49) {
                    $table = "human_work";
                    $answer = true;
                } else {
                    $table = "human_phone";
                    $answer = true;
                }

                if ($answer == true) {
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);
                    $userText = base64_encode($text);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 7) {
                                    $getUserWork = "SELECT * FROM ourwork WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wPlace = $row2[$table];
                                        }

                                        if ($wPlace == NULL) {
                                            $updateMessage = "UPDATE ourwork SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO ourwork (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $result = pg_query($conn, $postMessage);
                                            $insert_row = pg_fetch_row($result);
                                            $userLId = $insert_row[0];

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 7 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO ourwork (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 7 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO ourwork (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 7, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // ====================================================================== CHECK MESSAGE - USER ASK QUESTIONS FOR ASK TABLE
    if ($step_2 >= 51 && $step_2 <= 57) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 51) {
                    $table = "new_salary";
                    $answer = true;
                } elseif ($step_2 == 52) {
                    $table = "work_year";
                    $answer = true;
                } elseif ($step_2 == 53) {
                    $table = "after_work";
                    $answer = true;
                } elseif ($step_2 == 54) {
                    $table = "meet_work";
                    $answer = true;
                } elseif ($step_2 == 55) {
                    $table = "collectiv_work";
                    $answer = true;
                } elseif ($step_2 == 56) {
                    $table = "meet_parent";
                    $answer = true;
                } else {
                    $table = "healthy";
                    $answer = true;
                }

                if ($answer == true) {
                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);
                    $userText = base64_encode($text);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 8) {
                                    $getUserWork = "SELECT * FROM user_ask WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wPlace = $row2[$table];
                                        }

                                        if ($wPlace == NULL) {
                                            $updateMessage = "UPDATE user_ask SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO user_ask (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $result = pg_query($conn, $postMessage);
                                            $insert_row = pg_fetch_row($result);
                                            $userLId = $insert_row[0];

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 8 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO user_ask (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];

                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 8 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO user_ask (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 8, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // ====================================================================== CHECK MESSAGE - USER INFO LAST FOR USERINFO_LAST TABLE
    if ($step_2 >= 58 && $step_2 <= 59) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                if ($step_2 == 58) {
                    $table = "goods";
                    $answer = true;
                } else {
                    $table = "bads";
                    $answer = true;
                }

                if ($answer == true) {
                    $userText = base64_encode($text);

                    $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                    $resultInfoReg = pg_query($conn, $getInfoReg);

                    if (pg_num_rows($resultInfoReg) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoReg)) {
                            $userId = $row['user_id'];
                        }

                        $getUserInfo = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id AND is_active = 0";
                        $resultUserInfo = pg_query($conn, $getUserInfo);

                        if (pg_num_rows($resultUserInfo) > 0) {
                            $getUserLog = "SELECT * FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                            $resultUserLog = pg_query($conn, $getUserLog);

                            if (pg_num_rows($resultUserLog) > 0) {
                                while ($row4 = pg_fetch_assoc($resultUserLog)) {
                                    $userTId = $row4['type'];
                                    $userLId = $row4['last_id'];
                                }
                                if ($userTId == 9) {
                                    $getUserWork = "SELECT * FROM userinfo_last WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                    $resultUserWork = pg_query($conn, $getUserWork);

                                    if (pg_num_rows($resultUserWork) > 0) {
                                        while ($row2 = pg_fetch_assoc($resultUserWork)) {
                                            $wPlace = $row2[$table];
                                        }

                                        if ($wPlace == NULL) {
                                            $updateMessage = "UPDATE userinfo_last SET $table = '$userText' WHERE id = $userLId AND user_id = $userId AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        } else {
                                            $postMessage = "INSERT INTO userinfo_last (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                            $result = pg_query($conn, $postMessage);
                                            $insert_row = pg_fetch_row($result);
                                            $userLId = $insert_row[0];

                                            $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 9 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                            pg_query($conn, $updateMessage);

                                            $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                            $resultInfoStep = pg_query($conn, $getInfoStep);
                                            
                                            if (pg_num_rows($resultInfoStep) > 0) {
                                                while ($row = pg_fetch_assoc($resultInfoStep)) {
                                                    $lastStep = $row['order_step'];
                                                }

                                                $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                                pg_query($conn, $updateOrderNum);

                                                sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                            } else {
                                                sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                            }
                                        }
                                    }
                                } else {
                                    $postMessage = "INSERT INTO userinfo_last (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                    $result = pg_query($conn, $postMessage);
                                    $insert_row = pg_fetch_row($result);
                                    $userLId = $insert_row[0];
                                    
                                    $updateMessage = "UPDATE userlog SET last_id = $userLId, type = 9 WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                                    pg_query($conn, $updateMessage);

                                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                    $resultInfoStep = pg_query($conn, $getInfoStep);
                                    
                                    if (pg_num_rows($resultInfoStep) > 0) {
                                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                                            $lastStep = $row['order_step'];
                                        }

                                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                        pg_query($conn, $updateOrderNum);

                                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                    } else {
                                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                    }
                                }
                            } else {
                                $postMessage = "INSERT INTO userinfo_last (company_id, user_id, $table) VALUES (".BOT_ID.", $userId, '$userText')";
                                $result = pg_query($conn, $postMessage);
                                $insert_row = pg_fetch_row($result);
                                $userLId = $insert_row[0];

                                $postMessage2 = "INSERT INTO userlog (company_id, last_id, type, chat_id) VALUES (".BOT_ID.", $userLId, 9, $chat_id)";
                                pg_query($conn, $postMessage2);

                                $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                                $resultInfoStep = pg_query($conn, $getInfoStep);
                                
                                if (pg_num_rows($resultInfoStep) > 0) {
                                    while ($row = pg_fetch_assoc($resultInfoStep)) {
                                        $lastStep = $row['order_step'];
                                    }

                                    $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                                    pg_query($conn, $updateOrderNum);

                                    sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                                } else {
                                    sendMessageEnd($step_1, $userId, $chat_id, $conn);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($text)) {
                if ($text != $cancelArray[0][0] && $text != "/start") {
                    bot('deleteMessage', [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                }
            } else {
                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    // ====================================================================== CHECK MESSAGE - USER OUT OF STANDART QUESTIONS
    if ($step_2 >= 60 && $step_2 < 10000) {
        if (isset($text)) {
            if ($text != $documentArray[0][0] && $text != $documentArray[0][1] && $text != $cancelArray[0][0] && $text != "/start") {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);
                $userText = base64_encode($text);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }

                    $postMessage = "INSERT INTO additional_aq (company_id, user_id, step_id, answer) VALUES (".BOT_ID.", $userId, $step_2, '$userText')";
                    pg_query($conn, $postMessage);

                    $getInfoStep = "SELECT * FROM company_settings WHERE status = 1 AND company_id = ".BOT_ID." AND order_step > ".$orderId." ORDER BY order_step ASC LIMIT 1";
                    $resultInfoStep = pg_query($conn, $getInfoStep);
                    
                    if (pg_num_rows($resultInfoStep) > 0) {
                        while ($row = pg_fetch_assoc($resultInfoStep)) {
                            $lastStep = $row['order_step'];
                        }

                        $updateOrderNum = "UPDATE actionreg SET order_num = $lastStep WHERE company_id = ".BOT_ID." AND chat_id = ".$chat_id;
                        pg_query($conn, $updateOrderNum);

                        sendMessageNext($step_1, $lastStep, $chat_id, $conn);
                    } else {
                        sendMessageEnd($step_1, $userId, $chat_id, $conn);
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

    // ====================================================================== CHECK MESSAGE - ACCEPT OR NOT ACCEPT
    if ($step_2 == 10000) {
        if (isset($update->callback_query)) {
            if ($data == "yes_good") {
                $getInfoReg = "SELECT * FROM inforeg WHERE status = 1 AND chat_id = $chat_id AND company_id = ".BOT_ID;
                $resultInfoReg = pg_query($conn, $getInfoReg);

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                if (pg_num_rows($resultInfoReg) > 0) {
                    while ($row = pg_fetch_assoc($resultInfoReg)) {
                        $userId = $row['user_id'];
                    }
                    $getUserInfos = "SELECT * FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID." AND chat_id = $chat_id";
                    $resultUserInfos = pg_query($conn, $getUserInfos);

                    if (pg_num_rows($resultUserInfos) > 0) {
                        while ($row2 = pg_fetch_assoc($resultUserInfos)) {
                            $userFull = base64_decode($row2['fullname']);
                            $userPhone = $row2['phone'];
                            if ($row2['mail'] != NULL) {
                                $userName = base64_decode($row2['mail']);
                            } else {
                                if ($row2['username'] != NULL) {
                                    $userName = base64_decode($row2['username']);
                                } else {
                                    $userName = "Mavjud emas!";
                                }
                            }
                            $userBirth = base64_decode($row2['birthday']);
                        }
                        
                        $getSendId = "SELECT send_id FROM company WHERE is_active = 1 AND id = ".BOT_ID;
                        $resultSendId = pg_query($conn, $getSendId);

                        $row3 = pg_fetch_assoc($resultSendId);
                        $sendId = $row3['send_id'];

                        $_file_name = strtotime("Y_m-d H:i:s").rand(1000, 9999);
                        $doc_name = "$chat_id"."$_file_name";

                        if ($step_1 == 1) {
                            $groupText = "<b>Yangi hodimlikka namzod o'z ma'lumotlarini yubordi</b>\n\n<b>F-I-SH: </b><i>$userFull</i>\n<b>Username: </b><i>$userName</i>\n<b>Telefon raqami: </b><i>$userPhone</i>\n<b>Tug'ilgan sanasi: </b><i>$userBirth</i>\n\n<b>tanishib chiqib bog'laningю</b>";
                            $afterText = "<b>Ajoyib ma'lumotlaringiz yuborildi! Siz bilan tanishib chiqib, albatta bog'lanishadi.</b>";
                        } else {
                            $groupText = "<b>Кандидат на новую должность прислал свою информацию</b>\n\n<b>Ф-И-Ш: </b><i>$userFull</i>\n<b>Username: </b><i>$userName</i>\n<b>Телефонный номер: </b><i>$userPhone</i>\n<b>Дата рождения: </b><i>$userBirth</i>\n\n<b>знакомьтесь и подключайтесью</b>";
                            $afterText = "<b>Ваша замечательная информация отправлена! Познакомьтесь с вами и обязательно подключитесь.</b>";
                        }
                        // include 'pdf.php';

                        bot('sendMessage', [
                            'chat_id' => -$sendId,
                            // 'chat_id' => $chat_id,
                            'text' => $groupText,
                            'parse_mode' => 'html'
                        ]);
                
                        // $postMessage = "INSERT INTO user_pdf (company_id, user_id, chat_id, doc_name) VALUES (".BOT_ID.", $userId, $chat_id, '$doc_name.pdf')";
                        // pg_query($conn, $postMessage);
                        // bot('sendDocument',[
                        //     'chat_id' => $chat_id,
                        //     'document' => "https://maxdov.uz/bot/itgo/pdf_files/$doc_name.pdf",
                        // ]);

                        $updateMessage = "UPDATE userinfo SET is_active = 1 WHERE id = $userId AND chat_id = $chat_id AND company_id =".BOT_ID;
                        pg_query($conn, $updateMessage);

                        $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
                        pg_query($conn, $sql);

                        if ($step_1 == 1) {
                            $replyMarkup = array(
                                'keyboard' => $uzafterRegister,
                                'resize_keyboard' => true 
                            );
                            $encodedMarkup = json_encode($replyMarkup);
                        } else {
                            $replyMarkup = array(
                                'keyboard' => $ruafterRegister,
                                'resize_keyboard' => true 
                            );
                            $encodedMarkup = json_encode($replyMarkup);
                        }

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => $afterText,
                            'parse_mode' => "html",
                            'reply_markup' => $encodedMarkup
                        ]);
                    } 
                }
            } else {
                $getUserInfo = "SELECT id FROM userinfo WHERE chat_id = $chat_id AND company_id = ".BOT_ID." AND is_active = 0";
                $resultUserInfo = pg_query($conn, $getUserInfo);
                $row2 = pg_fetch_assoc($resultUserInfo);
                $userId = $row2['id'];

                $deleteInfo = "DELETE FROM userinfo WHERE id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo);
                
                $deleteInfo2 = "DELETE FROM user_grade WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo2);
                
                $deleteInfo3 = "DELETE FROM user_study WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo3);
                
                $deleteInfo4 = "DELETE FROM user_trip WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo4);
                
                $deleteInfo5 = "DELETE FROM user_work WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo5);
                
                $deleteInfo6 = "DELETE FROM user_prog WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo6);
                
                $deleteInfo7 = "DELETE FROM user_lang WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo7);
                
                $deleteInfo8 = "DELETE FROM user_family WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo8);
                
                $deleteInfo9 = "DELETE FROM user_ask WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo9);
                
                $deleteInfo10 = "DELETE FROM user_add WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo10);
                
                $deleteInfo11 = "DELETE FROM userinfo_last WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo11);
                
                $deleteInfo12 = "DELETE FROM ourwork WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo12);
                
                $deleteInfo13 = "DELETE FROM inforeg WHERE user_id = $userId AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo13);
                
                $deleteInfo14 = "DELETE FROM userlog WHERE chat_id = $chat_id AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo14);

                $deleteInfo15 = "DELETE FROM additional_aq WHERE user_id = $chat_id AND company_id = ".BOT_ID;
                pg_query($conn, $deleteInfo15);

                bot('deleteMessage', [
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);

                $getUserInfoActive = "SELECT * FROM userinfo WHERE chat_id = $chat_id AND is_active = 1 AND company_id = ".BOT_ID;
                $resultUserInfoActive = pg_query($conn, $getUserInfoActive);

                if (pg_num_rows($resultUserInfoActive) > 0) {
                    if ($step_1 == 1) {
                        $afterText = "O'zbek tilini tanladingiz. Hujjatni o'zbek tilida to'ldiring. Hujjat to'ldirishni boshlash uchun <b><i>«Hujjat topshirish»</i></b> bo'limiga kiring.";
                    } else {
                        $afterText = "Вы выбрали русский язык. Заполните документ на русском языке. Чтобы начать заполнение формы, перейдите в <b><i>«Сдавать документ»</i></b>.";
                    }

                    $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
                    pg_query($conn, $sql);

                    $replyMarkup = array(
                        'keyboard' => $afterRegister,
                        'resize_keyboard' => true 
                    );
                    $encodedMarkup = json_encode($replyMarkup);

                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $afterText,
                        'parse_mode' => "html",
                        'reply_markup' => $encodedMarkup
                    ]);
                } else {
                    if ($step_1 == 1) {
                        $sql = "UPDATE actionreg SET step_1 = 1, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
                        pg_query($conn, $sql);

                        $replyMarkup = array(
                            'keyboard' => $afterRegister,
                            'resize_keyboard' => true 
                        );
                        $encodedMarkup = json_encode($replyMarkup);

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "O'zbek tilini tanladingiz. Hujjatni o'zbek tilida to'ldiring. Hujjat to'ldirishni boshlash uchun <b><i>«Hujjat topshirish»</i></b> bo'limiga kiring.",
                            'parse_mode' => "html",
                            'reply_markup' => $encodedMarkup
                        ]);
                    } else {
                        $sql = "UPDATE actionreg SET step_1 = 2, step_2 = 0, order_num = 0 WHERE chat_id = ".$chat_id." AND company_id = ".BOT_ID;
                        pg_query($conn, $sql);

                        $replyMarkup = array(
                            'keyboard' => $ruLanguageArray,
                            'resize_keyboard' => true 
                        );
                        $encodedMarkup = json_encode($replyMarkup);

                        bot('sendMessage', [
                            'chat_id' => $chat_id,
                            'text' => "Вы выбрали русский язык. Заполните документ на русском языке. Чтобы начать заполнение формы, перейдите в <b><i>«Сдавать документ»</i></b>.",
                            'parse_mode' => "html",
                            'reply_markup' => $encodedMarkup
                        ]);
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