<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrginalKatalog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orginal Katalogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

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
<div class="orginal-katalog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a("O'zgartirish", ['/index.php/orginal-katalog/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a("O'chirish", ['/index.php/orginal-katalog/delete', 'id' => $model->id], [
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
                'attribute' => 'Media',
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
                'label' => 'Tavsif',
                'value' => function ($data) {
                    return base64_decode($data->caption);
                },
            ],
            [
                'format' => 'html',
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
                'label' => 'Kategoriya',
                'value' => function ($data) {
                    if (isset($data->cat)) {
                        return base64_decode($data->cat->title);
                    }

                },
            ],
        ],
    ]) ?>

</div>
