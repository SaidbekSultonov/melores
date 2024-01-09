<?php
    const TOKEN = '1611335763:AAF6-m9wtcdiYUiVi8pefTrGdnTeYuOYjvk';
    const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;

// ===================================================================== FUNCTIONS AND CLASSES
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
    
    // ob_start();
    
    function typing($ch) {
        return bot('sendChatAction',[
            'chat_id' => $ch,
            'action' => 'typing'
            ]);
    }


    $dsn = "pgsql:host=localhost;port=5432;dbname=orginal_db;user=postgres;password=postgres";
    try {
        $conn = new PDO($dsn);
        if($conn){
            echo "SuccessDb";
            die("123");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        
    }
// ===================================================================== TAKE NEED PARAMETRS


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
        if(isset($message->chat->last_name)){
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

// ====================================================================================== START
  
    // if ($text == "/start") {

    //     $sql = new ActionWithSql();


    //     $sql->SetTable("about_user");
    //     $sql->delete("status = 0 and chat_id = $chat_id");
        
    //     $sql->SetTable("another_works");
    //     $sql->delete("status = 0 and chat_id = $chat_id");
        
    //     $sql->SetTable("users");
    //     $result = $sql->select("*","chat_id = $chat_id");
    //     $sql->SetTable("step");
    //     $resultTwo = $sql->select("*","chat_id = $chat_id");
    //     $rowTwo = $resultTwo->fetch_assoc();
    //     $stepOne = $rowTwo["step_1"];
       

    //     if (!$result->num_rows > 0 or $stepOne == 0) {
    //         $valuesPart = "$chat_id,'".$username."','".$name."'";
    //         $sql->insert("chat_id,username,first_name",$valuesPart);
    //         $sql->SetTable("step");
    //         $valuesPart = "$chat_id,0,0";
    //         $insert = $sql->insert("chat_id,step_1,step_2",$valuesPart);
    //         $sql->SetTable("last_id");
    //         $sql->insert("last_id,chat_id","0,$chat_id");
    //         $sms = new Natfication();
    //         $sms->chat_id = $chat_id;
    //         $sms->text = "🇺🇿 Ўзингизга қулай бўлган тилни танланг!\n🇺🇿 O'zingizga qulay bo`lgan tilni tanlang!\n🇷🇺 Выберите язык, на котором вам комфортно общаться";
    //         $sms->keyboard = $lang;
    //         $sms->sendMessage();

    //     } else {

    //         $sql->SetTable("step");
    //         $result = $sql->select("*","chat_id = $chat_id");
    //         $step_1 = 0;
    //         $step_2 = 0;
    //         if ($result->num_rows > 0) {
    //             $row = $result->fetch_assoc();
    //             $step_1 = $row["step_1"];
    //             $step_2 = $row["step_2"];
    //         }
    //         if ($step_1 == 1) {
    //             $serviceType = "Hizmatlardan foydalanish 👨‍🔧";
    //             $service = "Hizmat Ko`rsatish 👷‍♂️";
    //             $addFriends = "Do`stlarimni Hizmatlarini qo`shish";
    //             $myCabinet = "Shaxsiy ma`lumotlar 👤";
    //             $txtSend = "A'zo bo'lishdan maqsadingiz ❓";
    //             $controlWorkers = "Hizmatchilar nazorati!";
    //             $controlList = "E'lonlar nazorati!";
    //             $controlService = "Kasblar nazorati!";
    //             $blocks = "Block qilinganlar!";
    //             $sendMessages = "Habarlarni yuborish! ✉️";
    //         } else if ($step_1 == 2) {
    //             $txtSend = "Ваша цель - стать участником ❓";
    //             $serviceType = "Использование услуг 👨‍🔧";
    //             $service = "Сервис 👷‍♂️";
    //             $addFriends = "Добавить сервисы моих друзей ➕";
    //             $myCabinet = "Личная информация 👤";
    //             $controlWorkers = "Персональный контроль!";
    //             $sendMessages = "Отправляйте сообщения! ✉️";
    //             $controlList = "Контроль рекламы!";
    //             $controlService = "Производственный контроль!";
    //             $blocks = "Заблокированные!";
               
    //         } else if ($step_1 == 3) {
    //             $txtSend = "Аъзо бўлишдан мақсадингиз ❓";
    //             $serviceType = "Ҳизматлардан фойдаланиш 👨‍🔧";
    //             $service = "Ҳизмат Кўрсатиш 👷‍♂️";
    //             $addFriends = "Дўстларимни Ҳизматларини қўшиш ➕";
    //             $myCabinet = "Шахсий маълумотлар 👤";
    //             $controlWorkers = "Ҳизматчилар назорати!";
    //             $sendMessages = "Ҳабарларни юбориш! ✉️";
    //             $controlList = "Эълонлар назорати!";
    //             $controlService = "Касблар назорати!";
    //             $blocks = "Блоcк қилинганлар!";
    //         }
    //         if (!in_array($chat_id, $admin)) {

    //             $menu = json_encode([
    //                 'inline_keyboard' =>[
    //                     [
    //                         ['callback_data'=>"service_choose",'text'=> $serviceType]
    //                     ],
    //                     [
    //                         ['callback_data'=>"DoService",'text'=> $service]
    //                     ],
    //                     [
    //                         ['callback_data' => "my_cabinet",'text'=>$myCabinet]
                            
    //                     ],
    //                     [
    //                         ['callback_data' => "addMyFriends",'text'=>$addFriends]   
    //                     ],
    //                 ]
    //             ]);
    //         } else if (in_array($chat_id, $admin)) {
    //             $sql->SetTable("type_of_work");
    //             $sql->delete("status = 0");
    //             $menu = json_encode([
    //                 'inline_keyboard' =>[
    //                     [
    //                         ['callback_data'=>"service_choose",'text'=> $serviceType]
    //                     ],
    //                     [
    //                         ['callback_data'=>"DoService",'text'=> $service]
    //                     ],
    //                     [
    //                         ['callback_data' => "my_cabinet",'text'=>$myCabinet]
    //                     ],
    //                     [
    //                         ['callback_data' => "addMyFriends",'text'=>$addFriends]   
    //                     ],
    //                     [
    //                         ['callback_data' => "controlWorkers",'text'=>$controlWorkers]   
    //                     ],
    //                     [
    //                         ['callback_data' => "controlList",'text'=>$controlList]   
    //                     ],
    //                     [
    //                         ['callback_data' => "controlService",'text'=>$controlService]   
    //                     ],
    //                     [
    //                         ['callback_data' => "blockUser",'text'=>$blocks]   
    //                     ],
    //                     [
    //                         ['callback_data' => "sendMessages",'text'=>$sendMessages]   
    //                     ]
    //                 ]
    //             ]);
    //         }
    //         $sql->updateStep("step_2 = 0","chat_id = $chat_id");

    //         $sms = new Natfication();
    //         $sms->chat_id = $chat_id;
    //         $sms->keyboard = $menu;

    //         $sms->text = $txtSend;
    //         $sms->sendMessage();
    //         die();
    //     }
    //     $sql->SetTable("about_user");
    //     $sql->delete("status = 0 AND chat_id = $chat_id");
    // }

// =============================================================================== TAKE STEPS


?>