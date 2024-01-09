<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use app\models\Category;
use app\models\BranchCategories;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        
    }
    
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;


            if(!isset(Yii::$app->request->post()['Category']['branchs'])){
                Yii::$app->session->setFlash('danger', "Filial tanlamadingiz!.");

                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            else{
                if ($model->save()) {
                    //                          START ADD EVENT
                    $user_id = Yii::$app->user->id;

                    $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                    $userId = $selectUsers->id;

                    eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Kategoriya qo'shildi", 'Kategoriya');

                    //
                    if (!empty(Yii::$app->request->post()['Category']['branchs'])) {
                        $array = Yii::$app->request->post()['Category']['branchs'];
                        foreach ($array as $key => $value) {
                            $branch_categories = new BranchCategories();
                            $branch_categories->category_id = $model->id;
                            $branch_categories->branch_id = $value;
                            $branch_categories->save();
                        }    
                    }

                    return $this->redirect(['/index.php/category/view', 'id' => $model->id]);
                }
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(!isset(Yii::$app->request->post()['Category']['branchs'])){
                Yii::$app->session->setFlash('danger', "Filial tanlamadingiz!.");

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            else{
                if ($model->save()) {
                    //                          START ADD EVENT
                    $user_id = Yii::$app->user->id;

                    $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                    $userId = $selectUsers->id;

                    eventUser($userId, date('Y-m-d H:i:s'), $model->title, "Kategoriya o'zgartirildi", 'Kategoriya');

                    //
                    if (!empty(Yii::$app->request->post()['Category']['branchs'])) {
                        $array = Yii::$app->request->post()['Category']['branchs'];
                        $find = BranchCategories::find()
                        ->where([
                            'category_id' => $model->id
                        ])
                        ->all();

                        if (!empty($find)) {
                            foreach ($find as $key => $value) {
                                $value->delete();
                            }
                        }
                        foreach ($array as $key => $value) {
                            $branch_categories = new BranchCategories();
                            $branch_categories->category_id = $model->id;
                            $branch_categories->branch_id = $value;
                            $branch_categories->save();
                        }    
                    }

                    return $this->redirect(['/index.php/category/view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //                          START ADD EVENT
        $user_id = Yii::$app->user->id;

        $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
        $userId = $selectUsers->id;

        eventUser($userId, date('Y-m-d H:i:s'), $this->findModel($id)->title, "Kategoriya o'chirildi", 'Kategoriya');

        //
        $this->findModel($id)->delete();

        return $this->redirect(['/index.php/category/index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
