<?php

namespace app\controllers;
use Yii;

use aki\telegram\types\CallbackQuery;
use aki\telegram\base\Command2;
use aki\telegram\base\Input;
use aki\telegram\base\Type;
use app\models\Users;
use app\models\BotUsers;
use app\models\Orders;
use app\models\Step;
use app\models\FeedbackUser;
use app\models\LastId;
use app\models\UserBalls;
use app\models\UsersBranch;
use app\models\Team;
use app\models\Category;
use app\models\Clients;
use app\models\ClientSalary;
use app\models\OrderCategories;

require(Yii::getAlias('@vendor')."/vendor2/autoload.php");

use Dompdf\Dompdf;

class ClientBotController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $pdf = new Dompdf();

        $update = file_get_contents('php://input');
        $update = json_decode($update);
        if (isset($update->callback_query)){
            $data = $update->callback_query->data;  
            $chat_id = $update->callback_query->message->chat->id;
            $message_id = $update->callback_query->message->message_id;

            $userStep = Step::find()->where(['chat_id' => $chat_id])->one();
            $step_1 = $userStep->step_1;
            $step_2 = $userStep->step_2;

            $chooseMaterial = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => "Akril  🔨",'callback_data' => "akril_material"],
                        ['text' => "Shpon  🔨",'callback_data' => "shpon_material"]
                    ]  
                ]
            ]);

            $dataOrder = explode("_", $data);
            $title = $dataOrder[0]."_title";

            if ($step_2 == 2) {
                if ($data == $title) {
                    $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 3;
                    $stepCheck->save();  

                    $selectOrder = Orders::find()->where(['id' => $dataOrder[0]])->one();
                    $selectId = $selectOrder->id;
                    $lastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                    $lastId->last_id = $selectId;
                    $lastId->save();

                    $selectTeam = Team::find()->where(['status' => 1])->all();

                    $arr_uz = [];
                    $row_arr = [];
                    foreach ($selectTeam as $key => $value) {
                        $teamId = $value['id'];
                        $teamTitle = base64_decode($value['title']);
                        $arr_uz[] = ["text" => $teamTitle, "callback_data" => $teamId."_team"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "*Brigadalar ⤵️*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $btnKey
                    ]); 
                }
            }

            $dataTeam = explode("_", $data);
            $teamTitle = $dataTeam[0]."_team";

            $dataMaterial = explode("_", $data);
            $orderMaterial = $dataMaterial[0]."_material";

            $dataSend = explode("_", $data);
            $orderSend = $dataSend[0]."_message";


            if($step_2 == 3) {
                if ($data == $teamTitle) {
                    $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                    $userLastId = $selectLastId->last_id;
                    $orderSelect = Orders::find()->where(['id' => $userLastId])->one();
                    $orderSelect->team_id = $dataTeam[0];
                    $orderSelect->save();

                    $deleteBall = UserBalls::find()->where(['chat_id' => $chat_id, 'order_id' => $userLastId])->all();

                    if (isset($deleteBall)) {
                        foreach ($deleteBall as $key => $value) {
                           $value->delete();
                        }   
                    }

                    $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                    if (isset($stepCheck)) {
                        $stepCheck->step_2 = 4;
                        $stepCheck->save();
                    }
                    Yii::$app->telegram2->editMessageText([
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "*Mahsulot turini tanlang ⤵️*" ,
                        'parse_mode' => 'markdown',
                        'reply_markup' => $chooseMaterial
                    ]);
                } 
            }

            $dataId = explode("_", $data);
            $keyFirst = $dataId[0];
            $dataId_1 = $dataId[0]."_1";
            $dataId_2 = $dataId[0]."_2";
            $dataId_3 = $dataId[0]."_3";
            $dataId_4 = $dataId[0]."_4";
            $dataId_5 = $dataId[0]."_5";

            $typeId = explode("_", $data);
            $keyFirst = $typeId[0];
            $typeId_1 = $typeId[0]."_1_3";
            $typeId_2 = $typeId[0]."_2_3";
            $typeId_3 = $typeId[0]."_3_3";
            $typeId_4 = $typeId[0]."_4_3";
            $typeId_5 = $typeId[0]."_5_3";

            if ($step_2 == 4) {
                if ($data == "akril_material") {
                    $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 5;
                    $stepCheck->save();

                    $feedbackUser = FeedbackUser::find()->where(['type' => 1, 'status' => 1])->orderBy(['id' => SORT_ASC])->one();
                    $question = $feedbackUser->title;
                    $baseQuestion = base64_decode($question);
                    $ballId = $feedbackUser->id;
                    $one = $ballId."_1";
                    $two = $ballId."_2";
                    $three = $ballId."_3";
                    $four = $ballId."_4";
                    $five = $ballId."_5";

                    $userBall = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "1️⃣",'callback_data' => $one],
                                ['text' => "2️⃣",'callback_data' => $two],
                                ['text' => "3️⃣",'callback_data' => $three],
                                ['text' => "4️⃣",'callback_data' => $four],
                                ['text' => "5️⃣",'callback_data' => $five]
                            ]   
                        ]
                    ]);
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "*".$baseQuestion."*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $userBall
                    ]);
                } else if($data == "shpon_material"){
                    $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 6;
                    $stepCheck->save();

                    $feedbackUser = FeedbackUser::find()->where(['type' => 2, 'status' => 1])->orderBy(['id' => SORT_ASC])->one();
                    $question = $feedbackUser->title;
                    $baseQuestion = base64_decode($question);
                    $ballId = $feedbackUser->id;
                    $one = $ballId."_1";
                    $two = $ballId."_2";
                    $three = $ballId."_3";
                    $four = $ballId."_4";
                    $five = $ballId."_5";

                    $userBall = json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => "1️⃣",'callback_data' => $one],
                                ['text' => "2️⃣",'callback_data' => $two],
                                ['text' => "3️⃣",'callback_data' => $three],
                                ['text' => "4️⃣",'callback_data' => $four],
                                ['text' => "5️⃣",'callback_data' => $five]
                            ]   
                        ]
                    ]);
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "*".$baseQuestion."*",
                        'parse_mode' => 'markdown',
                        'reply_markup' => $userBall
                    ]);   
                }
            }

            if ($step_2 == 5) {
                if ($data == $dataId_1 || $data == $dataId_2 || $data == $dataId_3 || $data == $dataId_4 || $data == $dataId_5) {
                    $feedbackUser = FeedbackUser::find()->where(['type' => 1, 'status' => 1])->andWhere(['>', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                    if(isset($feedbackUser)){
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);

                        $ballId = $feedbackUser->id;
                        $one = $ballId."_1";
                        $two = $ballId."_2";
                        $three = $ballId."_3";
                        $four = $ballId."_4";
                        $five = $ballId."_5";


                        $selectFeedback = FeedbackUser::find()->where(['id' => $keyFirst, 'status' => 1, 'type' => 1])->one();
                        $feedbackId = $selectFeedback->id;


                        $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id;

                        $selectBall = UserBalls::find()->where(['feedback_user_id' => $feedbackId, 'chat_id' => $chat_id, 'order_id' => $lastId])->one();   

                        if (!isset($selectBall)) {

                            $ballExplode = explode("_", $data);

                            $userBall = new UserBalls();
                            $userBall->feedback_user_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId;
                            $userBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "1️⃣",'callback_data' => $one],
                                        ['text' => "2️⃣",'callback_data' => $two],
                                        ['text' => "3️⃣",'callback_data' => $three],
                                        ['text' => "4️⃣",'callback_data' => $four],
                                        ['text' => "5️⃣",'callback_data' => $five]
                                    ]  
                                ]
                            ]);

                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "Bu savolga javob berib boldingiz",
                                'parse_mode' => 'markdown',
                            ]);        
                        }
                    } else {

                        $feedbackUser = FeedbackUser::find()->where(['type' => 1, 'status' => 1])->andWhere(['=', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_1";
                            $two = $ballId."_2";
                            $three = $ballId."_3";
                            $four = $ballId."_4";
                            $five = $ballId."_5";

                            $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                            $lastId = $selectLastId->last_id;

                            $ballExplode = explode("_", $data);

                            $userBall = new UserBalls();
                            $userBall->feedback_user_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId;
                            $userBall->save();
                        }

                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Mijoz etrozi 👤💬*',
                            'parse_mode' => 'markdown'
                        ]);  

                        $feedbackUser = FeedbackUser::find()->where(['type' => 3, 'status' => 1])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                            $stepCheck->step_2 = 7;
                            $stepCheck->save();

                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_1_3";
                            $two = $ballId."_2_3";
                            $three = $ballId."_3_3";
                            $four = $ballId."_4_3";
                            $five = $ballId."_5_3";

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "1️⃣",'callback_data' => $one],
                                        ['text' => "2️⃣",'callback_data' => $two],
                                        ['text' => "3️⃣",'callback_data' => $three],
                                        ['text' => "4️⃣",'callback_data' => $four],
                                        ['text' => "5️⃣",'callback_data' => $five]
                                    ]   
                                ]
                            ]);

                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => 'feedbackUser yo',
                                'parse_mode' => 'markdown'
                            ]);  
                        }
                    }
                } else {
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Berilgan tugmalardan foydalaning!'
                    ]);
                }
            }

            if ($step_2 == 6) {
                if ($data == $dataId_1 || $data == $dataId_2 || $data == $dataId_3 || $data == $dataId_4 || $data == $dataId_5) {
                    $feedbackUser = FeedbackUser::find()->where(['type' => 2, 'status' => 1])->andWhere(['>', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                    if(isset($feedbackUser)){
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);

                        $ballId = $feedbackUser->id;
                        $one = $ballId."_1";
                        $two = $ballId."_2";
                        $three = $ballId."_3";
                        $four = $ballId."_4";
                        $five = $ballId."_5";

                        $selectFeedback = FeedbackUser::find()->where(['id' => $keyFirst, 'status' => 1, 'type' => 2])->one();
                        $feedbackId = $selectFeedback->id;


                        $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id;

                        $selectBall = UserBalls::find()->where(['feedback_user_id' => $feedbackId, 'chat_id' => $chat_id, 'order_id' => $lastId])->one();   

                        if (!isset($selectBall)) {
                            $ballExplode = explode("_", $data);

                            $userBall = new UserBalls();
                            $userBall->feedback_user_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId;
                            $userBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "1️⃣",'callback_data' => $one],
                                        ['text' => "2️⃣",'callback_data' => $two],
                                        ['text' => "3️⃣",'callback_data' => $three],
                                        ['text' => "4️⃣",'callback_data' => $four],
                                        ['text' => "5️⃣",'callback_data' => $five]
                                    ]   
                                ]
                            ]);

                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "Bu savolga javob berib boldingiz",
                                'parse_mode' => 'markdown',
                            ]);        
                        }

                    } else {
                        $feedbackUser = FeedbackUser::find()->where(['type' => 2, 'status' => 1])->andWhere(['=', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_1";
                            $two = $ballId."_2";
                            $three = $ballId."_3";
                            $four = $ballId."_4";
                            $five = $ballId."_5";

                            $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                            $lastId = $selectLastId->last_id;

                            $ballExplode = explode("_", $data);

                            $userBall = new UserBalls();
                            $userBall->feedback_user_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId;
                            $userBall->save();
                        }
                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Mijoz etrozi 👤💬*',
                            'parse_mode' => 'markdown'
                        ]);  

                        $feedbackUser = FeedbackUser::find()->where(['type' => 3, 'status' => 1])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                            $stepCheck->step_2 = 7;
                            $stepCheck->save();

                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_1_3";
                            $two = $ballId."_2_3";
                            $three = $ballId."_3_3";
                            $four = $ballId."_4_3";
                            $five = $ballId."_5_3";

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "1️⃣",'callback_data' => $one],
                                        ['text' => "2️⃣",'callback_data' => $two],
                                        ['text' => "3️⃣",'callback_data' => $three],
                                        ['text' => "4️⃣",'callback_data' => $four],
                                        ['text' => "5️⃣",'callback_data' => $five]
                                    ]  
                                ]
                            ]);

                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => 'feedbackUser yo',
                                'parse_mode' => 'markdown'
                            ]);  
                        }
                    }
                } else {
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Berilgan tugmalardan foydalaning!'
                    ]);
                }
            }

            if ($step_2 == 7) {
                if ($data == $typeId_1 || $data == $typeId_2 || $data == $typeId_3 || $data == $typeId_4 || $data == $typeId_5) {
                    $feedbackUser = FeedbackUser::find()->where(['type' => 3, 'status' => 1])->andWhere(['>', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                    if(isset($feedbackUser)){
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);

                        $ballId = $feedbackUser->id;
                        $one = $ballId."_1_3";
                        $two = $ballId."_2_3";
                        $three = $ballId."_3_3";
                        $four = $ballId."_4_3";
                        $five = $ballId."_5_3";

                        $selectFeedback = FeedbackUser::find()->where(['id' => $keyFirst, 'status' => 1, 'type' => 3])->one();
                        $feedbackId = $selectFeedback->id;


                        $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id;

                        $selectBall = UserBalls::find()->where(['feedback_user_id' => $feedbackId, 'chat_id' => $chat_id, 'order_id' => $lastId])->one();   

                        if (!isset($selectBall)) {
                            $ballExplode = explode("_", $data);

                            $userBall = new UserBalls();
                            $userBall->feedback_user_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId;
                            $userBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "1️⃣",'callback_data' => $one],
                                        ['text' => "2️⃣",'callback_data' => $two],
                                        ['text' => "3️⃣",'callback_data' => $three],
                                        ['text' => "4️⃣",'callback_data' => $four],
                                        ['text' => "5️⃣",'callback_data' => $five]
                                    ]   
                                ]
                            ]);

                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "Bu savolga javob berib boldingiz",
                                'parse_mode' => 'markdown',
                            ]);        
                        }
                    } else {
                        $feedbackUser = FeedbackUser::find()->where(['type' => 3, 'status' => 1])->andWhere(['=', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_1_3";
                            $two = $ballId."_2_3";
                            $three = $ballId."_3_3";
                            $four = $ballId."_4_3";
                            $five = $ballId."_5_3";

                            $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                            $lastId = $selectLastId->last_id;

                            $ballExplode = explode("_", $data);

                            $userBall = new UserBalls();
                            $userBall->feedback_user_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId;
                            $userBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "1️⃣",'callback_data' => $one],
                                        ['text' => "2️⃣",'callback_data' => $two],
                                        ['text' => "3️⃣",'callback_data' => $three],
                                        ['text' => "4️⃣",'callback_data' => $four],
                                        ['text' => "5️⃣",'callback_data' => $five]
                                    ]  
                                ]
                            ]);
                        }

                        $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id;

                        $selectUsers = UserBalls::find()->where(['chat_id' => $chat_id, 'order_id' => $lastId])->all();

                        $arr_uz = [];
                        $row_arr = [];
                        $txtSend = '';
                        $i = 1;



                        $html ='
                          <!DOCTYPE html>
                            <html>
                            <head>
                                <title></title>
                                <meta http-equiv="Content - Type" content="text / html; charset = utf - 8"/>
                          <style>
                            *{
                                font-family: DejaVu Sans, sans-serif!important;                    

                             }
                             table {
                                   border-collapse: collapse; 
                            }
                            tr {
                                width: 300px;
                                border: 1px solid;
                            }
                            th, td {
                              padding: 13px;
                              text-align: left;
                              border: 1px solid;
                              width: 300px;
                            }
                            p{
                                line-height: normal;
                                margin: 0;
                                padding: 0;
                            }
                            .page_break { 
                                page-break-before: always; 
                            }
                          </style>
                          <h2 align="center">Buyurtma baholari</h2>';

                          $sql = "SELECT 
                            o.id,
                            o.title,
                            o.created_date, 
                            o.dead_line,
                            cl.full_name,
                            cat.title AS category_name
                            FROM orders o
                            INNER JOIN 
                            last_id AS l
                            ON l.last_id = o.id AND l.chat_id = ".$chat_id."
                            INNER JOIN clients AS cl
                            ON cl.id = o.client_id
                            INNER JOIN order_categories AS  oc
                            ON oc.order_id = o.id
                            INNER JOIN category AS cat
                            ON oc.category_id = cat.id";




                          $order_info = Yii::$app->db->createCommand($sql)->queryOne();

                          if (isset($order_info) && !empty($order_info)) {
                              $html .= '<p><b>Zakaz nomi:</b> '.$order_info['title'].'</p>';
                              $html .= '<p><b>Mijoz ismi:</b> '.base64_decode($order_info['full_name']).'</p>';
                              $html .= '<p><b>Boshlanish sanasi:</b> '.date('Y-m-d H:i', strtotime($order_info['created_date'])).'</p>';
                              $html .= '<p><b>Bitish sanasi:</b> '.date('Y-m-d H:i', strtotime($order_info['dead_line'])).'</p>';
                              $html .= '<p><b>Kategoriyasi:</b> '.$order_info['category_name'].'</p>';                            
                          } 

                          $html .= '<br><table>';
                        


                        foreach ($selectUsers as $key => $value) {
                            $feedbackUserId = $value->feedback_user_id;
                            $questionBall = $value->ball;

                            $orderQuestion = FeedbackUser::find()->where(['id' => $feedbackUserId])->all();

                            foreach ($orderQuestion as $key => $value) {
                                $questionTitle = base64_decode($value->title);

                                $titleBase = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $questionTitle);

                                $txtSend = $txtSend."*".$i."*"." - ".$titleBase . ": *".$questionBall." ball*"."\n";
                                $i++;
                                $html .= 
                                '<tr>
                                    <td>'.$titleBase .'</td>
                                    <td>'.$questionBall.'</td>
                                </tr>';
                            }

                        }

                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Siz qo'ygan ballar 👇*".PHP_EOL.PHP_EOL.$txtSend,
                            'parse_mode' => 'markdown'
                        ]);

                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Oylik maoshingizni kriting ❗️*",
                            'parse_mode' => 'markdown'
                        ]);


                        $_file_name = strtotime("Y-m-d H:i:s") . rand(1000, 9999);
                        $doc_name = "original-mebel-usta-bot_" . rand(1000, 9999) . '_' . $_file_name;

                        $pdf->loadHtml($html); 

                        // (Optional) Setup the paper size and orientation
                        $pdf->setPaper('A4');

                        // Render the HTML as PDF
                        $pdf->render();
                        file_put_contents('fpdf/uploads/'.$doc_name.'.pdf', $pdf->output());  

                        $last_doc_name = LastId::findOne(['chat_id' => $chat_id]);
                        $last_doc_name->doc_name = $doc_name;
                        $last_doc_name->save(); 
                        

                        $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 8;
                        $stepCheck->save();
                    }
                } else {
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Berilgan tugmalardan foydalaning!'
                    ]);
                }    
            }

            if ($step_2 == 9) {
                if ($data == $orderSend) {
                    $lastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                    $takeLastId = $lastId->last_id;
                    $orderFeedback = Orders::find()->where(['id' => $takeLastId, 'feedback_user' => 1])->one();
                    $orderFeedback->feedback_user = 0;
                    $orderFeedback->save();

                    $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_1 = 1;
                    $stepCheck->step_2 = 1;
                    $stepCheck->save();

                    $replyMarkup = array(
                        'keyboard' => array(
                            array(
                                array(
                                    'text' => "Bosh menyu ↩️"
                                )
                            ),
                        ),
                        'one_time_keyboard' => true,
                        'resize_keyboard' => true
                    );

                    $encodeMarkup = json_encode($replyMarkup);

                     

                    $last_doc_name = LastId::findOne(['chat_id' => $chat_id]);


                    if (isset($last_doc_name->doc_name) && !empty(isset($last_doc_name->doc_name))) {
                        $doc_name = $last_doc_name->doc_name;

                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Javoblar uchun katta rahmat ❗️😊*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $encodeMarkup
                        ]);
                        
                        Yii::$app->telegram2->sendDocument([
                            'chat_id' => Users::ADMIN_ID,
                            'document' => 'https://app.original-mebel.uz/fpdf/uploads/'.$doc_name.'.pdf',
                        ]);

                        unlink('fpdf/uploads/'.$doc_name.'.pdf');
                        $last_doc_name->doc_name = null;
                        $last_doc_name->save();
                    } else {
                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "❌ Javoblar saqlanmadi",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $encodeMarkup
                        ]); 
                    }  
                } else {
                    Yii::$app->telegram2->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Berilgan tugmalardan foydalaning!'
                    ]);
                }
            }
        } else {
            $message = Yii::$app->telegram->input->message;
            $text = $message->text;
            $chat_id = $message->chat->id;
            $first_name = $message->chat->first_name;
            $username = $message->chat->username;
            $txtArr = explode(" ", $text);

            $remove_keyboard = array(
                'remove_keyboard' => true
            );
            $remove_keyboard = json_encode($remove_keyboard);

            $number = 'xatolik';
            if (!empty($txtArr)) {
                $start = (isset($txtArr[0])) ? $txtArr[0] : ' xatolik';
                $number = (isset($txtArr[1])) ? $txtArr[1] : ' xatolik';
            }
            if ($text == $start . " " . $number) {
                $selectUsers = Users::find()
                ->where(["status" => 0, "phone_number" => $number])
                ->orWhere(["status" => 1, "phone_number" => $number])->one();
                if (isset($selectUsers)) {
                    $usersId = $selectUsers->id;
                    $selectBotUsers = BotUsers::find()->where(["user_id" => $usersId, "bot_id" => 2])->one();
                    if (isset($selectBotUsers)) {
                        $selectUsers->chat_id = $chat_id;
                        $selectUsers->name = $first_name;
                        $selectUsers->username = $username;
                        $selectUsers->save();
                        $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                        if (isset($stepCheck)) {
                            $stepCheck->step_1 = 0;
                            $stepCheck->step_2 = 0;
                            $stepCheck->save();

                        } else {
                            $step = new Step();
                            $step->chat_id = $chat_id;
                            $step->step_1 = 0;
                            $step->step_2 = 0;
                            $step->save();
                        }

                        $lastIdCheck = LastId::find()->where(['chat_id' => $chat_id])->one();
                        if (isset($lastIdCheck)) {
                            $lastIdCheck->last_id = 0;
                            $lastIdCheck->save();

                        } else {
                            $lastId = new LastId();
                            $lastId->chat_id = $chat_id;
                            $lastId->last_id = 0;
                            $lastId->video_last_id = 0;
                            $lastId->save();
                        }
                        $replyMarkup = array(
                            'keyboard' => array(
                                array(
                                    array(
                                        'text' => "Telefon raqam jo'natish 📱 ⤴️",
                                        'request_contact' => true
                                    )
                                ),
                            ),
                            'one_time_keyboard' => true,
                            'resize_keyboard' => true,
                        );

                        $encodeMarkup = json_encode($replyMarkup);
                        $res = Yii::$app->telegram2->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => 'Assalomu alaykum  🙋‍♂️'.PHP_EOL.PHP_EOL.'*"Telefon raqam jonatish 📱"* tugmasini bosing 🔘',
                            'parse_mode' => 'markdown',
                            'reply_markup' => $encodeMarkup
                        ]);    
                    } else {
                        $res = Yii::$app->telegram2->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "*Bu botdan 🤖 foydalanish uchun sizda huquq yo'q ☹️❗️* 1",
                            'parse_mode' => 'markdown' 
                        ]);
                    }
                } else if (isset($selectUsers) && $selectUsers->status == 1){
                    $res = Yii::$app->telegram2->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "*'start'* ni bosing ❗️⤵️".PHP_EOL.PHP_EOL."/start 🤏",
                        'parse_mode' => 'markdown' 
                    ]); 
                }else {
                    $res = Yii::$app->telegram2->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "*Bu botdan 🤖 foydalana olmaysiz ☹️❗️* 2",
                        'parse_mode' => 'markdown'
                    ]);    
                }
              
            } 
            else if ($text == "/start" || $text == 'Bosh menyu ↩️') {
                
                // Yii::$app->telegram2->sendMessage([
                //     'chat_id' => $chat_id,
                //     'text' => "1111111",
                    
                // ]);
                
                // return true;
                
                $selectUsers = Users::find()->where(["status" => 1, "chat_id" => $chat_id])->one();
                if (isset($selectUsers)) {
                    $userType = $selectUsers->type;
                    if ($userType == 5 || $userType == 6 || $userType == 7) {
                        $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_1 = 1;
                        $stepCheck->step_2 = 2;
                        $stepCheck->save();

                        $selectUsers = Users::find()->where(["status" => 1, "chat_id" => $chat_id])->one();
                        $userId = $selectUsers->id;
                        $userBranchId = UsersBranch::find()->where(["user_id" => $userId])->one();
                        $branchId = $userBranchId->branch_id;
                        $arr = [];                        
                        $findAllBranchId = UsersBranch::find()->where(['user_id' => $userId])->all();
                        foreach ($findAllBranchId as $key => $value) {
                            $userBranchId = $value->branch_id;
                            $arr[] = $userBranchId;    
                        }
                        $userBranch = Orders::find()->where(["status" => 0, "feedback_user" => 1])->
                        andWhere(['in', "branch_id", $arr])->limit(10)->all();

                        if (count($userBranch) != 0) {
                            Yii::$app->telegram2->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => '*Buyurtmalar 📑⤵️*',
                                'parse_mode' => 'markdown',
                                'reply_markup' => $remove_keyboard
                            ]);   
                            foreach ($userBranch as $key => $value) {
                                $clientId = $value['id'];
                                $clientTitle = $value['title'];
                                $createdDate = $value['created_date'];
                                $startDate = date('Y-m-d H:i', strtotime($createdDate));
                                $deadLine = $value['dead_line'];
                                $doneDate = date('Y-m-d H:i', strtotime($deadLine));
                                $categoryId = $value['category_id'];
                                $userId = $value['client_id'];
                                $selectCategory = OrderCategories::find()->where(['order_id' => $clientId, 'status' => 1])->all();
                                $selectClients = Clients::find()->where(['id' => $userId])->one();
                                $userTitle = base64_decode($selectClients->full_name);
                                $txtSend = '';
                                foreach ($selectCategory as $key => $value) {
                                    $categories = $value->category_id;
                                    $selectCategory = Category::find()->where(['id' => $categories, 'status' => 1])->one();
                                    $title = $selectCategory->title;

                                    $txtSend = $txtSend.$title.",";

                                }

                                $strCategories = substr($txtSend,0,-1);


                                Yii::$app->telegram2->sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => "🔤 | ".$clientTitle.PHP_EOL."👤 | ".$userTitle.PHP_EOL."📅 | ".$startDate.PHP_EOL."✅ | ".$doneDate.PHP_EOL."#️⃣ | ".$strCategories,
                                    'reply_markup' => json_encode([
                                        'inline_keyboard'=>[
                                            [
                                                ["text" => "Baholash 🖊", "callback_data" => $clientId."_title"]
                                            ]
                                        ]
                                    ]),
                                ]);
                            }
                        } else {
                            $res = Yii::$app->telegram2->sendMessage ([
                                'chat_id' => $chat_id,
                                'text' => "*Barcha buyurtmalar bajarilib bo'lingan 😊✅*",
                                'parse_mode' => 'markdown' ,
                                'reply_markup' => $remove_keyboard
                            ]);    
                        }
                    } else {
                        $res = Yii::$app->telegram2->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "*Bu botdan 🤖 sotuvchi foydalanishi mumkin emas ☹️❗️*",
                            'parse_mode' => 'markdown'
                        ]);
                    }
                } else {
                    $res = Yii::$app->telegram2->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "*Siz users jadvaliga kritilmagansiz yokida kritishda xatolik bo'lgan ☹️❗️*",
                        'parse_mode' => 'markdown' 
                    ]);
                }
            }   

            $userStep = Step::find()->where(['chat_id' => $chat_id])->one();
            if (isset($userStep)) {
                $step_1 = $userStep->step_1;
                $step_2 = $userStep->step_2;

                if ($step_1 == 0 and $step_2 == 0 and $text != "/start") {
                    if (isset($update->message->contact)) {
                        $userNumber = $update->message->contact->phone_number;
                        $userNumber = str_replace("+","",$userNumber);
                        $selectUsers = Users::find()
                        ->where(["chat_id" => $chat_id, "phone_number" => $userNumber, 'status' => 0]) 
                        ->orwhere(["chat_id" => $chat_id, "phone_number" => $userNumber, 'status' => 1])->one(); 
                        if (isset($selectUsers)) {
                            $userType = $selectUsers->type;
                            if ($userType == 5 || $userType == 6 || $userType == 7) {
                                $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                                $stepCheck->step_1 = 1;
                                $stepCheck->step_2 = 2;
                                $stepCheck->save();

                                $sql = "UPDATE users SET status = 1 where chat_id = ".$chat_id." and phone_number = '".$userNumber."'";
                                $command = Yii::$app->db->createCommand($sql)->queryAll();

                                // $updateUsers = Users::find()->where(["chat_id" => $chat_id, "phone_number" =>$userNumber])->one();
                                // if (isset($updateUsers) and !empty($updateUsers)) {
                                //     $updateUsers->status = 1;
                                //     $updateUsers->save();

                                    // Yii::$app->telegram2->sendMessage([
                                    //     'chat_id' => $chat_id,
                                    //     'text' => 'successFull if',
                                    // ]);  
                                // } else {
                                //     Yii::$app->telegram2->sendMessage([
                                //         'chat_id' => $chat_id,
                                //         'text' => 'Fail',
                                //     ]);   
                                // }
                                $selectUsers = Users::find()->where(["status" => 1, "chat_id" => $chat_id])->one();
                                $userId = $selectUsers->id;
                                $userBranchId = UsersBranch::find()->where(["user_id" => $userId])->one();
                                $branchId = $userBranchId->branch_id;
                                $arr = [];                        
                                $findAllBranchId = UsersBranch::find()->where(['user_id' => $userId])->all();
                                foreach ($findAllBranchId as $key => $value) {
                                    $userBranchId = $value->branch_id;

                                    $arr[] = $userBranchId;    
                                }
                                $userBranch = Orders::find()->where(["status" => 0, "feedback_user" => 1])->
                                andWhere(['in', "branch_id", $arr])->all();

                                if (count($userBranch) != 0) {
                                    Yii::$app->telegram2->sendMessage([
                                        'chat_id' => $chat_id,
                                        'text' => '*Buyurtmalar 📑⤵️*',
                                        'parse_mode' => 'markdown',
                                        'reply_markup' => $remove_keyboard
                                    ]);   
                                    foreach ($userBranch as $key => $value) {
                                        $clientId = $value['id'];
                                        $clientTitle = $value['title'];
                                        $createdDate = $value['created_date'];
                                        $startDate = date('Y-m-d H:i', strtotime($createdDate));
                                        $deadLine = $value['dead_line'];
                                        $doneDate = date('Y-m-d H:i', strtotime($deadLine));
                                        $categoryId = $value['category_id'];
                                        $userId = $value['client_id'];
                                        $selectClients = Clients::find()->where(['id' => $userId])->one();
                                        $userTitle = base64_decode($selectClients->full_name);
                                        $selectCategory = OrderCategories::find()->where(['order_id' => $clientId, 'status' => 1])->all();
                                        $txtSend = '';
                                        foreach ($selectCategory as $key => $value) {
                                            $categories = $value->category_id;
                                            $selectCategory = Category::find()->where(['id' => $categories, 'status' => 1])->one();
                                            $title = $selectCategory->title;

                                            $txtSend = $txtSend.$title.",";

                                        }

                                        $strCategories = substr($txtSend,0,-1);

                                        Yii::$app->telegram2->sendMessage([
                                            'chat_id' => $chat_id,
                                            'text' => "🔤 | ".$clientTitle.PHP_EOL."👤 | ".$userTitle.PHP_EOL."📅 | ".$startDate.PHP_EOL."✅ | ".$doneDate.PHP_EOL."#️⃣ | ".$strCategories,
                                            'reply_markup' => json_encode([
                                                'inline_keyboard'=>[
                                                    [
                                                        ["text" => "Baholash 🖊", "callback_data" => $clientId."_title"]
                                                    ]
                                                ]
                                            ]),
                                        ]);
                                    }
                                } else {
                                    $res = Yii::$app->telegram2->sendMessage ([
                                        'chat_id' => $chat_id,
                                        'text' => "*Barcha buyurtmalar bajarilib bo'lingan 😊✅*",
                                        'parse_mode' => 'markdown' 
                                    ]);    
                                }
                            } else {
                                $res = Yii::$app->telegram2->sendMessage ([
                                    'chat_id' => $chat_id,
                                    'text' => "*Bu botdan 🤖 foydalanish uchun sizda huquq yo'q ☹️❗️*",
                                    'parse_mode' => 'markdown'
                                ]);    
                            }

                        } else {
                            $res = Yii::$app->telegram2->sendMessage ([
                                'chat_id' => $chat_id,
                                'text' => "*Bu botdan 🤖 sotuvchi foydalanishi mumkin emas ☹️❗️*",
                                'parse_mode' => 'markdown'
                            ]);
                        }
                    }
                } 

                if ($step_2 == 8) {
                    if (preg_match("/^[0-9+]*$/",$text)) {
                        $selectLastId = LastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id;
                        $selectSalary = new ClientSalary();
                        $selectSalary->chat_id = $chat_id;
                        $selectSalary->quantity = $text;
                        $selectSalary->order_id = $lastId;
                        $selectSalary->save();
                        
                        $stepCheck = Step::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 9;
                        $stepCheck->save();

                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Moliya bo'limiga yuborish 📑⬆️*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $sendBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Yuborish 📤",'callback_data' => "send_message"]
                                    ]  
                                ]
                            ]),
                        ]);     
                    } else {
                        Yii::$app->telegram2->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Oylikni raqamda kriting ❗️*",
                            'parse_mode' => 'markdown'
                        ]);     
                    }
                }
            }

        }
    }
}