<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Ma'lumotlar";
$this->params['breadcrumbs'][] = $this->title;


function bot($method, $data = []) {
    $url = 'https://api.telegram.org/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$method;
//    $url = 'https://api.telegram.org/bot1659367153:AAG9gN37fDiIbj9zvD5ZZzpfKG-p_vnX6Uk/'.$method;
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
//    $url = 'https://api.telegram.org/bot1659367153:AAG9gN37fDiIbj9zvD5ZZzpfKG-p_vnX6Uk/'.$method;
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
<div class="video-index">

    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a("Qo'shish", ['/index.php/video/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'file_id',
                'format' => 'raw',
                'value' => function($data){
                    if (!is_null($data->file_id)){
                        $imageFileId = bot('getFile', [
                            'file_id' => $data->file_id
                        ]);

                        if(isset($imageFileId) and $data->type != "text" and $data->type != "video") {
//                            $file = 'https://api.telegram.org/file/bot1659367153:AAG9gN37fDiIbj9zvD5ZZzpfKG-p_vnX6Uk/'.$imageFileId->result->file_path;
                            $file = 'https://api.telegram.org/file/bot1248774241:AAHkBCsSAhMlCOlngdS5DFVWKBE7MWCi-W4/'.$imageFileId->result->file_path;
                        }

                        switch($data->type){
                            case "photo":
                                return html::img($file,
                                    ['style' => 'width: 80px; height: 80px']
                                );
                                break;
                            case "video":
                              //  return "<video controls width='250px' height=' 150px'  src=".$file."></video>";
//                                    return "<video controls width='250px' height=' 150px'  src=".$file."></video>";
                                return html::img('../../web/img/video_icon.png',
                                    ['style' => 'width: 50px; height: 50px']
                                );
                                break;
                            case "text":
                                return html::img('../../web/img/text_icon.png',
                                    ['style' => 'width: 50px; height: 50px']
                                );
                                break;
                            case "document":
                                return html::img('../../web/img/document_icon.jpg',
                                    ['style' => 'width: 50px; height: 50px']
                                );
                                break;
                        }
                    } else {
                        switch($data->type){
                            case "photo":
                                return NULL;
                                break;
                            case "video":
                                return html::img('../../web/img/dont_video_icon.png',
                                    ['style' => 'width: 50px; height: 50px']
                                );

                                break;
                            case "text":
                                return NULL;
                                break;
                            case "document":
                                return NULL;
                                break;
                        }
                    }
                }
            ],
            'type',
            'caption',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Jarayon',
                'headerOptions' => ['style' => 'color: #3c8dbc !important;'],
                'template' => '{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['index.php/video/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>