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

if (file_exists($mosConfig_absolute_path."/administrator/components/com_linkeditor/language/".$mosConfig_lang.".php")){
	    include($mosConfig_absolute_path."/administrator/components/com_linkeditor/language/".$mosConfig_lang.".php");
    }else{
	include($mosConfig_absolute_path."/administrator/components/com_linkeditor/language/russian.php");
    }

require_once( $mainframe->getPath( 'admin_html' ) );

$cid = josGetArrayInts( 'cid' );
$id	= intval( mosGetParam( $_REQUEST, 'id', 0 ) );

switch ($task) {
	case 'edit':
		editLink($id);
		break;

	case 'new':
		editLink();
		break;

	case 'cancel':
		mosRedirect( "index2.php?option=com_linkeditor" );
		break;

	case 'savelink':
		js_menu_cache_clear();
		saveLink($cid);
		break;

	default:
	case 'all':
		viewLinks();
		break;

	case 'credits':
		credits();
		break;

	case 'saveorder':
		js_menu_cache_clear();
		saveOrder( $cid );
		break;

	case 'remove':
		js_menu_cache_clear();
		deleteLink( $cid );
		break;
}

function deleteLink( &$cid ) {
	global $database;

	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__components"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	mosRedirect( 'index2.php?option=com_linkeditor&amp;task=all', 'Пункт меню удалён' );

}
function saveOrder( &$cid ) {
	global $database;

	$total		= count( $cid );
	$order 		= mosGetParam( $_POST, 'order', array(0) );
	$row 		= new mosComponent( $database );
	$conditions = array();

	// update ordering values
	for ( $i=0; $i < $total; $i++ ) {
		$row->load( $cid[$i] );
		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "parent='$row->parent' AND iscore >= 0";
			$found = false;
			foreach ( $conditions as $cond )
				if ($cond[1]==$condition) {
					$found = true;
					break;
				} // if
			if (!$found) $conditions[] = array($row->id, $condition);
		} // if
	} // for

	// execute updateOrder for each group
	foreach ( $conditions as $cond ) {
		$row->load( $cond[0] );
		$row->updateOrder( $cond[1] );
	} // foreach

	$msg 	= 'Порядок сохранён';
	mosRedirect( 'index2.php?option=com_linkeditor', $msg );
} // saveOrder

function editLink( $id=0 ) {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site;
	$row = new mosComponent( $database );
	$row->load( $id );

	$pathA 		= $mosConfig_absolute_path .'/includes/js/ThemeOffice';
	$pathL 		= $mosConfig_live_site .'/includes/js/ThemeOffice';
	$images 	= array();
	$folders 	= array();
	$folders[] 	= mosHTML::makeOption( '/' );

	$images['/'][] = mosHTML::makeOption( 'spacer.png','Отсутствует' );
	ReadImages( $pathA, '/', $folders, $images );

	$lists['image']	= GetImages( $images, $pathL, $row );

	$options = array();
    $options[] = mosHTML::makeOption('0', 'Top');
	$lists['parent'] = categoryParentList($row->id, "", $options);

	HTML_linkeditor::edit( $row, $lists );
}

function GetImages( &$images, $pathL, $row ) {
		if ( !isset($images['/'] ) ) {
			$images['/'][] = mosHTML::makeOption( '' );
		}
		$javascript	= "onchange=\"previewImage( 'admin_menu_img', 'view_imagefiles', '$pathL/' )\"";
		$getimages	= mosHTML::selectList( $images['/'], 'admin_menu_img', 'class="inputbox" size="10" style="width:95%"'. $javascript , 'value', 'text', $row->admin_menu_img );

		return $getimages;
	}

function ReadImages( $imagePath, $folderPath, &$folders, &$images ) {
	global $mosConfig_live_site;
		$imgFiles = mosReadDirectory( $imagePath );
		foreach ($imgFiles as $file) {
			$ff_ 	= $folderPath . $file .'/';
			$ff 	= $folderPath . $file;
			$i_f 	= $imagePath .'/'. $file;
			if ( eregi( "bmp|gif|jpg|png", $file ) && is_file( $i_f ) ) {
				$imageFile = substr( $ff, 1 );
				$images[$folderPath][] = mosHTML::makeOption($imageFile, $file );
			}
		}
	}

function categoryParentList($id, $action, $options = array())  {
        global $database;

        $list = categoryArray();

        $cat = new mosComponent($database);
        $cat->load($id);

        $this_treename = '';
        foreach ($list as $item) {
            if ($this_treename) {
                if ($item->id != $cat->id && strpos($item->treename, $this_treename) === false) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            } else {
                if ($item->id != $cat->id) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                } else {
                    $this_treename = "$item->treename/";
                }
            }
        }

        $parent = mosHTML::selectList($options, 'parent', 'class="inputbox" size="1" style="width:80%"', 'value', 'text', $cat->parent);
   return $parent;
}

function categoryArray() {
        global $database, $my;

        // get a list of the menu items
        $query = "SELECT *"
         . "\n FROM #__components"
         . "\n ORDER BY ordering" ;

        $database->setQuery($query);
        $items = $database->loadObjectList();
        // establish the hierarchy of the menu
        $children = array();
        // first pass - collect children
        foreach ($items as $v) {
            $pt = $v->parent;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }
        // second pass - get an indent list of the items
        $array = mosTreeRecurse(0, '', array(), $children);

   return $array;
}


function saveLink( &$id ) {
	global $database;
	$image	= mosGetParam( $_POST, 'admin_menu_img' );

	$admin_menu_img = "js/ThemeOffice/".$image;
	$_POST['admin_menu_img']=$admin_menu_img;
	$_POST['option']=	$_POST['cur_option'];
	$row = new mosComponent( $database );

if (!$row->bind( $_POST )) {
	echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
	exit();
}
if (!$row->store()) {
	echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
	exit();
}
mosRedirect( "index2.php?option=com_linkeditor", _LE_SUCCESS );
}

function viewLinks() {
	global $database, $mainframe, $mosConfig_absolute_path,$mosConfig_list_limit,$option,$section,$menutype;
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$section}limitstart", 'limitstart', 0 ) );
	$levellimit = intval( $mainframe->getUserStateFromRequest( "view{$option}limit$menutype", 'levellimit', 10 ) );
	$database->setQuery( "SELECT * FROM #__components ORDER by ordering, name" );
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}
	// establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }
     // second pass - get an indent list of the items
	$list = mosTreeRecurse(0, '', array(), $children, max(0, $levellimit-1));

	$total = count($list);

	require_once($GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php');
	$pageNav = new mosPageNav($total, $limitstart, $limit);

	$levellist = mosHTML::integerSelectList(1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
	$list = array_slice($list, $pageNav->limitstart, $pageNav->limit);
	HTML_linkeditor::viewall( $list, $pageNav );

}

function credits() {
	$version = "3.0";
	?>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td bgcolor="#FFFFFF">
				<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td align="center">
					<b>Версия <?php echo $version; ?></b>
					</td>
				</tr>
				<tr>
					<td align="center">
					<b>
					Авторы
					</b>
					<br /><br />
					</td>
				<tr>
					<td align="center">
<script type="text/javascript">

/***********************************************
* Pausing updown message scroller- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

//configure the below five variables to change the style of the scroller
var scrollerdelay='3000' //delay between msg scrolls. 3000=3 seconds.
var scrollerwidth='200px'
var scrollerheight='100px'
var scrollerbgcolor='white'
//set below to '' if you don't wish to use a background image
var scrollerbackground='scrollerback.gif'

//configure the below variable to change the contents of the scroller
var messages=new Array()
messages[0]="<b>Главный кодер</b><br><br>Soner (pisdoktor) Ekici<br><a href='http://www.joomlaturkiye.org' target='_blank'>Joomla! Turkiye Support Site</a>"
messages[1]="<b>Контакты</b><br><br><a href='mailto:pisdoktor@joomlaturkiye.org'>pisdoktor@joomlaturkiye.org</a>"
messages[2]="<b>Домашний адрес</b><br><br><a href='http://www.sonerekici.com' target='_blank'>www.sonerekici.com</a>"
messages[3]="<b>Локализация: boston</b><br><br><a href='http://www.joom.ru' target='_blank'>www.joom.ru - русский дом Joomla!</a>"
///////Do not edit pass this line///////////////////////

var ie=document.all
var dom=document.getElementById

if (messages.length>2)
i=2
else
i=0

function move(whichdiv){
tdiv=eval(whichdiv)
if (parseInt(tdiv.style.top)>0&&parseInt(tdiv.style.top)<=5){
tdiv.style.top=0+"px"
setTimeout("move(tdiv)",scrollerdelay)
setTimeout("move2(second2_obj)",scrollerdelay)
return
}
if (parseInt(tdiv.style.top)>=tdiv.offsetHeight*-1){
tdiv.style.top=parseInt(tdiv.style.top)-5+"px"
setTimeout("move(tdiv)",50)
}
else{
tdiv.style.top=parseInt(scrollerheight)+"px"
tdiv.innerHTML=messages[i]
if (i==messages.length-1)
i=0
else
i++
}
}

function move2(whichdiv){
tdiv2=eval(whichdiv)
if (parseInt(tdiv2.style.top)>0&&parseInt(tdiv2.style.top)<=5){
tdiv2.style.top=0+"px"
setTimeout("move2(tdiv2)",scrollerdelay)
setTimeout("move(first2_obj)",scrollerdelay)
return
}
if (parseInt(tdiv2.style.top)>=tdiv2.offsetHeight*-1){
tdiv2.style.top=parseInt(tdiv2.style.top)-5+"px"
setTimeout("move2(second2_obj)",50)
}
else{
tdiv2.style.top=parseInt(scrollerheight)+"px"
tdiv2.innerHTML=messages[i]
if (i==messages.length-1)
i=0
else
i++
}
}

function startscroll(){
first2_obj=ie? first2 : document.getElementById("first2")
second2_obj=ie? second2 : document.getElementById("second2")
move(first2_obj)
second2_obj.style.top=scrollerheight
second2_obj.style.visibility='visible'
}

if (ie||dom){
document.writeln('<div id="main2" style="position:relative;width:'+scrollerwidth+';height:'+scrollerheight+';overflow:hidden;background-color:'+scrollerbgcolor+' ;background-image:url('+scrollerbackground+')">')
document.writeln('<div style="position:absolute;width:'+scrollerwidth+';height:'+scrollerheight+';clip:rect(0 '+scrollerwidth+' '+scrollerheight+' 0);left:0px;top:0px">')
document.writeln('<div id="first2" style="position:absolute;width:'+scrollerwidth+';left:0px;top:1px;">')
document.write(messages[0])
document.writeln('</div>')
document.writeln('<div id="second2" style="position:absolute;width:'+scrollerwidth+';left:0px;top:0px;visibility:hidden">')
document.write(messages[dyndetermine=(messages.length==1)? 0 : 1])
document.writeln('</div>')
document.writeln('</div>')
document.writeln('</div>')
}

if (window.addEventListener)
window.addEventListener("load", startscroll, false)
else if (window.attachEvent)
window.attachEvent("onload", startscroll)
else if (ie||dom)
window.onload=startscroll

</script>
					</td>
				</tr>
				</table>
			</td>
		</tr>
</table>
	<?php
}
?>
