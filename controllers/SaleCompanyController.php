<?php

namespace app\controllers;

use app\models\SaleCompanyFiles;
use app\models\SaleCompanyServicesType;
use app\models\SaleServiceStatistics;
use Yii;
use app\models\SaleCompany;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SaleCompanyController implements the CRUD actions for SaleCompany model.
 */
class SaleCompanyController extends Controller
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
     * Lists all SaleCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = SaleCompany::find()
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
     * Displays a single SaleCompany model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = intval($id);
        $files = SaleCompanyFiles::find()
            ->where([
                'company_id' => $id
            ])
            ->all();


        return $this->render('view', [
            'model' => $this->findModel($id),
            'files' => $files,
        ]);
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

                $find = SaleCompany::find()
                    ->orderBy([
                        'order_column' => SORT_DESC
                    ])
                    ->one();

                $model = new SaleCompany();
                $model->title_uz = $services_uz;
                $model->title_ru = $services_ru;
                $model->click_count = 0;
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

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'status' => 'success',
                'header' => '<h3>Kompaniya qo`shish</h3>',
                'content' => $this->renderAjax('_form.php'),
            ];
        }
    }

    /**
     * Updates an existing SaleCompany model.
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
                'header' => '<h3>Kompaniya nomini o`zgartirish</h3>',
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


                $model = SaleCompany::findOne($id);
                if (isset($model)) {
                    $model->title_uz = $services_uz;
                    $model->title_ru = $services_ru;
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

    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $companyFiles = SaleCompanyFiles::find()->where(['company_id' => $id])->all();
            if (isset($companyFiles) and !empty($companyFiles)){
                foreach ($companyFiles as $value){
                    $value->delete();
                }
            }

            $companyServiceType = SaleCompanyServicesType::find()->where(['company_id' => $id])->all();
            if (isset($companyServiceType) and !empty($companyServiceType)){
                foreach ($companyServiceType as $value){
                    $value->delete();
                }
            }

            $companyStatistics = SaleServiceStatistics::find()->where(['type' => 'company', 'button_id' => $id])->all();
            if (isset($companyStatistics) and !empty($companyStatistics)){
                foreach ($companyStatistics as $value){
                    $value->delete();
                }
            }

            $model = SaleCompany::findOne($id);
            $others = SaleCompany::find()
                ->where(['!=','id',$id])
                ->andWhere(['>','order_column', $model->order_column])
                ->all();

            if (!empty($others)) {
                foreach ($others as $key => $value) {
                    $value->order_column = $value->order_column - 1;
                    $value->save();
                }
            }

            $model->delete();


            $transaction->commit();
            return $this->redirect(Yii::$app->request->referrer);

        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;

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

                $model = SaleCompany::findOne($id);

                if (isset($model)) {
                    $find = SaleCompany::find()
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
     * Finds the SaleCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaleCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleCompany::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
