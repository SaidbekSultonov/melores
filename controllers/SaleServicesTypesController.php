<?php

namespace app\controllers;

use app\models\SaleServices;
use Yii;
use app\models\SaleServicesType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SaleServicesTypesController implements the CRUD actions for SaleServicesType model.
 */
class SaleServicesTypesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }

    }

    /**
     * Lists all SaleServicesType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = SaleServicesType::find()
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
     * Displays a single SaleServicesType model.
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
     * Creates a new SaleServicesType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = SaleServices::find()
                ->where([
                    'type' => 1
                ])
                ->all();

            return [
                'status' => 'success',
                'header' => '<h3>Kategoriya turini qo`shish</h3>',
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

                $model = new SaleServicesType();
                $model->title_uz = $services_uz;
                $model->title_ru = $services_ru;
                $model->services_id = $services_id;
                $model->status = 1;
                $model->click_count = 0;

                $find = SaleServicesType::find()
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
     * Updates an existing SaleServicesType model.
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
            $services = SaleServices::find()
                ->where([
                    'type' => 1
                ])
                ->all();

            return [
                'status' => 'success',
                'header' => '<h3>Kategoriya turini o`zgartirish</h3>',
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


                $model = SaleServicesType::findOne($id);
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
     * Deletes an existing SaleServicesType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = SaleServicesType::findOne($id);
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

                $model = SaleServicesType::findOne($id);

                if (isset($model)) {
                    $find = SaleServicesType::find()
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
     * Finds the SaleServicesType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaleServicesType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleServicesType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
