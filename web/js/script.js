$(document).on("click",".product_expense",function(){
  let id = $(this).attr("data-id");
        $.ajax({
          type: 'GET',
          url: '/product/proexpense',
          data:{id:id},
          success: function(response){
            if(response.status == 'success'){
                $('#exampleModal .modal-header h5').text(response.header);
                $('#exampleModal .modal-body').html(response.content);
                $('#exampleModal .modal-footer .btn-primary').css("display","none");
                $('#exampleModal .modal-footer .btn-secondary').css("margin-right","70px");
                $("#exampleModal").find('.btn-success').attr("data-id",id)

            }
            
          }
  })
})

$(document).on("click", ".product_yarim", function(){
  let id = $(this).attr("data-id");
  $.ajax({
    type: 'GET',
    url: '/product/proyarim',
    data:{id:id},
    success: function(response){
      if(response.status == 'failure'){
          $('#exampleModal .modal-header h5').text(response.header);
          $('#exampleModal .modal-body').html(response.content);
          $('#exampleModal .modal-footer .btn-primary').css("display","none");
          $('#exampleModal .modal-footer .btn-secondary').css("margin-right","70px");
          $("#exampleModal").find('.btn-success').attr("data-id",id);
      }
    }
  });
});

$(document).on("click",".save_pro_expense",function(){
        $.ajax({
          type: 'POST',
          url: '/product/proexpense',
          data:$("#create_expense").serialize(),
          success: function(response){
            if(response.status == 'success'){
                $('#exampleModal .modal-header h5').text(response.header);
                $('#exampleModal .modal-body').html(response.content);
                $('#exampleModal .modal-footer .btn-primary').css("display","none");
                $('#exampleModal .modal-footer .btn-secondary').css("margin-right","70px");

            }
            if(response.status == 'success2'){
                location.reload()

            }
            
          }
  })
})

$(document).on("click",".save_pro_yarim",function(){
    $.ajax({
        type: 'POST',
        url: '/product/proyarim',
        data:$("#create_expense").serialize(),
        success: function(response){
          if(response.status == 'success'){
              location.reload();
          }
        }
    });
});

$(document).on("click",".update",function(){
  $(this).siblings(".close").addClass("hide")
  $(this).parent().parent().addClass("current")
  let ex_d = $(this).attr("data-ed")
  let cost = $(this).siblings(".form-control").val()
  let ex = $(this).attr("data-ex")

  
  
  $.ajax({
        url: '/expense-daily/updatedata',
        type: 'GET',
        dataType: 'json',
        data:{ex_d,cost,ex},
        success: function(response) {
          if(response.status == 'success')
          {
            $(".current").html(response.content);
            new Toast({
              message: "Xarjat qiymati muvaffaqiyatli amalga oshirildi!",
              type: 'danger'});

            $(function(){
              setTimeout(function(){ $(".toastjs-container").attr("aria-hidden",true); }, 2000);
            })
              
          }
          else{
            alert('Xatolik!')
          }
            
        },
        error: function (data) {
        
        }
          
          
    });


  
})

$(document).on("click",".change",function(){
  $(this).siblings(".close").removeClass("hide")
  $(this).addClass("hide")
  $(this).siblings(".form-control").removeClass("hide")
  $(this).siblings(".update").removeClass("hide")
  $(this).siblings(".cost").addClass("hide")
})

$(document).on("change","#date",function(){
  let date = $(this).val()

  $.ajax({
        url: '/expense-daily/updatedate',
        type: 'GET',
        dataType: 'json',
        data:{date},
        success: function(response) {
          if(response.status == 'success')
          {
            $("#expense_data").html(response.content);
          }
          else{
            alert('Xatolik!')
          }
            
        },
        error: function (data) {
        
        }
          
          
    });
})




$(document).on("click",".info",function(){
  $("#exampleModal .modal-body").html('')
  let id = $(this).attr("data-id")
  
        $.ajax({
          type: 'GET',
          url: '/expense-daily/info',
          data:{id:id},
          success: function(response){
            if(response.status == 'success'){
                $('#exampleModal .modal-header h5').text(response.header);
                $('#exampleModal .modal-body').html(response.content);
                $('#exampleModal .modal-footer .btn-primary').css("display","none");
               

            }
            
          }
  })
})

$(document).on("click",".close",function(){
  $(this).addClass("hide")
  $(this).siblings(".form-control").addClass("hide")
  $(this).siblings(".update").addClass("hide")
  $(this).siblings(".cost").removeClass("hide")
  $(this).siblings(".change").removeClass("hide")
        
})


$(document).ready(function(){
  $('.select2').select2()
})


$(document).on('change','#first-select',function(){
  let first = $(this).val()
  if(first != 0){
    $.ajax({
      type: 'GET',
      url: '/model/filter',
      data:{first:first},
      success: function(response){
        if(response.status == 'success'){
            $('#second-select').html(response.content);
            // $('#unit').html(response.content2);
            // $('#used-units').html(response.content3);
            // if(response.content4 == ''){
            //   $('#used-units2').hide()
            //   $('.first_').attr('colspan','2')
            // }
            // else{

            //   $('#used-units2').show()
            //   $('.first_').removeAttr('colspan')
            // }
            // $('#used-units2').html(response.content4);
           
           

        }
      }
    })
  }
})


$(document).on('change','#second-select',function(){
  var mat_id = $(this).val()

  $.ajax({
      type: 'GET',
      url: '/model/filter3',
      data:{mat_id:mat_id},
      success: function(response){
        if(response.status == 'success'){
          $('#unit').html(response.content2);
          $('#used-units').html(response.content3);
          $('#used-units2').html(response.content4);
        }
      }
    })

})


$(document).on('click','.plus-xom',function(){
  let modelId = $(this).attr('data-id')
  let materialId = $('#second-select').val()
  let quantity = $('#quantity-model').val()
 
  
  const used_unit = $( ".used-input:checked" ).attr('data-id');
  
  
  if(materialId != '' && modelId != '' && quantity != ''){
    if ($('input.used-input').is(':checked')) {
      $.ajax({
        type: 'GET',
        url: '/model/addrow',
        data:{
          modelId: modelId,
          materialId: materialId,
          quantity: quantity,
          used_unit: used_unit,
        },
        success: function(response){
          if(response.status == 'success'){
              $('.data tbody').html(response.content);

          }
        }
      })
    }
    else{
      alert('Фойдаланиш ўлчовини танланг!')
    }
    
  }
  else{
    alert("Барча майдонларни тўлдиринг!")
  }
})

$(document).on('change','.used-input',function(){
    if ($(this).is(':checked')) {
      let name = $(this).parent().siblings('.parent_name').text()
      let data_name = $(this).attr('data-name')
      $('#unit').text(name)
      $('#unit').text(data_name)
    }
    
    
  
})

$(document).on('click','#add_session',function(){
  let materialId = $('#second-select').val()
  let quantity = $('#quantity-model').val()
  var used_unit = $( ".used-input:checked" ).attr('data-id');
  var used_name = $( ".used-input:checked" ).attr('data-name');
  var model_id = $(this).attr('data-id')

  if(used_unit == undefined){
    used_unit = 0
  }
  if(used_name == undefined){
    used_name = ''
  }

  if(materialId != null  && quantity != ''){
    $.ajax({
      type: 'GET',
      url: '/orders/addmaterial',
      data:{
        materialId: materialId,
        quantity: quantity,
        used_unit: used_unit,
        used_name: used_name,
        model_id: model_id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('.data').html(response.content);

        }
      }
    })
  }
  else{
    alert("Барча майдонларни тўлдиринг!")
  }
})


$(document).on('click','#add_session_expense',function(){
  let expense_id = $('#expense-select').val()
  let quantity = $('#quantity-model-expense').val()
  let count = $('#count').val()
  let model_id = $(this).attr('data-id')

  if(expense_id == undefined){
    expense_id = 0
  }
  if(quantity == undefined){
    quantity = ''
  }
  if(count == undefined){
    count = ''
  }

  if(expense_id != null  && quantity != '' && count != ''){
    $.ajax({
      type: 'GET',
      url: '/orders/addmaterialexpense',
      data:{
        expense_id: expense_id,
        quantity: quantity,
        count: count,
        model_id: model_id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('.data').html(response.content);

        }
      }
    })
  }
  else{
    alert("Барча майдонларни тўлдиринг!")
  }
})

$(document).on('click','#add_session_semi_expense',function(){
  let expense_id = $('#expense-select').val()
  let quantity = $('#quantity-model-expense').val()
  let count = $('#count').val()
  let model_id = $(this).attr('data-model')
  let order_product_id = $(this).attr('data-order-id')

  if(expense_id == undefined){
    expense_id = 0
  }
  if(quantity == undefined){
    quantity = ''
  }
  if(count == undefined){
    count = ''
  }

  if(expense_id != null  && quantity != '' && count != ''){
    $.ajax({
      type: 'GET',
      url: '/orders/addmaterialsemiexpense',
      data:{
        expense_id: expense_id,
        quantity: quantity,
        count: count,
        model_id: model_id,
        order_product_id: order_product_id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('#semi-models').html(response.content);

        }
      }
    })
  }
  else{
    alert("Барча майдонларни тўлдиринг!")
  }
})


$(document).on('click','#add_session_semi',function(){
  let materialId = $('#second-select-semi').val()
  let quantity = $('#quantity-model').val()
  var used_unit = $( ".used-input:checked" ).attr('data-id')
  var used_name = $( ".used-input:checked" ).attr('data-name')
  var order_id = $(this).attr('data-order-id')
  var model_id = $(this).attr('data-model')

  if(used_unit == undefined){
    used_unit = 0
  }
  if(used_name == undefined){
    used_name = ''
  }

  if(materialId != null  && quantity != ''){
    $.ajax({
      type: 'GET',
      url: '/orders/addmaterialsemi',
      data:{
        materialId: materialId,
        quantity: quantity,
        used_unit: used_unit,
        used_name: used_name,
        order_id: order_id,
        model_id: model_id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('#semi-models').html(response.content);

        }
      }
    })
  }
  else{
    alert("Барча майдонларни тўлдиринг!")
  }
})



$(document).on('click','.remove-row',function(){
  let detail_id = $(this).attr('data-id')
  var sum = parseFloat($(this).attr('data-sum'))
  var total = parseFloat($('.total').text())
  var current = total-sum
  var ddd = current.toFixed(2)
  $(this).parent().parent().addClass('remove')
  $.ajax({
      type: 'GET',
      url: '/model/removerow',
      data:{
        detail_id: detail_id,
      },
      success: function(response){
        if(response.status == 'success'){
          $('.remove').remove();
          $('.total').text(ddd);
        }
      }
    })
 
})

$(document).on('click','.remove-material',function(){
  let detail_id = $(this).attr('data-id')
  $(this).parent().parent().addClass('remove')
  $.ajax({
      type: 'GET',
      url: '/orders/removesession',
      data:{
        detail_id: detail_id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('.remove').remove();
           
           

        }
      }
    })
 
})

$(document).ready(function(){
  $('.datepicker_order').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })
})

$(document).on('change','#orderproducts-product_id',function(){
  let modelId = $(this).val()

  $.ajax({
      type: 'GET',
      url: '/orders/findmodels',
      data:{
        modelId: modelId,
      },
      success: function(response){
        if(response.status == 'success'){
            $('.data').html(response.content)

           
           

        }
      }
    })
})


$(document).on('click','.change-status',function(){
  let id = $(this).attr('data-id')
  if (confirm('Ушбу маҳсулотни сотишни истайсизми?')) {
    $.ajax({
      type: 'GET',
      url: '/order-products/changestatus',
      data:{
        id: id,
      },
      success: function(response){
        if(response.status == 'success'){
            window.location.reload();
        }
      }
    })
  }
  
 
})

$(document).on('click','.plus-tov',function(){
  let nak_id = $(this).attr('data-id')
  let materialId = parseInt($('#second-select').val())
  let quantity = $('#quantity-model').val()

  console.log(nak_id)
  console.log(materialId)
  console.log(quantity)

 

  if(materialId != NaN && nak_id != '' && quantity != null){
    $.ajax({
      type: 'GET',
      url: '/materials-history/addtov',
      data:{
        nak_id: nak_id,
        materialId: materialId,
        quantity: quantity
      },
      success: function(response){
        if(response.status == 'success'){
            $('.data tbody').html(response.content);
           
           

        }
        else if(response.status == 'failure'){
          alert("Барча майдонларни тўлдиринг!")
        }
      }
    })
  }
  else{
    alert("Барча майдонларни тўлдиринг!")
  }
})


// delete product in order
$(document).on('click','.btn-delete',function(){
  $(this).addClass("current")
  let order_product_id = $(this).attr('data-id')
  if(confirm('Бу маҳсулотни буюртмалар рўйхатидан ўчирмоқчимисиз?')){
    $.ajax({
      type: 'GET',
      url: '/orders/delete_product',
      data:{
        order_product_id: order_product_id,
      },
      success: function(response){
        if(response.status == 'success'){
          $(".current").parent().parent().remove()
            $.toast({
              heading: '',
              text: "Маҳсулот муваффақиятли ўчирилди!",
              hideAfter: 2000,
              position: 'top-right',
              loaderBg: '#3C763D',
              icon: 'success'
            });
        }
        else if(response.status == 'failure'){
          $.toast({
              heading: '',
              text: "Ушбу маҳсулотни буюртмадан ўчириш муддати тугаган!",
              hideAfter: 2000,
              position: 'top-right',
              loaderBg: 'rgba(255,0,0,.5)',
              bgColor: 'red',
              icon: 'danger'
            });  
        }
        else if(response.status == 'failure2'){
          $.toast({
              heading: '',
              text: "Ушбу маҳсулот бўш!",
              hideAfter: 2000,
              position: 'top-right',
              loaderBg: 'orange',
              bgColor: '#FF8000',
              icon: 'warning'
            });  
        }
      }
    })
  }
})


  // delete semiproduct in order
$(document).on('click','.btn-delete-semi',function(){
  $(this).addClass("current")
  let order_product_id = $(this).attr('data-id')
  if(confirm('Бу маҳсулотни буюртмалар рўйхатидан ўчирмоқчимисиз?')){
    $.ajax({
      type: 'GET',
      url: '/orders/delete_product_semi',
      data:{
        order_product_id: order_product_id,
      },
      success: function(response){
        if(response.status == 'success'){
          $(".current").parent().parent().remove()
            $.toast({
              heading: '',
              text: "Маҳсулот муваффақиятли ўчирилди!",
              hideAfter: 2000,
              position: 'top-right',
              loaderBg: '#3C763D',
              icon: 'success'
            });
        }
        else if(response.status == 'failure'){
          $.toast({
              heading: '',
              text: "Ушбу маҳсулотни буюртмадан ўчириш муддати тугаган!",
              hideAfter: 2000,
              position: 'top-right',
              loaderBg: 'rgba(255,0,0,.5)',
              bgColor: 'red',
              icon: 'danger'
            });  
        }
        else if(response.status == 'failure2'){
          $.toast({
              heading: '',
              text: "Ушбу маҳсулот бўш!",
              hideAfter: 2000,
              position: 'top-right',
              loaderBg: 'orange',
              bgColor: '#FF8000',
              icon: 'warning'
            });  
        }
      }
    })
  }
  
})

$(document).on('click','.sell_product',function(){
  const id = parseInt($(this).attr('data-id'))
  
  $.ajax({
      type: 'GET',
      url: '/order-products/sellmodal',
      data:{
        id: id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('#exampleModal .modal-header').html(response.header);
            $('#exampleModal .modal-body').html(response.content);
            $('#exampleModal .modal-footer .btn-primary').text('Сотиш');
            $('#exampleModal .modal-footer .btn-secondary').text('Бекор қилиш');
            $('#exampleModal .modal-footer .btn-primary').addClass('sell');
            $('.sell').attr('data-id',response.id);

        }
        
      }
    })
})

$(document).on('click','.sell',function(){
  const id = parseInt($(this).attr('data-id'))
  const count = $('#count_product').val()
  const client_id = $("#client").val()
  const total = $("#total").val()
  // console.log(price)
  $.ajax({
      type: 'GET',
      url: '/order-products/sellproduct',
      data:{
        id: id,
        count: count,
        client_id: client_id,
        total: total,
      },
      success: function(response){
        if(response.status == 'success'){
            window.location.reload()
        }
        if(response.status == 'failure'){
          alert('Сиз киритган сон омбордаги қолдиқдан кўп!')
          $('.sell').prop('disabled',false)
        }
        if(response.status == 'null'){
          alert('Бу маҳсулот омборда қолмаган!')
          $('.sell').prop('disabled',false)
        }
      }
    })
})

// yarim tayyor mahsulotni sotish modali
$(document).on('click','.sell_semiproduct',function(){
  const id = parseInt($(this).attr('data-id'))
  
  $.ajax({
      type: 'GET',
      url: '/order-products/sellsemimodal',
      data:{
        id: id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('#exampleModal .modal-header').html(response.header);
            $('#exampleModal .modal-body').html(response.content);
            $('#exampleModal .modal-footer .btn-primary').text('Сотиш');
            $('#exampleModal .modal-footer .btn-secondary').text('Бекор қилиш');
            $('#exampleModal .modal-footer .btn-primary').addClass('sellsemi');
            $('.sellsemi').attr('data-id',response.id);

        }
        
      }
    })
})

// yarim tayyor mahsulotni sotish
$(document).on('click','.sellsemi',function(){
  const id = parseInt($(this).attr('data-id'))
  const count = $('#count_product').val()
  const client_id = $("#client").val()
  const total = $("#total").val()
  
  $.ajax({
      type: 'GET',
      url: '/order-products/sellsemiproduct',
      data:{
        id: id,
        count: count,
        client_id: client_id,
        total: total,
      },
      success: function(response){
        if(response.status == 'success'){
            window.location.reload()
        }
        if(response.status == 'failure'){
          alert('Сиз киритган сон омбордаги қолдиқдан кўп!')
          $('.sell').prop('disabled',false)
        }
        if(response.status == 'null'){
          alert('Бу маҳсулот омборда қолмаган!')
          $('.sell').prop('disabled',false)
        }
      }
    })
})


// plus_count
$(document).on('click','.plus_count',function(){
  var count = parseInt($('#count_product').val())
  var max = parseInt($('#count_product').attr('max'))
  var price = parseInt($(this).attr('data-sum'))
  var new_price = parseInt($('#total').val())
  if(count < max){
    count++
    new_price = count*price  
  }
  $('#count_product').val(count)
  $('#total').val(new_price)
})

// minus_count
$(document).on('click','.minus_count',function(){
  var count = parseInt($('#count_product').val())
  var min = parseInt($('#count_product').attr('min'))
  var price = parseInt($(this).attr('data-sum'))
  var new_price = parseInt($('#total').val())
  if(count > min){
    count--
    new_price = new_price-price  
  }
  $('#count_product').val(count)
  $('#total').val(new_price)
})

$(document).on('click','.null-count',function(){
  alert('Бу маҳсулот омборда қолмаган!')
})

// semi-ready
$(document).on('click','#semi-ready',function(){
  const order_id = parseInt($(this).attr('data-id'))
 
  $.ajax({
      type: 'GET',
      url: '/orders/semiready',
      data:{
        order_id: order_id,
      },
      success: function(response){
        if(response.status == 'success'){
            $('#tab_2 .row').html(response.content)
        }
      }
    })
})


$(document).on('click','.add_expense',function(){
  let expense = $('#expense').val()
  let expense_sum = $('#expense_sum').val()
  let expense_unit = $('#expense_unit').val()
  let expense_count = $('#expense_count').val()
  let model_id = $(this).attr('data-id')


  if (expense != '' && expense_sum != '' && expense_unit != '' && expense_count != '') {
      $.ajax({
        url: '/model/add-expense',
        type: 'GET',
        dataType: 'json',
        data:{
          expense: expense,
          expense_sum: expense_sum,
          model_id: model_id,
          expense_unit: expense_unit,
          expense_count: expense_count,
        },
        success: function(response){
          if(response.status == 'success'){
            window.location.reload()
          }
        }
      })
    }
    else{
      alert('Харажат тури ва қийматларини киритинг!')
    }

})

$(document).on('click','.edit_expense',function(){
  $('#exampleModal .modal-title').html('')
  $('#exampleModal .modal-body').html('')
  let id = $(this).attr('data-id')

  $.ajax({
    url: '/model/edit-expense',
    type: 'GET',
    dataType: 'json',
    data:{
      id: id,
    },
    success: function(response){
      if(response.status == 'success'){
        $('#exampleModal .modal-title').html('Харажат қийматларини ўзгартириш')
        $('#exampleModal .modal-body').html(response.content)
        $('#exampleModal .modal-footer .btn-primary').addClass('save_edit_expense')

      }
    }
  })
})

$(document).on('click','.save_edit_expense',function(){
  let expense_id = $('#expense_id').val()
  let expense_sum = $('#expense_sum_modal').val()
  let expense_unit = $('#expense_unit_modal').val()
  let expense_count = $('#expense_count_modal').val()

  $.ajax({
    url: '/model/save-expense',
    type: 'GET',
    dataType: 'json',
    data:{
      expense_id: expense_id,
      expense_sum: expense_sum,
      expense_unit: expense_unit,
      expense_count: expense_count,
    },
    success: function(response){
      if(response.status == 'success'){
        window.location.reload();
      }
      if(response.status == 'failure_count'){
        $('.red_block1').css('display','block')
      }
      if(response.status == 'failure_sum'){
        $('.red_block2').css('display','block')
      }
    }
  })

})


$(document).on('click','.delete_expense',function(){
  let expense_id = $(this).attr('data-expense-id')
  let model_id = $(this).attr('data-model')
  let id = $(this).attr('data-id')

  if(confirm('Ушбу харажатни ўчиримоқчимисиз?')){
    $.ajax({
      url: '/model/delete-expense',
      type: 'GET',
      dataType: 'json',
      data:{
        expense_id: expense_id,
        model_id: model_id,
        id:id
      },
      success: function(response){
        if(response.status == 'success'){
          window.location.reload();
        }
      }
    })
  }

})



function goBack() {
  window.history.back()
}


