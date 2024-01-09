<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sections */

$this->title = 'Bo`lim qo`shish';
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'section_time' => $section_time,
    ]) ?>

</div>
