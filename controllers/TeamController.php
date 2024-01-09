<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\Team;
use app\models\TeamSchedule;
use app\models\Orders;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends Controller
{
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        
    }
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
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Team::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Team model.
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
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Team();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            $model->title = base64_encode($model->title);
            if ($model->save()) {

                print_r(Yii::$app->request->post());
                exit;
                return $this->redirect(['/index.php/team/view', 'id' => $model->id]);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->title = base64_encode($model->title);
            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), base64_decode($model->title), "Ustanovshik o'zgartirildi", 'Ustanovshik');

                //
                return $this->redirect(['/index.php/team/view', 'id' => $model->id]);    
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Team model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Orders::find()
        ->where([
            'team_id' => $id,
            'status' => 1
        ])
        ->one();
        if (empty($model)) {
            //                          START ADD EVENT
            $user_id = Yii::$app->user->id;

            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
            $userId = $selectUsers->id;

            eventUser($userId, date('Y-m-d H:i:s'), base64_decode($this->findModel($id)->title), "Ustanovshik o'chirildi", 'Ustanovshik');

            //
            $this->findModel($id)->delete();
            return $this->redirect(['/index.php/team/index']);
        }
        else{
            Yii::$app->session->setFlash('danger', "Bu brigadada faol buyurtma mavjud!.");
            
            return $this->redirect(['/index.php/team/index']);
        }

        
    }

    public function actionSchedule()
    {
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Team::find(),
        // ]);

        $firstDate = date('Y-m-01');

        $lastDate = date("Y-m-t", strtotime($firstDate));
        $sql = "SELECT 
            t.id, 
            t.title as t_title, 
            o.title as title, 
            ts.order_id as order_title, 
            ts.team_id as team_id, 
            ts.id,
            ts.date 
        FROM team AS t
        LEFT JOIN team_schedule AS ts ON t.id = ts.team_id 
        LEFT JOIN orders AS o ON ts.order_id = o.id
        WHERE ts.date::DATE >= '".$firstDate."' AND ts.date::DATE <= '".$lastDate."'  
        ORDER BY ts.date::DATE, ts.team_id ASC";

        $command = Yii::$app->db->createCommand($sql)->queryAll();

        $arr = [];
        $aaaa = [];
        if (isset($command) && !empty($command)) {
            $con = 0;
            for ($d = strtotime($firstDate); $d <= strtotime($lastDate); $d += 86400) {
                if (isset($d) && isset($command[$con]['date']) && $d == strtotime(date("Y-m-d", strtotime($command[$con]['date'])))) {
                    $selectTeam = Team::find()->all();
                    $aaaa[date("Y-m-d", strtotime($command[$con]['date']))][$command[$con]['team_id']][] = $command[$con]['title'];
                    $con++;
                    if (isset($d) && isset($command[$con]['date']) && $d == strtotime(date("Y-m-d", strtotime($command[$con]['date'])))) {
                        $aaaa[date("Y-m-d", strtotime($command[$con]['date']))][$command[$con]['team_id']][] = $command[$con]['title'];
                        $con++;
                        if (isset($d) && isset($command[$con]['date']) && $d == strtotime(date("Y-m-d", strtotime($command[$con]['date'])))) {
                            $d = $d - 86400;
                        }
                    }
                } 
                else {
                    $aaaa[date("Y-m-d", $d)] = [];
                }
            }
        } else {
            while (strtotime($firstDate) <= strtotime($lastDate)) {
                $aaaa[$firstDate] = [];  
                $firstDate = date ("Y-m-d", strtotime("+1 day", strtotime($firstDate)));
            }
        }

        return $this->render('schedule', [
            'command' => $aaaa,
        ]);
    }

    public function actionSchedule_form()
    {
        $model = new TeamSchedule();
        if ($model->load(Yii::$app->request->post())) {
            $model->date = date('Y-m-d H:i:s', strtotime(date($model->date)));
            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                $selectOrder = Orders::find()->where(['id' => $model->order_id])->one();
                $order_name = $selectOrder->title;

                $selectTeam = Team::find()->where(['id' => $model->team_id])->one();
                $team_name = $selectTeam->title;

                eventUser($userId, date('Y-m-d H:i:s'), "Order - ".$order_name." | Brigada - ".base64_encode($team_name), "Grafikga qo'shildi", 'Grafik');

                //
                return $this->redirect(['/index.php/team/schedule']);
            }
            
        }

        return $this->render('schedule_form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionMonth() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (!empty($_GET['date']) && isset($_GET['date'])) {
                $firstDate = $_GET["date"];
                $lastDate = date("Y-m-t", strtotime($firstDate));
                $sql = "SELECT 
                    t.id, 
                    t.title as t_title, 
                    o.title as title, 
                    ts.order_id as order_title, 
                    ts.team_id as team_id, 
                    ts.id,
                    ts.date 
                FROM team AS t
                LEFT JOIN team_schedule AS ts ON t.id = ts.team_id 
                LEFT JOIN orders AS o ON ts.order_id = o.id
                WHERE ts.date::DATE >= '".$firstDate."' AND ts.date::DATE <= '".$lastDate."'  
                ORDER BY ts.date::DATE, ts.team_id ASC";

                $command = Yii::$app->db->createCommand($sql)->queryAll();

                $arr = [];
                $aaaa = [];
                if (isset($command) && !empty($command)) {
                    $con = 0;
                    for ($d = strtotime($firstDate); $d <= strtotime($lastDate); $d += 86400) {
                        if (isset($d) && isset($command[$con]['date']) && $d == strtotime(date("Y-m-d", strtotime($command[$con]['date'])))) {
                            $selectTeam = Team::find()->all();
                            $aaaa[date("Y-m-d", strtotime($command[$con]['date']))][$command[$con]['team_id']][] = $command[$con]['title'];
                            $con++;
                            if (isset($d) && isset($command[$con]['date']) && $d == strtotime(date("Y-m-d", strtotime($command[$con]['date'])))) {
                                $aaaa[date("Y-m-d", strtotime($command[$con]['date']))][$command[$con]['team_id']][] = $command[$con]['title'];
                                $con++;
                                if (isset($d) && isset($command[$con]['date']) && $d == strtotime(date("Y-m-d", strtotime($command[$con]['date'])))) {
                                    $d = $d - 86400;
                                }
                            }
                        } 
                        else {
                            $aaaa[date("Y-m-d", $d)] = [];
                        }
                    }
                } else {
                    while (strtotime($firstDate) <= strtotime($lastDate)) {
                        $aaaa[$firstDate] = [];  
                        $firstDate = date ("Y-m-d", strtotime("+1 day", strtotime($firstDate)));
                    }
                }
                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('month.php', [
                        'team' => $aaaa
                    ]),
                ];
            } else {
                return [
                    'status' => 'fail',
                    'content' => 'Data notogri qabul qilindi'
                ];
            } 

        } else {
            return [
                    'status' => 'fail',
                    'content' => 'Ajaxdan ma\'lumot kelishida xatolik yuz berdi'
                ];
        }
    }
}
