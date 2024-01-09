<?php
include_once('fpdf/fpdf.php');
ob_start();

date_default_timezone_set('Asia/Tashkent');

const MEET_UP_START = '18:00';
const MEET_UP_END = '21:00';
const salary_id = 5;

$conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
if ($conn) {
    echo "Success";
}

const TOKEN = '1656261809:AAFUxaShUltI6zG6KdDrdUQvLQYyp1x7BAU';
const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;

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
    } else
    {
       return json_decode($res);
    }
}




$update = file_get_contents('php://input');
$update = json_decode($update);
$text = "";
$chat_id = "";
if (isset($update->callback_query)) {
    $data = $update->callback_query->data;
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
}else {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;
    $name = $message->chat->first_name;
    $message_id = $message->message_id;
    $username = $message->chat->username;
}



$txtArr = 0;
if (preg_match('~start~',$text)) {
    $txtArr = explode(" ", $text);
}

$number = 'xatolik';
if (!empty($txtArr)) {
    $start = (isset($txtArr[0])) ? $txtArr[0] : ' xatolik';
    $number = (isset($txtArr[1])) ? $txtArr[1] : ' xatolik';
}

//Keyboards

$set_contact = json_encode([
    'keyboard' =>[
        [
            ['text' => '📱 Telefon raqam jonating!','request_contact'=>true],
        ]
    ],
    'resize_keyboard' => true
]);

$admin_keyboard = json_encode([
    'keyboard' => [
        [
            ['text' => 'Barcha meetuplar 🧾'],
            ['text' => 'Tasdiqlanmagan meetuplar'],
        ]
    ],
    'resize_keyboard' => true
]);

$remove_keyboard = json_encode([
    'remove_keyboard' => true
]);

$daily_meetup_start = json_encode([
    'keyboard' => [
        [
            ['text' => 'Kunlik hisobotni boshlash'],
        ]
    ],
    'resize_keyboard' => true
]);




if ($text == $start . " " . $number && $txtArr != 0) {
    $sql = "select * from users where phone_number = '".$number."'";
    $result = pg_query($conn,$sql);
    if (pg_num_rows($result) > 0) {
        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Ushbu botdan foydalanish oldin telefon raqam jonating.',
            'reply_markup' => $set_contact
        ]);
    }else {
        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Notg\'ri link orqali kirdingiz',
        ]);
    }
}
if (isset($update->message->contact)) {
    $number = $update->message->contact->phone_number;
    $number = str_replace("+","",$number);
    
    $get_contact = "SELECT * FROM users WHERE phone_number = '".$number."'";
    
    $contact = pg_query($conn, $get_contact);
    if (pg_num_rows($contact) > 0) {
        $row = pg_fetch_assoc($contact);
        $user_type = $row["type"];
        
        $sql_user_set_quiz = "SELECT * FROM quiz_step WHERE phone_number = '".$number."'";
        $user_set_quiz = pg_query($conn, $sql_user_set_quiz);
        if (pg_num_rows($user_set_quiz) > 0) {
            $update_step_step_quiz = 'UPDATE quiz_step SET step_1 = 3,step_2 = 0, chat_id = '.$chat_id.' WHERE phone_number = \''.$number.'\'';
            pg_query($conn,$update_step_step_quiz);
            
            if ($user_type == 1) {
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => 'Siz muvafaqiyatli botga qoshildingiz',
                    'reply_markup' => $admin_keyboard
                ]);
            }else{
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => 'Siz muvafaqiyatli botga qoshildingiz',
                    'reply_markup' => $daily_meetup_start
                ]);
            }
        }else {
            $insert_step_quiz = 'INSERT INTO quiz_step ( "chat_id", "step_1", "step_2","phone_number") 
            VALUES (\''.$chat_id.'\', \'3\', \'0\',\''.$number.'\')';
            $insert = pg_query($conn, $insert_step_quiz);
            if ($user_type == 1) {
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => 'Siz muvafaqiyatli botga qoshildingiz',
                    'reply_markup' => $admin_keyboard
                ]);
            }else{
                bot('sendMessage',[
                    'chat_id' => $chat_id,
                    'text' => 'Siz muvafaqiyatli botga qoshildingiz',
                    'reply_markup' => $daily_meetup_start
                ]);
            }
        }
    }else {
        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Bu botdan 🤖 foydalanish uchun sizda huquq yo\'q ☹️❗️',
            'reply_markup' => $remove_keyboard
        ]);
    }
}

$step_user = "SELECT
        qs.step_1,
        qs.step_2,
        u.type
    FROM quiz_step AS qs
    INNER JOIN users AS u
        ON qs.phone_number = u.phone_number
    WHERE qs.chat_id = '".$chat_id."' AND u.type != 1";
$result = pg_query($conn, $step_user);

if (pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $step_1 = (isset($row["step_1"])) ? $row["step_1"] : ' xatolik';
    $step_2 = (isset($row["step_2"])) ? $row["step_2"]: ' xatolik';    
    $type = (isset($row["type"])) ? $row["type"]: ' xatolik';    
}

$step_admin = "SELECT
        qs.step_1,
        qs.step_2,
        u.type
    FROM quiz_step AS qs
    INNER JOIN users AS u
        ON qs.phone_number = u.phone_number
    WHERE qs.chat_id = '".$chat_id."' AND u.type = 1";
$result_admin = pg_query($conn, $step_admin);
if (pg_num_rows($result_admin) > 0) {
    $row = pg_fetch_assoc($result_admin);
    $step_1 = (isset($row["step_1"])) ? $row["step_1"] : ' xatolik';
    $step_2 = (isset($row["step_2"])) ? $row["step_2"]: ' xatolik';    
    $type = (isset($row["type"])) ? $row["type"]: ' xatolik';    
}

if ($text == '/start' && $type == 1) {
    bot('sendMessage',[
        'chat_id' => $chat_id,
        'text' => 'Kunlik hisibotlar',
        'reply_markup' => $admin_keyboard
    ]);
}elseif ($text == '/start' && ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)) {
    if (date('H:i') < MEET_UP_START || date('H:i') > MEET_UP_END) {
        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Kunlik hisobot '.MEET_UP_START.' da boshlanadi',
            'reply_markup' => $daily_meetup_start
        ]);
    }
}

if (date('H:i') == MEET_UP_START) {
    $date = date('Y-m-d');

    $sql = 'SELECT
            sq.user_id,
            qs.chat_id
        FROM send_quiz AS sq
        INNER JOIN users AS u
            ON u.id = sq.user_id
        INNER JOIN quiz_step AS qs
            ON qs.phone_number = u.phone_number
        WHERE u."type" != 1 AND qs.step_2 = 3 AND qs.step_1 = 1
    GROUP BY sq.user_id,qs.chat_id';
    $result = pg_query($conn,$sql);

    
    if (pg_num_rows($result) > 0) {
        $update_step_step_quiz = 'UPDATE quiz_step SET step_1 = 0,step_2 = 4';    
        $update = pg_query($conn,$update_step_step_quiz);
    
        $delete_answer = 'DELETE FROM answer';
        pg_query($conn,$delete_answer);    

        while ($value = pg_fetch_assoc($result)) {
            $start_chat_id = $value["chat_id"];
            $start_user_id = $value["user_id"];
            
            bot('sendMessage',[
                'chat_id' => $start_chat_id,
                'text' => 'Kunlik hisobot vaqti boshlanid va '.MEET_UP_END.' da tugidi',
                'reply_markup' => $daily_meetup_start
            ]);
        }
    }
}

if ($text == 'Kunlik hisobotni boshlash' && ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) ) {
    if (date('H:i') >= MEET_UP_START && date('H:i') < MEET_UP_END) {
        $date = date('Y-m-d');
        $sql = 'SELECT * FROM daily_answer AS da
            INNER JOIN users AS u
                ON da.user_id = u.id
            INNER JOIN quiz_step AS qs
                ON u.phone_number = qs.phone_number
        WHERE qs.chat_id = '.$chat_id.' AND da.date = \''.$date.'\'';
        $result = pg_query($conn, $sql);
        if (pg_num_rows($result) == 0) {
            $update_step_step_quiz = 'UPDATE quiz_step SET step_1 = 1,step_2 = 0 WHERE chat_id = '.$chat_id;
            $update = pg_query($conn,$update_step_step_quiz);
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => 'Siz kunlik hisobot boshladiz!',
                'reply_markup' => $remove_keyboard
            ]);

            $answer_sql = 'SELECT 
                    q.question,
                    a.answer,
                    qs.chat_id,
                    qs.step_1,
                    qs.step_2,
                    qs.phone_number
                FROM send_quiz AS sq
                INNER JOIN quiz AS q
                    ON sq.quiz_id = q.id
                LEFT JOIN answer AS a
                    ON a.quiz_id = q.id
                INNER JOIN users AS u
                    ON u.id = sq.user_id
                LEFT JOIN quiz_step AS qs
                    ON qs.phone_number = u.phone_number
            WHERE u."type" != 1 AND a.date IS NULL AND a.answer IS NULL AND qs.chat_id = '.$chat_id.'
            ORDER BY sq.quiz_id ASC,q.category_id ASC';
            $result = pg_query($conn,$answer_sql);

            $answer_have = 'SELECT * FROM daily_answer AS da
                INNER JOIN users AS u
                    ON u.id = da.user_id
                INNER JOIN quiz_step AS qs 
                    ON qs.phone_number = u.phone_number 
            WHERE da.date = \''.$date.'\' AND qs.chat_id = '.$chat_id;
            $result3 = pg_query($conn,$answer_have);

            if (pg_num_rows($result) > 0 && pg_num_rows($result3) == 0 && ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)) {
                $row = pg_fetch_assoc($result);
                $quiz_question = (isset($row["question"])) ? $row["question"] : ' xatolik';
                $quiz_chat_id = (isset($row["chat_id"])) ? $row["chat_id"] : ' xatolik';
                $quiz_answer = (isset($row["answer"])) ? $row["answer"] : ' xatolik';
                $quiz_number = (isset($row["phone_number"])) ? $row["phone_number"] : ' xatolik';

                $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 1 WHERE phone_number = \''.$quiz_number.'\'';
                $update = pg_query($conn,$update_step_step_quiz);

                bot('sendMessage',[
                    'chat_id' => $quiz_chat_id,
                    'text' => $quiz_question
                ]);
            }
        }else {
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => 'Siz kunlik hisobot topshirib bolgansiz!',
            ]);
        }
    }else {
        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Kunlik hisobot '.MEET_UP_START.' da boshlanadi',
            'reply_markup' => $daily_meetup_start
        ]);
    }
}

if ($type == 1 && $text == 'Barcha meetuplar 🧾') {
    $file_date_list_sql = 'SELECT da.date FROM daily_answer AS da
        WHERE status = 1 AND type = 0
        GROUP BY da.date 
        ORDER BY da.date DESC
        LIMIT 10';
    $file_date_list = pg_query($conn,$file_date_list_sql);
    if (pg_num_rows($file_date_list) > 0) {
        $row_arr = [];
        $arr_uz = [];
        while ($value = pg_fetch_assoc($file_date_list)) {
            $arr_date_list = $value["date"];
            $arr_uz[] = ["text" => $arr_date_list, "callback_data" => $arr_date_list];
            $row_arr[] = $arr_uz;
            $arr_uz = [];
        }
        $inline_btn = json_encode(['inline_keyboard' => $row_arr]);
        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Kunlik hisobotlar',
            'reply_markup' => $inline_btn
        ]);
    }

}

if (isset($update->callback_query) && $type == 1) {
    if (preg_match('~\d{4}-\d{2}-\d{2}$~',$data)) {
        $answer_list_sql = 'SELECT da.user_id,u.second_name,da.file_name FROM daily_answer AS da
        LEFT JOIN users AS u
            ON da.user_id = u.id
        WHERE da.date = \''.$data.'\' AND da."status" = 1 AND da.type = 0
        LIMIT 10';
        $answer_list = pg_query($conn, $answer_list_sql);
        if (pg_num_rows($answer_list) > 0) {
            $row_arr = [];
            $arr_uz = [];
            while ($value = pg_fetch_assoc($answer_list)) {
                $arr_date_list = $value["second_name"];
                $arr_date = $data.'_'.$value["user_id"];
                $arr_uz[] = ["text" => $arr_date_list, "callback_data" => $arr_date];
                $row_arr[] = $arr_uz;
                $arr_uz = [];
            }
            $inline_btn = json_encode(['inline_keyboard' => $row_arr]);
            bot('editMessageText',[
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => $date.' kunnig hosbotlari.',
                'reply_markup' => $inline_btn
            ]);
        }
    }elseif(preg_match('~\d{4}-\d{2}-\d{2}_\d+~',$data)) {
        $date_and_user = explode("_", $data);
        $date = $date_and_user[0];
        $user = $date_and_user[1];
        $sql = 'SELECT * FROM daily_answer AS da
        LEFT JOIN users AS u
            ON da.user_id = u.id
        WHERE da.user_id = '.$user.' AND da."status" = 1 AND da."type" = 0 AND da.date = \''.$date.'\'';
        $file_find = pg_query($conn, $sql);
        if (pg_num_rows($file_find) > 0) {
            $row = pg_fetch_assoc($file_find);
            $file_name = $row['file_name'];
            $second_name = $row['second_name'];
            bot('sendDocument',[
                'chat_id' => $chat_id,
                'document' => $file_name,
                'caption' => $second_name.' ning hisoboti'
            ]);        
        }        
    }
}


if (date('H:i') >= MEET_UP_START && date('H:i') < MEET_UP_END && !(preg_match('~^/(?:start)~',$text)) && ($step_1 == 1 || $type == 1)) {

    if ($type == 1 && $text == 'Tasdiqlanmagan meetuplar') {
        $date = date('Y-m-d');
        $sql = 'SELECT da.file_name,da.user_id,u.second_name FROM daily_answer AS da
            INNER JOIN users AS u
		        ON da.user_id = u.id
        WHERE da.status = 0 AND da.date = \''.$date.'\'';
        $result = pg_query($conn,$sql);
        if (pg_num_rows($result) > 0) {
            while ($value = pg_fetch_assoc($result)) {
                $admin_check = json_encode([
                    'inline_keyboard' =>[
                        [
                            ['callback_data' => $value['user_id'].'-yes','text'=>"Tasdiqlash✅"],['callback_data'=> $value['user_id'].'-no','text'=>"Ortga🚫"]  
                        ],
                    ]
                ]);
                bot('sendDocument',[
                    'chat_id' => $chat_id,
                    'document' => $value['file_name'],
                    'caption' => $value['second_name'].' ning hisoboti',
                    'reply_markup' => $admin_check
                ]);

            }
        }else{
            bot('sendMessage',[
                'chat_id' => $chat_id,
                'text' => 'Tasdiqlanmgan meetuplar yoq!'
            ]);
        }
    }

    if (isset($update->callback_query) && ($type == 1 || $type == 2)) {
        if (preg_match('~^\d+-(?:yes|no)$~',$data)) {
            $date = date('Y-m-d');
            $answer_status = explode("-", $data);
            $sql = 'SELECT qs.chat_id AS chat_id FROM quiz_step AS qs 
            INNER JOIN users AS u
            ON qs.phone_number = u.phone_number
            WHERE u.id = '.$answer_status[0];
            $send = pg_query($conn,$sql);
            if ($answer_status[1] == 'yes') {
                $daily_save = 'UPDATE daily_answer SET status = 1 WHERE date = \''.$date.'\' AND  user_id = '.$answer_status[0];
                $update = pg_query($conn,$daily_save);
                if (pg_num_rows($send) > 0) {
                    $row = pg_fetch_assoc($send);
                    $quiz_users_id = $row["chat_id"];
                    $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 2 WHERE chat_id = '.$quiz_users_id;
                    $update = pg_query($conn,$update_step_step_quiz);                
                    bot('sendMessage',[
                        'chat_id' => $quiz_users_id,
                        'text' => 'Sizning kunlik hisobotingiz tasdiqlandi.'
                    ]);
                }
                $sql = 'SELECT da.file_name,u.second_name FROM daily_answer AS da
                    INNER JOIN users AS u
                    ON da.user_id = u.id
                    WHERE da.date = \''.$date.'\' AND da.user_id = '.$answer_status[0].'
                LIMIT 1';
                $remve_check_keyboard = pg_query($conn,$sql);
                if (pg_num_rows($remve_check_keyboard) > 0) {
                    $row = pg_fetch_assoc($remve_check_keyboard);
                    bot('deleteMessage',[
                        'chat_id' => $chat_id,
                        'message_id' => $message_id
                    ]);
                    
                    bot('sendDocument',[
                        'chat_id' => $chat_id,
                        'document' => $row['file_name'] ,
                        'caption' => $row['second_name'].' Kunlik hisoboti'
                    ]);
                }

            }elseif ($answer_status[1] == 'no') {
                $daily_delete = 'DELETE FROM daily_answer WHERE date = \''.$date.'\' AND status = 0 AND  user_id = '.$answer_status[0];
                $delete = pg_query($conn,$daily_delete);
                if (pg_num_rows($send) > 0) {
                    $row = pg_fetch_assoc($send);
                    $quiz_users_id = $row["chat_id"];
                    $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 0 WHERE chat_id = '.$quiz_users_id;
                    $update = pg_query($conn,$update_step_step_quiz);                
                    bot('sendMessage',[
                        'chat_id' => $quiz_users_id,
                        'text' => 'Sizning kunlik hisobotingiz bekor qilindi va qayta javob bering.',
                        'reply_markup' => $daily_meetup_start
                    ]);
                    $update_step_step_quiz = 'UPDATE quiz_step SET step_1 = 0,step_2 = 4 WHERE chat_id = '.$quiz_users_id;    
                    pg_query($conn,$update_step_step_quiz);
                
                }
                bot('deleteMessage',[
                    'chat_id' => $chat_id,
                    'message_id' => $message_id
                ]);
            }
        }
    }

    $date = date('Y-m-d');

    if (isset($chat_id) && !empty($chat_id) && isset($text) && $step_1 == 1 && ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)) {
        $select = 'SELECT 
                u.id AS users_id,
                q.id AS quiz_id,
                q.type AS quiz_type 
            FROM send_quiz AS sq
            INNER JOIN quiz AS q
                ON sq.quiz_id = q.id
            LEFT JOIN answer AS a
                ON a.quiz_id = q.id
            INNER JOIN users AS u
                ON u.id = sq.user_id
            LEFT JOIN quiz_step AS qs
                ON qs.phone_number = u.phone_number
            WHERE u."type" != 1 AND a.date IS NULL AND a.answer IS NULL AND qs.chat_id = '.$chat_id.' 
            ORDER BY sq.quiz_id ASC,q.category_id ASC
        LIMIT 1';
        $result = pg_query($conn,$select);

        $condition_da = 'SELECT * FROM daily_answer AS da 
            INNER JOIN users AS u
                ON u.id = da.user_id
            INNER JOIN quiz_step AS qs 
                ON qs.phone_number = u.phone_number
        WHERE  da.date = \''.$date.'\' AND qs.chat_id = '.$chat_id;
        $result2 = pg_query($conn,$condition_da);

        if (pg_num_rows($result) > 0 && pg_num_rows($result2) == 0) {
            $row = pg_fetch_assoc($result);
            if ($row["quiz_type"] == 1) {
                if (preg_match('~^\d+$~',$text)) {
                    $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 0 WHERE chat_id = \''.$chat_id.'\'';
                    $update = pg_query($conn,$update_step_step_quiz);
                    $quiz_users_id = (isset($row["users_id"])) ? $row["users_id"] : ' xatolik';
                    $quiz_quiz_id = (isset($row["quiz_id"])) ? $row["quiz_id"] : ' xatolik';
                    $date = date('Y-m-d');
                    $encode_text = base64_encode($text);
                    $insert = "INSERT INTO answer (\"answer\",\"user_id\",\"quiz_id\",\"date\",\"status\")
                    VALUES ('".$encode_text."','".$quiz_users_id."','".$quiz_quiz_id."','".$date."','0')";
                    $result = pg_query($conn,$insert);                        
                }else {
                    bot('sendMessage',[
                        'chat_id' => $chat_id,
                        'text' => 'Bu savolga son bilan javob beriladi.'
                    ]);
                }
            }else {
                $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 0 WHERE chat_id = \''.$chat_id.'\'';
                $update = pg_query($conn,$update_step_step_quiz);
                $quiz_users_id = (isset($row["users_id"])) ? $row["users_id"] : ' xatolik';
                $quiz_quiz_id = (isset($row["quiz_id"])) ? $row["quiz_id"] : ' xatolik';
                $date = date('Y-m-d');
                $encode_text = base64_encode($text);
                $insert = "INSERT INTO answer (\"answer\",\"user_id\",\"quiz_id\",\"date\",\"status\")
                VALUES ('".$encode_text."','".$quiz_users_id."','".$quiz_quiz_id."','".$date."','0')";
                $result = pg_query($conn,$insert);
            }
        }
    
    }


    $answer_sql = 'SELECT 
            q.question,
            a.answer,
            qs.chat_id,
            qs.step_1,
            qs.step_2,
            qs.phone_number
        FROM send_quiz AS sq
        INNER JOIN quiz AS q
            ON sq.quiz_id = q.id
        LEFT JOIN answer AS a
            ON a.quiz_id = q.id
        INNER JOIN users AS u
            ON u.id = sq.user_id
        LEFT JOIN quiz_step AS qs
            ON qs.phone_number = u.phone_number
        WHERE qs.step_2 = 0 AND u."type" != 1 AND a.date IS NULL AND a.answer IS NULL
    ORDER BY sq.quiz_id ASC,q.category_id ASC';
    $result = pg_query($conn,$answer_sql);

    $answer_have = 'SELECT * FROM daily_answer AS da
        INNER JOIN users AS u
            ON u.id = da.user_id
        INNER JOIN quiz_step AS qs 
            ON qs.phone_number = u.phone_number 
    WHERE da.date = \''.$date.'\' AND qs.chat_id = '.$chat_id;
    $result3 = pg_query($conn,$answer_have);

    if (pg_num_rows($result) > 0 && pg_num_rows($result3) == 0 && ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) && $step_1 == 1) {
        $row = pg_fetch_assoc($result);
        $quiz_question = (isset($row["question"])) ? $row["question"] : ' xatolik';
        $quiz_chat_id = (isset($row["chat_id"])) ? $row["chat_id"] : ' xatolik';
        $quiz_answer = (isset($row["answer"])) ? $row["answer"] : ' xatolik';
        $quiz_number = (isset($row["phone_number"])) ? $row["phone_number"] : ' xatolik';

        $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 1 WHERE phone_number = \''.$quiz_number.'\'';
        $update = pg_query($conn,$update_step_step_quiz);

        bot('sendMessage',[
            'chat_id' => $quiz_chat_id,
            'text' => $quiz_question
        ]);
    }

    $select = 'SELECT * FROM send_quiz AS sq
        LEFT JOIN answer AS a
            ON a.quiz_id = sq.quiz_id
        INNER JOIN users AS u
            ON u.id = sq.user_id
        LEFT JOIN quiz_step AS qs
            ON qs.phone_number = u.phone_number
    WHERE qs.chat_id = '.$chat_id.' AND u.type != 1 AND qs.step_2 = 1 AND a.date = \''.$date.'\''; 
    $result = pg_query($conn,$select);

    $quiz_have = 'SELECT u.id AS users_id,q.id AS quiz_id FROM send_quiz AS sq
        INNER JOIN quiz AS q
            ON sq.quiz_id = q.id
        LEFT JOIN answer AS a
            ON a.quiz_id = q.id
        INNER JOIN users AS u
            ON u.id = sq.user_id
        LEFT JOIN quiz_step AS qs
            ON qs.phone_number = u.phone_number
        WHERE  u."type" != 1 AND qs.chat_id = '.$chat_id.' AND a.answer IS NULL 
        ORDER BY sq.quiz_id ASC,q.category_id ASC
    LIMIT 1';
    $result2 = pg_query($conn,$quiz_have);

    $answer_have = 'SELECT * FROM daily_answer AS da
        INNER JOIN users AS u
            ON u.id = da.user_id
        INNER JOIN quiz_step AS qs 
            ON qs.phone_number = u.phone_number 
    WHERE da.date = \''.$date.'\' AND qs.chat_id = '.$chat_id;
    $result3 = pg_query($conn,$answer_have);

    if (pg_num_rows($result) == 0 && pg_num_rows($result2) == 0 && pg_num_rows($result3) == 0 && ($type == 2 || $type == 3|| $type == 4 || $type == 5 || $type == 6 || $type == 7) && $step_1 == 1) {
        $update_step_step_quiz = 'UPDATE quiz_step SET step_2 = 2 WHERE chat_id = '.$chat_id;
        $update = pg_query($conn,$update_step_step_quiz);    

        $sql = 'SELECT
            u.id,
            u.second_name
            FROM quiz_step AS qs
            INNER JOIN users AS u
            ON qs.phone_number = u.phone_number
        WHERE qs.chat_id = \''.$chat_id.'\'';
        $result = pg_query($conn,$sql);
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $delete_user_id = (isset($row["id"])) ? $row["id"] : ' xatolik';
            $second_name = (isset($row["second_name"])) ? $row["second_name"] : ' xatolik';


            //PDF create start
            $date = date('d-m-Y');
            $pdf = new PDF_MC_Table();

            $pdf->AddPage();
            $pdf->SetFont('Arial','B',14);

            $pdf->Ln();

            $pdf->Cell(90,10,$second_name);
            $pdf->Cell(90,10,$date);
            $pdf->Ln();
            $pdf->Cell(90,10,'Savol',1);
            $pdf->Cell(90,10,'Javob',1);
            $pdf->Ln();
            $pdf->SetFont('Arial','',14);

            
            $sql = 'SELECT * FROM answer AS a
            INNER JOIN quiz AS q 
            ON q.id = a.quiz_id AND a.user_id = '.$delete_user_id;
            $result = pg_query($conn,$sql);
            if (pg_num_rows($result) > 0) {
                $pdf->SetWidths(array(90, 90));
                while ($value = pg_fetch_assoc($result)) {
                    $answer = base64_decode($value["answer"]);
                    $quiz = (isset($value["question"])) ? $value["question"] : ' xatolik';
                    
                    $pdf->Row(
                        [$quiz,$answer]
                    );
                    // $x = $pdf->GetX();
                    // $pdf->myCell(90,16,$x,$quiz);
                    // $x = $pdf->GetX();
                    // $pdf->myCell(90,16,$x,$answer);
                    // $pdf->Ln();
                }
            }
            
            $file_name = date('Ymdhis').$delete_user_id;
            $pdf->Output('F','fpdf/uploads/'.$file_name.'.pdf');
            $pdf->Close();
            

            $res = bot('sendDocument',[
                'chat_id' => -1001396848602,
                'document' => 'https://app.original-mebel.ru/fpdf/uploads/'.$file_name.'.pdf',
            ]);
            if (isset($res->result->document)) {
                $file_id = $res->result->document->file_id;
                unlink('fpdf/uploads/'.$file_name.'.pdf');
            }

            $delete = 'DELETE FROM answer
            WHERE answer.user_id = \''.$delete_user_id.'\'';
            $result = pg_query($conn,$delete);

            $date = date('Y-m-d');
            $save_file_sql = 'INSERT INTO daily_answer ("user_id","file_name","date","status","type")
            VALUES ('.$delete_user_id.',\''.$file_id.'\',\''.$date.'\',0,0)';
            $save_file = pg_query($conn,$save_file_sql);
            

            //PDF create end


            $sql = 'SELECT qs.chat_id FROM quiz_step AS qs 
            INNER JOIN users AS u
            ON qs.phone_number = u.phone_number
            WHERE u."type" = 1 OR u."type" = 2';
            $result = pg_query($conn,$sql);
            if (pg_num_rows($result) > 0) {
                while ($value = pg_fetch_assoc($result)) {
                    $admin = (isset($value["chat_id"])) ? $value["chat_id"] : ' xatolik';
                    $admin_check = json_encode([
                        'inline_keyboard' =>[
                            [
                                ['callback_data' => $delete_user_id.'-yes','text'=>"Tasdiqlash✅"],['callback_data'=> $delete_user_id.'-no','text'=>"Ortga🚫"]  
                            ],
                        ]
                    ]);
                    bot('sendDocument',[
                        'chat_id' => $admin,
                        'document' => $file_id,
                        'caption' => $second_name.' ning hisoboti',
                        'reply_markup' => $admin_check
                    ]);
                }
            }
        }

        bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'Siz hamma savolarga javob berdingiz va admin tasdiqlashini kuting.'
        ]);
    }
       
    
}

if (date('H:i') == MEET_UP_END) {
    $date = date('Y-m-d');

    $sql ='SELECT
            sq.user_id,
            qs.chat_id,
            u.second_name
        FROM send_quiz AS sq
        INNER JOIN users AS u
            ON u.id = sq.user_id
        INNER JOIN quiz_step AS qs
            ON qs.phone_number = u.phone_number
        WHERE qs.step_2 != 3
    GROUP BY sq.user_id,qs.chat_id,u.second_name';
    $result = pg_query($conn,$sql);
      

    $delete_answer = 'DELETE FROM answer';
    pg_query($conn,$delete_answer);
    
    $update_step_step_quiz = 'UPDATE quiz_step SET step_1 = 1,step_2 = 3';    
    $update = pg_query($conn,$update_step_step_quiz);
            
    $select_daily = 'SELECT 
        qs.chat_id
        FROM daily_answer AS da
        LEFT JOIN users AS u
            ON da.user_id = u.id
        LEFT JOIN quiz_step AS qs
            ON qs.phone_number = u.phone_number
    WHERE da.status = 0 AND da.date = \''.$date.'\'';
    $daily_status = pg_query($conn,$select_daily);
    if (pg_num_rows($daily_status) > 0){
        while ($select_daily_while = pg_fetch_assoc($daily_status)) {
            $send_true_chat_id = $select_daily_while['chat_id'];
            bot('sendMessage',[
                'chat_id' => $send_true_chat_id,
                'text' => 'Sizning kunlik hisobot tasdiqlandi'
            ]);
        }
    }        

    $update_daily = 'UPDATE daily_answer SET status = 1 WHERE status = 0 AND date = \''.$date.'\'';
    $update = pg_query($conn,$update_daily);        

    if (pg_num_rows($result) > 0) {
        while ($value = pg_fetch_assoc($result)) {
            $start_chat_id = $value["chat_id"];
            $start_user_id = $value["user_id"];
            $start_name = $value["second_name"];
            
            $have_daily_sql = 'SELECT * FROM daily_answer AS da
            WHERE da.user_id = '.$start_user_id.' AND da.date = \''.$date.'\'';
            $have_daily = pg_query($conn,$have_daily_sql);        
            if (pg_num_rows($have_daily) == 0) {
                $penalties = 0;
                $find_last_sum_sql = 'SELECT * FROM penalty_users
                WHERE user_id = '.$start_user_id;
                $find_last_sum = pg_query($conn,$find_last_sum_sql);
                if (pg_num_rows($find_last_sum) > 0) {
                    $last_sum = pg_fetch_assoc($find_last_sum);
                    $penalty_sum = $last_sum['sum'];

                    $find_penalty_sql = 'SELECT
                            SUM(penalty) AS penalty
                        FROM penalties 
                        WHERE user_id = '.$start_user_id.'
                    GROUP BY user_id';
                    $find_penalty = pg_query($conn,$find_penalty_sql);
                    if (pg_num_rows($find_penalty) > 0) {
                        $sum_penlty = pg_fetch_assoc($find_penalty);
                        $penalties = $sum_penlty['penalty'];
                    }

                    $daily_pen = 'INSERT INTO daily_answer ("user_id","file_name","date","status","type")
                    VALUES ('.$start_user_id.',\''.$penalties.'\',\''.$date.'\',1,1);';
                    pg_query($conn,$daily_pen);

                    if ($penalty_sum == 0) {
                        $sum = $penalties;
                    }else {
                        $sum = $penalties + $penalty_sum;
                    }

                    $penalty_add = 'UPDATE penalty_users SET sum = '.$sum.' WHERE user_id = '.$start_user_id;
                    pg_query($conn,$penalty_add);

                    $salery_title_sql = 'SELECT * FROM salary_category AS sc
                    WHERE sc.id = '.salary_id;
                    $salery_title = pg_query($conn,$salery_title_sql);        
                    if (pg_num_rows($salery_title) > 0) {
                        $salery_title_fetch = pg_fetch_assoc($salery_title);
                        $salery_title = base64_encode($salery_title_fetch['title']);
                        
                        $insertAmout = 'INSERT INTO salary_amount ("chat_id","category_id","price","comment","date","type","status","user_id")
                        VALUES ('.$start_chat_id.','.salary_id.','.$penalties.',\''.$salery_title.'\',\''.$date.'\',3,1,'.$start_user_id.')';
                        
                        pg_query($conn,$insertAmout);
                    }

                    bot('sendMessage',[
                        'chat_id' => $start_chat_id,
                        'text' => 'Bugungi kunlik hisobot qilmadingiz va sizga '.$penalties.' jarima tushdi!'
                    ]);   
    
                }else{
                    $find_penalty_sql = 'SELECT
                            SUM(penalty) AS penalty
                        FROM penalties 
                        WHERE user_id = '.$start_user_id.'
                    GROUP BY user_id';
                    $find_penalty = pg_query($conn,$find_penalty_sql);
                    if (pg_num_rows($find_penalty) > 0) {
                        $sum_penlty = pg_fetch_assoc($find_penalty);
                        $penalties = $sum_penlty['penalty'];
                    }

                    $insert_sql = 'INSERT INTO penalty_users ("user_id", "sum") 
                    VALUES (\''.$start_user_id.'\', \''.$penalties.'\');'; 
                    pg_query($conn,$insert_sql);

                    $daily_pen = 'INSERT INTO daily_answer ("user_id","file_name","date","status","type")
                    VALUES ('.$start_user_id.',\''.$penalties.'\',\''.$date.'\',1,1)';
                    pg_query($conn,$daily_pen);

                    $salery_title_sql = 'SELECT * FROM salary_category AS sc
                    WHERE sc.id = '.salary_id;
                    $salery_title = pg_query($conn,$salery_title_sql);        
                    if (pg_num_rows($salery_title) > 0) {
                        $salery_title_fetch = pg_fetch_assoc($salery_title);
                        $salery_title = base64_encode($salery_title_fetch['title']);
                        
                        $insertAmout = 'INSERT INTO salary_amount ("chat_id","category_id","price","comment","date","type","status","user_id")
                        VALUES ('.$start_chat_id.','.salary_id.','.$penalties.',\''.$salery_title.'\',\''.$date.'\',3,1,'.$start_user_id.')';
                        pg_query($conn,$insertAmout);
                    }

                    bot('sendMessage',[
                        'chat_id' => $start_chat_id,
                        'text' => 'Bugungi kunlik hisobot qilmadingiz va sizga '.$penalties.' jarima tushdi!'
                    ]);   
                }
            }
        }
    }
}


if (date('H:i') > MEET_UP_START && date('H:i') < MEET_UP_END && (date('i') == '30' || date('i') == '00') && empty($chat_id)) {
    $usersFind_sql = 'SELECT 
        sq.user_id AS id,
        qs.chat_id AS chat_id
        FROM send_quiz AS sq
        INNER JOIN users AS us
            ON us.id = sq.user_id
        INNER JOIN quiz_step AS qs
            ON qs.phone_number = us.phone_number	
        WHERE us."type" != 1
    GROUP BY sq.user_id,qs.chat_id';
    $usersFind_result = pg_query($conn, $usersFind_sql);
    if (pg_num_rows($usersFind_result) > 0) {
        $date = date('Y-m-d');
        while ($userFind = pg_fetch_assoc($usersFind_result)) {
            $userFindId = $userFind['id'];
            $userFindChatId = $userFind['chat_id'];
            
            $find_user_in_daily_sql = 'SELECT da.user_id FROM daily_answer AS da
                WHERE da.date = \''.$date.'\' AND da.user_id = '.$userFindId.'
            GROUP BY da.user_id';
            $find_user_in_daily_result = pg_query($conn, $find_user_in_daily_sql);
            if (pg_num_rows($find_user_in_daily_result) == 0) {
                $find_user_in_answer_sql = 'SELECT an.user_id FROM answer AS an
                    WHERE an.user_id = '.$userFindId.' AND an.date = \''.$date.'\'
                GROUP BY an.user_id';
                $find_user_in_answer_result = pg_query($conn, $find_user_in_answer_sql);
                if (pg_num_rows($find_user_in_answer_result) == 0) {
                    bot('sendMessage',[
                        'chat_id' => $userFindChatId,
                        'text' => '‼️ Kunlik hisobot bering, aks holda sizga jarima yoziladi ‼️',
                        'reply_markup' => $daily_meetup_start
                    ]);
                }
            }
        }
    }
}

?>