<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalStepKatalog */

$this->title = base64_decode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Orginal Step Katalogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orginal-step-katalog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a("O'zgartirish", ['/index.php/orginal-step-katalog/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a("O'chirish", ['/index.php/orginal-step-katalog/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => "O'chirishni istaysizmi?",
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'label' => 'Matn tili',
                'value' => function ($data) {
                    if ($data->lang == 1) {
                        return "O'zbekcha";
                    } else if($data->lang == 2) {
                        return 'Ruscha';
                    }
                },
            ],
            [
                'format' => 'html',
                'label' => 'Matn',
                'value' => function ($data) {
                    return base64_decode($data->title);
                },
            ],
        ],
    ]) ?>

</div>
