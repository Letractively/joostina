<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

/** registration*/
DEFINE('_ERROR_PASSWORD','Извините, такой пользователь не найден.');
DEFINE('_NEWPASS_MSG','Учетная запись пользователя $checkusername соответствует адресу e-mail.\n'.
	' Пользователь сайта JPATH_SITE сделал запрос на получение нового пароля.\n\n'.
	' Ваш новый пароль: $newpass\n\nЕсли Вы не запрашивали изменение пароля, сообщите об этом администратору.'.
	' Только Вы можете увидеть это сообщение, больше никто. Если это ошибка, просто зайдите '.
	' на сайт, используя новый пароль, и затем, измените его на удобный Вам.');
DEFINE('_NEWPASS_SUB','$config->config_sitename :: Новый пароль для $checkusername');
DEFINE('_NEWPASS_SENT','Новый пароль создан и отправлен пользователю!');
DEFINE('_REGWARN_NAME','Пожалуйста, введите свое настоящее имя (имя, отображаемое на сайте).');
DEFINE('_REGWARN_USERNAME','Пожалуйста, введите свое имя пользователя (логин).');
DEFINE('_REGWARN_PASSWORD','Пожалуйста, правильно введите пароль. Пароль не должен содержать пробелы, его длина должна быть не меньше 6 символов и он должен состоять только из цифр (0-9) и латинских символов (a-z, A-Z)');
DEFINE('_REGWARN_VPASS1','Пожалуйста, проверьте пароль.');
DEFINE('_REGWARN_VPASS2','Пароль и его подтверждение не совпадают. Пожалуйста, попробуйте ещё раз.');
DEFINE('_SEND_SUB','Данные о новом пользователе %s с %s');
DEFINE('_USEND_MSG_ACTIVATE','Здравствуйте %s,

Благодарим за регистрацию на сайте %s. Ваша учетная запись успешно создана и должна быть активирована.
Чтобы активировать учетную запись, нажмите на ссылке или скопируйте ее в адресную строку браузера:
%s

После активации Вы можете зайти на сайт %s, используя свое имя пользователя и пароль:

Имя пользователя - %s
Пароль - %s');
DEFINE('_USEND_MSG',"Здравствуйте %s,

Благодарим Вас за регистрацию на сайте %s.

Сейчас Вы можете войти на сайт %s, используя имя пользователя и пароль, введенные вами при регистрации.");
DEFINE('_USEND_MSG_NOPASS','Здравствуйте $name,\n\nВы успешно зарегистрированы на сайте $mosConfig_live_site.\n'.
	'Вы можете зайти на сайт $mosConfig_live_site, используя данные, которые Вы указали при регистрации.\n\n'.
	'На это письмо не надо отвечать, так как оно создано автоматически и предназначено только для уведомления\n');
DEFINE('_ASEND_MSG','Здравствуйте! Это системное сообщение с сайта %s.

На сайте %s зарегистрировался новый пользователь.

Данные пользователя:
Настоящее имя - %s
Адрес e-mail - %s
Имя пользователя - %s

На это письмо не надо отвечать, так как оно создано автоматически и предназначено только для уведомления');
DEFINE('_REG_COMPLETE_NOPASS','<div class="componentheading">Регистрация завершена!</div><br />&nbsp;&nbsp;'.'Сейчас Вы можете войти на сайт.<br />&nbsp;&nbsp;');
DEFINE('_REG_COMPLETE','<div class="componentheading">Регистрация завершена!</div><br />Сейчас Вы можете войти на сайт.');
DEFINE('_REG_COMPLETE_ACTIVATE','<div class="componentheading">Регистрация завершена!</div><br />Ваша учетная запись создана и должна быть активирована перед тем, как вы ею воспользуетесь. На Ваш e-mail было отправлено письмо со ссылкой, с помощью которой Вы можете активировать свою учетную запись.');
DEFINE('_REG_ACTIVATE_COMPLETE','<div class="componentheading">Учетная запись активирована!</div><br />Ваша учетная запись активирована. Теперь Вы можете зайти на сайт, используя имя пользователя и пароль, которые Вы ввели при регистрации.');
DEFINE('_REG_ACTIVATE_NOT_FOUND','<div class="componentheading">Неверная ссылка активации!</div><br />Данная учетная запись отсутствует на сайте или уже активирована.');
DEFINE('_REG_ACTIVATE_FAILURE','<div class="componentheading">Ошибка активации!</div><br />Активация вашей учетной записи невозможна. Пожалуйста, обратитесь к администратору.');
DEFINE('_USER_ACTIVATION_FAILED','<div class="componentheading">Ошибка активации!</div><br />Активация вашей учетной записи невозможна. Пожалуйста, обратитесь к администрации сайта');
DEFINE('_NEW_PASSWORD_DESC','Пожалуйста, введите свои имя пользователя и адрес e-mail, затем нажмите кнопку "Отправить пароль".<br />Вскоре, на указанный адрес e-mail Вы получите письмо с новым паролем. Используйте этот пароль для входа на сайт.');
DEFINE('_PROMPT_USERNAME','Имя пользователя:');
DEFINE('_PROMPT_EMAIL','Адрес e-mail:');
DEFINE('_BUTTON_SEND_PASSWORD','Отправить пароль');
DEFINE('_REGISTER_TITLE','Регистрация');
DEFINE('_REGISTER_NAME','Настоящее имя:');
DEFINE('_REGISTER_USERNAME','Имя пользователя:');
DEFINE('_REGISTER_EMAIL','E-mail:');
DEFINE('_REGISTER_PASSWORD','Пароль:');
DEFINE('_REGISTER_VPASS','Подтверждение пароля:');
DEFINE('_BUTTON_SEND_REG','Регистрироваться');
DEFINE('_SENDING_PASSWORDWORD','Ваш пароль будет отправлен на указанный выше адрес e-mail.<br />Когда Вы получите новый пароль, Вы сможете зайти на сайт и изменить этот пароль в любое время.');
DEFINE('_VALID_AZ09',"Пожалуйста, проверьте, правильно ли написано %s.  Имя не должно содержать пробелов, только символы 0-9,a-z,A-Z и иметь длину не менее %d символов.");
DEFINE('_VALID_AZ09_USER',"Пожалуйста, правильно введите %s. Должно содержать только символы 0-9,a-z,A-Z и иметь длину не менее %d символов.");
/** user*/
DEFINE('_SAVE_ERR','Пожалуйста, заполните все поля.');
DEFINE('_THANK_SUB_PUB','Спасибо за Ваш материал.');
DEFINE('_UP_SIZE','Вы не можете загружать файлы размером больше чем 15Кб.');
DEFINE('_UP_EXISTS','Изображение с именем $userfile_name уже существует. Пожалуйста, измените название файла и попробуйте ещё раз.');
DEFINE('_UP_COPY_FAIL','Ошибка при копировании');
DEFINE('_UP_TYPE_WARN','Вы можете загружать изображения только в формате gif или jpg.');
DEFINE('_PASSWORD_VERR1','Если Вы желаете изменить пароль, пожалуйста, введите его ещё раз для подтверждения изменения.');
DEFINE('_PASSWORD_VERR2','Если Вы решили изменить пароль, пожалуйста, обратите внимание, что пароль и его подтверждение должны совпадать.');
DEFINE('_USERNAME_INUSE','Выбранное имя пользователя уже используется.');
DEFINE('_UPDATE','Обновить');
DEFINE('_USER_DETAILS_SAVE','Ваши данные сохранены.');
DEFINE('_USER_LOGIN','Вход пользователя');
DEFINE('_USER_PERSONAL_DATA','Личные данные');
DEFINE('_YOUR_NAME','Полное имя');
DEFINE('_VPASS','Подтверждение пароля');
DEFINE('_SUBMIT_SUCCESS','Ваша информация принята!');
DEFINE('_SUBMIT_SUCCESS_DESC','Ваша информация успешно отправлена администратору. После просмотра, Ваш материал будет опубликован на этом сайте');
DEFINE('_WELCOME','Добро пожаловать!');
DEFINE('_WELCOME_DESC','Добро пожаловать в раздел пользователя нашего сайта');
DEFINE('_CONF_CHECKED_IN',"Все 'заблокированные' Вами элементы теперь 'разблокированы'.");
DEFINE('_CHECK_TABLE','Проверка таблицы');
DEFINE('_CHECKED_IN','Проверено ');
DEFINE('_PASSWORD_MATCH','Пароли не совпадают');
DEFINE('_SITE_SETTINGS','Настройки сайта');
DEFINE('_SELECT_EDITOR','- Выберите редактор -');
DEFINE('_USER_NOT_FOUND','Извините, пользователь не найден');
DEFINE('_WAIT_ACTIVATION','Благодарим за регистрацию. Доступ к аккаунту будет предоставлен после проверки модератором.');
DEFINE('_USER_LAST_VISIT','Последний визит');
DEFINE('_USER_EDIT_PROFILE','Редактировать профиль');
DEFINE('_USER_ON_LINE','на сайте');
DEFINE('_USER_OFF_LINE','отсутствует');

DEFINE('_USER_PROFILE_INFO','Данные профиля');
DEFINE('_USER_CONTENTS','Публикации');





/* Более актуальные */

DEFINE('_ALREADY_LOGIN','Вы уже авторизированы!');
DEFINE('_LOGOUT','Нажмите здесь для завершения работы');
DEFINE('_LOGIN_TEXT','Используйте поля "Пользователь" и "Пароль" для доступа к сайту');
DEFINE('_LOGIN_SUCCESS','Вы успешно вошли');
DEFINE('_LOGOUT_SUCCESS','Вы успешно закончили работу с сайтом');
DEFINE('_LOGIN_DESCRIPTION','Вы должны войти на сайт как пользователь, для доступа к закрытым разделам');
DEFINE('_LOGOUT_DESCRIPTION','Вы действительно хотите покинуть профиль?');