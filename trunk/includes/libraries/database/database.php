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

/**
 * Класс работы с базой данных
 * @subpackage Database
 * @package Joostina
 */
class database {
    
    private static $_instance;
    /**
     @var string переменныя хранения активной SQL команды */
    protected $_sql;
    /**
     @var int код ошибки работы с базой данных */
    protected $_errorNum = 0;
    /**
     @var string текст ошибки работы с базой данных */
    protected $_errorMsg;
    /**
     @var string преффикс таблиц активного соединения */
    protected $_table_prefix;
    /**
     @var активное соединение с базой данных */
    protected $_resource;
    /**
     @var результат последнего активного SQL запроса */
    protected $_cursor;
    /**
     @var boolean параметр включения отладки работы с базой данных */
    protected $_debug;
    /**
     @var int лимит для активного запроса */
    protected $_limit;
    /**
     @var int смещение для актиновго запроса */
    protected $_offset;
    /**
     @var string null/zero строка для поля типа даты */
    protected $_nullDate = '0000-00-00 00:00:00';
    /**
     @var string символ квотирования названия полей таблиц */
    protected $_nameQuote = '`';

    /**
     *
     * @param <type> $host
     * @param <type> $user
     * @param <type> $pass
     * @param <type> $db
     * @param <type> $table_prefix
     * @param <type> $goOffline
     * @param <type> $debug
     */
    private function __construct($host = 'localhost', $user = 'root', $pass = '', $db = '', $table_prefix = '', $goOffline = true, $debug = 0) {
        $this->_debug = $debug;
        $this->_table_prefix = $table_prefix;

        // perform a number of fatality checks, then die gracefully
        if (!function_exists('mysql_connect')) {
            $mosSystemError = 1;
            if ($goOffline) {
                include JPATH_BASE . '/templates/system/offline.php';
                exit();
            }
        }
        if (!($this->_resource = @mysql_connect($host, $user, $pass, true))) {
            $mosSystemError = 2;
            if ($goOffline) {
                include JPATH_BASE . '/templates/system/offline.php';
                exit();
            }
        }

        if ($db != '' && !mysql_select_db($db, $this->_resource)) {
            $mosSystemError = 3;
            if ($goOffline) {
                include JPATH_BASE . '/templates/system/offline.php';
                exit();
            }
        }

        if ($this->_debug == 1) {
            mysql_query('set profiling=1', $this->_resource);
            mysql_query('set profiling_history_size=150', $this->_resource);
        };

        // устанавливаем верное соединение с сервером базы данных
        mysql_set_charset('utf8');
    }

    /**
     *
     * @return database - объект базы данных
     */
    public static function getInstance() {

        JDEBUG ? jd_inc('database::getInstance()') : null;

        if (self::$_instance === NULL) {
            $config = Jconfig::getInstance();

            $database = new database($config->config_host, $config->config_user, $config->config_password, $config->config_db, $config->config_dbprefix, true, JDEBUG);
            if ($database->getErrorNum()) {
                $mosSystemError = $database->getErrorNum();
                include JPATH_BASE . DS . 'templates/system/offline.php';
                exit();
            }
            self::$_instance = $database;
        }
        return self::$_instance;
    }

    /**
     *
     * @param <type> $level
     */
    public function debug($level) {
        $this->_debug = intval($level);
    }

    /**
     *
     * @return <type>
     */
    public function getErrorNum() {
        return $this->_errorNum;
    }

    /**
     *
     * @return <type>
     */
    public function getErrorMsg() {
        return str_replace(array("\n", "'"), array('\n', "\'"), $this->_errorMsg);
    }

    /**
     *
     * @param <type> $text
     * @param <type> $extra
     * @return <type>
     */
    public function getEscaped($text, $extra = false) {
        $string = mysql_real_escape_string($text, $this->_resource);
        return $extra ? addcslashes($string, '%_') : $string;
    }

    /**
     *
     * @param <type> $text
     * @param <type> $escaped
     * @return <type>
     */
    public function Quote($text, $escaped = true) {
        return '\'' . ($escaped ? $this->getEscaped($text) : $text) . '\'';
    }

    /**
     *
     * @param <type> $s
     * @return <type>
     */
    public function NameQuote($s) {
        $q = $this->_nameQuote;
        return (strlen($q) == 1) ? $q . $s . $q : $q{0}. $s . $q{1};
    }

    /**
     *
     * @return <type>
     */
    public function getPrefix() {
        return $this->_table_prefix;
    }

    /**
     *
     * @return <type>
     */
    public function getNullDate() {
        return $this->_nullDate;
    }

    /**
     *
     * @param <type> $sql
     * @param <type> $offset
     * @param <type> $limit
     * @param <type> $prefix
     * @return <type>
     */
    public function setQuery( $sql, $offset = 0, $limit = 0, $prefix = '#__') {
        $this->_sql = $this->replacePrefix(trim($sql), $prefix);
        $this->_limit = intval($limit);
        $this->_offset = intval($offset);
        return $this;
    }

    /**
     *
     * @param <type> $sql
     * @param <type> $prefix
     * @return <type>
     */
    private function replacePrefix($sql, $prefix = '#__') {
        return str_replace('#__', $this->_table_prefix, $sql);
    }

    /**
     *
     * @return <type>
     */
    public function getResource() {
        return $this->_resource;
    }

    /**
     *
     * @return <type>
     */
    public function getQuery() {
        return '<pre>' . htmlspecialchars($this->_sql) . '</pre>';
    }

    /**
     *
     * @return <type>
     */
    public function query() {
        if ($this->_limit > 0 && $this->_offset == 0) {
            $this->_sql .= "\nLIMIT $this->_limit";
        } elseif($this->_limit > 0 || $this->_offset > 0) {
            $this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
        }

        $this->_errorNum = 0;
        $this->_errorMsg = '';
        $this->_cursor = mysql_query($this->_sql, $this->_resource);
        // для оптимизации расхода памяти можно раскомментировать следующие строки, но некоторые особенно кривые расширения сразу же отвалятся
        //unset($this->_sql);
        //return $this->_cursor;
        // /*
        if (!$this->_cursor) {
            $this->_errorNum = mysql_errno($this->_resource);
            $this->_errorMsg = mysql_error($this->_resource) . " SQL=$this->_sql";
            if ($this->_debug) {
                $this->getUtils()->show_db_error(mysql_error($this->_resource), $this->_sql);
            }
            return false;
        }

        // тут тоже раскомментировать, что бу верхнее условие оказалось в комментариях, или еще лучше его вообще удалить
        //*/
        return $this->_cursor;
    }

    /**
     *
     * @return <type>
     */
    public function getAffectedRows() {
        return mysql_affected_rows($this->_resource);
    }

    /**
     *
     * @param <type> $cur
     * @return <type>
     */
    public function getNumRows($cur = null) {
        return mysql_num_rows($cur ? $cur : $this->_cursor);
    }

    /**
     *
     * @return <type>
     */
    public function loadResult() {
        if (!($cur = $this->query())) {
            return null;
        }

        $ret = ($row = mysql_fetch_row($cur)) ? $row[0] : null;

        mysql_free_result($cur);
        return $ret;
    }

    /**
     *
     * @param <type> $numinarray
     * @return <type>
     */
    public function loadResultArray($numinarray = 0) {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_row($cur)) {
            $array[] = $row[$numinarray];
        }
        mysql_free_result($cur);
        return $array;
    }

    /**
     *
     * @param <type> $key
     * @return <type>
     */
    public function loadAssocList($key = '') {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_assoc($cur)) {
            if ($key) {
                $array[$row[$key]] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysql_free_result($cur);

        return $array;
    }

    /**
     *
     * @return <type>
     */
    public function loadAssocRow() {
        if (!($cur = $this->query())) {
            return null;
        }
        $row = mysql_fetch_assoc($cur);
        mysql_free_result($cur);

        return $row;
    }

    /**
     *
     * @param <type> $object
     * @return <type>
     */
    public function loadObject( & $object) {
        if ($object != null) {
            if (!($cur = $this->query())) {
                return false;
            }
            if ($array = mysql_fetch_assoc($cur)) {
                mysql_free_result($cur);
                mosBindArrayToObject($array, $object, null, null, false);
                return true;
            } else {
                return false;
            }
        } else {
            if ($cur = $this->query()) {
                if ($object = mysql_fetch_object($cur)) {
                    mysql_free_result($cur);
                    return true;
                } else {
                    $object = null;
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     *
     * @param <type> $key
     * @return <type>
     */
    public function loadObjectList($key = '') {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_object($cur)) {
            if ($key) {
                $array[$row->$key] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysql_free_result($cur);

        return $array;
    }

    /**
     *
     * @return <type>
     */
    public function loadRow() {
        if (!($cur = $this->query())) {
            return null;
        }
        $ret = ($row = mysql_fetch_row($cur)) ?  $row : null;
        mysql_free_result($cur);

        return $ret;
    }

    /**
     *
     * @param <type> $key
     * @return <type>
     */
    public function loadRowList($key = null) {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_row($cur)) {
            if (!is_null($key)) {
                $array[$row[$key]] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysql_free_result($cur);

        return $array;
    }

    /**
     *
     * @param <type> $table
     * @param <type> $object
     * @param <type> $keyName
     * @param <type> $verbose
     * @return <type>
     */
    public function insertObject($table, $object, $keyName = null, $verbose = false) {

        $fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";

        $fields = array();
        foreach (get_object_vars($object) as $k => $v) {
            if (is_array($v) or is_object($v) or $v === null) {
                continue;
            }
            if ($k[0] == '_') { // внешние поля
                continue;
            }
            $fields[] = $this->NameQuote($k);
            $values[] = $this->Quote($v);
        }
        $this->setQuery(sprintf($fmtsql, implode(",", $fields), implode(",", $values)));
        ($verbose) && print "$fmtsql<br />\n";
        if (!$this->query()) {
            return false;
        }
        $id = mysql_insert_id($this->_resource);
        ($verbose) && print "id=[$id]<br />\n";
        if ($keyName && $id) {
            $object->$keyName = $id;
        }

        return ($id>0) ? $id : true;
    }

    /**
     *
     * @param <type> $table
     * @param <type> $object
     * @param <type> $keyName
     * @param <type> $updateNulls
     * @return <type>
     */
    public function updateObject($table, $object, $keyName, $updateNulls = true) {

        $fmtsql = "UPDATE $table SET %s  WHERE %s";
        $tmp = array();
        foreach (get_object_vars($object) as $k => $v) {
            if (is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
                continue;
            }
            if ($k == $keyName) { // PK not to be updated
                $where = $keyName . '=' . $this->Quote($v);
                continue;
            }
            if ($v === null && !$updateNulls) {
                continue;
            }
            if ($v == '') {
                $val = "''";
            } else {
                $val = $this->Quote($v);
            }
            $tmp[] = $this->NameQuote($k) . '=' . $val;
        }
        $this->setQuery(sprintf($fmtsql, implode(",", $tmp), $where));

        return (bool) $this->query();
    }

    /**
     *
     * @param <type> $showSQL
     * @return <type>
     */
    public function stderr($showSQL = false) {
        return "DB function failed with error number $this->_errorNum <br /><font color=\"red\">$this->_errorMsg</font>" . ($showSQL ? "<br />SQL = <pre>$this->_sql</pre>" : '');
    }

    /**
     *
     * @return <type>
     */
    public function insertid() {
        return mysql_insert_id($this->_resource);
    }

    /**
     * Fudge method for ADOdb compatibility
     */
    // TODO убрать
    public function GenID() {
        return 0;
    }

    /**
     *
     * @return <type>
     */
    public function getCursor() {
        return $this->_cursor;
    }

    /**
     *
     * @return UtulsDB
     */
    public function getUtils() {
        return new UtulsDB();
    }
}

/**
 * Утилиты для работы с базой данных
 */
class UtulsDB extends database {

    /**
     *
     * @return <type>
     */
    public function getVersion() {
        return mysql_get_server_info($this->_resource);
    }

    /**
     *
     * @param <type> $only_joostina
     * @return <type>
     */
    public function getTableList($only_joostina = true) {
        $only_joostina = $only_joostina ? " LIKE '$this->_table_prefix%' " : '';
        return $this->setQuery('SHOW TABLES ' . $only_joostina)->loadResultArray();
    }

    /**
     *
     * @param <type> $tables
     * @return <type>
     */
    public function getTableCreate($tables) {
        $result = array();

        foreach ($tables as $tblval) {
            $rows = $this->setQuery('SHOW CREATE table ' . $this->getEscaped($tblval))->loadRowList();
            foreach ($rows as $row) {
                $result[$tblval] = $row[1];
            }
        }

        return $result;
    }

    /**
     *
     * @param <type> $tables
     * @return <type>
     */
    public function getTableFields($tables) {
        $result = array();

        foreach ($tables as $tblval) {
            $fields = $this->setQuery('SHOW FIELDS FROM ' . $tblval)->loadObjectList();

            foreach ($fields as $field) {
                $result[$tblval][$field->Field] = preg_replace("/[(0-9)]/", '', $field->Type);
            }
        }

        return $result;
    }

    /**
     *
     * @param <type> $abort_on_error
     * @param <type> $p_transaction_safe
     * @return <type>
     */
    public function query_batch($abort_on_error = true, $p_transaction_safe = false) {
        $this->_errorNum = 0;
        $this->_errorMsg = '';
        if ($p_transaction_safe) {
            $si = mysql_get_server_info($this->_resource);
            preg_match_all("/(\d+)\.(\d+)\.(\d+)/i", $si, $m);
            if ($m[1] >= 4) {
                $this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
            } else
            if ($m[2] >= 23 && $m[3] >= 19) {
                $this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
            } else
            if ($m[2] >= 23 && $m[3] >= 17) {
                $this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
            }
        }
        $query_split = preg_split("/[;]+/", $this->_sql);
        $error = 0;
        foreach ($query_split as $command_line) {
            $command_line = trim($command_line);
            if ($command_line != '') {
                $this->_cursor = mysql_query($command_line, $this->_resource);
                if (!$this->_cursor) {
                    $error = 1;
                    $this->_errorNum .= mysql_errno($this->_resource) . ' ';
                    $this->_errorMsg .= mysql_error($this->_resource) . " SQL=$command_line <br />";
                    if ($abort_on_error) {
                        return $this->_cursor;
                    }
                }
            }
        }
        return (bool) $error;
    }

    /**
     *
     * @return <type>
     */
    public function explain() {
        $temp = $this->_sql;
        $this->_sql = 'EXPLAIN ' . $this->_sql;
        $this->query();

        if (!($cur = $this->query())) {
            return null;
        }
        $first = true;

        $buf = '<table cellspacing="1" cellpadding="2" border="0" bgcolor="#000000" align="center">';
        $buf .= $this->getQuery();
        while ($row = mysql_fetch_assoc($cur)) {
            if ($first) {
                $buf .= '<tr>';
                foreach ($row as $k => $v) {
                    $buf .= '<th bgcolor="#ffffff">' . $k . '</th>';
                }
                $buf .= '</tr>';
                $first = false;
            }
            $buf .= '<tr>';
            foreach ($row as $k => $v) {
                $buf .= '<td bgcolor="#ffffff">' . $v . '</td>';
            }
            $buf .= '</tr>';
        }
        $buf .= '</table><br />';
        mysql_free_result($cur);

        $this->_sql = $temp;

        return '<div style="background-color:#FFFFCC" align="left">' . $buf . '</div>';
    }
    
    /**
     *
     * @param <type> $message
     * @param <type> $sql
     */
    public function show_db_error($message, $sql = null) {
        echo '<div style="display:block;width:100%;"><b>DB::error:</b> ';
        echo $message;
        echo $sql ? '<pre>' . $sql . '</pre><b>UseFiles</b>::' : '';
        if (function_exists('debug_backtrace')) {
            foreach (debug_backtrace() as $back) {
                if (@$back['file']) {
                    echo '<br />' . $back['file'] . ':' . $back['line'];
                }
            }
        }
        echo '</div>';
    }

}

/**
 * mosDBTable Abstract Class.
 * @abstract
 * @package Joostina
 * @subpackage Database
 *
 * Parent classes to all database derived objects.  Customisation will generally
 * not involve tampering with this object.
 * @author Andrew Eddie <eddieajau@users.sourceforge.net
 */
class mosDBTable {
    
    public $_tbl;
    public $_tbl_key;
    public $_error;
    public $_db;

    /**
     *	Object constructor to set table and key field
     *
     *	Can be overloaded/supplemented by the child class
     *	@param string $table name of the table in the db schema relating to child class
     *	@param string $key name of the primary key field in the table
     */
    function mosDBTable($table, $key, $db = null) {
        $this->_tbl = $table;
        $this->_tbl_key = $key;
        $this->_db = $db ? $db : database::getInstance();
    }

    /**
     * Returns an array of public properties
     * @return array
     */
    function getPublicProperties() {
        static $cache = null;
        if (is_null($cache)) {
            $cache = array();
            foreach (get_class_vars(get_class($this)) as $key => $val) {
                if (substr($key, 0, 1) != '_') {
                    $cache[] = $key;
                }
            }
        }
        return $cache;
    }

    /**
     * Filters public properties
     * @access protected
     * @param array List of fields to ignore
     */
    function filter($ignoreList = null) {
        $ignore = is_array($ignoreList);

        $iFilter = InputFilter::getInstance();
        foreach ($this->getPublicProperties() as $k) {
            if ($ignore && in_array($k, $ignoreList)) {
                continue;
            }
            $this->$k = $iFilter->process($this->$k);
        }
    }

    /**
     *	@return string Returns the error message
     */
    function getError() {
        return $this->_error;
    }

    /**
     * Gets the value of the class variable
     * @param string The name of the class variable
     * @return mixed The value of the class var (or null if no var of that name exists)
     */
    function get($_property) {
        return isset($this->$_property) ? $this->$_property : null;
    }

    /**
     * Set the value of the class variable
     * @param string The name of the class variable
     * @param mixed The value to assign to the variable
     */
    function set($_property, $_value) {
        $this->$_property = $_value;
    }

    /**
     * Resets public properties
     * @param mixed The value to set all properties to, default is null
     */
    function reset($value = null) {
        $keys = $this->getPublicProperties();
        foreach ($keys as $k) {
            $this->$k = $value;
        }
    }

    /**
     *	binds a named array/hash to this object
     *
     *	can be overloaded/supplemented by the child class
     *	@param array $hash named array
     *	@return null|string	null is operation was satisfactory, otherwise returns an error
     */
    function bind($array, $ignore = '') {
        if (!is_array($array)) {
            $this->_error = strtolower(get_class($this)) . '::ошибка выполнения bind.';
            return false;
        } else {
            return mosBindArrayToObject($array, $this, $ignore);
        }
    }

    /**
     *	binds an array/hash to this object
     *	@param int $oid optional argument, if not specifed then the value of current key is used
     *	@return any result from the database operation
     */
    function load($oid = null) {
        $k = $this->_tbl_key;

        if ($oid !== null) {
            $this->$k = $oid;
        }

        $oid = $this->$k;

        if ($oid === null) {
            return false;
        }

        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $value) {
            if (($name != $k) and ($name != '_db') and ($name != '_tbl') and ($name != '_tbl_key')) {
                $this->$name = $value;
            }
        }

        $this->reset();

        $query = 'SELECT * FROM ' . $this->_tbl . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->Quote($oid);
        return $this->_db->setQuery($query)->loadObject($this);
    }

    /**
     *	generic check method
     *
     *	can be overloaded/supplemented by the child class
     *	@return boolean True if the object is ok
     */
    public function check() {
        return true;
    }

    /**
     * Inserts a new row if id is zero or updates an existing row in the database table
     *
     * Can be overloaded/supplemented by the child class
     * @param boolean If false, null object variables are not updated
     * @return null|string null if successful otherwise returns and error message
     */
    public function store($updateNulls = false) {
        $k = $this->_tbl_key;

        if ($this->$k != 0) {
            $ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
        } else {
            $ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
        }

        if (!$ret) {
            $this->_error = strtolower(get_class($this)) . "::ошибка выполнения store<br />" . $this->_db->getErrorMsg();
            return false;
        } else {
            return true;
        }
    }

    /**
     *	Default delete method
     *
     *	can be overloaded/supplemented by the child class
     *	@return true if successful otherwise returns and error message
     */
    function delete($oid = null) {
        $k = $this->_tbl_key;

        if ($oid) {
            $this->$k = intval($oid);
        }

        $query = "DELETE FROM $this->_tbl WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
        $this->_db->setQuery($query);

        if ($this->_db->query()) {
            return true;
        } else {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }
    }
    
    function delete_array($oid = array(), $key = false, $table = false) {
        $key = $key ? $key : $this->_tbl_key;
        $table = $table ? $table : $this->_tbl;

        $query = "DELETE FROM $table WHERE $key IN (" . implode(',', $oid) . ')';

        if ($this->_db->setQuery($query)->query()) {
            return true;
        } else {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }
    }

    /**
     * Generic save function
     * @param array Source array for binding to class vars
     * @param string Filter for the order updating. This is expected to be a valid (and safe!) SQL expression
     * @returns TRUE if completely successful, FALSE if partially or not succesful
     * NOTE: Filter will be deprecated in verion 1.1
     */
    function save($source, $order_filter = '') {
        if (!$this->bind($source)) {
            return false;
        }
        if (!$this->check()) {
            return false;
        }
        if (!$this->store()) {
            return false;
        }

        $this->_error = '';
        return true;
    }

    /**
     * @deprecated As of 1.0.3, replaced by publish
     */
    function publish_array($cid = null, $publish = 1, $user_id = 0) {
        $this->publish($cid, $publish, $user_id);
    }

    /**
     * Generic Publish/Unpublish function
     * @param array An array of id numbers
     * @param integer 0 if unpublishing, 1 if publishing
     * @param integer The id of the user performnig the operation
     * @since 1.0.4
     */
    function publish($cid = null, $publish = 1, $user_id = 0) {
        mosArrayToInts($cid, array());

        $user_id = (int) $user_id;
        $publish = (int) $publish;
        if (count($cid) < 1) {
            $this->_error = "No items selected.";
            return false;
        }

        $cids = $this->_tbl_key . '=' . implode(' OR ' . $this->_tbl_key . '=', $cid);

        $query = "UPDATE $this->_tbl SET published = " . (int) $publish . " WHERE ($cids) AND (checked_out = 0 OR checked_out = " . (int) $user_id . ")";

        if (!$this->_db->setQuery($query)->query()) {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }

        $this->_error = '';
        return true;
    }

    /**
     * Checks out an object
     * @param int User id
     * @param int Object id
     */
    function checkout($user_id, $oid = null) {
        global $mosConfig_disable_checked_out;

        // отключение блокировок
        if ($mosConfig_disable_checked_out) return true;

        if (!array_key_exists('checked_out', get_class_vars(strtolower(get_class($this))))) {
            $this->_error = "ВНИМАНИЕ: " . strtolower(get_class($this)) . " не поддерживает проверку.";
            return false;
        }
        $k = $this->_tbl_key;
        if ($oid !== null) {
            $this->$k = $oid;
        }

        $time = _CURRENT_SERVER_TIME;

        if (intval($user_id)) {
            $user_id = intval($user_id);
            // new way of storing editor, by id
            $query = "UPDATE $this->_tbl SET checked_out = $user_id, checked_out_time = " . $this->_db->Quote($time) . " WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);

            $this->checked_out = $user_id;
            $this->checked_out_time = $time;
        } else {
            $user_id = $this->_db->Quote($user_id);
            // old way of storing editor, by name
            $query = "UPDATE $this->_tbl SET checked_out = 1, checked_out_time = " . $this->_db->Quote($time) . ", editor = $user_id WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);

            $this->checked_out = 1;
            $this->checked_out_time = $time;
            $this->checked_out_editor = $user_id;
        }

        return $this->_db->query();
    }

    /**
     * Checks in an object
     * @param int Object id
     */
    function checkin($oid = null) {
        global $mosConfig_disable_checked_out;

        // отключение блокировок
        if ($mosConfig_disable_checked_out) return true;

        if (!array_key_exists('checked_out', get_class_vars(strtolower(get_class($this))))) {
            $this->_error = "WARNING: " . strtolower(get_class($this)) . " does not support checkin.";
            return false;
        }

        $k = $this->_tbl_key;
        $nullDate = $this->_db->getNullDate();

        if ($oid !== null) {
            $this->$k = intval($oid);
        }
        if ($this->$k == null) {
            return false;
        }

        $query = "UPDATE $this->_tbl SET checked_out = 0, checked_out_time = " . $this->_db->Quote($nullDate) . " WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
        $this->_db->setQuery($query);

        $this->checked_out = 0;
        $this->checked_out_time = '';

        return $this->_db->query();
    }

    /**
     * Increments the hit counter for an object
     * @param int Object id
     */
    function hit($oid = null) {
        global $mosConfig_enable_log_items, $mosConfig_content_hits;

        if (!$mosConfig_content_hits) return false;

        $k = $this->_tbl_key;
        if ($oid !== null) {
            $this->$k = intval($oid);
        }

        $query = "UPDATE $this->_tbl SET hits = ( hits + 1 ) WHERE $this->_tbl_key = " . $this->_db->Quote($this->id);
        $this->_db->setQuery($query)->query();

        if (@$mosConfig_enable_log_items) {
            $now = date('Y-m-d');
            $query = "SELECT hits FROM #__core_log_items WHERE time_stamp = " . $this->_db->Quote($now) . " AND item_table = " . $this->_db->Quote($this->_tbl) . " AND item_id = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);
            $hits = intval($this->_db->loadResult());
            if ($hits) {
                $query = "UPDATE #__core_log_items SET hits = ( hits + 1 ) WHERE time_stamp = " . $this->_db->Quote($now) . " AND item_table = " . $this->_db->Quote($this->_tbl) . " AND item_id = " .
                        $this->_db->Quote($this->$k);
                $this->_db->setQuery($query);
                $this->_db->query();
            } else {
                $query = "INSERT INTO #__core_log_items VALUES ( " . $this->_db->Quote($now) . ", " . $this->_db->Quote($this->_tbl) . ", " . $this->_db->Quote($this->$k) . ", 1 )";
                $this->_db->setQuery($query);
                $this->_db->query();
            }
        }
    }

    /**
     * @param string $where This is expected to be a valid (and safe!) SQL expression
     */
    function move($dirn, $where = '') {
        $k = $this->_tbl_key;

        $sql = "SELECT $this->_tbl_key, ordering FROM $this->_tbl";

        if ($dirn < 0) {
            $sql .= "\n WHERE ordering < " . (int) $this->ordering;
            $sql .= ($where ? ' AND ' . $where : '');
            $sql .= "\n ORDER BY ordering DESC";
            $sql .= "\n LIMIT 1";
        } else
        if ($dirn > 0) {
            $sql .= "\n WHERE ordering > " . (int) $this->ordering;
            $sql .= ($where ? "\n AND $where" : '');
            $sql .= "\n ORDER BY ordering";
            $sql .= "\n LIMIT 1";
        } else {
            $sql .= "\nWHERE ordering = " . (int) $this->ordering;
            $sql .= ($where ? "\n AND $where" : '');
            $sql .= "\n ORDER BY ordering";
            $sql .= "\n LIMIT 1";
        }

        $this->_db->setQuery($sql);

        $row = null;
        if ($this->_db->loadObject($row)) {
            $query = "UPDATE $this->_tbl SET ordering = " . (int) $row->ordering . " WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);

            if (!$this->_db->query()) {
                $err = $this->_db->getErrorMsg();
                die($err);
            }

            $query = "UPDATE $this->_tbl SET ordering = " . (int) $this->ordering . " WHERE $this->_tbl_key = " . $this->_db->Quote($row->$k);
            $this->_db->setQuery($query);

            if (!$this->_db->query()) {
                $err = $this->_db->getErrorMsg();
                die($err);
            }

            $this->ordering = $row->ordering;
        } else {
            $query = "UPDATE $this->_tbl SET ordering = " . (int) $this->ordering . " WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $err = $this->_db->getErrorMsg();
                die($err);
            }
        }
    }

    /**
     * Compacts the ordering sequence of the selected records
     * @param string Additional where query to limit ordering to a particular subset of records. This is expected to be a valid (and safe!) SQL expression
     */
    function updateOrder($where = '') {
        $k = $this->_tbl_key;

        if (!array_key_exists('ordering', get_class_vars(strtolower(get_class($this))))) {
            $this->_error = "ВНИМАНИЕ: " . strtolower(get_class($this)) . " не поддерживает сортировку.";
            return false;
        }

        if ($this->_tbl == "#__content_frontpage") {
            $order2 = ", content_id DESC";
        } else {
            $order2 = '';
        }

        $query = "SELECT $this->_tbl_key, ordering" . "\n FROM $this->_tbl" . ($where ? "\n WHERE $where" : '') . "\n ORDER BY ordering$order2 ";
        $this->_db->setQuery($query);
        if (!($orders = $this->_db->loadObjectList())) {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }
        // first pass, compact the ordering numbers
        for ($i = 0, $n = count($orders); $i < $n; $i++) {
            if ($orders[$i]->ordering >= 0) {
                $orders[$i]->ordering = $i + 1;
            }
        }

        $shift = 0;
        $n = count($orders);
        for ($i = 0; $i < $n; $i++) {
            if ($orders[$i]->$k == $this->$k) {
                // place 'this' record in the desired location
                $orders[$i]->ordering = min($this->ordering, $n);
                $shift = 1;
            } else
            if ($orders[$i]->ordering >= $this->ordering && $this->ordering > 0) {
                $orders[$i]->ordering++;
            }
        }

        // compact once more until I can find a better algorithm
        for ($i = 0, $n = count($orders); $i < $n; $i++) {
            if ($orders[$i]->ordering >= 0) {
                $orders[$i]->ordering = $i + 1;
                $query = "UPDATE $this->_tbl" . "\n SET ordering = " . (int) $orders[$i]->ordering . "\n WHERE $k = " . $this->_db->Quote($orders[$i]->$k);
                $this->_db->setQuery($query);
            }
        }

        // if we didn't reorder the current record, make it last
        if ($shift == 0) {
            $order = $n + 1;
            $query = "UPDATE $this->_tbl" . "\n SET ordering = " . (int) $order . "\n WHERE $k = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);
        }
        return true;
    }

    /**
     * Tests if item is checked out
     * @param int A user id
     * @return boolean
     */
    function isCheckedOut($user_id = 0) {
        if ($user_id) {
            return ($this->checked_out && $this->checked_out != $user_id);
        } else {
            return $this->checked_out;
        }
    }

//  число записей в таблице по условию
    public function count($where = '') {
        $sql = "SELECT count(*) FROM $this->_tbl " . $where;
        return $this->_db->setQuery($sql)->loadResult();
    }

// получение списка значений
    public function get_list(array $params = array()) {

        $select = isset($params['select']) ? $params['select'] : '*';
        $where = isset($params['where']) ? ' WHERE ' . $params['where'] : '';
        $order = isset($params['order']) ? ' ORDER BY ' . $params['order'] : '';
        $offset = isset($params['offset']) ? intval($params['offset']) : 0;
        $limit = isset($params['limit']) ? intval($params['limit']) : 0;

        return $this->_db->setQuery("SELECT $select FROM $this->_tbl " . $where . $order, $offset, $limit)->loadObjectList();
    }

// получение списка значений для селектора
    public function get_selector(array $key_val, array $params = array()) {

        $key = isset($key_val['key']) ? $key_val['key'] : 'id';
        $value = isset($key_val['value']) ? $key_val['value'] : 'title';

        $select = $key . ',' . $value;
        $where = isset($params['where']) ? 'WHERE ' . $params['where'] : '';
        $order = isset($params['order']) ? 'ORDER BY ' . $params['order'] : '';
        $offset = isset($params['offset']) ? intval($params['offset']) : 0;
        $limit = isset($params['limit']) ? intval($params['limit']) : 0;
        $tablename = isset($params['table']) ? $params['table'] : $this->_tbl;

        $opts = $this->_db->setQuery("SELECT $select FROM $tablename " . $where, $offset, $limit)->loadAssocList();

        $return = array();
        foreach ($opts as $opt) {
            $return[$opt[$key]] = $opt[$value];
        }

        return $return;
    }

// отношение один-ко-многим, список выбранных значений из многих
    public function get_select_one_to_many($table_values, $table_keys, $key_parent, $key_children, array $params = array()) {

        $select = isset($params['select']) ? $params['select'] : 't_val.*';
        $where = isset($params['where']) ? 'WHERE ' . $params['where'] : "WHERE t_key.$key_parent = $this->id ";
        $order = isset($params['order']) ? 'ORDER BY ' . $params['order'] : '';
        $offset = isset($params['offset']) ? intval($params['offset']) : 0;
        $limit = isset($params['limit']) ? intval($params['limit']) : 0;
        $join = isset($params['join']) ? intval($params['join']) : 'LEFT JOIN';

        $sql = "SELECT $select FROM $table_values AS t_val $join $table_keys AS  t_key ON t_val.id=t_key.$key_children $where ";
        return $this->_db->setQuery($sql, $offset, $limit)->loadAssocList('id');
    }

// сохранение значение одного ко многим
    public function save_one_to_many($name_table_keys, $key_name, $value_name, $key_value, $values) {

        //сначала чистим все предыдущие связи
        $this->_db->setQuery("DELETE FROM $name_table_keys WHERE $key_name=$key_value ")->query();

        // фомируем массив сохраняемых значений
        $vals = array();
        foreach ($values as $value) {
            $vals[] = " ($key_value, $value  ) ";
        }

        $values = implode(',', $vals);

        $sql = "INSERT IGNORE INTO $name_table_keys ( $key_name,$value_name ) VALUES $values";
        return $this->_db->setQuery($sql)->query();
    }

// булево изменение содержимого указанного столбца. Используется для смены статуса элемента
    public function changeState($fieldname) {
        $this->_db->setQuery("UPDATE $this->_tbl SET `$fieldname` = !`$fieldname` WHERE $this->_tbl_key = $this->id ", 0, 1)->query();
    }

// селектор выбора отношений один-ко-многим
    public function get_one_to_many_selectors($name, $table_values, $table_keys, $key_parent, $key_children, array $selected_ids, array $params = array()) {
        mosMainFrame::addLib('form');

        $params['select'] = isset($params['select']) ? $params['select'] : 't_val.id, t_val.title';

        $childrens = $this->get_selector(array(), array('table' => $table_values));

        $rets = array();
        foreach ($childrens as $key => $value) {
            $el_id = $name.$key;
            $checked = (bool) isset($selected_ids[$key]);
            $rets[] = form::checkbox($name . '[]', $key, $checked, 'id="' . $el_id . '" ');
            $rets[] = form::label($el_id, $value);
        }

        return implode("\n\t", $rets);
    }
}