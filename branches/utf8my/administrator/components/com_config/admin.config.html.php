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
* @subpackage Config
*/
class HTML_config {
	function showconfig( &$row, &$lists, $option) {
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_session_type, $mainframe;
		$tabs = new mosTabs(1);
				?>
				<script type="text/javascript">
				<!--
						function saveFilePerms() {
								var f = document.adminForm;
								if (f.filePermsMode0.checked)
										f.config_fileperms.value = '';
								else {
										var perms = 0;
										if (f.filePermsUserRead.checked) perms += 400;
										if (f.filePermsUserWrite.checked) perms += 200;
										if (f.filePermsUserExecute.checked) perms += 100;
										if (f.filePermsGroupRead.checked) perms += 40;
										if (f.filePermsGroupWrite.checked) perms += 20;
										if (f.filePermsGroupExecute.checked) perms += 10;
										if (f.filePermsWorldRead.checked) perms += 4;
										if (f.filePermsWorldWrite.checked) perms += 2;
										if (f.filePermsWorldExecute.checked) perms += 1;
										f.config_fileperms.value = '0'+''+perms;
								}
						}
						function changeFilePermsMode(mode) {
								if(document.getElementById) {
										switch (mode) {
												case 0:
														document.getElementById('filePermsValue').style.display = 'none';
														document.getElementById('filePermsTooltip').style.display = '';
														document.getElementById('filePermsFlags').style.display = 'none';
														break;
												default:
														document.getElementById('filePermsValue').style.display = '';
														document.getElementById('filePermsTooltip').style.display = 'none';
														document.getElementById('filePermsFlags').style.display = '';
										} // switch
								} // if
								saveFilePerms();
						}
						function saveDirPerms()  {
								var f = document.adminForm;
								if (f.dirPermsMode0.checked)
										f.config_dirperms.value = '';
								else {
										var perms = 0;
										if (f.dirPermsUserRead.checked) perms += 400;
										if (f.dirPermsUserWrite.checked) perms += 200;
										if (f.dirPermsUserSearch.checked) perms += 100;
										if (f.dirPermsGroupRead.checked) perms += 40;
										if (f.dirPermsGroupWrite.checked) perms += 20;
										if (f.dirPermsGroupSearch.checked) perms += 10;
										if (f.dirPermsWorldRead.checked) perms += 4;
										if (f.dirPermsWorldWrite.checked) perms += 2;
										if (f.dirPermsWorldSearch.checked) perms += 1;
										f.config_dirperms.value = '0'+''+perms;
								}
						}
						function changeDirPermsMode(mode)   {
								if(document.getElementById) {
										switch (mode) {
												case 0:
														document.getElementById('dirPermsValue').style.display = 'none';
														document.getElementById('dirPermsTooltip').style.display = '';
														document.getElementById('dirPermsFlags').style.display = 'none';
														break;
												default:
														document.getElementById('dirPermsValue').style.display = '';
														document.getElementById('dirPermsTooltip').style.display = 'none';
														document.getElementById('dirPermsFlags').style.display = '';
										} // switch
								} // if
								saveDirPerms();
						}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (form.config_session_type.value != <?php echo $row->config_session_type; ?> ){
				if ( confirm('Вы действительно хотите изменить `Метод аутентификации сессии`? \n\n Это действие удалит все существующие сессии фронтенда \n\n') ) {
					submitform( pressbutton );
				} else {
					return;
				}
			} else {
				submitform( pressbutton );
			}
		}
				//-->
				</script>
				<form action="index2.php" method="post" name="adminForm">
				<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
				<table cellpadding="1" cellspacing="1" border="0" width="100%">
				<tr>
				<td width="250"><table class="adminheading"><tr><th class="config jtd_nowrap">Глобальная конфигурация</th></tr></table></td>
						<td width="270">
					<span class="componentheading">Файл configuration.php:<br />
								<?php echo is_writable( '../configuration.php' ) ? '<b><font color="green">Доступен для записи</font></b>' : '<b><font color="red">Защищен от записи</font></b>' ?>
								</span>
						</td>
						<?php
						if (mosIsChmodable('../configuration.php')) {
								if (is_writable('../configuration.php')) {
										?>
										<td>
											<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
											<label for="disable_write">Защитить от записи после сохранения</label>
										</td>
										<?php
								} else {
										?>
										<td>
											<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
											<label for="enable_write">Игнорировать защиту от записи при сохранении</label>
										</td>
								<?php
								} // if
						} // if
						?>
				</tr>
				</table>
						<?php
				$tabs->startPane("configPane");
				$tabs->startTab("Сайт","site-page");
						?>
						<table class="adminform">
						<tr>
						<td>Название сайта:</td>
								<td><input class="text_area" style="width:500px;" type="text" name="config_sitename" size="50" value="<?php echo $row->config_sitename; ?>"/></td>
						</tr>
						<tr>
						<td width="185">Сайт выключен:</td>
								<td><?php echo $lists['offline']; ?></td>
						</tr>
						<tr>
						<td valign="top">Сообщение при выключенном сайте:</td>
						<td><textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="config_offline_message"><?php echo $row->config_offline_message; ?></textarea><?php
								$tip = 'Сообщение, которое выводится пользователям вместо сайта, когда он находится в выключенном состоянии.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td valign="top">Сообщение при системной ошибке:</td>
						<td><textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="config_error_message"><?php echo $row->config_error_message; ?></textarea><?php
								$tip = 'Сообщение, которое выводится пользователям вместо сайта, когда Joomla! не может подключиться к базе данных MySQL.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Показывать "Подробнее..." неавторизованным:</td>
								<td><?php echo $lists['shownoauth']; ?><?php
								$tip = 'Если ДА, то неавторизованным пользователям будут показываться ссылки на содержимое с уровнем доступа -Для зарегистрированных-. Для возможности полного просмотра пользователь должен будет авторизоваться.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Разрешить регистрацию пользователей:</td>
								<td><?php echo $lists['allowUserRegistration']; ?><?php
								$tip = 'Если ДА, то пользователям будет разрешено регистрироваться на сайте.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Использовать активацию нового аккаунта:</td>
								<td><?php echo $lists['useractivation']; ?>
								<?php
								$tip = 'Если ДА, то пользователю необходимо будет активировать новый аккаунт, после получения им письма со ссылкой для активации.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Требовать уникальный E-mail:</td>
								<td><?php echo $lists['uniquemail']; ?><?php
								$tip = 'Если ДА, то пользователи не смогут создавать несколько аккаунтов с одинаковым адресом e-mail.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
			<tr>
				<td>Параметры пользователя сайта:</td>
				<td>
					<?php echo $lists['frontend_userparams']; ?>
					<?php
					$tip = 'Если `Нет`, то будет отключена возможность установки пользователем параметров сайта (выбор редактора)';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
						<tr>
						<td>WYSIWYG-редактор по умолчанию:</td>
								<td><?php echo $lists['editor']; ?></td>
						</tr>
						<tr>
						<td>Длина списков (кол-во строк):</td>
								<td>
<?php
	echo $lists['list_limit'];
	$tip = 'Устанавливает длину списков по умолчанию в панели управления для всех пользователей';
	echo mosToolTip( $tip );
?>
				</td>
						</tr>
						</table>
						<?php
$tabs->endTab();
$tabs->startTab("Фронт","front-page");?>
		<table class="adminform">
			<tr>
				<td width="300">Язык сайта:</td>
				<td><?php echo $lists['lang']; ?></td>
			</tr>
			<tr>
				<td>Формат даты:</td>
				<td><?php echo $lists['form_date'];
					$tip = 'Выберите формат для отображения даты.';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Полный формат даты и времени:</td>
				<td><?php echo $lists['form_date_full'];
					$tip = 'Выберите полный формат для отображения даты и времени.';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Обрамлять заголовки содержимого тегом H1 при полном просмотре:</td>
				<td><?php echo $lists['config_title_h1_only_view'];
					$tip = 'Обрамлять заголовки тегом h1 только в режиме полного просмотра содержимого ( при нажатии на Подробнее... ).';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Обрамлять все заголовки содержимого тегом H1:</td>
				<td><?php echo $lists['config_title_h1'];
					$tip = 'Помещать заголовки материала в теги h1.';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Отключить генерацию RSS (syndicate):</td>
				<td><?php echo $lists['syndicate_off'];
					$tip = 'Если `Да`, то будет отключена возможность генерации RSS лент и работа с ними';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Использовать шаблон:</td>
				<td><?php echo $lists['one_template'];
					$tip = 'При выборе шаблона он будет использован на всем сайте независимо от привязок к пунктам меню других шаблонов. Для использования нескольких шаблонов выберите \\\'Разные\\\'';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Значок сайта в Закладках браузера:</td>
				<td><input class="text_area" type="text" name="config_favicon" size="20" value="<?php echo $row->config_favicon; ?>"/>
					<?php $tip = 'Значок сайта в Закладках (Избранном) браузера. Если не указано или файл значка не найден, по умолчанию будет использоваться файл favicon.ico.';
						echo mosToolTip( $tip, 'Значок сайта в Закладках' );
					?>
				</td>
			</tr>
			<tr>
				<td>Отключить favicon:</td>
				<td><?php echo $lists['config_disable_favicon'];
					$tip = 'Использовать в качестве значка сайта favicon';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Отключить мамботы группы system:</td>
				<td><?php echo $lists['mmb_system_off'];
					$tip = 'Если `Да`, то использование системных мамботов будет прекращено. ВНИМАНИЕ, если на сайте используются мамботы этой группы, то активация параметра не рекомендуется';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Отключить мамботы группы content:</td>
				<td><?php echo $lists['mmb_content_off'];
					$tip = 'Если `Да`, то использование мамботов обработки контента будет прекращено. ВНИМАНИЕ, если на сайте используются мамботы этой группы, то активация параметра не рекомендуется';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Отключить мамботы группы mainbody:</td>
				<td><?php echo $lists['config_mmb_mainbody_off'];
					$tip = 'Если `Да`, то использование мамботов обработки стека компонентов (mainbody) будет прекращено.';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Авторизация на сайте:</td>
				<td><?php echo $lists['frontend_login'];
						$tip = 'Если `Нет`, то страница авторизации на сайте будет отключена, если с ней не связан пункт меню. Также будет отключена возможность регистрации на сайте';
						echo mosToolTip( $tip );?>
				</td>
			</tr>
			<tr>
				<td>Время существования сессии на фронте:</td>
				<td>
					<input class="text_area" type="text" name="config_lifetime" size="10" value="<?php echo $row->config_lifetime; ?>"/>&nbsp;секунд&nbsp;
						<?php echo mosWarning('Время автоотключения пользователя сайта при неактивности. Большое значение этого параметра снижает безопасность.'); ?>
					</td>
			</tr>
			<tr>
				<td>Отключить сессии на фронте</td>
				<td><?php echo $lists['session_front'];
					$tip = 'Если `Да`, то будет отключено ведение сессий для каждого пользователя на сайте. Если подсчет числа пользователей не нужен и регистрация отключена - можно выключить.';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Отключить контроль доступа к содержимому</td>
				<td><?php echo $lists['config_disable_access_control'];
					$tip = 'Если `Да`, то контроль доступа к содержимому осуществляться не будет, и всем пользователям будет отображено всё содержимое. Рекомендуется совместно с пунктами отключения авторизации и сессий на фронте.';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Считать число прочтений содержимого:</td>
				<td><?php echo $lists['config_content_hits'];
					echo mosToolTip('При выключении параметра статистика прочтений содержимого будет не активна.'); ?></td>
			</tr>
			<tr>
				<td>Отключить проверки публикации по датам:</td>
				<td><?php echo $lists['config_disable_date_state'];
					echo mosToolTip('Если на сайте не критичны временные промежутки публикации содержимого - то данный параметр лучше активизировать.'); ?></td>
			</tr>
			<tr>
				<td>Отключать модули в редактировании:</td>
				<td><?php echo $lists['module_on_edit_off'];
					$tip = 'Если `Да`, то на странице редактирования содержимого с фронта будут отключены все модули';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Рассчитывать время генерации страницы:</td>
				<td><?php echo $lists['time_gen'];
					$tip = 'Если `Да`, то на каждой странице будет отображено время на её генерацию';
					echo mosToolTip( $tip );?>
			</td>
			</tr>
			<tr>
				<td>GZIP-сжатие страниц:</td>
				<td><?php echo $lists['gzip'];
					echo mosToolTip('Поддержка сжатия страниц перед отправкой (если поддерживается). Включение этой функции уменьшает размер загружаемых страниц и приводит к уменьшению трафика. В то же время, это увеличивает нагрузку на сервер.'); ?>
				</td>
			</tr>
			<tr>
				<td>Режим отладки сайта:</td>
				<td><?php echo $lists['debug'];
					$tip = 'Если ДА, то будет показываться диагностическая информация, запросы и ошибки MySQL...';
					echo mosToolTip( $tip );
				?></td>
				</tr>
			<tr>
				<td>Расширенный отладчик:</td>
				<td><?php echo $lists['config_front_debug'];
					echo mosToolTip('Использовать на фронте сайта расширенный отладчик выводящий множество информации о сайте.'); ?>
				</td>
			</tr>
		</table>
<?php $tabs->endTab();
$tabs->startTab("Панель управления","back-page"); ?>
		<table class="adminform">
			<tr>
				<td width="300">Отключить удаление сессий в панели управления:</td>
				<td><?php echo $lists['config_adm_session_del'];
					echo mosToolTip('Не удалять сессии даже после истечения времени существования.');?>
				</td>
			</tr>
			<tr>
				<td>Отключить кнопку "Помощь":</td>
				<td><?php echo $lists['config_disable_button_help'];
					echo mosToolTip('Позволяет запретить к показу кнопку помощи тулбара панели управления.' ); ?>
				</td>
			</tr>
			<tr>
				<td>Отключить вкладку "Изображения":</td>
				<td><?php echo $lists['config_disable_image_tab'];
					echo mosToolTip('Позволяет запретить к показу при редактировании содержимого вкладку Изображения.' ); ?>
				</td>
			</tr>
			<tr>
				<td>Время существования сессии в панели управления:</td>
				<td>
				<input class="text_area" type="text" name="config_session_life_admin" size="10" value="<?php echo $row->config_session_life_admin; ?>"/>&nbsp;секунд&nbsp;
				<?php echo mosWarning('Время, по истечении которого будут отключены пользователи <strong>админцентра</strong> при неактивности. Слишком большое значение уменьшает защищенность сайта!'); ?>
				</td>
			</tr>
			<tr>
				<td>Запоминать страницу Админцентра при окончании сессии:</td>
				<td><?php echo $lists['admin_expired'];
					echo mosToolTip('Если сессия работы в панели управления закончилась, и Вы заходите на сайт в течение 10 минут, то при входе вы будете перенаправлены на страницу, с которой произошло отключение'); ?>
				</td>
			</tr>
		</table>
<?php
	$tabs->endTab();
	$tabs->startTab("Содержимое","content-page");
?>
						<table class="adminform">
						<tr>
						<td colspan="3">* Эти параметры контролируют вывод элементов содержимого*<br/><br/></td>
						</tr>
						<tr>
						<td width="250">Заголовки в виде ссылок:</td>
						<td width="150"><?php echo $lists['link_titles']; ?></td>
								<td><?php
								$tip = 'Если ДА, заголовки объектов содержимого начинают работать как гиперссылки на эти объекты';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td width="250">Ссылка "Подробнее...":</td>
						<td width="150"><?php echo $lists['readmore']; ?></td>
								<td><?php
								$tip = 'Если выбран параметр Показать, то будет показываться ссылка -Подробнее...- для просмотра полного содержимого';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Рейтинг/Голосование:</td>
								<td><?php echo $lists['vote']; ?></td>
								<td><?php
								$tip = 'Если выбран параметр Показать, система --Рейтинг/Голосование-- будет включена';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Имена авторов:</td>
								<td><?php echo $lists['hideAuthor']; ?></td>
								<td><?php
								$tip = 'Выберите, показывать ли в имена авторов. Это глобальная установка, но она может быть изменена в других местах на уровне меню или объекта.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Дата и время создания:</td>
								<td><?php echo $lists['hideCreateDate']; ?></td>
								<td><?php
								$tip = 'Если Показать, то показывается дата и время создания статьи. Это глобальная установка, но может быть изменена в других местах на уровне меню или объекта.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Дата и время изменения:</td>
								<td><?php echo $lists['hideModifyDate']; ?></td>
								<td><?php
								$tip = 'Если установлено Показать, то будет показываться дата изменения статьи. Это глобальная установка, но она может быть изменена в других местах.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Кол-во просмотров:</td>
								<td><?php echo $lists['hits']; ?></td>
								<td><?php
								$tip = 'Если установлено -Показать-, то показывается количество просмотров объекта посетителями сайта. Эта глобальная установка может быть изменена в других местах панели управления.';
										echo mosToolTip( $tip );
								?></td>
						</tr>
						<tr>
						<td>Ссылка Печать:</td>
								<td><?php echo $lists['hidePrint']; ?></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Ссылка E-mail:</td>
								<td><?php echo $lists['hideEmail']; ?></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Значки Печать и E-mail:</td>
								<td><?php echo $lists['icons']; ?></td>
						<td><?php echo mosToolTip('Если выбрано Показать, то ссылки Печать и E-mail будут отображаться в виде значков, иначе - простым текстом-ссылкой.'); ?></td>
						</tr>
						<tr>
						<td>Оглавление для многостраничных объектов:</td>
								<td><?php echo $lists['multipage_toc']; ?></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Кнопка Назад (Вернуться):</td>
								<td><?php echo $lists['back_button']; ?></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Навигация по содержимому:</td>
								<td><?php echo $lists['item_navigation']; ?></td>
						</tr>
						<tr>
							<td>Уникальные идентификаторы новостей:</td>
							<td><?php echo $lists['config_uid_news']; ?></td>
							<td><?php echo mosToolTip('При включении параметра для каждой новости в списке будет задаваться уникальный идентификатор стиля.'); ?></td>
						</tr>
						<tr>
							<td>Автоматическая публикация на главной:</td>
							<td><?php echo $lists['auto_frontpage']; ?></td>
							<td><?php echo mosToolTip('При включении параметра всё создаваемое содержимое будет автоматически помечено для публикации на главной странице.'); ?></td>
						</tr>
						<tr>
							<td>Отключить блокировки содержимого:</td>
							<td><?php echo $lists['config_disable_checked_out']; ?></td>
							<td><?php echo mosToolTip('При включении параметра блокировки объектов содержимого не будут проверяться. Не рекомендуется использовать на сайтах с большим числом редакторов.'); ?></td>
						</tr>
						<tr>
							<td>Режим работы Itemid:</td>
							<td><?php echo $lists['itemid_compat']; ?></td>
						</tr>
				</table>
				<input type="hidden" name="config_multilingual_support" value="<?php echo $row->config_multilingual_support?>" />
<?php
	$tabs->endTab();
	$tabs->startTab("Локаль","Locale-page");
?>
				<table class="adminform">
					<tr>
							<td width="185">Часовой пояс (смещение времени относительно UTC, ч):</td>
							<td>
								<?php echo $lists['offset']; ?>
					<?php
						$tip = "Текущие дата и время, которые будут показываться на сайте: " . mosCurrentDate(_DATE_FORMAT_LC2);
								echo mosToolTip($tip);
								?>
					</td>
					<td>&nbsp;</td>
						</tr>
						<tr>
							   <td width="185">Разница со временем сервера, ч:</td>
							   <td>
								<input class="text_area" type="text" name="config_offset" size="15" value="<?php echo $row->config_offset; ?>" disabled="disabled"/>
							   </td>
					 <td>&nbsp;</td>
						</tr>
						<tr>
							<td width="185">RSS (смещение времени относительно UTC, ч):</td>
							  <td>
								<?php echo $lists['feed_timeoffset']; ?>
					<?php
						$tip = "Текущие дата и время, которые будут показываться в RSS: " . mosCurrentDate(_DATE_FORMAT_LC2);
								echo mosToolTip($tip);
								?>
					</td>
					<td>&nbsp;</td>
						</tr>
						<tr valign="top">
							<td width="185">Локаль страны:</td>
							  <td>
								<input class="text_area" type="text" name="config_locale" size="15" value="<?php echo $row->config_locale; ?>"/>
					<?php
						$tip = "Определяет региональные настройки страны: отображение даты, времени, чисел и т.д.";
								echo mosToolTip($tip);
								?>
					</td>
							  <td>&nbsp;</td>
				</tr>
				<tr valign="top">
							<td width="185">&nbsp;</td>
							  <td>При использовании в Windows необходимо ввести <span style="color: red"><strong>RU</strong></span>.
	  <br />В Unix-системах, если не работает значение по умолчанию, можно попробовать изменить регистр символов локали на <strong>RU_RU.UTF8, ru_RU.UTF8, ru_ru.utf8</strong>, или узнать значение русской локали у провайдера.<br />
Также можно попробовать ввести одну из следующих локалей: <strong>rus, russian</strong>.
	  				</td>
					<td>&nbsp;</td>
						</tr>
						</table>
<?php
	$tabs->endTab();
	$tabs->startTab("База данных","db-page");
?>
						<table class="adminform">
						<tr>
						<td width="185">Адрес хоста MySQL:</td>
								<td><input class="text_area" type="text" name="config_host" size="25" value="<?php echo $row->config_host; ?>"/></td>
						</tr>
						<tr>
						<td>Имя пользователя БД (MySQL):</td>
								<td><input class="text_area" type="text" name="config_user" size="25" value="<?php echo $row->config_user; ?>"/></td>
						</tr>
						<tr>
						<td>База данных MySQL:</td>
								<td><input class="text_area" type="text" name="config_db" size="25" value="<?php echo $row->config_db; ?>"/></td>
						</tr>
						<tr>
						<td>Префикс базы данных MySQL:</td>
								<td>
								<input class="text_area" type="text" name="config_dbprefix" size="10" value="<?php echo $row->config_dbprefix; ?>"/>
						&nbsp;<?php echo mosWarning('!! НЕ ИЗМЕНЯЙТЕ, ЕСЛИ У ВАС УЖЕ ЕСТЬ РАБОЧАЯ БАЗА ДАННЫХ. В ПРОТИВНОМ СЛУЧАЕ, ВЫ МОЖЕТЕ ПОТЕРЯТЬ К НЕЙ ДОСТУП !!'); ?>
								</td>
						</tr>
						<tr>
							<td>Ежедневная оптимизация таблиц базы данных:</td>
							<td>
							<?php echo $lists['optimizetables'];
								$tip = 'Если `Да`, то каждые сутки база данных будет автоматически оптимизирована для лучшего быстродействия';
								echo mosToolTip( $tip );
							?>
							</td>
						</tr>
						<tr>
							<td>Поддержка младших версий MySQL:</td>
							<td>
							<?php echo $lists['config_dbold'];
								$tip = 'Параметр позволяет отключить автоматический перевод работы БД в режим совместимости с кириллицей';
								echo mosToolTip( $tip );
							?>
							</td>
						</tr>
						<tr>
							<td>Отключить SET sql_mode:</td>
							<td>
							<?php echo $lists['config_sql_mode_off'];
								$tip = 'Отключить перевод режима работы базы данных SET sql_mode';
								echo mosToolTip( $tip );
							?>
							</td>
						</tr>
						</table>
						<?php
				$tabs->endTab();
				$tabs->startTab("Сервер","server-page");
						?>
						<table class="adminform">
						<tr>
						<td>URL сайта:</td>
								<td><strong><?php echo $row->config_live_site; ?></strong></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td width="185">Абсолютный путь( корень сайта ):</td>
							<td width="450"><strong><?php echo $row->config_absolute_path; ?></strong></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Корень медиа менеджера:</td>
							<td>
								<input class="text_area" type="text" name="config_media_dir" size="50" value="<?php echo $row->config_media_dir; ?>"/>
								<?php echo mosToolTip('Корневой каталог для работы компонента управления медиа данными. Путь от корня сайта без / по краям.'); ?>
							</td>
						</tr>
						<tr>
						<td>Корень файлового менеджера:</td>
							<td>
								<input class="text_area" type="text" name="config_joomlaxplorer_dir" size="50" value="<?php echo $row->config_joomlaxplorer_dir; ?>"/>
								<?php echo mosToolTip('Корневой каталог для работы компонента управления файлами. Без / в конце. При использовании в Windows (c) путь может начинаться с названия буквы диска.'); ?>
							</td>
						</tr>
						<tr>
						<td>Секретное слово:</td>
								<td><strong><?php echo $row->config_secret; ?></strong></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Сжатие CSS и JS файлов:</td>
								<td><?php echo $lists['config_gz_js_css']; ?></td>
								<td>&nbsp;</td>
						</tr>
			<tr>
				<td>Метод идентификации сессии:</td>
				<td>
				<?php echo $lists['session_type']; ?>
				&nbsp;&nbsp;
				<?php echo mosWarning('Не изменяйте, если не знаете, зачем это надо!<br /><br /> Если сайт будет использоваться пользователями службы AOL или пользователями, использующими для доступа на сайт прокси-серверы, то можете использовать настройки 2 уровня' ); ?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
						<td>Сообщения об ошибках:</td>
								<td><?php echo $lists['error_reporting']; ?></td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Сервер помощи:</td>
								<td><input class="text_area" type="text" name="config_helpurl" size="50" value="<?php echo $row->config_helpurl; ?>"/></td>
				<td>&nbsp;</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
						<td><em>Сервер помощи</em> - Если поле пустое, то файлы помощи будут браться из локальной папки <span style="color: green; white-space: nowrap"><strong>http://адрес_вашего_сайта/help/</strong></span>
	<br />Для включения сервера "On-Line помощи" введите <strong><a href="http://help.joom.ru" title="On-Line сервер помощи">http://help.joom.ru</a></strong> или <strong><a href="http://help.joomla.org" title="On-Line сервер помощи">http://help.joomla.org</a></strong><br /><br />
	</td>
						</tr>
						<tr>
								<?php
								$mode = 0;
								$flags = 0644;
								if ($row->config_fileperms!='') {
										$mode = 1;
										$flags = octdec($row->config_fileperms);
								} // if
								?>
						<td valign="top">Создание файлов:</td>
								<td>
					<fieldset><legend>Разрешения доступа к файлам</legend>
												<table cellpadding="1" cellspacing="1" border="0">
														<tr>
																<td><input type="radio" id="filePermsMode0" name="filePermsMode" value="0" onclick="changeFilePermsMode(0)"<?php if (!$mode) echo ' checked="checked"'; ?>/></td>
								<td><label for="filePermsMode0">Не менять CHMOD для новых файлов (использовать умолчание сервера)</label></td>
														</tr>
														<tr>
																<td><input type="radio" id="filePermsMode1" name="filePermsMode" value="1" onclick="changeFilePermsMode(1)"<?php if ($mode) echo ' checked="checked"'; ?>/></td>
																<td>
																<label for="filePermsMode1">Установить CHMOD для новых файлов</label>
																		<span id="filePermsValue"<?php if (!$mode) echo ' style="display:none"'; ?>>
																как:		<input class="text_area" type="text" readonly="readonly" name="config_fileperms" size="4" value="<?php echo $row->config_fileperms; ?>"/>
																		</span>
																		<span id="filePermsTooltip"<?php if ($mode) echo ' style="display:none"'; ?>>
																&nbsp;<?php echo mosToolTip('Выберите этот пункт для установки разрешений доступа к вновь создаваемым файлам'); ?>
																		</span>
																</td>
														</tr>
														<tr id="filePermsFlags"<?php if (!$mode) echo ' style="display:none"'; ?>>
																<td>&nbsp;</td>
																<td>
																		<table cellpadding="0" cellspacing="1" border="0">
																				<tr>
											<td style="padding:0px">Владелец:</td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsUserRead" name="filePermsUserRead" value="1" onclick="saveFilePerms()"<?php if ($flags & 0400) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="filePermsUserRead">чтение</label></td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsUserWrite" name="filePermsUserWrite" value="1" onclick="saveFilePerms()"<?php if ($flags & 0200) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="filePermsUserWrite">запись</label></td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsUserExecute" name="filePermsUserExecute" value="1" onclick="saveFilePerms()"<?php if ($flags & 0100) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px" colspan="3"><label for="filePermsUserExecute">выполнение</label></td>
																				</tr>
																				<tr>
											<td style="padding:0px">Группа:</td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsGroupRead" name="filePermsGroupRead" value="1" onclick="saveFilePerms()"<?php if ($flags & 040) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="filePermsGroupRead">чтение</label></td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsGroupWrite" name="filePermsGroupWrite" value="1" onclick="saveFilePerms()"<?php if ($flags & 020) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="filePermsGroupWrite">запись</label></td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsGroupExecute" name="filePermsGroupExecute" value="1" onclick="saveFilePerms()"<?php if ($flags & 010) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px" width="70"><label for="filePermsGroupExecute">выполнение</label></td>
																						<td><input type="checkbox" id="applyFilePerms" name="applyFilePerms" value="1"/></td>
																						<td class="jtd_nowrap">
																								<label for="applyFilePerms">
																								Применить к существующим файлам
																										&nbsp;<?php
																										echo mosWarning(
																										'Изменения коснутся <em>всех существующих файлов</em> на сайте.<br/>'.
																										'<b>НЕПРАВИЛЬНОЕ ИСПОЛЬЗОВАНИЕ ЭТОЙ ОПЦИИ МОЖЕТ ПРИВЕСТИ К НЕРАБОТОСПОСОБНОСТИ САЙТА!</b>'
																										);?>
																								</label>
																						</td>
																				</tr>
																				<tr>
											<td style="padding:0px">Все:</td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsWorldRead" name="filePermsWorldRead" value="1" onclick="saveFilePerms()"<?php if ($flags & 04) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="filePermsWorldRead">чтение</label></td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsWorldWrite" name="filePermsWorldWrite" value="1" onclick="saveFilePerms()"<?php if ($flags & 02) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="filePermsWorldWrite">запись</label></td>
																						<td style="padding:0px"><input type="checkbox" id="filePermsWorldExecute" name="filePermsWorldExecute" value="1" onclick="saveFilePerms()"<?php if ($flags & 01) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px" colspan="4"><label for="filePermsWorldExecute">выполнение</label></td>
																				</tr>
																		</table>
																</td>
														</tr>
												</table>
										</fieldset>
								</td>
								<td>&nbsp;</td>
						</tr>
						<tr>
								<?php
								$mode = 0;
								$flags = 0755;
								if ($row->config_dirperms!='') {
										$mode = 1;
										$flags = octdec($row->config_dirperms);
								} // if
								?>
						<td valign="top">Создание каталогов:</td>
								<td>
					<fieldset><legend>Разрешения доступа к каталогам</legend>
												<table cellpadding="1" cellspacing="1" border="0">
														<tr>
																<td><input type="radio" id="dirPermsMode0" name="dirPermsMode" value="0" onclick="changeDirPermsMode(0)"<?php if (!$mode) echo ' checked="checked"'; ?>/></td>
								<td><label for="dirPermsMode0">Не менять CHMOD для новых каталогов (использовать умолчание сервера)</label></td>
														</tr>
														<tr>
																<td><input type="radio" id="dirPermsMode1" name="dirPermsMode" value="1" onclick="changeDirPermsMode(1)"<?php if ($mode) echo ' checked="checked"'; ?>/></td>
																<td>
																<label for="dirPermsMode1">Установить CHMOD для новых каталогов</label>
																		<span id="dirPermsValue"<?php if (!$mode) echo ' style="display:none"'; ?>>
															   как: <input class="text_area" type="text" readonly="readonly" name="config_dirperms" size="4" value="<?php echo $row->config_dirperms; ?>"/>
																		</span>
																		<span id="dirPermsTooltip"<?php if ($mode) echo ' style="display:none"'; ?>>
																&nbsp;<?php echo mosToolTip('Выберите этот пункт для установки разрешений доступа к вновь создаваемым каталогам'); ?>
																		</span>
																</td>
														</tr>
														<tr id="dirPermsFlags"<?php if (!$mode) echo ' style="display:none"'; ?>>
																<td>&nbsp;</td>
																<td>
																		<table cellpadding="1" cellspacing="0" border="0">
																				<tr>
											<td style="padding:0px">Владелец:</td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsUserRead" name="dirPermsUserRead" value="1" onclick="saveDirPerms()"<?php if ($flags & 0400) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="dirPermsUserRead">чтение</label></td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsUserWrite" name="dirPermsUserWrite" value="1" onclick="saveDirPerms()"<?php if ($flags & 0200) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="dirPermsUserWrite">запись</label></td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsUserSearch" name="dirPermsUserSearch" value="1" onclick="saveDirPerms()"<?php if ($flags & 0100) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px" colspan="3"><label for="dirPermsUserSearch">поиск</label></td>
																				</tr>
																				<tr>
											<td style="padding:0px">Группа:</td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsGroupRead" name="dirPermsGroupRead" value="1" onclick="saveDirPerms()"<?php if ($flags & 040) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="dirPermsGroupRead">чтение</label></td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsGroupWrite" name="dirPermsGroupWrite" value="1" onclick="saveDirPerms()"<?php if ($flags & 020) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="dirPermsGroupWrite">запись</label></td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsGroupSearch" name="dirPermsGroupSearch" value="1" onclick="saveDirPerms()"<?php if ($flags & 010) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px" width="70"><label for="dirPermsGroupSearch">поиск</label></td>
																						<td><input type="checkbox" id="applyDirPerms" name="applyDirPerms" value="1"/></td>
																						<td class="jtd_nowrap">
																								<label for="applyDirPerms">
																								Применить к существующим каталогам
																										&nbsp;<?php
																										echo mosWarning(
																										'Включение этих флагов будет применено ко<em> всем существующим каталогам</em> на сайте.<br/>'.
																										'<b>НЕПРАВИЛЬНОЕ ИСПОЛЬЗОВАНИЕ ЭТОЙ ОПЦИИ МОЖЕТ ПРИВЕСТИ К НЕРАБОТОСПОСОБНОСТИ САЙТА!</b>'
																										);?>
																								</label>
																						</td>
																				</tr>
																				<tr>
											<td style="padding:0px">Все:</td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsWorldRead" name="dirPermsWorldRead" value="1" onclick="saveDirPerms()"<?php if ($flags & 04) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="dirPermsWorldRead">чтение</label></td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsWorldWrite" name="dirPermsWorldWrite" value="1" onclick="saveDirPerms()"<?php if ($flags & 02) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px"><label for="dirPermsWorldWrite">запись</label></td>
																						<td style="padding:0px"><input type="checkbox" id="dirPermsWorldSearch" name="dirPermsWorldSearch" value="1" onclick="saveDirPerms()"<?php if ($flags & 01) echo ' checked="checked"'; ?>/></td>
											<td style="padding:0px" colspan="3"><label for="dirPermsWorldSearch">поиск</label></td>
																				</tr>
																		</table>
																</td>
														</tr>
												</table>
										</fieldset>
								</td>
								<td>&nbsp;</td>
						  </tr>
						  	<tr>
				<?php
				$rgmode = 0;
				if( defined( 'RG_EMULATION' ) ) {
					$rgmode = RG_EMULATION;
				}
				?>
				<td valign="top">Register Globals Emulation:</td>
				<td>
					<fieldset><legend>Эмуляция Регистрации глобальных переменных</legend>
						<table cellpadding="1" cellspacing="1" border="0">
							<tr>
								<td><input type="radio" id="rgemulation" name="rgemulation" value="0"<?php if (!$rgmode) echo ' checked="checked"'; ?>/></td>
								<td><label for="rgemulation">Выкл. (РЕКОМЕНДУЕТСЯ) - дополнительная защита</label></td>
							</tr>
							<tr>
								<td><input type="radio" id="rgemulation" name="rgemulation" value="1"<?php if ($rgmode) echo ' checked="checked"'; ?>/></td>
								<td><label for="rgemulation">Вкл. (НЕ РЕКОМЕНДУЕТСЯ) - совместимость со старыми расширениями, при использовании параметра повышается угроза безопасности.</label></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td>&nbsp;</td>
			</tr>

						</table>
						<?php
				$tabs->endTab();
				$tabs->startTab("Метаданные","metadata-page");
						?>
						<table class="adminform">
						<tr>
						<td width="185" valign="top">Описание сайта, которое индексируется поисковиками:</td>
						<td>
								<textarea class="text_area" cols="50" rows="3" style="width:500px; height:50px" name="config_MetaDesc"><?php echo $row->config_MetaDesc; ?></textarea>
										<?php echo mosToolTip(' Вы можете не ограничивать Ваше описание двадцатью словами, в зависимости от Поискового сервера, который Вы планируете использовать. Делайте описание кратким и подходящим для содержания вашего сайта. Вы можете включить некоторые из ваших ключевых слов и ключевых фраз. Так как некоторые поисковые серверы читают больше 20 слов, то Вы можете добавить одно или два предложения. Пожалуйста удостоверьтесь, что самая важная часть вашего описания находится в первых 20 словах.'); ?>
							</td>
						</tr>
						<tr>
						<td valign="top">Ключевые слова сайта:</td>
				<td><textarea class="text_area" cols="50" rows="3" style="width:500px; height:50px" name="config_MetaKeys"><?php echo $row->config_MetaKeys; ?></textarea></td>
						</tr>
						<tr>
						<td valign="top">Показывать мета-тег <b>title</b>:</td>
								<td>
								<?php echo $lists['MetaTitle'];
								echo mosToolTip('Показывает мета-тег <b>title</b> при просмотре объектов содержимого'); ?>
								</td>
								  </tr>
						<tr>
						<td valign="top">Показывать мета-тег <b>author</b>:</td>
								<td>
								<?php echo $lists['MetaAuthor'];
								echo mosToolTip('Показывает мета-тег <b>author</b> при просмотре объектов содержимого'); ?>
								</td>
						</tr>
								<tr>
				<td>Отключить тег Generator:</td>
				<td>
					<?php echo $lists['generator_off'];
					$tip = 'Если `Да`, то из кода каждой HTML страницы будет исключен тег name=\\\'Generator\\\'';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
			<tr>
				<td>Расширенные теги индексации:</td>
				<td>
					<?php echo $lists['index_tag'];
					$tip = 'Если `Да`, то в код каждой страницы будут добавлены специальные теги для лучшей индексации';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
						</table>
						<?php
				$tabs->endTab();
				$tabs->startTab("Почта","mail-page");
						?>
						<table class="adminform">
						<tr>
						<td width="185">Для отправки почты использовать:</td>
								<td><?php echo $lists['mailer']; ?></td>
						</tr>
						<tr>
						<td>Письма от (Mail From):</td>
								<td><input class="text_area" type="text" name="config_mailfrom" size="50" value="<?php echo $row->config_mailfrom; ?>"/></td>
						</tr>
						<tr>
						<td>Отправитель (From Name):</td>
								<td><input class="text_area" type="text" name="config_fromname" size="50" value="<?php echo $row->config_fromname; ?>"/></td>
						</tr>
						<tr>
						<td>Путь к Sendmail:</td>
								<td><input class="text_area" type="text" name="config_sendmail" size="50" value="<?php echo $row->config_sendmail; ?>"/></td>
						</tr>
						<tr>
						<td>Использовать SMTP-авторизацию:</td>
								<td><?php echo $lists['smtpauth']; ?>&nbsp;<?php echo mosToolTip('Выберите ДА, если для отправки почты используется smtp-сервер с необходимостью авторизации'); ?></td>
						</tr>
						<tr>
						<td>Имя пользователя SMTP:</td>
								<td><input class="text_area" type="text" name="config_smtpuser" size="50" value="<?php echo $row->config_smtpuser; ?>"/>&nbsp;<?php echo mosToolTip('Заполняется, если для отправки почты используется smtp-сервер с необходимостью авторизации'); ?></td>
						</tr>
						<tr>
						<td>Пароль для доступа к SMTP:</td>
								<td><input class="text_area" type="text" name="config_smtppass" size="50" value="<?php echo $row->config_smtppass; ?>"/>&nbsp;<?php echo mosToolTip('Заполняется, если для отправки почты используется smtp-сервер с необходимостью авторизации'); ?></td>
						</tr>
						<tr>
						<td>Адрес SMTP-сервера:</td>
								<td><input class="text_area" type="text" name="config_smtphost" size="50" value="<?php echo $row->config_smtphost; ?>"/></td>
						</tr>
						</table>
						<?php
				$tabs->endTab();
				$tabs->startTab("Кэш","cache-page");
						?>
						<table class="adminform" border="0">
						<?php
						if (is_writeable($row->config_cachepath)) {
								?>
								<tr>
								<td width="185">Включить кэширование:</td>
										<td width="500"><?php echo $lists['caching']; ?><?php echo mosToolTip('Включение кэширования уменьшает запросы к MySQL и уменьшению нагрузки на сервер'); ?></td>
										<td>&nbsp;</td>
								</tr>
								<tr>
								<td width="185">Оптимизация кэширования:</td>
										<td width="500"><?php echo $lists['config_cache_opt']; ?><?php echo mosToolTip('Автоматически удаляет из файлов кэша лишние символы тем самым уменьшая размер файлов.'); ?></td>
										<td>&nbsp;</td>
								</tr>
								<tr>
								<td width="185">Кэширование меню панели управления:</td>
										<td width="500"><?php echo $lists['adm_menu_cache']; ?><?php echo mosToolTip('Включение кэширования меню панели управления. Работает независимо от стандартного кэша.'); ?></td>
										<td>&nbsp;</td>
								</tr>
								<?php
						}else{
						   ?><tr>
											<td width="185">Кэширование не возможно:</td>
										<td width="500"><font color="red"><b>Каталог кэша не доступен для записи.</b></font></td>
										<td>&nbsp;</td>
								</tr>
						<?php
						}
						?>

						<tr>
						<td>Папка кэша:</td>
								<td>
								<input class="text_area" type="text" name="config_cachepath" size="50" value="<?php echo $row->config_cachepath; ?>"/>
								<?php
								if (is_writeable($row->config_cachepath)) {
								echo mosToolTip('Текущий каталог кэша <b>Доступен для записи</b>');
								} else {
								echo mosWarning('Текущий каталог кэша <b>НЕ ДОСТУПЕН ДЛЯ ЗАПИСИ</b> - смените CHMOD каталога на 755 перед включением кэша');
								}
								?>
								</td>
								<td>&nbsp;</td>
						</tr>
						<tr>
						<td>Время жизни кэша:</td>
						<td><input class="text_area" type="text" name="config_cachetime" size="5" value="<?php echo $row->config_cachetime; ?>"/> секунд</td>
								<td>&nbsp;</td>
						</tr>
						</table>
						<?php
				$tabs->endTab();
				$tabs->startTab("Статистика","stats-page");
						?>
						<table class="adminform">
						<tr>
						<td width="185">Включить сбор статистики:</td>
								<td width="100"><?php echo $lists['enable_stats']; ?></td>
						<td><?php echo mostooltip('Разрешить/запретить сбор статистики сайта'); ?></td>
						</tr>
						<tr>
						<td>Вести статистику просмотра содержимого по дате:</td>
								<td><?php echo $lists['log_items']; ?></td>
						<td><span class="error"><?php echo mosWarning('ПРЕДУПРЕЖДЕНИЕ: В этом режиме записываются большие объемы данных!'); ?></span></td>
						</tr>
						<tr>
						<td>Статистика поисковых запросов:</td>
								<td><?php echo $lists['log_searches']; ?></td>
								<td>&nbsp;</td>
						</tr>
						</table>
						<?php
				$tabs->endTab();
				$tabs->startTab("SEO","seo-page");
						?>
						<table class="adminform">
						<tr>
						<td>Дружественные для поисковых систем URL-ы (SEF):</td>
							<td><?php
									echo $lists['sef'];
									echo mosWarning('Только для Apache! Перед использованием переименуйте htaccess.txt в .htaccess. Это необходимо для включения модуля apache - mod_rewrite'); ?>
							</td>
						</tr>
						<tr>
						<td>Динамические заголовки страниц (теги title):</td>
								<td><?php
								echo $lists['pagetitles'];
								echo mosToolTip('Динамическое изменение заголовков страниц в зависимости от текущего просматриваемого содержимого'); ?>
								</td>
						</tr>
						<tr>
						<td>Очистка ссылки на com_frontpage:</td>
								<td><?php
								echo $lists['com_frontpage_clear'];
								echo mosToolTip('Придавать ссылке на компонент главной страницы более короткий вид.'); ?>
								</td>
						</tr>
						<tr>
						<td>Не показывать пачвей (pathway) на главной:</td>
								<td><?php
								echo $lists['config_pathway_clean'];
								echo mosToolTip('При включенном режиме строка \\\'Главная\\\' на первой странице сайта будет заменена на символ неразрывного пробела.'); ?>
								</td>
						</tr>
						<tr>
						<td>Порядок расположения элементов title:</td>
								<td><?php
								echo $lists['pagetitles_first'];
								echo mosToolTip('Порядок расположения элементов заголовка страниц (тег title)'); ?>
								</td>
						</tr>
						<tr>
						<td>Разделитель элементов title:</td>
								<td><input class="text_area" type="text" name="config_tseparator" size="5" value="<?php echo $row->config_tseparator; ?>"/>
									<?php echo mosToolTip('Разделитель элементов заголовка страниц (тег title). По умолчанию - дефис.'); ?>
								</td>
						</tr>
						<tr>
				<td>Индексация печатной версии:</td>
				<td>
					<?php echo $lists['index_print'];
					$tip = 'Если `Да`, то печатная версия содержимого будет доступна для индексации';
					echo mosToolTip( $tip );
					?>
				</td>
			</tr>
		</table>
<?php
	$tabs->endTab();
	$tabs->startTab("CAPTCHA","captcha-page");
?>
	<table class="adminform">
		<tr>
			<td width="300">Для авторизации в панели управления:</td>
			<td><?php echo $lists['captcha'];
				echo mosToolTip('Использовать captcha для более безопасной авторизации в панели управления.');?>
			</td>
		</tr>
		<tr>
			<td width="300">Для регистрации:</td>
			<td><?php echo $lists['config_captcha_reg'];
				echo mosToolTip('Использовать captcha для более безопасной регистрации.');?>
			</td>
		</tr>
		<tr>
			<td width="300">Для формы контактов:</td>
			<td><?php echo $lists['config_captcha_cont'];
				echo mosToolTip('Использовать captcha для более безопасной формы контактов.');?>
			</td>
		</tr>
	</table>
<?php
	$tabs->endTab();
	$tabs->endPane();
	// show security setting check
	josSecurityCheck();
?>

				<input type="hidden" name="option" value="<?php echo $option; ?>"/>
				<input type="hidden" name="config_absolute_path" value="<?php echo $row->config_absolute_path; ?>"/>
				<input type="hidden" name="config_live_site" value="<?php echo $row->config_live_site; ?>"/>
				<input type="hidden" name="config_secret" value="<?php echo $row->config_secret; ?>"/>
				<input type="hidden" name="config_auto_activ_login" value="0"/>
				<input type="hidden" name="task" value=""/>
				</form>
				<script  type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
				<?php
		}

}
?>