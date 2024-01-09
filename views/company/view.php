<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

function bot($method, $data = []) {
    $url = 'https://api.telegram.org/bot1931399705:AAGipbWOhynFHLeHFyfgEFFBGc6K3TcPrL8/'.$method;
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

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->title_uz;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <a class="btn btn-success pull-right" href="/index.php/company-files/create?id=<?php echo $model->id ?>">Ma'lumot qo'shish</a>
            </h2>
        </div>
    </div>
    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title_uz',
            'title_ru',
        ],
    ]) ?>

    <hr>
    <h3><?php echo $this->title ?> kompaniyasi ma'lumotlari</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>#</th>
            <th>Nomi UZ</th>
            <th>Nomi RU</th>
            <th>Bo'limi</th>
            <th>Hududi</th>
            <th>Fayl</th>
            <th>O'chirish</th>
        </tr>
        <?php if (!empty($files)): ?>
            <?php $i = 1; foreach ($files as $key => $value): ?>
                <tr>
                    <td>
                        <?php echo $i ?>
                    </td>
                    <td>
                        <?php echo $value->title_uz ?>
                    </td>
                    <td>
                        <?php echo $value->title_ru ?>
                    </td>
                    <td>
                        <span class="label label-primary">
                            <?php echo $value->services_type->title_uz ?>    
                        </span>
                    </td>
                    <td>
                        <span class="label label-success">
                            <?php echo $value->district->title_uz ?>    
                        </span>
                    </td>
                    <td>
                        <?php
                            if (!is_null($value->file_id)){
                                $imageFileId = bot('getFile', [
                                    'file_id' => $value->file_id
                                ]); 

                                if(isset($imageFileId) and $value->type != "text" and $value->type != "video") {
                                    $file = 'https://api.telegram.org/file/bot1931399705:AAGipbWOhynFHLeHFyfgEFFBGc6K3TcPrL8/'.$imageFileId->result->file_path;
                                }

                                switch($value->type){
                                    case "photo":
                                        echo html::img($file,
                                            ['style' => 'width: 80px; height: 80px']
                                        );
                                        break;
                                    case "video":
                                        echo html::img('../../web/img/video_icon.png',
                                            ['style' => 'width: 50px; height: 50px']
                                        );
                                        break;
                                    case "text":
                                            echo base64_decode($value->file);
                                        break;

                                    case "location":
                                        echo "<a target='_blank' href='https://www.google.com/maps/place/Korzinka.uz+-+%D0%9A%D0%B0%D1%80%D0%B0%D1%82%D0%B0%D1%88/@".$value->long.",".$value->lat.",17z/data=!4m5!3m4!1s0x38ae8ba215c252f5:0xeafa366911899d4b!8m2!3d41.3162848!4d69.2329358'>Lokatsiya Google Map</a>";
                                    break;
                                }
                            } else {
                                switch($value->type){
                                    case "photo":
                                        echo NULL;
                                        break;
                                    case "video":
                                        echo html::img('../../web/img/dont_video_icon.png',
                                            ['style' => 'width: 50px; height: 50px']
                                        );
                                        break;
                                    case "text":
                                        echo NULL;
                                        break;

                                    case "location":
                                        echo NULL;
                                        break;
                                }
                            }
                        ?>
                    </td>
                    <td>
                        <a class="del" href="/index.php/company-files/delete?id=<?php echo $value->id ?>&com=<?php echo $model->id ?>">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php $i++; endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="7">
                    <center>Ma'lumot qo'shilmagan</center>
                </td>
            </tr>
        <?php endif ?>
    </table>

</div>


<script>
    $(document).on('click','.del',function(event){
        if (!confirm('O`chirishni istaysizmi ?')) {
            event.preventDefault();
        }
    })
</script>
