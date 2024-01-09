<?php


namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\Branch;
use app\models\Orders;
use app\models\Clients;
use yii\web\Controller;
use app\models\Category;
use app\models\StepOrder;
use app\models\LastIdOrder;
use app\models\OrderMaterials;
use aki\telegram\base\Command;
// require(Yii::getAlias('@vendor')."/aki/yii2-bot-telegram/base/Command.php");
/**
 * Default controller for the `grapher` module
 */
class DefaultController extends Controller
{
    public $enableCsrfValidation = false;


    public function actionIndex()
    {
        $update = file_get_contents('php://input');
        $update = json_decode($update);
        if (isset($update->callback_query)){
            $data = $update->callback_query->data;
            $chat_id = $update->callback_query->message->chat->id;
            $message_id = $update->callback_query->message->message_id; 
            $userStep = StepOrder::find()->where(['chat_id' => $chat_id])->one();
            if (isset($userStep)) {
                $step_1 = (isset($userStep->step_1)) ? $userStep->step_1 : ' xatolik';
                $step_2 = (isset($userStep->step_1)) ? $userStep->step_2 : ' xatolik';
            }
            $dataArr = explode("_", $data);
            $key = (!empty($dataArr[0])) ? $dataArr[0] : ' xatolik';
            $keySecond = (!empty($dataArr[1])) ? $dataArr[1] : ' xatolik';
            $keyThirst = (!empty($dataArr[2])) ? $dataArr[2] : ' xatolik';
            $branch = $key."_branch";
            $category = $key."_category";
            $clients = $key."_clients";
            $info = $key."_info";
            $ordersdata = $key."_".$keySecond."_".$keyThirst."_orders";
            // $res = Yii::$app->telegram->sendMessage ([
            //     'chat_id' => $chat_id,
            //     'text' => "SuccessFull \n " . $data . PHP_EOL . $step_2,
            // ]);
            
            if ($step_2 == 2) {
                if ($data == $branch) {
                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        if (!empty($sqlStep)) {
                            $sqlStep->step_2 = 3;
                            $sqlStep->save();
                        }
                    }

                    $sqlClient = new Clients();
                    $sqlClient->chat_id = $chat_id;
                    $sqlClient->status = 0;
                    $sqlClient->full_name = "test";
                    $sqlClient->phone_number = "1345213";
                    $sqlClient->branch_id = $key;
                    $sqlClient->save();

                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Iltimos Buyurtma beruvchini Ismini kiritng!"
                    ]);
                }
            }

            if ($step_2 == 5) {
                if ($data == "confirm") {
                    
                    $sqlClient = Clients::find()->where(["chat_id" => $chat_id,"status" => "0"])->one();
                    if (isset($sqlClient)) {
                        if (!empty($sqlClient)) {
                            $sqlClient->status = 1;
                            $branch_id = $sqlClient->branch_id;
                            $last_id = $sqlClient->id;
                            $sqlClient->save();
                           
                        }
                    }
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            [
                                ["text" => "Mijozlar"],["text" => "Mijozlar qo`shish ➕"]
                            ]
                        ],
                        'resize_keyboard' => true
                    ]);
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Foydalanuvchi muvaffaqiyatli qo`shildi!,Marhamat",
                        'reply_markup' => $bekor
                    ]);

                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        if (!empty($sqlStep)) {
                            $sqlStep->step_2 = 1;
                            $sqlStep->save();
                        }
                    }
                } 
                if ($data == "cancel") {
                    $sqlClient = Clients::find()->where(["chat_id" => $chat_id,"status" => 0])->one();
                    if(isset($sqlClient)) {
                        if (!empty($sqlClient)) {
                            $sqlClient->delete();
                        }
                    }
                    $bekor = json_encode($bekor = [
                        'keyboard' => [
                            [
                                ["text" => "Mijozlar"],["text" => "Mijozlar qo`shish ➕"]
                            ]
                        ],
                        'resize_keyboard' => true
                    ]);
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Marhamat",
                        'reply_markup' => $bekor
                    ]);

                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        if (!empty($sqlStep)) {
                            $sqlStep->step_2 = 1;
                            $sqlStep->save();
                        }
                    }
                }
            }
            
            if ($step_2 == 6) {
                if ($data == $branch) {
                
                    $sqlUpdate = StepOrder::find()->where(['chat_id' => $chat_id])->one();
                    if (isset($sqlUpdate)) {
                        $sqlUpdate->step_2 = 7;
                        $sqlUpdate->save();
                    } 
              
                    $user_title = Clients::find()->where(["branch_id" => $key,"status" => 1])->limit(10)->all();
                    $arr_uz = [];
                    $row_arr = [];
                    foreach ($user_title as $key => $value) {
                        $branch_id = $value['id'];
                        $branchTitle = base64_decode($value['full_name']);
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $branch_id."_clients"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $row_arr[] = [["text" => "Keyingi ➡️", "callback_data" => "goToNext"],["text" => "🏘 Bosh menu", "callback_data" => "home"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    $res = Yii::$app->telegram->editMessageText ([
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'text' => "Success ",
                        'reply_markup' => $btnKey
                    ]);
                }
            }

            if ($step_2 == 7) {
                if ($data == $clients) {
                    $sqlUpdate = StepOrder::find()->where(['chat_id' => $chat_id])->one();
                    if (isset($sqlUpdate)) {
                        $sqlUpdate->step_2 = 8;
                        $sqlUpdate->save();
                    } 

                    $sql = "select cl.title , cl.id, o.id as order_id from orders  as o inner join category as cl on o.category_id = cl.id WHERE o.client_id = ".$key;
                    $user_title = Yii::$app->db->createCommand($sql)->queryAll();
                    $json = json_encode($user_title);
                    $arr_uz = [];
                    $row_arr = [];
                    foreach ($user_title as $keys => $value) {
                        $category_id = $value['id'];
                        $order_id = $value['order_id'];
                        $orderTitle = $value['title'];
                        $dataCall = $category_id."_".$order_id."_".$key."_orders";
                        $arr_uz[] = ["text" => "$orderTitle", "callback_data" => $dataCall];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $row_arr[] = [["text" => "Keyingi ➡️", "callback_data" => "goToNext"],["text" => "🏘 Bosh menu", "callback_data" => "home"]];
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                        $res = Yii::$app->telegram->editMessageText([
                            'chat_id' => $chat_id,
                            'text' => "Success \n",
                            'message_id' => $message_id,
                            'reply_markup' => $btnKey 
                        ]);
                }
            }
            if ($step_2 == 8) {
                if ($data == $ordersdata) {
                    $sqlUpdate = StepOrder::find()->where(['chat_id' => $chat_id])->one();
                    if (isset($sqlUpdate)) {
                        $sqlUpdate->step_2 = 9;
                        $sqlUpdate->save();
                    } 
                    $users = Users::find()->where(["chat_id" => $chat_id])->one();
                    if (isset($users) and !empty($users)) {
                        $type =  $users->type;
                    }
                    if ($type == 4) {

                        $orders = Orders::find()->where(["category_id" => $key,'client_id' => $keyThirst])->all();
                        foreach ($orders as $keys => $value) {
                            $sql = "select s.title from orders as o 
                              inner join section_orders as so on o.id = so.order_id
                              inner join sections as s on s.id = so.section_id
                              where o.id =" . $keySecond;
                            $user_title = Yii::$app->db->createCommand($sql)->queryOne();
                            if (isset($user_title) and !empty($user_title) ) {
                                $sectionTitle = $user_title["title"];
                            }

                            $txtSend = "#zakaz \nNomi: ".$value["title"]."\nVaqti: ".$value["created_date"]."\nTugash vaqti: ".$value["dead_line"]."\nIshlayotgan Bo`limi: " . $sectionTitle;
                            $res = Yii::$app->telegram->sendMessage ([
                                'chat_id' => $chat_id,
                                'text' => $txtSend  
                            ]);
                        }
                    } else if ($type == 1 or $type == 2 or $type == 3) {
                        $orders = Orders::find()->where(["category_id" => $key,'client_id' => $keyThirst])->all();
                        foreach ($orders as $keys => $value) {
                            $sql = "select s.title from orders as o 
                              inner join section_orders as so on o.id = so.order_id
                              inner join sections as s on s.id = so.section_id
                              where o.id =" . $keySecond;
                            $user_title = Yii::$app->db->createCommand($sql)->queryOne();
                            if (isset($user_title) and !empty($user_title) ) {
                                $sectionTitle = $user_title["title"];
                            }

                            $clients = Clients::find()->where(["id" => $keyThirst])->one();
                            if (isset($clients) and !empty($clients)) {
                                $name = base64_decode($clients["full_name"]);

                            }

                            $txtSend = "#".$value["title"]." \nIsmi: <b>".$name."</b>\nVaqti: 🕓 <b>".$value["created_date"]."</b>\nTugash vaqti: ⏳ <b>".$value["dead_line"]."</b>\nIshlayotgan Bo`limi: 🔧 <b>" . $sectionTitle."</b>";
                            $dataInfo  = $value["id"]."_info";
                            $dataReady  = $value["id"]."_ready";
                            $readyInfoBtn = json_encode([
                                'inline_keyboard' =>[
                                    [
                                        ['callback_data' => $dataInfo,'text'=>"ℹ️ Ma'lumotlar"],['callback_data'=> $dataReady,'text'=>"✅ Bitdi"]
                                        
                                    ]
                                ]
                            ]);
                            $res = Yii::$app->telegram->sendMessage ([
                                'chat_id' => $chat_id,
                                'text' => $txtSend ,
                                'parse_mode' => "html",
                                'reply_markup' => $readyInfoBtn
                            ]);
                        }
                    }
                }
            }
            if ($step_2 == 9) {
                if ($data == $info) {
                    $OrderMaterials = OrderMaterials::find()->where(["order_id" => $key])->all();
                    if (!empty($OrderMaterials)) {

                    } else {
                        $res = Yii::$app->telegram->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "Malumotlar hozircha yo`q \nMarhamat malumot kiriting!" ,
                        ]);
                    }

                    $sqlUpdate = StepOrder::find()->where(['chat_id' => $chat_id])->one();
                    if (isset($sqlUpdate)) {
                        $sqlUpdate->step_2 = 9;
                        $sqlUpdate->save();
                    } 
                }
            }

        } else {
            $message = Yii::$app->telegram->input->message;
            $text = $message->text;
            $chat_id = $message->chat->id;
            $first_name = $message->chat->first_name;
            $username = $message->chat->username;
            $txtArr = explode(" ", $text);

            $number = 'xatolik';
            if (!empty($txtArr)) {
                $start = (isset($txtArr[0])) ? $txtArr[0] : ' xatolik';
                $number = (isset($txtArr[1])) ? $txtArr[1] : ' xatolik';
            }
                // $res = Yii::$app->telegram->sendMessage ([
                //     'chat_id' => $chat_id,
                //     'text' => "SuccessFull \n " . $text,
                // ]);
            if ($text == $start . " " . $number) { // birinchi startga tekshiriladi keyin ichida referal linkga
                $sql = Users::find()->where(["phone_number" => $number,"status" => "0"])->one();
                if (isset($sql)) {
                    $sql->chat_id = $chat_id;
                    $sql->name = $first_name;
                    $sql->username = $username;
                    $sql->save();

                    $sqlTwo = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if (!isset($sqlTwo)) {
                        $step = new StepOrder();
                        $step->chat_id = $chat_id;
                        $step->step_1 = 0;
                        $step->step_2 = 0;
                        $step->save();

                        $last_id = new LastIdOrder();
                        $last_id->chat_id = $chat_id;
                        $last_id->last_id = 0;
                        $last_id->save();
                    }
                    $replyMarkup = array(
                        'keyboard' => array(
                            array(
                                array( 
                                      'text'=>"📞Telefon raqam qoldirish",
                                      'request_contact'=>true
                                    )
                                ),
                            ),
                        'one_time_keyboard' => true,
                        'resize_keyboard' => true
                    );

                    $encodedMarkup = json_encode($replyMarkup);
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Iltimos teleforn raqamingizni kiriting!",
                        'reply_markup' => $encodedMarkup
                    ]);

                }
            } else if ($text == "/start") {

                $sql = Users::find()->where(["chat_id" => $chat_id,"status" => 1])->one();
                if (isset($sql)) {
                    $type = $sql->type;
                    if ($type == 1 or $type == 2 or $type == 4) {
                        $bekor = json_encode($bekor = [
                            'keyboard' => [
                                [
                                    ["text" => "Mijozlar"],["text" => "Mijozlar qo`shish ➕"]
                                ]
                            ],
                            'resize_keyboard' => true
                        ]);
                    } else {
                        $bekor = json_encode($bekor = [
                            'keyboard' => [
                                ["Mijozlar"]
                            ],
                            'resize_keyboard' => true
                        ]);
                    }

                    $sql->status = 1;
                    $sql->save();
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Marhamat!",
                        'reply_markup' => $bekor
                    ]);
                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        $sqlStep->step_2 = 1;
                        $sqlStep->save();
                    }
                } else {
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Siz bot faoliyatidan foydalana olmaysiz!" 
                    ]);
                }
                $sqlDrop = Clients::find()->where(["status" => 0,"chat_id" => $chat_id])->all();
                foreach ($sqlDrop as $key => $value) {
                    $value->delete();                    
                }
            }    

            $userStep = StepOrder::find()->where(['chat_id' => $chat_id])->one();
            if (isset($userStep)) {
                $step_1 = $userStep->step_1;
                $step_2 = $userStep->step_2;
                $step_1 = (isset($userStep->step_1)) ? $userStep->step_1 : ' xatolik';
                $step_2 = (isset($userStep->step_1)) ? $userStep->step_2 : ' xatolik';
            }
            if ($step_1 == 0 and $text != "/start") {
                if (isset($update->message->contact)) {
                    $number = $update->message->contact->phone_number;
                    $number = str_replace("+","",$number);
                    $sql = Users::find()->where(["chat_id" => $chat_id,"phone_number" => $number])->one();
                    if (isset($sql)) {
                        $type = $sql->type;
                        if ($type == 1 or $type == 2 or $type == 4) {
                            $bekor = json_encode($bekor = [
                                'keyboard' => [
                                    [
                                        ["text" => "Mijozlar"],["text" => "Mijozlar qo`shish ➕"]
                                    ]
                                ],
                                'resize_keyboard' => true
                            ]);
                        } else {
                            $bekor = json_encode($bekor = [
                                'keyboard' => [
                                    ["Mijozlar"]
                                ],
                                'resize_keyboard' => true
                            ]);
                        }

                        $sql->status = 1;
                        $sql->save();
                        $res = Yii::$app->telegram->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "Tekshiruvdan muvaffaqiyatli o`tingiz!\n".$type,
                            'reply_markup' => $bekor
                        ]);
                        $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                        if(isset($sqlStep)) {
                            if (!empty($sqlStep)) {
                                $sqlStep->step_2 = 1;
                                $sqlStep->save();
                            }
                        }
                    } else {
                        $res = Yii::$app->telegram->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => "Siz bu raqam orqali yuqoridagi linkni ishlata olmaysiz",
                        ]);
                    }
                }    
            } 
            if ($step_2 == 1 and $text != "/start"){
                
                if ($text == "Mijozlar") {
                    $user_title = Branch::find()->where(["status" => 1])->all();
                    $arr_uz = [];
                    $row_arr = [];
                    foreach ($user_title as $key => $value) {
                        $branch_id = $value['id'];
                        $branchTitle = $value['title'];
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $branch_id."_branch"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                     $row_arr[] = [["text" => "🏘 Bosh menu", "callback_data" => "home"]];

                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    Yii::$app->telegram->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Filiallar*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => $btnKey
                    ]);
                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        if (!empty($sqlStep)) {
                            $sqlStep->step_2 = 6;
                            $sqlStep->save();
                        }
                    }

                } else if ($text == "Mijozlar qo`shish ➕") {
                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        if (!empty($sqlStep)) {
                            $sqlStep->step_2 = 2;
                            $sqlStep->save();
                        }
                    }

                    $user_title = Branch::find()->where(["status" => 1])->all();
                    $arr_uz = [];
                    $row_arr = [];
                    foreach ($user_title as $key => $value) {
                        $branch_id = $value['id'];
                        $branchTitle = $value['title'];
                        $arr_uz[] = ["text" => "$branchTitle", "callback_data" => $branch_id."_branch"];
                        $row_arr[] = $arr_uz;
                        $arr_uz = [];
                    }
                    $btnKey = json_encode(['inline_keyboard' => $row_arr]);
                    Yii::$app->telegram->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => '*Filiallar*',
                            'parse_mode' => 'markdown',
                            'reply_markup' => $btnKey
                    ]);
                }
            }
            if ($step_2 == 3 and $text != "/start") {
                if (isset($text)) {
                    $txt = base64_encode($text);
                    $updateClients = Clients::find()->where(["chat_id" => $chat_id,'status' => 0])->one();
                    if (isset($updateClients)) {
                        if(!empty($updateClients)) {
                            $updateClients->full_name = $txt;
                            $updateClients->save();
                        }
                    }
                    $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                    if(isset($sqlStep)) {
                        if (!empty($sqlStep)) {
                            $sqlStep->step_2 = 4;
                            $sqlStep->save();
                        }
                    }
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => "Iltimos Buyurtma beruvchini Telefon raqamini kiritng!"
                    ]);
                }
            }
            if ($step_2 == 4 and $text != "/start") {
                if (preg_match("/^[0-9+]*$/",$text)) {
                    $countNumber = strlen($text);
                    if($countNumber == 9) {
                        $txt = base64_encode($text);
                        $updateClients = Clients::find()->where(["chat_id" => $chat_id,'status' => 0])->one();
                        if (isset($updateClients)) {
                            if(!empty($updateClients)) {
                                $updateClients->phone_number = $txt;
                                $updateClients->save();
                            }
                        }
                        $sqlStep = StepOrder::find()->where(["chat_id" => $chat_id])->one();
                        if(isset($sqlStep)) {
                            if (!empty($sqlStep)) {
                                $sqlStep->step_2 = 5;
                                $sqlStep->save();
                            }
                        }
                        $sqlSelect = Clients::find()->where(["chat_id" => $chat_id, "status" => 0])->one();
                        if (isset($sqlSelect)) {
                            if (!empty($sqlSelect)) {
                                $name = base64_decode($sqlSelect->full_name);
                                $number = base64_decode($sqlSelect->phone_number);
                                $branch_id = $sqlSelect->branch_id;
                                $sqlBranch = Branch::find()->where(["id" => $branch_id])->one();
                                if (isset($sqlBranch)) {
                                    if (!empty($sqlBranch)){
                                        $branchTitle = $sqlBranch->title;
                                    }
                                }
                            }
                        }
                        $txtSend = "Malumotlarni tasdiqlang:\nIsmi ".$name."\nRaqami: ".$number."\nFiliali: ".$branchTitle;

                        $deleteAndCheckBtn = json_encode([
                            'inline_keyboard' =>[
                                [
                                    ['callback_data' => "cancel",'text'=>"❌ Bekor qilish"],['callback_data'=> "confirm",'text'=>"✅ Tasdiqlash"]
                                    
                                ]
                            ]
                        ]);
                        $res = Yii::$app->telegram->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => $txtSend,
                            'reply_markup' => $deleteAndCheckBtn
                        ]);         
                    } else {
                        $txtSend = "Iltimos telefon raqaminito`g`ri kiritng!\nMasalan: 998785906";
                        $res = Yii::$app->telegram->sendMessage ([
                            'chat_id' => $chat_id,
                            'text' => $txtSend
                        ]);
                    }
                } else {
                    $txtSend = "Iltimos telefon raqaminito`g`ri kiritng!\nMasalan: 998785906";
                    $res = Yii::$app->telegram->sendMessage ([
                        'chat_id' => $chat_id,
                        'text' => $txtSend
                    ]);
                }
            }
        }
    }
}   

