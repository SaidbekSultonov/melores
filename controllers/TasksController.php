<?php

namespace app\controllers;

use app\models\OrderCategories;
use app\models\Orders;
use app\models\OrderStep;
use app\models\RequiredMaterialOrder;
use app\models\RequiredMaterials;
use app\models\SectionOrdersControl;
use app\models\TaskMaterials;
use app\models\TaskStatus;
use app\models\TaskUser;
use app\models\TaskUserMaterials;
use app\models\Team;
use app\models\Users;
use Yii;
use app\models\Tasks;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends Controller
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
     * Lists all Tasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tasks::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => false
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTaskdelete()
    {
        if (isset(Yii::$app->request->get()['id']) and isset(Yii::$app->request->get()['user_id'])) {
            $task_id = $_GET["id"];
            $user_id = $_GET["user_id"];

            $deleteUser = TaskUser::find()->where(['user_id' => $user_id, 'task_id' => $task_id])->one();
            if (isset($deleteUser) and !empty($deleteUser)) {
                $deleteUser->delete();
            }

            $deleteUserMaterial = TaskUserMaterials::find()->where(['user_id' => $user_id, 'task_id' => $task_id])->one();
            if (isset($deleteUserMaterial) and !empty($deleteUserMaterial)) {
                $deleteUserMaterial->delete();
            }

            return $this->redirect(['/index.php/tasks/index']);
        }
    }

    public function actionStatusdelete()
    {
        if (isset(Yii::$app->request->get()['id'])) {
            $task_id = $_GET["id"];

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $deleteTask = Tasks::find()->where(['id' => $task_id])->one();
                if (isset($deleteTask) and !empty($deleteTask)) {
                    $deleteTask->delete();
                }

                $deleteUser = TaskUser::find()->where(['task_id' => $task_id])->all();
                if (isset($deleteUser) and !empty($deleteUser)) {
                    foreach ($deleteUser as $key => $value) {
                        $value->delete();
                    }
                }

                $deleteUserMaterial = TaskUserMaterials::find()->where(['task_id' => $task_id])->all();
                if (isset($deleteUserMaterial) and !empty($deleteUserMaterial)) {
                    foreach ($deleteUserMaterial as $key => $value) {
                        $value->delete();
                    }
                }

                $deleteMaterials = TaskMaterials::find()->where(['task_id' => $task_id])->one();
                if (isset($deleteMaterials) and !empty($deleteMaterials)) {
                    $deleteMaterials->delete();
                }

                $deleteTaskStatus = TaskStatus::find()->where(['task_id' => $task_id])->all();
                if (isset($deleteTaskStatus) and !empty($deleteTaskStatus)) {
                    foreach ($deleteTaskStatus as $key => $value) {
                        $value->delete();
                    }
                }

                $transaction->commit();
                return $this->redirect(['/index.php/tasks/index']);

            } catch (Exception $e) {
                $transaction->rollBack();
                throw $e;

                return $this->redirect(['/index.php/tasks/index']);

            }
        }
    }

    public function actionArxiv()
    {
        $sql = "
            SELECT 
                   t.id as task_id, 
                   t.status as status, 
                   t.created_date as create_date, 
                   ts.end_date as end_date, 
                   tm.caption AS caption 
            FROM tasks AS t
            INNER JOIN task_materials AS tm ON t.id = tm.task_id
            INNER JOIN task_status AS ts ON t.id = ts.task_id
            WHERE t.status = 4";
        $command = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('arxiv', [
            'command' => $command
        ]);
    }

    /**
     * Displays a single Tasks model.
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
     * Creates a new Tasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tasks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tasks model.
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
     * Deletes an existing Tasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $sql = "DELETE FROM task_fine WHERE task_id = ".$id;
            $command = Yii::$app->db->createCommand($sql)->execute();

            $sql = "DELETE FROM task_fine_deadline WHERE task_id = ".$id;
            $command = Yii::$app->db->createCommand($sql)->execute();


            TaskUser::deleteAll(['task_id' => $id]);
            TaskUserMaterials::deleteAll(['task_id' => $id]);
            TaskMaterials::deleteAll(['task_id' => $id]);
            TaskStatus::deleteAll(['task_id' => $id]);

            $this->findModel($id)->delete();

            $transaction->commit();
            return $this->redirect(['index']);

        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;

            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
