<?php  

    function diff_date($date1,$date2){

        $date1 = strtotime($date1);
        $date2 = strtotime($date2);

        // Formulate the Difference between two dates 
        $diff = abs($date2 - $date1);  
          
          
        // To get the year divide the resultant date into 
        // total seconds in a year (365*60*60*24) 
        $years = floor($diff / (365*60*60*24));  
          
          
        // To get the month, subtract it with years and 
        // divide the resultant date into 
        // total seconds in a month (30*60*60*24) 
        $months = floor(($diff - $years * 365*60*60*24) 
                                       / (30*60*60*24));  
          
          
        // To get the day, subtract it with years and  
        // months and divide the resultant date into 
        // total seconds in a days (60*60*24) 
        $days = floor(($diff - $years * 365*60*60*24 -  
                 $months*30*60*60*24)/ (60*60*24)); 
      
      
        // To get the hour, subtract it with years,  
        // months & seconds and divide the resultant 
        // date into total seconds in a hours (60*60) 
        $hours = floor(($diff - $years * 365*60*60*24  
           - $months*30*60*60*24 - $days*60*60*24) 
                                       / (60*60));  
      
      
        // To get the minutes, subtract it with years, 
        // months, seconds and hours and divide the  
        // resultant date into total seconds i.e. 60 
        $minutes = floor(($diff - $years * 365*60*60*24  
             - $months*30*60*60*24 - $days*60*60*24  
                              - $hours*60*60)/ 60);  
      
      
        // To get the minutes, subtract it with years, 
        // months, seconds, hours and minutes  
        $seconds = floor(($diff - $years * 365*60*60*24  
             - $months*30*60*60*24 - $days*60*60*24 
                    - $hours*60*60 - $minutes*60));  
      
        return printf("%d kun, %d soat, "
         . "%d minut",$days, $hours, $minutes);  
    }

?>
<?php if (Yii::$app->user->can('Admin')){ ?>
<h4 class="btn btn-danger delete_order" 
order-id="<?php echo $order->id ?>">Buyurtmani o'chirish</h4>

<?php if (!$order_control): ?>
    <h4 class="btn btn-primary back_order" 
order-id="<?php echo $order->id ?>">Buyurtmani ortga qaytarish</h4>
<?php endif ?>
<?php } ?>
<hr>
<table class="table table-bordered table-striped">
    <tr>
        <th>Buyurtma holati</th>
        <td>
            <?php if ($order->pause == 0): ?>
                <div class="label label-success">
                    FAOL
                </div>
            <?php else: ?>
                <div class="label label-warning">
                    PAUZA
                </div>
            <?php endif ?>
        </td>
    </tr>
    <tr>
        <th>Buyurtma nomi</th>
        <td>
            <?php echo $order->title ?>
        </td>
    </tr>
    <tr>
        <th>Mijoz Ismi</th>
        <td>
            <?php echo base64_decode($order->client->full_name) ?>
        </td>
    </tr>
    <tr>
        <th>Kategoriya nomi</th>
        <td>
            <?php foreach ($order->categories as $key => $value) {
                echo "<span class='label label-default'>".$value->category->title."</span> ";
            } ?>
        </td>
    </tr>
    <tr>
        <th>Buyurtma boshlanish sanasi</th>
        <td>
            <?php echo date("d-m-Y H:i",strtotime(date($order->created_date))) ?>
        </td>
    </tr>
    <tr>
        <th>Buyurtma bitish sanasi</th>
        <td>
            <?php echo date("d-m-Y H:i",strtotime(date($order->dead_line))) ?>
        </td>
    </tr>
    <tr>
        <th>OTK bahosi</th>
        <td>
            <?php if ($order->feedback_user == 0): ?>
                <i class="fa fa-check-square text-success"></i>
            <?php else: ?>
                <i class="fa fa-minus-square text-danger"></i>
            <?php endif ?>
        </td>
    </tr>
    <tr>
        <th>Qo'shimcha izoh</th>
        <td>
            <textarea name="desc" rows="5" id="desc" class="form-control" style="resize: vertical;"><?php echo $order->description; ?></textarea>
            <br>
            <?php if (Yii::$app->user->can('Admin')){ ?>
                <button class="btn btn-sm btn-success btn-block" id="save_desc" data-id="<?php echo $order->id; ?>">
                    Izohni o'zgartirish
                </button>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <th>Majburiy fayllar</th>
        <td>
            <?php if (!empty($order->required)): ?>
                <?php foreach ($order->required as $key => $value): ?>
                    <span class="label label-success" style="margin-right: 3px">
                        <?php echo $value->material->title ?>
                    </span>
                <?php endforeach ?>
            <?php else: ?>
                <span class="label label-default">Majburiy faylar kiritilmagan</span>
            <?php endif ?>
        </td>
    </tr>
</table>

<hr>
<?php if (!empty($order->order_step)): ?>

        <?php if (!$order_control): ?>
            <h4>Buyurtma holati
                <?php if (Yii::$app->user->can('Admin')){ ?>
                    <?php if ($pause): ?>
                        <span class="btn btn-success pull-right" order-id="<?php echo $order->id ?>" pause-id="<?php echo $pause->id ?>" id="active">Faollashtirish</span>
                    <?php else: ?>
                        <span class="btn btn-warning pull-right" order-id="<?php echo $order->id ?>" id="pause">STOP</span>
                    <?php endif ?>
                <?php } ?>
            </h4>
        <?php endif ?>

        
    <br>
    <br>
    <br>
    <div class="timeline timeline--label-checkpoint-above">
    <?php foreach ($order->order_step as $key => $value): ?>
            
        <div class="timeline-step <?php echo (($value->status == 0) ? 'timeline-step--current' : ''); ?>">
          <div class="timeline-step__checkpoint">
            <div class="timeline-step__label">
                <?php echo $value->section->title ?>
            </div>
            
          </div>
        </div>
            
    <?php endforeach ?>
    </div>
    <br>
    <table class="table table-bordered table-striped">
        <tr>
            <th>#</th>
            <th>Bo'lim nomi</th>
            <th>Kirish vaqti</th>
            <th>Chiqish vaqti</th>
            <th>Ketgan vaqt</th>
        </tr>
        <?php $i = 1; foreach ($order->order_step as $key => $value): ?>
            <tr>
                <td><?php echo $i ?></td>
                <td>
                    <?php echo $value->section->title ?>        
                </td>
                <td>
                    <?php echo $value->section_orders_enter_date ?>
                </td>
                <td>
                    <?php echo $value->section_orders_exit_date ?>
                </td>
                <td>
                    <?php if (isset($value->section_orders_enter_date) && isset($value->section_orders_exit_date) && $value->section_orders_exit_date != NULL): ?>
                        
                        <?php  diff_date($value->section_orders_enter_date,$value->section_orders_exit_date); ?>
                    <?php endif ?>

                </td>
            </tr> 
            
            
        <?php $i++; endforeach ?>
    </table>
<?php endif ?>