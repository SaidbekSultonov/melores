<?php  

   
// $start = strtotime('2021-09-13 23:12:02');
// $end = strtotime('2021-09-14 15:00:02');

// $days_between = abs($end - $start) / 86400;


function different_day($deadline){
    $endDate = date('Y-m-d H:i:s');
    $startDate = date($deadline);
    $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);

    // $start  = date_create(date('Y-m-d H:i:s'));
    // $end    = date_create(date("2021-09-19 10:30:00"));
    // $diff   = date_diff( $start, $end );
    // $day = $diff->d;

    return  floor($days);

}



?>
<style>
    .box-day{
        position: absolute;
        width: 50%;
        height: 50%;
        right: 0;
        top: 25%;
        bottom: 50%;
        opacity: 0.5;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 5rem;
    }
</style>
<section class="lists-container">

    <?php if (!empty($sections)): ?>
        <?php $i = 1; foreach ($sections as $key => $value): ?>
            <div class="list">
                <h3 class="list-title">
                    <?php echo $value->title ?>
                    <br>
                    <br>
                    <span class="font-lighter text-muted">
                        <?php echo ((count($value->orders) > 0) ?  count($value->orders)."ta
                         buyurtma" : "Buyurtma yo`q") ?>
                    </span>
                </h3>

                <ul class="list-items">
                    <?php if (!empty($value->orderscontrol)): 
                        ?>
                        <?php foreach ($value->orderscontrol as $keyo => $valueo): 
                            $style = 'style="border:blue solid 2px"';
                            // if ($valueo->order->pause == 1) {
                            //     $style = 'style="border:orange solid 2px; opacity:.5"';
                            // }
                            // else if($valueo->order->section_orders($valueo->order->id,$value->id) != NULL){
                            //     $style = 'style="border:green solid 2px;"';
                            // }
                            ?>
                            <li <?php // echo $style ?> class="open_modal"  order-id="<?php echo $valueo->order_id ?>" section-id="<?php echo $value->id ?>" 
                                data-toggle="modal" 
                                data-target="#modal-default2">
                                <b title="Buyurtma nomi">
                                    <i class="text-info fa fa-reorder"></i>
                                    <?php echo (($valueo->order) ? $valueo->order->title : '') ?>
                                </b>    
                                <hr class="m-1">
                                <p title="Mijoz Ismi">
                                    <i class="text-info fa fa-user"></i>
                                    <?php echo (($valueo->order) ? base64_decode($valueo->order->client->full_name) : '') ?>
                                </p>

                                
                                    <?php if(!empty($valueo->order->categories)): ?>
                                        <?php foreach ($valueo->order->categories as $keyv => $valuev): ?>
                                            <p title="Kategoriya nomi">
                                                <i class="text-info fa fa-bookmark-o"></i>
                                                <?php echo $valuev->category->title ?>
                                            </p>

                                        <?php endforeach ?>
                                    <?php endif; ?>

                                <p title="Filial nomi">
                                    <i class="text-info fa fa-bank"></i>
                                    <?php echo (($valueo->order) ? $valueo->order->branch->title : '') ?>
                                </p>
                                <hr class="m-1">
                                <b title="Buyurtma bitish sanasi">
                                    <i class="text-info fa fa-hourglass-3"></i>
                                    <?php echo

                                    (($valueo->order) ? date("d-m-Y H:i",strtotime(date($valueo->order->dead_line)) ) : '') 
                                    
                                     ?>
                                </b> 
                                <hr class="m-1">
                                <?php if (isset($valueo->order->parralel) && $valueo->order->parralel == 1): ?>
                                    <span class="label label-warning">
                                        Shponli buyurtma
                                    </span>    
                                <?php endif ?>
                                
                                
                            </li>        
                        <?php endforeach ?>
                    <?php endif ?>
                    <?php if (!empty($value->orders)): ?>
                        <?php foreach ($value->orders as $keyo => $valueo): 
                            $style = '';
                            
                            if (isset($valueo->order) && $valueo->order->pause == 1) {
                                $style = 'border:orange solid 2px; opacity:.5";background: orange';
                            }
                            else if(isset($valueo->order) && $valueo->order->section_orders($valueo->order->id,$value->id) != NULL){
                                
                                $style = 'border:green solid 2px;background:#00a65a52';
                            }
                            else if(isset($valueo->order) && $valueo->order->dead_line < date('Y-m-d H:i:s')){
                                $style = 'border:#dd4b39 solid 2px; background: #dd4b3929';
                            }

                            
                            $start = $valueo->enter_date;
                            $add_time = '+'.$value->current_time.' hour';
                            $work_time = date('Y-m-d H:i:s',strtotime($add_time,strtotime($start)));
                            

                            $bg = '';
                            $dif_day = different_day($work_time);
                            if ($dif_day == 1) 
                                $bg = 'background: yellowgreen; border: 1px solid;';
                            elseif($dif_day == 2)
                                $bg = 'background: #a7fddb; border: 1px solid;';
                            elseif($dif_day >= 3)
                                $bg = 'background: #faff92; border: 1px solid;';
                            else
                                $bg = 'background: #b1cfff; border: 1px solid;';

                            



                            ?>
                            <li 
                                work-time="<?php echo $value->current_time ?>"
                                class="open_modal"  
                                order-id="<?php echo $valueo->order_id ?>" 
                                section-id="<?php echo $value->id ?>"  
                                data-toggle="modal" 
                                data-target="#modal-default2" 
                                style="<?php echo $bg ?>;position: relative; <?php echo $style; ?>">
                                <div class="box-day">
                                    <?php echo ((different_day($work_time) > 0) ? different_day($work_time) : '') ?>
                                </div>
                                <b title="Buyurtma nomi">
                                    <i class="text-info fa fa-reorder"></i>
                                    <?php echo (($valueo->order) ? $valueo->order->title : '') ?>
                                </b>    
                                <hr class="m-1">
                                <p title="Mijoz Ismi">
                                    <i class="text-info fa fa-user"></i>
                                    <?php echo (($valueo->order) ? base64_decode($valueo->order->client->full_name) : '') ?>
                                </p>

                                
                                    <?php if(!empty($valueo->order->categories)): ?>
                                        <?php foreach ($valueo->order->categories as $keyv => $valuev): ?>
                                            <p title="Kategoriya nomi">
                                                <i class="text-info fa fa-bookmark-o"></i>
                                                <?php echo $valuev->category->title ?>
                                            </p>

                                        <?php endforeach ?>
                                    <?php endif; ?>

                                <p title="Filial nomi">
                                    <i class="text-info fa fa-bank"></i>
                                    <?php echo (($valueo->order) ? $valueo->order->branch->title : '') ?>
                                </p>
                                <hr class="m-1">
                                <b title="Buyurtma bitish sanasi">
                                    <i class="text-info fa fa-hourglass-3"></i>
                                    <?php echo

                                    (($valueo->order) ? date("d-m-Y H:i",strtotime(date($valueo->order->dead_line)) ) : '') 
                                    
                                     ?>
                                </b> 
                                <hr class="m-1">
                                <?php if (isset($valueo->order) && $valueo->order->parralel == 1): ?>
                                    <span class="label label-warning">
                                        Shponli buyurtma
                                    </span>    
                                <?php endif ?>
                            </li>        
                        <?php endforeach ?>
                    <?php endif ?>
                    
                </ul>
                <?php if ($i == 1): ?>
                    
                <?php
                    if (Yii::$app->user->can('Admin')){ ?>
                        <button class="add-card-btn btn2 add_order"
                                section-id="<?php echo $value->id ?>"
                                data-toggle="modal"
                                data-target="#modal-default3"
                        >
                            <i class="fa fa-plus"></i> Buyurtma qo`shish
                        </button>
                    <?php }
                ?>
                <?php endif ?>
            </div>
        <?php $i++; endforeach ?>
    <?php endif ?>

</section>



<?php


$js = <<<JS
    $(function(){
        $(".sidebar-toggle").trigger("click");
    })

    // open_modal
    $(document).on("click",".open_modal",function(){
        $('#modal-default2 .modal-body').html(`<div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>`)
        let order_id = $(this).attr("order-id")
        let section_id = $(this).attr("section-id")

        $.ajax({
            url: '/index.php/orders/open-modal',
            dataType: 'json',
            type: 'GET',
            data:{
                order_id: order_id,
                section_id: section_id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    $('#modal-default2 .modal-body').html(response.content)
                    $('#modal-default2 .modal-header').html(response.header)
                    
                    if(!response.order_control){
                        $('#modal-default2 .modal-footer .btn-primary').addClass('btn-danger')
                        $('#modal-default2 .modal-footer .btn-danger').removeClass('btn-primary')
                        $('#modal-default2 .modal-footer .btn-danger').addClass('order_end')
                        $('.order_end').removeClass('save_order')
                        $('.order_end').removeClass('order_start')
                        $('.order_end').attr('order-id',order_id)
                        $('.order_end').attr('section-id',section_id)
                        $('.order_end').text('Buyurtma bitdi')
                    }
                    else{
                        $('#modal-default2 .modal-footer .btn-danger').addClass('btn-primary')
                        $('#modal-default2 .modal-footer .btn-primary').removeClass('btn-danger')
                        $('#modal-default2 .modal-footer .btn-primary').addClass('order_start')
                        $('.order_start').removeClass('save_order')
                        $('.order_start').removeClass('order_end')
                        $('.order_start').attr('order-id',order_id)
                        $('.order_start').attr('section-id',section_id)
                        $('.order_start').text('Buyurtmani boshlash')
                        $('.order_start').attr('data-id',response.order_control)
                    }

                }
            }
        });

    });

    $(document).on("click",".order_start",function(){
        
        if(confirm('Buyurtmaga start berilishini tasdiqlaysizmi ?')){
            let _this = $(this)
            $(_this).prop('disabled',true);
            let order_id = $(this).attr("order-id");
            let id = $(this).attr('data-id')

            $.ajax({
                url: '/index.php/orders/start-order',
                dataType: 'json',
                type: 'GET',
                data: {order_id: order_id,id: id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()
                    }
                    if(response.status == 'failure'){
                        $(_this).prop('disabled',false);
                        alert('Ma`lumot saqlash xatolik!')
                    }   
                }
            });
        }
        else{
            $(_this).prop('disabled',false);
        }
        

    })



    // order_end
    $(document).on("click",".order_end",function(){
        var _this = $(this)
        $(_this).prop('disabled',true)
        let order_id = $(this).attr("order-id")
        let section_id = $(this).attr("section-id")


        if(confirm('Ushbu buyurtmani tugatishni istaysizmi ?')){
            $.ajax({
                url: '/index.php/orders/order-end',
                dataType: 'json',
                type: 'GET',
                data:{
                    order_id: order_id,
                    section_id: section_id,
                },
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()
                    }
                    else{
                        alert('Ma`lumot saqlashda xatolik');
                        $(_this).prop('disabled',false)
                    }
                }
            });
        }
        else{
            $(_this).prop('disabled',false)
        }
    });

    // add_order
    $(document).on("click",".add_order",function(){
        let section_id = $(this).attr("section-id")

        $.ajax({
            url: '/index.php/orders/add-order-modal',
            dataType: 'json',
            type: 'GET',
            data:{
                section_id: section_id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    $('#modal-default3 .modal-body').html(response.content)
                    $('#modal-default3 .modal-header').html(response.header)
                    $('#modal-default3 .modal-footer .btn-success').addClass('save_order')
                    $('.save_order').removeClass('order_end')
                    $('.save_order').attr('section-id',section_id)
                    $('.save_order').text('Saqlash')
                    
                }
            }
        });

    });


    $(document).on("change","#branch",function(){
        let branch_id = $(this).val()

        $.ajax({
            url: "/index.php/orders/branch",
            dataType: "json",
            type: "GET",
            data:{branch_id: branch_id},
            success: function (response) {
                if (response.status == "success") {
                        $("#client").prop("disabled",false)
                        $("#client").html(response.content)
                        $("#category").prop("disabled",false)
                        $("#category").html(response.content2)
                    }
                    else if(response.status = "failure_branch"){
                        $("#client").prop("disabled",true)
                        $("#client").html('')
                        $("#category").prop("disabled",true)
                        $("#category").html('')
                        alert("Tanlanga filial uchun mijozlar biriktirilmagan!")
                    }
                    else if(response.status = "failure_category"){
                        $("#client").prop("disabled",true)
                        $("#client").html('')
                        $("#category").prop("disabled",true)
                        $("#category").html('')
                        alert("Tanlanga filial uchun kategoriyalar biriktirilmagan!")
                    }
            }
        });
        
    })


    $(document).on("click","#plus",function(){
        let i = parseInt($(this).attr('data-count'));
        let section_id = $(this).attr('section-id');
        $("#plus").attr('data-count',i+1)
        $.ajax({
            url: '/index.php/orders/add-section',
            dataType: 'json',
            type: 'GET',
            data: {i: i,section_id: section_id},
            success: function (response) {
                if (response.status == 'success') {
                    $("#sections_order").append(response.content)
                    
                }   
            }
        });

    })

    $(document).on("click","#minus",function(){
        let leng = $("#sections_order .row").length
        let i = parseInt($('#plus').attr('data-count'));
        if(leng > 1){
            $("#sections_order .row").last().remove() 

        }

        if(i > 2){
            $("#plus").attr('data-count',i-1)
        }
        
        
    })

    // save_order
    $(document).on("click",".save_order",function(){
        const thisEl = $(this)
        thisEl.prop("disabled",true)

        $.ajax({
            url: '/index.php/orders/save-order',
            dataType: 'json',
            type: 'GET',
            data: $("#form").serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload()
                }
                if(response.status == 'failure_empty'){
                    alert('Buyurtma bo`limlari yoki ish vaqtlari bo`sh bo`lishi mumkin emas!')
                    thisEl.prop("disabled",false)
                }
                if(response.status == 'failure_title'){
                    $('#order_title').parent().addClass('has-error')
                    $('.error_title').removeClass('hidden')
                    thisEl.prop("disabled",false)
                }
                if(response.status == 'failure_branch'){
                    $('#branch').parent().addClass('has-error')
                    $('.error_branch').removeClass('hidden')
                    thisEl.prop("disabled",false)
                }  
                if(response.status == 'failure_client'){
                    $('#client').parent().addClass('has-error')
                    $('.error_client').removeClass('hidden')
                    thisEl.prop("disabled",false)
                }
                if(response.status == 'failure_category'){
                    $('#category').parent().addClass('has-error')
                    $('.error_category').removeClass('hidden')
                    thisEl.prop("disabled",false)
                } 
                if(response.status == 'failure_end_date'){
                    $('#end_date').parent().addClass('has-error')
                    $('.error_end').removeClass('hidden')
                    thisEl.prop("disabled",false)
                }
                if(response.status == 'failure_section'){
                    alert('Bo`lim va ish vaqti kiritilmadi !')
                    thisEl.prop("disabled",false)
                }      
            }
        });

    })


    // pause
    $(document).on("click","#pause",function(){
        if(confirm("Buyurtmani vaqtinchalik to'xtatmoqchimisiz ?")){
            let id = $(this).attr('order-id')

            $.ajax({
                url: '/index.php/orders/pause',
                dataType: 'json',
                type: 'GET',
                data: {id: id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()
                    }   
                }
            });
        }
    })
    
    // events
    $(document).ready(function(){ 
        $(".sidebarr").fadeIn("slow");
    });

    // active
    $(document).on("click","#active",function(){

        if(confirm("Buyurtmani faollashtirmoqchimisiz ?")){
            let id = $(this).attr('order-id')
            let pause_id = $(this).attr('pause-id')

            $.ajax({
                url: '/index.php/orders/active',
                dataType: 'json',
                type: 'GET',
                data: {id: id,pause_id: pause_id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()
                    }   
                }
            });
        }
        

    })


    $(document).on('change','#section',function(){
        let id = $(this).val();
        var _this = $(this)

        $.ajax({
            url: '/index.php/orders/find-time',
            dataType: 'json',
            type: 'GET',
            data: {id: id},
            success: function (response) {
                if (response.status == 'success') {
                    $(_this).parent().parent().find('.time').val(response.content)
                }   
            }
        });

    })


    // delete_order
    $(document).on('click','.delete_order',function(){

        if(confirm('Buyurtmani o`chirishga ishonchingiz komilmi ?')){
            let id = $(this).attr('order-id');

            $.ajax({
                url: '/index.php/orders/delete',
                dataType: 'json',
                type: 'GET',
                data: {id: id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }   
                }
            });
        }
        

    })


    // back_order
    $(document).on('click','.back_order',function(){
        var _this = $(this);
        _this.attr('disabled',true)

        if(confirm('Buyurtmani ortga qaytarmoqchimisiz ?')){
            let id = $(this).attr('order-id');

            $.ajax({
                url: '/index.php/orders/back-order',
                dataType: 'json',
                type: 'GET',
                data: {id: id},
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }
                    if(response.status == 'failure_back'){
                        alert('Bu bo`limdan buyurtmani ortga qaytarib bo`lmaydi')
                    }   
                }
            });
        }
        else{
            _this.attr('disabled',false)
        }
    })


    // save_desc
    $(document).on('click','#save_desc',function(){
        let desc = $('#desc').val()
        let id = $(this).attr('data-id')

        $.ajax({
            url: '/index.php/orders/change-desc',
            dataType: 'json',
            type: 'GET',
            data: {desc: desc,id: id},
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
                if(response.status == 'failure'){
                    alert('Xatolik! Buyurtma topilmadi!')
                }
                if(response.status == 'failure_save'){
                    alert('Xatolik! Buyurtma izohini o`zgartirilmadi!')
                }   
            }
        });
        
    })

JS;


$this->registerJs($js);
?>