<?php
/**
* @package Joostina
* @copyright јвторские права (C) 2008-2009 Joostina team. ¬се права защищены.
* @license Ћицензи€ http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распростран€емое по услови€м лицензии GNU/GPL
* ƒл€ получени€ информации о используемых расширени€х и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет пр€мого доступа
defined( '_VALID_MOS' ) or die();

$use_to = intval($params->get( 'use_to', 0 ));


if(!defined('_SECATOR_INCLUDE')){
	DEFINE('_SECATOR_INCLUDE',1);

	class mod_secator{

		# используем дл€ разделов
		function use_sections(&$params){

			$view_sections	= strval($params->get( 'view_section', 'section_defaults.php' ));

			$config			= &Jconfig::getInstance();
			$file			= $config->config_absolute_path.DS.'modules'.DS.'mod_secator'.DS.'section'.DS.$view_sections;
			$file_defaults	= $config->config_absolute_path.DS.'modules'.DS.'mod_secator'.DS.'section'.DS.'section_defaults.php';

			require is_file($file) ? $file : $file_defaults;
		}

		# используем дл€ категорий
		function use_category(&$params){

			$view_category	= strval($params->get( 'view_category', 'category_defaults.php' ));

			$config			= &Jconfig::getInstance();
			$file			= $config->config_absolute_path.DS.'modules'.DS.'mod_secator'.DS.'category'.DS.$view_category;
			$file_defaults	= $config->config_absolute_path.DS.'modules'.DS.'mod_secator'.DS.'category'.DS.'category_defaults.php';

			require is_file($file) ? $file : $file_defaults;
		}


		/* получение типа публикации */
		function get_where_published($show_published=null,$name='published'){

			if($show_published==0){
				$_where_published = $name.'=0';
			}elseif($show_published==2){
				$_where_published = ' ';
			}else{
				$_where_published = $name.'=1';
			}
			return $_where_published;
		}


		function get_secator_link($row,$params){
			$link = 'index.php?option=com_content';

			$Itemid = $params->get( 'Itemid', null );
			$use_to = $params->get( 'use_to', 0 );

			if(!$Itemid){
				global $Itemid;
			}

			if($use_to==1){
				// используем категроии
				$task = $params->get( 'task_category', 'blogcategory' );
			}else{
				$task = $params->get( 'task_category', 'blogsection' );
			}

			$link = $link.'&task='.$task.'&id='.$row->id.'&Itemid='.$Itemid;

			return sefRelToAbs($link);
		}

		/* подсчет числа содержимого в категории */
		function get_count_content(){
			static $return;

			if(!$return){
				$db = &database::getInstance();
				$sql = 'SELECT catid,count(id) as count FROM #__content GROUP BY catid';
				$db->setQuery($sql);
				$cats = $db->loadObjectList();
				$ret = array();
				foreach($cats as $cat){
					$ret[$cat->catid]=$cat->count;
				}
				$return = $ret;
			}
			return $return;
		}
	}
}

if($use_to==1){
	mod_secator::use_category($params); // используем категроии
}else{
	mod_secator::use_sections($params); // используем разделы
}