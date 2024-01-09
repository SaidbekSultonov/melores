<?php

namespace app\controllers;

use Yii;
use app\models\Orders;
use app\models\OrderMaterials;
use app\models\SectionTimes;
use app\models\RequiredMaterials;
use app\models\SectionOrdersControl;
use app\models\OrderStep;
use app\models\UsersSection;
use app\models\Clients;
use app\models\PausedOrders;
use app\models\Sections;
use app\models\OrderCategories;
use app\models\BranchCategories;
use app\models\Branch;
use app\models\Bot;
use app\models\SectionOrders;
use app\models\Category;
use app\models\RequiredMaterialOrder;
use app\models\OrderResponsibles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Users;
use app\models\Events;
use app\models\SectionMinimal;
use yii\data\Pagination;

function bot($method, $data = []) {
    $url = 'https://api.telegram.org/bot1594810052:AAHQEku5Q3tslozhq7uGiUpEB6oGUtzUXfs/'.$method;
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

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
    // public function behaviors()
    // {
        
    // }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect('/index.php/site/login');
        }
        $sections = Sections::find()->where(['status' => 1])->orderBy(['order_column' => SORT_ASC])->all();

        return $this->render('index', [
            'sections' => $sections,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {

        $model = Orders::find()
        ->where([
            'status' => 1
        ])
        ->all();
        
        return $this->render('view',[
            'model' => $model
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Orders model.
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
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);

                //                          START ADD EVENT

                    $user_id = Yii::$app->user->id;

                    $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                    $userId = $selectUsers->id;

                    $selectOrder = Orders::find()->where(['id' => $id])->one();
                    $orderName = $selectOrder->title;

                    eventUser($userId, date('Y-m-d H:i:s'), $orderName, 'Buyurtma o`chirildi', 'Buyurtmalar');

//                          END ADD EVENT

                // step
                $order_step = OrderStep::find()
                ->where([
                    'order_id' => $id
                ])
                ->all();

                if (!empty($order_step)) {
                    foreach ($order_step as $key => $value) {
                        $value->delete();
                    }
                }

                // material
                // $order_mat = OrderMaterials::find()
                // ->where([
                //     'order_id' => $id
                // ])
                // ->all();

                // if (!empty($order_mat)) {
                //     foreach ($order_mat as $key => $value) {
                //         $value->delete();
                //     }
                // }

                // category
                $order_cat = OrderCategories::find()
                ->where([
                    'order_id' => $id
                ])
                ->all();

                if (!empty($order_cat)) {
                    foreach ($order_cat as $key => $value) {
                        $value->delete();
                    }
                }

                // paused
                $paused_orders = PausedOrders::find()
                ->where([
                    'order_id' => $id
                ])
                ->all();

                if (!empty($paused_orders)) {
                    foreach ($paused_orders as $key => $value) {
                        $value->delete();
                    }
                }

                // required
                $required_orders = RequiredMaterialOrder::find()
                ->where([
                    'order_id' => $id
                ])
                ->all();

                if (!empty($required_orders)) {
                    foreach ($required_orders as $key => $value) {
                        $value->delete();
                    }
                }

                // section_orders
                $section_orders = SectionOrders::find()
                ->where([
                    'order_id' => $id
                ])
                ->all();

                if (!empty($section_orders)) {
                    foreach ($section_orders as $key => $value) {
                        $value->delete();
                    }
                }

                // section_order_control
                $section_order_control = SectionOrdersControl::find()
                ->where([
                    'order_id' => $id
                ])
                ->all();

                if (!empty($section_order_control)) {
                    foreach ($section_order_control as $key => $value) {
                        $value->delete();
                    }
                }

                if ($this->findModel($id)->delete()) {
                    return ['status' => 'success'];
                }
                
            }

        }

    }


    // open-modal
    public function actionOpenModal()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['order_id']) && isset($_GET['section_id'])) {
                $order_id = intval($_GET['order_id']);
                $section_id = intval($_GET['section_id']);

                $order = Orders::findOne($order_id);
                $order_control = SectionOrdersControl::find()
                ->where([
                    'order_id' => $order_id,
                    'exit_date' => NULL
                ])
                ->one();
                

                $pause = PausedOrders::find()
                ->where([
                    'order_id' => $order_id,
                    'end_date' => '9999-12-31'
                ])
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();

                if (!isset($pause) || empty($pause)) {
                    $pause = false;
                }


                return [
                    'header' => '<h3>'.$order->title.'</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('open-modal.php',[
                        'order' => $order,
                        'pause' => $pause,
                        'order_control' => $order_control,
                    ]),
                    'order_control' => (($order_control) ? $order_control->id : false),
                ];

                
            }
            else{
                return [
                    'status' => 'failure',
                    'content' => 'Ma`lumotlarda xatolik!',
                ];
            }


        }
    }


    // order-end
    public function actionOrderEnd()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['order_id']) && isset($_GET['section_id'])) {
                $order_id = intval($_GET['order_id']);
                $section_id = intval($_GET['section_id']);

                $order = Orders::findOne($order_id);
                $order->status = 0;
                $orderName = $order->title;
                $order->end_date = date("Y-m-d H:i:s");
                if($order->save()){
                    //                          START ADD EVENT

                    $user_id = Yii::$app->user->id;

                    $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                    $userId = $selectUsers->id;

                    eventUser($userId, date('Y-m-d H:i:s'), $orderName, 'Buyurtma bitdi', 'Buyurtmalar');

//                          END ADD EVENT

                    $section_orders = SectionOrders::find()
                    ->where([
                        'order_id' => $order_id
                    ])
                    ->all();

                    if (!empty($section_orders)) {
                        foreach ($section_orders as $key => $value) {
                            $value->status = 0;
                            // if ($value->exit_date == NULL) {
                                $value->exit_date = date("Y-m-d H:i:s");
                                $value->step = 1;
                            // }
                            $value->save();
                        }
                    }



                    return ['status' => 'success'];
                }
                
            }
            else{
                return [
                    'status' => 'failure',
                    'content' => 'Ma`lumotlarda xatolik!',
                ];
            }


        }
    }

    // add-order-modal
    public function actionAddOrderModal()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['section_id'])) {
                $section_id = intval($_GET['section_id']);

                $section = Sections::findOne($section_id);
                $branchs = Branch::find()->all();
                $sections = Sections::find()
                ->where(['is not','order_column',NULL])
                ->andWhere(['status' => 1])
                ->orderBy(['order_column' => SORT_ASC])
                ->all();
                $required_mat = RequiredMaterials::find()->all();
                
                return [
                    'header' => '<h3>'.$section->title.' bo`limiga buyurtma qo`shish</h3>',
                    'status' => 'success',
                    'content' => $this->renderAjax('add-order-modal.php',[
                        'section' => $section,
                        'branchs' => $branchs,
                        'sections' => $sections,
                        'section_id' => $section_id,
                        'required_mat' => $required_mat,
                    ]),
                ];

                
            }
            else{
                return [
                    'status' => 'failure',
                    'content' => 'Ma`lumotlarda xatolik!',
                ];
            }


        }
    }


    // branch
    public function actionBranch()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (isset($_GET['branch_id'])) {
                $branch_id = intval($_GET['branch_id']);

                $clients = Clients::find()
                ->where([
                    'branch_id' => $branch_id
                ])
                ->all();

                $category = BranchCategories::find()
                ->where([
                    'branch_id' => $branch_id
                ])
                ->all();


                if (empty($clients)) {
                    return ['status' => 'failure_branch'];
                }

                if (empty($category)) {
                    return ['status' => 'failure_category'];
                }
                
                $options = '';
                foreach ($clients as $key => $value) {
                    $options .= "<option value='".$value->id."'>".base64_decode($value->full_name)."</option>";
                }

                $options2 = '';
                foreach ($category as $key => $value) {
                    $options2 .= "<option value='".$value->category_id."'>".$value->category->title."</option>";
                }
                    
                return [
                    'status' => 'success',
                    'content' => $options,
                    'content2' => $options2,
                ];
                
            }

        }
    }


    // add-section
    public function actionAddSection()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $sections = Sections::find()
            ->where(['is not','order_column',NULL])
            ->orderBy(['order_column' => SORT_ASC])
            ->all();
            if ($_GET['i']) {
                $i = intval($_GET['i']);
            }
            $section_id = intval($_GET['section_id']);


            return [
                'status' => 'success',
                'count' => $i,
                'content' => $this->renderAjax('add-section.php',[
                    'sections' => $sections,
                    'i' => $i,
                    'section_id' => $section_id,
                ]),
            ];
        }
    }

    // save-order
    public function actionSaveOrder()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


            if (!empty($_GET)) {

                if (empty(preg_replace('/\s+/', '', $_GET['title']))) 
                {
                    return ['status' => 'failure_title'];    
                }

                if (empty(preg_replace('/\s+/', '', $_GET['branch'])))
                {
                    return ['status' => 'failure_branch'];    
                }

                if (empty(preg_replace('/\s+/', '', $_GET['client'])))
                {
                    return ['status' => 'failure_client'];    
                }

                if (empty($_GET['category']))
                {
                    
                    return ['status' => 'failure_category'];    
                }

                if (empty(preg_replace('/\s+/', '', $_GET['end_date'])))
                {
                    return ['status' => 'failure_end_date'];    
                }

                


                $sections = [];
                $i = 1;
                foreach ($_GET['Section'] as $key => $value) {
                    if (isset($value['sections'])) {
                        $sections['Order_'.$i] = [
                            'sections' => $value['sections'],
                            'times' => $value['times'],
                        ];
                    $i++;
                    }

                }
                if (empty($sections))
                {
                    return ['status' => 'failure_section'];    
                }



                $order_categories = $_GET['category'];




                $test = 0;
                foreach ($sections as $key => $value) {
    
                    if (empty($value['sections'])) {
                        $test++;
                    }
                    if(empty($value['times'])){
                        $test++;
                    }

                }


                if ($test > 0) {
                    return ['status' => 'failure_empty'];
                }
                else{
                    $title = htmlspecialchars($_GET['title']);
                    $deadline = date("Y-m-d H:i", strtotime(date($_GET['end_date'])));
                    $branch = intval($_GET['branch']);
                    $client = intval($_GET['client']);
                    $section_id = intval($_GET['section']);
                    $description = htmlspecialchars($_GET['description']);

                    $status = 0;
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $order = new Orders();
                        $order->title = $title;
                        $order->branch_id = $branch;
                        $order->user_id = 1;
                        $order->client_id = $client;
                        $order->created_date = date("Y-m-d H:i:s");
                        $order->dead_line = $deadline;
                        $order->status = 1;
                        $order->feedback_user = 1;
                        $order->feedback_client = 1;
                        $order->pause = 0;
                        $order->description = $description;
                        if (isset($_GET['parralel'])) {
                            $order->parralel = 1;
                        }
                        if ($order->save()) {

                        //START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $title, 'Buyurtma qo`shildi', 'Buyurtmalar');

                            //END ADD EVENT

                            if (!empty($order_categories)) {
                                foreach ($order_categories as $key => $value) {
                                    $order_cat = new OrderCategories();
                                    $order_cat->order_id = $order->id;
                                    $order_cat->category_id = $value;
                                    $order_cat->status = 1;
                                    $order_cat->save();
                                }
                            }

                            if (isset($_GET['required']) && !empty($_GET['required'])) {
                                foreach ($_GET['required'] as $key => $value) {
                                    $one_mat = RequiredMaterials::findOne($value);
                                    if (isset($one_mat)) {
                                        $required = new RequiredMaterialOrder();
                                        $required->order_id = $order->id;
                                        $required->title = $one_mat->title;
                                        $required->status = 0;
                                        $required->required_material_id = $one_mat->id;
                                        $required->save();
                                    }
                                    
                                }
                                
                            }


                            $section_orders = new SectionOrdersControl();
                            $section_orders->order_id = $order->id;
                            $section_orders->section_id = $section_id;
                            $section_orders->enter_date = date('Y-m-d H:i:s');
                            if ($section_orders->save()) {
                                $i = 1;
                                foreach ($sections as $key => $value) 
                                {
                                    $order_step = new OrderStep();
                                    $order_step->order_id = $order->id;
                                    $order_step->section_id = $value['sections'];
                                    $order_step->work_hour = $value['times'];
                                    $order_step->order_column_2 = $i;
                                    
                                    if (isset($_GET['parralel']) && $value['sections'] == 29) 
                                    {
                                        $ii = $i;
                                        $ii--;
                                        $order_step->order_column = $ii;
                                    }
                                    else{
                                       $order_step->order_column = $i; 
                                    }
                                    
                                    $order_step->status = 0;
                                    
                                    if ($order_step->save()) {
                                        $i++;
                                        $status = 0;
                                    }
                                    else{
                                        $status++;   
                                    }


                                }
                            }
                            
                        }

                        if ($status == 0) {
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
        }
    }

    // pause
    public function actionPause()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


            if ($_GET['id']) {
                $id = intval($_GET['id']);

                $status = false;
                $model = Orders::findOne($id);
                $orderName = $model->title;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (isset($model)) {
                        $model->pause = 1;
                        if ($model->save()) {

                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $orderName, 'Buyurtma pauzaga qo`yldi', 'Buyurtmalar');

                            // $find_pause = PausedOrders::find()
                            //     ->where([
                            //         'start_date' => date("Y-m-d"),
                            //         'order_id' => $id
                            //     ])
                            //     ->one();

                        //     if (isset($find_pause)) {
                        //         $find_pause->end_date = date("9999-12-31");
                        //         if($find_pause->save()){
                        //             $status = true;
                        //         }
                        //     }
                        // else{
                                $paused = new PausedOrders();
                                $paused->order_id = $id;
                                $paused->start_date = date("Y-m-d H:i:s");
                                $paused->end_date = date("9999-12-31");
                                if($paused->save()){
                                    $status = true;
                                }
                            // }
                        }
                    }

                    if ($status) {
                        $transaction->commit();
                        return ['status' => 'success'];
                    }
                }
                catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                    return ['status' => 'failure'];

                }

            }

        }
    }


    // active
    public function actionActive()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($_GET['id']) {
                $id = intval($_GET['id']);
                $pause_id = intval($_GET['pause_id']);

                $model = Orders::findOne($id);
                $orderName = $model->title;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (isset($model)) {
                        $model->pause = 0;
                        if ($model->save()) {
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $orderName, 'Buyurtma faollashtirildi', 'Buyurtmalar');

                            $paused = PausedOrders::findOne($pause_id);
                            $paused->end_date = date("Y-m-d H:i:s");

                            if($paused->save()){
                                $status = true;    
                            }
                        }
                    }

                    if ($status) {
                        $transaction->commit();
                        return ['status' => 'success'];
                    }
                } 
                catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                    return ['status' => 'failure'];
                    
                }
            }
            
        }
    }

    public function actionFindTime()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($_GET['id']) {
                $id = intval($_GET['id']);
                
                $model = SectionTimes::find()
                ->where(['section_id' => $id])
                ->andWhere([
                    '<=','start_date',date('Y-m-d')
                ])
                ->andWhere([
                    '>=','end_date',date('Y-m-d')
                ])
                ->one();

                return [
                    'status' => 'success',
                    'content' => $model->work_time
                ];

            }    
            
        }
    }


    public function actionStartOrder()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($_GET['id'] && $_GET['order_id']) {
                $id = intval($_GET['id']);
                $order_id = intval($_GET['order_id']);

                $order = Orders::findOne($order_id);
                $orderName = $order->title;

                $model = SectionOrdersControl::findOne($id);

                if (isset($model) && isset($order)) {
                    $status = 0;
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $model->exit_date = date('Y-m-d H:i:s');
                        if ($model->save()) {
                            //                          START ADD EVENT
                            $user_id = Yii::$app->user->id;

                            $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                            $userId = $selectUsers->id;

                            eventUser($userId, date('Y-m-d H:i:s'), $orderName, 'Buyurtma boshlandi', 'Buyurtmalar');

                            //                          END ADD EVENT
                            $order_step = OrderStep::find()
                            ->where([
                                'order_id' => $order_id
                            ])
                            ->orderBy(['order_column_2' => SORT_ASC])
                            ->one();

                            if ($order->parralel == 1 && $order_step->section_id == 28) {
                                if (isset($order_step) && !empty($order_step)) {
                                    $section_orders = new SectionOrders();
                                    $section_orders->order_id = $order_id;
                                    $section_orders->section_id = $order_step->section_id;
                                    $section_orders->enter_date = date("Y-m-d H:i:s");
                                    $order->start_time = date("Y-m-d H:i:s");
                                    $section_orders->status = 1;
                                    if ($section_orders->save() && $order->save()) {

                                        $all_order_step = OrderStep::find()
                                        ->where([
                                            'order_id' => $order_id
                                        ])
                                        ->orderBy([
                                            'order_column_2' => SORT_ASC
                                        ])
                                        ->all();

                                        if (!empty($all_order_step)) {
                                            $i = 1;
                                            foreach ($all_order_step as $key => $value) {

                                                if ($i == 1) {
                                                    $new_time = date("Y-m-d H:i:s", strtotime('+'.$value->work_hour.' hours'));
                                                }
                                                else{

                                                    $step = OrderStep::find()
                                                    ->where([
                                                        'order_id' => $order_id,
                                                        'order_column_2' => $i-1
                                                    ])
                                                    ->one();

                                                     

                                                    if (isset($step)) {
                                                        $new_time = date('Y-m-d H:i',strtotime('+'.$step->work_hour.' hours',strtotime($step->deadline)));
                                                    }
                                                }
                                                
                                                $value->deadline = $new_time;
                                                if ($value->save()) {
                                                    $status++;
                                                    $i++;
                                                }
                                            }
                                        }
                                    }

                                    $section_orders = new SectionOrders();
                                    $section_orders->order_id = $order_id;
                                    $section_orders->section_id = 29;
                                    $section_orders->enter_date = date("Y-m-d H:i:s");
                                    $order->start_time = date("Y-m-d H:i:s");
                                    $section_orders->status = 1;
                                    if ($section_orders->save() && $order->save()) {

                                        $all_order_step = OrderStep::find()
                                        ->where([
                                            'order_id' => $order_id
                                        ])
                                        ->orderBy([
                                            'order_column_2' => SORT_ASC
                                        ])
                                        ->all();

                                        if (!empty($all_order_step)) {
                                            $i = 1;
                                            foreach ($all_order_step as $key => $value) {

                                                if ($i == 1) {
                                                    $new_time = date("Y-m-d H:i:s", strtotime('+'.$value->work_hour.' hours'));
                                                }
                                                else{

                                                    $step = OrderStep::find()
                                                    ->where([
                                                        'order_id' => $order_id,
                                                        'order_column_2' => $i-1
                                                    ])
                                                    ->one();
                                                       

                                                    if (isset($step)) {
                                                        $new_time = date('Y-m-d H:i',strtotime('+'.$value->work_hour.' hours',strtotime($step->deadline)));
                                                    }
                                                }
                                                
                                                $value->deadline = $new_time;
                                                if ($value->save()) {
                                                    $status++;
                                                    $i++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                if (isset($order_step) && !empty($order_step)) {
                                    $section_orders = new SectionOrders();
                                    $section_orders->order_id = $order_id;
                                    $section_orders->section_id = $order_step->section_id;
                                    $section_orders->enter_date = date("Y-m-d H:i:s");
                                    $order->start_time = date("Y-m-d H:i:s");
                                    $section_orders->status = 1;
                                    if ($section_orders->save() && $order->save()) {

                                        $all_order_step = OrderStep::find()
                                        ->where([
                                            'order_id' => $order_id
                                        ])
                                        ->orderBy([
                                            'order_column_2' => SORT_ASC
                                        ])
                                        ->all();

                                        if (!empty($all_order_step)) {
                                            $i = 1;
                                            foreach ($all_order_step as $key => $value) {

                                                if ($i == 1) {
                                                    $new_time = date("Y-m-d H:i:s", strtotime('+'.$value->work_hour.' hours'));
                                                }
                                                else{

                                                    $step = OrderStep::find()
                                                    ->where([
                                                        'order_id' => $order_id,
                                                        'order_column_2' => $i-1
                                                    ])
                                                    ->one();
                                                       

                                                    if (isset($step)) {
                                                        $new_time = date('Y-m-d H:i',strtotime('+'.$value->work_hour.' hours',strtotime($step->deadline)));
                                                    }
                                                }

                                                
                                                $value->deadline = $new_time;
                                                if ($value->save()) {
                                                    $status++;
                                                    $i++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if ($status > 0) {

                            $connection = Yii::$app->getDb();
                            $command = $connection->createCommand("SELECT *, u.id AS person_id FROM section_minimal AS sm
                                LEFT JOIN users AS u ON sm.user_id = u.id
                                WHERE sm.section_id = $order_step->section_id AND u.type IN (1,2,5,6,7)");

                            
                            $result = $command->queryAll();
                            if (!empty($result)) {
                                foreach ($result as $key => $value) {
                                    if ($value['chat_id'] != NULL) {
                                        $person_id = $value['person_id'];
                                        $prvt_order_id = $order->id;


                                        $order_responsibles = new OrderResponsibles();
                                        $order_responsibles->section_id = $order_step->section_id;
                                        $order_responsibles->order_id = $prvt_order_id;
                                        $order_responsibles->user_id = $person_id;
                                        $order_responsibles->save();

                                        // $sql = "INSERT INTO order_responsibles (section_id, order_id, user_id) VALUES (".$order_step->section_id.", $prvt_order_id, $person_id)";
                                        // $insertCommand = $connection->createCommand($sql);
                                        // $insertCommand->queryAll();


                                        $command = $connection->createCommand("SELECT u.id, u.chat_id FROM leader_employees AS le
                                            LEFT JOIN users AS u ON le.employee_id = u.id
                                            WHERE le.leader_id = :id AND u.status = 1", [':id' => $person_id]);
                                        $resultIsEmployee = $command->queryAll();


                                        $command = $connection->createCommand("SELECT * FROM brigada_leader WHERE user_id = :id", [':id' => $person_id]);
                                        $resultIsBrigada = $command->queryAll();
                                        if (!empty($resultIsBrigada) && empty($resultIsEmployee)) {
                                            
                                            $personId = $person_id."_brigadir_".$order->id;
                                            $lookOrders = json_encode([
                                                'inline_keyboard' =>[
                                                    [
                                                        ['callback_data' => $personId,'text'=>"Brigadir tanlash"]
                                                    ],
                                                ]
                                            ]);
                                        } else {
                                            $lookOrders = $order->id."_lookOrders_".$order_step->section_id;
                                            $lookOrders = json_encode([
                                                'inline_keyboard' =>[
                                                    [
                                                        ['callback_data' => $lookOrders,'text'=>"Buyurtmani ko'rish"]
                                                    ],
                                                ]
                                            ]);
                                            if (!empty($resultIsEmployee)) {
                                                foreach ($resultIsEmployee as $key => $employ) {
                                                    $employeeId = $employ['id'];
                                                    $employeeChatId = $employ['chat_id'];

                                                    

                                                    $new_responsibles = new OrderResponsibles();
                                                    $new_responsibles->section_id = $order_step->section_id;
                                                    $new_responsibles->order_id = $prvt_order_id;
                                                    $new_responsibles->user_id = $employeeId;
                                                    $new_responsibles->save();


                                                    // $insert = $connection->createCommand("INSERT INTO order_responsibles (section_id, order_id, user_id) VALUES (".$order_step->section_id.", $prvt_order_id, $employeeId)");
                                                    
                                                    // $insert->queryAll();

                                                    bot('sendMessage', [
                                                        'chat_id' => $employeeChatId,
                                                        'text' => "📌<b><i>".$order->title."</i></b> nomli buyurtma sizga berildi",
                                                        'parse_mode' => 'html',
                                                        'reply_markup' => $lookOrders
                                                    ]);
                                                }
                                            }
                                        }

                                        bot('sendMessage', [
                                            'chat_id' => $value['chat_id'],
                                            'text' => "📌<b><i>".$order->title."</i></b> nomli buyurtma sizning bo`limingizga o'tdi",
                                            'parse_mode' => 'html',
                                            'reply_markup' => $lookOrders
                                        ]);
                                    }
                                }
                            }
                            
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
            
        }
    }

    // back_order
    public function actionBackOrder()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($_GET['id']) {
                $id = intval($_GET['id']);

                $order = Orders::findOne($id);
                $orderName = $order->title;

                //                          START ADD EVENT

                $user_id = Yii::$app->user->id;

                $selectUsers = Users::find()->where(['user_id' => $user_id])->one();
                $userId = $selectUsers->id;

                eventUser($userId, date('Y-m-d H:i:s'), $orderName, 'Buyurtma ortga qaytarildi', 'Buyurtmalar');

                //                          END ADD EVENT
                $model = SectionOrders::find()
                ->where([
                    'order_id' => $id,
                    'exit_date' => NULL 
                ])
                ->one();

                if (!isset($model)) {
                    $model = SectionOrders::find()
                    ->where([
                        'order_id' => $id,
                    ])
                    ->one();
                }

                $section_id = $model->section_id;

                $count = SectionOrders::find()
                ->where([
                    'order_id' => $id
                ])->count();

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("DELETE FROM order_responsibles WHERE order_id = :id AND section_id = :section_id", [':id' => $id, ':section_id' => $section_id]);
                $command->execute();

                $status = false;
                if(isset($model)){
                    if ($count == 1) {
                        $section_orders_control = SectionOrdersControl::find()
                        ->where([
                            'order_id' => $id,
                        ])
                        ->andWhere([
                            'is not', 'exit_date', NULL
                        ])
                        ->one();


                        if (isset($section_orders_control)) {
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $section_orders_control->exit_date = NULL;
                                if ($section_orders_control->save()) {
                                    if($model->delete()){
                                        $status = true;
                                    }
                                }
                                else{
                                    pre('1111');
                                }

                                if ($status) {


                                    $transaction->commit();
                                    return ['status' => 'success'];
                                }
                               
                            } catch (Exception $e) {
                                $transaction->rollBack();
                                throw $e;
                                return ['status' => 'failure_delete_one'];
                                
                            }
                        }
                        else{
                            return ['status' => 'failure_not_order_section_control'];
                        }

                    }
                    else{
                        $old_model = SectionOrders::find()
                        ->where([
                            'order_id' => $id
                        ])
                        ->andWhere([
                            '!=', 'id', $model->id
                        ])
                        ->andWhere([
                            'is not', 'exit_date', NULL
                        ])
                        ->orderBy([
                            'id' => SORT_DESC
                        ])
                        ->one();
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if (isset($old_model)) {
                                $old_model->exit_date = NULL;
                                $old_model->step = NULL;
                                if ($old_model->save()) {
                                    $order_step = OrderStep::find()
                                    ->where([
                                        'order_id' => $id,
                                        'section_id' => $old_model->section_id
                                    ])
                                    ->one();

                                    if (isset($order_step)) {
                                        $order_step->status = 0;
                                        if ($order_step->save()) {
                                            if($model->delete()){
                                                $status = true;
                                            }
                                        }
                                    }
                                }
                                else{
                                    pre('3333');    
                                }
                                
                            }
                            else{
                                return ['status' => 'failure_back'];
                            }

                            if ($status) {

                                if (isset($order_step)) {
                                    

                                    $section_minimal = SectionMinimal::find()
                                    ->where(['section_id' => $order_step->section_id])
                                    ->andWhere(['!=', 'section_id', 1])
                                    ->one();

                                    if (isset($section_minimal)) {
                                        $user_id = $section_minimal->user_id;


                                        $order_responsibles = new OrderResponsibles();
                                        $order_responsibles->section_id = $order_step->section_id;
                                        $order_responsibles->order_id = $order_step->order_id;
                                        $order_responsibles->user_id = $user_id;
                                        $order_responsibles->save();

                                        $lookOrders = $order_responsibles->order->id."_lookOrders_".$order_step->section_id;
                                        $lookOrders = json_encode([
                                            'inline_keyboard' =>[
                                                [
                                                    ['callback_data' => $lookOrders,'text'=>"Buyurtmani ko`rish"]
                                                    
                                                ],
                                            ]
                                        ]);

                                        bot('sendMessage', [
                                            'chat_id' => $order_responsibles->user->chat_id,
                                            'parse_mode' => 'html',
                                            'text' => "📌<b><i>".$order_responsibles->order->title."</i></b> nomli buyurtma sizning bo`limingizga o'tdi",
                                            'reply_markup' => $lookOrders
                                            
                                        ]);

                                    }    
                                }

                                


                                $section_users = UsersSection::find()
                                ->where([
                                    'section_id' => $order_step->section_id,
                                ])
                                ->all();
                                if (!empty($section_users)) {
                                    foreach ($section_users as $key => $value) {

                                        if (isset($value->users) && $value->users->chat_id != NULL) {

                                            $lookOrders = $order->id."_lookOrders_".$order_step->section_id;
                                            $lookOrders = json_encode([
                                                'inline_keyboard' =>[
                                                    [
                                                        ['callback_data' => $lookOrders,'text'=>"Buyurtmani ko`rish"]
                                                        
                                                    ],
                                                ]
                                            ]);

                                            


                                            // -------------------------- //

                                            // bot('sendMessage', [
                                            //     'chat_id' => $value->users->chat_id,
                                            //     'parse_mode' => 'html',
                                            //     'text' => "📌<b><i>".$order->title."</i></b> nomli buyurtma sizning bo`limingizga qaytarib yuborildi 🔄",
                                                
                                            // ]);
                                        }

                                        
                                        
                                    }
                                }



                                
                                

                                $transaction->commit();
                                return ['status' => 'success'];
                            }
                            
                        } catch (Exception $e) {
                            $transaction->rollBack();
                            throw $e;
                            return ['status' => 'failure_delete_one'];
                        }
                        
                    }
                }
                else{
                    return ['status' => 'failure_not_section_order'];
                }
                
                

            }
            else{
                return ['status' => 'failure'];
            }    
            
        }
    }


    public function actionComplete()
    {
        $model = Orders::find()
        ->where([
            'status' => 0
        ])
        ->orderBy([
            'id' => SORT_DESC
        ])
        ->all();

        $master_bot = Bot::findOne(2);
        $client_bot = Bot::findOne(3);

        return $this->render('complete',[
            'model' => $model,
            'master_bot' => $master_bot,
            'client_bot' => $client_bot
        ]);
    }

    public function actionOrderMaterials()
    {
        $model = Orders::find()->all();


        return $this->render('order-materials',[
            'model' => $model,
        ]);
    }


    public function actionViewMaterials($id)
    {
        if (isset($id)) {
            $id = intval($id);
        }

        $order = Orders::findOne($id);

        $model = OrderMaterials::find()
        ->where([
            'order_id' => $id
        ])
        ->all();


        return $this->render('view-materials',[
            'model' => $model,
            'order' => $order,
        ]);
    }

    public function actionDeleteFile($id)
    {
        if (isset($id)) {
            $id = intval($id);
        }

        $model = OrderMaterials::findOne($id);
        $order_id = $model->order_id;
        if (isset($model)) {
            $model->delete();
        }

        return $this->redirect(['/index.php/orders/view-materials', 'id' => $order_id]);
    }

    public function actionChangeDesc()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (!empty($_GET['id'])) {
                $desc = htmlspecialchars($_GET['desc']);
                $id = intval($_GET['id']);
                
                $model = Orders::findOne($id);
                if (isset($model)) {
                    $model->description = $desc;
                    if($model->save()){
                        return ['status' => 'success'];
                    }
                    else{
                        return ['status' => 'failure_save'];
                    }
                }
                else{
                    return ['status' => 'failure'];
                }
            }
        }
    }


    
    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
