<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\FeedbackUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FeedbackUserController implements the CRUD actions for FeedbackUser model.
 */
class FeedbackUserController extends Controller
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
     * Lists all FeedbackUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeedbackUser::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FeedbackUser model.
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
     * Creates a new FeedbackUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FeedbackUser();
        $model->status = 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->title = base64_encode($model->title);
            if($model->save()){
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), base64_decode($model->title), "Savol qo'shildi", 'User uchun savol');

                //                          END ADD EVENT
            }
            return $this->redirect(['/index.php/feedback-user/view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FeedbackUser model.
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
            if($model->save()){
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), base64_decode($model->title), "Savol o'zgartirildi", 'User uchun savol');

                //
            }
            return $this->redirect(['/index.php/feedback-user/view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FeedbackUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $questionName = $this->findModel($id)->title;
        $this->findModel($id)->delete();
        //                          START ADD EVENT
        $user_id = Yii::$app->user->id;

        $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
        $userId = $selectUsers->id;

        eventUser($userId, date('Y-m-d H:i:s'), base64_decode($questionName), "Savol o'chirildi", 'User uchun savol');

        //

        return $this->redirect(['index.php/feedback-user/index']);
    }

    /**
     * Finds the FeedbackUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeedbackUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeedbackUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
