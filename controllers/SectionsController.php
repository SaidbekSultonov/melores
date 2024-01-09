<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\Sections;
use app\models\SectionOrders;
use app\models\OrderResponsibles;
use app\models\SectionTimes;
use app\models\SectionMinimal;
use app\models\Minimal;
use app\models\BrigadaLeader;
use app\models\LeaderEmployees;
use app\models\UsersSection;
use app\models\BotUsers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SectionsController implements the CRUD actions for Sections model.
 */
class SectionsController extends Controller
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
     * Lists all Sections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sections::find()->where(['status' => 1])->orderBy(['order_column' => SORT_ASC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sections model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $section_minimal = SectionMinimal::find()
        ->where([
            'section_id' => $id
        ])
        ->all();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT bl.id, b.title_uz, b.user_id, u.second_name, b.status FROM brigada_leader AS bl
            LEFT JOIN section_minimal AS sm
            ON bl.user_id = sm.user_id
            LEFT JOIN brigada AS b
            ON bl.brigada_id = b.id
            LEFT JOIN users AS u
            ON bl.user_id = u.id AND sm.user_id = u.id
            WHERE sm.section_id = :secId AND u.type = :id", [':id' => 5, ':secId' => $id]);
        $result = $command->queryAll();

        $connection = Yii::$app->getDb();
        $commandEmployee = $connection->createCommand("SELECT u.id, u.second_name, u.status FROM leader_employees AS le
            LEFT JOIN users AS u ON le.employee_id = u.id
            LEFT JOIN section_minimal AS sm ON sm.user_id = le.leader_id
            WHERE sm.section_id = :secId", [':secId' => $id]);
        $resultEmployee = $commandEmployee->queryAll();

        if (!empty($result) && empty($resultEmployee)) {
            
            return $this->render('view', [
                'model' => $this->findModel($id),
                'section_minimal' => $section_minimal,
                'brigadas' => $result,
            ]);
        } else if (!empty($resultEmployee) && empty($result)) {
            return $this->render('view', [
                'model' => $this->findModel($id),
                'section_minimal' => $section_minimal,
                'employees' => $resultEmployee
            ]);
        } else {
            
            return $this->render('view', [
                'model' => $this->findModel($id),
                'section_minimal' => $section_minimal,
                'brigadas' => $result,
                'employees' => $resultEmployee
            ]);
        }

    }

    /**
     * Creates a new Sections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sections();
        $section_time = new SectionTimes();

        if ($model->load(Yii::$app->request->post()) && $section_time->load(Yii::$app->request->post())) {
            $model->created_date = date("Y-m-d");
            $model->status = 1;

            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Bo'lim qo'shildi", "Bo'limlar");

                //

                $section_time->section_id = $model->id;
                $section_time->start_date = date('Y-m-d');
                $section_time->end_date = date('9999-12-31');
                $section_time->status = 1;
                if ($section_time->save()) {
                    return $this->redirect(['/index.php/sections/view', 'id' => $model->id]);
                }
                else{
                    pre($section_time->errors);
                }
                
            }
            else{
                pre($model->errors);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'section_time' => $section_time,
        ]);
    }

    /**
     * Updates an existing Sections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $section_time = SectionTimes::find()
        ->where(['section_id' => $id])
        ->andWhere([
            '<=','start_date',date('Y-m-d')
        ])
        ->andWhere([
            '>=','end_date',date('Y-m-d')
        ])
        ->one();

        if ($model->load(Yii::$app->request->post()) && $section_time->load(Yii::$app->request->post())) {
            $model->created_date = date("Y-m-d");
            $model->status = 1;

            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Bo'lim o'zgartirildi", "Bo'lim");

                //

                $section_time->section_id = $model->id;
                $section_time->start_date = date('Y-m-d');
                $section_time->end_date = date('9999-12-31');
                $section_time->status = 1;
                if ($section_time->save()) {
                    return $this->redirect(['/index.php/sections/view', 'id' => $model->id]);
                }
                else{
                    pre($section_time->errors);
                }
                
            }
            else{
                pre($model->errors);
            }
            
        }

        return $this->render('update', [
            'model' => $model,
            'section_time' => $section_time,
        ]);
    }

    /**
     * Deletes an existing Sections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $section_orders = SectionOrders::find()
        // ->where(['section_id' => $id])
        // ->one();
        // if (empty($section_orders)) {
        //     $this->findModel($id)->delete();    
        // }
        // else{
        //     Yii::$app->session->setFlash('danger', "Bu bo'limda buyurtmalar mavjud.");
        // }

        

        return $this->redirect(['/index.php/sections/index']);
    }

    public function actionDeleteTime()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['id']) && isset($_GET['section_id'])) {
                $id = intval($_GET['id']);
                $section_id = intval($_GET['section_id']);

                $model = SectionTimes::find()
                ->where([
                    'section_id' => $section_id
                ])
                ->andWhere([
                    '<>','id',$id
                ])
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();

                if (isset($model) && !empty($model)) {
                    $status = false;
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $model->end_date = '9999-12-31';

                        if ($model->save()) {
                            $current_model = SectionTimes::findOne($id);
                            if (isset($current_model) && !empty($current_model)) {
                                $current_model->delete();
                                $status = true;
                            }
                        }

                        if ($status) {
                            $transaction->commit();
                            return ['status' => 'success'];    
                        }
                        
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                        return ['status' => 'failure'];
                    }
                }
                else{
                    return ['status' => 'failure_not_time'];
                }

            }
            else{
                return ['status' => 'failure'];
            }
        }
    }

    public function actionOpenModalTime()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['id']) && isset($_GET['section_id'])) {
                $id = intval($_GET['id']);

                $model = SectionTimes::findOne($id);


                return [
                    'header' => '<h3>O`zgartish</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('open-modal-time.php',[
                        'model' => $model
                    ]),
                ];

            }
            else{
                return ['status' => 'failure'];
            }
        }
    }

    public function actionSaveTime()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['id']) && isset($_GET['time']) && !empty($_GET['start_date']) && isset($_GET['section_id'])) {

                $id = intval($_GET['id']);
                $time = intval($_GET['time']);
                $section_id = intval($_GET['section_id']);
                $start_date = date('Y-m-d',strtotime(date($_GET['start_date'])));


                $model = SectionTimes::findOne($id);

                if (!isset($model) || empty($model)) {
                    return ['status' => 'failure_empty'];
                }

                if (isset($model) && $start_date == $model->start_date) 
                {
                    $model->work_time = $time;
                    if ($model->save()) {
                        return ['status' => 'success'];
                    }
                }
                else if($start_date > $model->start_date){

                    $yesterday = date('Y-m-d',strtotime($start_date . "-1 days"));
                    $model->end_date = $yesterday;
                    if ($model->save()) {
                        $new_time = new SectionTimes();
                        $new_time->section_id = $section_id;
                        $new_time->work_time = $time;
                        $new_time->start_date = $start_date;
                        $new_time->end_date = '9999-12-31';
                        $new_time->status = 1;
                        if ($new_time->save()) {
                            return ['status' => 'success'];
                        }
                    }

                    
                }

            }
            else{
                return ['status' => 'failure'];
            }
        }
    }

    public function actionOpenModal()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($_GET['id']) {
                $id = intval($_GET['id']);

                $section_users = SectionMinimal::find()
                ->where([
                    'section_id' => $id
                ])
                ->all();

                $user_arr = [];
                if (!empty($section_users)) {
                    foreach ($section_users as $key => $value) {
                        $user_arr[] = $value->user_id;
                    }    
                }
                
                $users = Users::find()
                ->where([
                    'not in', 'id', $user_arr
                ])->andWhere('type != :type', [':type' => 7])
                ->all();

                return [
                    'header' => '<h3>Javobgarlarni shaxs biriktirish</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('open-modal.php',[
                        'id' => $id,
                        'users' => $users,
                    ]),
                ];

            }
        }
    }

    public function actionOpenBrigadaModal()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($_GET['id']) {
                $id = intval($_GET['id']);

                $person = Users::find()
                    ->where('id = :id', [':id' => $id])
                    ->select(['id', 'second_name'])
                    ->one();

                $brigadaLeader = new BrigadaLeader();

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("SELECT b.id, b.title_uz FROM brigada AS b
                    WHERE b.id NOT IN(SELECT brigada_id FROM brigada_leader WHERE user_id = :id)
                    ORDER BY id ASC", [':id' => $id]);
                $result = $command->queryAll();
                $arr = [];
                if (isset($result) && !empty($result)) {
                    foreach ($result as $value) {
                        $arr[$value['id']] = $value['title_uz'];
                    }
                }

                return [
                    'header' => '<h3>Javobgar shaxsga brigada biriktirish</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('open-brigada-modal.php',[
                        'model' => $brigadaLeader,
                        'person' => $person,
                        'brigada' => $arr
                    ]),
                ];

            }
        }
    }

    public function actionOpenEmployeeModal()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($_GET['id']) {
                $id = intval($_GET['id']);
                $section_id = intval($_GET['section_id']);


                $person = Users::find()
                    ->where('id = :id', [':id' => $id])
                    ->select(['id', 'second_name'])
                    ->one();

                $employeeLeader = new LeaderEmployees();

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("SELECT u.id, u.second_name FROM users AS u
                    WHERE u.type != 5 AND u.id NOT IN (SELECT employee_id FROM leader_employees WHERE leader_id = :id)
                    ORDER BY id ASC", [':id' => $id]);
                $result = $command->queryAll();
                $arr = [];
                if (isset($result) && !empty($result)) {
                    foreach ($result as $value) {
                        $arr[$value['id']] = $value['second_name'];
                    }
                }

                return [
                    'header' => '<h3>Javobgar shaxsga ishchi biriktirish</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('open-employee-modal.php',[
                        'model' => $employeeLeader,
                        'person' => $person,
                        'employees' => $arr,
                        'section_id' => $section_id,
                    ]),
                ];

            }
        }
    }

    public function actionSaveUsers()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            

            if ($_GET['id']) {
                $id = intval($_GET['id']);
                $users = $_GET['users'];
                $bonus = intval($_GET['bonus']);
                $penalty = intval($_GET['penalty']);

                if (empty($users))
                    return ['status' => 'failure_users'];
                if (empty($bonus)) 
                    return ['status' => 'failure_bonus'];
                if (empty($penalty))
                    return ['status' => 'failure_penalty'];

                $status = false;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $minimal = new Minimal();
                    $minimal->bonus_sum = $bonus;
                    $minimal->penalty_summ = $penalty;
                    if ($minimal->save()) {
                        $model = new SectionMinimal();
                        $model->minimal_id = $minimal->id;
                        $model->section_id = $id;
                        $model->user_id = $users;
                        if ($model->save()) {
                            $transaction->commit();
                            return ['status' => 'success'];
                        }
                        else{
                            echo "<pre>";
                            print_r($model->errors);
                            die();
                        }
                    } else{
                        echo "<pre>";
                        print_r($minimal->errors);
                        die('222222');
                    }
                } catch (Exception $e) {
                    return ['status' => 'failure'];
                }
            }
        }
    }

    public function actionSaveBrigadaUser()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (Yii::$app->request->post()) {
                $post = Yii::$app->request->post();
                
                $user_id = $post['BrigadaLeader']['user_id'];
                foreach ($post['BrigadaLeader']['brigada_id'] as $key => $value) {
                    $model = new BrigadaLeader();
                    $model->brigada_id = $value;
                    $model->user_id = $user_id;
                    $model->save();
                }
                return ['status' => 'success'];
            }
        }
    }

    public function actionSaveEmployeeUser()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (Yii::$app->request->post()) {
                $post = Yii::$app->request->post();
                
                $section_id = $post['section_id'];

                $user_id = $post['LeaderEmployees']['leader_id'];
                foreach ($post['LeaderEmployees']['employee_id'] as $key => $value) {
                    $model = new LeaderEmployees();
                    $model->employee_id = $value;
                    $model->leader_id = $user_id;
                    if($model->save()){
                        $find_user = UsersSection::find()
                        ->where(['section_id' => $section_id, 'user_id' => $model->employee_id])
                        ->one();

                        if (!$find_user) {
                            $user = Users::findOne($model->employee_id);
                            if (isset($user)) {
                                $new_section_user = new UsersSection();
                                $new_section_user->user_id = $model->employee_id;
                                $new_section_user->section_id = $section_id;
                                
                                $bot_user = BotUsers::find()->where(['user_id' => $model->employee_id])->one();
                                if (!isset($bot_user)) {
                                    $bot_user = new BotUsers();
                                    $bot_user->user_id = $model->employee_id;
                                    $bot_user->bot_id = 1;
                                    $bot_user->save();
                                }
                                
                                $new_section_user->bot_users_id = $bot_user->id;
                                $new_section_user->role = $user->type;
                                $new_section_user->save();
                                
                            }
                        }
                    }
                }
                return ['status' => 'success'];
            }
        }
    }

    public function actionDeleteUser()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($_GET['id'] && $_GET['section_id']) {
                $id = intval($_GET['id']);
                $section_id = intval($_GET['section_id']);

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("DELETE FROM users_section WHERE user_id = (SELECT user_id FROM section_minimal WHERE id = :id)", [':id' => $id]);
                $result = $command->queryAll();

                $command = $connection->createCommand("DELETE FROM order_responsibles WHERE section_id = :id", [':id' => $section_id]);
                $result = $command->queryAll();

                $section_users = SectionMinimal::findOne($id);
                if (isset($section_users) && $section_users->delete()) {

                    return ['status' => 'success'];
                }
                else{
                    return ['status' => 'failure'];
                }

            }
        }
    }

    public function actionDeleteUserBrigada()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($_GET['id']) {
                $id = intval($_GET['id']);

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("DELETE FROM brigada_leader WHERE id = :id", [':id' => $id]);
                $result = $command->queryAll();
                if ($result) {
                    return ['status' => 'success'];
                }
                else{
                    return ['status' => 'failure'];
                }
            }
        }
    }

    public function actionDeleteUserEmployee()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($_GET['id'] && $_GET['section_id']) {
                $id = intval($_GET['id']);
                $section_id =  intval($_GET['section_id']);

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("DELETE FROM leader_employees WHERE employee_id = :id", [':id' => $id]);
                $result = $command->queryAll();
                if ($result) {
                    $sql = "DELETE FROM order_responsibles WHERE user_id = $id AND section_id = $section_id";
                    $insertCommand = $connection->createCommand($sql);
                    $insertCommand->queryAll();

                    return ['status' => 'success'];
                }
                else{
                    return ['status' => 'failure'];
                }
            }
        }
    }


    // public function actionRes()
    // {
    //     $model = SectionOrders::find()
    //     ->where(['exit_date' => NULL])
    //     ->all();

    //     $arr = [];

    //     foreach ($model as $key => $value) {
    //         if ($value->section_id == 35) {
    //             $arr[$value->order_id] = [
    //                 'section_id' => $value->section_id,
    //                 'order_id' => $value->order_id,
    //             ];    
    //         }
            
    //     }

        

    //     $workers = [47, 118, 204, 261];

        
    //     // 47 118 204 261
    //     // Habibulloh 102 = 258
    //     // Habibulloh 881 = 114
    //     // Zohid = 102
    //     // Ilg'or = 49
    //     // Ilg'oraka kraska = 76
    //     // Jamshid = 234
    //     // Shuxrat shaxsiy = 243
    //     // Elyor 374 = 62
    //     // MOxir = 59
         

    //     foreach ($arr as $key => $value) {
    //         $section_id = $value['section_id'];
    //         $order_id = $value['order_id'];

    //         $model = new OrderResponsibles();
    //         $model->order_id = $order_id;
    //         $model->user_id = 114;
    //         $model->section_id = $section_id;
    //         if(!$model->save()){
    //             pre($model->errors);    
    //         }
    //         // else{
    //         //     foreach ($workers as $keyw => $valuew) {
    //         //         $modelw = new OrderResponsibles();
    //         //         $modelw->order_id = $order_id;
    //         //         $modelw->user_id = $valuew;
    //         //         $modelw->section_id = $section_id;
    //         //         if (!$modelw->save()) {
    //         //             pre($modelw->errors);
    //         //         }
    //         //     }
    //         // }
    //     }

        
    // }

    /**
     * Finds the Sections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sections::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
