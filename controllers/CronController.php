<?php

namespace app\controllers;
use app\models\Users;
use app\models\BotUsers;
use app\models\UsersSection;
use app\models\UserPenalties;
use Yii;

function different_day($deadline){
  $endDate = date('Y-m-d H:i:s');
  $startDate = date($deadline);
  $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);

  // $start  = date_create(date('Y-m-d H:i:s'));
  // $end    = date_create(date("2021-09-19 10:30:00"));
  // $diff   = date_diff( $start, $end );
  // $day = $diff->d;

  return  floor($days);

}


class CronController extends \yii\web\Controller
{
  public function actionIndex()
  {

    // Husniddin Latipov 325
    // Ibrohim Tursunov 47
    // Zebo Murаdova 266
    // Zoxid Toirov 102
    // Ilxom Abraev 95
    // Ilg'or Maxkamov2 49
    // Muzaffar shkurka 53
    // Ilg'or Maxkamov 76
    // Soxib kraskachi 51
    // Aziz kraskachi 52
    // Ibrohim shaxsiy nazorat 247

    // $user_id = 325;
    // $section_id = 36;

    // $transaction = Yii::$app->db->beginTransaction();
    // try {
    //   $new_section_user = new UsersSection();
    //   $new_section_user->user_id = $user_id;
    //   $new_section_user->section_id = $section_id;
    //   $user = Users::findOne($user_id);
    //   $bot_user = BotUsers::find()->where(['user_id' => $user_id])->one();
    //   if (!isset($bot_user)) {
    //       $bot_user = new BotUsers();
    //       $bot_user->user_id = $user_id;
    //       $bot_user->bot_id = 1;
    //       $bot_user->save();
    //   }
    //   $new_section_user->bot_users_id = $bot_user->id;
    //   $new_section_user->role = $user->type;
    //   $new_section_user->save(); 
    //   $transaction->commit();
    // } 
    // catch (Exception $e) {
    //   $transaction->rollBack();
    //   throw $e;
    // }
    
     

  }

  public function actionInsert()
  {
    $conn = pg_connect("host=localhost dbname=vkoenlqd_original_db user=vkoenlqd_original_user password=_(1[F#b@D{Sd");

    $sql = "SELECT
    ord.id AS orders_id, 
    os.deadline,
    ord.title as order_name ,
    u.username,
    u.chat_id,
    u.id AS user_idd,
    so.id,
    so.section_id AS ssection_id,
    s.title,
    u.id AS user_id,
    s.id AS section_id,
    st.work_time,
    so.enter_date,
    min.penalty_summ,
    min.bonus_sum,
    st.section_id AS s_id,
    sm.user_id AS minimal_user_id,
    sm.id AS minimal_id,
    ord.created_date AS order_date
    FROM orders AS ord 
    INNER JOIN section_orders AS so ON ord.id = so.order_id
    INNER JOIN order_step AS os ON ord.id = os.order_id AND os.section_id = so.section_id
    INNER JOIN sections AS s ON so.section_id = s.id
    INNER JOIN users_section AS us ON s.id = us.section_id
    INNER JOIN users AS u ON us.user_id = u.id AND u.chat_id IS NOT NULL
    INNER JOIN section_times AS st ON st.section_id = s.id
    LEFT JOIN section_minimal AS sm ON sm.section_id = s.id --AND sm.user_id = u.id
    LEFT JOIN minimal AS min ON min.id = sm.minimal_id
    WHERE so.exit_date IS NULL AND ord.pause = 0 ORDER BY order_date";

  
    $result = pg_query($conn,$sql);

  
  
    if (pg_num_rows($result) > 0) {
      $sqlTxt = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES ";
      $sqlTxt2 = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES ";
      $arr = [];
      $arr_pen = [];
      
      $penalties = UserPenalties::find()->all();
      
      if (!empty($penalties)) {
        foreach ($penalties as $key => $value) {
          $value->delete();
        }
      }

      while ($row = pg_fetch_assoc($result)) {
        $diff_day = different_day($row['enter_date']);
        $work_time = $diff_day - ($row['work_time']/24);




        $new_time = date("Y-m-d H:i:s", strtotime('+'.$row['work_time'].' hours', strtotime($row['enter_date'])));

        $penalty_sum = 0;
        if($work_time > 0 && !empty($row['penalty_summ'])){
          if ($work_time <= 3) {
            $penalty_sum = ($work_time*24)*$row['penalty_summ'];
          }
          else if($work_time > 3 && $work_time <= 6){
            $penalty_sum = ($work_time*24)*3000;  
          }
          else if($work_time > 6){
            $penalty_sum = ($work_time*24)*5000;  
          }
        }
        
        if ($work_time > 0) {
          $arr_pen[] = [
            'user_id' => $row['user_idd'],
            'order_id' => $row['orders_id'],
            'sum' => $penalty_sum,
            'day' => $work_time,
          ];
          
        }
        $penalty_sum = number_format($penalty_sum,0,'',' ');
        $time = $row["enter_date"];
        $sectoion_title = $row["title"];
        $chat_id_insert_admin = 1270367;
        $chat_id_insert = $row["chat_id"];

        

        
        // $chat_id_insert = 284914591;
        // $chat_id_insert_admin = 284914591;
        

        $date = date("Y-m-d",strtotime(date($time)));     
        $yestrday = date("Y-m-d",(strtotime("-1 day")));
        $today = date("Y-m-d",(strtotime("now")));
        $nextDay = date("Y-m-d",(strtotime("+1 day")));

        // echo $date."-----".$yestrday;

        if (strtotime($new_time) == strtotime($nextDay)) {
          
          $title = $row["order_name"]." nomli buyurtma ertaga ".$sectoion_title." bo'limidan chiqib ketishiga 1 kun qoldi!";
          // $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
          // $resultInsert = pg_query($conn,$sqlInsert);
        } else if (strtotime($new_time) == strtotime($today)) {
          
          $title = $row["order_name"]." nomli buyurtma bugun ".$sectoion_title." bo'limidan chiqib ketishi kerak";
          // $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
          // $resultInsert = pg_query($conn,$sqlInsert);
        } else if (strtotime($new_time) == strtotime($yestrday)) {
          
          $title = $row["order_name"]." nomli buyurtma ".$sectoion_title." bo'limidan 1 kun kechga qolyaptdi! ".(($penalty_sum) ? " 
            💴 <b>Hozirgi vaqtgacha Jarima: ".$penalty_sum." so'm</b>" : '' );
          // $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
          // $resultInsert = pg_query($conn,$sqlInsert);
        }  else if (strtotime($new_time) < strtotime($yestrday)) {
          
          $title = $row["order_name"]." nomli buyurtma ".$sectoion_title." bo`limidan ".$new_time." kuni  chiqib ketishi kerak edi! ".(($penalty_sum) ? " 
            💴 <b>Hozirgi vaqtgacha Jarima: ".$penalty_sum." so'm</b>" : '' );
          // $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
          // $resultInsert = pg_query($conn,$sqlInsert);

          
        }
        $title = str_replace("'", "`", $title);
        $sqlTxt = $sqlTxt.PHP_EOL."(".$chat_id_insert.",'".$title."',0),";
        if (!in_array($row['orders_id'], $arr)) {
          $sqlTxt2 = $sqlTxt2.PHP_EOL."(".$chat_id_insert_admin.",'".$title."',0),";
          $arr[] = $row['orders_id'];
        }
        

      }


        $str = substr($sqlTxt,0,-1);
        $str2 = substr($sqlTxt2,0,-1);
        
        $resultInsert = pg_query($conn,$str);
        $resultInsert2 = pg_query($conn,$str2);
      }


      if (!empty($arr_pen)) {
        
        foreach ($arr_pen as $key2 => $value2) {
          $penalties2 = new UserPenalties();
          $penalties2->user_id = $value2['user_id'];
          $penalties2->order_id = $value2['order_id'];
          $penalties2->sum = $value2['sum'];
          $penalties2->delay_day_count = $value2['day'];
          if(!$penalties2->save())
            pre($penalties2->errors);
          
        }
      }


      // pre($arr_pen);

  }

}