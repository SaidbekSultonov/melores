<?php

namespace app\controllers;

use Yii;
use app\models\RequiredMaterials;
use app\models\SectionOrders;
use app\models\OrderResponsibles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequiredMaterialOrderController implements the CRUD actions for RequiredMaterialOrder model.
 */
// test
class RequiredMaterialOrderController extends Controller
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
     * Lists all RequiredMaterialOrder models.
     * @return mixed
     */
    public function actionIndex()
    {

        // $model = SectionOrders::find()->where(['exit_date' => NULL,'section_id' => 35])->all();

        // foreach ($model as $key => $value) {
        //     $res = new OrderResponsibles();
        //     $res->order_id = $value->order_id;
        //     $res->section_id = $value->section_id;
        //     $res->user_id = 329;
        //     $res->save();

        // }

        $dataProvider = new ActiveDataProvider([
            'query' => RequiredMaterials::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequiredMaterialOrder model.
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
     * Creates a new RequiredMaterialOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RequiredMaterials();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index.php/required-material-order/view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RequiredMaterialOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index.php/required-material-order/view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RequiredMaterialOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index.php/required-material-order/index']);
    }

    /**
     * Finds the RequiredMaterialOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RequiredMaterialOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RequiredMaterials::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
