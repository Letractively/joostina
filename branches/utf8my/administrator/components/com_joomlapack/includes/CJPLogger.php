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
require(dirname(__FILE__).'/../../../die.php');

// Log levels
define("_JP_LOG_ERROR",		1);
define("_JP_LOG_WARNING",	2);
define("_JP_LOG_INFO",		3);
define("_JP_LOG_DEBUG",		4);

class CJPLogger
{
	/**
	 * Clears the logfile
	 */
	function ResetLog() {
		$logName = CJPLogger::logName();
		@unlink( $logName );
		touch( $logName );
	}

	/**
	 * Writes a line to the log, if the log level is high enough
	 *
	 * @param integer $level The log level (_JP_LOG_XXXXX constants)
	 * @param string $message The message to write to the log
	 */
	function WriteLog( $level, $message )
	{
		global $JPConfiguration, $mosConfig_absolute_path;

		if( $JPConfiguration->logLevel >= $level )
		{
			$logName = CJPLogger::logName();
			$message = str_replace( $mosConfig_absolute_path, "<root>", $message );
			switch( $level )
			{
				case _JP_LOG_ERROR:
					$string = "ERROR   |";
					break;
				case _JP_LOG_WARNING:
					$string = "WARNING |";
					break;
				case _JP_LOG_INFO:
					$string = "INFO    |";
					break;
				default:
					$string = "DEBUG   |";
					break;
			}
			$string .= strftime( "%y%m%d %R" ) . "|$message\n";
			$fp = fopen( $logName, "at" );
			if (!($fp === FALSE))
			{
				fwrite( $fp, $string );
				fclose( $fp );
			}
		}
	}

	/**
	 * Parses the log file and outputs formatted HTML to the standard output
	 */
	function VisualizeLogDirect()
	{
		$logName = CJPLogger::logName();
		if(!file_exists($logName)) return false; //joostina pach
		$fp = fopen( $logName, "rt" );
		if ($fp === FALSE) return false;

		echo "<p style=\"font-family: Courier New, monospace; text-align: left; font-size: medium;\">\n";
		while( !feof($fp) )
		{
			$line = fgets( $fp );
			if(!$line) return;
			$exploded = explode( "|", $line, 3 );
			unset( $line );
			switch( trim($exploded[0]) )
			{
				case "ERROR":
					$fmtString = "<span style=\"color: red; font-weight: bold;\">[";
					break;
				case "WARNING":
					$fmtString = "<span style=\"color: #D8AD00; font-weight: bold;\">[";
					break;
				case "INFO":
					$fmtString = "<span style=\"color: black;\">[";
					break;
				case "DEBUG":
					$fmtString = "<span style=\"color: #666666; font-size: small;\">[";
					break;
				default:
					$fmtString = "<span style=\"font-size: small;\">[";
					break;
			}
			$fmtString .= $exploded[1] . "] " . htmlspecialchars($exploded[2]) . "</span><br/>\n";
			unset( $exploded );
			echo $fmtString;
			unset( $fmtString );
		}
		echo "</p>\n";
		ob_flush();
	}

	/**
	 * Calculates the absolute path to the log file
	 */
	function logName()
	{
		global $JPConfiguration;
		return $JPConfiguration->TranslateWinPath( $JPConfiguration->OutputDirectory . "/joomlapack.log" );
	}

}
?>