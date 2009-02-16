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

class HTML_submit {
	/**
	* Writes the edit form for new and existing content item
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param mosContent The category object
	* @param string The html for the groups select list
	*/
	function editContent() {
		global $database, $mainframe, $Itemid,$my,$mosConfig_live_site,$mosConfig_editor, $mosConfig_absolute_path,$H_notify_email, $H_catid, $H_sectionid;
		require ($mosConfig_absolute_path.'/administrator/components/com_ja_submit/settings.php');
		require_once( $mosConfig_absolute_path . '/includes/HTML_toolbar.php' );
		$guest = 0;
		if(!$my->id){
			$guest = 1;
			$my->id = $H_id_users;
		};
		$mainframe->set( 'loadEditor', true );
		initEditor();
		?>
		<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/joomla.javascript.js" type="text/javascript"></script>
		<script language="javascript" type="text/javascript">
		var allowimages=Array;
		<?php
			//delete all unexpected dots
			$H_allowable_images=str_replace(".","",$H_allowable_images);
			$images=explode(",",$H_allowable_images);
			for ($i=0;$i<count($images);$i++){ ?>
				allowimages[<?php echo $i; ?>]="<?php echo $images[$i]; ?>";
			<?php } ?>
		var arrlen=<?php echo count($images); ?>;
		function validimg(){
			var form=document.adminForm;
			var found=false;
			img1=form.image1.value;
			img2=form.image2.value;
			if (img1!=""){
				if (!inarray(getExt(img1),allowimages)){
					alert("<?php echo _H_UNSUPPORT_IMAGE_1; ?>");
					return false;
				}
			}
			if (img2!=""){
				if (!inarray(getExt(img2),allowimages)){
					alert("<?php echo _H_UNSUPPORT_IMAGE_2; ?>");
					return false;
				}	
			}
			return true;
		}
		function inarray(key,array){
			var found=false;
			for (i=0;i<arrlen;i++){
				if (key==array[i]){
					found=true;
					break;
				}
			}
			return found;
		}
		function getExt(img){
			length=img.length;
			return img.substring(length-3,length);
		}
		function submitbutton(pressbutton){
			var form = document.adminForm;
			if (pressbutton == 'cancel'){
				submitform( pressbutton );
				return;
			}
			// var goodexit=false;
			// assemble the images back into one field
			form.goodexit.value=1;
			// do field validation
			if ( form.catid.value == "NA" ) {
				alert ( "<?php echo _H_CHOOSE_CAT; ?>" );
			}
			else if ( form.created_by_alias.value == "" ) {
				alert ( "<?php echo _H_ENTER_NAME; ?>" );
			} 
			else if ( ( form.email.value == "" ) || ( form.email.value.search("@") == -1 ) || ( form.email.value.search("[.*]" ) == -1 ) ) {
				alert ( "<?php echo _H_ENTER_EMAIL; ?>" );
			} 
			else if ( form.title.value == "" ) {
				alert ( "<?php echo _H_ENTER_TITLE; ?>" );
			}
			else {
				submitform(pressbutton);
			}
		}
		function setgood(){
			document.adminForm.goodexit.value=1;
		}
		function WarnUser(){
			if (document.adminForm.goodexit.value==0) {
				alert('<?php echo _E_WARNUSER;?>');
				window.location="<?php echo sefRelToAbs("index.php?option=com_ja_submit");?>";
			}
		}
	</script>
	<?php if ($H_title) { ?>
		<div class="componentheading"><?php echo _H_SUBMIT_CONTENT; ?></div>
	<?php } if ($H_rules) { ?>
		<table width="100%" cellspacing="4" cellpadding="4" border="0">
			<tr>
				<td class="contentdescription">
					<?php
						echo _H_SUBMIT_GUIDE;
						echo _H_SUBMIT_RULES;
					?>
				</td>
			</tr>
		</table>
	<?php };?>
	<form action="index.php?option=com_ja_submit" method="post" enctype="multipart/form-data" name="adminForm" onSubmit="javascript:setgood();">
	<table class="adminform" width="100%">
		<tr>
			<td width="16%"><b><label for="catid"><?php echo _H_CAT; ?>*</label>:</b></td>
			<td>
				<select class="inputbox" name="catid" id="catid">
					<option value="NA"><?php echo _H_CHOOSE_CAT; ?></option>
					<?php
					$database->setQuery( "SELECT c.id, CONCAT(s.name,' / ',c.name) as name FROM #__categories c,#__sections s where c.section = s.id");
					$rows = $database->loadObjectList();
					$avaiCats=explode(",",$H_avaiCategories);
					foreach($rows as $row){
						if (in_array($row->id,$avaiCats)){
							echo "<option value=\"$row->id\">$row->name</option>";
						}
					};
					unset($rows);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><b><label for="created_by_alias"><?php echo _H_FULLNAME; ?>*</label>:</b></td>
			<td><?php
				if (!$my->id) {
					echo "<input class=\"inputbox\" type=\"text\" name=\"created_by_alias\" size=\"50\" maxlength=\"50\" value=\"\" />" ;
				} else {
					if ($H_hiddenfield AND !$guest){
						echo "<input class=\"inputbox\" type=\"text\" name=\"created_by_alias\" disabled size=\"50\" maxlength=\"50\" value=\"".$my->name."\" />";
					}else {
						echo "<input class=\"inputbox\" type=\"text\" name=\"created_by_alias\" size=\"50\" maxlength=\"50\" value=\"".$my->name."\" />";}
				}
				?>
			</td>
		</tr>
		<tr>
			<td><b><label for="email"><?php echo _H_EMAIL; ?>*</label>:</b></td>
			<td><?php
				if (!$my->id) {
					echo "<input class=\"inputbox\" type=\"text\" name=\"email\" id=\"email\" size=\"50\" maxlength=\"50\" value=\"".$my->email."\" />";
				} else {
				if ($H_hiddenfield AND !$guest) {
					echo "<input class=\"inputbox\" type=\"text\" name=\"email\" disabled id=\"email\" size=\"50\" maxlength=\"50\" value=\"".$my->email."\" />";
					} else {
						echo "<input class=\"inputbox\" type=\"text\" name=\"email\" id=\"email\" size=\"50\" maxlength=\"50\" value=\"".$my->email."\" />";}
				}
				?>
			</td>
		</tr>
		<tr>
			<td><b><label for="submit_title"><?php echo _H_TITLE; ?>*</label>:</b></td>
			<td><input class="inputbox" type="text" name="title" id="submit_title" size="50" maxlength="100" /></td>
		</tr>
		<tr>
			<td colspan="2"><b><label for="introtext"><?php echo _H_INTROTEXT.' ('._H_REQUIRED.')'; ?></label>:</b></td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if ($H_editor){
				editorArea( 'editor1','', 'introtext', '500px', '200px', '70', '15' ) ;
				} else {echo "<textarea id=\"introtext\" name=\"introtext\" style=\"width:99%; height:200px;\"></textarea>";}?>
			</td>
		</tr>
		<?php if ($H_fulltext) { ?>
		<tr>
			<td colspan="2"><b><label for="fulltext"><?php echo _H_MAINTEXT.' ('._H_OPTIONAL.')'; ?></label>:</b></td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if ($H_editor) {
				editorArea( 'editor2','', 'fulltext', '500px', '200px', '70', '15' ) ;
				} else {echo "<textarea id=\"fulltext\" name=\"fulltext\" style=\"width:99%; height:200px;\"></textarea>";}?>
			</td>
		</tr>
		<?php }
		?>
		<tr>
			<td colspan="2"><?php echo _H_ALLOW_IMG_TYPE; ?>:<?php echo $H_allowable_images;?></td>
		</tr>
		<tr>
			<td><label for="image1"><?php echo _H_INTRO_IMG ;?></label>:</td>
			<td><input class="inputbox" type="file" name="image1" id="image1" /></td>
		</tr>
		<?php if ($H_fulltext) { ?>
		<tr>
			<td><label for="image2"><?php echo _H_MAIN_IMG ;?></label>:</td>
			<td><input class="inputbox" type="file" name="image2" id="image2" /></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2">
<?php
	if($H_captcha){
	session_start();
?>
			<img src="<?php echo  $mosConfig_live_site; ?>/includes/kcaptcha/index.php?<?php echo session_name()?>=<?php echo session_id()?>" alt="Обновите страницу для получения другого изображения." />
			<div>Введите код проверки с картинки выше:</div>
			<div><input name="captcha" type="text" class="inputbox" size="15" /></div>
<?php }; ?></td>
		</tr>
		<tr>
			<td><?php
				// Toolbar Top
				mosToolBar::startTable();
				mosToolBar::save();
				mosToolBar::cancel();
				mosToolBar::endtable();
				?>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="images" value="" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
	<input type="hidden" name="option" value="com_ja_submit" />
	<input type="hidden" name="goodexit" value="0" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="sectionid" value="" />
	<input type="hidden" name="referer" value="0" />
	<input type="hidden" name="task" value="" />
	</form>
	<?php
}

	function great(){
		global $mosConfig_live_site,$Itemid;
	?>
	<table width="100%">
		<tr>
			<td colspan="2"><?php
			if ($_GET['phrase']=="1"){
				echo _H_THANKS_PUBLISHED;
				echo "<br><a href='$mosConfig_live_site/index.php?option=com_content&task=view&id=".$_GET['id']."&Itemid=".$Itemid."'>
					$mosConfig_live_site/index.php?option=com_content&task=view&id=".$_GET['id']."&Itemid=".$Itemid."</a>";
			}else{
				echo _H_THANKS;
			}
			?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:center;"><span style="font-size:10px">>> </span><a href="<?php echo sefRelToAbs("index.php");?>"><?php echo _H_RETURN_FRONTPAGE; ?></a></td>
			<td style="text-align:center;"><span style="font-size:10px">>> </span><a href="<?php echo sefRelToAbs("index.php?option=com_ja_submit&Itemid=".$Itemid);?>"><?php echo _H_ADD_MORE; ?></a></td>
		</tr>
	</table>
	<?php
	}
}
?>
