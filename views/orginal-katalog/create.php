<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalKatalog */

$this->title = "Ma'lumot yuklash";
$this->params['breadcrumbs'][] = ['label' => 'Orginal Katalogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orginal-katalog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
