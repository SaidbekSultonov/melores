<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
        <div class="col-md-6">
            <h2 style="text-align: right;">
                <?= Html::a('O`zgartirish', ['index.php/category/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('O`chirish', ['index.php/category/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Kategoriyani o`chirmoqchimisiz ?',
                        'method' => 'post',
                    ],
                ]) ?>
            </h2>
        </div>
    </div>

    

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
        ],
    ]) ?>

</div>
