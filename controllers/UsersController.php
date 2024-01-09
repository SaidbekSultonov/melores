<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\User;
use app\models\Branch;
use app\models\UsersBranch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find()->orderBy(['type' => SORT_ASC]),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
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
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $branchs = Branch::find()->all();
        if ($model->load(Yii::$app->request->post())) {



            $model->status = 0;

            if(!isset($_POST['Users']['user_branch'])){
                Yii::$app->session->setFlash('danger', "Fililal tanlamadingiz.");
                return $this->render('create', [
                    'model' => $model,
                    'branchs' => $branchs,
                ]);
            }

            $find = Users::find()
            ->where([
                'phone_number' => $model->phone_number
            ])
            ->one();


            if (empty($find)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {

                    $user = new User();
                    $user->username = $model->phone_number;
                    $user->password = sha1(123);
                    $user->status = 1;

                    if ($user->save()) {
                       
                        $model->user_id = $user->id;
                        $model->link = 'https://t.me/Orginal_mebel_office_bot?start='.$model->phone_number;
                        // $model->type = 3;
                        if($model->save()){
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $model->second_name, "User qo'shildi", 'Foydalanuvchilar');

                            //
                        }
                        $auth = Yii::$app->authManager;

                        $user_role = $model->type;

                        if (($user_role) == 1) {
                            $role = "Admin";
                        } 
                        elseif (($user_role) == 2) {
                            $role = "Manager";   
                        } 
                        elseif (($user_role) == 3) {
                            $role = "OTK";
                        } 
                        elseif (($user_role) == 4) {
                            $role = "Sales";
                        }
                        elseif (($user_role) == 5) {
                            $role = "Boss";
                        }
                        elseif (($user_role) == 6) {
                            $role = "Kroy";
                        }
                        elseif (($user_role) == 7) {
                            $role = "Worker";
                        } 
                        elseif (($user_role) == 8) {
                            $role = "HR";
                        }
                        elseif (($user_role) == 9) {
                            $role = "Marketer";
                        }
                        elseif (($user_role) == 10) {
                            $role = "Brigadir";
                        }

                        $authorRole = $auth->getRole($role);
                        $auth->assign($authorRole, $user->id);

                        if (!empty($_POST['Users']['user_branch'])) {
                            foreach ($_POST['Users']['user_branch'] as $key => $value) {
                                $user_branch = new UsersBranch();
                                $user_branch->user_id = $model->id;
                                $user_branch->branch_id = $value;
                                $user_branch->save();


                            }
                        }

                        $transaction->commit();
                        return $this->redirect(['/index.php/users/view', 'id' => $model->id]);
                    }
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;

                    Yii::$app->session->setFlash('danger', "Ma`lumotlar sqlashda xatolik !");
                    return $this->render('create', [
                        'model' => $model,
                        'branchs' => $branchs,
                    ]);
                }
            }
            else{
                Yii::$app->session->setFlash('danger', "Bunday raqamli foydalanuvchi platformada mavjud.");
                return $this->render('create', [
                    'model' => $model,
                    'branchs' => $branchs,
                ]);
            }

            
        }

        return $this->render('create', [
            'model' => $model,
            'branchs' => $branchs,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $branchs = Branch::find()->all();

        if ($model->load(Yii::$app->request->post())) {

            if(!isset($_POST['Users']['user_branch'])){
                Yii::$app->session->setFlash('danger', "Fililal tanlamadingiz.");
                return $this->render('update', [
                    'model' => $model,
                    'branchs' => $branchs,
                ]);
            }

            if ($model->save()) {
                if (!empty($_POST['Users']['user_branch'])) {
                    $del_users = UsersBranch::find()
                    ->where([
                        'user_id' => $id
                    ])
                    ->all();

                    if (!empty($del_users)) {
                        foreach ($del_users as $key => $value) {
                            $value->delete();
                        }
                    }

                    foreach ($_POST['Users']['user_branch'] as $key => $value) {
                        $user_branch = new UsersBranch();
                        $user_branch->user_id = $model->id;
                        $user_branch->branch_id = $value;
                        $user_branch->save();


                    }
                }


                $auth = Yii::$app->authManager;
                $user_role = $model->type;

                if (($user_role) == 1) {
                    $role = "Admin";
                } 
                elseif (($user_role) == 2) {
                    $role = "Manager";   
                } 
                elseif (($user_role) == 3) {
                    $role = "OTK";
                } 
                elseif (($user_role) == 4) {
                    $role = "Sales";
                }
                elseif (($user_role) == 5) {
                    $role = "Boss";
                }
                elseif (($user_role) == 6) {
                    $role = "Kroy";
                }
                elseif (($user_role) == 7) {
                    $role = "Worker";
                } 
                elseif (($user_role) == 8) {
                    $role = "HR";
                }
                elseif (($user_role) == 9) {
                    $role = "Marketer";
                }
                elseif (($user_role) == 10) {
                    $role = "Brigadir";
                }
                else {
                    $role = "Brigadir";
                }

                $authorRole = $auth->getRole($role);
                
                return $this->redirect(['/index.php/users/view', 'id' => $model->id]);
            }

            
        }

        return $this->render('update', [
            'model' => $model,
            'branchs' => $branchs,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = User::findOne($this->findModel($id)->user_id);
        
        if(isset($user)){
            if ($user->delete()) {
                $this->findModel($id)->delete(); 
                Yii::$app->session->setFlash('success', "Foydalanuvchi muvaffaqiyatli o`chirildi.");   
            }    
        }
        else{
            Yii::$app->session->setFlash('danger', "Foydalanuvchini  o`chirishda xatolik!."); 
        }
        
        
        

        return $this->redirect(['index.php/users/index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
