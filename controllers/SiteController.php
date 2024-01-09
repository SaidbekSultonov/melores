<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SectionOrders;
use app\models\OrderResponsibles;

class SiteController extends Controller
{
   
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect('index.php/site/login');
        } else if(Yii::$app->user->identity->status == 1){

            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
            $role = '';
            if(is_array($roles) && !empty($roles)) {
                $role = array_shift($roles)->name;
            }


            if($role == 'Admin')
            {
                $this->redirect('index.php/users/index');
            }
            else if ($role == 'Manager') {
                $this->redirect('index.php/users/index');
            }
            else if ($role == 'HR') {
                $this->redirect('index.php/users/index');
            }
            else if ($role == 'Sales') {
                $this->redirect('index.php/orders/view');
            }
            else if ($role == 'Marketer') {
                $this->redirect('index.php/services/index');
            }
            else{
                pre('222');
                Yii::$app->user->logout();
                $this->redirect('/index.php/site/login');
            }

            
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'main-login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        // $model = SectionOrders::find()->where(['section_id' => 36,'exit_date' => NULL])->all();
        // foreach ($model as $key => $value) {
        //     $res = new OrderResponsibles();
        //     $res->user_id = 261;
        //     $res->section_id = 36;
        //     $res->order_id = $value->order_id;
        //     $res->save();
        // }
        // pre(count($model));
        return $this->render('about');
    }
}
