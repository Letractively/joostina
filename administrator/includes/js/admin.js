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
    } );

// все поля типа textarea делаем растягиваемыми
//$('textarea').TextAreaResizer();

});

/**
* Включение - выключение визуального редактора
*/
function jtoggle_editor(){
    var jeimage = $('#jtoggle_editor');
    jeimage.attr('src','images/aload.gif');

    $.ajax({
        url: 'ajax.index.php?option=com_admin&task=toggle_editor',
        dataType: 'json',
        // обрабатываем результат
        success: function( data ){
            jeimage.attr('src', image_path + data.image ).attr('alt', data.text );
        }
    });

    return true;
}

// TODO переписать на Jquery
function writeDynaList( selectParams, source, key, orig_key, orig_val ) {
    var html = '<select ' + selectParams + '>';
    var i = 0;
    for (x in source) {
        if (source[x][0] == key) {
            var selected = '';
            if ((orig_key == key && orig_val == source[x][1]) || (i == 0 && orig_key != key)) {
                selected = 'selected="selected"';
            }
            html += '\n<option value="'+source[x][1]+'" '+selected+'>'+source[x][2]+'</option>';
        }
        i++;
    }
    html += '\n</select>';

    document.writeln( html );
}

