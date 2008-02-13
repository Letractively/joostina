<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );


$_MAMBOTS->registerFunction( 'onBeforeDisplayContent', 'pluginJWAjaxVote' );

function pluginJWAjaxVote( &$row, &$params, $page=0 ) {
	global $Itemid, $task, $database, $mainframe, $addScriptJWAjaxVote,$_MAMBOTS,$mosConfig_caching;

	if ( !isset($_MAMBOTS->_content_mambot_params['plugin_jw_ajaxvote']) ) {
		$query = "SELECT id FROM #__mambots WHERE element = 'plugin_jw_ajaxvote' AND folder = 'content'";
		$database->loadObject($mambot);
		$_MAMBOTS->_content_mambot_params['plugin_jw_ajaxvote'] = $mambot;
	}
	$param =& new mosParameters( $_MAMBOTS->_content_mambot_params['plugin_jw_ajaxvote'] );
	$language = $param->get('language', 'russian');
	require_once( $mainframe->getCfg('absolute_path') .'/mambots/content/plugin_jw_ajaxvote/languages/'.$language.'.php' );
	$id 	= $row->id;
	$option = 'com_content';
	$result=0;
	$html = '';
	if ($params->get( 'rating' ) && !$params->get( 'popup' )){
		$vote = new stdClass;
		$vote->rating_count = $row->rating_count;
		$vote->rating_sum = $row->rating;
		if($vote->rating_count!=0)
			$result = number_format(intval($vote->rating_sum) / intval( $vote->rating_count ),2)*20;
		$rating_sum = intval($vote->rating_sum);
		$rating_count = intval($vote->rating_count);
		$mosConfig_caching ? $thmess = _JWAJAXVOTE_THANKS_CACHE : $thmess = _JWAJAXVOTE_THANKS;
		
		$script='
<link href="'.$mainframe->getCfg('live_site').'/mambots/content/plugin_jw_ajaxvote/css/ajaxvote.php" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var live_site = \''.$mainframe->getCfg('live_site').'\';
	var jwajaxvote_lang = new Array();
	jwajaxvote_lang[\'UPDATING\'] = \''._JWAJAXVOTE_UPDATING.'\';
	jwajaxvote_lang[\'THANKS\'] = \''.$thmess.'\';
	jwajaxvote_lang[\'ALREADY_VOTE\'] = \''._JWAJAXVOTE_ALREADY_VOTE.'\';
	jwajaxvote_lang[\'VOTES\'] = \''._JWAJAXVOTE_VOTES.'\';
	jwajaxvote_lang[\'VOTE\'] = \''._JWAJAXVOTE_VOTE.'\';
</script>
<script type="text/javascript" src="'.$mainframe->getCfg('live_site').'/mambots/content/plugin_jw_ajaxvote/js/ajaxvote.php"></script>
';
		if(!$addScriptJWAjaxVote){
			$addScriptJWAjaxVote = 1;
			/* при включенном кешировании выведем подключение js кода вместе с первым выводом кнопок голосования */
			if($mosConfig_caching)
				echo $script;
			else // если кеширование не активно - добавим js код в заголовок страницы - так правильнее
				$mainframe->addCustomHeadTag($script);
		}

?>
<div class="jwajaxvote-inline-rating">
	<ul class="jwajaxvote-star-rating">
		<li id="rating<?php echo $id ?>" class="current-rating" style="width:<?php echo $result?>%;"></li>
		<li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote(<?php echo $id ?>,1,<?php echo $rating_sum ?>,<?php echo $rating_count ?>);" title="1 <?php echo _JWAJAXVOTE_STAR; ?> 5" class="one-star">1</a></li>
		<li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote(<?php echo $id ?>,2,<?php echo $rating_sum ?>,<?php echo $rating_count ?>);" title="2 <?php echo _JWAJAXVOTE_STARS; ?> 5" class="two-stars">2</a></li>
		<li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote(<?php echo $id ?>,3,<?php echo $rating_sum ?>,<?php echo $rating_count ?>);" title="3 <?php echo _JWAJAXVOTE_STARS; ?> 5" class="three-stars">3</a></li>
		<li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote(<?php echo $id ?>,4,<?php echo $rating_sum ?>,<?php echo $rating_count ?>);" title="4 <?php echo _JWAJAXVOTE_STARS; ?> 5" class="four-stars">4</a></li>
		<li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote(<?php echo $id ?>,5,<?php echo $rating_sum ?>,<?php echo $rating_count ?>);" title="5 <?php echo _JWAJAXVOTE_STARS; ?> 5" class="five-stars">5</a></li>
	</ul>
	<div id="jwajaxvote<?php echo $id ?>" class="jwajaxvote-box">
<?php
		if($rating_count!=1) {
			echo "(".$rating_count." "._JWAJAXVOTE_VOTES.")";
		} else { 
			echo "(".$rating_count." "._JWAJAXVOTE_VOTE.")";
		}
		?>
	</div>
</div>

<?php
	}
}
?>
