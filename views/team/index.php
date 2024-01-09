<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brigadalar';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
         <?= Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif; ?>
<div class="team-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Brigada qo`shish', ['/index.php/team/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>        
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Nomi',
                'value' => function ($data) {
                    return base64_decode($data->title);
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
                'visible' => Yii::$app->user->can('Admin'),
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/team/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
