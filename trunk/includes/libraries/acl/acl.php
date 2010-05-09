<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

class Jacl {

	private static $instance;
	private static $acl;

	public static function getInstance( $isAdmin = false ) {
		if (self::$instance === NULL) {
			set_include_path( JPATH_BASE.'/includes/libraries/acl/' );
			require_once('Zend/Acl.php');
			require_once('Zend/Acl/Role.php');
			require_once('Zend/Acl/Resource.php');
			self::$acl = new Zend_Acl;

			if( !$isAdmin ) {
				global $my;
				$usertype = ( $my->usertype == 'Super Administrator') ? 'superadmin' : $my->usertype;
				$m = '_acl_'.$usertype;
				call_user_func_array('Jacl::'.$m, array());
			}
			self::$instance = new self;
		}
	}

	public static function isAllowed( $obj, $task = null ) {
		global $my;

		if (self::$instance === NULL) {
			self::getInstance();
		}

		$usertype = ( $my->usertype == 'Super Administrator') ? 'superadmin' : $my->usertype;
		return self::$acl->isAllowed( $usertype ,$obj, $task );
	}

	public static function isDeny( $obj, $task = null ) {
		global $my;

		if (self::$instance === NULL) {
			self::getInstance();
		}

		$usertype = ( $my->usertype == 'Super Administrator') ? 'superadmin' : $my->usertype;
		return !self::$acl->isAllowed( $usertype ,$obj, $task );
	}

	/*
	 * Установка прав доступа для супер - администратора
	*/
	private static function _acl_superadmin() {
		self::$acl->addRole( new Zend_Acl_Role('superadmin') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') ); // доступ к комментариям

		self::$acl
				->allow('superadmin', 'comments'); // супер-админ может с комментариями всё
	}

	/*
	 * Установка прав доступа для администратора
	*/
	private static function _acl_admin() {
		self::$acl->addRole( new Zend_Acl_Role('admin') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') ); // доступ к комментариям

		self::$acl
				->allow('admin', 'comments', array('view','add','edit', /* 'delete' */ ) ); // админ может с комментариями всё кроме удаления
	}

	/*
	 * Установка прав доступа для авторизованных пользователй фронта сайта
	*/
	private static function _acl_user() {

		self::$acl->addRole( new Zend_Acl_Role('user') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') );

		self::$acl
				->allow('user', 'comments', 'view')
				->allow('user', 'comments', 'add');

	}

	/*
	 * Установка прав доступа для не авторизованных пользователй фронта сайта
	*/
	private static function _acl_guest() {
		$role= new Zend_Acl_Role('guest');
		self::$acl->addRole($role);

		$resource_comments = new Zend_Acl_Resource('comments');
		self::$acl->add($resource_comments);
		self::$acl->allow('guest', 'comments', 'view'); // гость может смотреть комментарии
		//self::$acl->allow('guest', 'comments', 'add'); // но не может их добавлять

	}


	public static function init_admipanel() {

		self::getInstance( true );

		// собираем роли
		self::$acl->addRole( new Zend_Acl_Role('guest') )
				->addRole( new Zend_Acl_Role('user') )
				->addRole( new Zend_Acl_Role('superadmin') )
				->addRole( new Zend_Acl_Role('admin')  )
				->addRole( new Zend_Acl_Role('manager')  )
				//->addRole( new Zend_Acl_Role('podmanager'), 'manager' ) /* это как бэ показывает что мы можем делать подгруппы */
				->addRole( new Zend_Acl_Role('editor')  );

		// собираем ресурсы - компонентам
		self::$acl
				->add( new Zend_Acl_Resource('adminpanel'))     // вообще доступ в админку
				->add( new Zend_Acl_Resource('config'))          // глобальная конфигурации
				->add( new Zend_Acl_Resource('mambots'))	   // мамботы
				->add( new Zend_Acl_Resource('modules'))        // модули
				->add( new Zend_Acl_Resource('filemanager'))     // файловый менеджер
				->add( new Zend_Acl_Resource('installer'))        // установщик расширений
				->add( new Zend_Acl_Resource('languages'))      // управление языками
				->add( new Zend_Acl_Resource('linkeditor'))       // редактор ссылок на компоненты
				->add( new Zend_Acl_Resource('menumanager'))   // менеджер корневого меню
				->add( new Zend_Acl_Resource('pages'))          // управление страницами
				->add( new Zend_Acl_Resource('quickicons'))       // кнопками быстрого доступа
				->add( new Zend_Acl_Resource('templates'))       // управление шаблонами
				->add( new Zend_Acl_Resource('trash'))           // корзина
				->add( new Zend_Acl_Resource('users'))           // управление пользователями
				->add( new Zend_Acl_Resource('cache'));           // управление кешем


		self::$acl
				->deny('guest') // неавторизованным ничего нелья
				->deny('user') // просто пользователям ничего нелья
				->allow('superadmin'); // суперадмину можно всё

		//_xdump(self::$acl);
		//exit();

	}


}