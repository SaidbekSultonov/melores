<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Brigada */

$this->title = $model->title_uz;
$this->params['breadcrumbs'][] = ['label' => 'Brigadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="brigada-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('O`zgartirish', ['class' => 'btn btn-primary', 'data-id'=> $model->id, 'id' => 'open-modal-edit', 'data-toggle' => 'modal', 'data-target' => '#modal-default']) ?>
        <?php
            if ($model->status == 1) {
                echo Html::a('O`chirish', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Ushbu brigada o`chirishni istaysizmi?',
                        'method' => 'post',
                    ],
                ]);
            } else {
                echo Html::a('Faollashtirish', ['activate', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Ushbu brigada faollashtirishni istaysizmi?',
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title_uz',
            'title_ru',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->status == 1) {
                        return '<span class="label label-success">Faol</span>';
                    } else {
                        return '<span class="label label-danger">Nofaol</span>';
                    }
                }
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->parent->second_name;
                }
            ],
        ],
    ]) ?>

    <div class="worker_body" style="padding: 0px 2px;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"># Ishchi id raqami</th>
                    <th scope="col">Ishchining ismi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workers as $worker => $value): ?>
                    <tr>
                        <th scope="row"><?= $value['id'] ?></th>
                        <td><?= $value->parent->second_name; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>


<?php
    $js = <<<JS
    $(document).on('click','#open-modal-edit',function(event){
        event.preventDefault();
        $("#modal-default").find('.modal-body').html('');
        var editId = $(this).attr('data-id');
        $.ajax({
            url: '/brigada/update',
            dataType: 'json',
            type: 'GET',
            data: {id: editId},
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer").remove()
                }   
            }
        });
    })

    $(document).on('click','.update-info',function(event){
        event.preventDefault();
        $.ajax({
            url: '/brigada/update-brigada',
            dataType: 'json',
            type: 'POST',
            data: $('#brigada-form').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }   
            }
        });
    })

JS;
    $this->registerJs($js); ?>