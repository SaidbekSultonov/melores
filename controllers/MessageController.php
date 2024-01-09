<?php

namespace app\controllers;
use Yii;
use app\models\Video;

class MessageController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;
    public function actionIndex()
    {
    	$message = Yii::$app->telegram->input->message;
        $text = $message->text;
        $chat_id = $message->chat->id;
        $first_name = $message->chat->first_name;
        $username = $message->chat->username;

        if ($text == "/start") {
            $selectVideo = Video::find()->where(['video_number' = 1])->one();
        	foreach ($selectVideo as $key => $value) {
                $file_id = $value->file_id;
        		$res = Yii::$app->telegram4->sendPhoto ([
		            'chat_id' => $chat_id,
		            'photo' => $file_id
		        ]);
        	}

            // $file_id = $selectVideo->file_id;
            // $res = Yii::$app->telegram4->sendPhoto ([
            //     'chat_id' => $chat_id,
            //     'photo' => $file_id
            // ]);
        }

        // if (isset($message->photo)) {
        //     if(isset($message->photo[2])) {
        //         $file_id = $message->photo[2]->file_id;
        //     } else if (isset($message->photo[1])) {
        //         $file_id = $message->photo[1]->file_id;
        //     } else { 
        //         $file_id = $message->photo[0]->file_id;
        //     }
        //     $caption = base64_encode($message->caption);
        //     $type = "photo";
        //     $res = Yii::$app->telegram4->sendMessage ([
        //         'chat_id' => $chat_id,
        //         'text' => $file_id
        //     ]);
        // }

        // if (isset($message->video)) {
        //     $file_id = $update->message->video->file_id;
        //     // $caption = base64_encode($message->caption);
        //     // $type = "video";

        //     $res = Yii::$app->telegram4->sendMessage ([
        //         'chat_id' => $chat_id,
        //         'text' => $file_id
        //     ]);
        // }
 
    }
}
