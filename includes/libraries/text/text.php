<?php
/**
 * Класс работы с текстом
 * 
 * @package Joostina
 * @copyright (C) 2009 Extention Team. Joostina Team. Все права защищены.
 * @license GNU/GPL, подробнее в help/lisense.php
 * @version $Id: text.php 05.07.2009 12:07:48 megazaisl $;
 * @since Version 1.3 
 */
defined('_VALID_MOS') or die();

class Text{
	
	var $text = null;
	
	function Text(){
		
	}
	
	/**
	 * Вывод численных результатов с учетом склонения слов
	 * 
	 * @access public
	 * @param integer $int 
	 * @param array $expressions Например: array("ответ", "ответа", "ответов")
	 */
 	function _declension($int, $expressions){
		if (count($expressions) < 3) {
			$expressions[2] = $expressions[1];
		};
		settype($int, 'integer');
		$count = $int % 100;
		if ($count >= 5 && $count <= 20) {
			$result = $expressions['2'];
		} else {
			$count = $count % 10;
			if ($count == 1) {
				$result = $expressions['0'];
			} elseif ($count >= 2 && $count <= 4) {
				$result = $expressions['1'];
			} else {
				$result = $expressions['2'];
			}
		}
		return $result;
	}
	
	
	/**
	 * Word Limiter
	 *
	 * Limits a string to X number of words.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */	
	function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
		{
			return $str;
		}
	
		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);
			
		if (strlen($str) == strlen($matches[0]))
		{
			$end_char = '';
		}
		
		return rtrim($matches[0]).$end_char;
	}


	/**
	 * Character Limiter
	 *
	 * Limits the string based on the character count.  Preserves complete words
	 * so the character count may not be exactly as specified.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */	
	function character_limiter($str, $n = 500, $end_char = '&#8230;')
	{
		if (strlen($str) < $n)
		{
			return $str;
		}
		
		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n)
		{
			return $str;
		}

		$out = "";
		foreach (explode(' ', trim($str)) as $val)
		{
			$out .= $val.' ';
			
			if (strlen($out) >= $n)
			{
				$out = trim($out);
				return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
			}		
		}
	}
	
	/**
	 * Word Censoring Function
	 *
	 * Supply a string and an array of disallowed words and any
	 * matched words will be converted to #### or to the replacement
	 * word you've submitted.
	 *
	 * @access	public
	 * @param	string	the text string
	 * @param	string	the array of censoered words
	 * @param	string	the optional replacement value
	 * @return	string
	 */	
	function word_censor($str, $censored, $replacement = '')
	{
		if ( ! is_array($censored))
		{
			return $str;
		}
        
        $str = ' '.$str.' ';

		// \w, \b and a few others do not match on a unicode character
		// set for performance reasons. As a result words like uber
		// will not match on a word boundary. Instead, we'll assume that
		// a bad word will be bookended by any of these characters.
		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach ($censored as $badword)
		{
			if ($replacement != '')
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
			}
			else
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

        return trim($str);
	}


	
}


