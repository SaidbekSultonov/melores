<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trade Company Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-company-files-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Trade Company Files', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'company_id',
            'file_id',
            'type',
            'status',
            //'title_uz',
            //'title_ru',
            //'services_type_id',
            //'district_id',
            //'long',
            //'lat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
