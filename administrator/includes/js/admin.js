// JS функции панели управления
$(document).ready(function() {
    // скрываем индиктор загрузки
    $('#ajax_status').hide();

    // клики на ячейки и значки смены статуса
    $('.adminlist .td-state').live('click', function(){
        // объект по которому производится клик
        var current_obj = $(this);
        var option = $('img',this).attr('obj_option') ? $('img',this).attr('obj_option') : _option;
        $.ajax({
            url: 'ajax.index.php?option='+option,
            type: 'post',
            data:{
                obj_id:       $('img',this).attr('obj_id'),
                task:    $('img',this).attr('obj_task')
            },
            dataType: 'json',
            // обрабатываем результат
            success: function( data ){
                $( 'img' ,current_obj ).attr('src',image_path + data.image );
                $( 'img' ,current_obj ).attr('alt',data.mess );
                $( 'img' ,current_obj ).attr('title',data.mess );
            }
        });
    } )
});