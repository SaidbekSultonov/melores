<?php

namespace app\controllers;

use Yii;
use app\models\CompanyFiles;
use app\models\Company;
use app\models\CompanyDistrict;
use app\models\CompanyServicesType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * CompanyFilesController implements the CRUD actions for CompanyFiles model.
 */
class CompanyFilesController extends Controller
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
     * Lists all CompanyFiles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CompanyFiles::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CompanyFiles model.
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
     * Creates a new CompanyFiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new CompanyFiles();
        $id = intval($id);
        $company = Company::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            $model->company_id = $id;

            if ($model->type == "photo"){
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->file_id = rand(1,100).strtotime(date('Y-m-d H:i:s')).'.'.$model->imageFile->extension;
                }
            }

            $find = CompanyServicesType::find()
                ->where([
                    'services_type_id' => $model->services_type_id,
                    'company_id' => $id
                ])
                ->one();

            $find_district = CompanyDistrict::find()
                ->where([
                    'company_id' => $id,
                    'district_id' => $model->district_id
                ])
                ->one();

            if (!isset($find_district)) {
                $new_company_district = new CompanyDistrict();
                $new_company_district->company_id = $id;
                $new_company_district->district_id = $model->district_id;
                $new_company_district->save();

            }

            if (!isset($find)) {
                $new_info = new CompanyServicesType();
                $new_info->company_id = $id;
                $new_info->services_type_id = $model->services_type_id;
                $new_info->save();
            }
            if ($model->save() and $model->type == "photo") {
                $img = $model->file_id;
                if ($model->imageFile) {
                    $model->imageFile->saveAs('web/uploads/'.$model->file_id);

                    //  ---- bot
                    function bot($method, $data = []) {
                        $url = 'https://api.telegram.org/bot1931399705:AAGipbWOhynFHLeHFyfgEFFBGc6K3TcPrL8/'.$method;
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


//                            $model->file_id = $file_id;
//                            $model->save(false);
//                            unlink(dirname(__FILE__).'/../web/uploads/'.$img);

                            if (isset($file_id) and !empty($file_id)){
                                $model->file_id = $file_id;
                                $model->save(false);
                                unlink(dirname(__FILE__).'/../web/uploads/'.$img);
                            } else {
                                unlink(dirname(__FILE__).'/../web/uploads/'.$img);
                                $deletePhoto = CompanyFiles::find()->where(['id' => $model->id])->one();
                                $deletePhoto->delete();
                                return $this->render('create', [
                                    'model' => $model,
                                    'company' => $company,
                                ]);
                            }

                            break;
                        case "video":
                            $res = bot('sendVideo',[
                                'chat_id' => -1001228494720,
                                'video' => 'https://app.original-mebel.uz/web/uploads/'.$model->file_id
                            ]);

                            if (isset($res->result->video)) {
                                $file_id = $res->result->video->file_id;
                            }

                            $model->file_id = $file_id;
                            $model->save(false);
                            unlink(dirname(__FILE__).'/../web/uploads/'.$img);

                            break;
                    }
                }
            }
            return $this->redirect(['/index.php/company/view', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
            'company' => $company,
        ]);
    }

    /**
     * Updates an existing CompanyFiles model.
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
     * Deletes an existing CompanyFiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id,$com)
    {

//        $this->findModel($id)->delete();

        $countSelect = CompanyFiles::find()->where(['id' => $id])->one();
        if (isset($countSelect) and !empty($countSelect)){
            $deleteType = CompanyFiles::find()->where(['company_id' => $countSelect->company_id, 'services_type_id' => $countSelect->services_type_id])->all();
            if (count($deleteType) == 1){
                $selectTypeCompany = CompanyServicesType::find()->where(['company_id' => $countSelect->company_id, 'services_type_id' => $countSelect->services_type_id])->one();
                $selectTypeCompany->delete();
            }
        }

        $countSelect->delete();

        return $this->redirect(['/index.php/company/view','id' => $com]);
    }

    /**
     * Finds the CompanyFiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyFiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyFiles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
