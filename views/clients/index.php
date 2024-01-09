<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mijozlar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Mijoz qo`shish', ['/index.php/clients/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>
    <hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'label' => 'Familiya Ismi',
                'value' => function ($data) {
                    return base64_decode($data->full_name);
               },
            ],
            [
                'format' => 'html',
                'label' => 'Telefon raqami',
                'value' => function ($data) {
                    return base64_decode($data->phone_number);
               },
            ],
            [
                'format' => 'html',
                'label' => 'Holati',
                'value' => function ($data) {
                    return (($data->status == 1) ? '<span class="label label-success">Faol</span>' : '<span class="label label-danger">Arxiv</span>');
               },
            ],
            [
                'format' => 'html',
                'label' => 'Filial',
                'value' => function ($data) {
                    if (isset($data->branch->title)) {
                        return $data->branch->title;
                    }
                    return "Ma'lumot topilmadi";
               },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/clients/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
<?php
$js = <<<JS
    $(function(){
        $('.table').dataTable({
            "paging": false
        } );
        
    })

JS;


$this->registerJs($js);
?>

