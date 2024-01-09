<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trade Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-category-index">
    <div class="row">
        <div class="col-md-6" style="padding: 20px 20px 10px 20px;">
            <h2 style="margin: 0!important;">Asosiy kategoriyalar</h2>
        </div>
        <div class="col-md-6 text-right" style="padding: 20px 20px 10px 20px;">
            <span class="btn btn-success pull-right" id="open-modal-category" data-toggle="modal" data-target="#modal-default">Qo'shish</span>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title_uz',
            'title_ru',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->status == 1) {
                        return '<span class="label label-success">Faol</span>';
                    } else {
                        return '<span class="label label-danger">Nofaol</span>';
                    }
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return  Html::a('<span class="fa fa-eye"></span>', $url) ;
                    },
                    'update' => function ($url, $model) {
                        return  Html::a('<span class="fa fa-pencil"></span>', $url) ;
                    },
                ],
            ],
        ],
    ]); ?>

</div>


<!-- <script>
    
    $(document).on('click','#open-modal-category',function(){
        $.ajax({
            url: '/trade-category/create',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer").remove()
                    
                }   
            }
        });
    })

    $(document).on('click','#add_trade_categoryy',function(event){
        event.preventDefault();
        var form = $("#trade_category_id").serialize();
        $.ajax({
            url: '/trade-category/create',
            dataType: 'json',
            type: 'POST',
            data: form,
            success: function (response) {
                if (response.status == 'success') {
                    alert("Success")
                }   
            }
        });
    })
</script> -->

<?php
    $js = <<<JS
       $(document).on('click','#open-modal-category',function(){
        $.ajax({
            url: '/trade-category/create',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer").remove()
                }   
            }
        });
    })

    $(document).on('click','#add_trade_category',function(event){
        event.preventDefault();
        var form = $("#trade_category_id").serialize();
        $.ajax({
            url: '/trade-category/create',
            dataType: 'json',
            type: 'POST',
            data: form,
            success: function (response) {
                if (response.status == 'success') {
                    location.reload()
                }   
            }
        });
    }) 
JS;
    $this->registerJs($js); ?>