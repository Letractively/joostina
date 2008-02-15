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
require(dirname(__FILE__).'/../../die.php');

// ensure user has access to this function
if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );

// get parameters
$pkey		= mosGetParam($_REQUEST,'pkey','');
$checkid	= mosGetParam($_REQUEST,'checkid','');
$component	= mosGetParam($_REQUEST,'component','');
$editor		= mosGetParam($_REQUEST,'editor','');

switch ($task) {
  	case "cancel":
		cancelMyCheckin( );
		break;

  	case "checkin":
		checkin( $pkey, $checkid, $component, $editor );
		showMyCheckin( $option );
		break;

	default:
		showMyCheckin( $option );
		break;
}

/**
* List the records
* @param string The current GET/POST option
*/
function showMyCheckin( $option ) {
	global $mainframe, $mosConfig_db, $database;
	
	$lt = mysql_list_tables($mosConfig_db);
	$k = 0;
	$dbprefix = $mainframe->getCfg( 'mosConfig_dbprefix' );

	$mosusers = new mosUser( $database );
	$list = "";
	$listcnt = 0;
       
	while (list($tn) = mysql_fetch_array( $lt )) 
	{
	   // make sure we get the right tables based on prefix
	   if (!preg_match( "/^".$dbprefix."/i", $tn )) 
	   {
		continue;
	   }
	   $lf = mysql_list_fields($mosConfig_db, "$tn");
	   $nf = mysql_num_fields($lf);

	   $foundCO = false;  // checked_out
	   $foundCOT = false; // checked_out_time
	   $foundTit = false;   // title
	   $foundE = false;   // title
	   $foundName = false;   // name 
	   $keyname = "";

	   $selstr = "checked_out, checked_out_time";

 	   // Search the table definition for the words 'checked_out', 'checked_out_time' and 'editor'
	   for ($i = 0; $i < $nf; $i++) 
	   {    
		$fname = mysql_field_name($lf, $i);
		switch ( $fname) 
		{
		case 'checked_out':
		    $foundCO = true;
		    break;

		case 'checked_out_time':
		    $foundCOT = true;
		    break;

		case 'editor':
		    $foundE = true;
		    break;

		case 'title':
		    $foundTit = true;
		    $selstr .= ", title";
		    break;

		case 'name':
		    $foundName = true;
		    $selstr .= ", name";
		    break;

		default:
		    break;
		}
		if  (preg_match( "/primary_key/i", mysql_field_flags($lf, $i)))
		{
		    $keyname = $fname; 
		    $selstr .= ", $fname";
		}
	    }

	    if ($foundCO && $foundCOT) 
	    {
		$database->setQuery( "SELECT $selstr FROM $tn WHERE checked_out > 0" );

		$res = $database->query();
		$num = $database->getNumRows( $res );

		if ($num > 0)
		{
		    $rows = $database->loadObjectList();
		    for ($i = 0; $i < $num; $i++)
		    {
		        if ($foundTit)
			{
			    $str = $rows[$i]->title;
			} 
			elseif ($foundName) {
			    $str = $rows[$i]->name;
			}
			else
			{
			    $str = "unknown";
			} 
			$mosusers->load( $rows[$i]->checked_out );
			$checkouttime = mktime(substr($rows[$i]->checked_out_time,11,2), substr($rows[$i]->checked_out_time,14,2), 
					substr($rows[$i]->checked_out_time,17,2), substr($rows[$i]->checked_out_time,5,2),
					substr($rows[$i]->checked_out_time,8,2), substr($rows[$i]->checked_out_time,0,4));
			
			$duration = round((time () - $checkouttime) / 60);
			if ($duration <= 120)
			{
			    $duration .= " минут";	
			}
			else if ($duration <= (48 * 60))
			{
			    $duration = round($duration / 60);
			    $duration .= " часов";	
			}
			else
			{
			    $duration = round($duration / (60 * 24));
			    $duration .= " дней";	
			}

			$list[$listcnt] = array ( "component"=> $tn, "title"=> $str, "name" => $mosusers->name, "cotime" => $rows[$i]->checked_out_time." ($duration)", "PKEY" => $keyname,  "id" => $rows[$i]->$keyname, "editor" => ($foundE) ? 'Y' : 'N' );
			$listcnt++;
		    }
		}
	    }
        }
		
	HTML_mycheckin::showlist( $option, $list, $listcnt );
}

function checkin( $pkey, $checkid, $component, $editor )
{
    	global $database;

	if ($editor == "Y") 
	{
	   $database->setQuery( "UPDATE $component SET checked_out=0, checked_out_time='00:00:00', editor=NULL WHERE $pkey = $checkid AND checked_out > 0" );
	} else {
	   $database->setQuery( "UPDATE $component SET checked_out=0, checked_out_time='00:00:00' WHERE $pkey = $checkid AND checked_out > 0" );
	}
	$res = $database->query();
	
	echo "<tr class=\"row1\">";
	echo "\n	<td align=\"center\" width=\"70%\"><b>$component</b> разблокирован";
	if ($res == 1) 
	{
	    echo "\n<img src=\"images/tick.png\" border=\"0\" alt=\"успешно\" />";
	}
	else
	{
	    echo "\n	При разблокировании произошла ошибка";
	}
	echo "</td>\n</tr>";
}

/** 
* Cancels editing and checks in the record
* @int the contact id
*/
function cancelMyCheckin($cid){
	mosRedirect('index2.php');
}
?>
