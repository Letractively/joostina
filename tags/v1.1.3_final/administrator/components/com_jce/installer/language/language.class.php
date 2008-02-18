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
/**
* Language installer
* @package Joostina
* @subpackage Installer
*/
class JCELanguageInstaller extends mosInstaller {

    function componentAdminDir($p_dirname = null) {
		if(!is_null($p_dirname)) {
			$this->i_componentadmindir = mosPathName($p_dirname);
		}
		return $this->i_componentadmindir;
	}

    /**
	* Custom install method
	* @param boolean True if installing from directory
	*/
	function install( $p_fromdir = null ) {
		global $mainframe, $database;

		require_once( $mainframe->getCfg('absolute_path') . '/administrator/components/com_jce/languages/languages.class.php' );

		if (!$this->preInstallCheck( $p_fromdir, 'jcelang' )) {
			return false;
		}

		$xmlDoc = $this->xmlDoc();
		$root 	= &$xmlDoc->documentElement;

		// Set some vars
		$e = &$root->getElementsByPath( 'name', 1);
		$this->elementName($e->getText());
		$this->elementDir( mosPathName( $mainframe->getCfg('absolute_path') . "/mambots/editors/jce/jscripts/tiny_mce/" ) );
		$this->componentAdminDir( mosPathName( $mainframe->getCfg('absolute_path') . "/administrator/components/com_jce/language/" ) );

		// Find files to copy
		if ($this->parseFiles( 'files' ) === false) {
			return false;
		}
		$this->parseFiles( 'administration/files','','',1 );

		if ($e = &$root->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}

        $lang = $root->getAttribute( 'lang' );

        // Insert mambot in DB
		$query = "SELECT id"
		. "\n FROM #__jce_langs"
		. "\n WHERE lang = '" . $lang . "'"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			$this->setError( 1, 'Ошибка SQL: ' . $database->stderr( true ) );
			return false;
		}

		$id = $database->loadResult();

		if (!$id) {
			$row = new jceLanguages( $database );
			$row->name 	= $this->elementName();
			$row->lang  = $lang;
			$row->published = 0;

			if (!$row->store()) {
				$this->setError( 1, 'Ошибка SQL: ' . $row->getError() );
				return false;
			}
		} else {
			$this->setError( 1, 'Язык "' . $this->elementName() . '" уже существует!' );
			return false;
		}
		if ($e = &$root->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}

        return $this->copySetupFile();

	}
	/**
	* Custom install method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function uninstall( $id, $option, $client=0 ) {
		global $mainframe, $database;
		$id = str_replace( array( '\\', '/' ), '', $id );

        $adminpath 	= $mainframe->getCfg('absolute_path') . '/administrator/components/com_jce/language/';
        $basepath 	= $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/';
		$xmlfile 	= $adminpath . $id . '.xml';

		// see if there is an xml install file, must be same name as element
		if (file_exists( $xmlfile )) {
			$this->i_xmldoc = new DOMIT_Lite_Document();
			$this->i_xmldoc->resolveErrors( true );

			if ($this->i_xmldoc->loadXML( $xmlfile, false, true )) {
				$mosinstall =& $this->i_xmldoc->documentElement;
				// get the files element
				$files_element =& $mosinstall->getElementsByPath( 'files', 1 );
                $admin_files_element =& $mosinstall->getElementsByPath( 'administration/files', 1 );

				if (!is_null( $files_element )) {
					$files = $files_element->childNodes;
					foreach ($files as $file) {
						// delete the files
						$filename = $file->getText();
						echo $filename;
						if (file_exists( $basepath . $filename )) {
							echo '<br />Удаление: '. $basepath . $filename;
							$result = unlink( $basepath . $filename );
						}
						echo intval( $result );
					}
				}
				if (!is_null( $admin_files_element )) {
					$files = $admin_files_element->childNodes;
					foreach ($files as $file) {
						// delete the files
						$filename = $file->getText();
						echo $filename;
						if (file_exists( $adminpath . $filename )) {
							echo '<br />Deleting: '. $adminpath . $filename;
							$result = unlink( $adminpath . $filename );
						}
						echo intval( $result );
					}
				}
			}
		} else {
			HTML_installer::showInstallMessage( 'Пустой id языка, невозможно удалить файлы', 'Ошибка удаления', $this->returnTo( $option, 'install&element=language', $client ) );
			exit();
		}

		// remove XML file from front
		@unlink( $xmlfile );

		$query = "DELETE FROM #__jce_langs"
		. "\n WHERE lang = '$id'"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			$msg = $database->stderr( true );
			die( $msg );
		}
		return true;
	}
	/**
	* return to method
	*/
	function returnTo( $option, $element, $client ) {
		return "index2.php?option=$option&task=$element";
	}

}
?>
