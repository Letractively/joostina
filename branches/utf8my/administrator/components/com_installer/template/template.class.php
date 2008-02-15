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

// ensure user has access to this function
if (!$acl->acl_check( 'administration', 'manage', 'users', $GLOBALS['my']->usertype, 'components', 'com_templates' )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

/**
* Template installer
* @package Joostina
* @subpackage Installer
*/
class mosInstallerTemplate extends mosInstaller {
	/**
	* Custom install method
	* @param boolean True if installing from directory
	*/
	function install( $p_fromdir = null ) {
		global $mosConfig_absolute_path,$database;

		if (!$this->preInstallCheck( $p_fromdir, 'template' )) {
			return false;
		}

		$xmlDoc 	=& $this->xmlDoc();
		$mosinstall =& $xmlDoc->documentElement;

		$client = '';
		if ($mosinstall->getAttribute( 'client' )) {
			$validClients = array( 'administrator' );
			if (!in_array( $mosinstall->getAttribute( 'client' ), $validClients )) {
				$this->setError( 1, 'Неизвестный тип клиента ['.$mosinstall->getAttribute( 'client' ).']' );
				return false;
			}
			$client = 'admin';
		}

		// Set some vars
		$e = &$mosinstall->getElementsByPath( 'name', 1 );
		$this->elementName($e->getText());
		$this->elementDir( mosPathName( $mosConfig_absolute_path
		. ($client == 'admin' ? '/administrator' : '')
		. '/templates/' . strtolower(str_replace(" ","_",$this->elementName())))
		);

		if (!file_exists( $this->elementDir() ) && !mosMakePath( $this->elementDir() )) {
			$this->setError(1, 'Невозможно создать каталог "' . $this->elementDir() . '"' );
			return false;
		}

		if ($this->parseFiles( 'files' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'images' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'css' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'media' ) === false) {
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

		// Delete directories
		$path = $mosConfig_absolute_path
		. ($client == 'admin' ? '/administrator' : '' )
		. '/templates/' . $id;

		$id = str_replace( '..', '', $id );
		if (trim( $id )) {
			if (is_dir( $path )) {
				return deldir( mosPathName( $path ) );
			} else {
				HTML_installer::showInstallMessage( 'Невозможно удалить файл, т.к. каталог не существует', 'Ошибка деинсталляции',
					$this->returnTo( $option, 'template', $client ) );
			}
		} else {
			HTML_installer::showInstallMessage( 'Невозможно удалить файлы, т.к. Id шаблона пустой', 'Ошибка деинсталляции',
				$this->returnTo( $option, 'template', $client ) );
			exit();
		}
	}
	/**
	* return to method
	*/
	function returnTo( $option, $element, $client ) {
		return "index2.php?option=com_templates&client=$client";
	}
}
?>
