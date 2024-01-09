<?php

namespace app\controllers;

use Yii;
use app\models\Video;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
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
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
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
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video();

        if ($model->load(Yii::$app->request->post())) {
            function bot($method, $data = []) {
                $url = 'https://api.telegram.org/bot1659367153:AAG9gN37fDiIbj9zvD5ZZzpfKG-p_vnX6Uk/'.$method;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $res = curl_exec($ch);

                if(curl_error($ch)){
                    var_dump(curl_error($ch));
                } else {
                    return json_decode($res);
                }
            }
            $model->status = 1;
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $model->file_id = rand(1,100).strtotime(date('Y-m-d H:i:s')).'.'.$model->imageFile->extension;
            }
            if ($model->save()) {
                $img = $model->file_id;
                if ($model->imageFile) {
                    $model->imageFile->saveAs('web/uploads/'.$model->file_id);
                    switch($model->type){
                        case "photo":
                            $res = bot('sendPhoto',[
                                'chat_id' => -1001228494720,
                                'photo' => 'https://app.original-mebel.uz/web/uploads/'.$model->file_id
                            ]);

                            if(isset($res->result->photo[2])) {
                                $file_id = $res->result->photo[2]->file_id;
                            } else if (isset($res->result->photo[1])) {
                                $file_id = $res->result->photo[1]->file_id;
                            } else {
                                $file_id = $res->result->photo[0]->file_id;
                            }

                            $selectVideo = Video::find()->where(['file_id' => $model->file_id])->one();
                            $selectVideo->file_id = $file_id;
                            if($selectVideo->save(false)){
                                //                          START ADD EVENT
                                $user_id = Yii::$app->user->id;

                                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                                $userId = $selectUsers->id;

                                eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Rasim qo'shildi", 'Video');

                                //
                            }
                            unlink(dirname(__FILE__).'/../web/uploads/'.$img);

                            break;
                        case "video":
                            $res = bot('sendVideo',[
                                'chat_id' => -1001228494720,
                                'video' => 'https://app.original-mebel.uz/web/uploads/'.$model->file_id
                            ]);
                            if (isset($res->result->video)) {
                                $file_id = $res->result->video->file_id;
                            }

                            $selectVideo = Video::find()->where(['file_id' => $model->file_id])->one();
                            $selectVideo->file_id = $file_id;
                            if($selectVideo->save(false)){
                                //                          START ADD EVENT
                                $user_id = Yii::$app->user->id;

                                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                                $userId = $selectUsers->id;

                                eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Video qo'shildi", 'Video');

                                //
                            }
                            unlink(dirname(__FILE__).'/../web/uploads/'.$img);
                            break;
                        case "document":
                            $res = bot('sendDocument',[
                                'chat_id' => -1001228494720,
                                'document' => 'https://app.original-mebel.uz/web/uploads/'.$model->file_id
                            ]);

                            if (isset($res->result->document)) {
                                $file_id = $res->result->document->file_id;
                            }

                            $selectVideo = Video::find()->where(['file_id' => $model->file_id])->one();
                            $selectVideo->file_id = $file_id;
                            if($selectVideo->save(false)){
                                //                          START ADD EVENT
                                $user_id = Yii::$app->user->id;

                                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                                $userId = $selectUsers->id;

                                eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Dakument qo'shildi", 'Video');

                                //
                            }
                            unlink(dirname(__FILE__).'/../web/uploads/'.$img);
                            break;
                        case "text":
                            $model->file_id = $model->caption;
                            if($model->save(false)){
                                //                          START ADD EVENT
                                $user_id = Yii::$app->user->id;

                                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                                $userId = $selectUsers->id;

                                eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Tekst qo'shildi", 'Video');

                                //
                            }

                            bot('sendMessage',[
                                'chat_id' => -1001228494720,
                                'text' => $model->file_id,
                            ]);

                            break;
                    }
                }
            } else {
                pre($model->errors);
            }

            $selectVideo = Video::find()->where(['status' => 1])->all();
            foreach ($selectVideo as $key => $value) {
                $file_id =  $value->file_id;
                $caption =  $value->caption;
                $type =  $value->type;
                switch($type){
                    case "photo":
                        bot('sendPhoto',[
                            'chat_id' => 398187848,
                            'photo' => $file_id,
                        ]);
                        break;
                    case "video":
                        bot('sendVideo',[
                            'chat_id' => 398187848,
                            'video' => $file_id,
                        ]);
                        break;
                    case "document":
                        bot('sendDocument',[
                            'chat_id' => 398187848,
                            'document' => $file_id,
                        ]);
                        break;
                    case "text":
                        bot('sendMessage',[
                            'chat_id' => 398187848,
                            'text' => $caption,
                        ]);
                        break;
                }
            }

            return $this->redirect(['/index.php/video/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            function bot($method, $data = []) {
                $url = 'https://api.telegram.org/bot1659367153:AAG9gN37fDiIbj9zvD5ZZzpfKG-p_vnX6Uk/'.$method;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $res = curl_exec($ch);

                if(curl_error($ch)){
                    var_dump(curl_error($ch));
                } else {
                    return json_decode($res);
                }
            }

            switch($model->type){
                case "photo":
                    $imageName = strtotime(date("Y-m-d H:i:s")).rand(1000, 9999);
                    $model->file_id = UploadedFile::getInstance($model, 'file_id');

                    if ($model->file_id->extension == "jpg" || $model->file_id->extension == "png" || $model->file_id->extension == "jpeg"){
                        $model->file_id->saveAs('web/img/'.$imageName.".".$model->file_id->extension);
                        $model->file_id = $imageName.".".$model->file_id->extension;
                        $model->save(false);

                        $res = bot('sendPhoto',[
                            'chat_id' => -1001228494720,
                            'photo' => 'https://app.original-mebel.uz/web/img/'.$model->file_id,
                        ]);

                        if(isset($res->result->photo[2])) {
                            $file_id = $res->result->photo[2]->file_id;
                        } else if (isset($res->result->photo[1])) {
                            $file_id = $res->result->photo[1]->file_id;
                        } else {
                            $file_id = $res->result->photo[0]->file_id;
                        }

                        $selectVideo = Video::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        if($selectVideo->save(false)){
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Rasim o'zgartirildi", 'Video');

                            //
                        }
                        unlink(dirname(__FILE__).'/../web/img/'.$model->file_id);
                    } else {
                        Yii::$app->session->setFlash('success', "Rasim yuklang!");
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }


                    break;
                case "video":
                    $imageName = strtotime(date("Y-m-d H:i:s")).rand(1000, 9999);
                    $model->file_id = UploadedFile::getInstance($model, 'file_id');

                    if ($model->file_id->extension == "mp4"){
                        $model->file_id->saveAs('web/img/'.$imageName.".".$model->file_id->extension);
                        $model->file_id = $imageName.".".$model->file_id->extension;
                        $model->save(false);


                        $res = bot('sendVideo',[
                            'chat_id' => -1001228494720,
                            'video' => 'https://app.original-mebel.uz/web/img/'.$model->file_id,
                        ]);
                        if (isset($res->result->video)) {
                            $file_id = $res->result->video->file_id;
                        }

                        $selectVideo = Video::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        if($selectVideo->save(false)){
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Video o'zgartirildi", 'Video');

                            //
                        }
                        unlink(dirname(__FILE__).'/../web/img/'.$model->file_id);
                    } else {
                        Yii::$app->session->setFlash('success', "Video yuklang!");
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }

                    break;
                case "document":
                    $imageName = strtotime(date("Y-m-d H:i:s")).rand(1000, 9999);
                    $model->file_id = UploadedFile::getInstance($model, 'file_id');

                    if ($model->file_id->extension == "gif" || $model->file_id->extension == "pdf" || $model->file_id->extension == "zip"){

                        $model->file_id->saveAs('web/img/'.$imageName.".".$model->file_id->extension);
                        $model->file_id = $imageName.".".$model->file_id->extension;
                        $model->save(false);

                        $res = bot('sendDocument',[
                            'chat_id' => -1001228494720,
                            'document' => 'https://app.original-mebel.uz/web/img/'.$model->file_id
                        ]);

                        if (isset($res->result->document)) {
                            $file_id = $res->result->document->file_id;
                        }

                        $selectVideo = Video::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        if($selectVideo->save(false)){
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Dakument o'zgartirildi", 'Video');

                            //
                        }
                        unlink(dirname(__FILE__).'/../web/img/'.$model->file_id);
                    } else {
                        Yii::$app->session->setFlash('success', "Dakument yuklang!");
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }

                    break;
                case "text":
                    if (!empty($model->caption)) {
                        $imageName = strtotime(date("Y-m-d H:i:s")).rand(1000, 9999);
                        $model->file_id = $model->caption;
                        $model->save(false);

                        $selectVideo = Video::find()->where(['file_id' => $model->caption])->one();
                        $selectVideo->file_id = $model->caption;
                        if($selectVideo->save(false)){
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $model->file_id, "Tekst o'zgartirildi", 'Video');

                            //
                        }

                        bot('sendMessage',[
                            'chat_id' => -1001228494720,
                            'text' => $model->file_id,
                        ]);

                    } else {
                        Yii::$app->session->setFlash('success', "Tekst kriting!");
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                    break;
            }
            return $this->redirect(['/index.php/video/view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Video model.
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

        eventUser($userId, date('Y-m-d H:i:s'), $this->findModel($id)->file_id, "Media o'chirildi", 'Video');

        //
        $this->findModel($id)->delete();

        return $this->redirect(['/index.php/video/index']);
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
