<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;


class InsertController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $sql = "SELECT 
        os.deadline,ord.title,u.username,u.chat_id,so.id
        FROM orders AS ord 
        INNER JOIN section_orders AS so ON ord.id = so.order_id
        INNER JOIN order_step AS os ON ord.id = os.order_id AND os.section_id = so.section_id
        INNER JOIN sections AS s ON so.section_id = s.id
        INNER JOIN users_section AS us ON s.id = us.section_id
        INNER JOIN users AS u ON us.user_id = u.id 
        WHERE so.exit_date IS NULL ";
        $result = pg_query($conn,$sql);
        if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $time = $row["deadline"];
                $chat_id_insert = $row["chat_id"];
                // echo "Its time: " .$time;
                $date = date("Y-m-d",strtotime(date($time)));           
                $yestrday = date("Y-m-d",(strtotime("-1 day")));
                $today = date("Y-m-d",(strtotime("now")));
                $nextDay = date("Y-m-d",(strtotime("+1 day")));
                if (strtotime($date) == strtotime($nextDay)) {
                    $title = $row["title"]." nomli buyurtma sizning bo`limingizda 1 kun kechga qolyaptdi!";
                    $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
                    $resultInsert = pg_query($conn,$sqlInsert);
                } else if (strtotime($date) == strtotime($today)) {
                    $title = $row["title"]." nomli buyurtma bugun sizning bo`limingizdan chiqib ketishi kerak";
                    $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
                    $resultInsert = pg_query($conn,$sqlInsert);
                } else if (strtotime($date) == strtotime($yestrday)) {
                    $title = $row["title"]." nomli buyurtma ertaga sizning bo`limingizdan chiqib ketishiga 1 kun qoldi!";
                    $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
                    $resultInsert = pg_query($conn,$sqlInsert);
                }  else if (strtotime($date) < strtotime($yestrday)) {
                    $title = $row["title"]." nomli buyurtma sizning bo`limingizdan ".$date." kuni  chiqib ketishi kerak edi!";
                    $sqlInsert = "INSERT INTO otdel_log_message (chat_id,text,status) VALUES (".$chat_id_insert.",'".$title."',0)";
                    $resultInsert = pg_query($conn,$sqlInsert);
                }
            }
        }
    }
}