<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackClient */

$this->title = base64_decode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Feedback Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('O`zgartirish', ['/index.php/feedback-client/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('O`chirish', ['/index.php/feedback-client/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => "Savolni o'chirmoqchimisiz?",
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'html',
                'label' => 'Savol',
                'value' => function ($data) {
                    return base64_decode($data->title);
               },
            ],
            [
                'format' => 'html',
                'label' => 'Savol turi',
                'value' => function ($data) {
                    if ($data->type == 1) {
                        return 'O`zbekcha savol';
                    } else if($data->type == 2) {
                        return 'Ruscha savol';
                    }
               },
            ],
        ],
    ]) ?>

</div>
