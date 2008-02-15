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
require(dirname(__FILE__).'/../../die.php');

/**
* @package Joostina
* @subpackage Messages
*/
class HTML_messages {
        function showMessages( &$rows, $pageNav, $search, $option ) {
?>
<form action="index2.php" method="post" name="adminForm">
		
  <table class="adminheading">
        <tr>
          <th class="inbox">
Личные сообщения
</th>
          <td>
Поиск:
</td>
			<td> 
				<input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="inputbox" onChange="document.adminForm.submit();" />
          </td>
        </tr>
  </table>
  
  <table class="adminlist">
        <tr>
			<th width="20">
				#
          </th>
			<th width="5%" class="title"> 
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
          <th width="50%" class="title">
Тема
</th>
          <th width="20%" class="title">
От
</th>
          <th width="15%" class="title">
Дата
</th>
          <th width="10%" class="title">
Статус
</th>
        </tr>
<?php
$k = 0;
for ($i=0, $n=count( $rows ); $i < $n; $i++) {
        $row =& $rows[$i];
?>
        <tr class="<?php echo "row$k"; ?>">
          <td width="20">
<?php echo $i+1+$pageNav->limitstart;?>
</td>
          <td width="5%">
<?php echo mosHTML::idBox( $i, $row->message_id ); ?>
</td>
          <td width="50%">
<a href="#edit" onClick="hideMainMenu();return listItemTask('cb<?php echo $i;?>','view')">
						<?php echo $row->subject; ?></a> 
				</td>
				<td width="20%">
					<?php echo $row->user_from; ?>
				</td>
				<td width="15%">
					<?php echo $row->date_time; ?>
				</td>
				<td width="10%">
					<?php
          if (intval( $row->state ) == "1") {
                  echo 'Прочитано';
          } else {
                  echo 'Не прочитано';
          } 
          ?>
          </td>
        </tr>
        <?php $k = 1 - $k;
		} 
		?>
        </table>
        <?php echo $pageNav->getListFooter(); ?>
		
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />
</form>
		<?php 
	}

function editConfig( &$vars, $option) {
        $tabs = new mosTabs(0);
?>
<form action="index2.php" method="post" name="adminForm">
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'saveconfig') {
                if (confirm ("Вы уверены?")) {
                        submitform( pressbutton );
                }
        } else {
                document.location.href = 'index2.php?option=<?php echo $option;?>';
        }
}
</script>

		<table class="adminheading">
		<tr>
			<th class="msgconfig">
				Настройки личных сообщений
			</th>
		</tr>
		</table>

        <table class="adminform">
          <tr>
			<td width="25%">
Заблокировать входящую почту:
</td>
                <td> 
<?php echo $vars['lock']; ?> 
</td>
          </tr>
          <tr>
                <td>
Посылать мне новые сообщения:
</td>
                <td> 
<?php echo $vars['mail_on_new']; ?> 
</td>
          </tr>
		<tr>
			<td>
				Автоматическая очистка сообщений:
			</td>
			<td> 
				старше <input type="text" name="vars[auto_purge]" size="5" value="<?php echo $vars['auto_purge']; ?>" class="inputbox" /> дней
			</td>
		</tr>
        </table>

		<input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="">
</form>
		<?php 
	}

function viewMessage( &$row, $option ) {
?>
		<form action="index2.php" method="post" name="adminForm">
		
        <table class="adminheading">
                <tr>
                        <th class="inbox">
Просмотр персональных сообщений
</th>
                </tr>
        </table>

        <table class="adminform">
                <tr>
                        <td width="100">
От:
</td>
                        <td width="85%" bgcolor="#ffffff">
<?php echo $row->user_from;?>
</td>
                </tr>
                <tr>
                        <td>
Отправлено:
</td>
                        <td bgcolor="#ffffff">
<?php echo $row->date_time;?>
</td>
                </tr>
                <tr>
                        <td>
Тема:
</td>
                        <td bgcolor="#ffffff">
				<?php echo htmlspecialchars( $row->subject, ENT_QUOTES );?>
</td>
                </tr>
                <tr>
                        <td valign="top">
Сообщение:
</td>
                        <td width="100%" bgcolor="#ffffff">
				<pre><?php echo htmlspecialchars( $row->message, ENT_QUOTES );?></pre>
</td>
                </tr>
        </table>
		
        <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="1" />
        <input type="hidden" name="cid[]" value="<?php echo $row->message_id; ?>" />
        <input type="hidden" name="userid" value="<?php echo $row->user_id_from; ?>" />
		<input type="hidden" name="subject" value="<?php echo ( substr( $row->subject, 0, 4 ) != 'Re: ' ? 'Re: ' : '' ) . htmlspecialchars( $row->subject, ENT_QUOTES ); ?>" />
        <input type="hidden" name="hidemainmenu" value="0" />
        </form>
		<?php 
	}

function newMessage($option, $recipientslist, $subject ) {
        global $my;
?>
        <script language="javascript" type="text/javascript">
        function submitbutton(pressbutton) {
                var form = document.adminForm;
                if (pressbutton == 'cancel') {
                        submitform( pressbutton );
                        return;
                }

                // do field validation
                if (form.subject.value == "") {
                        alert( "Вы должны ввести название темы." );
                } else if (form.message.value == "") {
                        alert( "Вы должны ввести текст сообщения." );
                } else if (getSelectedValue('adminForm','user_id_to') < 1) {
                        alert( "Вы должны выбрать получателя." );
                } else {
                        submitform( pressbutton );
                }
        }
        </script>

        <table class="adminheading">
                <tr>
                        <th class="inbox">
Новое персональное сообщение
</th>
                </tr>
        </table>

        <form action="index2.php" method="post" name="adminForm">
        <table class="adminform">
                <tr>
                        <td width="100">
Кому:
</td>
                        <td width="85%">
<?php echo $recipientslist; ?>
</td>
                </tr>
                <tr>
                        <td>
Тема:
</td>
                        <td>
				<input type="text" name="subject" size="50" maxlength="100" class="inputbox" value="<?php echo htmlspecialchars( $subject, ENT_QUOTES ); ?>"/>
                        </td>
                </tr>
                <tr>
                        <td valign="top">
Сообщение:
</td>
                        <td width="100%">
                                <textarea name="message" style="width:100%" rows="30" class="inputbox"></textarea>
                        </td>
                </tr>
        </table>   

        <input type="hidden" name="user_id_from" value="<?php echo $my->id; ?>">
        <input type="hidden" name="option" value="<?php echo $option; ?>">
        <input type="hidden" name="task" value="">
        </form>
		<?php 
	}
}
?>
