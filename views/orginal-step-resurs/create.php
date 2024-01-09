<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalStepResurs */

$this->title = "Ma'lumot yuklash";
$this->params['breadcrumbs'][] = ['label' => 'Orginal Step Resurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orginal-step-resurs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
