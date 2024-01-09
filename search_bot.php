<?php 
	if ($step_2 == 210  and $text != "/start") {
		$sql = "
			SELECT 
			 	o.id,o.user_id as order_user_id,
			 	o.title,
			 	o.created_date,
			 	o.dead_line,
			 	cl.full_name,
			    s.title as section_title,
			 	os.deadline as deadline_of_section,
			    b.title as branch_title,
			 	o.description,
			 	ctg.title as category_name,
			 	so.section_id as section_id
			from orders as o 
			inner join clients as cl on o.client_id = cl.id 
			inner join section_orders as so on o.id = so.order_id
			inner join sections as s on so.section_id = s.id
			inner join order_step as os on o.id = os.order_id and s.id = os.section_id
			inner join branch as b on o.branch_id = b.id
			inner join order_categories as oc on o.id = oc.order_id
			inner join category as ctg on oc.category_id = ctg.id
			where so.exit_date is null and o.pause = 0 and (lower(o.title) like lower('%".$text."%') or lower(convert_from(decode(cl.full_name, 'base64'), 'UTF8')) like lower('%".$text."%'))";
			// bot('sendMessage',[
			// 	'chat_id' => $chat_id,
			// 	'text' => 'success' . $sql
			// ]);
		$result = pg_query($conn,$sql);
		if (pg_num_rows($result)) {
           while ($row = pg_fetch_assoc($result)) {
           		$section_id = $row["section_id"];
           		$sqlTwo = "
           			SELECT us.type AS role from users as us 
					INNER JOIN order_responsibles AS ord ON ord.user_id = us.id
					where us.chat_id = ".$chat_id." and ord.section_id = ".$section_id;
				$resultTwo = pg_query($conn,$sqlTwo);
				if (pg_num_rows($resultTwo) > 0) {
					$rowTwo = pg_fetch_assoc($resultTwo);
					$role = $rowTwo["role"];
				}

               $title  = $row["title"];
               $section_id  = $row["section_id"];
               $endDate = $row["dead_line"];
               $stepOrderDeadline = $row["deadline_of_section"];
               $crDate = $row["created_date"];
               $sectionTitle = $row["section_title"];
               $branch_title = $row["branch_title"];
               $category_name = $row["category_name"];
               $order_user_id = $row["order_user_id"];
               $section_user_id = $row["section_user_id"];
               $crDateNew = date("Y-m-d H:i",strtotime(date($crDate)));
               $endDateNew = date("Y-m-d H:i",strtotime(date($endDate)));
               $stepOrderDeadlineNew = date("Y-m-d H:i",strtotime(date($stepOrderDeadline)));
               $phone_number = base64_decode($row["phone_number"]);
               $clientName = base64_decode($row["full_name"]);
               $camment = $row["description"];
               if (!empty($camment)) {
                   $camment = $camment;
               } else {
                   $camment = "";
               }
               $dataInfo  = $row["id"]."_info";
               $dataAdd  = $row["id"]."_addWorker";
               $dataReady  = $row["id"]."_ready_".$section_id;
            
               if (($role == 5 or $role == 6) and $section_id != 35) {
                   $readyInfoBtn = json_encode([
                       'inline_keyboard' =>[
                           [
                               ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                               
                           ],
                       ]
                   ]);
               } else if ($section_id == 35 and $role == 5) {
                   $readyInfoBtn = json_encode([
                       'inline_keyboard' =>[
                           [
                               ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data' => $dataAdd,'text'=>"➕ Ishchi Biriktirish"]
                           ],
                           [  
                            ['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                         ]
                       ]
                   ]);
               }

               else if ($chat_id == 1245165562 ) {
                   $readyInfoBtn = json_encode([
                       'inline_keyboard' =>[
                           [
                               ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                           ],
                           [  
                            ['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                         ]
                       ]
                   ]);
               }

               else if ($chat_id == 5669838528 ) {
                   $readyInfoBtn = json_encode([
                       'inline_keyboard' =>[
                           [
                               ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                           ],
                           [  
                            ['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                         ]
                       ]
                   ]);
               }

                else {
                   $readyInfoBtn = json_encode([
                       'inline_keyboard' =>[
                           [
                               ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"]
                               
                           ],
                       ]
                   ]);   
               }
               
               
               $txtSend = "#".$title." \n👨‍💻 Buyurtmachi: <b>".$clientName."</b>\n🏢 Buyurtma filiali :<b>".$branch_title."</b>\n🕓 Boshlangan Vaqti: <b>".$crDateNew."</b>\n⏳ Tugash vaqti: <b>".$endDateNew."</b>\n🔧 Ishlayotgan Bo`limi: <b>" . $sectionTitle."</b>\n⏳ Bo'limdan chiqish vaqti: <b>".$stepOrderDeadlineNew."</b>\n⚒ Kategoriya: <b>".$category_name."</b>\n💬 Izoh:<b> ".$camment."</b>";
               $getResult =  bot('sendMessage',[
                   'chat_id' => $chat_id,
                   'text' => $txtSend,
                   'parse_mode' => "html",
                   'reply_markup' => $readyInfoBtn
               ]);
               $deleteMessageId = $getResult->result->message_id;
               $sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
               pg_query($conn,$sqlGo);
               $role = 0;
           }
        } else {
        	$getResult = bot('sendMessage',[
			   'chat_id' => $chat_id,
			   'text' => "Bunday buyurtma topilmadi!",
			   'reply_markup' => $menu
			]);
			$deleteMessageId = $getResult->result->message_id;
			$sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
			pg_query($conn,$sqlGo);	
        }
		$getResult = bot('sendMessage',[
		   'chat_id' => $chat_id,
		   'text' => "Buyurtmalar barchasi shulardan iborat",
		   'reply_markup' => $menu
		]);
		$deleteMessageId = $getResult->result->message_id;
		$sqlGo = "INSERT INTO delete_messages (chat_id,message_id) values (".$chat_id.",".$deleteMessageId.")";
		pg_query($conn,$sqlGo);
		$sqlUpdate = "UPDATE step_order SET step_2 = 9 WHERE chat_id = ".$chat_id;
        $result = pg_query($conn,$sqlUpdate);

	}
?>