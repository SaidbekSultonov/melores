<?php

namespace app\controllers;

use app\models\Users;
use app\models\Video;
use Yii;
use app\models\OrginalKatalog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * OrginalKatalogController implements the CRUD actions for OrginalKatalog model.
 */
class OrginalKatalogController extends Controller
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
     * Lists all OrginalKatalog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OrginalKatalog::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrginalKatalog model.
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
     * Creates a new OrginalKatalog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrginalKatalog();

        if ($model->load(Yii::$app->request->post())) {
            $model->caption = base64_encode($model->caption);
            function bot($method, $data = []) {
                $url = 'https://api.telegram.org/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$method;
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
                            'photo' => 'https://app.original-mebel.uz/web/img/'.$model->file_id
                        ]);

                        if(isset($res->result->photo[2])) {
                            $file_id = $res->result->photo[2]->file_id;
                        } else if (isset($res->result->photo[1])) {
                            $file_id = $res->result->photo[1]->file_id;
                        } else {
                            $file_id = $res->result->photo[0]->file_id;
                        }

                        $selectVideo = OrginalKatalog::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        $selectVideo->save(false);
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

                        $selectVideo = OrginalKatalog::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        $selectVideo->save(false);
                        unlink(dirname(__FILE__).'/../web/img/'.$model->file_id);
                    } else {
                        Yii::$app->session->setFlash('success', "Video yuklang!");
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

                        $selectVideo = OrginalKatalog::find()->where(['file_id' => $model->caption])->one();
                        $selectVideo->file_id = $model->caption;
                        $selectVideo->save(false);

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

            return $this->redirect(['/index.php/orginal-katalog/view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrginalKatalog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->caption = base64_encode($model->caption);
            function bot($method, $data = []) {
                $url = 'https://api.telegram.org/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$method;
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

                        $selectVideo = OrginalKatalog::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        $selectVideo->save(false);

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

                        $selectVideo = OrginalKatalog::find()->where(['file_id' => $model->file_id])->one();
                        $selectVideo->file_id = $file_id;
                        $selectVideo->save(false);

                        unlink(dirname(__FILE__).'/../web/img/'.$model->file_id);
                    } else {
                        Yii::$app->session->setFlash('success', "Video yuklang!");
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

                        $selectVideo = OrginalKatalog::find()->where(['file_id' => $model->caption])->one();
                        $selectVideo->file_id = $model->caption;
                        $selectVideo->save(false);

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
            return $this->redirect(['/index.php/orginal-katalog/view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrginalKatalog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/index.php/orginal-katalog/index']);
    }

    /**
     * Finds the OrginalKatalog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrginalKatalog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrginalKatalog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
