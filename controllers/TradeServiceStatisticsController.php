<?php

namespace app\controllers;

use app\models\TradeCompany;
use app\models\TradeDistrict;
use app\models\TradeServices;
use app\models\TradeServicesTypes;
use Yii;
use app\models\TradeServiceStatistics;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TradeServiceStatisticsController implements the CRUD actions for TradeServiceStatistics model.
 */
class TradeServiceStatisticsController extends Controller
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
     * Lists all TradeServiceStatistics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $selectService = TradeServices::find()->all();
        $selectServiceType = TradeServicesTypes::find()->all();
        $selectCompany = TradeCompany::find()->all();
        $selectDistrict = TradeDistrict::find()->all();

//        $sql = "SELECT *
//                FROM service_statistics";
//        $allStatistics = Yii::$app->db->createCommand($sql)->queryAll();
        $selectAll = TradeServiceStatistics::find()->all();
        return $this->render('index', [
            'service' => $selectService,
            'serviceType' => $selectServiceType,
            'company' => $selectCompany,
            'district' => $selectDistrict,
            'model' => $selectAll
        ]);
    }

    /**
     * Displays a single TradeServiceStatistics model.
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
     * Creates a new TradeServiceStatistics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TradeServiceStatistics();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TradeServiceStatistics model.
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
     * Deletes an existing TradeServiceStatistics model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TradeServiceStatistics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TradeServiceStatistics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TradeServiceStatistics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
