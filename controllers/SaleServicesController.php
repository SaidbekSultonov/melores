<?php

namespace app\controllers;

use Yii;
use app\models\SaleServices;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SaleServicesController implements the CRUD actions for SaleServices model.
 */
class SaleServicesController extends Controller
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
     * Lists all SaleServices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = SaleServices::find()
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
     * Displays a single SaleServices model.
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
     * Creates a new SaleServices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'status' => 'success',
                'header' => '<h3>Kategoriya qo`shish</h3>',
                'content' => $this->renderAjax('_form.php'),
            ];
        }
    }

    /**
     * Updates an existing SaleServices model.
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
                'header' => '<h3>Kategoriyani o`zgartirish</h3>',
                'content' => $this->renderAjax('form-update.php',[
                    'model' => $model
                ]),
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

                $model = new SaleServices();
                $model->title_uz = $services_uz;
                $model->title_ru = $services_ru;
                $model->type = 1;
                $model->click_count = 0;

                $find = SaleServices::find()
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


                $model = SaleServices::findOne($id);
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
     * Deletes an existing SaleServices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = SaleServices::findOne($id);
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

                $model = SaleServices::findOne($id);

                if (isset($model)) {
                    $find = SaleServices::find()
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
     * Finds the SaleServices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaleServices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleServices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
