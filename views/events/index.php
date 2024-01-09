<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Harakatlar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">
    <?php if (Yii::$app->session->hasFlash('success1')): ?>
        <div class="alert alert-danger alert-dismissable div_error">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
            <?= Yii::$app->session->getFlash('success1') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
    </div>
    <form class="row" style="margin-bottom: 17px">
        <div id="salom" class="col-md-3">
            <select name="branch" class="form-control select2" id="month" data-placeholder="Bo'lim tanlang" style="width: 100%">
                <option></option>
                <option value="Buyurtmalar">Buyurtmalar</option>
                <option value="Foydalanuvchilar">Foydalanuvchilar</option>
                <option value="Filial">Filiallar</option>
                <option value="Mijoz">Mijozlar</option>
                <option value="Kategoriya">Kategoriyalar</option>
                <option value="Video">Video</option>
                <option value="Bo'lim">Bo'limlar</option>
                <option value="Ustanovshik">Ustanovshiklar</option>
                <option value="Grafik">Grafik</option>
                <option value="Klient uchun savol">Klient uchun savol</option>
                <option value="User uchun savol">User uchun savol</option>
                <option value="Kroy 3d Bot">Kroy 3d Bot</option>
                <option value="Usta bot">Usta bot</option>
            </select>
        </div>
        <div class="col-md-3">
            <?php
                echo DateTimePicker::widget([
                    'name' => 'Section[Order_1][times]',
                    'id' => 'w1',
                    'options' => ['placeholder' => 'Boshlanish vaqti', 'autocomplete' => 'off'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii',
                        'autoclose'=>true,
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php
                echo DateTimePicker::widget([
                    'name' => 'Section[Order_1][times]',
                    'id' => 'w2',
                    'options' => ['placeholder' => 'Tugash vaqti', 'autocomplete' => 'off'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii',
                        'autoclose'=>true,
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <button type="button" class="filter btn btn-primary">Ko'rish</button>
        </div>
    </form>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'label' => 'Foydalanuvchi',
                'value' => function ($data) {
                    return (($data->user) ? $data->user->second_name : '<span class="label label-default">O`chirilgan</span>');
                },
            ],
            'created_date',
            'title',
            'event:ntext',
            'section_title',
        ],
    ]); ?>


</div>

<?php $this->registerJs(
    '
    $(document).on("click", ".filter", function(){
        var section_name =  $("#month").val();
        var first_date =  $("#w1").val();
        var second_date =  $("#w2").val();
        $.ajax({
            url: "section",
            data: {
                section_name: section_name,
                first_date: first_date,
                second_date: second_date
            },
            dataType: "json",
            type: "get",
            success: function(response) {
                if (response.status == "success") {
                    $(".table > tbody").html(response.content)
                }            
            },
            error: function(error){
                console.log(error)
            }
        })
    })
    
    $(".div_error").delay(3000).fadeOut();
    
', yii\web\View::POS_READY); ?>
