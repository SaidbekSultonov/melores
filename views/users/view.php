<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('O`zgartirish', ['/index.php/users/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Ochirish', ['/index.php/users/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Foydalanuvchini o`chirishni istaysizmi?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'second_name',
            'phone_number',
            [
                'format' => 'html',
                'label' => 'Ro`li',
                'value' => function ($data) {
                    switch ($data->type) {
                        case '1':
                            return 'Admin';
                        break;
                        case '2':
                            return 'Nazoratchi';
                        break;
                        case '3':
                            return 'O`TK';
                        break;
                        case '4':
                            return 'Sotuvchi';
                        break;
                        case '5':
                            return 'Otdel boshlig`i';
                        break;
                        case '6':
                            return 'Kroychi';
                        break;
                        case '7':
                            return 'Bo`lim ishchisi';
                        break;
                        case '8':
                            return 'HR';
                        break;
                        case '9':
                            return 'Marketolog';
                        break;
                        case '10':
                            return 'Brigadir';
                        break;
                    }
               },
            ],
            [
                'format' => 'html',
                'label' => 'Filiallari',
                'value' => function ($data) {
                    $span = '';
                    foreach ($data->branchs as $key => $value) {
                        $span .= '<span class="label label-default">'.$value->branch->title.'</span> ';
                    }

                    return $span;
                    
               },
            ],
        ],
    ]) ?>

</div>
