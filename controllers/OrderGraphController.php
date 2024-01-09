<?php

namespace app\controllers;

use Yii;
use app\models\OrderGraph;
use app\models\Orders;
use app\models\Branch;
use app\models\Clients;
use app\models\Sections;
use app\models\SectionMinimal;
use app\models\SectionOrders;
use app\models\BrigadaLeader;
use app\models\OrderResponsibles;
use app\models\OrderStep;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderGraphController implements the CRUD actions for OrderGraph model.
 */
class OrderGraphController extends Controller
{
    public $layout = "graph";
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrderGraph models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OrderGraph::find(),
        ]);
        $date = date('Y-m-d');
        $sections = Sections::find()->where(['status' => 1])->andWhere(['!=', 'id', 1])->orderBy('order_column')->all();

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['section_id'])) {
                $section_id = $_GET['section_id'];
            }

            $sectionOne = Sections::findOne($section_id);

            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("SELECT o.id, o.title, o.created_date, o.dead_line FROM orders AS o 
                    INNER JOIN section_orders AS so ON so.order_id = o.id
                    WHERE so.section_id = :id AND so.exit_date IS NULL AND DATE(o.dead_line) > '$date'", [':id' => $section_id
                ]);
            $defaultOrders = $command->queryAll();

            return [
                'status' => 'success',
                'content' => $this->renderAjax('view.php', [
                    'sections' => $sectionOne,
                    'defaultOrders' => $defaultOrders,
                ]),
            ];
        } else {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("SELECT o.id, o.title, o.created_date, o.dead_line FROM orders AS o 
                    INNER JOIN section_orders AS so ON so.order_id = o.id
                    WHERE so.section_id = :id AND so.exit_date IS NULL AND DATE(o.dead_line) > '$date'", [':id' => 25]);
            $defaultOrders = $command->queryAll();
            
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'sections' => $sections,
                'defaultOrders' => $defaultOrders
            ]);
        }

        // pre($defaultOrders);
    }

    /**
     * Displays a single OrderGraph model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing OrderGraph model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['order_id']) && isset($_GET['section_id'])) {
                $order_id = $_GET['order_id'];
                $section_id = $_GET['section_id'];
            }
            $model = Orders::findOne($order_id);
            $branch = Branch::findOne($model->branch_id);
            $client = Clients::findOne($model->client_id);

            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("SELECT u.id, u.second_name FROM users AS u
                INNER JOIN section_minimal AS sm ON sm.user_id = u.id
                INNER JOIN order_step AS os ON os.section_id = sm.section_id
                WHERE sm.section_id = :section_id AND os.order_id = :order_id", [':section_id' => $section_id, ':order_id' => $order_id]);
            $result = $command->queryAll();
            $person_id = $result[0]['id'];
            $person_name = $result[0]['second_name'];

            $command = $connection->createCommand("SELECT b.user_id, b.title_uz FROM brigada AS b
                LEFT JOIN brigada_leader AS bl ON bl.brigada_id = b.id
                WHERE bl.user_id = :person_id", [':person_id' => $person_id]);
            $brigadas = $command->queryAll();
            if (!empty($brigadas)) {
                $arr = [];
                if (isset($brigadas)) {
                    foreach ($brigadas as $value) {
                        $arr[$value['user_id']] = $value['title_uz'];
                    }
                }

                $command = $connection->createCommand("SELECT b.user_id, b.title_uz FROM brigada AS b
                    LEFT JOIN order_responsibles AS ors ON ors.user_id = b.user_id
                    WHERE ors.order_id = :order_id AND section_id = :section_id", [':order_id' => $order_id, ':section_id' => $section_id]);
                $chosenBrigada = $command->queryAll();
                $arrOneBri = [];
                if (isset($chosenBrigada) && !empty($chosenBrigada)) {
                    foreach ($chosenBrigada as $value) {
                        $arrOneBri[$value['user_id']] = $value['title_uz'];
                    }
                }

                return [
                    'header' => '<h3>Buyurtma ma`lumotlari</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('update.php', [
                        'model' => $model,
                        'branch' => $branch,
                        'client' => $client,
                        'responsible' => $person_name,
                        'brigadas' => $arr,
                        'chosenone' => $arrOneBri
                    ]),
                ];
            } else {
                return [
                    'header' => '<h3>Buyurtma ma`lumotlari</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('update.php', [
                        'model' => $model,
                        'branch' => $branch,
                        'client' => $client,
                        'responsible' => $person_name,
                    ]),
                ];
            }

            pre($brigadas);

        } 
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if (Yii::$app->request->post()) {
                $post = Yii::$app->request->post();
                $orderId = $post['Orders']['id'];
                $brigadirId = $post['Orders']['feedback_user'];
                $newDate = date("Y-m-d H:i", strtotime($post['Orders']['dead_line']));

                if (isset($brigadirId)) {
                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand("DELETE FROM order_responsibles WHERE order_id = :order_id AND id > (SELECT id FROM order_responsibles WHERE order_id = :order_id LIMIT 1)", [':order_id' => $orderId]);
                    $result = $command->queryAll();

                    $command = $connection->createCommand("SELECT section_id FROM order_responsibles WHERE order_id = :order_id LIMIT 1", [':order_id' => $orderId]);
                    $section = $command->queryAll();
                    $sectionId = $section[0]['section_id'];

                    $command = $connection->createCommand("INSERT INTO order_responsibles (order_id, user_id, section_id) VALUES ($orderId, $brigadirId, $sectionId)");
                    $command->execute();

                    $command = $connection->createCommand("SELECT bu.user_id FROM brigada_users AS bu
                        INNER JOIN brigada AS b ON b.id = bu.brigada_id
                        WHERE b.user_id = :brigadir_id", [':brigadir_id' => $brigadirId]);
                    $employee = $command->queryAll();
                    foreach ($employee as $key => $value) {
                        $employeeId = $value['user_id'];

                        $command = $connection->createCommand("INSERT INTO order_responsibles (order_id, user_id, section_id) VALUES ($orderId, $employeeId, $sectionId)");
                        $command->execute();                        
                    }
                }


                $model = Orders::findOne($orderId);
                $model->dead_line = $newDate;
                if ($model->save()) {
                    return ['status' => 'success'];
                }
            }
        }
    }

    public function actionDetail($month)
    {
        $this->layout = 'layout_detail';

        $current = date('Y-'.$month.'-01');
        // $limit = intval($limit);
       

        $model = Orders::find()
        ->where(['status' => 1, 'pause' => 0])
        ->andWhere(['not', ['start_time' => null]])
        ->andWhere(['>=','dead_line',$current])
        ->orderBy(['id' => SORT_DESC])
        ->limit(10)
        ->all();



        $sections = Sections::find()
        ->where(['status' => 1])
        ->andWhere(['!=', 'id', 1])
        ->orderBy(['order_column' => SORT_ASC])
        ->all();


        $days = cal_days_in_month(CAL_GREGORIAN,date($month),date('Y'));
        $start_day = date('01');

        $arr_days = [];
        for ($i = $start_day; $i <= $days; $i++) { 
            if (strlen($i) == 1) {
                $i = '0'.$i;
            }
            $arr_days[] = $i;
        }

        $months = [
            '01' => 'Yanvar',
            '02' => 'Fevral',
            '03' => 'Mart',
            '04' => 'Aprel',
            '05' => 'May',
            '06' => 'Iyun',
            '07' => 'Iyul',
            '08' => 'Avgust',
            '09' => 'Sentabr',
            '10' => 'Oktabr',
            '11' => 'Noyabr',
            '12' => 'Dekabr',
        ];  

       
        // $sql = "SELECT 
        // o.id AS order__id, 
        // o.title AS order_title, 
        // os.deadline AS step_deadline,
        // os.section_id AS os_section_id, 
        // sc.id AS s_section_id,
        // sc.color,
        // o.start_time AS order_start,
        // os.work_hour,
        // os.order_column_2 AS os_column
        // FROM orders AS o 
        // INNER JOIN order_step AS os ON o.id = os.order_id
        // INNER JOIN sections AS sc ON sc.id = os.section_id
        // WHERE o.status = 1 AND o.pause = 0 
        // AND ( os.deadline >= '$current')
        // order by o.id DESC, sc.id DESC";

        // $query = Yii::$app->db->createCommand($sql)->queryAll();

        // $arr = [];


        // // pre($sql);
        // if (!empty($query)) {
        //     $count_arr = [];
        //     foreach ($query as $key => $value) {
        //         foreach ($arr_days as $values){

        //             $start = date("Y-m-d H:i:s", strtotime('-'.$value['work_hour'].' hours', strtotime($value['step_deadline'])));
                    
        //             $arr[$value['order_title']][$values][$value['step_deadline']] = [
        //                 'order__id' => $value['order__id'],
        //                 'order_title' => $value['order_title'],
        //                 's_section_id' => $value['s_section_id'],
        //                 'color' => $value['color'],
        //                 'step_deadline' => $value['step_deadline'],
        //                 'start_date' => $start,
        //             ];
                   
        //         }
                
        //     }
        // }

        
        return $this->render('detail',[
            'arr_days' => $arr_days,
            'model' => $model,
            'sections' => $sections,
            'months' => $months,
            'month' => $month,
            // 'arr' => $arr,
        ]);
    }


    public function actionOpenModal()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['order_id']) && isset($_GET['section_id'])) {
                $order_id = intval($_GET['order_id']);
                $section_id = intval($_GET['section_id']);

                $order_step = OrderStep::find()
                ->where(['order_id' => $order_id,'section_id' => $section_id])
                ->one();

                $section_order = SectionOrders::find()
                ->where(['order_id' => $order_id,'section_id' => $section_id])
                ->one();


                if (isset($order_step)) {
                    $all_steps = OrderStep::find()
                    ->where(['order_id' => $order_id])
                    ->all();

                    $order_responsibles = OrderResponsibles::find()
                    ->where(['order_id' => $order_id])
                    ->andWhere(['!=', 'user_id', $order_step->section_minimal->user_id])
                    ->one();


                    return [
                        'header' => $order_step->section->title,
                        'status' => 'success',
                        'content' => $this->renderAjax('modal.php', [
                            'order_step' => $order_step,
                            'all_steps' => $all_steps,
                            'order_id' => $order_id,
                            'section_id' => $section_id,
                            'section_order' => $section_order,
                            'order_responsibles' => $order_responsibles,
                        ]),
                    ];


                }
                else{
                    return ['status' => 'failure'];
                }
            }
        }
    }


    public function actionSaveUpdate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['order_id']) && isset($_GET['section_id'])) {
                $order_id = intval($_GET['order_id']);
                $section_id = intval($_GET['section_id']);


                if (!empty($_GET['date'])) {
                    $date = date('Y-m-d H:i:s',strtotime($_GET['date']));

                    $order_step = OrderStep::find()
                    ->where(['order_id' => $order_id,'section_id' => $section_id])
                    ->one();


                    $t1 = strtotime($date);
                    $t2 = strtotime($order_step->deadline);
                    $diff = $t1 - $t2;
                    $hours = round($diff / ( 60 * 60 ));

                    $other_steps = OrderStep::find()
                    ->where(['order_id' => $order_id])
                    ->andWhere(['!=','deadline',$order_step->deadline])
                    ->andWhere(['>','order_column',$order_step->order_column])
                    ->orderBy(['order_column' => SORT_ASC])
                    ->all();

                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        $order_step->deadline = $date;
                        if ($order_step->save() && !empty($other_steps)) {
                            foreach ($other_steps as $key => $value) {
                                if ($hours > 0)
                                    $new_date = date("Y-m-d H:i:s", strtotime('+'.$hours.' hours', strtotime($value->deadline)));
                                else
                                    $new_date = date("Y-m-d H:i:s", strtotime($hours.' hours', strtotime($value->deadline)));
                                
                                $value->deadline = $new_date;
                                $value->save();
                            }
                        }
                        $transaction->commit();

                        return ['status' => 'success'];

                    }
                    catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                        return ['status' => 'failure'];
                    } 
                }
                else{
                    return ['status' => 'failure'];
                }

                

                


                
            }
        }
    }

    /**
     * Finds the OrderGraph model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderGraph the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderGraph::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
