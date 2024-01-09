<?php

namespace app\controllers;

use Yii;
use app\models\Events;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
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
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Events::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'route' => "index.php/events/",
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Events model.
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
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Events();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Events model.
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

    public function actionSection()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($_GET['first_date'] < $_GET['second_date'] AND !empty($_GET['first_date'])){
                $section_name = $_GET["section_name"];
                $first_date = $_GET["first_date"];
                $second_date = $_GET["second_date"];

                $sql = "SELECT * from events WHERE created_date BETWEEN '".$first_date."' AND '".$second_date."' AND section_title = '".$section_name."' ORDER BY created_date DESC";
                $event = Yii::$app->db->createCommand($sql)->queryAll();
                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('section.php', [
                        'event' => $event
                    ]),
                ];
            } else if(empty($_GET['first_date']) and empty($_GET['second_date'])){
                $section_name = $_GET["section_name"];
                $sql = "SELECT * from events where section_title = '".$section_name."' ORDER BY created_date DESC";
                $event = Yii::$app->db->createCommand($sql)->queryAll();
                return [
                    'status' => 'success',
                    'content' => $this->renderAjax('section.php', [
                        'event' => $event
                    ]),
                ];
            } else {
                Yii::$app->session->setFlash('success1', "Sana notog'ri kritildi!");
                return $this->redirect(['/index.php/events/index']);
            }

        } else {
            return [
                'status' => 'fail',
                'content' => 'Ajaxdan ma\'lumot kelishida xatolik yuz berdi'
            ];
        }
    }

    /**
     * Deletes an existing Events model.
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
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
