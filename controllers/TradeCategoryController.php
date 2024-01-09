<?php

namespace app\controllers;

use Yii;
use app\models\TradeCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TradeCategoryController implements the CRUD actions for TradeCategory model.
 */
class TradeCategoryController extends Controller
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
     * Lists all TradeCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TradeCategory::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TradeCategory model.
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
     * Creates a new TradeCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $arr = ["status" => "fail","error" => "Bad request!"];
        if (Yii::$app->request->isAjax) {
            $model = new TradeCategory();
            if (Yii::$app->request->isGet) {
                $arr = [
                    "status" => "success",
                    "content" => $this->renderAjax('_form', [
                        'model' => $model
                    ]),
                ];
            } else if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();

                $model->status = 1;
                if ($model->load(Yii::$app->request->post()) && $model->save()){
                    $arr = [
                        "status" => "success",
                    ];
                } else {
                    $arr = [
                        "status" => "fail",
                        "content" => $this->renderAjax('_form', [
                            'model' => $model
                        ]),
                    ];
                }
            }
        }
        return $arr;
    }

    /**
     * Updates an existing TradeCategory model.
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
     * Deletes an existing TradeCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the TradeCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TradeCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TradeCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
