<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\Branch;
use app\models\OrderStep;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BranchController implements the CRUD actions for Branch model.
 */
class BranchController extends Controller
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
     * Lists all Branch models.
     * @return mixed
     */
    public function actionIndex()
    {

        
        $dataProvider = new ActiveDataProvider([
            'query' => Branch::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Branch model.
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
     * Creates a new Branch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Branch();
        $users = Users::find()->where(['type' => 4])->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;

            if ($model->save()) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Filial qo'shildi", 'Filial');

                //
                return $this->redirect(['/index.php/branch/view', 'id' => $model->id]);    
            }
            else{
                pre($model->errors);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Updates an existing Branch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $users = Users::find()->where(['type' => 4])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //                          START ADD EVENT
            $user_id = Yii::$app->user->id;

            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
            $userId = $selectUsers->id;

            eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Filial o'zgartirildi", 'Filial');

            //
            return $this->redirect(['/index.php/branch/view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Deletes an existing Branch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Branch::findOne($id);
        if ($model->status == 0)
            $model->status = 1;
        else
            $model->status = 0;    
        
        if($model->save()){
            //                          START ADD EVENT
            $user_id = Yii::$app->user->id;

            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
            $userId = $selectUsers->id;

            eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Filial o'chirildi", 'Filial');

            //
        }

        return $this->redirect(['/index.php/branch/index']);
    }

    /**
     * Finds the Branch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
