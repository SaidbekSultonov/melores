<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kategoriyalar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-services-index">
    <div class="row">
        <div class="col-md-6">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-md-6">
            <h2>
                <span class="btn btn-success pull-right" id="open-modal-services" data-toggle="modal" data-target="#modal-default">
                    Qo'shish
                </span>
            </h2>
        </div>
    </div>
    <table class="table table-bordered table-striped">
        <tr>
            <th># Tartibi</th>
            <th>Nomi UZ</th>
            <th>Nomi RU</th>
            <th>Holati</th>
            <th>Jarayon</th>
        </tr>
        <?php if (!empty($model)): ?>
            <?php foreach ($model as $key => $value): ?>
                <tr>
                    <td>
                        <select style="width: 100px;" data-id="<?php echo $value->id ?>" class="form-control order">
                            <?php foreach ($orders as $key2 => $value2): ?>
                                <option <?php echo (($key2 == $value->order_column) ? "selected" : "") ?> value="<?php echo $key2 ?>">
                                    <?php echo $value2 ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td><?php echo $value->title_uz ?></td>
                    <td><?php echo $value->title_ru ?></td>
                    <td>
                        <?php if ($value->type == 1): ?>
                            <span class="label label-success">Faol</span>
                        <?php else: ?>
                            <span class="label label-danger">Nofaol</span>
                        <?php endif ?>
                    </td>
                    <td>
                        <a href="#" data-id="<?php echo $value->id; ?>" id="update-services" data-toggle="modal" data-target="#modal-default" class="glyphicon glyphicon-pencil"></a>

                        <a href="/index.php/sale-services/delete?id=<?php echo $value->id; ?>" id="delete-services" class="glyphicon glyphicon-trash"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">
                    <center>Yo'nalishlar kiritilmagan!</center>
                </td>
            </tr>
        <?php endif ?>
    </table>

</div>

<script>

    $(function(){
        $('select').select2()

        $('select').change('select2:select', function (e) {
            let id = $(this).attr('data-id')
            let order = $(this).val()

            $.ajax({
                url: '/index.php/sale-services/order',
                dataType: 'json',
                type: 'GET',
                data: {
                    id: id,
                    order: order,
                },
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload()

                    }
                }
            });

        })
    })
    $(document).on('click','#open-modal-services',function(){
        $('.modal-body').html('')
        $('.modal-footer .btn-primary').removeClass().addClass('btn btn-primary save-services')

        $.ajax({
            url: '/index.php/sale-services/create',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer .btn-primary").addClass("save-services")

                }
            }
        });
    })

    // save-services
    $(document).on('click','.save-services',function(){
        var this_el = $(this)
        this_el.prop('disabled',true)
        let services_uz = $('#services_name_uz').val()
        let services_ru = $('#services_name_ru').val()

        $.ajax({
            url: '/index.php/sale-services/save-services',
            dataType: 'json',
            type: 'GET',
            data:{
                services_uz: services_uz,
                services_ru: services_ru
            },
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload()
                }
                if(response.status == 'failure_uz'){
                    $('#services_name_uz').css('border-color','#dd4b39')
                    $('#services_name_uz').siblings('.p1').html(`<small style="color: #dd4b39;">Maydon to'ldirilishi shart!</small>`)
                    this_el.prop('disabled',false)
                }
                if(response.status == 'failure_ru'){
                    $('#services_name_ru').css('border-color','#dd4b39')
                    $('#services_name_ru').siblings('.p2').html(`<small style="color: #dd4b39;">Maydon to'ldirilishi shart!</small>`)
                    this_el.prop('disabled',false)
                }
            }
        });


    })

    // update-services
    $(document).on('click','#update-services',function(){
        $('.modal-body').html('')
        $('.modal-footer .btn-primary').removeClass().addClass('btn btn-primary update-save-services')

        let id = $(this).attr('data-id')

        $.ajax({
            url: '/index.php/sale-services/update',
            dataType: 'json',
            type: 'GET',
            data: {id: id},
            success: function (response) {
                if (response.status == 'success') {
                    $("#modal-default .modal-body").html(response.content)
                    $("#modal-default .modal-header").html(response.header)
                    $("#modal-default .modal-footer .btn-primary").addClass("update-save-services")
                    $('.update-save-services').attr('data-id',id)

                }
            }
        });
    })


    // update-save-services
    $(document).on('click','.update-save-services',function(){
        var this_el = $(this)
        this_el.prop('disabled',true)
        let services_uz = $('#services_name_uz').val()
        let services_ru = $('#services_name_ru').val()
        let id = $(this).attr('data-id')

        $.ajax({
            url: '/index.php/sale-services/update-save',
            dataType: 'json',
            type: 'GET',
            data:{
                services_uz: services_uz,
                services_ru: services_ru,
                id: id
            },
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload()
                }
                if(response.status == 'failure_uz'){
                    $('#services_name_uz').css('border-color','#dd4b39')
                    $('#services_name_uz').siblings('.p1').html(`<small style="color: #dd4b39;">Maydon to'ldirilishi shart!</small>`)
                    this_el.prop('disabled',false)
                }
                if(response.status == 'failure_ru'){
                    $('#services_name_ru').css('border-color','#dd4b39')
                    $('#services_name_ru').siblings('.p2').html(`<small style="color: #dd4b39;">Maydon to'ldirilishi shart!</small>`)
                    this_el.prop('disabled',false)
                }
            }
        });
    })
</script>