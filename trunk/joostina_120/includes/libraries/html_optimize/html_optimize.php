<?php
/**
 * Оптимизатор HTML кода на PHP.
 * The optimizer of HTML code on PHP.
 *
 * НАЗНАЧЕНИЕ
 *   Оптимизация сгенерированного PHP скриптом html кода перед выводом в браузер, оптимизация "на лету".
 *
 * ВОЗМОЖНОСТИ
 *   * удаляет пробелы вначале и в конце переносов строк
 *   * удаляет пробелы ПОСЛЕ открывающих тагов, если перед тагом есть пробел
 *   * удаляет пробелы ПЕРЕД закрывающими тагами, если после тага есть пробел
 *   * удаляет многострочные или большие html комментарии, комментарии в javascript и стилях.
 *   * корректно обрабатывает таги <pre>, <textarea>, <code>, <nooptimize>
 *   * специальный таг <nooptimize> на выходе вырезается.
 *
 * ОСОБЕННОСТИ
 *   Ценность этого оптимизатора в том, что он аккуратен к html коду с формами ввода,
 *   "с умом" вырезает комментарии вида <!--...--> и // в <script>...</script>.
 *   Верстальщики могут временно/навсегда комментировать большие участки html кода,
 *   писать пояснительные комментарии для себя, не опасаясь за размер выходного файла.
 *
 * Пример использования:
 *   ob_start('html_optimize');
 *
 * С параметрами по умолчанию программа даёт приемлемое сжатие за небольшое время работы,
 * (баланс между степенью сжатия и скоростью работы) для использования оптимизации "на лету".
 *
 * Никогда не экономьте на отступах и пробелах в написании кода и ваш КПД увеличится! :)
 *
 * @param    string   $s
 * @param    bool     $is_js   "выпускает воздух" из javascript, не рекомендуется для оптимизации "на лету"
 * @param    bool     $is_css  "выпускает воздух" из стилей,     не рекомендуется для оптимизации "на лету"
 * @return   string
 * @tags     php, html, js, cleaner, clean, cleanse, clear, cruncher, optimize, optimizer, purge, obfuscate, vacuum, vacuumize
 *
 * @license  http://creativecommons.org/licenses/by-sa/3.0/
 * @author   Nasibullin Rinat, http://orangetie.ru/
 * @charset  ANSI
 * @version  2.2.1
 */
function html_optimize(/*string*/ $s, $is_js = false, $is_css = true)
{
    #в библиотеке PCRE для PHP \s - это любой пробельный символ, а именно класс символов [\x09\x0a\x0c\x0d\x20\xa0] или, по другому, [\t\n\f\r \xa0]
    #если \s используется с модификатором /u, то \s трактуется как [\x09\x0a\x0c\x0d\x20]
    #regular expression for tag attributes
    #correct processes dirty and broken HTML in a singlebyte or multibyte UTF-8 charset!
    static $re_attrs_fast_safe =  '(?![a-zA-Z\d])  #statement, which follows after a tag
                                   #correct attributes
                                   (?>
                                       [^>"\']++
                                     | (?<=[\=\x20\r\n\t]|\xc2\xa0) "[^"]*+"
                                     | (?<=[\=\x20\r\n\t]|\xc2\xa0) \'[^\']*+\'
                                   )*
                                   #incorrect attributes
                                   [^>]*+';

    #заменяем содержимое тагов на врЕменные метки
    $s = preg_replace_callback('/<(pre|code|textarea|nooptimize)(' . $re_attrs_fast_safe . ')(>.*?<\/\\1)>/sxiSX', '_html_optimize_pre', $s);

    $GLOBALS['html_optimize_is_js']  = $is_js;
    $GLOBALS['html_optimize_is_css'] = $is_css;
    $s = preg_replace_callback('/  (<((?i:script|style))' . $re_attrs_fast_safe . '(?<!\/)>)  #1,2
                                   (.*?)                                          #3
                                   (<\/(?i:\\2)>)                                 #4

                                   #условные комментарии IE: <!--[if expression]> HTML <![endif]-->
                                 | (<!--\[ [\x20\r\n\t]*+ if [^a-zA-Z] [^\]]++ \]>) #5

                                   #comments
                                 | <!-- .*? -->

                                 ' . ( $is_js || $is_css ? '
                                   #JS events or style attribute
                                 | (?<=[\x20\r\n\t"\']|\xc2\xa0)
                                   #(?<![a-zA-Z\d])
                                   (on[a-zA-Z]{3,}+|style)       #6 on* or style attribute
                                   (?>[\x20\r\n\t]++|\xc2\xa0)*  #пробельные символы (необязательно)
                                   \=
                                   (?>[\x20\r\n\t]++|\xc2\xa0)*  #пробельные символы (необязательно)
                                   #значение атрибута:
                                   (
                                        "   [^"]*+    "      #в двойных кавычках
                                     |  \'  [^\']*+  \'      #в одиночных кавычках
                                   )  #7 значение атрибута
                                 ' : '') . '
                                /sxSX', '_html_optimize_chunks', $s);
    unset($GLOBALS['html_optimize_is_js']);

    #вырезаем лишние переносы строк после некоторых тагов (+0.005 sec.)
    #атомарную группировку в перечислении названий тагов не используем, т.к. в альтернативах есть "li" и "link"!
    $a = preg_split('/ (
                         (?> <\/?+(?:br|p|div|li|ol|ul|table|t[drh]|meta|link|h[1-6]|form|option|select|title|script|style|map|area|head|body|html)' . $re_attrs_fast_safe . '>
                           | <!--\[if [^\]]++ \]>
                           | <!\[endif\]-->
                         )
                         (?:<\/?+noindex>)?+
                       )
                     /sxiSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $s = implode('', array_map('trim', $a));

    #вырезаем пробелы ПОСЛЕ открывающих тагов, если перед тагом есть пробел (+0.001 sec.)
    $s = preg_replace('/ (?<=[\x20\r\n\t])
                         <[a-z][a-z\d]*+ (?<!<input|<img) ' . $re_attrs_fast_safe . ' >
                         \K  #any previously matched characters not to be included in the final matched sequence
                         [\x20\r\n\t]++
                       /sxiSX', '', $s);
    #вырезаем пробелы ПЕРЕД закрывающими тагами, если после тага есть пробел (+0.001 sec.)
    $a = preg_split('/ (?<=[\x20\r\n\t])
                       (<\/[a-zA-Z][a-zA-Z\d]*+>)  #1
                       (?=[\x20\r\n\t])
                     /sxSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $s = implode('', array_map('rtrim', $a));

    #вырезаем лишние пробелы в начале и в конце переводов строк (+0.002 sec.)
    $s = _html_optimize_strip_spaces($s);

    #восстанавливаем врЕменные метки на содержимое тагов
    $s = _html_optimize_placeholder($s, $is_restore = true);
    return str_replace(array('<nooptimize>', '</nooptimize>'), '', $s);
}

function _html_optimize_strip_spaces(/*string*/ $s)
{
    #вырезаем пробелы в начале и в конце переводов строк
    return preg_replace('/ [\x20\t]*+      #возможные пробелы ПЕРЕД переносом строки
                           [\r\n]          #первый перенос строки
                           [\x20\r\n\t]*+  #возможные пробельные символы ПОСЛЕ переноса строки
                         /sxSX', "\r", $s);
}

function _html_optimize_pre(array &$m)
{
    return '<' . $m[1] . $m[2] . _html_optimize_placeholder($m[3]) . '>';
}

function _html_optimize_placeholder(/*string*/ $s, $is_restore = false)
{
    static $tags = array();
    if ($is_restore)
    {
        #d($tags);
        $s = strtr($s, $tags);
        $tags = array();
        return $s;
    }
    $key = "\x01" . count($tags) . "\x02";
    $tags[$key] = $s;
    return $key;
}

#вырезаем комментарии
function _html_optimize_chunks(array &$m)
{
    #<script> or <style> tag
    if (@$m[1])
    {
        if (! $m[3]) return $m[0];
        $s = (strtolower($m[2]) === 'script') ? _html_optimize_parse_js($m[3], $is_script_tag = true)
                                              : _html_optimize_parse_css($m[3]);
        return $m[1] . _html_optimize_placeholder(_html_optimize_strip_spaces($s)) . $m[4];
    }

    if ($m[6] === 'style')
    {
        if ($GLOBALS['html_optimize_is_css']) $m[7] = _html_optimize_parse_css($m[7]);
        return _html_optimize_placeholder('style=' . _html_optimize_strip_spaces($m[7]));
    }

    #js events: onClick, onMouseOver and etc.
    if (@$m[6])
    {
        if (! $GLOBALS['html_optimize_is_js']) return _html_optimize_placeholder(_html_optimize_strip_spaces($m[6] . '=' . $m[7]));
        $attr  =& $m[6];
        $value = substr($m[7], 1, -1);
        #в значении атрибута могут использоваться юникод-сущности, но нам в первую очередь нужно это:
        #~ htmlspecialchars_decode() + декодируем DEC и HEX сущности
        if (! function_exists('utf8_html_entity_decode')) include_once 'utf8_html_entity_decode.php';
        $value = utf8_html_entity_decode($value, $is_htmlspecialchars = true);
        return _html_optimize_placeholder($attr . '="' . htmlspecialchars(_html_optimize_strip_spaces(_html_optimize_parse_js($value, $is_script_tag = false))) . '"');
    }

    #условные комментарии IE не вырезаем!
    if (@$m[5]) return $m[0];
    #счетчики и баннеры могут использовать в комментариях свои сигнатуры,
    #поэтому не вырезаем комментарии, если длина текста мала, текст в ANSI и нет переносов строк и тагов
    if (preg_match('/^<!--(?:[\x20-\x7e]{4,60}+$|\xc2\xa0|&nbsp;)/sSX', $m[0]) &&  #\xc2\xa0 = &nbsp;
        ! preg_match('/<[a-zA-Z][a-zA-Z\d]*+ [^>]*+ >/sxSX', $m[0])) return $m[0];
    return '';
}

function _html_optimize_parse_css(/*string*/ $s)
{
    #вырезаем многострочные комментарии /* ... */
    if (strpos($s, '/*') !== false) $s = preg_replace('~/\*.*?\*/~sSX', ' ', $s);
    #вырезаем лишние пробелы
    if (strpos($s, ' ') !== false ||
        strpos($s, "\r") !== false ||
        strpos($s, "\n") !== false ||
        strpos($s, "\t") !== false)
    {
        $a = preg_split('/([{}():;,%!*]++)/sSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $s = implode('', array_map('trim', $a));
    }
    return $s;
}

function _html_optimize_parse_js(/*string*/ $s, $is_script_tag = false)
{
    if ($GLOBALS['html_optimize_is_js'])
    {
        $re_chunks = ($is_script_tag ? '|  <!-- (?!\/\/-->)                             #fix IE-6.0 bug?' : '') . '
                                        |  [\x20\r\n\t]*  [;{}()]  [;{}()\x20\r\n\t]*   #expression delimiters
                                        |  [\x20\r\n\t]+  (?![a-zA-Z\d\_\$])            #air BEFORE variable
                                        |  (?<![a-zA-Z\d\_\$]|\x01@\x02)  [\x20\r\n\t]+ #air AFTER variable';
    }
    else $re_chunks = '';
    /*
    http://www.crockford.com/javascript/jsmin.html
    Use parens with confusing sequences of + or -.
    For example, minification changes "a + ++b" into "a+++b" which is interpreted as "a++ + b" which is wrong.
    You can avoid this by using parens: "a + (++b)".
    JSLint checks for all of these problems: http://www.jslint.com/
    */
    $s = preg_replace_callback('/#remove chunks
                                    \/\*  .*?                      \*\/  #multi line comment
                                 |  \/\/  (?>(?!\/\/) [^\r\n])*          #single line comment
                                 #ignore chunks
                                 |  "     (?>[^"\\\\\r\n]+ |\\\\.)*  "   #string
                                 |  \'    (?>[^\'\\\\\r\n]+|\\\\.)*  \'  #string
                                 |  \/    (?>[^\/\\\\\r\n]+|\\\\.)+  \/  #regular expression
                                 |  \+    [\r\n\t]++             (?=\+)  #safe for "a + ++b"
                                 |  -     [\r\n\t]++             (?=\-)  #safe for "a - --b"
                                 #vacuumize chunks
                                 ' . $re_chunks . '
                                /sxSX', '_html_optimize_vacuumize_js', $s);
    return str_replace("\x01@\x02", '', $s);
}

function _html_optimize_vacuumize_js(array &$m)
{
    $s =& $m[0];
    $token_type = substr($s, 0, 2);

    #remove chunks
    if ($token_type == '/*') return '';
    if ($token_type == '//') return (strpos($s, '-->') === false) ? '' : $s . "\r\x01@\x02";

    #ignore chunks
    if ($token_type == '<!') return $s . "\r";
    if (strpos('"\'/+-', $s{0}) !== false) return $s;

    #vacuumize chunks
    $s = str_replace(array(' ', "\r", "\n", "\t"), '', $s);
    return preg_replace('/ ;++ (\}++) $/sxSX', '$1;', $s);
}
?>
