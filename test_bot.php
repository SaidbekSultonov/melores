<?php 
 // bunga tema Abdulaziz ---
  date_default_timezone_set('Asia/Tashkent');
  // ---


  $conn = pg_connect("host=localhost dbname=orginal_db user=postgres password=postgres");
  if ($conn) {
     echo "Success";
  }

		$sql = "SELECT * FROM users WHERE type = 1";
		$result = pg_query($conn,$sql);
		$admins = [];
		if (pg_num_rows($result) > 0) {
		while ($row = pg_fetch_assoc($result)) {
		$admins[] = $row["chat_id"];
		}
		}

		foreach ($admins as $key => $value) {
			echo $value;
			echo "<br>";
		}




  



//   const TOKEN = '1846722404:AAG-i0MGjynmwIzHJZD8uUO_vNFlNvMA3mo';
//   const BASE_URL = 'https://api.telegram.org/bot'.TOKEN;
//   // ===================================================================== FUNCTIONS AND CLASSES
//     function bot($method, $data = []) {
//        $url = BASE_URL.'/'.$method;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        $res = curl_exec($ch);
       
//        if(curl_error($ch))
//        {
//         var_dump(curl_error($ch));   
//        }
//        else{
//            return json_decode($res);
//        }
//     }

//     // ob_start();

//     function typing($ch) {
//        return bot('sendChatAction',[
//            'chat_id' => $ch,
//            'action' => 'typing'
//            ]);
//     }

//     function differenceInHours($startdate,$enddate){
//     $starttimestamp = strtotime($startdate);
//     $endtimestamp = strtotime($enddate);
//     $difference = abs($endtimestamp - $starttimestamp)/3600;
//     return $difference;
//     }


//     $update = file_get_contents('php://input');
//     $update = json_decode($update);
//     $message_id = "";
//     $text = "";
//     $chat_id = "";
//     $name = "";
//     $username = "";
//     $surname = "";
//     $data = "";
//     $audio = "";
//     if (isset($update->message)) {
//       $message = $update->message;
//       $message_id = $message->message_id;
//       $text = $message->text;
//       $chat_id = $message->chat->id;
//       $name = $message->chat->first_name;
//       $username = $message->chat->username;
//       $audio = $message->audio->file_id;
//       if(isset($message->chat->last_name)){
//          $surname = $message->chat->last_name;
//       }
//     } else if (isset($update->callback_query)) {
//       $data = $update->callback_query->data;
//       $chat_id = $update->callback_query->message->chat->id;
//       $message_id = $update->callback_query->message->message_id;
//     }

//     if (isset($text)) {
//       typing($chat_id);       
//     }
//     $txtArr = explode(" ", $text);
//     $number = 'xatolik';
//     if (!empty($txtArr)) {
//       $start = (isset($txtArr[0])) ? $txtArr[0] : ' xatolik';
//       $number = (isset($txtArr[1])) ? $txtArr[1] : ' xatolik';
//     }

// // ============================================================================================ TEXT ACTIONS
   
//    $sqlDelete = "SELECT * FROM delete_messages WHERE chat_id = ".$chat_id." ORDER BY id DESC";
//    $resultDelete = pg_query($conn,$sqlDelete);
//    if (pg_num_rows($resultDelete) > 0) {
//        while ($rowDelete = pg_fetch_assoc($resultDelete)) {
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $rowDelete["message_id"]
//            ]);
//        }
//    }
//    $drop = "DELETE FROM delete_messages where chat_id = ".$chat_id;
//    pg_query($conn,$drop);
//    if ($text == "/start " . $number) { // birinchi startga tekshiriladi keyin ichida referal linkga
//        $sql = "select * from users where status = 0 and phone_number = '".$number."'";
//        $result = pg_query($conn,$sql);
//        $sqlTwo = "select * from users where status = 1 and phone_number = '".$number."' and chat_id = ".$chat_id;
//        $resultTwo = pg_query($conn,$sqlTwo);
//        if (pg_num_rows($result) > 0) {
           
//            $sql = "update users set name = '".$name."', username = '".$username."', chat_id = ".$chat_id. " where status = 0 and phone_number = '".$number."'";
          
//            $result = pg_query($conn,$sql);
//            $sqlStep = "select * from step_order where chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlStep);
//            if (!pg_num_rows($resultStep) > 0) {
//                $sqlInsertStep = "insert into step_order (chat_id,step_1,step_2) values (".$chat_id.",0,0)";
//                pg_query($sqlInsertStep);
//                $sqlInsertLastId = "insert into last_id_order (chat_id,last_id) values (".$chat_id.",0)";
//                pg_query($conn,$sqlInsertLastId);
//            } 
//            $replyMarkup = array(
//                'keyboard' => array(
//                    array(
//                        array( 
//                              'text'=>"📞Telefon raqam qoldirish",
//                              'request_contact'=>true
//                            )
//                        ),
//                    ),
//                'one_time_keyboard' => true,
//                'resize_keyboard' => true
//            );
//            $encodedMarkup = json_encode($replyMarkup);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Iltimos teleforn raqamingizni kiriting!",
//                'reply_markup' => $encodedMarkup
//            ]);
//        } else if (pg_num_rows($resultTwo) > 0) {
//            $row = pg_fetch_assoc($resultTwo);
//            $type = $row["type"];
//            if ($type == 1) {
//             $bekor = json_encode($bekor = [
//               'keyboard' => [
//                 [
//                   ["text" => "Barcha buyurtmalar"],
//                   ["text" => "Mijozlar qo`shish ➕"]
//                 ],
//                 [
//                   ["text"=>"Mening buyurtmalarim"],
//                   ["text"=>"✅ Bitgan buyurtmalar"]
//                 ]
//               ],
//               'resize_keyboard' => true
//             ]);
//            } else if ($type == 2 or $type == 4) {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        [
//                            ["text" => "Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"]
//                        ],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            } else {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        ["Barcha buyurtmalar"],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            }
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Orginal-Mebel botiga xush kelibsiz!",
//                'reply_markup' => $bekor
//            ]);
//            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//            pg_query($conn,$sqlUpdate);
//        } else {
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Url link notogri"
//            ]);
//        }
//    } else if ($text == "/start") {
//        // $sql = Users::find()->where(["chat_id" => $chat_id,"status" => 1])->one();
//        $sql = "select * from users where status = 1 and chat_id = ".$chat_id;
//        $result = pg_query($conn,$sql);
//        if (pg_num_rows($result) > 0) {
//            $row = pg_fetch_assoc($result);
//            $type = $row["type"];
//            if ($type == 1) {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                       [
//                         ["text" => "Barcha buyurtmalar"],
//                         ["text" => "Mijozlar qo`shish ➕"]
//                       ],
//                       [
//                         ["text"=>"Mening buyurtmalarim"],
//                         ["text"=>"✅ Bitgan buyurtmalar"]
//                       ]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            } else if ($type == 1 or $type == 2 or $type == 4) {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        [
//                            ["text" => "Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"]
//                        ],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            } else {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        ["Barcha buyurtmalar"],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            }
//            $sqlStep = "select * from step_order where chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlStep);
//            if (!pg_num_rows($resultStep) > 0) {
//                $sqlInsertStep = "insert into step_order (chat_id,step_1,step_2) values (".$chat_id.",0,0)";
//                pg_query($sqlInsertStep);
//                $sqlInsertLastId = "insert into last_id_order (chat_id,last_id) values (".$chat_id.",0)";
//                pg_query($conn,$sqlInsertLastId);
//            } 
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Orginal-Mebel botiga xush kelibsiz!",
//                'reply_markup' => $bekor
//            ]);
//            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//            pg_query($conn,$sqlUpdate);
//        } else {
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Siz bot faoliyatidan foydalana olmaysiz!\n"
//            ]);
//        }
//        $sqlDrop = "delete from clients where status = 0 and chat_id = ".$chat_id;
//        pg_query($conn,$sqlDrop);
//    }   
//    $remove_keyboard = array(
//        'remove_keyboard' => true
//    );
//    $sqlUpdate = "select * from last_id_order where chat_id = ".$chat_id;
//    $resultSql = pg_query($conn,$sqlUpdate);
//    if (pg_num_rows($resultSql) > 0) {
//        $row = pg_fetch_assoc($resultSql);
//        $deleteId = $row["message_id"];
//        bot('deleteMessage',[
//            'chat_id' => $chat_id,
//            'message_id' => $deleteId
//        ]);
//    } 
  
//    $remove_keyboard = json_encode($remove_keyboard);
//    $sqlStep =  "SELECT * FROM step_order WHERE chat_id = ".$chat_id;
//    $result = pg_query($conn,$sqlStep);
//     if (pg_num_rows($result) > 0) {
//       $row = pg_fetch_assoc($result);
//       $step_1 = (isset($row["step_1"])) ? $row["step_1"] : ' xatolik';
//       $step_2 = (isset($row["step_2"])) ? $row["step_2"]: ' xatolik';    
//     }
//    $menu = json_encode([
//        'keyboard' => [
//            ["🏘 Bosh menu"]
//        ],
//        'resize_keyboard' =>true,
//    ]);
//    $menuAndback = json_encode([
//        'keyboard' => [
//            [
//                ['text' => "⬅️ Ortga"],['text' => "🏘 Bosh menu"]
//            ]
//        ],
//        'resize_keyboard' =>true,
//    ]);
//    $OnlyBack = json_encode([
//        'keyboard' => [
//            ["⬅️ Ortga"]
//        ],
//        'resize_keyboard' =>true,
//    ]);
//    if ($text == "🏘 Bosh menu") {
//        $sql = "select * from users where status = 1 and chat_id = ".$chat_id;
//        $result = pg_query($conn,$sql);
//        if (pg_num_rows($result) > 0) {
//            $row = pg_fetch_assoc($result);
//            $type = $row["type"];
//            if ($type == 1 ) {
//               $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        [
//                            ["text" => "Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"],
//                        ],
//                        [
//                            ["text" => "✅ Bitgan buyurtmalar"],
//                            ["text" => "Mening buyurtmalarim"],
//                        ]
//                     ],
//                    'resize_keyboard' => true
//                ]);
//            } else if ($type == 2 or $type == 4) {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        [
//                            ["text" => "Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"],
//                        ],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            } else {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        ["Barcha buyurtmalar"],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            }
//            $sqlStep = "select * from step_order where chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlStep);
//            if (!pg_num_rows($resultStep) > 0) {
//                $sqlInsertStep = "insert into step_order (chat_id,step_1,step_2) values (".$chat_id.",0,0)";
//                pg_query($sqlInsertStep);
//                $sqlInsertLastId = "insert into last_id_order (chat_id,last_id) values (".$chat_id.",0)";
//                pg_query($conn,$sqlInsertLastId);
//            } else {
//                $sqlUpdate = "update step_order step_2 = 0 where = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate); 
//            }
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Orginal-Mebel botiga xush kelibsiz!",
//                'reply_markup' => $bekor
//            ]);
//            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//            pg_query($conn,$sqlUpdate);
//        } 
//        $sqlDrop = "delete from clients where status = 0 and chat_id = ".$chat_id;
//        pg_query($conn,$sqlDrop);
//    }
//    if ($step_1 == 0 and $text != "/start") {
//        if (isset($update->message->contact)) {
//            $number = $update->message->contact->phone_number;
//            $number = str_replace("+","",$number);
           
//            // $sql = Users::find()->where(["chat_id" => $chat_id,"phone_number" => $number])->one();
//            $sql = "select * from users where status = 0 and chat_id = ".$chat_id." and phone_number = '".$number."'";
//            $result = pg_query($conn,$sql);
//            if (pg_num_rows($result) > 0) {
//                $sqlUpdate = "update users set status = 1 where chat_id = ".$chat_id." and phone_number = '".$number."' ";
//                pg_query($conn,$sqlUpdate);
//                $row = pg_fetch_assoc($result);
//                $type = $row["type"];
//                if ($type == 1 or $type == 2 or $type == 4) {
//                    $bekor = json_encode($bekor = [
//                        'keyboard' => [
//                            [
//                                ["text" => "Barcha buyurtmalar"],
//                                ["text" => "Mijozlar qo`shish ➕"]
//                            ]
//                        ],
//                        'resize_keyboard' => true
//                    ]);
//                } else {
//                    $bekor = json_encode($bekor = [
//                        'keyboard' => [
//                            ["Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"]
//                        ],
//                        'resize_keyboard' => true
//                    ]);
//                }
               
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Tekshiruvdan muvaffaqiyatli o`tingiz!",
//                    'reply_markup' => $bekor
//                ]);
//                $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate); 
//            } else {
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Siz bu raqam orqali yuqoridagi linkni ishlata olmaysiz"
//                ]);
//            }
//        }    
//    }
//    if ($step_2 == 1 and $text != "/start"){
       
//       if ($text == "Barcha buyurtmalar") {
//          // $user_title = Branch::find()->where(["status" => 1])->all();
//          $sql = "select * from branch where status = 1";
//          $result = pg_query($conn,$sql);
//          $arr_uz = [];
//          $row_arr = [];
//          while ($value = pg_fetch_assoc($result)) {
//              $branch_id = $value['id'];
//              $branchTitle = $value['title'];
//              $datacl = $branch_id."_branch"; 
//              $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $datacl];
//              $row_arr[] = $arr_uz;
//              $arr_uz = [];
//          }
//          $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//          $btnKey = json_encode(['inline_keyboard' => $row_arr]);
//          $getResult = bot('sendMessage',[
//              'chat_id' => $chat_id,
//              'text' => "Filiallar bilan tanishing",
//              'reply_markup' => $remove_keyboard
//          ]);
//          $questionMessageId = $getResult->result->message_id;
//          $sqlUpdate = "update last_id_order set message_id = ".$questionMessageId." where chat_id =  ".$chat_id;
//          $resultUpdate = pg_query($conn,$sqlUpdate);
//          bot('sendMessage',[
//              'chat_id' => $chat_id,
//              'text' => "<b>Filiallardan birini tanlang!</b>",
//              'parse_mode' => 'html',
//              'reply_markup' => $btnKey
//          ]);
//          $sqlUpdate = "update step_order set step_2 = 6 where chat_id = ".$chat_id;
//          $result = pg_query($conn,$sqlUpdate); 
//       } else if ($text == "Mijozlar qo`shish ➕") {
//          $sqlUpdate = "update step_order set step_2 = 2 where chat_id = ".$chat_id;
//          $result = pg_query($conn,$sqlUpdate); 
//          $sql = "select * from branch where status = 1";
//          $result = pg_query($conn,$sql);
//          $arr_uz = [];
//          $row_arr = [];
//          while ($value = pg_fetch_assoc($result)) {
//              $branch_id = $value['id'];
//              $branchTitle = $value['title'];
//              $datacl = $branch_id."_branch"; 
//              $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $datacl];
//              $row_arr[] = $arr_uz;
//              $arr_uz = [];
//          }
//          $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//          $btnKey = json_encode(['inline_keyboard' => $row_arr]);
//          bot('sendMessage',[
//              'chat_id' => $chat_id,
//              'text' => "Filiallar bilan tanishing",
//              'reply_markup' => $remove_keyboard
//          ]);
//          bot('sendMessage',[
//              'chat_id' => $chat_id,
//              'text' => "<b>Filiallardan birini tanlang!</b>",
//              'parse_mode' => 'html',
//              'reply_markup' => $btnKey
//          ]);
//       } else if ($text == "Mening buyurtmalarim") {
//          $sql = "select 
//                      o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
//                      s.title as section_title , os.deadline as deadline_of_section,
//                      b.title as branch_title , us.role , o.description, ctg.title as category_name
//                      from users as u
//                      inner join users_section as us on u.id = us.user_id
//                      inner join sections as s on us.section_id = s.id
//                      inner join section_orders as so on s.id = so.section_id
//                      inner join orders as o on so.order_id = o.id
//                      inner join order_step as os on o.id = os.order_id and s.id = os.section_id
//                      inner join branch as b on o.branch_id = b.id    
//                      inner join clients as cl on o.client_id = cl.id
//                      inner join order_categories as oc on o.id = oc.order_id
//                      inner join category as ctg on oc.category_id = ctg.id
//                  where o.pause = 0 and u.chat_id = ".$chat_id." and so.exit_date is null";
                 
//          $result = pg_query($conn,$sql);
//          if (pg_num_rows($result)) {
//              while ($row = pg_fetch_assoc($result)) {
//                  $title  = $row["title"];
//                  $section_id  = $row["section_id"];
//                  $endDate = $row["dead_line"];
//                  $stepOrderDeadline = $row["deadline_of_section"];
//                  $crDate = $row["created_date"];
//                  $sectionTitle = $row["section_title"];
//                  $branch_title = $row["branch_title"];
//                  $category_name = $row["category_name"];
//                  $order_user_id = $row["order_user_id"];
//                  $section_user_id = $row["section_user_id"];
//                  $crDateNew = date("Y-m-d H:i",strtotime(date($crDate)));
//                  $endDateNew = date("Y-m-d H:i",strtotime(date($endDate)));
//                  $stepOrderDeadlineNew = date("Y-m-d H:i",strtotime(date($stepOrderDeadline)));
//                  $phone_number = base64_decode($row["phone_number"]);
//                  $clientName = base64_decode($row["full_name"]);
//                  $camment = $row["description"];
//                  if (!empty($camment)) {
//                      $camment = $camment;
//                  } else {
//                      $camment = "";
//                  }
//                  $role = $row["role"];
//                  $dataInfo  = $row["id"]."_info";
//                  $dataAdd  = $row["id"]."_addWorker";
//                  $dataReady  = $row["id"]."_ready_".$section_id;
              
//                  if (($role == 5 or $role == 6) and $section_id != 35) {
//                      $readyInfoBtn = json_encode([
//                          'inline_keyboard' =>[
//                              [
//                                  ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                 
//                              ],
//                          ]
//                      ]);
//                  } else if ($section_id == 35 and $role == 5) {
//                      $readyInfoBtn = json_encode([
//                          'inline_keyboard' =>[
//                              [
//                                  ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data' => $dataAdd,'text'=>"➕ Ishchi Biriktirish"]
//                              ],
//                              [  
//                               ['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
//                            ]
//                          ]
//                      ]);
//                  } else {
//                      $readyInfoBtn = json_encode([
//                          'inline_keyboard' =>[
//                              [
//                                  ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                                 
//                              ],
//                          ]
//                      ]);   
//                  }
                 
                 
//                  $txtSend = "#".$title." \n👨‍💻 Buyurtmachi: <b>".$clientName."</b>\n🏢 Buyurtma filiali :<b>".$branch_title."</b>\n🕓 Boshlangan Vaqti: <b>".$crDateNew."</b>\n⏳ Tugash vaqti: <b>".$endDateNew."</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle."</b>\n⏳ Bo'limdan chiqish vaqti: <b>".$stepOrderDeadlineNew."</b>\n⚒ Kategoriya: <b>".$category_name."</b>\n💬 Izoh:<b> ".$camment."</b>";
//                  $getResult =  bot('sendMessage',[
//                      'chat_id' => $chat_id,
//                      'text' => $txtSend,
//                      'parse_mode' => "html",
//                      'reply_markup' => $readyInfoBtn
//                  ]);
//                  $deleteMessageId = $getResult->result->message_id;
//                  $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//                  pg_query($conn,$sqlGo);
//              }
//          }
         
//          $getResult = bot('sendMessage',[
//              'chat_id' => $chat_id,
//              'text' => "Buyurtmalar barchasi shulardan iborat",
//              'reply_markup' => $menu
//          ]);
//          $deleteMessageId = $getResult->result->message_id;
//          $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//          pg_query($conn,$sqlGo);
//          $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = ".$chat_id;
//          $result = pg_query($conn,$sqlUpdate);
//       } else if ($text == "✅ Bitgan buyurtmalar") {
//         $sql = "select * from branch where status = 1";
//         $result = pg_query($conn,$sql);
//         $arr_uz = [];
//         $row_arr = [];
//         while ($value = pg_fetch_assoc($result)) {
//            $branch_id = $value['id'];
//            $branchTitle = $value['title'];
//            $datacl = $branch_id."_branch"; 
//            $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $datacl];
//            $row_arr[] = $arr_uz;
//            $arr_uz = [];
//         }
//         $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//         $btnKey = json_encode(['inline_keyboard' => $row_arr]);
//         $getResult = bot('sendMessage',[
//            'chat_id' => $chat_id,
//            'text' => "Filiallar bilan tanishing",
//            'reply_markup' => $remove_keyboard
//         ]);
//         $questionMessageId = $getResult->result->message_id;
//         $sqlUpdate = "update last_id_order set message_id = ".$questionMessageId." where chat_id =  ".$chat_id;
//         $resultUpdate = pg_query($conn,$sqlUpdate);
//         bot('sendMessage',[
//            'chat_id' => $chat_id,
//            'text' => "<b>Filiallardan birini tanlang!</b>",
//            'parse_mode' => 'html',
//            'reply_markup' => $btnKey
//         ]);
//         $sqlUpdate = "update step_order set step_2 = 200 where chat_id = ".$chat_id;
//         $result = pg_query($conn,$sqlUpdate); 
//       }
//    }
//    if ($step_2 == 3 and $text != "/start") {
//        if (isset($text)) {
//            $txt = base64_encode($text);
//            $sqlUpdate = "update clients set full_name = '".$txt."' where status = 0 and chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate);
//            // $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
//            $sqlUpdate = "update step_order set step_2 = 4 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate); 
           
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Iltimos Buyurtma beruvchini Telefon raqamini kiritng!\n<b>(Masalan: 998941234567)</b>",
//                'parse_mode' => "html"
//            ]);
//        }
//    }
//    if ($step_2 == 4 and $text != "/start") {
//        if (preg_match("/^[0-9+]*$/",$text)) {
//            $countNumber = strlen($text);
//            if($countNumber == 12) {
//                $txt = base64_encode($text);
//                $sqlUpdate = "update clients set phone_number = '".$txt."' where status = 0 and chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate);
           
//                $sqlUpdate = "update step_order set step_2 = 5 where chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate); 
//                $sql = "select * from clients as cl inner join branch as b on cl.branch_id = b.id where cl.status = 0 and cl.chat_id = ".$chat_id;
//                $result = pg_query($conn,$sql);
//                if (pg_num_rows($result) > 0) {
//                    $row = pg_fetch_assoc($result);
//                    $name = base64_decode($row["full_name"]);
//                    $phone_number = base64_decode($row["phone_number"]);
//                    $branchTitle = $row["title"];
//                    $txtSend = "Malumotlarni tasdiqlang:\nIsmi ".$name."\nRaqami: ".$phone_number."\nFiliali: ".$branchTitle;
//                    $deleteAndCheckBtn = json_encode([
//                        'inline_keyboard' =>[
//                            [
//                                ['callback_data' => "cancel",'text'=>"❌ Bekor qilish"],['callback_data'=> "confirm",'text'=>"✅ Tasdiqlash"]
                               
//                            ]
//                        ]
//                    ]);
                 
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => $txtSend,
//                        'reply_markup' => $deleteAndCheckBtn
//                    ]);     
//                } else {
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => "xatolik"
//                    ]);
//                }
//            } else {
//                $txtSend = "Iltimos telefon raqamni to`g`ri kiritng!\nMasalan: 998941234567";
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => $txtSend
//                ]);       
//            }
//        } else {
//            $txtSend = "Iltimos telefon raqamni to`g`ri kiritng!\nMasalan: 998941234567";
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => $txtSend
//            ]);       
//        }
//    } 
   
//    if ($step_2 == 10 and $text != "/start" and $text != "🏘 Bosh menu") {
//        if ($text == "⬅️ Ortga") {
//            $sql = "select 
//                        o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
//                        s.title as section_title , os.deadline as deadline_of_section,
//                        b.title as branch_title , us.role , o.description , ctg.title as category_name
//                        from users as u
//                        inner join users_section as us on u.id = us.user_id
//                        inner join sections as s on us.section_id = s.id
//                        inner join section_orders as so on s.id = so.section_id
//                        inner join orders as o on so.order_id = o.id
//                        inner join order_step as os on o.id = os.order_id and s.id = os.section_id
//                        inner join branch as b on o.branch_id = b.id    
//                        inner join clients as cl on o.client_id = cl.id
//                        inner join order_categories as oc on o.id = oc.order_id
//                        inner join category as ctg on oc.category_id = ctg.id
//                    where o.pause = 0 u.chat_id = ".$chat_id." and so.exit_date is null";
                   
//            $result = pg_query($conn,$sql);
//            if (pg_num_rows($result)) {
//                while ($row = pg_fetch_assoc($result)) {
//                    $title  = $row["title"];
//                    $section_id  = $row["section_id"];
//                    $endDate = $row["dead_line"];
//                    $stepOrderDeadline = $row["deadline_of_section"];
//                    $crDate = $row["created_date"];
//                    $sectionTitle = $row["section_title"];
//                    $branch_title = $row["branch_title"];
//                    $category_name = $row["category_name"];
//                    $order_user_id = $row["order_user_id"];
//                    $section_user_id = $row["section_user_id"];
//                    $crDateNew = date("Y-m-d H:i",strtotime(date($crDate)));
//                    $endDateNew = date("Y-m-d H:i",strtotime(date($endDate)));
//                    $stepOrderDeadlineNew = date("Y-m-d H:i",strtotime(date($stepOrderDeadline)));
//                    $phone_number = base64_decode($row["phone_number"]);
//                    $clientName = base64_decode($row["full_name"]);
//                    $camment = $row["description"];
//                    if (!empty($camment)) {
//                        $camment = $camment;
//                    } else {
//                        $camment = "";
//                    }
//                    $role = $row["role"];
//                    $dataInfo  = $row["id"]."_info";
//                    $dataAdd  = $row["id"]."_addWorker";
//                    $dataReady  = $row["id"]."_ready_".$section_id;
                
//                    if (($role == 5 or $role == 6) and $section_id != 35 or ($section_user_id == $order_user_id)) {
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                   
//                                ],
//                            ]
//                        ]);
//                    } else if ($section_id == 35 and $order_user_id == 1) {
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data' => $dataAdd,'text'=>"➕ Ishchi Biriktirish"]
                                   
//                                ],
//                            ]
//                        ]);
//                    } else {
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                                   
//                                ],
//                            ]
//                        ]);   
//                    }
//                    $txtSend = "#".$title." \n👨‍💻 Buyurtmachi: <b>".$clientName."</b>\n🏢 Buyurtma filiali :<b>".$branch_title."</b>\n🕓 Boshlangan Vaqti: <b>".$crDateNew."</b>\n⏳ Tugash vaqti: <b>".$endDateNew."</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle."</b>\n⏳ Bo'limdan chiqish vaqti: <b>".$stepOrderDeadlineNew."</b>\n⚒ Kategoriya: <b>".$category_name."</b>\n💬 Izoh:<b> ".$camment."</b>";
//                    $getResult =  bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => $txtSend,
//                        'parse_mode' => "html",
//                        'reply_markup' => $readyInfoBtn
//                    ]);
//                    $deleteMessageId = $getResult->result->message_id;
//                    $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//                    pg_query($conn,$sqlGo);
//                }
//            }
           
//            $getResult = bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Buyurtmalar barchasi shulardan iborat",
//                'reply_markup' => $menu
//            ]);
//            $deleteMessageId = $getResult->result->message_id;
//            $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//            pg_query($conn,$sqlGo);
//            $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate);
//        } else {
//            if (!isset($update->callback_query)) {
//                $sqlLastOrder = "select us.id , lio.last_id from users as us
//                        inner join last_id_order as lio on us.chat_id = lio.chat_id
//                        where us.chat_id = ".$chat_id;
                  
//                $resultOrderLast = pg_query($conn,$sqlLastOrder);
//                if (pg_num_rows($resultOrderLast) > 0) {
//                    $row = pg_fetch_assoc($resultOrderLast);
//                    $userId = $row["id"];
//                    $orderId = $row["last_id"];
//                }
//                $sqlUsers = "SELECT us.role FROM section_orders as so
//                                inner join users_section as us on so.section_id = us.section_id
//                                inner join users as u on us.user_id = u.id
//                            WHERE so.order_id = ".$orderId." and so.exit_date is null and u.chat_id = ".$chat_id;
//                $resultStep = pg_query($conn,$sqlUsers);
//                if (pg_num_rows($resultStep) > 0) {
//                    $row = pg_fetch_assoc($resultStep);
//                    $role = $row["role"];
//                }
//                if ($role == 6) {
//                    if (isset($message->text) and $text != "⬅️ Ortga") {
//                        $file_id = base64_encode($text);
//                        $type = "text";
//                        $caption = base64_encode($text);
//                    }
//                    if (isset($message->photo)) {
//                        if(isset($message->photo[2])) {
//                            $file_id = $message->photo[2]->file_id;
//                        } else if (isset($message->photo[1])) {
//                            $file_id = $message->photo[1]->file_id;
//                        } else { 
//                            $file_id = $message->photo[0]->file_id;
//                        }
//                        $caption = base64_encode($message->caption);
//                        $type = "photo";
//                    }
            
//                    if (isset($message->video)) {
//                        $file_id = $message->video->file_id;
//                        $caption = base64_encode($message->caption);
//                        $type = "video";
//                    }
//                    if (isset($message->document)) {
//                        $file_id = $message->document->file_id;
//                        $caption = base64_encode($message->caption);
//                        $type = "document"; 
//                    }
//                    if (isset($message->video_note)) {
//                        $file_id = $message->video_note->file_id;
//                        $caption = "null";
//                        $type = "video_note";
//                    }
//                    if (isset($message->audio)) {
//                        $file_id = $message->audio->file_id;
//                        $caption = base64_encode($message->caption);
//                        $type = "audio";
//                    }
//                    if (isset($message->voice)) {
//                        $file_id = $message->voice->file_id;
//                        $caption = base64_encode($message->caption);
//                        $type = "voice";
//                    }
//                    $dateformat = date("Y-m-d H:i:s");
//                    $getResult = bot("copyMessage",[
//                        'chat_id' => -1001444743477,
//                        'from_chat_id' => $chat_id,
//                        'message_id' => $message_id
//                    ]);
//                    $questionMessageId = $getResult->result->message_id;
                 
//                    if (isset($message->location)) {
//                        $location = $message->location;
//                        $lang = $location->longitude;
//                        $lat  = $location->latitude;
//                        $type = "loc";
//                        $sqlMaterials = "insert into order_materials (long,lat,type,order_id,created_date,status,chat_id,copy_message_id,user_id) values ('".$lang."','".$lat."','".$type."',".$orderId.",'".$dateformat."',1,".$chat_id.",".$questionMessageId.",".$userId.")";
//                    }  else {
//                        $sqlMaterials = "insert into order_materials (file,type,order_id,created_date,status,chat_id,copy_message_id,user_id) values ('".$file_id."','".$type."',".$orderId.",'".$dateformat."',1,".$chat_id.",".$questionMessageId.",".$userId.")";
//                    }
                   
//                    $result = pg_query($conn,$sqlMaterials);
//                    if ($result == true) {
//                        $sql = "select * from orders where id = ".$orderId;
//                        $resultOrder = pg_query($conn,$sql);
//                        $row = pg_fetch_assoc($resultOrder);
//                        $order_name = $row["title"];
//                        $sqlUpdate = "update step_order set step_2 = 10 where chat_id = ".$chat_id;
//                        $result = pg_query($conn,$sqlUpdate);
//                        bot('sendMessage',[
//                            'chat_id' => $chat_id,
//                            'text' => "<b><i>".$order_name."</i></b> Yana ma'lumot qo'shishingiz mumkin!",
//                            'parse_mode' => "html",
//                            'reply_markup' => $OnlyBack
//                        ]);
                       
//                    }
//                } else {
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => "Siz buyurtmalarga ma'lumotlar kirita olmaysiz! 🚫"
//                    ]);
//                }
//            }
//        }
//    } 
//    // =============================================================== ADD REQUIRED MATERIALS
//    if ($step_2 == 15 and $text != "/start" and $text != "🏘 Bosh menu") {
//        if ($text == "⬅️ Ortga") {
//            $sql = "select 
//                        o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
//                        s.title as section_title , os.deadline as deadline_of_section,
//                        b.title as branch_title , us.role , o.description, ctg.title as category_name
//                        from users as u
//                        inner join users_section as us on u.id = us.user_id
//                        inner join sections as s on us.section_id = s.id
//                        inner join section_orders as so on s.id = so.section_id
//                        inner join orders as o on so.order_id = o.id
//                        inner join order_step as os on o.id = os.order_id and s.id = os.section_id
//                        inner join branch as b on o.branch_id = b.id    
//                        inner join clients as cl on o.client_id = cl.id 
//                        inner join order_categories as oc on o.id = oc.order_id
//                        inner join category as ctg on oc.category_id = ctg.id
//                    where o.pause = 0 and  u.chat_id = ".$chat_id." and so.exit_date is null";
                   
//            $result = pg_query($conn,$sql);
//            if (pg_num_rows($result)) {
//                while ($row = pg_fetch_assoc($result)) {
//                    $title  = $row["title"];
//                    $section_id  = $row["section_id"];
//                    $endDate = $row["dead_line"];
//                    $stepOrderDeadline = $row["deadline_of_section"];
//                    $crDate = $row["created_date"];
//                    $sectionTitle = $row["section_title"];
//                    $branch_title = $row["branch_title"];
//                    $category_name = $row["category_name"];
//                    $order_user_id = $row["order_user_id"];
//                    $section_user_id = $row["section_user_id"];
//                    $crDateNew = date("Y-m-d H:i",strtotime(date($crDate)));
//                    $endDateNew = date("Y-m-d H:i",strtotime(date($endDate)));
//                    $stepOrderDeadlineNew = date("Y-m-d H:i",strtotime(date($stepOrderDeadline)));
//                    $phone_number = base64_decode($row["phone_number"]);
//                    $clientName = base64_decode($row["full_name"]);
//                    $camment = $row["description"];
//                    if (!empty($camment)) {
//                        $camment = $camment;
//                    } else {
//                        $camment = "";
//                    }
//                    $role = $row["role"];
//                    $dataInfo  = $row["id"]."_info";
//                    $dataAdd  = $row["id"]."_addWorker";
//                    $dataReady  = $row["id"]."_ready_".$section_id;
                
//                    if (($role == 5 or $role == 6) and $section_id != 35 or ($section_user_id == $order_user_id)) {
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                   
//                                ],
//                            ]
//                        ]);
//                    } else if ($section_id == 35 and $order_user_id == 1) {
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data' => $dataAdd,'text'=>"➕ Ishchi Biriktirish"]
                                   
//                                ],
//                            ]
//                        ]);
//                    } else {
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                                   
//                                ],
//                            ]
//                        ]);   
//                    }
                   
                   
//                    $txtSend = "#".$title." \n👨‍💻 Buyurtmachi: <b>".$clientName."</b>\n🏢 Buyurtma filiali :<b>".$branch_title."</b>\n🕓 Boshlangan Vaqti: <b>".$crDateNew."</b>\n⏳ Tugash vaqti: <b>".$endDateNew."</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle."</b>\n⏳ Bo'limdan chiqish vaqti: <b>".$stepOrderDeadlineNew."</b>\n⚒ Kategoriya: <b>".$category_name."</b>\n💬 Izoh:<b> ".$camment."</b>";
//                    $getResult =  bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => $txtSend,
//                        'parse_mode' => "html",
//                        'reply_markup' => $readyInfoBtn
//                    ]);
//                    $deleteMessageId = $getResult->result->message_id;
//                    $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//                    pg_query($conn,$sqlGo);
//                }
//            }
           
//            $getResult = bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Buyurtmalar barchasi shulardan iborat",
//                'reply_markup' => $menu
//            ]);
//            $deleteMessageId = $getResult->result->message_id;
//            $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//            pg_query($conn,$sqlGo);
//            $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate);
//        } else {
//            if (!isset($update->callback_query)) {
//                if (isset($message->text)) {
//                    $file_id = base64_encode($text);
//                    $type = "text";
//                    $caption = base64_encode($text);
//                }
//                if (isset($message->photo)) {
//                    if(isset($message->photo[2])) {
//                        $file_id = $message->photo[2]->file_id;
//                    } else if (isset($message->photo[1])) {
//                        $file_id = $message->photo[1]->file_id;
//                    } else { 
//                        $file_id = $message->photo[0]->file_id;
//                    }
//                    $caption = base64_encode($message->caption);
//                    $type = "photo";
//                }
        
//                if (isset($message->video)) {
//                    $file_id = $message->video->file_id;
//                    $caption = base64_encode($message->caption);
//                    $type = "video";
//                }
//                if (isset($message->document)) {
//                    $file_id = $message->document->file_id;
//                    $caption = base64_encode($message->caption);
//                    $type = "document"; 
//                }
//                if (isset($message->video_note)) {
//                    $file_id = $message->video_note->file_id;
//                    $caption = "null";
//                    $type = "video_note";
//                }
//                if (isset($message->audio)) {
//                    $file_id = $message->audio->file_id;
//                    $caption = base64_encode($message->caption);
//                    $type = "audio";
//                }
//                if (isset($message->voice)) {
//                    $file_id = $message->voice->file_id;
//                    $caption = base64_encode($message->caption);
//                    $type = "voice";
//                }
//                $sqlLastOrder = "select us.id , lio.last_id,lio.type from users as us
//                    inner join last_id_order as lio on us.chat_id = lio.chat_id
//                    where us.chat_id = ".$chat_id;
//                $resultOrderLast = pg_query($conn,$sqlLastOrder);
//                if (pg_num_rows($resultOrderLast) > 0) {
//                    $row = pg_fetch_assoc($resultOrderLast);
//                    $MaterialId = $row["last_id"];
//                    $orderId = $row["type"];
//                }
             
//                $dateformat = date("Y-m-d H:i:s");
//                // $file_id = base64_encode($file_id);
//                $getResult = bot("copyMessage",[
//                    'chat_id' => -1001444743477,
//                    'from_chat_id' => $chat_id,
//                    'message_id' => $message_id
//                ]);
//                $questionMessageId = $getResult->result->message_id;
//                $sqlMaterials = "update required_material_order set status = 1, file = '".$file_id."', chat_id = ".$chat_id.", type = '".$type."',text = '".$caption."',message_id = ".$questionMessageId." where id = ".$MaterialId;
//                $result = pg_query($conn,$sqlMaterials);
//                $sqlOrderMaterials = "select * from required_material_order WHERE id = ".$MaterialId;
//                $resultOrderMaterials = pg_query($conn,$sqlOrderMaterials);
//                $sqlUser = "SELECT id FROM users where chat_id = ".$chat_id;
//                $resultUser = pg_query($conn,$sqlUser);
//                $rowUser = pg_fetch_assoc($resultUser);
//                $IdUser = $rowUser["id"];
//                if (pg_num_rows($resultOrderMaterials)  > 0) {
//                    $rowMaterials = pg_fetch_assoc($resultOrderMaterials);
//                    $file = $rowMaterials["file"];
//                    $type = $rowMaterials["type"];
//                    $order_idUser = $rowMaterials["order_id"];
//                    $status = 1;
//                    $created_date = date("Y-m-d H:s:i");
                   
//                    $sqlInsertOrder = "INSERT INTO order_materials (file,type,order_id,user_id,created_date,status,chat_id,copy_message_id) values('".$file."','".$type."',".$order_idUser.",".$IdUser.",'".$created_date."',".$status.",".$chat_id.",".$questionMessageId.")";
//                    pg_query($conn,$sqlInsertOrder);
//                }
//                $sqlGetRequiredMaterials = "SELECT r.title, o.title as order_name ,r.id FROM required_material_order as r 
//                                                INNER JOIN orders AS o ON r.order_id = o.id
//                                            WHERE r.status = 0 and r.order_id = ".$orderId." limit 1";
//                $resultRequiredMaterials = pg_query($conn,$sqlGetRequiredMaterials);
//                if (pg_num_rows($resultRequiredMaterials) > 0) {
//                    $rowRequiredMaterials = pg_fetch_assoc($resultRequiredMaterials);
//                    $idLast = $rowRequiredMaterials["id"];
//                    $order_name = $rowRequiredMaterials["order_name"];
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => "<b><i>".$order_name."</i></b> buyurtmasiga  <b>".$rowRequiredMaterials["title"] . "</b>ni kiriting!\n",
//                        'parse_mode' => "html",
//                        'reply_markup' => $OnlyBack
//                    ]);
//                    $sqlUpdateLastId = "update last_id_order set last_id = ".$idLast." where chat_id = ".$chat_id;
//                    $resultStep = pg_query($conn,$sqlUpdateLastId);
//                } else {
//                    $sql = "select * from orders where id = ".$orderId;
//                    $resultOrder = pg_query($conn,$sql);
//                    $row = pg_fetch_assoc($resultOrder);
//                    $order_name = $row["title"];
//                    $sqlUpdate = "update step_order set step_2 = 10 where chat_id = ".$chat_id;
//                    $result = pg_query($conn,$sqlUpdate);
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => "<b><i>".$order_name."</i></b> Yana ma'lumot qo'shishingiz mumkin!",
//                        'parse_mode' => "html",
//                        'reply_markup' => $OnlyBack
//                    ]);
                   
//                    $sqlUpdateLastId = "update last_id_order set last_id = ".$orderId." where chat_id = ".$chat_id;
//                    $resultStep = pg_query($conn,$sqlUpdateLastId);
//                }
//            }
//        }
//    }
//    if ($step_2 == 101 and $text != "/start" and $text != "🏘 Bosh menu") {
//        if ($text == "⬅️ Ortga") {
//            $sql = "select * from last_id_order WHERE chat_id = ".$chat_id;
//            $resultLastId = pg_query($conn,$sql);
//            if (pg_num_rows($resultLastId) > 0) {
//                $row = pg_fetch_assoc($resultLastId);
//                $last_id = $row["last_id"];
//            }
//            $sqlOrders = "select
//                    ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title
//                    from orders as ord
//                    inner join clients as cl on ord.client_id = cl.id
//                    inner join section_orders as so on ord.id = so.order_id
//                    inner join sections as sec on so.section_id = sec.id
//                    inner join order_categories as oc on ord.id = oc.order_id
//                    inner join category as ctg on oc.category_id = ctg.id
//                where ord.id = ".$last_id;
//            $resultOrders = pg_query($conn,$sqlOrders);
//            if (pg_num_rows($resultOrders) > 0 ) {
//                while ($row = pg_fetch_assoc($resultOrders)) {
//                    $title  = $row["title"];
//                    $endDate = $row["dead_line"];
//                    $crDate = $row["created_date"];
//                    $sectionTitle = $row["section_title"];
//                    $category_name = $row["category_name"];
//                    $phone_number = base64_decode($row["phone_number"]);
//                    $clientName = base64_decode($row["full_name"]);
                   
//                    $dataInfo  = $row["id"]."_info";
//                    $dataReady  = $row["id"]."_ready_".$keyThirst;
//                    $readyInfoBtn = json_encode([
//                        'inline_keyboard' =>[
//                            [
//                                ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                               
//                            ],
//                            [
//                                ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                               
//                            ]
//                        ]
//                    ]);
                   
//                    $txtSend = "#".$title." \nIsmi: <b>".$clientName."</b>\nBoshlangan Vaqti: 🕓 <b>".$crDate."</b>\nTugash vaqti: ⏳ <b>".$endDate."</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle."</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name."</b>\nTelefon raqami: 📞  +".$phone_number;
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'message_id' => $message_id,
//                        'text' => $txtSend,
//                        'parse_mode' => "html",
//                        'reply_markup' => $readyInfoBtn
//                    ]);
                       
//                }
//            } 
//            $sqlUpdate = "update step_order set step_2 = 100 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate);
//        }
//    }
// // =============================================================================================== CALLBACK ACTIONS
//   if (isset($update->callback_query)) {
//     $dataArr = explode("_", $data);
//     $key = (!empty($dataArr[0])) ? $dataArr[0] : ' xatolik';
//     $keySecond = (!empty($dataArr[1])) ? $dataArr[1] : ' xatolik';
//     $keyThirst = (!empty($dataArr[2])) ? $dataArr[2] : ' xatolik';
//     $branch = $key."_branch";
//     $category = $key."_category";
//     $clients = $key."_clients";
//     $info = $key."_info";
//     $ready = $key."_ready_".$keyThirst;
//     $realyDone = $key."_confirm_".$keyThirst;
//     $cancelDone = $key."_cancelReady_".$keyThirst;
//     $ordersdata = $key."_".$keySecond."_".$keyThirst."_orders";
//     $lookOrders  = $key."_lookOrders_".$keyThirst;      
//     $checkWorkersData  = $key."_workerId_".$keyThirst;
//     $checkWorkers = $key."_confirmWokers_".$keyThirst;
//     $cancelWorkers = $key."_cancelWorkers_".$keyThirst;
//     $addWorker = $key."_addWorker";
//     $nextClData = $key."_nextClient";
//     $backClData = $key."_backClient";
//     // bot('sendMessage',[
//     //   'chat_id' => $chat_id,
//     //   'text' => $data
//     // ]);
//     // die();
    
//     if ($data == "home") {
//        $sql = "select * from users where status = 1 and chat_id = ".$chat_id;
//        $result = pg_query($conn,$sql);
//        if (pg_num_rows($result) > 0) {
//            $row = pg_fetch_assoc($result);
//            $type = $row["type"];
//            if ($type == 1 ) {
//               $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        [
//                            ["text" => "Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"],
//                        ],
//                        [
//                            ["text" => "✅ Bitgan buyurtmalar"],
//                            ["text" => "Mening buyurtmalarim"],
//                        ]
//                     ],
//                    'resize_keyboard' => true
//                ]);
//            } else if ($type == 2 or $type == 4) {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        [
//                            ["text" => "Barcha buyurtmalar"],
//                            ["text" => "Mijozlar qo`shish ➕"],
//                        ],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            } else {
//                $bekor = json_encode($bekor = [
//                    'keyboard' => [
//                        ["Barcha buyurtmalar"],
//                        ["Mening buyurtmalarim"]
//                    ],
//                    'resize_keyboard' => true
//                ]);
//            }
//            $sqlStep = "select * from step_order where chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlStep);
//            if (!pg_num_rows($resultStep) > 0) {
//                $sqlInsertStep = "insert into step_order (chat_id,step_1,step_2) values (".$chat_id.",0,0)";
//                pg_query($sqlInsertStep);
//                $sqlInsertLastId = "insert into last_id_order (chat_id,last_id) values (".$chat_id.",0)";
//                pg_query($conn,$sqlInsertLastId);
//            } else {
//                $sqlUpdate = "update step_order step_2 = 0 where = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate); 
//            }
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Orginal Mebel botiga xush kelibsiz!",
//                'reply_markup' => $bekor
//            ]);
//            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//            pg_query($conn,$sqlUpdate);
//        } 
//        $sqlDrop = "delete from clients where status = 0 and chat_id = ".$chat_id;
//        pg_query($conn,$sqlDrop);
//     }
    
//     if ($step_2 == 2) {
//        if ($data == $branch) {
//            $sqlUpdate = "update step_order set step_2 = 3 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate); 
//            $sqlInsert = "insert into clients (chat_id,status,full_name,phone_number,branch_id) values (".$chat_id.",0,'test','12345',".$key.")";
//            $result = pg_query($conn,$sqlInsert);
           
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Iltimos Buyurtma beruvchini Ismini kiritng!"
//            ]);
//        }
//     }
    
//     if ($step_2 == 5) {
//        if ($data == "confirm") {
//            $sql = "update clients set status = 1 where status = 0 and chat_id = ".$chat_id;
//            $result = pg_query($conn,$sql);
//            $bekor = json_encode($bekor = [
//                'keyboard' => [
//                    [
//                        ["text" => "Barcha buyurtmalar"],
//                        ["text" => "Mijozlar qo`shish ➕"]
//                    ]
//                ],
//                'resize_keyboard' => true
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Foydalanuvchi muvaffaqiyatli qo`shildi!,Marhamat",
//                'reply_markup' => $bekor
//            ]);
//            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate); 
//        } 
//        if ($data == "cancel") {
//            $sqlDrop = "delete from clients status = 0 and chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlDrop);
//            $bekor = json_encode($bekor = [
//                'keyboard' => [
//                    [
//                        ["text" => "Barcha buyurtmalar"],
//                        ["text" => "Mijozlar qo`shish ➕"]
//                    ]
//                ],
//                'resize_keyboard' => true
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Foydalanuvchi Qo`shilmadi!,Marhamat",
//                'reply_markup' => $bekor
//            ]);
//            $sqlUpdate = "update step_order set step_2 = 1 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate); 
//        }
//     }
       
//     if ($step_2 == 6) {
//        if ($data == $branch) {
//           $sqlUpdateLast = "update last_id_order set order_type = 0 where chat_id = ".$chat_id;
//           $resultUpdateLast = pg_query($conn,$sqlUpdateLast);

//            $sqlUpdate = "update step_order set step_2 = 7 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate); 
     
//            $sql = "select * from clients where status = 1 and branch_id = ".$key." limit 20";
//            $result = pg_query($conn,$sql);
//            $sqlSecond = "select * from clients where status = 1 and branch_id = ".$key;
//            $resultSecond = pg_query($conn,$sqlSecond);
//            // $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
//            $arr_uz = [];
//            $row_arr = [];
//            if (pg_num_rows($result) > 0 ){
//                while ($value = pg_fetch_assoc($result)) {
//                    $client_id = $value['id'];
//                    $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = ".$client_id;
//                    $resultOrder = pg_query($conn,$sqlOrder);
//                    $branchTitle = base64_decode($value['full_name']);
//                    $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id."_clients"];
//                    $row_arr[] = $arr_uz;
//                    $arr_uz = [];
//                }
//                $nextClients = $client_id."_nextClient";
//                if (pg_num_rows($resultSecond) > 20) {
//                   $row_arr[] = [["text" => "➡️ Boshqa Mijozlar","callback_data" => $nextClients]];
//                }
//                $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            
//                bot('editMessageText',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id,
//                    'text' => "Mijozlardan birini tanlang!",
//                    'reply_markup' => $btnKey
//                ]);
//            } else {
//                bot('deleteMessage',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id
//                ]);
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
//                    'reply_markup' => $menu
//                ]);
//            }
//        }
//     }
    
//     if ($step_2 == 7) {
//       if ($data == $clients) {
//          $sqlOrderType = "SELECT * FROM last_id_order WHERE chat_id = ".$chat_id;
//          $resultOrderType = pg_query($conn,$sqlOrderType);
//          $rowOrderType = pg_fetch_assoc($resultOrderType);
//          $order_type = $rowOrderType["order_type"];
//          if($order_type == 1) {
//            $sqlOrders = "
//               select
//                 ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number, ctg.title as category_name
//               from orders as ord
//               inner join clients as cl on ord.client_id = cl.id
//               inner join section_orders as so on ord.id = so.order_id
//               inner join sections as sec on so.section_id = sec.id
//               inner join order_categories as oc on ord.id = oc.order_id
//               inner join category as ctg on oc.category_id = ctg.id
//               where ord.pause = 0 and ord.status != 0 and  ord.client_id = ".$key." group by 
//               ord.id,
//               ord.title,
//               ord.created_date,
//               ord.dead_line,
//               cl.full_name,
//               cl.phone_number,
//               ctg.title";  
//          } else if ($order_type == 0) {
//             $sqlOrders = "select
//                 ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number, ctg.title as category_name
//               from orders as ord
//               inner join clients as cl on ord.client_id = cl.id
//               inner join section_orders as so on ord.id = so.order_id
//               inner join sections as sec on so.section_id = sec.id
//               inner join order_categories as oc on ord.id = oc.order_id
//               inner join category as ctg on oc.category_id = ctg.id
//               where ord.pause = 0 and ord.status != 0 and  ord.client_id = ".$key." group by 
//               ord.id,
//               ord.title,
//               ord.created_date,
//               ord.dead_line,
//               cl.full_name,
//               cl.phone_number,
//               ctg.title";
//          }
//          $resultOrders = pg_query($conn,$sqlOrders);
//          if (pg_num_rows($resultOrders) > 0 ) {
//              while ($row = pg_fetch_assoc($resultOrders)) {

//                  $title  = $row["title"];
//                  $endDate = $row["dead_line"];
//                  $crDate = $row["created_date"];
//                  $category_name = $row["category_name"];
//                  $sectionTitle = $row["section_title"];
//                  $phone_number = base64_decode($row["phone_number"]);
//                  $clientName = base64_decode($row["full_name"]);
                 
//                  $dataInfo  = $row["id"]."_info";
//                  $dataReady  = $row["id"]."_ready_".$keyThirst;
//                  $readyInfoBtn = json_encode([
//                      'inline_keyboard' =>[
//                          [
//                              ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                             
//                          ],
//                          [
//                              ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                             
//                          ]
//                      ]
//                  ]);
                 
//                  $txtSend = "#".$title." \nIsmi: <b>".$clientName."</b>\nBoshlangan Vaqti: 🕓 <b>".$crDate."</b>\nTugash vaqti: ⏳ <b>".$endDate."</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name."</b>\nTelefon raqami: 📞  +".$phone_number;

//                  bot('sendMessage',[
//                      'chat_id' => $chat_id,
//                      'message_id' => $message_id,
//                      'text' => $txtSend,
//                      'parse_mode' => "html",
//                      'reply_markup' => $readyInfoBtn
//                  ]);
                     
//              }
//          } 
//          $sqlUpdate = "update step_order set step_2 = 100 where chat_id = ".$chat_id;
//          $result = pg_query($conn,$sqlUpdate);
         
//       } else if ($data == $nextClData) {
//           $sqlGetBranchId = "select * from clients where id = ".$key;
//           $resultGetBranchid = pg_query($conn,$sqlGetBranchId);
//           $rowGetBranchId = pg_fetch_assoc($resultGetBranchid);
//           $branchId = $rowGetBranchId["branch_id"];
//           $sql = "select * from clients where status = 1 and branch_id = ".$branchId." and id > ".$key." limit 20";
//           $result = pg_query($conn,$sql);
//           $sqlSecond = "select * from clients where status = 1 and  id > ".$key." and branch_id = ".$branchId;
//           $resultSecond = pg_query($conn,$sqlSecond);

//           $arr_uz = [];
//           $row_arr = [];
//           if (pg_num_rows($result) > 0 ){
//              while ($value = pg_fetch_assoc($result)) {
//                  $client_id = $value['id'];
//                  $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = ".$client_id;
//                  $resultOrder = pg_query($conn,$sqlOrder);
//                  $branchTitle = base64_decode($value['full_name']);
//                  $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id."_clients"];
//                  $row_arr[] = $arr_uz;
//                  $arr_uz = [];
//              }
//              $nextClients = $client_id."_nextClient";
//              $backClients = $client_id."_backClient";
//              if (pg_num_rows($resultSecond) > 20) {
//                 $row_arr[] = [
//                   ["text" => "⬅️ Oldingi Mijozlar","callback_data" => $backClients],
//                   ["text" => "➡️ Boshqa Mijozlar","callback_data" => $nextClients]
//                 ];
//              } else {
//                 $row_arr[] = [
//                   ["text" => "⬅️ Oldingi Mijozlar","callback_data" => $backClients]
//                 ];
//              }
//              $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//              $btnKey = json_encode(['inline_keyboard' => $row_arr]);

//              bot('editMessageText',[
//                  'chat_id' => $chat_id,
//                  'message_id' => $message_id,
//                  'text' => "Mijozlardan birini tanlang!",
//                  'reply_markup' => $btnKey
//              ]);
//           } else {
//              bot('deleteMessage',[
//                  'chat_id' => $chat_id,
//                  'message_id' => $message_id
//              ]);
//              bot('sendMessage',[
//                  'chat_id' => $chat_id,
//                  'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
//                  'reply_markup' => $menu
//              ]);
//           }
//       } else if ($data == $backClData) {
//           $sqlGetBranchId = "select * from clients where id = ".$key;
//           $resultGetBranchid = pg_query($conn,$sqlGetBranchId);
//           $rowGetBranchId = pg_fetch_assoc($resultGetBranchid);
//           $branchId = $rowGetBranchId["branch_id"];
//           $sql = "select * from clients where status = 1 and branch_id = ".$branchId." and id < ".$key." limit 20";
//           $result = pg_query($conn,$sql);
//           $sqlSecond = "select * from clients where status = 1 and  id < ".$key." and branch_id = ".$key;
//           $resultSecond = pg_query($conn,$sqlSecond);
//           // $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
//           $arr_uz = [];
//           $row_arr = [];
//           // bot('sendMessage',[
//           //   'chat_id' => $chat_id,
//           //   'text' => "Success \n".$sql.PHP_EOL.$sqlSecond
//           // ]);
//           if (pg_num_rows($result) > 0 ){
//              while ($value = pg_fetch_assoc($result)) {
//                  $client_id = $value['id'];
//                  $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = ".$client_id;
//                  $resultOrder = pg_query($conn,$sqlOrder);
//                  $branchTitle = base64_decode($value['full_name']);
//                  $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id."_clients"];
//                  $row_arr[] = $arr_uz;
//                  $arr_uz = [];
//              }
//              $nextClients = $client_id."_nextClient";
//              $backClients = $client_id."_backlient";
//              if (pg_num_rows($resultSecond) > 20) {
//                 $row_arr[] = [
//                   ["text" => "⬅️ Oldingi Mijozlar","callback_data" => $backClients],
//                   ["text" => "➡️ Boshqa Mijozlar","callback_data" => $nextClients]
//                 ];
//              } else {
//                 $row_arr[] = [
//                   ["text" => "➡️ Boshqa Mijozlar","callback_data" => $nextClients]
//                 ];
//              }
//              $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//              $btnKey = json_encode(['inline_keyboard' => $row_arr]);

//              bot('editMessageText',[
//                  'chat_id' => $chat_id,
//                  'message_id' => $message_id,
//                  'text' => "Mijozlardan birini tanlang!",
//                  'reply_markup' => $btnKey
//              ]);
//           } else {
//              bot('deleteMessage',[
//                  'chat_id' => $chat_id,
//                  'message_id' => $message_id
//              ]);
//              bot('sendMessage',[
//                  'chat_id' => $chat_id,
//                  'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
//                  'reply_markup' => $menu
//              ]);
//           }
//       }
//     }
    
//     if ($step_2 == 8) {
//        if ($data == $ordersdata) {
//            $sqlUpdate = "update step_order set step_2 = 9 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate);
//            $sqlUsers = "select * from users where chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlUsers);
//            if (pg_num_rows($resultStep) > 0) {
//                $row = pg_fetch_assoc($resultStep);
//                $type = $row["type"];
//            }
//            $sqlUpdateTwo = "update last_id_order set last_id = ".$keySecond.",message_id = ".$keyThirst." where chat_id = ".$chat_id;
//            $pg_query = pg_query($conn,$sqlUpdateTwo);
//            if ($type != 5 and $type != 6 and $type != 7) {
//                $sqlOrders = "select
//                    ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title,
//                    ctg.title as category_name
//                    from orders as ord 
//                    inner join clients as cl on ord.client_id = cl.id
//                    inner join section_orders as so on ord.id = so.order_id
//                    inner join sections as sec on so.section_id = sec.id
//                    inner join order_categories as oc on o.id = oc.order_id
//                    inner join category as ctg on oc.category_id = ctg.id
//                where ord.client_id = ".$keyThirst." and so.order_id = ".$keySecond;
//                $resultOrders = pg_query($conn,$sqlOrders);
//                if (pg_num_rows($resultOrders) > 0 ) {
//                    while ($row = pg_fetch_assoc($resultOrders)) {

//                        $title  = $row["title"];
//                        $endDate = $row["dead_line"];
//                        $category_name = $row["category_name"];
//                        $crDate = $row["created_date"];
//                        $sectionTitle = $row["section_title"];
//                        $phone_number = base64_decode($row["phone_number"]);
//                        $clientName = base64_decode($row["full_name"]);
                       
//                        $dataInfo  = $row["id"]."_info";
//                        $dataReady  = $row["id"]."_ready_".$keyThirst;
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                                   
//                                ],
//                                [
//                                    ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                                   
//                                ]
//                            ]
//                        ]);
                       
//                        $txtSend = "#".$title." \nIsmi: <b>".$clientName."</b>\nBoshlangan Vaqti: 🕓 <b>".$crDate."</b>\nTugash vaqti: ⏳ <b>".$endDate."</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle."</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name."</b>\nTelefon raqami: 📞  +".$phone_number;
//                        if ($type != 4) {
//                            bot('editMessageText',[
//                                'chat_id' => $chat_id,
//                                'message_id' => $message_id,
//                                'text' => $txtSend,
//                                'parse_mode' => "html",
//                                'reply_markup' => $readyInfoBtn
//                            ]);
//                        } else if ($type == 4) {
//                            bot('editMessageText',[
//                                'chat_id' => $chat_id,
//                                'message_id' => $message_id,
//                                'text' => $txtSend,
//                                'parse_mode' => "html"
//                            ]);
//                        }
//                    }
//                } 
//            }
//            else if ($type == 5 or $type == 7 or $type == 6) {
               
//                $sqlOrders = "select 
//                      ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title , os.deadline as deadline_of_section, ctg.title as category_name
//                    from orders as ord 
//                    inner join clients as cl on ord.client_id = cl.id
                   
//                    inner join section_orders as so on ord.id = so.order_id
//                    inner join sections as sec on so.section_id = sec.id
//                    inner join order_step as os on os.order_id = ord.id and os.section_id = sec.id
//                    inner join users_section as us on sec.id = us.section_id
//                    inner join users as use on us.user_id = use.id
//                    inner join order_categories as oc on ord.id = oc.order_id
//                    inner join category as ctg on oc.category_id = ctg.id
//                    where ord.id = ".$keySecond." and ord.client_id = ".$keyThirst." and use.chat_id = ".$chat_id." and so.exit_date is null";
//                $resultOrders = pg_query($conn,$sqlOrders);
//                if (pg_num_rows($resultOrders) > 0 ) {
//                    while ($row = pg_fetch_assoc($resultOrders)) {
//                        $title  = $row["title"];
//                        $endDate = $row["dead_line"];
//                        $crDate = $row["created_date"];
//                        $sectionTitle = $row["section_title"];
//                        $deadline_of_section = $row["deadline_of_section"];
//                        $phone_number = base64_decode($row["phone_number"]);
//                        $clientName = base64_decode($row["full_name"]);
                       
//                        $dataInfo  = $row["id"]."_info";
//                        $dataReady  = $row["id"]."_ready_".$keyThirst;
//                        if ($type == 5 or $type == 6) {
//                            $readyInfoBtn = json_encode([
//                                'inline_keyboard' =>[
//                                    [
//                                        ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                       
//                                    ],
//                                    [
//                                        ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                                       
//                                    ]
//                                ]
//                            ]);     
//                        } else if ($type == 7) {
//                            $readyInfoBtn = json_encode([
//                                'inline_keyboard' =>[
//                                    [
//                                        ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"], ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                                       
//                                    ],
//                                ]
//                            ]);   
//                        }
                      
//                        $txtSend = "#".$title." \nIsmi: <b>".$clientName."</b>\nBoshlangan Vaqti: 🕓 <b>".$crDate."</b>\nTugash vaqti: ⏳ <b>".$endDate."</b>\nBo`lim tugalash vaqti <b>".$deadline_of_section."</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle."</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name."</b>\nTelefon raqami: 📞  +".$phone_number;
//                        if ($type != 4) {
//                            bot('editMessageText',[
//                                'chat_id' => $chat_id,
//                                'message_id' => $message_id,
//                                'text' => $txtSend,
//                                'parse_mode' => "html",
//                                'reply_markup' => $readyInfoBtn
//                            ]);
//                        } else if ($type == 4) {
//                            bot('editMessageText',[
//                                'chat_id' => $chat_id,
//                                'message_id' => $message_id,
//                                'text' => $txtSend,
//                                'parse_mode' => "html"
//                            ]);
//                        }
//                    }
//                } else {
//                    bot('deleteMessage',[
//                        'chat_id' => $chat_id,
//                        'message_id' => $message_id
//                    ]);
//                    bot('sendMessage',[
//                        'chat_id' => $chat_id,
//                        'text' => "Sizning bo`limingizda buyurtmalar yo`q 🙃!",
//                        "reply_markup" => $menu
//                    ]);   
//                }
//            } 
//        }  
//     }
    
//     if($step_2 == 9) {
       
//        if ($data == $info) {
//            $sqlUsers = "SELECT us.role FROM section_orders as so
//                            inner join users_section as us on so.section_id = us.section_id
//                            inner join users as u on us.user_id = u.id
//                        WHERE so.order_id = ".$key." and so.exit_date is null and u.chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlUsers);
//            if (pg_num_rows($resultStep) > 0) {
//                $row = pg_fetch_assoc($resultStep);
//                $role = $row["role"];
//            }
//            $sqlGetRequiredMaterials = "SELECT 
//                                        r.title, 
//                                        o.title as order_name,
//                                        r.id FROM required_material_order as r 
//                                        INNER JOIN orders AS o ON r.order_id = o.id
//                                        WHERE r.status = 0 and r.order_id = ".$key." limit 1";
//            $resultRequiredMaterials = pg_query($conn,$sqlGetRequiredMaterials);
//            if (pg_num_rows($resultRequiredMaterials) > 0 and $role == 6) {
              
//                $sqlUpdate = "update step_order set step_2 = 15 where chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate);
//                $rowRequiredMaterials = pg_fetch_assoc($resultRequiredMaterials);
//                $idLast = $rowRequiredMaterials["id"];
//                $order_name = $rowRequiredMaterials["order_name"];
//                bot('deleteMessage',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id
//                ]);
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "<b><i>".$order_name."</i></b> buyurtmasiga  <b>".$rowRequiredMaterials["title"] . "</b>ni kiriting!\n",
//                    'parse_mode' => "html",
//                    'reply_markup' => $OnlyBack
//                ]);
//                $sqlUpdateLastId = "update last_id_order set last_id = ".$idLast.",type=".$key." where chat_id = ".$chat_id;
//                $resultStep = pg_query($conn,$sqlUpdateLastId);                
//            } else {
              
//                $sqlUpdate = "update step_order set step_2 = 10 where chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate);
               
//                $sql = "select * from order_materials where order_id = ".$key;
//                $resultOrder = pg_query($conn,$sql);
//                $sqlUpdateLastId = "update last_id_order set last_id = ".$key." where chat_id = ".$chat_id;
//                $resultStep = pg_query($conn,$sqlUpdateLastId);
               
//                $requiredMaterials = "SELECT * FROM required_material_order where order_id = ".$key;
//                $resultMaterials = pg_query($conn,$requiredMaterials);
//                if(pg_num_rows($resultOrder) > 0 or pg_num_rows($resultMaterials) > 0) {
//                    bot('deleteMessage',[
//                            'chat_id' => $chat_id,
//                            'message_id' => $message_id
//                        ]);
//                    while ($row = pg_fetch_assoc($resultOrder)) {
//                        $getResult = bot("copyMessage",[
//                            'chat_id' => $chat_id,
//                            'from_chat_id' => -1001444743477,
//                            'message_id' => $row["copy_message_id"]
//                        ]);
//                    }
//                    while ($rowTwo = pg_fetch_assoc($resultMaterials)) {
//                        $getResult = bot("copyMessage",[
//                            'chat_id' => $chat_id,
//                            'from_chat_id' => -1001444743477,
//                            'message_id' => $rowTwo["copy_message_id"]
//                        ]);
//                    }
//                    if ($role == 6) {
//                        $sql = "select * from orders where id = ".$key;
//                        $resultOrder = pg_query($conn,$sql);
//                        $row = pg_fetch_assoc($resultOrder);
//                        $order_name = $row["title"];
//                        $sqlUpdate = "update step_order set step_2 = 10 where chat_id = ".$chat_id;
//                        $result = pg_query($conn,$sqlUpdate);
//                        bot('sendMessage',[
//                            'chat_id' => $chat_id,
//                            'text' => "<b><i>".$order_name."</i></b> Yana ma'lumot qo'shishingiz mumkin!",
//                            'parse_mode' => "html",
//                            'reply_markup' => $OnlyBack
//                        ]);
                      
//                    } else {
//                        bot('sendMessage',[
//                            'chat_id' => $chat_id,
//                            'text' => "Buyurtmadagi ma'lumotlar shulardan iborat edi!",
//                            'reply_markup' => $OnlyBack
//                        ]);
//                    }
//                } else {
//                    bot('deleteMessage',[
//                        'chat_id' => $chat_id,
//                        'message_id' => $message_id
//                    ]);
//                    if ($typeOFUser == 6) {
//                        $sql = "select * from orders where id = ".$key;
//                        $resultOrder = pg_query($conn,$sql);
//                        $row = pg_fetch_assoc($resultOrder);
//                        $order_name = $row["title"];
//                        $sqlUpdate = "update step_order set step_2 = 10 where chat_id = ".$chat_id;
//                        $result = pg_query($conn,$sqlUpdate);
//                        bot('sendMessage',[
//                            'chat_id' => $chat_id,
//                            'text' => "Hozircha <b><i>".$order_name."</i></b> nomli  buyurtmada malumotlar mavjud emas,\nHohlasangiz ma'lumotlar kiritishingiz mumkin! 🙃",
//                            'parse_mode' => "html",
//                            'reply_markup' => $menu
//                        ]);
//                        bot('sendMessage',[
//                            'chat_id' => $chat_id,
//                            'text' => "Hozircha Buyurtma malumotlari mavjud emas,\nHohlasangiz ma'lumotlar kiritishingiz mumkin! 🙃",
//                            'reply_markup' => $OnlyBack
//                        ]);
//                    } else {
//                        bot('sendMessage',[
//                            'chat_id' => $chat_id,
//                            'text' => "Hozircha Buyurtma malumotlari mavjud emas! 🙃",
//                            'reply_markup' => $OnlyBack
//                        ]);
//                    }
//                }                 
//            }
//        } else if ($data == $ready) {
//            $sql = "UPDATE step_order SET step_2 = 20 where chat_id = ".$chat_id;
//            pg_query($conn,$sql);
//            $dataCancel = $key."_cancelReady_".$keyThirst;
//            $dataConfirm = $key."_confirm_".$keyThirst;
//            $readyInfoBtn = json_encode([
//                'inline_keyboard' =>[
//                    [
//                        ['callback_data' => $dataCancel,'text'=>"Bekor qilish 🚫"],['callback_data'=> $dataConfirm,'text'=>"✅ Tasdiqlayman"]
                       
//                    ],
//                    [
//                        ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                       
//                    ]
//                ]
//            ]);
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id,
//                'text' => "Buyurtma bitganini tasdiqlang! ✅",
//                'reply_markup' => $readyInfoBtn
//            ]);
//        } else if ($data == $addWorker) {
//            $sql = "SELECT 
//                        us.user_id,
//                        u.second_name
//                    FROM users_section as us 
//                    inner join users as u  on us.user_id = u.id 
//                    where us.section_id = 35 and us.role = 7";
//            $result = pg_query($conn,$sql);
//            $arr_uz = [];
//            $row_arr = [];
//            if (pg_num_rows($result) > 0) {
//                while ($row = pg_fetch_assoc($result)) {
//                    $user_id = $row['user_id'];
//                    $worker_name = $row['second_name'];
//                    $dataWorkers = $user_id."_workerId_".$key;
//                    $arr_uz[] = ["text" => $worker_name, "callback_data" => $dataWorkers];
//                    $row_arr[] = $arr_uz;
//                    $arr_uz = [];
                   
//                }
//                $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Bo'lim ishchilaridan birini tanlang!",
//                    'reply_markup' => $btnKey
//                ]);
//                $sqlUpdate = "update step_order set step_2 = 30 where chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate); 
//            }
//        }
//     }
    
//     if ($step_2 == 20) {
//       if ($data == $realyDone) {
//           $dateRightNow = date("Y-m-d H:i:s");
//           $sql = "
//             select 
//               so.enter_date,
//               os.order_column,
//               os.section_id,
//               os.order_id,
//               os.id
//             from section_orders as so
//             inner join order_step as os on so.section_id = os.section_id
//              where so.order_id = ".$key." and os.order_id = ".$key." AND os.section_id = ".$keyThirst." and so.exit_date IS NULL ORDER BY so.id DESC";
//           $result = pg_query($conn,$sql);
//           if (pg_num_rows($result) > 0) {
//             $row = pg_fetch_assoc($result);
//             $orderColumn = $row["order_column"];
//             $order_id = $row["order_id"];
//             $enter_date = $row["enter_date"];
//             $idOrderStep = $row["id"];
//             $section_id = $row["section_id"];
//             $sqlUpdateStatus = "update order_step set status = 1 where order_id = ".$order_id." and order_column = ".$orderColumn;
//             pg_query($conn,$sqlUpdateStatus);
//             $sqlOrders = "select title,parralel from orders where id = ".$key;
//             $resultOrder = pg_query($conn,$sqlOrders);
//             if (pg_num_rows($resultOrder) > 0) {
//                $rowOrder = pg_fetch_assoc($resultOrder);
//                $titleOrder = $rowOrder["title"];
//                $parralel = $rowOrder["parralel"];
//             }
            
//             $sqlColumn = "select * from order_step where order_column > ".$orderColumn." and order_id = ".$key." or order_column = ".$orderColumn." and order_id = ".$key." and id > ".$idOrderStep." ORDER BY order_column ASC limit 1";
//              $resultColumn = pg_query($conn,$sqlColumn);
//              if (pg_num_rows($resultColumn) > 0) {
//                  $rowColumnTwo = pg_fetch_assoc($resultColumn);
//                  $sectionIdTwo = $rowColumnTwo["section_id"];
//                  $orderColumnNext = $rowColumnTwo["order_column"];
                 
//                   if ($sectionIdTwo == 42  and $parralel == 1 and isset($parralel)) {
//                       $selectOrderParralel = "
//                       SELECT 
//                         os.order_id,
//                         os.section_id,
//                         s.title,
//                         os.status
//                       FROM order_step as os 
//                       INNER JOIN sections as s ON os.section_id = s.id where (os.section_id = 42 or os.section_id = 29) and os.order_id =".$key;
//                     $resultParralel = pg_query($conn,$selectOrderParralel);
//                     if (pg_num_rows($resultParralel) > 0) {

//                       $btn_row = [];
//                       $btn_column = [];
//                       while ($rowParralel = pg_fetch_assoc($resultParralel)) {
//                         if ($rowParralel["status"] == 0) {
//                           $order_id = $rowParralel["order_id"];
//                           $section_id = $rowParralel["section_id"];
//                           $title = $rowParralel["title"];
//                           $clb_shpon = $order_id."_".$section_id."_shpon_".$keyThirst;
//                           $btn_column[] = ["callback_data" =>$clb_shpon,'text' => $title];
//                         }
//                       }
//                       $btn_row[] = $btn_column; 
//                       $update_sql = "UPDATE step_order SET step_2 = 150 WHERE chat_id = ".$chat_id;
//                       $result_update = pg_query($conn,$update_sql);
//                       if ($result_update == true) {
//                         $shpon_korpus = json_encode([
//                           'inline_keyboard' => $btn_row
//                         ]);
//                         bot('deleteMessage',[
//                           'chat_id' => $chat_id,
//                           'message_id' => $message_id
//                         ]);
//                         bot('sendMessage',[
//                           "chat_id" => $chat_id,
//                           'text' => "Buyurmani qaysi bo`limga o`tkazmoqchisiz ?",
//                           'reply_markup' => $shpon_korpus
//                         ]);
//                         die();  
//                       }
//                     }
//                   } else if ($keyThirst != 28 or !isset($parralel)) {

//                      $insertOrderSection = "INSERT INTO section_orders (order_id,section_id,enter_date) VALUES (".$order_id.",".$sectionIdTwo.",'".$dateRightNow."') ";
                    
//                      $insertResult = pg_query($conn,$insertOrderSection);
//                   }
//                   $sqlUpdate = "update section_orders set step = 1, exit_date = '".$dateRightNow."' where exit_date is null and order_id = ".$order_id ." and section_id = ".$keyThirst ;
                
//                   $resultUpdate = pg_query($conn,$sqlUpdate);
                 
//                  // STATUS MUST BE 1
//                   if ($sectionIdTwo != 35) {
//                     $sqlSection = "select u.chat_id from users_section as us inner join users as u on us.user_id = u.id where  us.section_id = ".$sectionIdTwo;
//                   } else if ($sectionIdTwo == 35) {
//                     $sqlSection = "select u.chat_id from users_section as us inner join users as u on us.user_id = u.id where us.role = 5 and us.section_id = ".$sectionIdTwo;
//                   }
//                   $resultSection = pg_query($conn,$sqlSection);
//                   bot("deleteMessage",[
//                      'chat_id' => $chat_id,
//                      'message_id' => $message_id
//                   ]);
//                   bot('sendMessage',[
//                      'chat_id' => $chat_id,
//                      'text' => "Siz buyurtmani keyingi bo`limga o`tqazib yubordingiz!",
//                      'reply_markup' => $menu
//                   ]);
                 
//                   if (pg_num_rows($resultSection) > 0) {
//                     if ($keyThirst != 28 or !isset($parralel)) {
//                         while($rowSection = pg_fetch_assoc($resultSection)) {
//                             $OtdelChat_id = $rowSection["chat_id"];
//                             $lookOrders = $key."_lookOrders_".$keyThirst;
//                             $lookOrders = json_encode([
//                                 'inline_keyboard' =>[
//                                     [
//                                         ['callback_data' => $lookOrders,'text'=>"Buyurtmani ko`rish"]
                                        
//                                     ],
//                                 ]
//                             ]);
//                             bot('sendMessage',[
//                                 'chat_id' => $OtdelChat_id,
//                                  'text' => "📌<b><i>".$titleOrder."</i></b> nomli buyurtma sizning bo`limingizga o'tdi",
//                                  'parse_mode' => "html",
//                                  'reply_markup' => $lookOrders
//                              ]);
//                          }
//                      }
//                   }
//                   $ClientSql = "SELECT sec.title AS section,clp.chat_id as chat_id,ord.title as order,clp.step_1 AS lang FROM orders AS ord
//                       LEFT JOIN client_step AS clp
//                           ON clp.client_id = ord.client_id
//                       LEFT JOIN section_orders AS sec_ord
//                           ON sec_ord.order_id = ord.id
//                       LEFT JOIN sections AS sec
//                           ON sec_ord.section_id = sec.id
//                   WHERE ord.id = ".$key."
//                   LIMIT 1";
//                   $resultClient = pg_query($conn,$ClientSql);
//                   if (pg_num_rows($resultClient) == 1){
//                       $rowClient = pg_fetch_assoc($resultClient);
//                       if ($rowClient['lang'] == 1) {
//                           $text = "📌<b><i>".$rowClient['order']."</i></b> nomli buyurtmangiz <b><i>".$rowClient['section']."</i></b>  bolimga otdi";
//                       }elseif ($rowClient['lang'] == 2) {
//                           $text = "📌 Ваш заказ под названием <b><i>".$rowClient['order']."</i></b> перемешен в раздел <b><i>".$rowClient['section']."</i></b>";
//                       }else{
//                           $text = "🇷🇺 | 📌 Ваш заказ под названием <b><i>".$rowClient['order']."</i></b> перемешен в раздел <b><i>".$rowClient['section']."</i></b>".PHP_EOL."🇺🇿 | 📌<b><i>".$rowClient['order']."</i></b> nomli buyurtmangiz <b><i>".$rowClient['section']."</i></b>  bolimga otdi";
//                       }
//                       $testSendUrl = 'https://api.telegram.org/bot1576388332:AAG2d9KptV4wICUw6BMujGc3aYEvo1wzQNs/sendMessage';
//                       $chOne = curl_init();
//                       curl_setopt($chOne, CURLOPT_URL, $testSendUrl);
//                       curl_setopt($chOne, CURLOPT_RETURNTRANSFER, TRUE);
//                       curl_setopt($chOne, CURLOPT_POSTFIELDS, [
//                           'chat_id' => $rowClient['chat_id'],
//                           'text' => $text,
//                           'parse_mode' => "html"
//                       ]);
//                       $resOne = curl_exec($chOne);
//                   }

//              } else {
//                  $dateRightNow = date("Y-m-d H:i:s");
//                  $sqlUpdate = "update section_orders set status = 1,  exit_date = '".$dateRightNow."' where exit_date is null and order_id = ".$order_id;
//                  $sqlUpdate = pg_query($conn,$sqlUpdate);
//                  bot('deleteMessage',[
//                      'chat_id' => $chat_id,
//                      'message_id' => $message_id
//                  ]);
//                  bot ('sendMessage',[
//                      'chat_id' => $chat_id,
//                      'text' => "Siz malumotni buyurtma boyicha oxirgi bolinma edingiz.Buyurtma tugatildi!",
//                      'reply_markup' => $menu
//                  ]);
//              }
//           }

//           $sql_bonus = "
//             SELECT 
//               st.section_id,
//               st.work_time,
//               us.chat_id,
//               sm.user_id,
//               mm.penalty_summ,
//               mm.bonus_sum,
//               ord.title as order_names,
//               sec.title as section_name,
//               us.second_name    
//             FROM section_times as st 
//             INNER JOIN section_minimal as sm on sm.section_id = st.section_id
//             INNER JOIN users as us on sm.user_id = us.id
//             INNER JOIN minimal as mm on sm.minimal_id = mm.id
//             INNER JOIN orders as ord on sm.order_id = ord.id
//             INNER JOIN sections as sec on sm.section_id = sec.id
//             WHERE st.section_id = ".$keyThirst;
//           $resultTwo = pg_query($conn,$sql_bonus);
//           if (pg_num_rows($resultTwo) > 0) {
//             $true = true;
//             $responsible = '';
//             while ($rowThree = pg_fetch_assoc($resultTwo)) {
//               $work_time = $rowThree["work_time"];
//               $dead_line_time = date("Y-m-d H:i:s", strtotime('+'.$work_time.' hours', strtotime($enter_date)));
//               if ($dateRightNow > $dead_line_time) {
//                 $type = 2;
//                 $differce_time = differenceInHours($dateRightNow,$dead_line_time);
//                 $round_time = round($differce_time);
//               } else if ($dateRightNow < $dead_line_time) {
//                 $type = 1;
//                 $differce_time = differenceInHours($dateRightNow,$dead_line_time);
//                 $round_time = round($differce_time);
//               }

//               $bonus_sum = $rowThree["bonus_sum"];
//               $penalty_summ = $rowThree["penalty_summ"];
              
//               if ($type == 1) {
//                 $quantity = $round_time * $bonus_sum;
//                 $txt_end = "<b>💴 Bonus summasi</b>: ";
//                 $user_id = 1270367;
//                 $receiver = $rowThree["user_id"]; 
//                 $title_msg = "<b>✅ Bonus yozildi</b>:";
//               } else if  ($type == 2) {
//                 $quantity = $round_time * $penalty_summ;
//                 $txt_end = "<b>💴 Jarima summasi</b>: ";
//                 $user_id = $rowThree["user_id"];
//                 $receiver = 1270367;
//                 $title_msg = "<b>❌ Jarima yozildi</b>:"; 
//               }

//               $quantity_sum = number_format("$quantity",0," "," ");
//               $quantity_sum = $quantity_sum." so'm";
//               $order_names = $rowThree["order_names"];
//               $section_name = $rowThree["section_name"];
//               $responsible = $responsible.$rowThree["second_name"].", ";
//               $category_id = 21;

//               $sql_insert_salary = "
//                 INSERT INTO salary_event_balance (user_id, receiver, quantity, category_id, date,type) 
//                   VALUES 
//                 (".$user_id.",".$receiver.",".$quantity.",".$category_id.",'".$dateRightNow."',".$type.");
//               ";

//               $result_bonus = pg_query($conn,$sql_insert_salary);
//               if ($result_bonus == false and $true == TRUE){
//                 $true = false;
//               }
//             }
//             $txtSend = $title_msg."\n<b>📦 Buyurtma nomi</b>:".$order_names."\n<b>🏭 Bo`lim nomi</b>:".$section_name."\n<b>👤 Ma`sullar</b> :".$responsible.PHP_EOL.$txt_end.$quantity_sum;
         
//             bot('sendMessage',[
//               'chat_id' => 1825809871,
//               'text' => $txtSend,
//               'parse_mode' => 'html'
//             ]);  
//             if ($type == 1){
//                 bot('sendMessage',[
//                   'chat_id' => $chat_id,
//                   'text' => "💰 Sizga Bonus yozildi.\n⏳ Bonusni rahbar ko`rib chiqadi!"
//                 ]);
//             }
//           } else { 
//             bot('sendMessage',[
//               'chat_id' => $chat_id,
//               'text' => "Success \n ".$sql_bonus
//             ]);
//           }
//       } else if ($data == $cancelDone) {
//          $sql = "select 
//                  o.id,o.user_id as order_user_id,us.user_id as section_user_id,o.title,o.created_date,o.dead_line,us.section_id,cl.full_name,
//                  s.title as section_title , os.deadline as deadline_of_section,
//                  b.title as branch_title , us.role , o.description, ctg.title as category_name
//                  from users as u
//                  inner join users_section as us on u.id = us.user_id
//                  inner join sections as s on us.section_id = s.id
//                  inner join section_orders as so on s.id = so.section_id
//                  inner join orders as o on so.order_id = o.id
//                  inner join order_step as os on o.id = os.order_id and s.id = os.section_id
//                  inner join branch as b on o.branch_id = b.id    
//                  inner join clients as cl on o.client_id = cl.id
//                  inner join order_categories as oc on o.id = oc.order_id
//                  inner join category as ctg on oc.category_id = ctg.id
//              where o.pause = 0 and u.chat_id = ".$chat_id." and so.exit_date is null";
             
//          $result = pg_query($conn,$sql);
//          if (pg_num_rows($result)) {
//              while ($row = pg_fetch_assoc($result)) {
//                  $title  = $row["title"];
//                  $section_id  = $row["section_id"];
//                  $endDate = $row["dead_line"];
//                  $stepOrderDeadline = $row["deadline_of_section"];
//                  $crDate = $row["created_date"];
//                  $sectionTitle = $row["section_title"];
//                  $branch_title = $row["branch_title"];
//                  $category_name = $row["category_name"];
//                  $order_user_id = $row["order_user_id"];
//                  $section_user_id = $row["section_user_id"];
//                  $crDateNew = date("Y-m-d H:i",strtotime(date($crDate)));
//                  $endDateNew = date("Y-m-d H:i",strtotime(date($endDate)));
//                  $stepOrderDeadlineNew = date("Y-m-d H:i",strtotime(date($stepOrderDeadline)));
//                  $phone_number = base64_decode($row["phone_number"]);
//                  $clientName = base64_decode($row["full_name"]);
//                  $camment = $row["description"];
//                  if (!empty($camment)) {
//                      $camment = $camment;
//                  } else {
//                      $camment = "";
//                  }
//                  $role = $row["role"];
//                  $dataInfo  = $row["id"]."_info";
//                  $dataAdd  = $row["id"]."_addWorker";
//                  $dataReady  = $row["id"]."_ready_".$section_id;
              
//                  if (($role == 5 or $role == 6) and $section_id != 35 or ($section_user_id == $order_user_id)) {
//                      $readyInfoBtn = json_encode([
//                          'inline_keyboard' =>[
//                              [
//                                  ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                 
//                              ],
//                          ]
//                      ]);
//                  } else if ($section_id == 35 and $order_user_id == 1) {
//                      $readyInfoBtn = json_encode([
//                          'inline_keyboard' =>[
//                              [
//                                  ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data' => $dataAdd,'text'=>"➕ Ishchi Biriktirish"]
                                 
//                              ],
//                          ]
//                      ]);
//                  } else {
//                      $readyInfoBtn = json_encode([
//                          'inline_keyboard' =>[
//                              [
//                                  ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                                 
//                              ],
//                          ]
//                      ]);   
//                  }
                 
                 
//                  $txtSend = "#".$title." \n👨‍💻 Buyurtmachi: <b>".$clientName."</b>\n🏢 Buyurtma filiali :<b>".$branch_title."</b>\n🕓 Boshlangan Vaqti: <b>".$crDateNew."</b>\n⏳ Tugash vaqti: <b>".$endDateNew."</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle."</b>\n⏳ Bo'limdan chiqish vaqti: <b>".$stepOrderDeadlineNew."</b>\n⚒ Kategoriya: <b>".$category_name."</b>\n💬 Izoh:<b> ".$camment."</b>";
//                  $getResult =  bot('sendMessage',[
//                      'chat_id' => $chat_id,
//                      'text' => $txtSend,
//                      'parse_mode' => "html",
//                      'reply_markup' => $readyInfoBtn
//                  ]);
//                  $deleteMessageId = $getResult->result->message_id;
//                  $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//                  pg_query($conn,$sqlGo);
//              }
//          }
         
//          $getResult = bot('sendMessage',[
//              'chat_id' => $chat_id,
//              'text' => "Buyurtmalar barchasi shulardan iborat",
//              'reply_markup' => $menu
//          ]);
//          $deleteMessageId = $getResult->result->message_id;
//          $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
//          pg_query($conn,$sqlGo);
//          $sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = ".$chat_id;
//          $result = pg_query($conn,$sqlUpdate);
//       }
//     }
    
//     if ($data == $lookOrders) {
//       $sqlUsers = "SELECT us.role FROM section_orders as so
//                      inner join users_section as us on so.section_id = us.section_id
//                      inner join users as u on us.user_id = u.id
//                  WHERE so.order_id = ".$key." and so.exit_date is null and u.chat_id = ".$chat_id;
//       $resultStep = pg_query($conn,$sqlUsers);
//       if (pg_num_rows($resultStep) > 0) {
//          $row = pg_fetch_assoc($resultStep);
//          $role = $row["role"];
//       }
//       $sqlUpdateTwo = "update last_id_order set last_id = ".$keySecond.",message_id = ".$keyThirst." where chat_id = ".$chat_id;
//       $pg_query = pg_query($conn,$sqlUpdateTwo);
//       $sqlOrders = "
//         select 
//           ord.id,ord.title, ord.created_date, ord.dead_line,cl.full_name,cl.phone_number,sec.title as section_title , os.deadline as deadline_of_section,
//           us.role,us.section_id , ctg.title as category_name
//         from orders as ord 
//         inner join clients as cl on ord.client_id = cl.id

//         inner join section_orders as so on ord.id = so.order_id
//            inner join sections as sec on so.section_id = sec.id
//         inner join order_step as os on os.order_id = ord.id and os.section_id = sec.id
//         inner join users_section as us on sec.id = us.section_id
//         inner join users as use on us.user_id = use.id
//         inner join order_categories as oc on ord.id = oc.order_id
//         inner join category as ctg on oc.category_id = ctg.id
//         where ord.id = ".$key."  and use.chat_id = ".$chat_id." and so.exit_date is null"; #and ord.client_id = ".$keyThirst."
//       $resultOrders = pg_query($conn,$sqlOrders);
//       if (pg_num_rows($resultOrders) > 0 ) {
//         while ($row = pg_fetch_assoc($resultOrders)) {
//            $title  = $row["title"];
//            $endDate = $row["dead_line"];
//            $category_name = $row["category_name"];
//            $section_id = $row["section_id"];
//            if ($section_id == 35) {
//                if ($role == 5) {
//                    $sql = "SELECT 
//                                us.user_id,
//                                u.second_name
//                            FROM users_section as us 
//                            inner join users as u  on us.user_id = u.id 
//                            where us.section_id = 35 and us.role = 7";
//                    $result = pg_query($conn,$sql);
//                    $arr_uz = [];
//                    $row_arr = [];
//                    if (pg_num_rows($result) > 0) {
//                        while ($row = pg_fetch_assoc($result)) {
//                            $user_id = $row['user_id'];
//                            $worker_name = $row['second_name'];
//                            $dataWorkers = $user_id."_workerId_".$key;
//                            $arr_uz[] = ["text" => $worker_name, "callback_data" => $dataWorkers];
//                            $row_arr[] = $arr_uz;
//                            $arr_uz = [];
                           
//                        }
//                        $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//                        $btnKey = json_encode(['inline_keyboard' => $row_arr]);
//                        bot('editMessageText',[
//                            'chat_id' => $chat_id,
//                            'message_id' => $message_id,
//                            'text' => "Bo'lim ishchilaridan birini tanlang!",
//                            'reply_markup' => $btnKey
//                        ]);
//                        $sqlUpdate = "update step_order set step_2 = 30 where chat_id = ".$chat_id;
//                        $result = pg_query($conn,$sqlUpdate); 
//                        die();
//                    }
//                } else if ($role == 7) {
//                    $crDate = $row["created_date"];
//                    $sectionTitle = $row["section_title"];
//                    $deadline_of_section = $row["deadline_of_section"];
//                    $phone_number = base64_decode($row["phone_number"]);
//                    $clientName = base64_decode($row["full_name"]);
                   
//                    $dataInfo  = $row["id"]."_info";
//                    $dataReady  = $row["id"]."_ready_".$section_id;
//                        $readyInfoBtn = json_encode([
//                            'inline_keyboard' =>[
//                                [
//                                    ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                   
//                                ],
//                                [
//                                    ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                                   
//                                ]
//                            ]
//                        ]);
//                    $txtSend = "#".$title." \nIsmi: <b>".$clientName."</b>\nBoshlangan Vaqti: 🕓 <b>".$crDate."</b>\nTugash vaqti: ⏳ <b>".$endDate."</b>\nBo`lim tugalash vaqti <b>".$deadline_of_section."</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle."</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name."</b>\nTelefon raqami: 📞  +".$phone_number;
//                    bot('editMessageText',[
//                        'chat_id' => $chat_id,
//                        'message_id' => $message_id,
//                        'text' => $txtSend,
//                        'parse_mode' => "html",
//                        'reply_markup' => $readyInfoBtn
//                    ]);
//                }
               
              
//            } else {
//                $crDate = $row["created_date"];
//                $sectionTitle = $row["section_title"];
//                $deadline_of_section = $row["deadline_of_section"];
//                $phone_number = base64_decode($row["phone_number"]);
//                $clientName = base64_decode($row["full_name"]);
               
//                $dataInfo  = $row["id"]."_info";
//                $dataReady  = $row["id"]."_ready_".$section_id;
//                if ($role == 5 or $role == 6) {
//                    $readyInfoBtn = json_encode([
//                        'inline_keyboard' =>[
//                            [
//                                ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                               
//                            ],
//                            [
//                                ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
                               
//                            ]
//                        ]
//                    ]);
//                } else {
//                    $readyInfoBtn = json_encode([
//                        'inline_keyboard' =>[
//                            [
//                                ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data' => "home",'text'=>"🏘 Bosh menu"]
//                            ],
//                        ]
//                    ]);
//                }
//                $txtSend = "#".$title." \nIsmi: <b>".$clientName."</b>\nBoshlangan Vaqti: 🕓 <b>".$crDate."</b>\nTugash vaqti: ⏳ <b>".$endDate."</b>\nBo`lim tugalash vaqti <b>".$deadline_of_section."</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle."</b>\n⚒ Kategoriya: 🔧 <b>" . $category_name."</b>\nTelefon raqami: 📞  +".$phone_number;
              
//                bot('editMessageText',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id,
//                    'text' => $txtSend,
//                    'parse_mode' => "html",
//                    'reply_markup' => $readyInfoBtn
//                ]);
//            }
//         }
//       } 
//       $sqlUpdate = "update step_order set step_2 = 9 where chat_id = ".$chat_id;
//       $result = pg_query($conn,$sqlUpdate); 
//     }
    
//     if ($step_2 == 30) {
//        if ($data == $checkWorkersData) {
//            $sql = "UPDATE step_order SET step_2 = 31 where chat_id = ".$chat_id;
//            pg_query($conn,$sql);
//            $dataCancel = $key."_cancelWorkers_".$keyThirst;
//            $dataConfirm = $key."_confirmWokers_".$keyThirst;
//            $readyInfoBtn = json_encode([
//                'inline_keyboard' =>[
//                    [
//                        ['callback_data' => $dataCancel,'text'=>"Bekor qilish 🚫"],['callback_data'=> $dataConfirm,'text'=>"✅ Tasdiqlayman"]
//                    ],
//                    [
//                        ['callback_data' => "home",'text'=>"🏘 Bosh menu"]
//                    ]
//                ]
//            ]);
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id
//            ]);
//            bot('sendMessage',[
//                'chat_id' => $chat_id,
//                'text' => "Bo'lim ichisini tasdiqlang! ✅",
//                'reply_markup' => $readyInfoBtn
//            ]);
//        }
//     }
    
//     if ($step_2 == 31) {
//        if ($data == $checkWorkers) {
//            $update = "UPDATE orders SET user_id = ".$key." where id = ".$keyThirst;
//            $result = pg_query($conn,$update);
//            if ($result == true) {
//                $sqlOrder = "select title from orders  where id = ".$keyThirst;
//                $resultSql = pg_query($conn,$sqlOrder);
//                $rowOrder = pg_fetch_assoc($resultSql);
//                $titleOrder = $rowOrder["title"];
            
//                $sql = "SELECT chat_id FROM users where id = ".$key;
//                $result = pg_query($conn,$sql);
//                if (pg_num_rows($result) > 0) {
//                    $row = pg_fetch_assoc($result);
//                    $WorkerChatId = $row["chat_id"];
//                    $lookOrders = $keyThirst."_lookOrders_35";
//                    $lookOrders = json_encode([
//                        'inline_keyboard' =>[
//                            [
//                                ['callback_data' => $lookOrders,'text'=>"Buyurtmani ko`rish"]
                               
//                            ],
//                        ]
//                    ]);
//                    bot('sendMessage',[
//                        'chat_id' => $WorkerChatId,
//                        'text' => "📌<b><i>".$titleOrder."</i></b> nomli buyurtmani o'rnatish sizga topshirildi",
//                        'parse_mode' => "html",
//                        'reply_markup' => $lookOrders
//                    ]);
//                }
//                bot('deleteMessage',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id 
//                ]);
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Siz buyurtmani Bo'lim ichisiga biriktrib yubordingiz!",
//                    'reply_markup' => $menu
//                ]);
//            } 
//        }
//        if ($data == $cancelWorkers) {
//            $sql = "SELECT 
//                        us.user_id,
//                        u.second_name
//                    FROM users_section as us 
//                    inner join users as u  on us.user_id = u.id 
//                    where us.section_id = 35 and us.role = 7";
//            $result = pg_query($conn,$sql);
//            $arr_uz = [];
//            $row_arr = [];
//            if (pg_num_rows($result) > 0) {
//                while ($row = pg_fetch_assoc($result)) {
//                    $user_id = $row['user_id'];
//                    $worker_name = $row['second_name'];
//                    $dataWorkers = $user_id."_workerId_".$key;
//                    $arr_uz[] = ["text" => $worker_name, "callback_data" => $dataWorkers];
//                    $row_arr[] = $arr_uz;
//                    $arr_uz = [];
                   
//                }
//                $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
//                bot('editMessageText',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id,
//                    'text' => "Bo'lim ishchilaridan birini tanlang!",
//                    'reply_markup' => $btnKey
//                ]);
//                $sqlUpdate = "update step_order set step_2 = 30 where chat_id = ".$chat_id;
//                $result = pg_query($conn,$sqlUpdate); 
//                die();
//            }
//        }
//     }
    
//     if ($step_2 == 100) {
//        if ($data == $info) {
//            $sqlGetRequiredMaterials = "SELECT 
//                r.title, 
//                o.title as order_name,
//                r.id FROM required_material_order as r 
//                INNER JOIN orders AS o ON r.order_id = o.id
//                WHERE r.status = 0 and r.order_id = ".$key." limit 1";
//            $resultRequiredMaterials = pg_query($conn,$sqlGetRequiredMaterials);
         
          
//            $sqlUpdate = "update step_order set step_2 = 101 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate);
           
//            $sql = "select * from order_materials where order_id = ".$key;
//            $resultOrder = pg_query($conn,$sql);
//            $sqlUpdateLastId = "update last_id_order set last_id = ".$key." where chat_id = ".$chat_id;
//            $resultStep = pg_query($conn,$sqlUpdateLastId);
           
//            $requiredMaterials = "SELECT * FROM required_material_order where order_id = ".$key;
//            $resultMaterials = pg_query($conn,$requiredMaterials);
//            bot('deleteMessage',[
//                'chat_id' => $chat_id,
//                'message_id' => $message_id
//            ]);
//            if(pg_num_rows($resultOrder) > 0 or pg_num_rows($resultMaterials) > 0) {
//                while ($row = pg_fetch_assoc($resultOrder)) {
//                    $getResult = bot("copyMessage",[
//                        'chat_id' => $chat_id,
//                        'from_chat_id' => -1001444743477,
//                        'message_id' => $row["copy_message_id"]
//                    ]);
//                }
//                while ($rowTwo = pg_fetch_assoc($resultMaterials)) {
//                    $getResult = bot("copyMessage",[
//                        'chat_id' => $chat_id,
//                        'from_chat_id' => -1001444743477,
//                        'message_id' => $rowTwo["copy_message_id"]
//                    ]);
//                }
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Buyurtmadagi ma'lumotlar shulardan iborat edi!",
//                    'reply_markup' => $OnlyBack
//                ]);
//            } else {
//                bot('deleteMessage',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id
//                ]);
             
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Hozircha Buyurtma malumotlari mavjud emas! 🙃",
//                    'reply_markup' => $OnlyBack
//                ]);
//            }                 
//        }
//     }

//     if ($step_2 == 150) {
//       if ($dataArr[2] == "shpon") {
//         $dateRightNow = date("Y-m-d H:i:s");
//         $explode_data = explode("_", $data);
//         $order_id = $explode_data[0];
//         $section_id = $explode_data[1];
//         $current_section_id = $explode_data[3];

//         $insertOrderSection = "INSERT INTO section_orders (order_id,section_id,enter_date) VALUES (".$order_id.",".$section_id.",'".$dateRightNow."') ";
//         $result_update_step = pg_query($conn,$insertOrderSection);

//         $update_order_step = "UPDATE order_step SET status = 1 WHERE order_id = ".$order_id." and section_id = ".$section_id;
//         $result_update_step = pg_query($conn,$update_order_step);

//         // bot('sendMessage',[
//         //   'chat_id' => $chat_id,
//         //   'text' => "success" . 
//         // ]);
//         if ($section_id == 42) {
//           $txt = "Siz buyurtmani Raspil sehiga o`tqazdingiz!";
//         } else if ($section_id == 29) {
//           $txt = "Siz buyurtmani Shpon sehiga o`tqazdingiz!";
//         }
//         bot('deleteMessage',[
//           'chat_id' => $chat_id,
//           'message_id' => $message_id
//         ]);

//         bot('sendMessage',[
//           'chat_id' => $chat_id,
//           'text' => $txt,
//           'reply_markup' => $menu
//         ]);

//         // if ($step_1)

//         $sql_step = "select * from order_step where status = 0 and order_id = ".$order_id." and (section_id = 42 or  section_id = 29)";
//         $result_step = pg_query($conn,$sql_step);
//         if (!pg_num_rows($result_step) > 0 ) {
//           $sqlUpdate = "update section_orders set step = 1, exit_date = '".$dateRightNow."' where exit_date is null and order_id = ".$order_id ." and section_id = ".$current_section_id ;
//           $resultUpdate = pg_query($conn,$sqlUpdate);

//           $sql_bonus = "
//             SELECT 
//               st.section_id,
//               st.work_time,
//               us.chat_id,
//               sm.user_id,
//               mm.penalty_summ,
//               mm.bonus_sum,
//               ord.title as order_names,
//               sec.title as section_name,
//               us.second_name    
//             FROM section_times as st 
//             INNER JOIN section_minimal as sm on sm.section_id = st.section_id
//             INNER JOIN users as us on sm.user_id = us.id
//             INNER JOIN minimal as mm on sm.minimal_id = mm.id
//             INNER JOIN orders as ord on sm.order_id = ord.id
//             INNER JOIN sections as sec on sm.section_id = sec.id
//             WHERE st.section_id = ".$current_section_id;
//           $resultTwo = pg_query($conn,$sql_bonus);
//           if (pg_num_rows($resultTwo) > 0) {
//             $true = true;
//             $responsible = '';
//             while ($rowThree = pg_fetch_assoc($resultTwo)) {
//               $work_time = $rowThree["work_time"];
//               $dead_line_time = date("Y-m-d H:i:s", strtotime('+'.$work_time.' hours', strtotime($enter_date)));
//               if ($dateRightNow > $dead_line_time) {
//                 $type = 2;
//                 $differce_time = differenceInHours($dateRightNow,$dead_line_time);
//                 $round_time = round($differce_time);
//               } else if ($dateRightNow < $dead_line_time) {
//                 $type = 1;
//                 $differce_time = differenceInHours($dateRightNow,$dead_line_time);
//                 $round_time = round($differce_time);
//               }

//               $bonus_sum = $rowThree["bonus_sum"];
//               $penalty_summ = $rowThree["penalty_summ"];
              
//               if ($type == 1) {
//                 $quantity = $round_time * $bonus_sum;
//                 $txt_end = "<b>💴 Bonus summasi</b>: ";
//                 $user_id = 1270367;
//                 $receiver = $rowThree["user_id"]; 
//                 $title_msg = "<b>✅ Bonus yozildi</b>:";
//               } else if  ($type == 2) {
//                 $quantity = $round_time * $penalty_summ;
//                 $txt_end = "<b>💴 Jarima summasi</b>: ";
//                 $user_id = $rowThree["user_id"];
//                 $receiver = 1270367;
//                 $title_msg = "<b>❌ Jarima yozildi</b>:"; 
//               }

//               $quantity_sum = number_format("$quantity",0," "," ");
//               $quantity_sum = $quantity_sum." so'm";
//               $order_names = $rowThree["order_names"];
//               $section_name = $rowThree["section_name"];
//               $responsible = $responsible.$rowThree["second_name"].", ";
//               $category_id = 21;

//               $sql_insert_salary = "
//                 INSERT INTO salary_event_balance (user_id, receiver, quantity, category_id, date,type) 
//                   VALUES 
//                 (".$user_id.",".$receiver.",".$quantity.",".$category_id.",'".$dateRightNow."',".$type.");
//               ";

//               $result_bonus = pg_query($conn,$sql_insert_salary);
//               if ($result_bonus == false and $true == TRUE){
//                 $true = false;
//               }
//             }
//             $txtSend = $title_msg."\n<b>📦 Buyurtma nomi</b>:".$order_names."\n<b>🏭 Bo`lim nomi</b>:".$section_name."\n<b>👤 Ma`sullar</b> :".$responsible.PHP_EOL.$txt_end.$quantity_sum;
         
//             bot('sendMessage',[
//               'chat_id' => 1825809871,
//               'text' => $txtSend,
//               'parse_mode' => 'html'
//             ]);  
//             if ($type == 1){
//                 bot('sendMessage',[
//                   'chat_id' => $chat_id,
//                   'text' => "💰 Sizga Bonus yozildi.\n⏳ Bonusni rahbar ko`rib chiqadi!"
//                 ]);
//             }
//           } else { 
//             // bot('sendMessage',[
//             //   'chat_id' => $chat_id,
//             //   'text' => "bonus yoq"
//             // ]);
//           }


//         }


      
//       }
//     }
//     if ($step_2 == 200) {
//        if ($data == $branch) {
//            $sqlUpdateLast = "update last_id_order set order_type = 1 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdateLast);

//            $sqlUpdate = "update step_order set step_2 = 7 where chat_id = ".$chat_id;
//            $result = pg_query($conn,$sqlUpdate); 
     
//            $sql = "select * from clients where status = 1 and branch_id = ".$key." limit 20";
//            $result = pg_query($conn,$sql);
//            $sqlSecond = "select * from clients where status = 1 and branch_id = ".$key;
//            $resultSecond = pg_query($conn,$sqlSecond);
//            // $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
//            $arr_uz = [];
//            $row_arr = [];
//            if (pg_num_rows($result) > 0 ){
//                while ($value = pg_fetch_assoc($result)) {
//                    $client_id = $value['id'];
//                    $sqlOrder = "SELECT * FROM orders WHERE status = 1 and client_id = ".$client_id;
//                    $resultOrder = pg_query($conn,$sqlOrder);
//                    $branchTitle = base64_decode($value['full_name']);
//                    $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $client_id."_clients"];
//                    $row_arr[] = $arr_uz;
//                    $arr_uz = [];
//                }
//                $nextClients = $client_id."_nextClient";
//                if (pg_num_rows($resultSecond) > 20) {
//                   $row_arr[] = [["text" => "➡️ Boshqa Mijozlar","callback_data" => $nextClients]];
//                }
//                $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];
//                $btnKey = json_encode(['inline_keyboard' => $row_arr]);
            
//                bot('editMessageText',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id,
//                    'text' => "Mijozlardan birini tanlang!",
//                    'reply_markup' => $btnKey
//                ]);
//            } else {
//                bot('deleteMessage',[
//                    'chat_id' => $chat_id,
//                    'message_id' => $message_id
//                ]);
//                bot('sendMessage',[
//                    'chat_id' => $chat_id,
//                    'text' => "Bu filialda hozircha  mijoz yo`q 🙃\n",
//                    'reply_markup' => $menu
//                ]);
//            }
//        }
//     }
//   }