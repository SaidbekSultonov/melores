<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Foydalanuvchilar';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    
    #w0 .summary{
        display: none;
    }

</style>
<div class="users-index">
    <?php if (Yii::$app->session->hasFlash('danger')): ?>
        <div class="alert alert-danger alert-dismissable">
             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
             <h4><i class="icon fa fa-check"></i>Xatolik!</h4>
             <?= Yii::$app->session->getFlash('danger') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissable">
             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
             <h4><i class="icon fa fa-check"></i>Muvaffaqiyatli!</h4>
             <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <?= Html::a('Foydalanuvchi qo`shish', ['index.php/users/create'], ['class' => 'btn btn-success pull-right']) ?>
            </h2>
        </div>
    </div>
    <hr>
   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                            return 'Bo`lim boshlig`i';
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
            

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['/index.php/users/'.$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>

<?php

$js = <<<JS
    $(function(){
        $('.table').dataTable({
            "paging": false
        } );

        let sss = $(".sss").width()
        $(".ddd").width(sss)

        let vvv = $(".vvv").width()
        $(".fixed").width(vvv)

       
        
    })

    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        if(scroll >= 220){
            $('.fixed').css('top',0)
        }
        else{
            $('.fixed').css('top','-100%')
        }
    });

JS;


$this->registerJs($js);
?>
