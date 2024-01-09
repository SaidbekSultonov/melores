$(function(){
    $('.modalButton').click(function(){
    	console.log($(this).attr('value'));
        $('#modalDelay').modal('show').find('#modalContent').load($(this).attr('value'));
    });
});