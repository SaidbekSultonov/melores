<div class="tab-content" id="v-pills-tabContent">
    <?php if (!empty($sections)): ?>
        <div class="active in" id="order-block">
            <div class="calendar-container">    
                <?php $today = date("Y-m-d"); ?>
                <div class="calendar-header">
                    <h1><?php echo $sections->title; ?></h1>
                    <p><?php echo date("Y"); ?></p>
                </div>
                <div class="calendar">
                    <div class="day-block">
                        <?php for ($i=0; $i < 30; $i++): ?>
                            <div <?php if ($i == 29) echo "style='border-right: 0!important;'" ?> class="day">
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
                                                    echo "<div class='in_day' order-id='".$order['id']."' section-id='".$sections->id."' data-toggle='modal' data-target='#modal-default'></div>";
                                                } else {
                                                    echo "<div class='in_day' order-id='".$order['id']."' section-id='".$sections->id."' data-toggle='modal' data-target='#modal-default'></div>";
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php 
                                        if ($i == 0) {
                                            echo "<span class='order-title' order-id='".$order['id']."' section-id='".$sections->id."' data-toggle='modal' data-target='#modal-default'>".$order['title']."</span>";
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