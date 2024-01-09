<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brigadalar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brigada-index">
    <div class="row w-100 h-100">
        <div class="col-md-6 h-100" style="padding: 20px;">
            <h1 style="margin: 0;"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6 h-100 text-right" style="padding: 20px;">
            <span class="btn btn-success pull-right" id="open-modal-category" data-toggle="modal" data-target="#modal-default">Qo'shish</span>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title_uz',
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
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function($data) {
                    if (isset($data->parent)) {
                        return $data->parent->second_name;
                    } else {
                        return '<span>ismi mavjud emas</span>';
                    }
                    
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return  Html::a('<span class="fa fa-eye"></span>', $url);
                    },
                    'update' => function ($url, $model) {
                        return  Html::a('<span class="fa fa-pencil" data-id="'.$model->id.'" id="open-modal-edit" data-toggle="modal" data-target="#modal-default"></span>');
                    },
                    'delete' => function ($url, $model) {
                        return  Html::a('<span class="fa fa-trash"></span>', $url);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<?php
    $js = <<<JS
       $(document).on('click','#open-modal-category',function(){
        $("#modal-default").find('.modal-body').html('');
        $.ajax({
            url: '/brigada/open-modal',
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

    $(document).on('click','.save-info',function(event){
        event.preventDefault();
        $.ajax({
            url: '/brigada/save-brigada',
            dataType: 'json',
            type: 'POST',
            data: $('#brigada-form').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }   
            }
        });
    })

    $(document).on('click','#open-modal-edit',function(event){
        event.preventDefault();
        $("#modal-default").find('.modal-body').html('');
        var editId = $(this).attr('data-id');
        $.ajax({
            url: '/brigada/update',
            dataType: 'json',
            type: 'GET',
            data: {id: editId},
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer").remove()
                }   
            }
        });
    })

    $(document).on('click','.update-info',function(event){
        event.preventDefault();
        $.ajax({
            url: '/brigada/update-brigada',
            dataType: 'json',
            type: 'POST',
            data: $('#brigada-form').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }   
            }
        });
    })

JS;
    $this->registerJs($js); ?>