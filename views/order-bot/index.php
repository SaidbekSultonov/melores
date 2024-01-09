<?php 

	
	function bot($method, $data = []){
	    $url = 'https://api.telegram.org/bot1611335763:AAF6-m9wtcdiYUiVi8pefTrGdnTeYuOYjvk/'.$method;
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

	function typing($ch){
	    return bot('sendChatAction',[
	        'chat_id' => $ch,
	        'action' => 'typing'
	        ]);
	}
	
    
	bot('sendMessage',[
		'chat_id' => 1033542488,
		'text' => "successFull"
	]);


?>