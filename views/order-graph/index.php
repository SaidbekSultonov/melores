<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Buyurtma grafiki';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .graph-block {
        width: 100%;
        height: auto;
        padding: 20px;
    } .graph-block h2 {
            margin: 0;
        } .graphs-block {
                width: 100%;
                height: auto;
                overflow: hidden;
                border: 1px solid #f4f4f4;
                margin-top: 10px;
            } .left {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: row;
                } .left a {
                        padding: 5px;
                        font-size: 11px;
                        border-right: 1px solid #f4f4f4;
                        color: #444;
                        transition: .5s;
                    } .left a:hover {
                            background-color: #3c8dbc;
                            color: #fff;
                        } .left h4 {
                            padding: 15px 10px;
                            margin: 0;
                        }
              .right {
                    width: 100%;
                    height: 100%;
                    border-top: 1px solid #f4f4f4;
                }
    .calendar {
        display: grid;
        width: 100%;
        grid-template-rows: 40px;
        overflow: auto;
    } .calendar-container {
            width: 100%;
            margin: auto;
            overflow: hidden;
            background: #fff;
        } .calendar-header {
                text-align: center;
                padding: 20px 0;
                background: linear-gradient(to bottom, #fafbfd 0%, rgba(255, 255, 255, 0) 100%);
                border-bottom: 1px solid rgba(166, 168, 179, 0.12);
            } .calendar-header h1 {
                    margin: 0;
                    font-size: 18px;
                } .calendar-header p {
                        margin: 5px 0 0 0;
                        font-size: 13px;
                        font-weight: 600;
                        color: rgba(81, 86, 93, .4);
                    } .calendar-header button {
                            background: 0;
                            border: 0;
                            padding: 0;
                            color: rgba(81, 86, 93, .7);
                            cursor: pointer;
                            outline: 0;
                        }
                      .day-block {
                            width: 100%;
                            display: flex;
                            justify-content: space-between;
                        } .days {
                                width: 100%;
                                height: 40px;
                                border-bottom: 1px solid rgba(166, 168, 179, 0.12);
                                border-right: 1px solid rgba(166, 168, 179, 0.12);
                                color: #98a0a6;
                                position: relative;
                                z-index: 1;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                padding: 5px 0;
                            } .days p {
                                    margin: 0;
                                    font-size: 11.5px;
                                } .days .in_day {
                                        width: 40.5px;
                                        height: 80%;
                                        background-color: lightblue;
                                        position: absolute;
                                        z-index: 9999;
                                        overflow: hidden;
                                        cursor: pointer;
                                    }
                      .order-block {
                            width: 100%;
                            height: auto;
                            display: flex;
                        } span.order-title {
                                position: absolute;
                                z-index: 10;
                                font-size: 10px;
                                padding: 13px 0 13px 5px;
                                cursor: pointer;
                            }
</style>

<div class="order-graph-index">
    <div class="graph-block">
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="graphs-block">
            <div class="left">
                <?php if (!empty($sections)): ?>
                    <?php foreach ($sections as $key => $value): ?>
                        <a class="nav-link active self-section" <?php if ($value->id == 29) echo "style='border-right: 0!important;'";?> data-id="<?php echo $value->id?>"><?php echo (($value->title) ? $value->title : '') ?></a>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
            <div class="right">
                <div class="tab-content" id="v-pills-tabContent">
                    <?php if (!empty($sections)): ?>
                        <div class="active in" id="order-block">
                            <div class="calendar-container">    
                                <?php $today = date("Y-m-d"); ?>
                                <div class="calendar-header">
                                    <h1><?php echo $sections[0]['title']; ?></h1>
                                    <p><?php echo date("Y"); ?></p>
                                </div>
                                <div class="calendar">
                                    <div class="day-block">
                                        <?php for ($i=0; $i < 30; $i++): ?>
                                            <div <?php if ($i == 29) echo "style='border-right: 0!important;'" ?> class="days">
                                                <p>
                                                    <?php echo date('d.m', strtotime('+'.$i.' days', strtotime(str_replace('/', '.', $today)))); ?>
                                                </p>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                    <?php foreach ($defaultOrders as $key => $order): ?>
                                        <div class="order-block">
                                            <?php for ($i=0; $i < 30; $i++): ?>
                                                <div class="days" <?php if ($i == 29) echo "style='border-right: 0!important;'" ?>>
                                                    <?php 
                                                        $orderCreatDate = $order['created_date'];
                                                        $orderCreatMonDay = date("Y-m-d", strtotime($orderCreatDate));
                                                        $orderDeadDate = $order['dead_line'];
                                                        $orderDeadMonDay = date("Y-m-d", strtotime($orderDeadDate));
                                                        $todayDate = date('Y-m-d', strtotime('+'.$i.' days', strtotime(str_replace('/', '.', $today))));
                                                        if ($todayDate >= $orderCreatMonDay && $orderDeadMonDay >= $todayDate) {
                                                            if ($i == 0) {
                                                                echo "<div class='in_day' order-id='".$order['id']."' section-id='".$sections[0]['id']."' data-toggle='modal' data-target='#modal-default'></div>";
                                                            } else {
                                                                echo "<div class='in_day' order-id='".$order['id']."' section-id='".$sections[0]['id']."' data-toggle='modal' data-target='#modal-default'></div>";
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                                <?php 
                                                    if ($i == 0) {
                                                        echo "<span class='order-title' order-id='".$order['id']."' section-id='".$sections[0]['id']."' data-toggle='modal' data-target='#modal-default'>".$order['title']."</span>";
                                                    }
                                                 ?>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Yopish</button>
                <button type="button" class="btn btn-primary">Saqlash</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default2">
    <div class="modal-dialog" style="width: 75%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Yopish</button>
                <?php
                    if (Yii::$app->user->can('Admin')){ ?>
                        <button type="button" class="btn btn-danger">Buyurtma bitdi</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".self-section", function(){
        let _this = $(this)
        let section_id = $(this).attr("data-id")

        $.ajax({
            url: '/index.php/order-graph/index',
            dataType: 'json',
            type: 'GET',
            data:{
                section_id: section_id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    $(".right").html(response.content);
                    $("#order-block").removeClass('active in');
                    $(_this).addClass('active in');
                }
            }
        });
    });

    $(document).on("click", ".in_day", function(){
        let _this = $(this)
        let order_id = $(this).attr("order-id")
        let section_id = $(this).attr("section-id")
        $("#modal-default").find('.modal-body').html('');

        $.ajax({
            url: '/index.php/order-graph/update',
            dataType: 'json',
            type: 'GET',
            data:{
                order_id: order_id,
                section_id: section_id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer").remove()
                }   
            }
        });
    });

    $(document).on("click", ".order-title", function(){
        let _this = $(this)
        let order_id = $(this).attr("order-id")
        let section_id = $(this).attr("section-id")
        $("#modal-default").find('.modal-body').html('');

        $.ajax({
            url: '/index.php/order-graph/update',
            dataType: 'json',
            type: 'GET',
            data:{
                order_id: order_id,
                section_id: section_id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer").remove()
                }   
            }
        });
    });

    $(document).on("click", ".save-order-graph", function(event){
        event.preventDefault();
        $.ajax({
            url: '/index.php/order-graph/create',
            dataType: 'json',
            type: 'POST',
            data: $('#order-graph-form').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }   
            }
        });
    });
</script>