<?php

namespace app\controllers;
use Yii;

use aki\telegram\base\Command2;
use app\models\Users;
use app\models\Orders;
use app\models\OrderCategories;
use app\models\SectionOrders;
use app\models\Sections;
use app\models\Category;
use app\models\ClientStep;
use app\models\ClientBalls;
use app\models\ClientLastId;
use app\models\ClientRecommendation;
use app\models\FeedbackClient;
use app\models\Clients;

require(Yii::getAlias('@vendor')."/vendor2/autoload.php");

use Dompdf\Dompdf;

class MasterBotController extends \yii\web\Controller
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

            $userStep = ClientStep::find()->where(['chat_id' => $chat_id])->one();
            $step_1 = $userStep->step_1;
            $step_2 = $userStep->step_2;
            $client_id = $userStep->client_id;


            if($step_1 == 0 and $step_2 == 0){
                if ($data == "uzbek_tili") {
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_1 = 1;
                    $stepCheck->step_2 = 0;
                    $stepCheck->save();

                    $replyMarkup = [
                        'keyboard' => [
                            [
                                [
                                    'text' => "Mening buyurtmalarim",
                                ]
                            ],
                        ],
                        'resize_keyboard' => true,
                    ];

                    $encodeMarkup = json_encode($replyMarkup);
                    $res = Yii::$app->telegram3->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => 'O\'zbek tilini tanladingiz',
                        'reply_markup' => $encodeMarkup
                    ]);        
                } 
                else if($data == "rus_tili"){
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_1 = 2;
                    $stepCheck->step_2 = 0;
                    $stepCheck->save();

                    $replyMarkup = [
                        'keyboard' => [
                            [
                                [
                                    'text' => "Мои заказы",
                                ]
                            ],
                        ],
                        'resize_keyboard' => true,
                    ];
                    
                    $encodeMarkup = json_encode($replyMarkup);
                    $res = Yii::$app->telegram3->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => 'Вы выбрали русский язык',
                        'reply_markup' => $encodeMarkup
                    ]);
                }
            }

            if ($step_2 == 1 && preg_match('~^order_\d+$~',$data)) {
                $order_explode = explode("_", $data);
                $order_id = $order_explode[1];
                $ordereCondition = Orders::find()->where(['=','id',$order_id])->andWhere(['=','status',0])->andWhere(['=','feedback_client',1])->andWhere(['=','client_id',$client_id])->one();
                if (isset($ordereCondition) && !empty($ordereCondition)) {

                    $lastIdCheck = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                    if (isset($lastIdCheck)) {
                        $lastIdCheck->last_id = $order_id;
                        $lastIdCheck->last_id_2 = 0;
                        $lastIdCheck->save();

                    } else {
                        $lastId = new ClientLastId();
                        $lastId->chat_id = $chat_id;
                        $lastId->last_id = $order_id;
                        $lastId->last_id_2 = 0;
                        $lastId->save();
                    }
                    $remove_keyboard = array(
                        'remove_keyboard' => true
                    );
                    $remove_keyboard = json_encode($remove_keyboard);
                    if ($step_1 == 1) {
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 2;
                        $stepCheck->save();
    
                        $feedbackUser = FeedbackClient::find()->where(['type' => 1, 'status' => 1])->orderBy(['id' => SORT_ASC])->one();
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);
                        $ballId = $feedbackUser->id;
                        $one = $ballId."_0";
                        $two = $ballId."_5";
    
                        $userBall = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Xa 👍",'callback_data' => $two],
                                    ['text' => "Yo'q 👎",'callback_data' => $one],
                                ]
                            ]
                        ]);
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "*Savollar ❗️*",
                            'parse_mode' => 'markdown',
                        ]);
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*".$baseQuestion."*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $userBall
                        ]);
                    }elseif ($step_1 == 2) {
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 2;
                        $stepCheck->save();

                        $feedbackUser = FeedbackClient::find()->where(['type' => 2, 'status' => 1])->orderBy(['id' => SORT_ASC])->one();
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);
                        $ballId = $feedbackUser->id;
                        $one = $ballId."_0";
                        $two = $ballId."_5";

                        $userBall = json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "Да 👍",'callback_data' => $two],
                                    ['text' => "Нет 👎",'callback_data' => $one],
                                ]
                            ]
                        ]);
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "*Вопросы ❗️*",
                            'parse_mode' => 'markdown',
                        ]);
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*".$baseQuestion."*",
                            'parse_mode' => 'markdown',
                            'reply_markup' => $userBall
                        ]);
                    }
                }else {
                    if ($step_1 == 1) {
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => 'Bu zakazga feedback qoldirish mumkun emas',
                        ]); 
                    }elseif ($step_1 == 2) {
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => 'Оставить отзыв в этом заказе невозможно.',
                        ]); 
                    }
                }
            }else {
                $dataId = explode("_", $data);
                $keyFirst = $dataId[0];
                $dataId_1 = $dataId[0]."_0";
                $dataId_2 = $dataId[0]."_5";
            }

            if ($step_1 == 1 and $step_2 == 2) {
                if ($data == $dataId_1 || $data == $dataId_2) {
                    $feedbackUser = FeedbackClient::find()->where(['type' => 1, 'status' => 1])->andWhere(['>', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                    if(isset($feedbackUser)){
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);

                        $ballId = $feedbackUser->id;
                        $one = $ballId."_0";
                        $two = $ballId."_5";


                        $selectFeedback = FeedbackClient::find()->where(['id' => $keyFirst, 'status' => 1, 'type' => 1])->one();
                        $feedbackId = $selectFeedback->id;

                        $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id_2;
                        $lastId2 = $selectLastId->last_id;

                        $selectBall = ClientBalls::find()->where(['feedback_client_id' => $feedbackId, 'chat_id' => $chat_id, 'order_id' => $lastId2])->one();   

                        $ballExplode = explode("_", $data);
                        if (!isset($selectBall)) {

                            $userBall = new ClientBalls();
                            $userBall->feedback_client_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId2;
                            $userBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Xa 👍",'callback_data' => $two],
                                        ['text' => "Yo'q 👎",'callback_data' => $one],
                                    ]
                                ]
                            ]);

                            Yii::$app->telegram3->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            $selectBall->ball = $ballExplode[1];
                            $selectBall->created_date = date("Y-m-d H:i:s");
                            $selectBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Xa 👍",'callback_data' => $two],
                                        ['text' => "Yo'q 👎",'callback_data' => $one],
                                    ]
                                ]
                            ]);

                            Yii::$app->telegram3->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        }
                    } else {
                        $feedbackUser = FeedbackClient::find()->where(['type' => 1, 'status' => 1])->andWhere(['=', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_0";
                            $two = $ballId."_5";

                            $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                            $lastId = $selectLastId->last_id_2;
                            $lastId2 = $selectLastId->last_id;

                            $ballExplode = explode("_", $data);

                            $userBall = new ClientBalls();
                            $userBall->feedback_client_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId2;
                            $userBall->save();
                        }

                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Bizni kimlarga tavsiya qilasiz ❓🗣*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => json_encode([
                                'inline_keyboard'=>[
                                    [
                                        ['text'=>"Tavsiya qilish 🧾",'callback_data'=> "recommendation_user"],
                                        ['text'=>"Tugatish ✅",'callback_data'=> "finish_question"]
                                    ]
                                ]
                            ]),
                        ]); 

                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 3;
                        $stepCheck->save(); 
                    }
                }
            } 

            if ($step_1 == 2 and $step_2 == 2) {
                if ($data == $dataId_1 || $data == $dataId_2) {
                    $feedbackUser = FeedbackClient::find()->where(['type' => 2, 'status' => 1])->andWhere(['>', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                    if(isset($feedbackUser)){
                        $question = $feedbackUser->title;
                        $baseQuestion = base64_decode($question);

                        $ballId = $feedbackUser->id;
                        $one = $ballId."_0";
                        $two = $ballId."_5";


                        $selectFeedback = FeedbackClient::find()->where(['id' => $keyFirst, 'status' => 1, 'type' => 2])->one();
                        $feedbackId = $selectFeedback->id;

                        $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id_2;
                        $lastId2 = $selectLastId->last_id;

                        $selectBall = ClientBalls::find()->where(['feedback_client_id' => $feedbackId, 'chat_id' => $chat_id, 'order_id' => $lastId2])->one();   

                        $ballExplode = explode("_", $data);
                        if (!isset($selectBall)) {

                            $userBall = new ClientBalls();
                            $userBall->feedback_client_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId2;
                            $userBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Да 👍",'callback_data' => $two],
                                        ['text' => "Нет 👎",'callback_data' => $one],
                                    ]
                                ]
                            ]);

                            Yii::$app->telegram3->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);
                        } else {
                            $selectBall->ball = $ballExplode[1];
                            $selectBall->created_date = date("Y-m-d H:i:s");
                            $selectBall->save();

                            $userBall = json_encode([
                                'inline_keyboard' => [
                                    [
                                        ['text' => "Xa 👍",'callback_data' => $two],
                                        ['text' => "Yo'q 👎",'callback_data' => $one],
                                    ]
                                ]
                            ]);

                            Yii::$app->telegram3->sendMessage([
                                'chat_id' => $chat_id,
                                'text' => "*".$baseQuestion."*",
                                'parse_mode' => 'markdown',
                                'reply_markup' => $userBall
                            ]);        
                        }
                    } else {
                        $feedbackUser = FeedbackClient::find()->where(['type' => 2, 'status' => 1])->andWhere(['=', 'id', $dataId[0]])->orderBy(['id' => SORT_ASC])->one();
                        if(isset($feedbackUser)){
                            $question = $feedbackUser->title;
                            $baseQuestion = base64_decode($question);

                            $ballId = $feedbackUser->id;
                            $one = $ballId."_0";
                            $two = $ballId."_5";

                            $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                            $lastId = $selectLastId->last_id_2;
                            $lastId2 = $selectLastId->last_id;

                            $ballExplode = explode("_", $data);

                            $userBall = new ClientBalls();
                            $userBall->feedback_client_id = $keyFirst;
                            $userBall->ball = $ballExplode[1];
                            $userBall->created_date = date("Y-m-d H:i:s");
                            $userBall->chat_id = $chat_id;
                            $userBall->order_id = $lastId2;
                            $userBall->save();
                        }

                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Кому вы нас рекомендуете ❓🗣*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => json_encode([
                                'inline_keyboard'=>[
                                    [
                                        ['text'=>"Рекомендую 🧾",'callback_data'=> "recommendation_user"],
                                        ['text'=>"Закончить ✅",'callback_data'=> "finish_question"]
                                    ]
                                ]
                            ]),
                        ]); 

                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 3;
                        $stepCheck->save(); 
                    }
                }    
            }

            if ($step_1 == 1 and $step_2 == 3) {
                if ($data == "recommendation_user") {
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 4;
                    $stepCheck->save();
                    Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "Tasiya qilmoqchi bo'lgan insoningizni ismini kriting ✍️",
                        'parse_mode' => 'markdown'
                    ]);    
                }
                else if ($data == "finish_question") {
                    $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                    $lastId = $selectLastId->last_id;
                    $selectOrder = Orders::find()->where(['id' => $lastId, 'status' => 0, 'client_id' => $client_id])->one();
                    $selectOrder->feedback_client = 0;
                    $selectOrder->save();

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
                        .all-widt{
                            width: 600px;
                            border: 1px solid;
                        }
                      </style>
                      <h2 align="center">Mioz fikri</h2>'; 

                    $sql = "
                    SELECT 
                    o.id,
                    o.title,
                    o.created_date, 
                    o.dead_line,
                    cl.full_name,
                    cat.title AS category_name
                    FROM orders o
                    INNER JOIN 
                    client_last_id AS l
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
                    $html .= 
                    '<tr>
                        <td><b>Savol</b></td>
                        <td><b>Javob</b></td>
                    </tr>';

                    $id =  $order_info['id'];

                    $sql_ball = "
                    SELECT 
                    cb.ball,
                    f.title
                    FROM client_balls AS cb
                    INNER JOIN feedback_client AS f
                    ON f.id = cb.feedback_client_id
                    WHERE cb.order_id = " . $id;

                    $balls = Yii::$app->db->createCommand($sql_ball)->queryAll();

                    if (isset($balls) && !empty($balls)) {
                        foreach ($balls as $ball) {
                            $a = ($ball['ball'] == 5) ? "Xa" : "Yo`q";
                            $html .= 
                            '<tr>
                                <td>'. base64_decode($ball["title"]) .'</td>
                                <td>'.$a.'</td>
                            </tr>';
                        }
                    }



                    $friends = ClientRecommendation::find()->where(['order_id'=>$order_info['id']])->all();

                    if (isset($friends) && !empty($friends)) {
                        $html .= '</table><br>';
                        $html .= '<h3 align="center">Taklif qilgan do\'stlari</h3>';
                        $html .= '<br><table>';
                        $html .= 
                        '<tr>
                            <td><b>Ismi</b></td>
                            <td><b>Telefon raqami</b></td>
                        </tr>';
                        foreach ($friends as $f) {
                            $html .= 
                            '<tr>
                                <td>'. base64_decode($f->full_name) .'</td>
                                <td>'.$f->phone_number.'</td>
                            </tr>';
                        }
                    } 

                    $html .= '</table>';

                    $_file_name = strtotime("Y-m-d H:i:s") . rand(1000, 9999);
                    $doc_name = "original-mebel-client-bot_" . rand(1000, 9999) . '_' . $_file_name;

                    $pdf->loadHtml($html); 

                    // (Optional) Setup the paper size and orientation
                    $pdf->setPaper('A4');

                    // Render the HTML as PDF
                    $pdf->render();
                    file_put_contents('fpdf/uploads/'.$doc_name.'.pdf', $pdf->output());

                    $res = Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "*Katta rahmat 😊❗️*",
                        'parse_mode' => 'markdown'
                    ]);

                    Yii::$app->telegram3->sendDocument([
                        'chat_id' => Users::ADMIN_ID,
                        'document' => 'https://app.original-mebel.uz/fpdf/uploads/'.$doc_name.'.pdf',
                    ]);
                    
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 0;
                    $stepCheck->save();
                }
            }

            if ($step_1 == 2 and $step_2 == 3) {
                if ($data == "recommendation_user") {
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 4;
                    $stepCheck->save();
                    Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "Укажите имя человека, которого хотите порекомендовать ✍️",
                        'parse_mode' => 'markdown'
                    ]);    
                }
                else if ($data == "finish_question") {
                    $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                    $lastId = $selectLastId->last_id;
                    $selectOrder = Orders::find()->where(['id' => $lastId, 'status' => 0, 'client_id' => $client_id])->one();
                    $selectOrder->feedback_client = 0;
                    $selectOrder->save();
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 1;
                    $stepCheck->save();
                    $res = Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "*Большое спасибо 😊❗️*",
                        'parse_mode' => 'markdown'
                    ]);    
                }
            }

            if ($step_1 == 1 and $step_2 == 6) {
                if ($data == "confirm") {
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 3;
                    $stepCheck->save();
                    Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => '*Yana kimgadir tavsiya qilishni hohlaysizmi ❗️😁*',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard'=>[
                                [
                                    ['text'=>"Tavsiya qilish 🧾",'callback_data'=> "recommendation_user"],
                                    ['text'=>"Tugatish ✅",'callback_data'=> "finish_question"]
                                ]
                            ]
                        ]),
                    ]);    
                } 
                else if ($data == "cancle") {
                    $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                    $lastId = $selectLastId->last_id_2;
                    $selectClients = Clients::find()->where(['chat_id' => $chat_id])->one();
                    $idClient = $selectClients->id;
                    $selectRec = ClientRecommendation::find()->where(["id" => $lastId, 'client_id' => $idClient])->one();
                    $selectRec->delete();
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 3;
                    $stepCheck->save();
                    Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => '*Bizni kimlarga tavsiya qilasiz ❓🗣 *',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard'=>[
                                [
                                    ['text'=>"Tavsiya qilish 🧾",'callback_data'=> "recommendation_user"],
                                    ['text'=>"Tugatish ✅",'callback_data'=> "finish_question"]
                                ]
                            ]
                        ]),
                    ]);
                }
            }

            if ($step_1 == 2 and $step_2 == 6) {
                if ($data == "confirm") {
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 3;
                    $stepCheck->save();
                    Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => '*Хотели бы вы порекомендовать это кому-нибудь еще ❗️😁*',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard'=>[
                                [
                                    ['text'=>"Рекомендую 🧾",'callback_data'=> "recommendation_user"],
                                    ['text'=>"Закончить ✅",'callback_data'=> "finish_question"]
                                ]
                            ]
                        ]),
                    ]);    
                } 
                else if ($data == "cancle") {
                    $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                    $lastId = $selectLastId->last_id_2;
                    $selectClients = Clients::find()->where(['chat_id' => $chat_id])->one();
                    $idClient = $selectClients->id;
                    $selectRec = ClientRecommendation::find()->where(["id" => $lastId, 'client_id' => $idClient])->one();
                    $selectRec->delete();
                    $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                    $stepCheck->step_2 = 3;
                    $stepCheck->save();
                    Yii::$app->telegram3->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => '*Хотели бы вы порекомендовать это кому-нибудь еще ❓🗣 *',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard'=>[
                                [
                                    ['text'=>"Рекомендую 🧾",'callback_data'=> "recommendation_user"],
                                    ['text'=>"Закончить ✅",'callback_data'=> "finish_question"]
                                ]
                            ]
                        ]),
                    ]);
                }
            }
        } else {
            $message = Yii::$app->telegram->input->message;
            $text = $message->text;
            $chat_id = $message->chat->id;
            $first_name = $message->chat->first_name;
            $username = $message->chat->username;


            $remove_keyboard = array(
                'remove_keyboard' => true
            );
            $remove_keyboard = json_encode($remove_keyboard);

            if ($text == '/start') {
                $userStepChange = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                if (isset($userStepChange) && !empty($userStepChange)) {
                    $userStepChange->step_1 = 0;
                    $userStepChange->step_2 = 0;
                    $userStepChange->save();
                    $res = Yii::$app->telegram3->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => '🇺🇿 | Botga hush kelibsiz '.PHP_EOL.'🇷🇺 | Добро пожаловать в бот',
                        'reply_markup' => $remove_keyboard,
                    ]);                
                    $res = Yii::$app->telegram3->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => '*🇺🇿 | Tilni tanlang 👇*'.PHP_EOL.PHP_EOL.'*🇷🇺 | Выберите язык 👇*',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                        'inline_keyboard'=>[
                                [
                                    ['text'=>"🇺🇿 | O'zbek tili",'callback_data'=> 'uzbek_tili'],
                                    ['text'=>"🇷🇺 | Русский язык",'callback_data'=> 'rus_tili']
                                ]
                            ]
                        ]),
                    ]);                
                }else {
                    $res = Yii::$app->telegram3->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => '*🇺🇿 | Assalomu alaykum Shu botdan foydalanishdan oldin telefon raqam jonating*'.PHP_EOL.'*🇷🇺 | Здравствуйте, пожалуйста, пришлите номер телефона перед использованием этого бота*',
                        'parse_mode' => 'markdown',
                        'reply_markup' => json_encode([
                            'keyboard' =>[
                                [
                                    ['text' => '📱 telefon raqam jonatish | 📱отправить номер телефона','request_contact'=>true],
                                ]
                            ],
                            'resize_keyboard' => true
                        ])
                    ]);
                }
            } 


            $userStep = ClientStep::find()->where(['chat_id' => $chat_id])->one();
            if (isset($userStep) && !empty($userStep)) {
                $step_1 = $userStep->step_1;
                $step_2 = $userStep->step_2;
                $client_id = $userStep->client_id;
            } else {
                if (isset($update->message->contact)) {
                    $userNumber = $update->message->contact->phone_number;
                    $userNumber = base64_encode(str_replace("+","",$userNumber));
                    $selectUsers = Clients::find()->where(["phone_number" => $userNumber])->one();
                    if (isset($selectUsers) && !empty($selectUsers)) {
                        $selectUsers->chat_id = $chat_id;
                        $selectUsers->save();
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => 'Siz botga muvafaqiyatli kirdingiz',
                            'reply_markup' => $remove_keyboard,
                        ]);    
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => '*🇺🇿 | Tilni tanlang 👇*'.PHP_EOL.PHP_EOL.'*🇷🇺 | Выберите язык 👇*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => json_encode([
                            'inline_keyboard'=>[
                                    [
                                        ['text'=>"🇺🇿 | O'zbek tili",'callback_data'=> 'uzbek_tili'],
                                        ['text'=>"🇷🇺 | Русский язык",'callback_data'=> 'rus_tili']
                                    ]
                                ]
                            ]),
                        ]);
                        $insertClientsStep = new ClientStep();
                        $insertClientsStep->chat_id = $chat_id;
                        $insertClientsStep->client_id = $selectUsers->id;
                        $insertClientsStep->step_1 = 0;
                        $insertClientsStep->step_2 = 0;
                        $insertClientsStep->save(); 
                    }else {
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => '*🇺🇿 | Siz shu botdan foydalanish huquqi yoq'.PHP_EOL.'*🇷🇺 | У вас нет разрешения на использование этого бота',
                            'parse_mode' => 'markdown',
                            'reply_markup' => $remove_keyboard
                        ]);
                    }
                }
            }

            if (isset($step_1) && (!empty($step_1) || $step_1 == 0) && isset($step_2) && (!empty($step_2) || $step_2 == 0)) {
                if ($step_1 == 1 and ($step_2 == 0 || $step_2 == 1) and $text == "Mening buyurtmalarim") {
                    $haveOrders = Orders::find()->where(['=','client_id',$client_id])->andWhere(['=','feedback_client',1])->all();
                    if (isset($haveOrders) && !empty($haveOrders)) {
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 1;
                        $stepCheck->save();
                        foreach ($haveOrders as $orderValue) {
                            $findCategory = OrderCategories::find()->where(['=','order_id',$orderValue->id])->one();
                            $findSections = SectionOrders::find()->where(['=','order_id',$orderValue->id])->one();
                            $category_title = '';
                            $sections_title = '';
                            
                            if (isset($findCategory) && !empty($findCategory)) {
                                $categoryFind = Category::find()->where(['=','id',$findCategory->category_id])->one();
                                if (isset($categoryFind) && !empty($categoryFind)) {
                                    $category_title = $categoryFind->title;
                                }
                            }
                            
                            if (isset($findSections) && !empty($findSections)) {
                                $sectionFind = Sections::find()->where(['=','id',$findSections->section_id])->one();
                                if (isset($sectionFind) && !empty($sectionFind)) {
                                    $sections_title = $sectionFind->title;
                                } 
                            }

                            $text = 'Nomi: '.$orderValue->title.PHP_EOL.PHP_EOL.
                                    'Kategoriya: '.$category_title.PHP_EOL.PHP_EOL.
                                    'Bolim: '.$sections_title.PHP_EOL.PHP_EOL.
                                    'Boshlanish vaqt: '.date('Y-m-d H:i',strtotime($orderValue->created_date)).PHP_EOL.PHP_EOL.
                            'Tugalanish vaqt: '.date('Y-m-d H:i',strtotime($orderValue->dead_line));
                            if ($orderValue->feedback_client == 1 && $orderValue->status == 0) {
                                $res = Yii::$app->telegram3->sendMessage ([
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                    'reply_markup' => json_encode([
                                        'inline_keyboard'=>[
                                            [
                                                ['text'=>"Ovoz Berish",'callback_data'=> 'order_'.$orderValue->id],
                                            ]
                                        ]
                                    ]),
                                ]);
                            }else {
                                $res = Yii::$app->telegram3->sendMessage ([
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            }
                        }
                    }else {
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => 'Sizda buyurtma yoq',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        [
                                            'text' => "Mening buyurtmalarim",
                                        ]
                                    ],
                                ],
                                'resize_keyboard' => true,
                            ])
                        ]);
                    }
                }

                if ($step_1 == 2 and ($step_2 == 0 || $step_2 == 1) and $text == "Мои заказы") {
                    $haveOrders = Orders::find()->where(['=','client_id',$client_id])->andWhere(['=','feedback_client',1])->all();
                    if (isset($haveOrders) && !empty($haveOrders)) {
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 1;
                        $stepCheck->save();
                        foreach ($haveOrders as $orderValue) {
                            $findCategory = OrderCategories::find()->where(['=','order_id',$orderValue->id])->one();
                            $findSections = SectionOrders::find()->where(['=','order_id',$orderValue->id])->one();
                            $category_title = '';
                            $sections_title = '';
                
                            if (isset($findCategory) && !empty($findCategory)) {
                                $categoryFind = Category::find()->where(['=','id',$findCategory->category_id])->one();
                                if (isset($categoryFind) && !empty($categoryFind)) {
                                    $category_title = $categoryFind->title;
                                }
                            }
                
                            if (isset($findSections) && !empty($findSections)) {
                                $sectionFind = Sections::find()->where(['=','id',$findSections->section_id])->one();
                                if (isset($sectionFind) && !empty($sectionFind)) {
                                    $sections_title = $sectionFind->title;
                                } 
                            }
                            $text = 'Название: '.$orderValue->title.PHP_EOL.PHP_EOL.
                                    'Категория: '.$category_title.PHP_EOL.PHP_EOL.
                                    'Отдел: '.$sections_title.PHP_EOL.PHP_EOL.
                                    'Время начала: '.date('Y-m-d H:i',strtotime($orderValue->created_date)).PHP_EOL.PHP_EOL.
                            'Время завершения: '.date('Y-m-d H:i',strtotime($orderValue->dead_line));
                            if ($orderValue->feedback_client == 1 && $orderValue->status == 0) {
                                $res = Yii::$app->telegram3->sendMessage ([
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                    'reply_markup' => json_encode([
                                        'inline_keyboard'=>[
                                            [
                                                ['text'=>"Проголосовать",'callback_data'=> 'order_'.$orderValue->id],
                                            ]
                                        ]
                                    ]),
                                ]);
                            }else {
                                $res = Yii::$app->telegram3->sendMessage ([
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            }
                        }
                    }else {
                        $res = Yii::$app->telegram3->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => 'У вас нет заказов',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        [
                                            'text' => "Мои заказы",
                                        ]
                                    ],
                                ],
                                'resize_keyboard' => true,
                            ])
                        ]);
                    }
                }

                if ($step_1 == 1 and $step_2 == 4 and $text != "/start") {
                    if (ctype_alpha($text) !== false) {
                        $selectClients = Clients::find()->where(['chat_id' => $chat_id, 'status' => 1])->one();
                        if (isset($selectClients)) {
                           $clientId = $selectClients->id;
                        }
                        $order_id = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
    
                        $insertClients = new ClientRecommendation();
                        $insertClients->full_name = base64_encode($text);
                        $insertClients->client_id = $clientId;
                        $insertClients->order_id = $order_id->last_id;
                        $insertClients->save();
    
                        $clientLastId = ClientRecommendation::find()->where(['client_id' => $clientId])->orderBy(['id' => SORT_DESC])->one();
                        $recId = $clientLastId->id;
    
                        $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                        $selectLastId->last_id_2 = $recId;
                        $selectLastId->save();
    
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 5;
                        $stepCheck->save();
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "Tavsiya qilmoqchi bo'lgan insoningizni telefon 📱 raqamini kritning ✍️",
                            'parse_mode' => 'markdown'
                        ]);     
                    } else {
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Ismini to'g'ri kriting ❗️*",
                            'parse_mode' => 'markdown'
                        ]);     
                    }
                }
    
                if ($step_1 == 2 and $step_2 == 4 and $text != "/start") {
                    if (ctype_alpha($text) !== false) {
                        $selectClients = Clients::find()->where(['chat_id' => $chat_id, 'status' => 1])->one();
                        $clientId = $selectClients->id;
                        $order_id = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
    
                        $insertClients = new ClientRecommendation();
                        $insertClients->full_name = base64_encode($text);
                        $insertClients->client_id = $clientId;
                        $insertClients->order_id = $order_id->last_id;
                        $insertClients->save();
    
                        $clientLastId = ClientRecommendation::find()->where(['client_id' => $clientId])->orderBy(['id' => SORT_DESC])->one();
                        $recId = $clientLastId->id;
    
                        $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                        $selectLastId->last_id_2 = $recId;
                        $selectLastId->save();
    
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 5;
                        $stepCheck->save();
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "Укажите номер телефона человека, которого хотите порекомендовать ✍️",
                            'parse_mode' => 'markdown'
                        ]);     
                    } else {
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Укажите имя правильно ❗️*",
                            'parse_mode' => 'markdown'
                        ]);     
                    }            
                }
    
                if ($step_1 == 1 and $step_2 == 5 and $text != "/start") {
                    if(ctype_digit($text)!== false && mb_stripos($text, "9989") !== false && strlen($text) == 12){
                        $selectClients = Clients::find()->where(['chat_id' => $chat_id, 'status' => 1])->one();
                        $clientId = $selectClients->id;
                        $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id_2;
                        $insertClients = ClientRecommendation::find()->where(['client_id' => $clientId, 'id' => $lastId])->one();
                        $insertClients->phone_number = $text;
                        $insertClients->save();
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 6;
                        $stepCheck->save();
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Katta rahmat ❗️ '.PHP_EOL.'Tavsiyangizni tasdiqlang ✅*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => json_encode([
                                'inline_keyboard'=>[
                                    [
                                        ['text'=>"Bekor qilish ❌",'callback_data'=> "cancle"],
                                        ['text'=>"Tasdiqlash ✅",'callback_data'=> "confirm"]
                                    ]
                                ]
                            ]),
                        ]);        
                    } else {
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Raqamni to'g'ri kritning ❗️*".PHP_EOL."*Namuna: 998912345678*",
                            'parse_mode' => 'markdown',
                        ]);   
                    }
                }
    
                if ($step_1 == 2 and $step_2 == 5 and $text != "/start") {
                    if(preg_match("/^[0-9+]*$/",$text)){
                        $selectClients = Clients::find()->where(['chat_id' => $chat_id, 'status' => 1])->one();
                        $clientId = $selectClients->id;
                        $selectLastId = ClientLastId::find()->where(['chat_id' => $chat_id])->one();
                        $lastId = $selectLastId->last_id_2;
                        $insertClients = ClientRecommendation::find()->where(['client_id' => $clientId, 'id' => $lastId])->one();
                        $insertClients->phone_number = $text;
                        $insertClients->save();
                        $stepCheck = ClientStep::find()->where(['chat_id' => $chat_id])->one();
                        $stepCheck->step_2 = 6;
                        $stepCheck->save();
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Большое спасибо ❗️ '.PHP_EOL.'Подтвердите вашу рекомендацию ✅*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => json_encode([
                                'inline_keyboard'=>[
                                    [
                                        ['text'=>"Отмена ❌",'callback_data'=> "cancle"],
                                        ['text'=>"Подтверждение ✅",'callback_data'=> "confirm"]
                                    ]
                                ]
                            ]),
                        ]);        
                    } else {
                        Yii::$app->telegram3->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "*Укажите число правильно ❗️*".PHP_EOL."*Например: 998912345678*",
                            'parse_mode' => 'markdown',
                        ]);   
                    }
                }
            }

        }
    }
}