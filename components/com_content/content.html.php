<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
* Utility class for writing the HTML for content
* @package Joostina
* @subpackage Content
*/
class HTML_content {
	/**
	* Draws a Content List
	* Used by Content Category & Content Section
	*/
	function showMyContentList( &$items, &$access, &$params, &$pageNav, &$lists, $order ) {
		global $Itemid, $database;

		if ( $params->get( 'page_title' ) ) {
			$title = $params->get( 'my_page_title' );
			if(!$title){
				$menu = new mosMenu( $database );
				$menu->load( $Itemid );
				$title = $menu->name;
			}
?>
			<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>"><?php echo $title; ?></div>
<?php
		}
?>
		<table width="100%" align="center" class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<tr>
				<td  width="100%">
<?php
				if ( $items ) {
					HTML_content::showMyTable( $params, $items, $pageNav, $access, $lists, $order );
				} else {
					echo _YOU_HAVE_NO_CONTENT;
				}?>
				</td>
			</tr>
		</table>
<?php
		// displays back button
		mosHTML::BackButton ( $params );
	}


	/**
	* Display Table of items
	*/
	function showMyTable( &$params, &$items, &$pageNav, &$access, &$lists, $order ) {
		global $mosConfig_live_site, $Itemid,$mosConfig_form_date_full;
		$link = 'index.php?option=com_content&amp;task=mycontent&amp;Itemid='. $Itemid;
		/* подключаем fullajax */
		mosCommonHTML::loadFullajax();
		?>
		<script type="text/javascript">
		// смена статуса публикации, elID - идентификатор объекта у которого меняется статус публикации
		function ch_publ(elID){
			log('Смена статуса публикации содержимого: '+elID);
			id('img-pub-'+elID).src = '/images/system/aload.gif';
			dax({
				url: 'ajax.index.php?option=com_content&utf=0&task=publish&id='+elID,
				id:'publ-'+elID,
				callback:
					function(resp, idTread, status, ops){
						log('Получен ответ: ' + resp.responseText);
						id('img-pub-'+elID).src = ADMINISTRATOR_DIRECTORY.'/images/'+resp.responseText;
					}
			});
		}
		</script>
		<form action="<?php echo sefRelToAbs($link); ?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
		if ( $params->get( 'filter' ) || $params->get( 'order_select' ) || $params->get( 'display' ) ) {
			?>
			<tr>
				<td colspan="5">
					<table>
					<tr>
			<?php
						if ( $params->get( 'filter' ) ) {
							?>
								<td align="right" width="80%"><?php echo _FILTER; ?><br />
									<input type="text" name="filter" size="50" value="<?php echo $lists['filter'];?>" class="inputbox" onchange="document.adminForm.submit();" />
								</td><?php
						}
							if ( $params->get( 'order_select' ) ) {
						?>
							<td align="right" width="20%"><?php echo _ORDER_DROPDOWN;?><br /><?php echo $lists['order']; ?></td><?php
						}
						if ( $params->get( 'display' ) ) {
							?>
							<td align="right" width="80%"><?php echo _PN_DISPLAY_NR;?><br /><?php
							$link = 'index.php?option=com_content&amp;task=mycontent&amp;Itemid='. $Itemid.'&amp;order='.$order;
							echo $pageNav->getLimitBox( $link );
							?></td><?php
						}
						?>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>">&nbsp;</td>
				<td class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>" width="60%"><?php echo _HEADER_TITLE;?></td>
				<td class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>"><?php echo _E_PUBLISHING;?></td>
				<td class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>" width="20%"><?php echo _DATE;?></td>
				<td class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>"><?php echo _HEADER_HITS;?></td>
			</tr>
<?php
		}

		$k = 0;
		foreach ( $items as $row ) {
			$row->Itemid_link = '&amp;Itemid='.$Itemid;
			$row->_Itemid = $Itemid;
			$row->created = mosFormatDate ($row->created,$mosConfig_form_date_full,'0');
			$link	= sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id .'&amp;Itemid='. $Itemid );
			$img	= $row->published ? 'publish_g.png' : 'publish_x.png';
			$img	= $mosConfig_live_site.'/'.ADMINISTRATOR_DIRECTORY.'/images/'.$img;
			?>
			<tr class="sectiontableentry<?php echo ($k+1) . $params->get( 'pageclass_sfx' ); ?>" >
					<td><?php HTML_content::EditIcon( $row, $params, $access );?></td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a><br />
						<span class="small"><?php
						if($row->sectionid!=0)
							echo $row->section.' / '.$row->category; // раздел / категория
						else
							echo 'Статичное содержимое'; // тип добавленного содержимого - статичное содержимое
						?></span>
					</td>
					<td align="center" <?php echo ($access->canPublish) ? 'onclick="ch_publ('.$row->id.');" class="td-state"' : null ;?>>
						<img class="img-mini-state" src="<?php echo $img;?>" id="img-pub-<?php echo $row->id;?>" alt="Публикация" />
					</td>
					<td><?php echo $row->created; ?></td>
					<td align="center"><?php echo $row->hits ? $row->hits : 0; ?></td>
			</tr>
<?php
			$k = 1 - $k;
		}
		if ( $params->get( 'navigation' ) ) {
			?>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" colspan="5" class="sectiontablefooter<?php echo $params->get( 'pageclass_sfx' ); ?>">
	<?php
				$link = 'index.php?option=com_content&amp;task=mycontent&amp;Itemid='. $Itemid. '&amp;order=' .$order;
				echo $pageNav->writePagesLinks( $link );
				?>
				</td>
			</tr>
<?php
		}
		?>
		</table>
		<input type="hidden" name="task" value="mycontent" />
		<input type="hidden" name="option" value="com_content" />
		</form>
<?php
	}

	/**
	* Draws a Content List
	* Used by Content Category & Content Section
	*/
	function showContentList($title,&$items,&$access,$id = 0,$sectionid = null,$gid,&$params,&$pageNav,$other_categories,&$lists,$order,$categories_exist) {
		global $Itemid,$mosConfig_live_site, $mosConfig_absolute_path;

		if($sectionid) {
			$id = $sectionid;
		}

		if(strtolower(get_class($title)) == 'mossection') {
			$catid = 0;
		} else {
			$catid = $title->id;
		}
        $sfx = $params->get('pageclass_sfx');
        $page_title='';
        $title_description = '';
        $title_image = '';
        $add_button = '';
        $show_categories = 0;


        if ($params->get('page_title')) {
            $page_title = htmlspecialchars($title->name, ENT_QUOTES);
        }

        if ($params->get('description') && $title->description) {
            $title_description = $title->description;
        }

        if($params->get('description_image') && $title->image){
            $link = $mosConfig_live_site . '/images/stories/' . $title->image;
            $title_image = '<img class="desc_img" src="'.$link.'" align="'.$title->image_position.'"  alt="'.$title->image.'" />';
        }

	    if (($access->canEdit || $access->canEditOwn) && $categories_exist) {
		    $link = sefRelToAbs('index.php?option=com_content&amp;task=new&amp;sectionid=' . $id . '&amp;Itemid=' . $Itemid);
            $add_button = '<a href="'.$link.'" class="add_button add_content">'._CMN_NEW.'</a>';
	   	}

        if (((count($other_categories) > 1) || (count($other_categories) < 2 && count($items) < 1))) {
		    if ( (($params->get('type') == 'category') && $params->get('other_cat'))  || (($params->get('type') == 'section') && $params->get('other_cat_section'))) {
		        $show_categories = 1;
            }
	    }

        if(!$items){
            $page_type = 'section_catlist';
            $templates = $params->section_data->templates;
            //include_once($mosConfig_absolute_path.'/components/com_content/view/section/catlist/default.php');
        }
        else{
            $page_type = 'category_table';
            $templates = $params->category_data->templates;
            //include_once($mosConfig_absolute_path.'/components/com_content/view/category/table/default.php');
        }

        $template = new jstContentTemplate();
        $template->set_template($page_type, $templates);
        include_once($template->template_file);

	}


	/**
	* Display links to categories
	*/
	function showCategories(&$params,&$items,$gid,&$other_categories,$catid,$id,$Itemid) {
        //подключается шаблон
	}


	/**
	* Display Table of items
	*/
	function showTable(&$params,&$items,&$gid,$catid,$id,&$pageNav,&$access,&$sectionid,&$lists,$order) {
	   //подключается шаблон /components/com_content/view/table_of_items/default.php
	}


	/**
	* Display links to content items
	*/
	function showLinks(&$rows,$links,$total,$i = 0,$show = 1,$ItemidCount = null) {
		global $mainframe,$Itemid;

		// getItemid compatibility mode, holds maintenance version number
		$compat = (int)$mainframe->getCfg('itemid_compat');

		if($show && isset($rows[$i])) { ?>
		    <div class="more_items">
		        <strong> <?php echo _MORE; ?></strong>
		    </div>
        <?php } ?>


        <ul class="more_items">

            <?php for($z = 0; $z < $links; $z++) {
			    if(!isset($rows[$i])) {
				    // stops loop if total number of items is less than the number set to display as intro + leading
				    break;
			    }

			    if($compat > 0 && $compat <= 11) {
				    $_Itemid = $mainframe->getItemid($rows[$i]->id,0,0);
			    } else {
				    $_Itemid = $Itemid;
			    }

			    if($_Itemid && $_Itemid != 99999999) {
				    // where Itemid value is returned, do not add Itemid to url
				    $Itemid_link = '&amp;Itemid='.$_Itemid;
			    } else {
				    // where Itemid value is NOT returned, do not add Itemid to url
				    $Itemid_link = '';
			    }

			    $link = sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.$rows[$i]->id.$Itemid_link)
            ?>

            <li>
			    <a class="blogsection" href="<?php echo $link; ?>" title="<?php echo $rows[$i]->title; ?>"><?php echo $rows[$i]->title; ?></a>
			</li>

            <?php $i++; } ?>
		</ul>


    <?php
	}


	/**
	* Отображение содержимого
	* @param object An object with the record
	* @param boolean If <code>false</code>, the print button links to a popup window.  If <code>true</code> then the print button invokes the browser print method.
	* boston + хак отключения мамботов группы content
	*/
	function show(&$row,&$params,&$access,$page = 0, $template='') {
		global $mainframe,$hide_js,$_MAMBOTS,$mosConfig_mmb_content_off,$mosConfig_live_site,$mosConfig_uid_news, $mosConfig_absolute_path;
		global $news_uid,$task;
		// уникальные идентификаторы новостей
		$news_uid_css_title = '';
		$news_uid_css_body = '';
		if($mosConfig_uid_news) {
			$news_uid++;
			$news_uid_css_title = 'id="title-news-uid-'.$news_uid.'" ';
			$news_uid_css_body = 'id="body-news-uid-'.$news_uid.'" ';
		}
		// boston, это еще будет
		//if($task == 'view') $news_uid_css = 'id="pageclass_uid_'.$params->get( 'pageclass_uids' ).'" ';

		$mainframe->appendMetaTag('description',$row->metadesc);
		$mainframe->appendMetaTag('keywords',$row->metakey);

		// adds mospagebreak heading or title to <site> Title
		if(isset($row->page_title) && $row->page_title) {
			$mainframe->setPageTitle($row->title.' '.$row->page_title);
		}

		// получение параметров текущего содержимого
		$cur_params = new mosParameters($row->attribs);
		$news_uid_css_page = $cur_params->get('pageclass_uids');
		if($cur_params->get('pageclass_uids_full') && trim($news_uid_css_page) != '') {
			$news_uid_css_title = 'id="title-news-'.$news_uid_css_page.'" ';
			$news_uid_css_body = 'id="body-news-'.$news_uid_css_page.'" ';
		};
		// расчет Itemid
		HTML_content::_Itemid($row);
		// determines the link and `link text` of the readmore button & linked title
		HTML_content::_linkInfo($row,$params);
		// link used by print button
		$print_link = $mosConfig_live_site.'/index2.php?option=com_content&amp;task=view&amp;id='.$row->id.'&amp;pop=1&amp;page='.$page.$row->Itemid_link;

        $row->title=HTML_content::Title($row,$params,$access);


		// обработка контента ботами, если в глобальной конфигурации они отключены - то мамботы не  используем
		if($mosConfig_mmb_content_off != 1) {
			$_MAMBOTS->loadBotGroup('content');
			$results = $_MAMBOTS->trigger('onPrepareContent',array(&$row,&$params,$page),true);
		}
        //зануляем
        $loadbot_onAfterDisplayTitle='';
        $loadbot_onBeforeDisplayContent='';


		if(!$params->get('intro_only')) {
			$results_onAfterDisplayTitle = $_MAMBOTS->trigger('onAfterDisplayTitle',array(&$row,&$params,$page));
			$loadbot_onAfterDisplayTitle= trim(implode("\n",$results_onAfterDisplayTitle));
		}

		$results_onBeforeDisplayContent = $_MAMBOTS->trigger('onBeforeDisplayContent',array(&$row,&$params,$page));
		$loadbot_onBeforeDisplayContent = trim(implode("\n",$results_onBeforeDisplayContent));

        $create_date = null;
       	if($row->created != 0) {
			$create_date = mosFormatDate($row->created);
    	}

        $mod_date = null;
        if(intval($row->modified) != 0) {
			$mod_date = mosFormatDate($row->modified);
		}

        $author=mosContent::Author($row,$params);


        $readmore=mosContent::ReadMore($row,$params);

        $edit='';
        if($access->canEdit){
            $edit=mosContent::EditIcon2($row,$params,$access);
        }

        $results_onAfterDisplayContent = $_MAMBOTS->trigger('onAfterDisplayContent',array(&$row,&$params,$page));
		$loadbot_onAfterDisplayContent= trim(implode("\n",$results_onAfterDisplayContent));


        //если 'template' задано - значит выводится интро записи в блоге,
        //поэтому никаких дополнительных манипуляций не требуется,
        // так как имя шаблона задается непосредственно в шаблоне блога раздела или категории
        if($template){
            include($mosConfig_absolute_path.'/components/com_content/view/item/'.$template);
        }
        //иначе - это страница записи и нужно определить, какой шаблон  использовать для вывода
        else{
            $template = new jstContentTemplate();
            $templates=null;

            //если это статичное содержимое или у записи задан шаблон
            if(!$row->sectionid || $row->templates){
                if($row->templates) {
                    $templates = $row->templates;
                }
            }
            //иначе - проверяем настройки категории, возможно, шаблон задан там
            elseif($template->isset_settings($params->page_type, $params->category_data->templates)){
                $templates = $params->category_data->templates;
            }
            //иначе - проверяем настройки раздела
            elseif($template->isset_settings($params->page_type, $params->section_data->templates)){
                $templates = $params->section_data->templates;
            }

            $template->set_template($params->page_type, $templates);
            include_once($template->template_file);
        }

	}

	/**
	* calculate Itemid
	*/
	function _Itemid(&$row) {
		global $task,$Itemid,$mainframe;
		// getItemid compatibility mode, holds maintenance version number
		$compat = (int)$mainframe->getCfg('itemid_compat');
		if(($compat > 0 && $compat <= 11) && $task != 'view' && $task != 'category') {
			$row->_Itemid = $mainframe->getItemid($row->id,0,0);
		} else {
			// when viewing a content item, it is not necessary to calculate the Itemid
			$row->_Itemid = $Itemid;
		}

		if($row->_Itemid && $row->_Itemid != 99999999) {
			// where Itemid value is returned, do not add Itemid to url
			$row->Itemid_link = '&amp;Itemid='.$row->_Itemid;
		} else {
			// where Itemid value is NOT returned, do not add Itemid to url
			$row->Itemid_link = '';
		}
	}

	/**
	* determines the link and `link text` of the readmore button & linked title
	*/
	function _linkInfo(&$row,&$params) {
		global $my;

		$row->link_on = '';
		$row->link_text = '';

		if($params->get('readmore') || $params->get('link_titles')) {
			if($params->get('intro_only')) {
				// checks if the item is a public or registered/special item
				if($row->access <= $my->gid) {
					$row->link_on = sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.
						$row->id.$row->Itemid_link);

					if(isset($row->readmore) && @$row->readmore) {
						// text for the readmore link
						$row->link_text = _READ_MORE;
					}
				} else {
					$row->link_on = sefRelToAbs('index.php?option=com_registration&amp;task=register');

					if(isset($row->readmore) && @$row->readmore) {
						// text for the readmore link if accessible only if registered
						$row->link_text = _READ_MORE_REGISTER;
					}
				}
			}
		}
	}

	/**
	* Вывод заголовка
	*/
	function Title(&$row,&$params,&$access) {
		global $mosConfig_title_h1,$mosConfig_title_h1_only_view,$task;
		if($params->get('item_title')) {

              //PbICb
              //наводим порядок с выводом заголовков

              // Проверяем, нужно ли делать заголовки ссылками
              if($params->get('link_titles') && $row->link_on != '') {
                  $row->title='<a href="'.$row->link_on.'" title="'.$row->title.'" class="contentpagetitle">'.$row->title.'</a>';
              }

              switch($task){
                case 'blogsection':
                default:
                  $group_cat=$params->get('group_cat',0);
                  if(!$group_cat){
                      $row->title='<h2>'.$row->title.'</h2>';
                  }
                  else{
                      //Если включена группировка по категориям -
                      // в тэге <h2> выводятся названия категорий
                      // поэтому заключаем заголовки материалов в <h3>
                      $row->title='<h3>'.$row->title.'</h3>';
                  }
                break;

                case 'blogcategory':
                  $row->title='<h2>'.$row->title.'</h2>';
                break;

                case 'view':
                  $row->title='<h1>'.$row->title.'</h1>';
                break;
              }

              //Выводим заголовок

              return $row->title;

        }
    }

	/**
	* Writes Edit icon that links to edit page
	*/
	function EditIcon(&$row,&$params,&$access) {
		global $my;

		if($params->get('popup')) {
			return;
		}
		if($row->state < 0) {
			return;
		}
		if(!$access->canEdit && !($access->canEditOwn && $row->created_by == $my->id)) {
			return;
		}
		mosCommonHTML::loadOverlib();
		$link = 'index.php?option=com_content&amp;task=edit&amp;id='.$row->id.$row->Itemid_link.'&amp;Returnid='.$row->_Itemid;
		$image = mosAdminMenus::ImageCheck('edit.png','/images/M_images/',null,null,_E_EDIT,_E_EDIT);
		if($row->state == 0) {
			$overlib = _CMN_UNPUBLISHED;
		} else {
			$overlib = _CMN_PUBLISHED;
		}
		$date = mosFormatDate($row->created);
		$author = $row->created_by_alias ? $row->created_by_alias : $row->author;

		$overlib .= ' / ';
		$overlib .= $row->groups;
		$overlib .= '<br />';
		$overlib .= $date;
		$overlib .= '<br />';
		$overlib .= $author;
?>
		<a href="<?php echo sefRelToAbs($link); ?>" onmouseover="return overlib('<?php echo $overlib; ?>', CAPTION, '<?php echo _E_EDIT; ?>', BELOW, RIGHT);" onmouseout="return nd();"><?php echo $image; ?></a>
<?php
	}




	/**
	* Writes Email icon
	*/
	function EmailIcon(&$row,&$params,$hide_js) {
		global $mosConfig_live_site,$Itemid,$task,$cne_i;
		if(!isset($cne_i)) $cne_i = '';
		if($params->get('email') && !$params->get('popup') && !$hide_js) {
			$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=400,height=250,directories=no,location=no';

			if($task == 'view') {
				$_Itemid = '&amp;itemid='.$Itemid;
			} else {
				$_Itemid = '';
			}

			$link = $mosConfig_live_site.'/index2.php?option=com_content&amp;task=emailform&amp;id='.$row->id.$_Itemid;

			if($params->get('icons')) {
				$image = mosAdminMenus::ImageCheck('emailButton.png','/images/M_images/',null,null,_CMN_EMAIL,'email'.$cne_i);
				$cne_i++;
			} else {
				$image = '&nbsp;'._CMN_EMAIL;
			}
?>
			<td align="right" width="100%" class="buttonheading">
				<a href="<?php echo $link; ?>" target="_blank" onclick="window.open('<?php echo $link; ?>','win2','<?php echo $status; ?>'); return false;" title="<?php echo _CMN_EMAIL; ?>"><?php echo $image; ?></a>
			</td>
<?php
		}
	}

	/**
	* Writes Container for Section & Category
	*/
	function Section_Category(&$row,&$params) {
		if($params->get('section') || $params->get('category')) {
?>
			<tr>
				<td>
<?php
		}

		// displays Section Name
		HTML_content::Section($row,$params);

		// displays Section Name
		HTML_content::Category($row,$params);

		if($params->get('section') || $params->get('category')) {
?>
				</td>
			</tr>
<?php
		}
	}

	/**
	* Writes Section
	*/
	function Section(&$row,&$params) {
		if($params->get('section')) {
?>
				<span class="section_name">
	<?php
			echo $row->section;
			// writes dash between section & Category Name when both are active
			if($params->get('category')) {
				echo ' - ';
			}
?>
				</span>
<?php
		}
	}

	/**
	* Writes Category
	*/
	function Category(&$row,&$params) {
		if($params->get('category')) {
?>
			<span class="category_name">
<?php
			echo $row->category;
?>
			</span>
<?php
		}
	}




	/**
	* Writes Create Date
	*/
	function CreateDate(&$row,&$params) {
		$create_date = null;

		if(intval($row->created) != 0) {
			$create_date = mosFormatDate($row->created);
		}

		if($params->get('createdate')) {
?>
			<span class="date"><?php echo $create_date; ?></span>
<?php
		}
	}

	/**
	* Writes URL's
	*/
	function URL(&$row,&$params) {
		if($params->get('url') && $row->urls) {
?>
			<tr>
				<td valign="top" colspan="2"><a href="http://<?php echo $row->urls; ?>" target="_blank"><?php echo $row->urls; ?></a></td>
			</tr>
<?php
		}
	}

	/**
	* Writes TOC
	*/
	function TOC(&$row) {
		if(isset($row->toc)) {
			echo $row->toc;
		}
	}

	/**
	* Writes Modified Date
	*/
	function ModifiedDate(&$row,&$params) {
		$mod_date = null;

		if(intval($row->modified) != 0) {
			$mod_date = mosFormatDate($row->modified);
		}

		if(($mod_date != '') && $params->get('modifydate')) {
?>
        <div class="modifydate">
		<?php echo _LAST_UPDATED; ?> ( <?php echo $mod_date; ?> )
			</div>
<?php
		}
	}

	/**
	* Writes Readmore Button
	*/
	function ReadMore(&$row,&$params) {
		if($params->get('readmore')) {
			if($params->get('intro_only') && $row->link_text) {
?>

    <a href="<?php echo $row->link_on; ?>" title="<?php echo $row->title; ?>" class="readon<?php echo $params->get('pageclass_sfx'); ?>"><?php echo $row->link_text; ?></a>

	<?php
			}
		}
	}

	/**
	* Writes Next & Prev navigation button
	*/
    function Navigation(&$row,&$params) {
		global $task;

		$link_part = 'index.php?option=com_content&amp;task=view&amp;id=';

		// determines links to next and prev content items within category
		if($params->get('item_navigation')) {
			if($row->prev) {
				$row->prev = sefRelToAbs($link_part.$row->prev.$row->Itemid_link);
			} else {
				$row->prev = 0;
			}

			if($row->next) {
				$row->next = sefRelToAbs($link_part.$row->next.$row->Itemid_link);
			} else {
				$row->next = 0;
			}
		}

		if($params->get('item_navigation') && ($task == 'view') && !$params->get('popup') &&
			($row->prev || $row->next)) { ?>

            <table class="page_navigation">
			    <tr>

	            <?php if($row->prev) { ?>
				    <th class="pagenav_prev">
					    <a href="<?php echo $row->prev; ?>" title="<?php echo $row->prev_title; ?>"><?php echo _ITEM_PREVIOUS.$row->prev_title;?></a>
				    </th>

                <?php } ?>

                <?php if($row->prev && $row->next) { ?>
				    <th width="50">&nbsp;</th>
		        <?php } ?>

                <?php if($row->next) { ?>
				    <th class="pagenav_next">
					    <a href="<?php echo $row->next; ?>" title="<?php echo $row->next_title; ?>">
				        <?php echo $row->next_title._ITEM_NEXT; ?></a>
				    </th>
		        <?php } ?>

			    </tr>
			</table>

        <?php }
    }

	/**
	* Writes the edit form for new and existing content item
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param mosContent The category object
	* @param string The html for the groups select list
	*/
    function editContent(&$row,&$page,$task) {
		global $mosConfig_live_site, $mosConfig_absolute_path, $mainframe, $my;

		mosMakeHtmlSafe($row);

        //Загрузка скрипта ajax-валидации
        mosCommonHTML::loadJqueryPlugins('jquery.validate');


		require_once ($GLOBALS['mosConfig_absolute_path'].'/includes/HTML_toolbar.php');
        $s_id = mosGetParam($_REQUEST,'section',0);
		// used for spoof hardening
		$validate = josSpoofValue();

        if($task=='edit'){
            $section_id = $row->sectionid;
        }
        else{
            $section_id = $s_id;
        }

		mosCommonHTML::loadCalendar();

		// параметры полученные из настроек ссылки в меню
        $params = $page->params;

		$p_wwig = $params->get('wwig',1);// использование визуального редактора
        $wwig_buttons = $params->get('wwig_buttons',0);// отображение кнопок под визуальным редактором
		$content_type = $params->get('content_type',1); // тип контента
		$p_fulltext = $params->get('fulltext',1); // отображать поле полного основного текста
		$allow_alias = $params->get('allow_alias',0); // отображать поле "Псевдоним заголовка"
        $allow_info = $params->get('allow_info',1); // отображать информацию о записе
		$allow_params = $params->get('allow_params',1); // отображать вкладку "параметры"
		$allow_desc = $params->get('allow_desc',1); // отображать поле "Описание"
		$allow_tags = $params->get('allow_tags',1); // отображать поле "Ключевые слова"
        $auto_publish = $params->get('auto_publish',0); // настройки автопубликации
        $allow_frontpage = $params->get('allow_frontpage',0); // переключатель "На главной"
        $front_label = $params->get('front_label','На главной'); // подпись переключателя "На главной"

        if($p_wwig){
            $mainframe->set( 'allow_wysiwyg', 1 );
            $wwig_params=array('m_buttons'=>$wwig_buttons);
        }
        $access = $page->access;
        $lists = $row->lists;

        $class='';
        if(count($lists['catid'])>1){
            $class=' class="hidden" ';
        }

        $good_exit_link = 'index.php?option=com_content&task='.$task;
        if($s_id){
            $good_exit_link .= '&section='.$section_id;
        }
        if($task == 'edit'){
            $good_exit_link .= '&id='.$row->id;
        }
        $good_exit_link = sefRelToAbs($good_exit_link);

        //Если это редактирование статичного содержимого - подключаем шаблон item/edit/static.php
        if($task=='edit' && $row->sectionid==0){ 
            include($mosConfig_absolute_path.'/components/com_content/view/item/edit/static.php');
            return;
        }
        //иначе - проверяем, задан ли шаблон в настройках раздела
        else{
            $template = new jstContentTemplate();
            $templates=null;

            if(isset($params->section_data->templates) && $template->isset_settings($params->page_type, $params->section_data->templates)){
                $templates = $params->section_data->templates;
            }

            $template->set_template($params->page_type, $templates);
            include_once($template->template_file);
        }

        //include($mosConfig_absolute_path.'/components/com_content/view/item/edit_form/'.$template);
	}

	/**
	* Writes Email form for filling in the send destination
	*/
	function emailForm($uid,$title,$template = '',$itemid) {
		global $mainframe;

		// used for spoof hardening
		$validate = josSpoofValue();

		$mainframe->setPageTitle($title);
		$mainframe->addCustomHeadTag('<link rel="stylesheet" href="templates/'.$template.'/css/template_css.css" type="text/css" />');
?>
		<script language="javascript" type="text/javascript">
		function submitbutton() {
			var form = document.frontendForm;
			// do field validation
			if (form.email.value == "" || form.youremail.value == "") {
				alert( '<?php echo addslashes(_EMAIL_ERR_NOINFO); ?>' );
				return false;
			}
			return true;
		}
		</script>

		<form action="index2.php?option=com_content&amp;task=emailsend" name="frontendForm" method="post" onSubmit="return submitbutton();">
		<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td colspan="2"><?php echo _EMAIL_FRIEND; ?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="130"><?php echo _EMAIL_FRIEND_ADDR; ?></td>
			<td>
			<input type="text" name="email" class="inputbox" size="25" />
			</td>
		</tr>
		<tr>
			<td height="27"><?php echo _EMAIL_YOUR_NAME; ?></td>
			<td>
			<input type="text" name="yourname" class="inputbox" size="25" />
			</td>
		</tr>
		<tr>
			<td><?php echo _EMAIL_YOUR_MAIL; ?></td>
			<td>
			<input type="text" name="youremail" class="inputbox" size="25" />
			</td>
		</tr>
		<tr>
			<td><?php echo _SUBJECT_PROMPT; ?></td>
			<td>
			<input type="text" name="subject" class="inputbox" maxlength="100" size="40" />
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
			<input type="submit" name="submit" class="button" value="<?php echo _BUTTON_SUBMIT_MAIL; ?>" />
			&nbsp;&nbsp;
			<input type="button" name="cancel" value="<?php echo _BUTTON_CANCEL; ?>" class="button" onclick="window.close();" />
			</td>
		</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo $uid; ?>" />
		<input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
		<input type="hidden" name="<?php echo $validate; ?>" value="1" />
		</form>
<?php
	}

	/**
	* Writes Email sent popup
	* @param string Who it was sent to
	* @param string The current template
	*/
	function emailSent($to,$template = '') {
		global $mosConfig_sitename,$mainframe;

		$mainframe->setPageTitle($mosConfig_sitename);
		$mainframe->addCustomHeadTag('<link rel="stylesheet" href="templates/'.$template.'/css/template_css.css" type="text/css" />');
?>
		<span class="contentheading"><?php echo _EMAIL_SENT." $to"; ?></span> <br />
		<br />
		<br />
		<a href='javascript:window.close();'>
		<span class="small"><?php echo _PROMPT_CLOSE; ?></span>
		</a>
<?php
	}

    function _no_access($message='Извините, у Вас недостаточно прав для просмотра данной страницы'){
    	?>
		<div class="error"><?php echo $message;?></div>
	    <?php
    }

function _after_create_content($row){
    ?>
     Усё пучкомм
    <?php
}
}
?>
