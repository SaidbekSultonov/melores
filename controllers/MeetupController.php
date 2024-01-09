<?php

namespace app\controllers;

use Yii;
use app\models\Sections;
use app\models\Quiz;
use app\models\Answer;
use app\models\Users;
use app\models\Penalties;
use app\models\PenaltyUsers;
use app\models\QuizStep;
use app\models\SendQuiz;
use app\models\QuizCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class MeetupController extends Controller
{
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

    public function actionIndex()
    {
        $sql_cat = 'SELECT * FROM quiz_category';
        $sql_quiz_count = 'SELECT q.category_id,COUNT(q.id) AS q_co FROM quiz AS q
        GROUP BY q.category_id';
        $sql_users_count = 'SELECT q.category_id,sq.user_id
            FROM quiz AS q
            RIGHT JOIN send_quiz AS sq
                ON sq.quiz_id = q.id
        GROUP BY q.category_id,sq.user_id';

        $sql_daily = 'SELECT 
                u.second_name AS second_name,
                u."type" AS type,
                da.date AS date,
                da.file_name AS file_id,
                da.type AS da_type
            FROM daily_answer AS da
            INNER JOIN users AS u
                ON u.id = da.user_id
            WHERE da."status" = 1 AND 
            date = CURRENT_DATE 
        ORDER BY da.date DESC';

        $sql_users = 'SELECT * FROM users WHERE type != 1';

        $sql_penalty = 'SELECT 
            u.second_name AS second_name,
            u."type" AS type,
            pu.sum AS penalty_sum,
            pu.id AS pu_id
            FROM penalty_users AS pu
            INNER JOIN users AS u
                ON pu.user_id = u.id
                GROUP BY u.second_name,u."type",pu.sum,pu.id
        ORDER BY pu.sum DESC';

        $category_quiz = Yii::$app->db->createCommand($sql_cat)
        ->queryAll();

        $quiz_count = Yii::$app->db->createCommand($sql_quiz_count)
        ->queryAll();


        $users_count = Yii::$app->db->createCommand($sql_users_count)
        ->queryAll();


        $daily_answer = Yii::$app->db->createCommand($sql_daily)
        ->queryAll();

        

        $users = Yii::$app->db->createCommand($sql_users)
        ->queryAll();


        $penalty = Yii::$app->db->createCommand($sql_penalty)
        ->queryAll();


        $send_quiz = SendQuiz::find()->all();


        return $this->render('index', [
            'category_quiz' => $category_quiz,
            'users_count' => $users_count,
            'quiz_count' => $quiz_count,
            'send_quiz' => $send_quiz,
            'daily_answer' => $daily_answer,
            'users' => $users,
            'penalty' => $penalty,
        ]);
    }

    public function actionView()
    {
        if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != 0) {

            $quiz_sql = 'SELECT 
                q.id,
                q.question,
                q."type",
                q.category_id,
                p.penalty AS sum
                FROM quiz AS q
                LEFT JOIN penalties AS p
                    ON p.quiz_id = q.id
            WHERE q.category_id = '.$_GET['id'].'
            GROUP BY q.id,p.penalty';
            $users_sql = 'SELECT 
                    u.second_name,
                    u.type,
                    sq.quiz_id,
                    u.phone_number
                FROM send_quiz AS sq
                    LEFT JOIN users AS u
            ON	u.id = sq.user_id';

            $quiz = Yii::$app->db->createCommand($quiz_sql)
            ->queryAll();
            $users = Yii::$app->db->createCommand($users_sql)
            ->queryAll();

            $title = QuizCategory::find()->where(['=','id',$_GET['id']])->one();
            $select_users = Users::find()->where(['!=','type',1])->all();
    
            $sections = Sections::find()->all();

            return $this->render('view', [
                'title' => $title,
                'quiz' => $quiz,
                'users' => $users,
                'select_users' => $select_users,
                'sections' => $sections,
            ]);
        }else {
            return $this->redirect('index');            
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_POST['Quiz']) && !empty($_POST['Quiz'])) {
                $data_quiz = $_POST['Quiz'];
                $error = true;

                if (!isset($data_quiz['penalty']) || empty($data_quiz['penalty']) || $data_quiz['penalty'] <= 0) {
                    $error = false;
                    Yii::$app->session->setFlash('error', 'Jarima noldan kotta bolish kerkak');
                }

                if (!isset($data_quiz['question']) || empty($data_quiz['question'])) {
                    $error = false;
                    Yii::$app->session->setFlash('error', 'Savol bolish kerkak');
                }

                if (!isset($_POST['cat']) || $_POST['cat'] <= 0) {
                    $error = false;
                    Yii::$app->session->setFlash('error', 'Savol kategoriya faol emas');
                }

                if (isset($data_quiz['users']) && !empty($data_quiz['users'])) {
                    foreach ($data_quiz['users'] as $key => $value_user_valid) {
                        $user_valid = Users::find()->where(['=','id',$value_user_valid])->andWhere(['!=','type',1])->one();
                        if (!isset($user_valid) || empty($user_valid)) {
                            $error = false;
                            Yii::$app->session->setFlash('error', 'Tanlangan foydalanuvchi mavjud emas');
                        }
                    }
                }

                if ($error === false) {
                    return [
                        'status' => 'error'
                    ];
                }else {
                    $transaction = Yii::$app->db->beginTransaction();
                    $error_try = 0;
                    try {

                        $question_save = new Quiz();
                        $question_save->category_id = $_POST['cat'];
                        $question_save->question = $data_quiz['question'];
                        $type = 0;
                        if (isset($data_quiz['qestion_type']) && $data_quiz['qestion_type'] == 'on') {
                            $type = 1;
                        }
                        $question_save->type = $type;
                        if ($question_save->save()) {
                        }else {
                            $error_try++;
                        }
                        if (isset($data_quiz['users']) && !empty($data_quiz['users'])) {
                            foreach ($data_quiz['users'] as $user_save) {
                                $send_quiz = new SendQuiz();

                                $send_quiz->user_id = $user_save;
                                $send_quiz->quiz_id = $question_save->id;
                                if ($send_quiz->save()) {
                                }else {
                                    $error_try++;
                                }
                                
                                $question_id = $question_save->id;
                                $penalty_users_last_find = Penalties::find()->where(['=','user_id',$user_save])->andWhere(['=','quiz_id',$question_id])->one();
                                if (isset($penalty_users_last_find) && !empty($penalty_users_last_find)) {
                                    if ($penalty_users_last_find->penalty != $data_quiz['penalty']) {
                                        $penalty_users_last_find->penalty = $data_quiz['penalty'];
                                        if ($penalty_users_last_find->save()) {
                                        }else {
                                            $error_try++;
                                        }
                                    }                                    
                                }else {
                                    $penalty = new Penalties();
                                    $penalty->user_id = $user_save;
                                    $penalty->quiz_id = $question_id;
                                    $penalty->penalty = $data_quiz['penalty'];
                                    
                                    if ($penalty->save()) {
                                    }else {
                                        $error_try++;
                                    }
                                    
                                }
                                    
                            }
                        }
                        $transaction->commit();
                        return [
                            'status' => "success",
                        ];

                    } catch (Exception $e) {
                    
                        $transaction->rollBack();
                        throw $e;  

                        return [
                            'status' => "error",
                            'message' => "Try dan o'tomadi",
                        ];

                    }
                }
            }
        }
    }

    public function actionUpdate($id)
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $quiz_id = $_GET['id'];
            $question = Quiz::find()->where(['=','id',$quiz_id])->one();
            if (isset($question) && !empty($question)) {

                $sql_users = 'SELECT
                    us.id AS user_id,
                    us.second_name,
                    pe.sum,
                    sq."status",
                    sq.id
                FROM send_quiz AS sq
                INNER JOIN penalty_users AS pu 
                    ON pu.user_id = sq.user_id
                INNER JOIN penalties AS pe
                    ON pe.id = pu.penalty_id
                INNER JOIN users AS us
                    ON sq.user_id = us.id
                WHERE sq.quiz_id = :id AND pe.start_date < :date AND pe.end_date > :date';

                $send_quiz_users = Yii::$app->db->createCommand($sql_users)
                    ->bindValue(':date', date('Y-m-d H:i:s'))
                    ->bindValue(':id', $quiz_id)
                    ->queryAll();

                return $this->render('update',[
                    'question' => $question,
                    'users' => $send_quiz_users,
                ]);
            }else {
                return $this->redirect(['/index.php/meetup/index']);
            }
        }else {
            return $this->redirect(['/index.php/meetup/index']);
        }

    }

    public function actionAddUpdate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_POST['Quiz'])) {
                $data = $_POST['Quiz'];

                if ($data['type'] == 7) {
                    $sql_users = 'SELECT 
                        us.id,
                        us.second_name,
                        p.sum
                    FROM users AS us
                    LEFT JOIN penalty_users AS pu
                    ON pu.user_id = us.id
                    LEFT JOIN penalties AS p
                    ON pu.penalty_id = p.id
                    where us.type != 1 and us.type != 4';
                }else {
                    $sql_users = 'SELECT 
                        us.id,
                        us.second_name,
                        p.sum
                    FROM penalty_users AS pu
                    INNER JOIN users AS us 
                    ON pu.user_id = us.id
                    INNER JOIN penalties AS p
                    ON pu.penalty_id = p.id
                    where us.type = 4';
                }
                $send_quiz_users = Yii::$app->db->createCommand($sql_users)
                ->queryAll();

                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('update_form.php', [
                        'count' => $data['count'],
                        'users' => $send_quiz_users
                    ]),
                ];
            }
        }
    }

    public function actionSaveUpdates()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_POST['Quiz'])) {
                $data = $_POST['Quiz'];
                if (isset($data['list']) && !empty($data['list'])) {
                    $error = [];
                    $users = [];
                    $id = '';
                    foreach ($data['list'] as $validation) {
                        $id = $validation['users'];
                        if (!isset($users[$id]) && empty($users[$id])) {
                            $users[$id] = true;
                        }else {
                            $error['user'] = true;
                        }
                        if ($validation['penalty'] <= 0) {
                            $error['penalty'] = true;
                        }
                    }
                }
                if (isset($error) && !empty($error)) {
                    return [
                        'status' => "error",
                        'errors' => $error,
                    ]; 
                }else {     
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $quiz = Quiz::find()->where(['=','id',$data['id']])->one();
                        if (isset($quiz) && !empty($quiz)) {
                            $quiz->question = $data['question'];
                            $quiz->save();

                            if (isset($data['list']) && !empty($data['list'])) {
                                foreach ($data['list'] as $list) {
                                    if (isset($list['add']) && !empty($list['add'])) {
                                        $send_quiz = new SendQuiz();
                                        $send_quiz->user_id = $list['users'];
                                        $send_quiz->quiz_id = $data['id'];
                                        $send_quiz->status = 1;
                                        $send_quiz->save();
                                    }

                                    $penalty_users_last_find = PenaltyUsers::find()->where(['=','user_id',$list['users']])->one();
                                    if (isset($penalty_users_last_find) && !empty($penalty_users_last_find)) {
                                        $penalty_last_find = Penalties::find()->where(['=','id',$penalty_users_last_find->penalty_id])->andWhere('start_date <= :date AND end_date >= :date', [':date' => date('Y-m-d H:i:s')])->one();
                                        if (isset($penalty_last_find) && !empty($penalty_last_find)) {
                                            if ($penalty_last_find->sum != $list['penalty']) {
                                                $penalty_last_find->end_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' - 1 minute'));
                                                if ($penalty_last_find->save()) {
                                                }else {
                                                    $error_try++;
                                                }
                                                $penalty = new Penalties();
                                                $penalty->sum = $list['penalty'];
                                                $penalty->start_date = date('Y-m-d H:i:s');
                                                $penalty->end_date = '9999-12-31 00:00:00';

                                                if ($penalty->save()) {
                                                }else {
                                                    $error_try++;
                                                }
                                                $penalty_users_last_find->penalty_id = $penalty->id;
                                                if ($penalty_users_last_find->save()) {
                                                }else {
                                                    $error_try++;
                                                }
                                            }
                                        }else {
                                            $penalty = new Penalties();
                                            $penalty->sum = $list['penalty'];
                                            $penalty->start_date = date('Y-m-d H:i:s');
                                            $penalty->end_date = '9999-12-31 00:00:00';
                                            if ($penalty->save()) {
                                            }else {
                                                $error_try++;
                                            }
                                            $penalty_users_last_find->penalty_id = $penalty->id;
                                            if ($penalty_users_last_find->save()) {
                                            }else {
                                                $error_try++;
                                            }
                                        }
                                        
                                    }else {
                                        $penalt_user = new PenaltyUsers();
                                        $penalt_user->user_id = $list['users'];
                                        $penalt_user->date = date('Y-m-d H:i:s');
                                        $penalt_user->penalty_sum = 0;
                                        
                                        $penalty = new Penalties();
                                        $penalty->sum = $list['penalty'];
                                        $penalty->start_date = date('Y-m-d H:i:s');
                                        $penalty->end_date = '9999-12-31 00:00:00';
                                        if ($penalty->save()) {
                                        }else {
                                            $error_try++;
                                        }
                                        
                                        $penalt_user->penalty_id = $penalty->id;
                                        if ($penalt_user->save()) {
                                        }else {
                                            $error_try++;
                                        }

                                    }
                                }
                            }
                        }else {
                            return [
                                'status' => 'success',
                            ];
                        }

                        $transaction->commit();
                        
                        return $this->redirect(['/index.php/meetup/index']);
                    } catch (Exception $e) {
                    
                        $transaction->rollBack();
                        throw $e;  

                        return [
                            'status' => "fail",
                            'message' => "Try dan o'tomadi",
                        ];

                    }
                }
            }
        }
    }

    public function actionDelete($id)
    {
        if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['status'])) {
            $quiz_id = $_GET['id'];
            $delete_quiz = Quiz::find()->where(['=','id',$quiz_id])->one();
            
            if (isset($delete_quiz) && !empty($delete_quiz)) {
                $delete_quiz->status = $_GET['status'];
                $delete_quiz->save();

                $delete_send_quiz = SendQuiz::find()->where(['=','quiz_id',$delete_quiz->id])->all(); 
                if (isset($delete_send_quiz) && !empty($delete_send_quiz)) {
                    foreach ($delete_send_quiz as $delete_one_quiz) {
                        $one_send_quiz = SendQuiz::find()->where(['=','id',$delete_one_quiz['id']])->one();
                        $one_send_quiz->status = $_GET['status'];
                        $one_send_quiz->save();
                    }
                }
            }
            return $this->redirect(['/index.php/meetup/index']);
        }else {
            return $this->redirect(['/index.php/meetup/index']);
        }

    }

    public function actionDeleteSendQuiz()
    {
        if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['status'])) {
            $quiz_id = $_POST['id'];
            $delete_quiz = SendQuiz::find()->where(['=','id',$quiz_id])->one();
            
            if (isset($delete_quiz) && !empty($delete_quiz)) {
                $delete_quiz->status = $_POST['status'];
                $delete_quiz->save();

                return json_encode([
                    'status' => 'success',
                    'change_status' => $_POST['status']
                ]); 
            }
        }
    }

    public function actionCategory()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['con']) && !empty($_GET['con'])) {
                if ($_GET['con'] == 'true') {
                    if (isset($_GET['title']) && !empty($_GET['title'])) {
                        $category = new QuizCategory();
                        $category->title = $_GET['title'];
                        $category->save();
                        return [
                            'status' => 'success',
                        ];    
                    }
                }else {
                    $category = new QuizCategory();
                    return [
                        'status' => 'success',
                        'content' => $this->renderAjax('category_form.php',[
                            'category' => $category
                        ]),
                    ];
                }
            }
        }
    }

    public function actionUpdateCategory()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['id']) && isset($_GET['update']) && $_GET['id'] > 0) {
                if ($_GET['update'] == 'true') {
                    $category = QuizCategory::find()->where(['=','id',$_GET['id']])->one();
                    $category->title = $_GET['title'];
                    $category->save();
                    return [
                        'status' => 'success',
                    ];
                }else {
                    $category = QuizCategory::find()->where(['=','id',$_GET['id']])->one();
                    return [
                        'status' => 'success',
                        'content' => $this->renderAjax('category_update_form.php',[
                            'category' => $category
                        ]),
                    ];
                }
            }
        }
    }
    
    public function actionDeleteCategory()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $category = QuizCategory::find()->where(['=','id',$_GET['id']])->one();
                $category->delete();

                $quiz = Quiz::find()->where(['=','category_id',$_GET['id']])->all();
                if (isset($quiz) && !empty($quiz)) {
                    foreach ($quiz as $delete_quiz) {
                        $send_quiz = SendQuiz::find()->where(['=','quiz_id',$delete_quiz['id']])->all();
                        if (isset($send_quiz) && !empty($send_quiz)) {
                            foreach ($send_quiz as $send_quiz_value) {
                                $send_quiz_value->delete();
                            }
                        }
                        $penlty = Penalties::find()->where(['=','quiz_id',$delete_quiz['id']])->all();
                        if (isset($penlty) && !empty($penlty)) {
                            foreach ($penlty as $penalty_value) {
                                $penalty_value->delete();
                            }
                        }
                        $delete_quiz->delete();
                    }
                }

                return [
                    'status' => 'success'
                ];
            }
        }
    }

    public function actionDeleteQuiz()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $quiz = Quiz::find()->where(['=','id',$_GET['id']])->one();
                $quiz->delete();

                $sendQuiz = SendQuiz::find()->where(['=','quiz_id',$_GET['id']])->all();
                if (isset($sendQuiz) && !empty($sendQuiz)) {
                    foreach ($sendQuiz as $UsersValue) {
                        $UsersValue->delete();
                    }
                }
                $penlty = Penalties::find()->where(['=','quiz_id',$_GET['id']])->all();
                if (isset($penlty) && !empty($penlty)) {
                    foreach ($penlty as $penalty_value) {
                        $penalty_value->delete();
                    }
                }

                return [
                    'status' => 'success'
                ];
            }
        }
    }

    public function actionPayPenalties()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] > 0) {
                $penalty_user = PenaltyUsers::find()->where(['=','id',$_GET['id']])->one();
                if (isset($penalty_user) && !empty($penalty_user)) {
                    $penalty_user->sum = 0;
                    $penalty_user->save();
                }

                return [
                    'status' => 'success'
                ];
            }
        }
    }

    public function actionUpdateQuestion()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['save']) && isset($_GET['update_question']) && $_GET['update_question'] > 0) {
                if ($_GET['save'] == 'true' && isset($_GET['Quiz']) && !empty($_GET['Quiz'])) {

                    if (isset($_GET['Quiz']['question']) && !empty($_GET['Quiz']['question'])) {
                        $quiz = Quiz::find()->where(['=','id',$_GET['update_question']])->one();
                        $quiz->question = $_GET['Quiz']['question'];
                        $quiz->save();
                    }

                    if (isset($_GET['Quiz']['users']) && !empty($_GET['Quiz']['users'])) {
                        $penalt_user = Penalties::find()->where(['=','quiz_id',$_GET['update_question']])->all();
                        if (isset($penalt_user) && !empty($penalt_user)) {
                            foreach ($penalt_user as $db_user_value) {
                                $db_user_value->delete();
                            }
                            foreach ($_GET['Quiz']['users'] as $user_value) {
                                $create_penlty = new Penalties();
                                $create_penlty->user_id = $user_value;
                                $create_penlty->quiz_id = $_GET['update_question'];
                                if ($_GET['Quiz']['penalty'] > 0) {
                                    $create_penlty->penalty = $_GET['Quiz']['penalty'];
                                }else {
                                    $create_penlty->penalty = 100;
                                }
                                $create_penlty->save();
                            }
                        }else {
                            foreach ($_GET['Quiz']['users'] as $pe_val) {
                                $create_penlty = new Penalties();
                                $create_penlty->user_id = $pe_val;
                                $create_penlty->quiz_id = $_GET['update_question'];
                                if ($_GET['Quiz']['penalty'] > 0) {
                                    $create_penlty->penalty = $_GET['Quiz']['penalty'];
                                }else {
                                    $create_penlty->penalty = 100;
                                }
                                $create_penlty->save();
                            }
                        }

                        $send_user = SendQuiz::find()->where(['=','quiz_id',$_GET['update_question']])->all();
                        if (isset($send_user) && !empty($send_user)) {
                            foreach ($send_user as $db_send_value) {
                                $db_send_value->delete();
                            
                            } 
                            foreach ($_GET['Quiz']['users'] as $add_user_send_quiz) {
                                $addSendQuiz = new SendQuiz();
                                $addSendQuiz->user_id = $add_user_send_quiz;
                                $addSendQuiz->quiz_id = $_GET['update_question'];
                                $addSendQuiz->save();
                            }
                        }else {
                            foreach ($_GET['Quiz']['users'] as $sq_val) {
                                $create_send = new SendQuiz();
                                $create_send->user_id = $sq_val;
                                $create_send->quiz_id = $_GET['update_question'];
                                $create_send->save();
                            }
                        }
                    }else {
                        $penalt_user = Penalties::find()->where(['=','quiz_id',$_GET['update_question']])->all();
                        if (isset($penalt_user) && !empty($penalt_user)) {
                            foreach ($penalt_user as $pu_value) {
                                $pu_value->delete();
                            }
                        }
                        $sendQuiz = SendQuiz::find()->where(['=','quiz_id',$_GET['update_question']])->all();
                        if (isset($sendQuiz) && !empty($sendQuiz)) {
                            foreach ($sendQuiz as $UsersValue) {
                                $UsersValue->delete();
                            }
                        }
                    }
                    
                    


                    return [
                        'status' => 'success',
                    ];

                }else {
                    $quiz = Quiz::find()->where(['=','id',$_GET['update_question']])->one();
                    $users_select = Users::find()->where(['!=','type',1])->all();
                    $users_sql = 'SELECT sq.user_id FROM send_quiz AS sq
                    WHERE sq.quiz_id = '.$_GET['update_question'].'
                    GROUP BY sq.user_id';
                    $penalty_sql = 'SELECT
                            p.quiz_id,
                            p.penalty
                        FROM penalties AS p
                        WHERE p.quiz_id = '.$_GET['update_question'].'
                        GROUP BY p.quiz_id,p.penalty
                    LIMIT 1';
                    $users = Yii::$app->db->createCommand($users_sql)
                    ->queryAll();
                    $penalty = Yii::$app->db->createCommand($penalty_sql)
                    ->queryOne();
                    

                    return [
                        'status' => 'success',
                        'content' => $this->renderAjax('update_form.php',[
                            'quiz' => $quiz,
                            'users' => $users,
                            'users_select' => $users_select,
                            'penalty' => $penalty,
                        ]),
                    ];
                }
            }
        }
    }

    public function actionSectionsChanges()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['sections_id']) && !empty($_GET['sections_id'])) {
                $users_section_sql = 'SELECT u.id,u.second_name FROM users_section AS us
                    LEFT JOIN users AS u
                        ON us.user_id = u.id
                    WHERE u."type" != 1 AND us.section_id = '.$_GET['sections_id'].'
                GROUP BY u.id';
                $users_section = Yii::$app->db->createCommand($users_section_sql)
                ->queryAll();
        
                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('sectons_form.php',[
                        'users_section' => $users_section
                    ]),
                ];
            }
        }
    }

    public function actionFilter()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_POST['users']) && isset($_POST['date']) && (!empty($_POST['users']) || $_POST['users'] == 0) && !empty($_POST['date'])) {
                if ($_POST['users'] == 0) {
                    $sql = 'SELECT 
                            u.second_name,
                            u."type",
                            da.date,
                            da.file_name AS file_id,
                            da.type AS da_type
                        FROM daily_answer AS da
                        INNER JOIN users AS u
                            ON u.id = da.user_id
                    WHERE da."status" = 1 AND da.date = \''.$_POST['date'].'\'
                    ORDER BY da.date DESC';
                }else {
                    $sql = 'SELECT 
                            u.second_name,
                            u."type",
                            da.date,
                            da.file_name AS file_id,
                            da.type AS da_type
                        FROM daily_answer AS da
                        INNER JOIN users AS u
                            ON u.id = da.user_id
                    WHERE da."status" = 1 AND da.date = \''.$_POST['date'].'\' AND da.user_id = '.$_POST['users'].'
                    ORDER BY da.date DESC';
                }

                $filtered = Yii::$app->db->createCommand($sql)
                ->queryAll();            

                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('filter.php', [
                        'filtered' => $filtered,
                    ]),
                ];    
            }elseif(isset($_POST['users']) && (!empty($_POST['users']) || $_POST['users'] == 0)) {
                if ($_POST['users'] == 0) {
                    $sql = 'SELECT 
                            u.second_name,
                            u."type",
                            da.date,
                            da.file_name AS file_id,
                            da.type AS da_type
                        FROM daily_answer AS da
                        INNER JOIN users AS u
                            ON u.id = da.user_id
                    WHERE da."status" = 1
                    ORDER BY da.date DESC';
                }else {
                    $sql = 'SELECT 
                            u.second_name,
                            u."type",
                            da.date,
                            da.file_name AS file_id,
                            da.type AS da_type
                        FROM daily_answer AS da
                        INNER JOIN users AS u
                            ON u.id = da.user_id
                    WHERE da."status" = 1 AND da.user_id = '.$_POST['users'].'
                    ORDER BY da.date DESC';
                }

                $filtered = Yii::$app->db->createCommand($sql)
                ->queryAll();            

                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('filter.php', [
                        'filtered' => $filtered,
                    ]),
                ];  
            }elseif(isset($_POST['date']) && !empty($_POST['date'])) {
                $sql = 'SELECT 
                        u.second_name,
                        u."type",
                        da.date,
                        da.file_name AS file_id,
                        da.type AS da_type
                    FROM daily_answer AS da
                    INNER JOIN users AS u
                        ON u.id = da.user_id
                WHERE da."status" = 1 AND da.date = \''.$_POST['date'].'\'
                ORDER BY da.date DESC';

                $filtered = Yii::$app->db->createCommand($sql)
                ->queryAll();            

                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('filter.php', [
                        'filtered' => $filtered,
                    ]),
                ];  
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = SendQuiz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
