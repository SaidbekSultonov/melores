<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bo`limlar';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
         <?= Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif; ?>
<div class="sections-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Bo`lim qo`shish', ['/index.php/sections/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'created_date',
            [
                'format' => 'html',
                'label' => 'Ish vaqti',
                'value' => function ($data) {
                    return $data->current_time;
               },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/sections/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
