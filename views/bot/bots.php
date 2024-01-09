<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Botlar';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="team-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>        
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'link',
            'token',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'header' => 'Jarayon',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/bot/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
