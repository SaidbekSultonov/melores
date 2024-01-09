<?php
use yii\helpers\Html;
use app\models\Sections;

$sections = Sections::find()
->where(['status' => 1])
->andWhere(['!=', 'id', 1])
->orderBy(['order_column' => SORT_ASC])
->all();

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>Original-Mebel.APP</title>
        <link rel="stylesheet" href="../../web/css/select2.min.css">
        <link rel="stylesheet" href="../../web/css/orders.css">
        <link rel="stylesheet" href="../../Flip/css/flipTimer.css" />
         <link rel="shortcut icon" href="/web/img/logo.jpg" type="image/x-icon"/>
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css"/>
 
        

        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper2">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <div class="container-fluid">
          <?= $content ?>
        </div>


        <?php // $this->render(
            // 'content.php',
            // ['content' => $content, 'directoryAsset' => $directoryAsset]
        // ) ?>

    </div>

    <?php $this->endBody() ?>
    <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Yopish</button>
                <button type="button" class="btn btn-primary">Saqlash</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


        <div class="modal fade" id="modal-default2">
          <div class="modal-dialog" style="width: 75%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Yopish</button>
                  <?php
                    if (Yii::$app->user->can('Admin')){ ?>
                        <button type="button" class="btn btn-danger">Buyurtma bitdi</button>
                    <?php }
                  ?>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


        <div class="modal fade" id="modal-default3">
          <div class="modal-dialog" style="width: 75%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Yopish</button>
                <button type="button" class="btn btn-success">Saqlash</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
<script src="../../web/js/select2.full.min.js"></script>
<script src="../../web/js/jquery.countdown.min.js"></script>
<script src="../../Flip/js/jquery.flipTimer.js"></script>
