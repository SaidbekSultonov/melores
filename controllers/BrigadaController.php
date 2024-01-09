<?php

namespace app\controllers;

use Yii;
use app\models\Brigada;
use app\models\BrigadaUsers;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrigadaController implements the CRUD actions for Brigada model.
 */
class BrigadaController extends Controller
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
     * Lists all Brigada models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Brigada::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brigada model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $workers = BrigadaUsers::find()->where('brigada_id = :id', [':id' => $id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'workers' => $workers
        ]);
    }


    public function actionOpenModal() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = new Brigada();
            $model2 = new BrigadaUsers();
            $users = Users::find()->select(['id', 'second_name'])->where("type IN (7, 6, 2, 1)")->asArray()->all();
            $brigadir = Users::find()->where('type = :type', [':type' => 10])->all();
            $arr = [];
            if (isset($users) && !empty($users)) {
                foreach ($users as $value) {
                    $arr[$value['id']] = $value['second_name'];
                }
            }
            return [
                'header' => '<h3>Brigadalarni qo`shish</h3>',
                'status' => 'success',
                'content' => $this->renderAjax('open-modal.php', [
                    'model' => $model,
                    'model2' => $model2,
                    'users' => $arr,
                    'brigadir' => $brigadir,
                ]),
            ];
        }
    }

    public function actionSaveBrigada()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (Yii::$app->request->post()) {
                $post = Yii::$app->request->post();

                $model = new Brigada();
                if ($model->load(Yii::$app->request->post())) {
                    $model->save();
                    $brigadaId = $model->id;
                    
                    $brigadaUsers = $post['BrigadaUsers']['user_id'];
                    foreach ($brigadaUsers as $worker) {
                        $model2 = new BrigadaUsers();
                        $model2->brigada_id = $brigadaId;
                        $model2->user_id = $worker;
                        $model2->save();
                    }

                    return ['status' => 'success'];
                } else {
                    echo "<pre>";
                    print_r($model->errors);
                }

            }
        }
    }

    /**
     * Creates a new Brigada model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * Updates an existing Brigada model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if (Yii::$app->request->isGet) {
                $get = Yii::$app->request->get();
                $id = $get["id"];

                $model = Brigada::findOne($id);
                $brigadir = $model->user_id;

                $model2 = new BrigadaUsers();
                $model3 = BrigadaUsers::find()->where('brigada_id = :id', [':id' => $id])->all();
                $selectUser = [];
                if (isset($model3) && !empty($model3)) {
                    foreach ($model3 as $s) {
                        $selectUser[] = $s['user_id'];
                    }
                }

                $brigadirs = Users::find()->select(['id', 'second_name'])->where('type = :type', [':type' => 10])->asArray()->all();
                $arrBrig = [];
                if (isset($brigadirs) && !empty($brigadirs)) {
                    foreach ($brigadirs as $value) {
                        $arrBrig[$value['id']] = $value['second_name'];
                    }
                }
                
                $users = Users::find()->select(['id', 'second_name'])->where("type IN (7, 2, 1)")->asArray()->all();
                $arr = [];
                if (isset($users) && !empty($users)) {
                    foreach ($users as $value) {
                        $arr[$value['id']] = $value['second_name'];
                    }
                }

                return [
                    'header' => '<h3>Brigadani o`zgartirish</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('team_edit.php', [
                        'model' => $model,
                        'model2' => $model2,
                        'selected' => $selectUser,
                        'users' => $arr,
                        'brigadir' => $brigadir,
                        'brigadirs' => $arrBrig,
                    ]),
                ];
            }
        }
    }
    public function actionUpdateBrigada()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (Yii::$app->request->post()) {
                $post = Yii::$app->request->post();
                $id = $post['Brigada']['id'];

                $model = Brigada::findOne($id);
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    BrigadaUsers::deleteAll('brigada_id = :id', ['id' => $id]);
                    $brigadaUsers = $post['BrigadaUsers']['user_id'];
                    foreach ($brigadaUsers as $worker) {
                        $model2 = new BrigadaUsers();
                        $model2->brigada_id = $id;
                        $model2->user_id = $worker;
                        $model2->save();
                    }

                    return ['status' => 'success'];
                } else {
                    echo "<pre>";
                    print_r($model->errors);
                }

            }
        }
    }

    /**
     * Deletes an existing Brigada model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == 0) {
            $model->status = 1;
        } else {
            $model->status = 0;
        }
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->save();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Brigada model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brigada the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Brigada::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
