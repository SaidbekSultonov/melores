<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Users;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Vazifalar";
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    #w0 .summary, #w0 .table tbody{
        display: none;
    }
    #w0 .summary, #w0 .table {
        margin: 0;
    }
    #w0{
        position: fixed;
        top: -100%;
        transition: .5s;
        background: #ccc;
        z-index: 9999;
    }
</style>
<div class="tasks-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?php
                    if (Yii::$app->user->can('Admin')){
                       echo Html::a('Arvix', ['/index.php/tasks/arxiv'], ['class' => 'btn btn-success pull-right']);
                    }
                ?>
            </h2>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Vazifa tavsif',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    if (isset($data->file)) {
                        return base64_decode($data->file->caption);    
                    }
                    
                },
            ],
            [
                'format' => 'html',
                'label' => 'Bajaruvchilar',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    $span = '';
                    foreach ($data->users as $key => $value) {
                        $selectSecondName = Users::find()->where(['id' => $value->user_id])->one();
                        $span .= '<span class="label label-primary">'.(($selectSecondName != NULL)? $selectSecondName->second_name : '').'</span><br>';
                    }
                    return $span;

                },
            ],
            [
                'label' => 'Vazifa berilgan sana',
                'value' => function ($data) {
                    return date("Y-m-d H:i",strtotime(date($data->created_date)));    
                    
                },
            ],
            'dead_line',
            [
                'format' => 'html',
                'header' => 'Holati',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    if ($data->status == 2){
                        return '<span class="label label-warning">Bajarilmoqda</span>';
                    }
                    else if ($data->status == 3){
                        return '<span class="label label-success">Bitdi</span>';
                    }
                    else {
                        return '<span class="label label-danger">Qabul qilinmagan</span>';
                    }
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Jarayon',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/tasks/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Vazifa tavsif',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    if (isset($data->file)) {
                        return base64_decode($data->file->caption);    
                    }
                    
                },
            ],
            [
                'format' => 'html',
                'label' => 'Bajaruvchilar',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    $span = '';
                    foreach ($data->users as $key => $value) {
                        $selectSecondName = Users::find()->where(['id' => $value->user_id])->one();
                        $span .= '<span class="label label-primary">'.(($selectSecondName != NULL) ? $selectSecondName->second_name : '').'</span><br>';
                    }
                    return $span;

                },
            ],
            [
                'label' => 'Vazifa berilgan sana',
                'value' => function ($data) {
                    return date("Y-m-d H:i",strtotime(date($data->created_date)));    
                    
                },
            ],
            'dead_line',
            [
                'format' => 'html',
                'header' => 'Holati',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'value' => function ($data) {
                    if ($data->status == 2){
                        return '<span class="label label-warning">Bajarilmoqda</span>';
                    }
                    else if ($data->status == 4){
                        return '<span class="label label-success">Bitdi</span>';
                    }
                    else if ($data->status == 3){
                        return '<span class="label label-warning">Admin tomonidan tasdiqlanmagan</span>';
                    }
                    else {
                        return '<span class="label label-danger">Qabul qilinmagan</span>';
                    }
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => Yii::$app->user->can('Admin'),
                'header' => 'Jarayon',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/tasks/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>

<?php

$js = <<<JS
    $(function(){
        $(".sidebar-toggle").trigger("click");

        $("#w0").width($("#w1").width())

        let a = $("#w1 .table thead tr th").length

        for(let i = 1; i <= a; i++){
            let b = $("#w1 .table thead tr th").eq(i).width()
            $("#w0 .table thead tr th").eq(i).width(b)
        }

    })

    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        if(scroll >= 220){
            $('#w0').css('top',0)
        }
        else{
            $('#w0').css('top','-100%')
        }
    });

JS;


$this->registerJs($js);
?>

