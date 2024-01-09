<?php

namespace app\controllers;
use Yii;
use app\models\Sections;
use app\models\Orders;
use app\models\Users;
use app\models\FeedbackUser;
use app\models\FeedbackClient;
use app\models\UserPenalties;
use yii\data\Pagination;


class ReportController extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        
    }
    
    public function actionIndex()
    {
        $sections = Sections::find()
        ->where([
            '!=', 'id', 1
        ])
        ->orderBy(['id' => SORT_ASC])
        ->orderBy(['order_column' => SORT_ASC])
        ->all();
        $orders = Orders::find()
        ->where([
            'status' => 1,
            'pause' => 0
        ])
        ->orderBy(['id' => SORT_DESC])
        ->all();
       

        return $this->render('index',[
        	'sections' => $sections,
        	'orders' => $orders,
        ]);
    }

    // feedback_user
    public function actionFeedback_user()
    {
        $orders = Orders::find()
        ->orderBy(['id' => SORT_DESC])
        ->all();
        $feedback_user = FeedbackUser::find()->orderBy(['id' => SORT_ASC])->all();

        return $this->render('feedback_user',[
        	'orders' => $orders,
        	'feedback_user' => $feedback_user,
        ]);
    }

    // feedback_client
    public function actionFeedback_client()
    {
        $orders = Orders::find();
        $pages = new Pagination(['totalCount' => $orders->count(),'pageSize'=> 20]);
        $orders = $orders->offset($pages->offset)
        ->orderBy(['id' => SORT_DESC])
        ->limit($pages->limit)
        ->all();
        $feedback_client = FeedbackClient::find()->orderBy(['id' => SORT_ASC])->all();

        return $this->render('feedback_client',[
        	'orders' => $orders,
        	'feedback_client' => $feedback_client,
            'pages' => $pages,
        ]);
    }

    public function actionUsers()
    {
        $users = Users::find()->where(['status' => 1])->orderBy(['type' => SORT_ASC])->all();

        return $this->render('users',[
            'users' => $users
        ]);
    }

    public function actionView($id)
    {
        $id = intval($id);
        $user = Users::findOne($id);
        $user_penalties = UserPenalties::find()->where(['user_id' => $id])->orderBy(['order_id' => SORT_ASC])->all();


        return $this->render('view',[
            'user_penalties' => $user_penalties,
            'user' => $user,
        ]);
    }
}
