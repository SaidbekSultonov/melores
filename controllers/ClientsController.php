<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\Clients;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClientsController implements the CRUD actions for Clients model.
 */
class ClientsController extends Controller
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
     * Lists all Clients models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Clients::find()->orderBy(['id' => SORT_ASC]),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clients model.
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
     * Creates a new Clients model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clients();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            $model->full_name = base64_encode($model->full_name);
            $model->phone_number = base64_encode($model->phone_number);

            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), base64_decode($model->full_name), "Klient qo'shildi", 'Mijoz');

                //
                return $this->redirect(['/index.php/clients/view', 'id' => $model->id]);    
            }
            else{
                pre($model->errors);
            }

            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Clients model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->full_name = base64_encode($model->full_name);
            $model->phone_number = base64_encode($model->phone_number);

            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), $model->full_name, "Klient o'zgartirildi", 'Mijoz');

                //
                return $this->redirect(['/index.php/clients/view', 'id' => $model->id]);
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Clients model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Clients::findOne($id);
        if ($model->status == 0)
            $model->status = 1;
        else
            $model->status = 0;    
        
        if($model->save()){
            //                          START ADD EVENT
            $user_id = Yii::$app->user->id;

            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
            $userId = $selectUsers->id;

            eventUser($userId, date('Y-m-d H:i:s'), $model->full_name, "Klient o'chirildi", 'Mijoz');

            //
        }

        return $this->redirect(['/index.php/clients/index']);
    }

    /**
     * Finds the Clients model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clients the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clients::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
