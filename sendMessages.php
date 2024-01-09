<?php 
    
    // bunga tema Abdulaziz ---
    date_default_timezone_set('Asia/Tashkent');
    // ---
    
    $conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");
    if ($conn) {
        echo "Success";
    }
    

    const TOKEN = '1594810052:AAHQEku5Q3tslozhq7uGiUpEB6oGUtzUXfs';
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
        }
        else{
            return json_decode($res);
        }
    }
    $sql = "SELECT * FROM otdel_log_message where status = 0 order by id asc limit 100";
    $result = pg_query($conn,$sql);
    if (pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $id = $row["id"];
            $chat_id = $row["chat_id"];
            $text = $row["text"];
            bot('sendMessage',[
                'chat_id' => $chat_id,
                // 'chat_id' => 284914591,
                'parse_mode' => 'html',
                'text' => $text
            ]);
            $sqlUpdate = "UPDATE otdel_log_message set status = 1 WHERE id = ".$id;
            $pgResult = pg_query($conn,$sqlUpdate);


        }
        $sqlDelete  = "DELETE FROM otdel_log_message WHERE status = 1";
        $pgResult = pg_query($conn,$sqlDelete);
    } 

?>