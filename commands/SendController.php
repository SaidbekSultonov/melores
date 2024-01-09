<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Video;
use app\models\VideoLog;
use app\models\LastId;
use app\models\Users;
use app\models\Clients;


class SendController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        function bot($method, $data = []) {
            $url = 'https://api.telegram.org/bot1507522748:AAEEk43wU0wjQCAKkN6tOVmY0SDdob5Ggh0/'.$method;
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
        
        $sendMedia = VideoLog::find()->all();

        foreach ($sendMedia as $key => $value) {
            $mediaType = $value->type;
            $chat_id = $value->chat_id;
            $file_id = $value->file_id;
            switch ($mediaType) {
                case 'photo':
                    bot('sendPhoto', [
                        'chat_id' => $chat_id,
                        'photo' => $file_id
                    ]);
                    break;
                case 'video':
                    $selectCaption = Video::find()->where(['file_id' => $file_id])->one();
                    $caption = $selectCaption->caption;
                    if (isset($caption)) {
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id,
                            'caption' => $caption
                        ]);
                    } else {
                        bot('sendVideo', [
                            'chat_id' => $chat_id,
                            'video' => $file_id
                        ]);
                    }
                    break;
                case 'document':
                    bot('sendDocument', [
                        'chat_id' => $chat_id,
                        'document' => $file_id
                    ]);
                    break;
                case 'text':
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        'text' => $file_id
                    ]);
                    break;
            }
            $deletePhoto = VideoLog::find()->where(['chat_id' => $chat_id])->one();
            $deletePhoto->delete();
        }
    }
}