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

mosMainFrame::addClass( 'vcard' );

/**
 * @package Joostina
 * @subpackage Contact
 */
class mosContact extends mosDBTable {
	/**
	 @var int Primary key*/
	public $id;
	/**
	 @var string*/
	public $name;
	/**
	 @var string*/
	public $con_position;
	/**
	 @var string*/
	public $address;
	/**
	 @var string*/
	public $suburb;
	/**
	 @var string*/
	public $state;
	/**
	 @var string*/
	public $country;
	/**
	 @var string*/
	public $postcode;
	/**
	 @var string*/
	public $telephone;
	/**
	 @var string*/
	public $fax;
	/**
	 @var string*/
	public $misc;
	/**
	 @var string*/
	public $image;
	/**
	 @var string*/
	public $imagepos;
	/**
	 @var string*/
	public $email_to;
	/**
	 @var int*/
	public $default_con;
	/**
	 @var int*/
	public $published;
	/**
	 @var int*/
	public $checked_out;
	/**
	 @var datetime*/
	public $checked_out_time;
	/**
	 @var int*/
	public $ordering;
	/**
	 @var string*/
	public $params;
	/**
	 @var int A link to a registered user*/
	public $user_id;
	/**
	 @var int A link to a category*/
	public $catid;
	/**
	 @var int*/
	public $access;

	/**
	 * @param database A database connector object
	 */
	function mosContact() {
		$this->mosDBTable('#__contact_details','id');
	}

	function check() {
		$this->default_con = intval($this->default_con);
		return true;
	}
}

/**
 * @package Joostina
 * class needed to extend vcard class and to correct minor errors
 */
class MambovCard extends vCard {

	// needed to fix bug in vcard class
	function setName($family = '',$first = '',$additional = '',$prefix = '',$suffix = '') {
		$this->properties["N"] = "$family;$first;$additional;$prefix;$suffix";
		$this->setFormattedName(trim("$prefix $first $additional $family $suffix"));
	}

	// needed to fix bug in vcard class
	function setAddress($postoffice = '',$extended = '',$street = '',$city = '',$region ='',$zip = '',$country = '',$type = 'HOME;POSTAL') {
		// $type may be DOM | INTL | POSTAL | PARCEL | HOME | WORK or any combination of these: e.g. "WORK;PARCEL;POSTAL"
		$separator = ';';

		$key = 'ADR';
		if($type != '') {
			$key .= $separator.$type;
		}
		$key .= ';ENCODING=QUOTED-PRINTABLE';

		$return = encode($postoffice);
		$return .= $separator.encode($extended);
		$return .= $separator.encode($street);
		$return .= $separator.encode($city);
		$return .= $separator.encode($region);
		$return .= $separator.encode($zip);
		$return .= $separator.encode($country);

		$this->properties[$key] = $return;
	}

	// added ability to set filename
	function setFilename($filename) {
		$this->filename = $filename.'.vcf';
	}

	// added ability to set position/title
	function setTitle($title) {
		$title = trim($title);

		$this->properties['TITLE'] = $title;
	}

	// added ability to set organisation/company
	function setOrg($org) {
		$org = trim($org);

		$this->properties['ORG'] = $org;
	}

	function getVCard($sitename) {
		$text = 'BEGIN:VCARD';
		$text .= "\r\n";
		$text .= 'VERSION:2.1';
		$text .= "\r\n";

		foreach($this->properties as $key => $value) {
			$text .= "$key:$value";
			$text .= "\r\n";
		}
		$text .= 'REV:'.date('Y-m-d').'T'.date('H:i:s').'Z';
		$text .= "\r\n";
		$text .= 'MAILER: Joostina! vCard for '.$sitename;
		$text .= "\r\n";
		$text .= 'END:VCARD';
		$text .= "\r\n";

		return $text;
	}
}