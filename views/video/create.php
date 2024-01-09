<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Video */

$this->title = 'Fayl yuklash';
$this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-create">
    <?php if ($model->type == "photo"): ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($model->type == "video"): ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($model->type == "document"): ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($model->type == "text"): ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
