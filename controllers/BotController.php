<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\BotUsers;
use app\models\Sections;
use app\models\UsersSection;
use app\models\Bot;
use yii\data\ActiveDataProvider;


class BotController extends \yii\web\Controller
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
    	$sql  = "SELECT 
        us.second_name,
        us.phone_number,
        bu.user_id,
        us.link,
        us.status
        FROM users AS us
        INNER JOIN bot_users AS bu ON bu.user_id = us.id
        WHERE bu.bot_id = 1
        GROUP BY 
        us.second_name,
        us.phone_number,
        bu.user_id,
        us.link,
        us.status";

		$model = Yii::$app->db->createCommand($sql)->queryAll();
        $bot = Bot::findOne(1);


        return $this->render('index',[
        	'model' => $model,
            'bot' => $bot,
        ]);
    }

    public function actionOpenModalOtkBot()
    {

    	if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            
			$model = Users::find()->all();
            $sections = Sections::find()
            ->orderBy([
                'order_column' => SORT_ASC
            ])->all();

			return [
                    'header' => '<h4>Zayavka botiga foydalanuvchi qo`shish</h4>',
                    'status' => 'success',
                    'content' => $this->renderAjax('otk_modal.php',[
                        'model' => $model,
                        'sections' => $sections,
                    ]),
                ];

        }
    }

    public function actionSections()
    {

    	if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $sections = Sections::find()
            ->orderBy([
                'order_column' => SORT_ASC
            ])->all();

			return [
                    'status' => 'success',
                    'content' => $this->renderAjax('sections.php',[
                        'sections' => $sections,
                    ]),
                ];

        }
    }

    public function actionSaveOtkUser()
    {

    	if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (empty($_GET['id'])) {
                return ['status' => 'failure'];
            }
            if (empty($_GET['role'])) {
                return ['status' => 'failure_role'];
            }
            if (empty($_GET['section'])) {
                return ['status' => 'failure_section'];
            }

            $id = intval($_GET['id']);
            $role = intval($_GET['role']);
            $section = intval($_GET['section']);

            $users_section = UsersSection::find()
            ->where([
                'user_id' => $id,
                'role' => $role,
                'section_id' => $section
            ])
            ->one();

            if (isset($users_section) && !empty($users_section)) {
                return ['status' => 'failure_exist'];
            }


            $user = Users::findOne($id);
            
            $status = 0;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (isset($user)) {
                    $model = new BotUsers();
                    $model->user_id = $id;
                    $model->bot_id = 1;
                    if($model->save()){
                        $bot_users = new UsersSection();
                        $bot_users->section_id = $section;
                        $bot_users->user_id = $id;
                        $bot_users->bot_users_id = $model->id;
                        $bot_users->role = $role;
                        if($bot_users->save()){
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            $selectSecondName = Users::find()->where(['id' => $bot_users->user_id])->one();
                            $secondName = $selectSecondName->second_name;

                            eventUser($userId, date('Y-m-d H:i:s'), $secondName, "Kroy 3d Bot ga foydalanuvchi qo'shildi", 'Kroy 3d Bot');

                            //
                        }

                        $status = 1;
                        
                    }
                
                }

                if ($status) {
                    $transaction->commit();
                    return ['status' => 'success'];
                }
                

            } catch (Exception $e) {
                $transaction->rollBack();
                throw $e;
                return ['status' => 'failure'];
            }
        }
    }

    public function actionDeleteBotOne($id)
    {
    	$id = intval($id);
    	$model = UsersSection::find()
    	->where([
    		'user_id' => $id
    	])
    	->all();

    	if (!empty($model)) {
    		foreach ($model as $key => $value) {
                //                          START ADD EVENT
                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                $selectSecondName = Users::find()->where(['id' => $value->user_id])->one();
                $secondName = $selectSecondName->second_name;

                eventUser($userId, date('Y-m-d H:i:s'), $secondName, "Kroy 3d Bot dan foydalanuvchi o'chirildi", 'Kroy 3d Bot');

                //
                $value->delete();
    		}
    	}

    	$bot_user = BotUsers::find()
        ->where([
            'user_id' => $id
        ])
        ->all();

        if (!empty($bot_user)) {
            foreach ($bot_user as $key => $value) {
                $value->delete();
            }
        }


    	return $this->redirect('/index.php/bot/index');

    }

    public function actionDeleteMasterBot($id)
    {
      $id = intval($id);

      $bot_user = BotUsers::find()
      ->where([
        'user_id' => $id,
        'bot_id' => 2
      ])
      ->one();

      if (isset($bot_user)) {
          $bot_user->delete();
      }

      return $this->redirect('/index.php/bot/master');

    }

    public function actionBots()
    {
    	
        $dataProvider = new ActiveDataProvider([
            'query' => Bot::find(),
        ]);

        return $this->render('bots', [
            'dataProvider' => $dataProvider,
        ]);


    }

    public function actionMaster()
    {

    	$sql  = "SELECT bu.user_id AS bot_user_id, * FROM bot_users AS bu
			INNER JOIN users AS us ON bu.user_id = us.id
      INNER JOIN bot AS b ON b.id = bu.bot_id
			WHERE b.id = 2";


		  $model = Yii::$app->db->createCommand($sql)->queryAll();



      return $this->render('master',[
      	'model' => $model
      ]);
    }

    public function actionOpenModalMasterBot()
    {

    	if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            
			$model = Users::find()->all();

			return [
                    'header' => '<h4>Usta botiga foydalanuvchi qo`shish</h4>',
                    'status' => 'success',
                    'content' => $this->renderAjax('master_modal.php',[
                        'model' => $model,
                    ]),
                ];

        }
    }

    public function actionSaveMasterUser()
    {

    	if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $id = intval($_GET['id']);
            $user = Users::findOne($id);

            $find = BotUsers::find()
            ->where([
            	'user_id' => $id,
            	'bot_id' => 2
            ])
            ->one();

            if (!empty($find)) {
            	return ['status' => 'failure_user'];
            }


            if (isset($user)) {
            	$bot = Bot::findOne(1);
            	if (isset($bot)) {
            		$user->link = $bot->link.$user->phone_number;
            		$user->save();
            	}
            	$model = new BotUsers();
            	$model->user_id = $id;
            	$model->bot_id = 2;
            	if($model->save()){
            		if(isset($_GET['sections'])){
            			
            			foreach ($_GET['sections'] as $key => $value) 
            			{
            				$bot_users = new UsersSection();
            				$bot_users->section_id = $value;
            				$bot_users->user_id = $id;
            				$bot_users->bot_users_id = $model->id;
            				if($bot_users->save()){
                                //                          START ADD EVENT
                                $user_id = Yii::$app->user->id;

                                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                                $userId = $selectUsers->id;

                                $selectSecondName = Users::find()->where(['id' => $bot_users->user_id])->one();
                                $secondName = $selectSecondName->second_name;

                                eventUser($userId, date('Y-m-d H:i:s'), $secondName, "Usta bot ga foydalanuvchi qo'shildi", 'Usta Bot');

                                //
                            }

            			}
            		}

            		return ['status' => 'success'];
            	}
            }
            else{
            	return ['status' => 'failure'];
            }


			return [
                    'status' => 'success',
                    'content' => $this->renderAjax('sections.php',[
                        'sections' => $sections,
                    ]),
            ];

        }
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionUpdate($id)
	    {
	        $model = $this->findModel($id);

	        if ($model->load(Yii::$app->request->post())) {
	            if ($model->save()) {
	                return $this->redirect(['/index.php/bot/view', 'id' => $model->id]);    
	            }
	            
	        }

	        return $this->render('update', [
	            'model' => $model,
	        ]);
	    }

}
