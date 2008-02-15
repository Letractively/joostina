<?php 
require(dirname(__FILE__).'/../../die.php');
/**
 * Wraps HTML representation of the Xmap tree as an unordered list (ul)
 * @author Daniel Grothe
 * @see joomla.php
 * @package Xmap
 */

/** Wraps HTML output */
class XmapHtml {

	/** Convert sitemap tree to an 'unordered' html list.
	 * This function uses recursion, keep unnecessary code out of this!
	 */
	function &getHtmlList( &$tree, &$exlink, $level = 0,&$count ) {
		global $Itemid;
		
		if( !$tree ) {
			$result = '';
			return $result;
		}
		
		$out = '<ul class="level_'.$level.'">';
		foreach($tree as $node) {
			$count++;
			if ( $Itemid == $node->id )
				$out .= '<li class="active">';
			else
				$out .= '<li>';

			$link = Xmap::getItemLink($node);;

			if( !isset($node->browserNav) )
				$node->browserNav = 0;

			switch( $node->browserNav ) {
				case 1:		// open url in new window
					$ext_image = '';
					if( $exlink[0] ){
						$ext_image = '&nbsp;<img src="'. $GLOBALS['mosConfig_live_site'] .'/components/com_xmap/images/'. $exlink[1] .'" alt="' . _XMAP_SHOW_AS_EXTERN_ALT . '" title="' . _XMAP_SHOW_AS_EXTERN_ALT . '" border="0" />';
					}
					$out .= '<a href="'. $link .'" title="'. $node->name .'" target="_blank">'. $node->name . $ext_image .'</a>';
					break;

				case 2:		// open url in javascript popup window
					$ext_image = '';
					if( $exlink[0] ) {
						$ext_image = '&nbsp;<img src="'. $GLOBALS['mosConfig_live_site'] .'/components/com_xmap/images/'. $exlink[1] .'" alt="' . _XMAP_SHOW_AS_EXTERN_ALT . '" title="' . _XMAP_SHOW_AS_EXTERN_ALT . '" border="0" />';
					}
					$out .= '<a href="'. $link .'" title="'. $node->name .'" target="_blank" '. "onClick=\"javascript: window.open('". $link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false;\">". $node->name . $ext_image."</a>";
					break;

				case 3:		// no link
					$out .= '<span>'. $node->name .'</span>';
					break;

				default:	// open url in parent window
					$out .= '<a href="'. $link .'" title="'. $node->name .'">'. $node->name .'</a>';
					break;
			}

			if( isset($node->tree) ) {
				$out .= XmapHtml::getHtmlList( $node->tree, $exlink, $level + 1,$count );
			}
			$out .= '</li>' . "\n";
		}
		$out .= '</ul>' . "\n";
		return $out;
	}

	/** Print component heading, etc. Then call getHtmlList() to print list */
	function printTree( &$xmap, &$root ) {
		global $database, $Itemid;
		$config = &$xmap->config;
		$sitemap = &$xmap->sitemap;
	
		$menu = new mosMenu( $database );
		$menu->load( $Itemid ); // Load params for the Xmap menu-item
		$title = $menu->name;
		
		$exlink[0] = $sitemap->exlinks;// image to mark popup links
		$exlink[1] = $sitemap->ext_image;

		if( $sitemap->columns > 1 ) { // calculate column widths
			$total = count($root);
			$columns = $total < $sitemap->columns ? $total : $sitemap->columns;
			$width	= (100 / $columns) - 1;
		}

		echo '<div class="'. $config->classname .'">';
		echo '<div class="componentheading">'.$title.'</div>';
		echo '<div class="contentpaneopen"'. ($sitemap->columns > 1 ? ' style="float:left;width:100%;"' : '') .'>';
		
		$count=0;
		if( $sitemap->show_menutitle || $sitemap->columns > 1 ) {				// each menu gets a separate list
			foreach( $root as $menu ) {
				
				if( $sitemap->columns > 1 )									// use columns
					echo '<div style="float:left;width:'.$width.'%;">';
				
				if( $sitemap->show_menutitle )								// show menu titles
					echo '<h2 class="menutitle">'.$menu->name.'</h2>';

				echo XmapHtml::getHtmlList( $menu->tree, $exlink,'',$count );
				if( $sitemap->columns > 1 )
					echo "</div>\n";
			}

			if( $sitemap->columns > 1 )
				echo '<div style="clear:left"></div>';

		} else {															// don't show menu titles, all items in one big tree
			$tmp = array();
			foreach( $root as $menu ) {										// concatenate all menu-trees
				foreach( $menu->tree as $node ) {
					$tmp[] = $node;
				}
			}
			echo XmapHtml::getHtmlList( $tmp, $exlink,'',$count );
		}
		
		//BEGIN: Advertisement
		if( $sitemap->includelink ) {
			echo "<a href=\"http://joomla.vargas.co.cr\" style=\"font-size:1px;display:none;\">Powered by Xmap!</a>";
		}
		//END: Advertisement
		
		echo "</div>";
		echo "</div>\n";

		return $count;

	}
}
?>