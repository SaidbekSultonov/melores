<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orginal Katalogs';
$this->params['breadcrumbs'][] = $this->title;

    function bot($method, $data = []) {
        $url = 'https://api.telegram.org/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);

        if(curl_error($ch)){
            var_dump(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }

    function botFile($method, $data = []) {
        $url = 'https://api.telegram.org/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $res = curl_exec($ch);
        curl_close($ch);

        if(curl_error($ch)) {
            var_dump(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }
?>
<div class="orginal-katalog-index">

    <div class="row">
        <div class="col-md-6">
            <h1>Katalog</h1>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a("Ma'lumot qo'shish", ['/index.php/orginal-katalog/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'Media',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'format' => 'raw',
                'value' => function($data){
                    if (!is_null($data->file_id)){
                        $imageFileId = bot('getFile', [
                            'file_id' => $data->file_id
                        ]);

                        if(isset($imageFileId) and $data->type != "text") {
                            $file = 'https://api.telegram.org/file/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$imageFileId->result->file_path;
                        }

                        switch($data->type){
                            case "photo":
                                return html::img($file,
                                    ['style' => 'width: 80px; height: 80px']
                                );
                                break;
                            case "video":
                                return "<video controls width='250px' height=' 150px'  src=".$file."></video>";
                                break;
                            case "text":
                                return html::img('https://icon-library.com/images/png-file-icon/png-file-icon-6.jpg',
                                    ['style' => 'width: 80px; height: 80px']
                                );
                                break;
                        }
                    } else {
                        return NULL;
                    }
                }
            ],
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'label' => 'Tavsif',
                'value' => function ($data) {
                    return base64_decode($data->caption);
                },
            ],
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
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'label' => 'Kategoriya',
                'value' => function ($data) {
                    if (isset($data->cat)) {
                        return base64_decode($data->cat->title);
                    }

                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/orginal-katalog/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
