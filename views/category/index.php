<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$spans = '';
$this->title = 'Kategoriyalar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Kategoriya qo`shish', ['index.php/category/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>        
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'format' => 'html',
                'label' => 'Filial',
                'value' => function ($data) {
                    $spans = '';
                    if (!empty($data->branchs)) {
                        foreach ($data->branchs as $key => $value) {
                            $spans .= '<span class="label label-default">'.$value->branch->title.'</span> ';
                        }
                        return $spans;
                    }
                    else{
                        return '';
                    }
                    
                    
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/category/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
