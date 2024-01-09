<?php

namespace app\controllers;

use app\models\CompanyDistrict;
use app\models\CompanyServicesType;
use app\models\ServiceStatistics;
use Yii;
use app\models\Company;
use app\models\CompanyFiles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use function Webmozart\Assert\Tests\StaticAnalysis\email;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Company::find()
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
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = intval($id);
        $files = CompanyFiles::find()
        ->where([
            'company_id' => $id
        ])
        ->orderBy([
            'id' => SORT_ASC
        ])
        ->all();


        return $this->render('view', [
            'model' => $this->findModel($id),
            'files' => $files,
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
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

                $find = Company::find()
                ->orderBy([
                    'order_column' => SORT_DESC
                ])
                ->one();

                $model = new Company();
                $model->title_uz = $services_uz;
                $model->title_ru = $services_ru;
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


                    $model = Company::findOne($id);
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

    /**
     * Deletes an existing Services model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $companyDistrict = CompanyDistrict::find()->where(['company_id' => $id])->all();
            if (isset($companyDistrict) and !empty($companyDistrict)){
                foreach ($companyDistrict as $value){
                    $value->delete();
                }
            }

            $companyFiles = CompanyFiles::find()->where(['company_id' => $id])->all();
            if (isset($companyFiles) and !empty($companyFiles)){
                foreach ($companyFiles as $value){
                    $value->delete();
                }
            }

            $companyServiceType = CompanyServicesType::find()->where(['company_id' => $id])->all();
            if (isset($companyServiceType) and !empty($companyServiceType)){
                foreach ($companyServiceType as $value){
                    $value->delete();
                }
            }

            $companyStatistics = ServiceStatistics::find()->where(['type' => 'company', 'button_id' => $id])->all();
            if (isset($companyStatistics) and !empty($companyStatistics)){
                foreach ($companyStatistics as $value){
                    $value->delete();
                }
            }

            $model = Company::findOne($id);
            $others = Company::find()
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

                $model = Company::findOne($id);

                if (isset($model)) {
                    $find = Company::find()
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
