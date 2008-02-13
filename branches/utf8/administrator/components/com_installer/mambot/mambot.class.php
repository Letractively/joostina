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
* Module installer
* @package Joostina
* @subpackage Installer
*/
class mosInstallerMambot extends mosInstaller {
	/**
	* Custom install method
	* @param boolean True if installing from directory
	*/
	function install( $p_fromdir = null ) {
		global $mosConfig_absolute_path, $database;

		if (!$this->preInstallCheck( $p_fromdir, 'mambot' )) {
			return false;
		}

		$xmlDoc 	= $this->xmlDoc();
		$mosinstall =& $xmlDoc->documentElement;

		// Set some vars
		$e = &$mosinstall->getElementsByPath( 'name', 1 );
		$this->elementName( $e->getText() );

		$folder = $mosinstall->getAttribute( 'group' );
		$this->elementDir( mosPathName( $mosConfig_absolute_path . '/mambots/' . $folder ) );

		if(!file_exists($this->elementDir()) && !mosMakePath($this->elementDir())) {
			$this->setError( 1, 'Невозможно создать каталог "' . $this->elementDir() . '"' );
			return false;
		}

		if ($this->parseFiles( 'files', 'mambot', 'Нет файлов, отмеченных как мамботы' ) === false) {
			return false;
		}

		// Insert mambot in DB
		$query = "SELECT id"
		. "\n FROM #__mambots"
		. "\n WHERE element = " . $database->Quote( $this->elementName() )
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			$this->setError( 1, 'Ошибка SQL: ' . $database->stderr( true ) );
			return false;
		}

		$id = $database->loadResult();

		if (!$id) {
			$row = new mosMambot( $database );
			$row->name 		= $this->elementName();
			$row->ordering 	= 0;
			$row->folder 	= $folder;
			$row->iscore 	= 0;
			$row->access 	= 0;
			$row->client_id = 0;
			$row->element 	= $this->elementSpecial();
			
			if ($folder == 'editors') {
				$row->published	= 1;
			}

			if (!$row->store()) {
				$this->setError( 1, 'Ошибка SQL: ' . $row->getError() );
				return false;
			}
		} else {
			$this->setError( 1, 'Mambot "' . $this->elementName() . '" already exists!' );
			return false;
		}
		if ($e = &$mosinstall->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}

		return $this->copySetupFile('front');
	}
	/**
	* Custom install method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function uninstall( $id, $option, $client=0 ) {
		global $database, $mosConfig_absolute_path;

		$id = intval( $id );
		$query = "SELECT name, folder, element, iscore"
		. "\n FROM #__mambots"
		. "\n WHERE id = " . (int) $id
		;
		$database->setQuery( $query );

		$row = null;
		$database->loadObject( $row );
		if ($database->getErrorNum()) {
			HTML_installer::showInstallMessage( $database->stderr(), 'Ошибка деинсталляции',
			$this->returnTo( $option, 'mambot', $client ) );
			exit();
		}
		if ($row == null) {
			HTML_installer::showInstallMessage( 'Неправильный id объекта', 'Ошибка деинсталляции',
			$this->returnTo( $option, 'mambot', $client ) );
			exit();
		}

		if (trim( $row->folder ) == '') {
			HTML_installer::showInstallMessage( 'Поле папки пустое, невозможно удалить файлы', 'Ошибка деинсталляции',
			$this->returnTo( $option, 'mambot', $client ) );
			exit();
		}

		$basepath 	= $mosConfig_absolute_path . '/mambots/' . $row->folder . '/';
		$xmlfile 	= $basepath . $row->element . '.xml';

		// see if there is an xml install file, must be same name as element
		if (file_exists( $xmlfile )) {
			$this->i_xmldoc = new DOMIT_Lite_Document();
			$this->i_xmldoc->resolveErrors( true );

			if ($this->i_xmldoc->loadXML( $xmlfile, false, true )) {
				$mosinstall =& $this->i_xmldoc->documentElement;
				// get the files element
				$files_element =& $mosinstall->getElementsByPath( 'files', 1 );
				if (!is_null( $files_element )) {
					$files = $files_element->childNodes;
					foreach ($files as $file) {
						// delete the files
						$filename = $file->getText();
						if (file_exists( $basepath . $filename )) {
							$parts = pathinfo( $filename );
							$subpath = $parts['dirname'];
							if ($subpath != '' && $subpath != '.' && $subpath != '..') {
								echo '<br />Удаление: '. $basepath . $subpath;
								$result = deldir(mosPathName( $basepath . $subpath . '/' ));
							} else {
								echo '<br />Удаление: '. $basepath . $filename;
								$result = unlink( mosPathName ($basepath . $filename, false));
							}
							echo intval( $result );
						}
					}

					// remove XML file from front
					echo "Deleting XML File: $xmlfile";
					@unlink(  mosPathName ($xmlfile, false ) );

					// define folders that should not be removed
					$sysFolders = array(
						'content',
						'search'
					);
					if (!in_array( $row->folder, $sysFolders )) {
						// delete the non-system folders if empty
						if (count( mosReadDirectory( $basepath ) ) < 1) {
							deldir( $basepath );
						}
					}
				}
			}
		}

		if ($row->iscore) {
			HTML_installer::showInstallMessage( $row->name .' - элемент ядра и не может быть удален.<br />Если Вы не хотите его использовать, то отмените его публикацию',
			'Ошибка деинсталляции', $this->returnTo( $option, 'mambot', $client ) );
			exit();
		}

		$query = "DELETE FROM #__mambots"
		. "\n WHERE id = " . (int) $id
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			$msg = $database->stderr;
			die( $msg );
		}
		return true;
	}
}
?>
