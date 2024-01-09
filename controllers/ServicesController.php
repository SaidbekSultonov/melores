<?php

namespace app\controllers;

use Yii;
use app\models\Services;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicesController implements the CRUD actions for Services model.
 */
class ServicesController extends Controller
{
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        
    }
   

    /**
     * Lists all Services models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Services::find()
        ->orderBy([
            'order_column' => SORT_ASC
        ])
        ->all();
        $orders = [];
        foreach ($model as $key => $value) {
            $orders[$value->order_column] = $value->order_column;
        }

        return $this->render('index', [
            'model' => $model,
            'orders' => $orders,
        ]);
    }

    /**
     * Displays a single Services model.
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
     * Creates a new Services model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'status' => 'success',
                'header' => '<h3>Yo`nalish qo`shish</h3>',
                'content' => $this->renderAjax('_form.php'),
            ];
        }
    }

    public function actionSaveServices()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['services_uz']) && isset($_GET['services_ru'])) {

                if (empty($_GET['services_uz'])) {
                    return ['status' => 'failure_uz'];
                }
                
                if (empty($_GET['services_ru'])) {
                    return ['status' => 'failure_ru'];
                }

                $services_uz = htmlspecialchars($_GET['services_uz']);
                $services_ru = htmlspecialchars($_GET['services_ru']);

                $model = new Services();
                $model->title_uz = $services_uz;
                $model->title_ru = $services_ru;
                $model->type = 1;

                $find = Services::find()
                ->orderBy([
                    'order_column' => SORT_DESC
                ])
                ->one();

                if (isset($find)) {
                    $model->order_column = $find->order_column + 1;
                }
                else{
                    $model->order_column = 1;
                }

                if ($model->save()) {
                    return ['status' => 'success'];
                }
                else{
                    pre($model->errors);
                }

            }
            
        }
    }

    /**
     * Updates an existing Services model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id = intval($_GET['id']);

            $model = $this->findModel($id);

            return [
                'status' => 'success',
                'header' => '<h3>Yo`nalishni o`zgartirish</h3>',
                'content' => $this->renderAjax('form-update.php',[
                    'model' => $model
                ]),
            ];
        }
    }

    public function actionUpdateSave()
    {
         if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (isset($_GET['services_uz']) && isset($_GET['services_ru']) && isset($_GET['id'])) {

                    if (empty($_GET['services_uz'])) {
                        return ['status' => 'failure_uz'];
                    }
                    
                    if (empty($_GET['services_ru'])) {
                        return ['status' => 'failure_ru'];
                    }

                    $id = intval($_GET['id']);
                    $services_uz = htmlspecialchars($_GET['services_uz']);
                    $services_ru = htmlspecialchars($_GET['services_ru']);


                    $model = Services::findOne($id);
                    if (isset($model)) {
                        $model->title_uz = $services_uz;
                        $model->title_ru = $services_ru;
                        $model->type = 1;
                        if ($model->save()) {
                            return ['status' => 'success'];
                        }
                        else{
                            pre($model->errors);
                        }

                    }
                    else{
                        return ['status' => 'failure'];
                    }
                    

                    
            }
        }
    }

    /**
     * Deletes an existing Services model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Services::findOne($id);
        if ($model->type == 1)
            $model->type = 0;
        else
            $model->type = 1;

        if ($model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionOrder()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($_GET['id'] && $_GET['order']) {
                $id = intval($_GET['id']);
                $order = intval($_GET['order']);

                $model = Services::findOne($id);

                if (isset($model)) {
                    $find = Services::find()
                    ->where([
                        'order_column' => $order
                    ])
                    ->one();

                    $find->order_column = $model->order_column;
                    $find->save();
                    $model->order_column = $order;
                    $model->save();

                    return ['status' => 'success'];
                }
                else{
                    return ['status' => 'failure'];
                }

            }

        }
    }

    /**
     * Finds the Services model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Services the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Services::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
