<?php
/**
 * JComments - Joomla Comment System
 *
 * Mambot for attaching comments to content items
 *
 * @version 1.4
 * @package jComments
 * @filename jcomments.contentbot.php
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2008 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 * If you fork this to create your own project, 
 * please make a reference to JComments someplace in your code 
 * and provide a link to http://www.joomlatune.ru
 **/

// ensure this file is being included by a parent file
(defined('_VALID_MOS') OR defined('_JEXEC')) or die('Direct Access to this location is not allowed.');

// define directory separator short constant
if (!defined( 'DS' )) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

global $mainframe;

if (defined('JPATH_ROOT')) {
	include_once( JPATH_ROOT.DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php' );
} else {
	include_once( $mainframe->getCfg( 'absolute_path' ).DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php' );
}

// if component doesnt exists (may be already uninstalled) - return
if (!defined( 'JCOMMENTS_JVERSION' )) { return; }

if ( JCOMMENTS_JVERSION == '1.0' ) {

	$_MAMBOTS->registerFunction( 'onPrepareContent', 'botJCommentsLink' );
	$_MAMBOTS->registerFunction( 'onAfterDisplayContent', 'botJCommentsView' );

} else if ( JCOMMENTS_JVERSION == '1.5' ) {

	$mainframe->registerEvent( 'onPrepareContent', 'botJCommentsLinkJ15' );
	$mainframe->registerEvent( 'onAfterDisplayContent', 'botJCommentsViewJ15' );

	$GLOBALS['JC_CONTENT_TASK'] = JRequest::getCmd( 'view' ) == 'article' ? 'view' : '' ;
}

function botJCommentsLink($published, &$row, &$params, $page=0) {
        global $mainframe, $task, $option, $Itemid, $_JCC, $my;

	list($usec, $sec) = explode(" ",microtime());
	$sysstart = ((float)$usec + (float)$sec);

	// disable comments link in 3rd party components (except Events and AlphaContent)
	if ( $option != 'com_content' && $option != 'com_frontpage'
		&& $option != 'com_alphacontent' && $option != 'com_events' ) {
		return;
	}

	if (!isset($params) || $params == null) {
		$params = new mosParameters('');
	}

        $pvars = array_keys(get_object_vars($params->_params));
	if (!$published || $params->get( 'popup' ) || in_array('moduleclass_sfx', $pvars)) {
	        // remove all comment tags like {moscomment}
		JCommentsProcessTags( $row, true );
		// remove all JComments tags
		$row->text = preg_replace('/{jcomments\s+(off|on|lock)}/is', '', $row->text);
		return;
	}

        if (file_exists( JCOMMENTS_BASE.DS.'jcomments.php' )) {

	        require_once( JCOMMENTS_BASE.DS.'jcomments.php' );

	        if ($task != 'view') {

		        // replace other comment systems tags to JComments equivalents like {jcomment on} 
			JCommentsProcessTags( $row, false );           

	                // show link to comments only
			$count = JComments::getCommentsCount( $row->id, 'com_content' );

			if ( $row->access <= $my->gid ) {

				// getItemid compatibility mode, holds maintenance version number
				$compat = $mainframe->getCfg('itemid_compat');

				if ( $compat == null ) {
					// Joomla 1.0.12 or below
					if ( $Itemid && $Itemid != 99999999 ) {
						$_Itemid = $Itemid;
					} else {
						$_Itemid = $mainframe->getItemid( $row->id );
					}
				} else 	if ( (int) $compat > 0 && (int) $compat <= 11) {
					// Joomla 1.0.13 or higher and Joomla 1.0.11 compability
					$_Itemid = $mainframe->getItemid( $row->id, 0, 0  );
				} else {
					// Joomla 1.0.13 or higher and new Itemid algoritm
					$_Itemid = $Itemid;
				}

				$link = sefRelToAbs("index.php?option=com_content&amp;task=view&amp;id=$row->id&amp;Itemid=$_Itemid");
				$readmore_register = 0;
			} else {
				$link = sefRelToAbs( 'index.php?option=com_registration&amp;task=register' );
				$readmore_register = 1;
			}

			$tmpl =& JCommentsFactory::getTemplate();
			$tmpl->load( 'tpl_links' );

			$tmpl->addVar('tpl_links', 'comments-count', $count);
			$tmpl->addVar('tpl_links', 'comments_link_style', ($readmore_register ? -1 : $count ));
			$tmpl->addVar('tpl_links', 'readmore_register', $readmore_register);
			$tmpl->addVar('tpl_links', 'link-comment', $link);
			$tmpl->addVar('tpl_links', 'link-readmore', $link);

			$tmpl->addVar('tpl_links', 'content-hits', @$row->hits);
			$tmpl->addVar('tpl_links', 'content-author', @$row->created_by_alias ? @$row->created_by_alias : @$row->author);
			$tmpl->addVar('tpl_links', 'content-created', mosFormatDate( @$row->created ));
			$tmpl->addVar('tpl_links', 'content-modified', mosFormatDate( @$row->modified ));
			$tmpl->addVar('tpl_links', 'content-section', @$row->section);
			$tmpl->addVar('tpl_links', 'content-category', @$row->category);
			$tmpl->addVar('tpl_links', 'content-title', @$row->title);

			if(($params->get('readmore') == 0) || (@$row->readmore == 0)) {
				$tmpl->addVar('tpl_links', 'readmore_link_hidden', 1);

			} else if(@$row->readmore > 0) {
				$tmpl->addVar('tpl_links', 'readmore_link_hidden', 0);
			}

		        // enable comments in current content category?
		        if ( $_JCC['enable_categories'] != '' ) {
			        $catids = explode(',', $_JCC['enable_categories']);
		        	if (!in_array(@$row->catid, $catids)) {
	        	        	// Oh, no...
					$tmpl->addVar('tpl_links', 'comments_link_hidden', 1);
			        }
			} else {
				$tmpl->addVar('tpl_links', 'comments_link_hidden', 1);
			}

			if (preg_match('/{jcomments\s+off}/is', $row->text)) {
				$row->text = preg_replace('/{jcomments\s+off}/is', '', $row->text);
				$tmpl->addVar('tpl_links', 'comments_link_hidden', 1);
			} else if (preg_match('/{jcomments\s+on}/is', $row->text)) {
				$row->text = preg_replace('/{jcomments\s+on}/is', '', $row->text);
				$tmpl->addVar('tpl_links', 'comments_link_hidden', 0);
			}

			if ($readmore_register == 1 && $count == 0) {
				$tmpl->addVar('tpl_links', 'comments_link_hidden', 1);
			}

			// remove all JComments tags
			$row->text = preg_replace('/{jcomments\s+(off|on|lock)}/is', '', $row->text);

			// append links to content text
			$row->text .= $tmpl->renderTemplate( 'tpl_links' );

			$GLOBALS['jcomments_params_readmore'] = $params->get('readmore');
			$GLOBALS['jcomments_row_readmore'] = $row->readmore;

			$params->set('readmore', 0);
			$row->readmore = 0;
	        } else {
		        // remove all comment tags like {moscomment}
			JCommentsProcessTags( $row, true );
			// remove all JComments tags
			$row->text = preg_replace('/{jcomments\s+(off|on|lock)}/is', '', $row->text);
	        }
	}

	list($usec, $sec) = explode(" ",microtime());
	$sysstop = ((float)$usec + (float)$sec);
	echo '<div id="time_gen">'.round($sysstop-$sysstart,4).'</div>';

	return true;
} 

function botJCommentsView(&$row, &$params, $page=0) {
        global $mainframe, $task, $_JCC, $option;

	if (!isset($params) || $params == null) {
		$params = new mosParameters('');
	}

	$params->set('readmore', isset($GLOBALS['jcomments_params_readmore']) ? $GLOBALS['jcomments_params_readmore'] : 0);
	$row->readmore = isset( $GLOBALS['jcomments_row_readmore'] ) ? $GLOBALS['jcomments_row_readmore'] : 0;

        $pvars = array_keys(get_object_vars($params->_params));
	if ($params->get( 'popup' ) || in_array('moduleclass_sfx', $pvars)) {
		return;
	}

        if (file_exists( JCOMMENTS_BASE.DS.'jcomments.php' )) {
	        require_once( JCOMMENTS_BASE.DS.'jcomments.php' );

	        // replace other comment systems tags to JComments equivalents like {jcomment on} 
		JCommentsProcessTags( $row, false, false );           

		if ( (isset($row->introtext)&&preg_match('/{jcomments\s+off}/is', $row->introtext))
		        || (isset($row->fulltext)&&preg_match('/{jcomments\s+off}/is', $row->fulltext)) ) {
			return;
		}

	        $catids = explode(',', $_JCC['enable_categories']);
	        if (($task == 'view')
	        	&&( ($_JCC['enable_categories'] != '' && in_array($row->catid, $catids))
			        || (isset($row->introtext)&&preg_match('/{jcomments\s+on}/is', $row->introtext))
			        || (isset($row->fulltext)&&preg_match('/{jcomments\s+on}/is', $row->fulltext)))) {

		        // process locked discussion
			if ( (isset($row->introtext)&&preg_match('/{jcomments\s+lock}/is', $row->introtext))
			        || (isset($row->fulltext)&&preg_match('/{jcomments\s+lock}/is', $row->fulltext)) ) {
		        	$_JCC['object_locked'] = 1;
		        }
	                return '<br />' . JComments::showComments($row->id, 'com_content', $row->title);
	        } else if (($option == 'com_events')&&($task == 'view_detail')) {
	                return '<br />' . JComments::showComments($row->id, 'com_events', $row->title);
		}
	}
	return "";
} 

function botJCommentsLinkJ15(&$row, &$params, $page=0) {
        global $mainframe, $task, $option, $Itemid;
	global $_JCC, $_JCOMMENTS_ITEMIDCOUNT, $mainframe;

	// disable comments link in 3rd party components (except Events and AlphaContent)
	if ( $option != 'com_content' && $option != 'com_frontpage'
		&& $option != 'com_alphacontent' && $option != 'com_events' ) {
		return;
	}

	if (!isset($params) || $params == null) {
		$params = new mosParameters('');
	}

        if (file_exists( JCOMMENTS_BASE.DS.'jcomments.php' )) {
	        require_once( JCOMMENTS_BASE.DS.'jcomments.php' );

	        // replace other comment systems tags to JComments equivalents like {jcomment on} 
		JCommentsProcessTags( $row, false );           

        	$GLOBALS['JC_CONTENT_COMMENTS_ON'] = preg_match('/{jcomments\s+on}/is', $row->text);
        	$GLOBALS['JC_CONTENT_COMMENTS_OFF'] = preg_match('/{jcomments\s+off}/is', $row->text);
        	$GLOBALS['JC_CONTENT_COMMENTS_LOCKED'] = preg_match('/{jcomments\s+locked}/is', $row->text);

		if ( ! JRequest::getCmd( 'view' ) ) {
			$default = JRequest::getInt('id') ? 'article' : 'frontpage';
			JRequest::setVar('view', $default );
		}

	        if (JRequest::setVar('view') != 'article') {
	                // show link to comments only
			$count = JComments::getCommentsCount( $row->id, 'com_content' );

			$needles = array(
				'article'  => (int) $row->id,
				'category' => (int) $row->catid, 
				'section'  => (int) $row->sectionid, 
			);

			$user =& JFactory::getUser();

			if ($row->access <= $user->get('aid', 0))
			{
				$readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
				$readmore_register = 0;
			}
			else
			{
				if (!defined( '_JCOMMENTS_READMORE_REGISTER' ))
				{
					define( '_JCOMMENTS_READMORE_REGISTER', JText::_('Register to read more...') );
				}

				$readmore_link = JRoute::_("index.php?option=com_user&task=register");
				$readmore_register = 1;
			}

			$link = $readmore_link;



			// load template for comments & readmore links
			$tmpl =& JCommentsFactory::getTemplate($row->id, 'com_content');
			$tmpl->load('tpl_links');

			$tmpl->addVar('tpl_links', 'comments-count', $count);
			$tmpl->addVar('tpl_links', 'comments_link_style', ($readmore_register ? -1 : $count ));
			$tmpl->addVar('tpl_links', 'readmore_register', $readmore_register);
			$tmpl->addVar('tpl_links', 'link-comment', $link);
			$tmpl->addVar('tpl_links', 'link-readmore', $link);

			$tmpl->addVar('tpl_links', 'content-hits', @$row->hits);
			$tmpl->addVar('tpl_links', 'content-author', @$row->created_by_alias ? @$row->created_by_alias : @$row->author);
			$tmpl->addVar('tpl_links', 'content-created', mosFormatDate( @$row->created ));
			$tmpl->addVar('tpl_links', 'content-modified', mosFormatDate( @$row->modified ));
			$tmpl->addVar('tpl_links', 'content-section', @$row->section);
			$tmpl->addVar('tpl_links', 'content-category', @$row->category);
			$tmpl->addVar('tpl_links', 'content-title', @$row->title);

			if(($row->params->get('show_readmore') == 0) || (@$row->readmore == 0)) {
				$tmpl->addVar('tpl_links', 'content-readmore-link', 0);
			} else if(@$row->readmore > 0) {
				$tmpl->addVar('tpl_links', 'content-readmore-link', 1);
			}

		        // enable comments in current content category?
		        if ( $_JCC['enable_categories'] != '' ) {
			        $catids = explode(',', $_JCC['enable_categories']);
		        	if (!in_array(@$row->catid, $catids)) {
					$tmpl->addVar('tpl_links', 'content-comment-link', 0);
			        }
			} else {
				$tmpl->addVar('tpl_links', 'content-comment-link', 0);
			}

			if (preg_match('/{jcomments\s+off}/is', $row->text)) {
				$row->text = preg_replace('/{jcomments\s+off}/is', '', $row->text);
				$tmpl->addVar('tpl_links', 'content-comment-link', 0);
			} else if (preg_match('/{jcomments\s+on}/is', $row->text)) {
				$row->text = preg_replace('/{jcomments\s+on}/is', '', $row->text);
				$tmpl->addVar('tpl_links', 'content-comment-link', 1);
			}

			if ($readmore_register == 1 && $count == 0) {
				$tmpl->addVar('tpl_links', 'content-comment-link', 0);
			}

			// remove all JComments tags
			$row->text = preg_replace('/{jcomments\s+(off|on|lock)}/is', '', $row->text);

			// append links to content text
			$row->text .= $tmpl->renderTemplate('tpl_links');
			// free all memory consumed by the template
			$tmpl->freeTemplate('tpl_links');

			$row->readmore = 0;
			$row->params->set('show_readmore', 0);
			$row->readmore_link = '';
			$row->readmore_register = false;
	        } else {
			// remove all JComments tags
			$row->text = preg_replace('/{jcomments\s+(off|on|lock)}/is', '', $row->text);
	        }
	}
	return true;
} 

function botJCommentsViewJ15(&$row, &$params, $page=0) {
        global $mainframe, $task, $_JCC, $option;

	$full 	= JRequest::getBool('fullview');

	// check whether plugin has been unpublished
	if (!JPluginHelper::isEnabled('content', 'jcomments.contentbot') || $params->get( 'intro_only' )|| $params->get( 'popup' ) || $full) {
		$row->text = preg_replace('/{jcomments\s+(off|on|lock)}/is', '', $row->text);
		return;
	}

	// check for presence of {jcomments off} which is explicits disables this bot for the item
	if ( $GLOBALS['JC_CONTENT_COMMENTS_OFF'] == true ) {
		return true;
	}

        if (file_exists( JCOMMENTS_BASE.DS.'jcomments.php' )) {
	        require_once( JCOMMENTS_BASE.DS.'jcomments.php' );
	        $catids = explode(',', $_JCC['enable_categories']);

	        if ( preg_match( '/{moscomment}/is', $row->text ) ) {
			$row->text = JString::str_ireplace( '{moscomment}', '{jcomments on}', $row->text );
	        }

	        if ((($task == 'view') || ($GLOBALS['JC_CONTENT_TASK'] == 'view')) 
	        && (($_JCC['enable_categories'] != '' && in_array($row->catid, $catids))
		        || ($GLOBALS['JC_CONTENT_COMMENTS_ON'] == true))) {
			$row->text = JString::str_ireplace( '{jcomments\s+on}', '', $row->text );

	        	// process locked discussion
			if ($GLOBALS['JC_CONTENT_COMMENTS_LOCKED'] == true) {
		        	$_JCC['object_locked'] = 1;
		        }

	                return '<br />' . JComments::showComments($row->id, 'com_content', $row->title);
	        } else if (($option == 'com_events')&&($task == 'view_detail')) {
	                return '<br />' . JComments::showComments($row->id, 'com_events', $row->title);
		}
	}
	return "";
} 

/*
 * Replaces or removes commenting systems tags like {moscomment}, {jomcomment} etc 
 */
function JCommentsProcessTags( &$row, $removeTags = false, $fromText = true ) {
	ob_start();
	if ( $removeTags == false ) {
		$jc_on = '/{(moscomment|mxc|jomcomment|easycomments)}/is';
		$jc_off = '{!jomcomment}';
		$jc_lock = '{mxc::closed}';

		if ($fromText == true) {
			$row->text = preg_replace( $jc_on, '{jcomments on}', $row->text );
			$row->text = str_replace( $jc_off, '{jcomments off}', $row->text);
			$row->text = str_replace( $jc_lock, '{jcomments lock}', $row->text);
		} else {
			if (isset($row->introtext)) {
				$row->introtext = preg_replace( $jc_on, '{jcomments on}', $row->introtext);
				$row->introtext = str_replace( $jc_off, '{jcomments off}', $row->introtext);
				$row->introtext = str_replace( $jc_lock, '{jcomments lock}', $row->introtext);
			}
			if (isset($row->fulltext)) {
				$row->fulltext = preg_replace( $jc_on, '{jcomments on}', $row->fulltext);
				$row->fulltext = str_replace( $jc_off, '{jcomments off}', $row->fulltext);
				$row->fulltext = str_replace( $jc_lock, '{jcomments lock}', $row->fulltext);
			}
		}

        } else {
        	$remove_regexp = '/{(moscomment|mxc|msc::closed|!jomcomment|jomcomment|easycomments)}/is';
		if ($fromText == true) {
			$row->text = preg_replace( $remove_regexp, '', $row->text );
		} else {
			if (isset($row->introtext)) {
				$row->introtext = preg_replace( $remove_regexp, '', $row->introtext );
			}
			if (isset($row->fulltext)) {
				$row->fulltext = preg_replace( $remove_regexp, '', $row->fulltext);
			}
		}
	}
	ob_end_clean();
} 
?>