
<?php
/**
 * JComments - Joomla Comment System
 *
 * Search mambot
 *
 * @version 1.4
 * @package JComments
 * @filename jcomments.searchbot.php
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2008 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 * If you fork this to create your own project, 
 * please make a reference to JComments someplace in your code 
 * and provide a link to http://www.joomlatune.ru
 **/
require(dirname(__FILE__).'/../../die.php');

global $mainframe;

// define directory separator short constant
if (!defined( 'DS' )) {
	define( 'DS', DIRECTORY_SEPARATOR );
}
if (defined('JPATH_ROOT')) {
	include_once( JPATH_ROOT.DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php' );
} else {
	global $mosConfig_absolute_path;
	include_once( $mosConfig_absolute_path.DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php' );
}

// if component doesnt exists (may be already uninstalled) - return
if (!defined( 'JCOMMENTS_JVERSION' )) { return; }

if ( JCOMMENTS_JVERSION == '1.0' ) {
	global $_MAMBOTS;
	$_MAMBOTS->registerFunction( 'onSearch', 'botJCommentsSearch' );
} else if ( JCOMMENTS_JVERSION == '1.5' ) {
	$mainframe->registerEvent( 'onSearch', 'botJCommentsSearch' );
}

if (!function_exists('sefreltoabs')){ function sefRelToAbs($s) {  return $s; }}

function botJCommentsSearch( $text, $phrase='', $ordering='' ) {
	global $mainframe; 

        $text = trim( $text );

        if ($text == '' ){
                return array();
        }

        if (file_exists( JCOMMENTS_BASE.DS.'jcomments.php' )) {
		require_once( JCOMMENTS_BASE.DS.'jcomments.php' );

	        switch ($phrase) {
        	        case 'exact':
				$where = "LOWER(comment) LIKE '%$text%'";
	                	break;
        	        case 'all':
			case 'any':
			default:
	       		        $words = explode( ' ', $text );
        	       		$wheres = array();
	        	        foreach ($words as $word) {
        	        	        $wheres2 = array();
					$wheres2[] = "LOWER(name) LIKE '%$word%'";
					$wheres2[] = "LOWER(comment) LIKE '%$word%'";
        	                	$wheres[]  = implode( ' OR ', $wheres2 );
	        	        }               	
        	        	$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
	                	break;
		}

        	switch ($ordering) {
                	case 'oldest':
		                $order = 'date ASC';
		                break;
                	case 'newest':
			default:
        		        $order = 'date DESC';
	        	        break;
	        }

	        $db = & JCommentsFactory::getDBO();

		$query = "SELECT "
			 . "\n  comment      AS text"
			 . "\n, date         AS created"
			 . "\n, '2'          AS browsernav"
			 . "\n, '"._JCOMMENTS_HEADER."'   AS section"
			 . "\n, ''           AS href"
			 . "\n, id"
			 . "\n, object_id"
			 . "\n, object_group"
		         . "\nFROM #__jcomments "
		         . "\nWHERE published='1'"
			 . (($mainframe->getCfg( 'multilingual_support' ) == 1) ? "\nAND lang = '" . $mainframe->getCfg( 'lang' ) . "'" : "")
		         . "\n AND ($where) "
		         . "\nORDER BY object_id, $order";

		$db->setQuery( $query );
		$rows = $db->loadObjectList();


		$result = array();
		$cnt = count( $rows );

		if ( $cnt > 0 ) {

			$last_object_id = -1;
			$object_link = '';
		        	
			$bbcode = & JCommentsFactory::getBBCode();

			for($i=0;$i<$cnt;$i++) {
				if ($rows[$i]->object_id != $last_object_id) {
					$last_object_id = $rows[$i]->object_id;
					$object_link  = JCommentsPluginLoader::getObjectLink( $rows[$i]->object_id, $rows[$i]->object_group );
					$object_title = JCommentsPluginLoader::getObjectTitle( $rows[$i]->object_id, $rows[$i]->object_group );
				}
		
				$rows[$i]->href = $object_link . '#comment-' . $rows[$i]->id;

		        	$comment = $rows[$i]->text;
				$comment = $bbcode->filter($comment, true);
				$comment = str_replace('<br />', ' ', $comment);
				$comment = trim(preg_replace('/(\s){2,}/i', '\\1', $comment));
				$comment = mosHTML::cleanText($comment);
				$comment = html_entity_decode($comment);

				if ($comment != '') {
					$rows[$i]->title = $object_title;
					$rows[$i]->text = $comment;
					$result[] = $rows[$i];
				}
			}
			unset($bbcode);
		}
		
		unset($rows);
		
		return $result;
	}
	return array();
} 
?>