$(document).ready(function(){
	$('.to_bookmarks').live('click', function(){

		$.ajax({
			url: _live_site + "/ajax.index.php",
			type: 'post',
			data:{
				obj_option: $(this).attr('_obj_option'),
				obj_id: $(this).attr('_obj_id'),
				task : 'add',
				option: 'com_bookmarks'
			},
			dataType: 'json',
			success: function( data ){
				if(!data){
					$.prompt('Что-то пошло не так...');
					return false;
				}else if(data.error){
					$.prompt(data.error);
					return false;
				}
				else{
					$.prompt(data.message);
				}
			}
		});

		return false;
	})
})