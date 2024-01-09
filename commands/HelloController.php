<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Video;
use app\models\VideoLog;
use app\models\LastId;
use app\models\Users;
use app\models\Clients;


class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $selectFileId = Video::find()->where(['status' => 1])->andWhere(['!=', 'file_id', ''])->one();
        $videoFileId = $selectFileId->file_id;
        $videoId = $selectFileId->id;
        $videoType = $selectFileId->type;

        $selectClients = Clients::find()->where(['status' => 1])->all();
        foreach ($selectClients as $key => $value) {
            $chat_id = $value->chat_id;
            $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
            if (isset($selectLastId)) {
                $lastId = $selectLastId->video_last_id;
                $selectFileId = Video::find()->where(['status' => 1])->andWhere(['>', 'id', $lastId])->orderBy('id ASC')->one();
                if (isset($selectFileId)) {
                    $videoFileId = $selectFileId->file_id;
                    $videoId = $selectFileId->id;
                    $videoType = $selectFileId->type;

                    $checkVideo = new VideoLog;
                    $checkVideo->chat_id = $chat_id;
                    $checkVideo->file_id = $videoFileId;
                    $checkVideo->video_id = $videoId;
                    $checkVideo->type = $videoType;
                    $checkVideo->save();

                    $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                    $selectLastId->video_last_id = $videoId;
                    $selectLastId->save();
                }
            } else {
                $selectFileId = Video::find()->where(['status' => 1])->one();
                $videoFileId = $selectFileId->file_id;
                $videoId = $selectFileId->id;
                $videoType = $selectFileId->type;

                $checkVideo = new VideoLog;
                $checkVideo->chat_id = $chat_id;
                $checkVideo->file_id = $videoFileId;
                $checkVideo->video_id = $videoId;
                $checkVideo->type = $videoType;
                $checkVideo->save();

                $newLastId = new LastId();
                $newLastId->video_last_id = $videoId;
                $newLastId->chat_id = $chat_id;
                $newLastId->save();
            }
        }
    }
}