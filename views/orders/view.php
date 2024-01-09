<?php

use app\models\SectionOrders;
use app\models\OrderStep;
?>
<style>
    .box {
        background: #f0f0f0;
    }

    .count-down,
    .count-up {
        color: #000;
        font-family: arial;
        font-size: 10px;
        font-weight: bold;
    }

    .count-down p,
    .count-up p {
        margin-bottom: 0;
    }

    .count-down span {
        float: left;
        width: 23%;
        border: 1px solid #ddd;
        text-align: center;
        margin: 1%;
        padding: 0px;
        color: #808080;
    }

    .count-up span {
        float: left;
        width: 23%;
        border: 1px solid #ddd;
        text-align: center;
        margin: 1%;
        padding: 0px;
        color: #808080;
    }

    span.time-count {
        padding: 0;
        border: 0px solid;
        margin: 0;
        width: 100%;
        color: #444;
    }

    .count-down span {
        background: #dd4b3985;
    }

    .count-up span {
        background: #00a65a6e;
    }
</style>
<div class="row" style="padding-left:10px;padding-right:10px;">
    <?php if (!empty($model)) :
        $card_num_1 = 0;
    ?>
        <?php foreach ($model as $key => $value) :
            $model2 = SectionOrders::find()
                ->where([
                    'order_id' => $value->id,
                    'exit_date' => NULL
                ])
                ->one();
        ?>
            <?php if (isset($model2)) : ?>
                <?php
                if ($card_num_1 % 6 == 0) {
                    echo '<div class="row" style="padding-left:10px;padding-right:10px;">';
                }
                $card_num_1++;
                ?>

                <div class="col-md-2">
                    <div class="card">
                        <p class="text-muted">
                            <b>Mijoz:</b> <?php echo base64_decode($value->client->full_name) ?>
                        </p>

                        <hr class="m-1">
                        <p class="text-muted">
                            <b>Kategoriya:</b> <?php if (!empty($value->categories)) : ?>
                                <?php foreach ($value->categories as $keyc => $valuec) : ?>
                                    <?php echo $valuec->category->title ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </p>
                        <hr class="m-1">


                        <?php
                        $order_step = OrderStep::find()
                            ->where([
                                'order_id' => $value->id,
                                'status' => 0
                            ])
                            ->orderBy(['id' => SORT_ASC])
                            ->one();
                        ?>
                        <?php if (isset($order_step->deadline) && $order_step->deadline > date('Y-m-d H:i:s')) : ?>
                        <?php else : ?>
                        <?php endif ?>
                        <?php if (isset($order_step)) : ?>
                            <h5>
                                <center>
                                    <?php $model = SectionOrders::find()
                                        ->where([
                                            'order_id' => $value->id,
                                            'exit_date' => NULL
                                        ])
                                        ->one();

                                    if (isset($model)) {
                                        echo  $model->section->title;
                                    } else {
                                        echo  $value->id;
                                    } ?>dan chiqishiga</center>
                            </h5>
                            <div class="row">
                                <!-- <div class="count-down"> -->
                                <!-- <div data-countdown="<//?php echo date("Y-m-d", strtotime(date($order_step->deadline))) ?>"></div> -->
                                <!-- </div> -->
                                <?php
                                $class_to_timer_to_deadline = 'count-down';
                                $date_deadline_1 = new DateTime($order_step->deadline);
                                $date_deadline_2 = new DateTime();
                                if ($order_step->deadline > date('Y-m-d H:i:s')) {
                                    $class_to_timer_to_deadline = 'count-up';
                                    $diff = $date_deadline_1->diff($date_deadline_2);
                                } else {
                                    $diff = $date_deadline_2->diff($date_deadline_1);
                                }
                                ?>
                                <div class="<?= $class_to_timer_to_deadline ?>">
                                    <div class="timer" data-countdown="<?= $order_step->deadline ?>">
                                        <span class="cdown day"><span class="time-count"><?php echo $diff->d ?></span>
                                            <p>Kun</p>
                                        </span> <span class="cdown hour"><span class="time-count"><?php echo $diff->h ?></span>
                                            <p>Soat</p>
                                        </span> <span class="cdown minutes"><span class="time-count"><?php echo $diff->i ?></span>
                                            <p>Minut</p>
                                        </span> <span class="cdown second"><span class="time-count"><?php echo $diff->s ?></span>
                                            <p>Sekund</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <h5>
                            <center>Buyurtma bitishiga</center>
                        </h5>
                        <div class="row">
                            <?php
                            $class_to_timer = 'count-down';
                            $date1 = new DateTime($value->dead_line);
                            $date2 = new DateTime();
                            if ($value->dead_line > date('Y-m-d H:i:s')) {
                                $class_to_timer = 'count-up';
                                $diff = $date1->diff($date2);
                            } else {
                                $diff = $date2->diff($date1);
                            }
                            ?>
                            <div class="<?= $class_to_timer ?>">
                                <div class="timer" data-countdown="<?= $value->dead_line ?>">
                                    <span class="cdown day"><span class="time-count"><?php echo $diff->d ?></span>
                                        <p>Kun</p>
                                    </span> <span class="cdown hour"><span class="time-count"><?php echo $diff->h ?></span>
                                        <p>Soat</p>
                                    </span> <span class="cdown minutes"><span class="time-count"><?php echo $diff->i ?></span>
                                        <p>Minut</p>
                                    </span> <span class="cdown second"><span class="time-count"><?php echo $diff->s ?></span>
                                        <p>Sekund</p>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>

                <?php
                if ($card_num_1 % 6 == 0) {
                    echo "</div>";
                }
                ?>

            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
</div>

<?php

$js = <<<JS
var interval = setInterval(function() {
    $(".count-down").each(function(){
        var timer = $(this).children('.timer');
        var html_day = $(timer).children('.day').children('.time-count');
        var html_hours = $(timer).children('.hour').children('.time-count');
        var html_minutes = $(timer).children('.minutes').children('.time-count');
        var html_second = $(timer).children('.second').children('.time-count');

        var day = parseInt(html_day.html())
        var hours = parseInt(html_hours.html())
        var minutes = parseInt(html_minutes.html())
        var second = parseInt(html_second.html())

        if (second == 59) {
            second=0;
            if (minutes == 59) {
                minutes=0;
                if (hours == 23) {
                    hours=0;
                    day++
                }else{
                    hours++
                }
            }else{
                minutes++
            }
        }else{
            second++
        }


        html_day.html(day)
        html_hours.html(hours)
        html_minutes.html(minutes)
        html_second.html(second)
    });
	$(".count-up").each(function(){
        var timer = $(this).children('.timer');
        var html_day = $(timer).children('.day').children('.time-count');
        var html_hours = $(timer).children('.hour').children('.time-count');
        var html_minutes = $(timer).children('.minutes').children('.time-count');
        var html_second = $(timer).children('.second').children('.time-count');

        var day = parseInt(html_day.html())
        var hours = parseInt(html_hours.html())
        var minutes = parseInt(html_minutes.html())
        var second = parseInt(html_second.html())

        if (second == 0) {
            if (minutes == 0) {
                if (hours == 0) {
                   if (day == 0) {
                       $(this).addClass('count-down')
                       $(this).removeClass('count-up')
                   }else{
                    hours = 23;
                    minutes = 59;
                    second = 59;
                    day--
                   }
                }else{
                    minutes = 59;
                    second = 59;
                    hours--
                }
            }else{
                second = 59;
                minutes--
            }
        }else{
            second--
        }


        html_day.html(day)
        html_hours.html(hours)
        html_minutes.html(minutes)
        html_second.html(second)
    });
}, 1000);
JS;

$this->registerJs($js);
?>

<?php $this->registerJs(
    '
$(function(){
        $(".sidebar-toggle").trigger("click");
    })
   

',
    yii\web\View::POS_READY
); ?>