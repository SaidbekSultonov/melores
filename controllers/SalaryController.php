<?php

namespace app\controllers;

use app\models\SalaryAnswer;
use app\models\SalaryEventBalance;
use app\models\Users;
use Yii;
use app\models\SalaryAmount;
use app\models\SalaryCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SalaryController implements the CRUD actions for SalaryAmount model.
 */
class SalaryController extends Controller
{
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
     * Lists all SalaryAmount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $sql = "SELECT u.second_name as second_name, sc.title as title, SUM(sm.price) AS price, sm.task_id, sm.date FROM salary_amount AS sm 
INNER JOIN users AS u ON sm.chat_id = u.chat_id
INNER JOIN salary_category AS sc ON sm.category_id = sc.id
-- WHERE 
GROUP BY u.second_name, sc.title, sm.task_id, sm.date 
ORDER BY sm.date DESC limit 100";


        $command = Yii::$app->db->createCommand($sql)->queryAll();

        $selectCategory = SalaryCategory::find()->all();
        $selectAnswer = SalaryAnswer::find()->all();
        $selectEventBalance = SalaryEventBalance::find()->orderBy(['id' => SORT_DESC])->limit(50)->all();
        $selectUsers = Users::find()->all();
    // pre($selectEventBalance);
        return $this->render('index', [
            'model' => $command,
            'selectCategory' => $selectCategory,
            'answer' => $selectAnswer,
            'event' => $selectEventBalance,
            'selectUsers' => $selectUsers
        ]);
    }

    /**
     * Displays a single SalaryAmount model.
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
     * Creates a new SalaryAmount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SalaryAmount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SalaryAmount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SalaryAmount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index.php/salary/index']);
    }

    public function actionCategory()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['cat']) && !empty($_GET['cat'])) {
                if ($_GET['cat'] == 'true') {
                    if ($_GET['category_balance'] != 0){
                        if (isset($_GET['category']) && !empty($_GET['category']) && isset($_GET['category_balance']) && !empty($_GET['category_balance'])) {
                            $category = new SalaryCategory();
                            $category->title = $_GET['category'];
                            $category->balance = $_GET['category_balance'];
                            $category->save();
                            return [
                                'status' => 'success',
                            ];
                        }
                    } else {
                        return [
                            'status' => 'null',
                        ];
                    }
                }else {
                    $category = new SalaryCategory();
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
            if (isset($_GET['id']) && isset($_GET['edit']) && $_GET['id'] > 0) {
                if ($_GET['edit'] == 'true') {
                    $category = SalaryCategory::find()->where(['=','id',$_GET['id']])->one();
                    $category->title = $_GET['category'];
                    $category->balance = $category->balance + $_GET['category_balance'];
                    $category->save();
                    return [
                        'status' => 'success',
                    ];
                }else {
                    $category = SalaryCategory::find()->where(['=','id',$_GET['id']])->one();
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

    public function actionAnswer()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['ans']) && !empty($_GET['ans'])) {
                if ($_GET['ans'] == 'true') {
                    if (isset($_GET['answer']) && !empty($_GET['answer'])) {
                        $salary = new SalaryAnswer();
                        $salary->title = $_GET['answer'];
                        $salary->status = 1;
                        $salary->save();
                        return [
                            'status' => 'success',
                        ];
                    }
                }else {
                    $salary = new SalaryAnswer();
                    return [
                        'status' => 'success',
                        'content' => $this->renderAjax('answer_form.php',[
                            'answer' => $answer
                        ]),
                    ];
                }
            }
        }
    }

    public function actionUpdateAnswer()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['id']) && isset($_GET['edit']) && $_GET['id'] > 0) {
                if ($_GET['edit'] == 'true') {
                    $answer = SalaryAnswer::find()->where(['=','id',$_GET['id']])->one();
                    $answer->title = $_GET['answer'];
                    $answer->save();
                    return [
                        'status' => 'success',
                    ];
                }else {
                    $answer = SalaryAnswer::find()->where(['=','id',$_GET['id']])->one();
                    return [
                        'status' => 'success',
                        'content' => $this->renderAjax('answer_update_form.php',[
                            'answer' => $answer
                        ]),
                    ];
                }
            }
        }
    }

    public function actionSalary(){
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $part_sql = '';
            if (isset($_GET['date']) and !empty($_GET['date'])) {
                $date = $_GET["date"];
                $str_date = date('m', strtotime($date));
                $last_date = date('Y-'.$str_date.'-t');
                $part_sql .= ($part_sql == '') ? " WHERE date > '".$date." 00:00:00' and date < '".$last_date." 23:59:59'" : " AND date > '".$date." 00:00:00' and date < '".$last_date." 23:59:59'";
            }

            if (isset($_GET['category']) and !empty($_GET['category'])) {
                $category = $_GET["category"];
                $part_sql .= ($part_sql == '') ? " WHERE category_id = ".$category : " AND category_id = ".$category ;
            }

            if (isset($_GET['user']) and !empty($_GET['user'])) {
                $user = $_GET["user"];
                $part_sql .= ($part_sql == '') ? " WHERE user_id = " . $user : " AND user_id = " . $user ;
            }

            $sql = "SELECT * FROM salary_amount ".$part_sql;
            $salary = Yii::$app->db->createCommand($sql)->queryAll();

            return [
                'status' => 'success',
                'content' => $this->renderAjax('salary.php', [
                    'salary' => $salary
                ]),
            ];
        }
    }

    public function actionBalance(){
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $part_sql = '';
            if (isset($_GET['balance_date']) and !empty($_GET['balance_date'])) {
                $date = $_GET["balance_date"];
                $part_sql .= ($part_sql == '') ? " WHERE date > '".$date." 00:00:00' and date < '".$date." 23:59:59'" : " AND date > '".$date." 00:00:00' and date < '".$date." 23:59:59'";
            }

            if (isset($_GET['balance_user']) and !empty($_GET['balance_user'])) {
                $balance_user = $_GET["balance_user"];
                $selectUser = Users::find()->where(['id' => $balance_user])->one();
                if (isset($selectUser) and !empty($selectUser)){
                    $user_chat_id = $selectUser->chat_id;
                }
                $part_sql .= ($part_sql == '') ? " WHERE receiver = ".$user_chat_id." or user_id = ".$balance_user : " AND receiver = ".$user_chat_id." or category_id = ".$balance_user ;
            }

            $sql = "SELECT * FROM salary_event_balance ".$part_sql." ORDER BY id desc";
            $event = Yii::$app->db->createCommand($sql)->queryAll();

            return [
                'status' => 'success',
                'content' => $this->renderAjax('balance.php', [
                    'event' => $event
                ]),
            ];
        }
    }

    /**
     * Finds the SalaryAmount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SalaryAmount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalaryAmount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
