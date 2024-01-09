<?php

namespace app\controllers;

use app\models\SaleCompany;
use app\models\SaleDistrict;
use app\models\SaleServices;
use app\models\SaleServicesType;
use Yii;
use app\models\SaleServiceStatistics;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SaleServiceStatisticsController implements the CRUD actions for SaleServiceStatistics model.
 */
class SaleServiceStatisticsController extends Controller
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
     * Lists all SaleServiceStatistics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $selectService = SaleServices::find()->all();
        $selectServiceType = SaleServicesType::find()->all();
        $selectCompany = SaleCompany::find()->all();
        $selectDistrict = SaleDistrict::find()->all();

        $selectAll = SaleServiceStatistics::find()->all();
        return $this->render('index', [
            'service' => $selectService,
            'serviceType' => $selectServiceType,
            'company' => $selectCompany,
            'district' => $selectDistrict,
            'model' => $selectAll
        ]);
    }

    /**
     * Displays a single SaleServiceStatistics model.
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
     * Creates a new SaleServiceStatistics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SaleServiceStatistics();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SaleServiceStatistics model.
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
     * Deletes an existing SaleServiceStatistics model.
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
     * Finds the SaleServiceStatistics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaleServiceStatistics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleServiceStatistics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
