<?php

namespace app\controllers;

use Yii;
use app\models\ServicesTypes;
use app\models\Services;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicesTypesController implements the CRUD actions for ServicesTypes model.
 */
class ServicesTypesController extends Controller
{
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        
    }

    /**
     * Lists all ServicesTypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = ServicesTypes::find()
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
     * Displays a single ServicesTypes model.
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
     * Creates a new ServicesTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = Services::find()
            ->where([
                'type' => 1
            ])
            ->all();

            return [
                'status' => 'success',
                'header' => '<h3>Bo`lim qo`shish</h3>',
                'content' => $this->renderAjax('_form.php',[
                    'model' => $model
                ]),
            ];
        }
    }

    public function actionSaveServicesTypes()
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

                if (empty($_GET['services_id'])) {
                    return ['status' => 'failure_id'];
                }

                $services_uz = htmlspecialchars($_GET['services_uz']);
                $services_ru = htmlspecialchars($_GET['services_ru']);
                $services_id = intval($_GET['services_id']);

                $model = new ServicesTypes();
                $model->title_uz = $services_uz;
                $model->title_ru = $services_ru;
                $model->services_id = $services_id;

                $find = ServicesTypes::find()
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
     * Updates an existing ServicesTypes model.
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
            $services = Services::find()
            ->where([
                'type' => 1
            ])
            ->all();

            return [
                'status' => 'success',
                'header' => '<h3>Bo`limni o`zgartirish</h3>',
                'content' => $this->renderAjax('form-update.php',[
                    'model' => $model,
                    'services' => $services
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
                $services_id = intval($_GET['services_id']);


                $model = ServicesTypes::findOne($id);
                if (isset($model)) {
                    $model->title_uz = $services_uz;
                    $model->title_ru = $services_ru;
                    $model->services_id = $services_id;
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
     * Deletes an existing ServicesTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = ServicesTypes::findOne($id);
        if ($model->status == 1)
            $model->status = 0;
        else
            $model->status = 1;

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

                $model = ServicesTypes::findOne($id);

                if (isset($model)) {
                    $find = ServicesTypes::find()
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
     * Finds the ServicesTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServicesTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServicesTypes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
