<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

global $mosConfig_form_date,$mosConfig_form_date_full;

// Страница сайта не найдена
define('_404','Запрошенная страница не найдена.');
define('_404_RTS','Вернуться на сайт');

define('_SYSERR1','Нет поддержки MySQL');
define('_SYSERR2','Невозможно подключиться к серверу базы данных');
define('_SYSERR3','Невозможно подключиться к базе данных');

// общее
DEFINE('_LANGUAGE','ru');
DEFINE('_NOT_AUTH','Извините, но для просмотра этой страницы у Вас недостаточно прав.');
DEFINE('_DO_LOGIN','Вы должны авторизоваться или пройти регистрацию.');
DEFINE('_VALID_AZ09',"Пожалуйста, проверьте, правильно ли написано %s.  Имя не должно содержать пробелов, только символы 0-9,a-z,A-Z и иметь длину не менее %d символов.");
DEFINE('_VALID_AZ09_USER',"Пожалуйста, правильно введите %s. Должно содержать только символы 0-9,a-z,A-Z и иметь длину не менее %d символов.");
DEFINE('_CMN_YES','Да');
DEFINE('_CMN_NO','Нет');
DEFINE('_CMN_SHOW','Показать');
DEFINE('_CMN_HIDE','Скрыть');

DEFINE('_CMN_NAME','Имя');
DEFINE('_CMN_DESCRIPTION','Описание');
DEFINE('_CMN_SAVE','Сохранить');
DEFINE('_CMN_APPLY','Применить');
DEFINE('_CMN_CANCEL','Отмена');
DEFINE('_CMN_PRINT','Печать');
DEFINE('_CMN_EMAIL','E-mail');
DEFINE('_ICON_SEP','|');
DEFINE('_CMN_PARENT','Родитель');
DEFINE('_CMN_ORDERING','Сортировка');
DEFINE('_CMN_ACCESS','Уровень доступа');
DEFINE('_CMN_SELECT','Выбрать');

DEFINE('_CMN_NEXT','След.');
DEFINE('_CMN_NEXT_ARROW',"&nbsp;&raquo;");
DEFINE('_CMN_PREV','Пред.');
DEFINE('_CMN_PREV_ARROW',"&laquo;&nbsp;");

DEFINE('_CMN_SORT_NONE','Без сортировки');
DEFINE('_CMN_SORT_ASC','По возрастанию');
DEFINE('_CMN_SORT_DESC','По убыванию');

DEFINE('_CMN_NEW','Создать');
DEFINE('_CMN_NONE','Нет');
DEFINE('_CMN_LEFT','Слева');
DEFINE('_CMN_RIGHT','Справа');
DEFINE('_CMN_CENTER','По центру');
DEFINE('_CMN_ARCHIVE','Добавить в архив');
DEFINE('_CMN_UNARCHIVE','Извлечь из архива');
DEFINE('_CMN_TOP','Вверху');
DEFINE('_CMN_BOTTOM','Внизу');

DEFINE('_CMN_PUBLISHED','Опубликовано');
DEFINE('_CMN_UNPUBLISHED','Не опубликовано');

DEFINE('_CMN_EDIT_HTML','Редактировать HTML');
DEFINE('_CMN_EDIT_CSS','Редактировать CSS');

DEFINE('_CMN_DELETE','Удалить');

DEFINE('_CMN_FOLDER','Каталог');
DEFINE('_CMN_SUBFOLDER','Подкаталог');
DEFINE('_CMN_OPTIONAL','Не обязательно');
DEFINE('_CMN_REQUIRED','Обязательно');

DEFINE('_CMN_CONTINUE','Продолжить');

DEFINE('_STATIC_CONTENT','Статическое содержимое');

DEFINE('_CMN_NEW_ITEM_LAST','По умолчанию новые объекты будут добавлены в конец списка. Порядок расположения может быть изменен только после сохранения объекта.');
DEFINE('_CMN_NEW_ITEM_FIRST','По умолчанию новые объекты будут добавлены в начало списка. Порядок расположения может быть изменен только после сохранения объекта.');
DEFINE('_LOGIN_INCOMPLETE','Пожалуйста, заполните поля Имя пользователя и Пароль.');
DEFINE('_LOGIN_BLOCKED','Извините, ваша учетная запись заблокирована. За более подробной информацией обратитесь к администратору сайта.');
DEFINE('_LOGIN_INCORRECT','Неправильное имя пользователя (логин) или пароль. Попробуйте ещё раз.');
DEFINE('_LOGIN_NOADMINS','Извините, вы не можете войти на сайт. Администраторы на сайте не зарегистрированы.');
DEFINE('_CMN_JAVASCRIPT','Внимание! Для выполнения данной операции, в вашем браузере должна быть включена поддержка Java-script.');

DEFINE('_NEW_MESSAGE','Вам пришло новое личное сообщение');
DEFINE('_MESSAGE_FAILED','Пользователь заблокировал свой почтовый ящик. Сообщение не доставлено.');

DEFINE('_CMN_IFRAMES','Эта страница будет отображена некорректно. Ваш браузер не поддерживает вложенные фреймы (IFrame)');

DEFINE('_INSTALL_3PD_WARN','Предупреждение: Установка сторонних расширений может нарушить безопасность вашего сайта. При обновлении Joomla! сторонние расширения не обновляются.<br />Для получения дополнительной информации о мерах защиты своего сайта и сервера, пожалуйста, посетите <a href="http://forum.joomla.org/index.php/board,267.0.html" target="_blank" style="color: blue; text-decoration: underline;">Форум безопасности Joomla!</a>.');
DEFINE('_INSTALL_WARN','По соображениям безопасности, пожалуйста, удалите каталог <strong>installation</strong> с вашего сервера и обновите страницу.');
DEFINE('_TEMPLATE_WARN','<font color=\"red\"><strong>Файл шаблона не найден:</strong></font> <br /> Зайдите в Панель управления сайтом и выберите новый шаблон ');
DEFINE('_NO_PARAMS','Объект не содержит настраиваемых параметров');
DEFINE('_HANDLER','Обработчик для данного типа отсутствует');

/** мамботы*/
DEFINE('_TOC_JUMPTO','Оглавление');

/**  содержимое*/
DEFINE('_READ_MORE','Подробнее...');
DEFINE('_READ_MORE_REGISTER','Только для зарегистрированных пользователей...');
DEFINE('_MORE','Далее...');
DEFINE('_ON_NEW_CONTENT',"Пользователь [ %s ] добавил новый объект [ %s ]. Раздел: [ %s ]. Категория: [ %s ]");
DEFINE('_SEL_CATEGORY','- Выберите категорию -');
DEFINE('_SEL_SECTION','- Выберите раздел -');
DEFINE('_SEL_AUTHOR','- Выберите автора -');
DEFINE('_SEL_POSITION','- Выберите позицию -');
DEFINE('_SEL_TYPE','- Выберите тип -');
DEFINE('_EMPTY_CATEGORY','Данная категория не содержит объектов.');
DEFINE('_EMPTY_BLOG','Нет объектов для отображения!');
DEFINE('_NOT_EXIST','Извините, страница не найдена.<br />Пожалуйста, вернитесь на главную страницу сайта.');
DEFINE('_SUBMIT_BUTTON','Отправить');

/** classes/html/modules.php*/
DEFINE('_BUTTON_VOTE','Голосовать');
DEFINE('_BUTTON_RESULTS','Результаты');
DEFINE('_USERNAME','Пользователь');
DEFINE('_LOST_PASSWORD','Забыли пароль?');
DEFINE('_PASSWORD','Пароль');
DEFINE('_BUTTON_LOGIN','Войти');
DEFINE('_BUTTON_LOGOUT','Выйти');
DEFINE('_NO_ACCOUNT','Ещё не зарегистрированы?');
DEFINE('_CREATE_ACCOUNT','Регистрация');
DEFINE('_VOTE_POOR','Худшая');
DEFINE('_VOTE_BEST','Лучшая');
DEFINE('_USER_RATING','Рейтинг');
DEFINE('_RATE_BUTTON','Оценить');
DEFINE('_REMEMBER_ME','Запомнить');

/** contact.php*/
DEFINE('_ENQUIRY','Контакт');
DEFINE('_ENQUIRY_TEXT','Это сообщение отправлено с сайта %s. Автор сообщения:');
DEFINE('_COPY_TEXT',
	'Это копия сообщения, которое Вы отправили для %s с сайта %s ');
DEFINE('_COPY_SUBJECT','Копия: ');
DEFINE('_THANK_MESSAGE','Спасибо! Сообщение успешно отправлено.');
DEFINE('_CLOAKING','Этот e-mail защищен от спам-ботов. Для его просмотра в вашем браузере должна быть включена поддержка Java-script');
DEFINE('_CONTACT_HEADER_NAME','Имя');
DEFINE('_CONTACT_HEADER_POS','Положение');
DEFINE('_CONTACT_HEADER_EMAIL','E-mail');
DEFINE('_CONTACT_HEADER_PHONE','Телефон');
DEFINE('_CONTACT_HEADER_FAX','Факс');
DEFINE('_CONTACTS_DESC','Список контактов этого сайта.');
DEFINE('_CONTACT_MORE_THAN','Вы не можете вводить более одного адреса e-mail.');

/** classes/html/contact.php*/
DEFINE('_CONTACT_TITLE','Обратная связь');
DEFINE('_EMAIL_DESCRIPTION','Отправить e-mail пользователю:');
DEFINE('_NAME_PROMPT',' Введите Ваше имя:');
DEFINE('_EMAIL_PROMPT',' Введите Ваш e-mail:');
DEFINE('_MESSAGE_PROMPT',' Введите текст сообщения:');
DEFINE('_SEND_BUTTON','Отправить');
DEFINE('_CONTACT_FORM_NC','Пожалуйста, заполните форму полностью и правильно.');
DEFINE('_CONTACT_TELEPHONE','Телефон: ');
DEFINE('_CONTACT_MOBILE','Мобильный: ');
DEFINE('_CONTACT_FAX','Факс: ');
DEFINE('_CONTACT_EMAIL','E-mail: ');
DEFINE('_CONTACT_NAME','Имя: ');
DEFINE('_CONTACT_POSITION','Должность: ');
DEFINE('_CONTACT_ADDRESS','Адрес: ');
DEFINE('_CONTACT_MISC','Доп. информация: ');
DEFINE('_CONTACT_SEL','Выберите получателя:');
DEFINE('_CONTACT_NONE','Детали этой контактной записи отсутствуют.');
DEFINE('_CONTACT_ONE_EMAIL','Нельзя вводить более одного адреса email.');
DEFINE('_EMAIL_A_COPY','Отправить копию сообщения на собственный адрес');
DEFINE('_CONTACT_DOWNLOAD_AS','Скачать информацию в формате');
DEFINE('_VCARD','VCard');

/** pageNavigation*/
DEFINE('_PN_LT','&lt;');
DEFINE('_PN_RT','&gt;');
DEFINE('_PN_PAGE','Страница');
DEFINE('_PN_OF','из');
DEFINE('_PN_START','[Первая]');
DEFINE('_PN_PREVIOUS','Предыдущая');
DEFINE('_PN_NEXT','Следующая');
DEFINE('_PN_END','[Последняя]');
DEFINE('_PN_DISPLAY_NR','Отображать');
DEFINE('_PN_RESULTS','Результаты');

/** письмо другу*/
DEFINE('_EMAIL_TITLE','Отправить e-mail другу');
DEFINE('_EMAIL_FRIEND','Отправить ссылку страницы на e-mail:');
DEFINE('_EMAIL_FRIEND_ADDR','E-Mail друга:');
DEFINE('_EMAIL_YOUR_NAME','Ваше имя:');
DEFINE('_EMAIL_YOUR_MAIL','Ваш e-mail:');
DEFINE('_SUBJECT_PROMPT',' Тема сообщения:');
DEFINE('_BUTTON_SUBMIT_MAIL','Отправить');
DEFINE('_BUTTON_CANCEL','Отмена');
DEFINE('_EMAIL_ERR_NOINFO','Вы должны правильно ввести свой e-mail и e-mail получателя этого письма.');
DEFINE('_EMAIL_MSG',' Здравствуйте! Следующую ссылку на страницу сайта "%s" отправил Вам %s ( %s ).

Вы сможете просмотреть её по этой ссылке:
%s');
DEFINE('_EMAIL_INFO','Письмо отправил');
DEFINE('_EMAIL_SENT','Ссылка на эту страницу отправлена для');
DEFINE('_PROMPT_CLOSE','Закрыть окно');

/** classes/html/content.php*/
DEFINE('_AUTHOR_BY',' Автор');
DEFINE('_WRITTEN_BY',' Автор');
DEFINE('_LAST_UPDATED','Последнее обновление');
DEFINE('_BACK','Вернуться');
DEFINE('_LEGEND','История');
DEFINE('_DATE','Дата');
DEFINE('_ORDER_DROPDOWN','Порядок');
DEFINE('_HEADER_TITLE','Заголовок');
DEFINE('_HEADER_AUTHOR','Автор');
DEFINE('_HEADER_SUBMITTED','Написан');
DEFINE('_HEADER_HITS','Просмотров');
DEFINE('_E_EDIT','Редактировать');
DEFINE('_E_ADD','Добавить');
DEFINE('_E_WARNUSER','Пожалуйста, нажмите кнопку "Отмена" или "Сохранить", чтобы покинуть эту страницу');
DEFINE('_E_WARNTITLE','Содержимое должно иметь заголовок');
DEFINE('_E_WARNTEXT','Содержимое должно иметь вводный текст');
DEFINE('_E_WARNCAT','Пожалуйста, выберите категорию');
DEFINE('_E_CONTENT','Содержимое');
DEFINE('_E_TITLE','Заголовок:');
DEFINE('_E_CATEGORY','Категория');
DEFINE('_E_INTRO','Вводный текст');
DEFINE('_E_MAIN','Основной текст');
DEFINE('_E_MOSIMAGE','Вставить тег {mosimage}');
DEFINE('_E_IMAGES','Изображения');
DEFINE('_E_GALLERY_IMAGES','Галерея изображений');
DEFINE('_E_CONTENT_IMAGES','Изображения к тексту');
DEFINE('_E_EDIT_IMAGE','Параметры изображения');
DEFINE('_E_NO_IMAGE','Без изображения');
DEFINE('_E_INSERT','Вставить');
DEFINE('_E_UP','Выше');
DEFINE('_E_DOWN','Ниже');
DEFINE('_E_REMOVE','Удалить');
DEFINE('_E_SOURCE','Название файла:');
DEFINE('_E_ALIGN','Расположение:');
DEFINE('_E_ALT','Альтернативный текст:');
DEFINE('_E_BORDER','Рамка:');
DEFINE('_E_CAPTION','Заголовок');
DEFINE('_E_CAPTION_POSITION','Положение подписи');
DEFINE('_E_CAPTION_ALIGN','Выравнивание подписи');
DEFINE('_E_CAPTION_WIDTH','Ширина подписи');
DEFINE('_E_APPLY','Применить');
DEFINE('_E_PUBLISHING','Публикация');
DEFINE('_E_STATE','Состояние:');
DEFINE('_E_AUTHOR_ALIAS','Псевдоним автора:');
DEFINE('_E_ACCESS_LEVEL','Уровень доступа:');
DEFINE('_E_ORDERING','Порядок:');
DEFINE('_E_START_PUB','Дата начала публикации:');
DEFINE('_E_FINISH_PUB','Дата окончания публикации:');
DEFINE('_E_SHOW_FP','Показывать на главной странице:');
DEFINE('_E_HIDE_TITLE','Скрыть заголовок:');
DEFINE('_E_METADATA','Мета-тэги');
DEFINE('_E_M_DESC','Описание:');
DEFINE('_E_M_KEY','Ключевые слова:');
DEFINE('_E_SUBJECT','Тема:');
DEFINE('_E_EXPIRES','Дата истечения:');
DEFINE('_E_VERSION','Версия');
DEFINE('_E_ABOUT','Об объекте');
DEFINE('_E_CREATED','Дата создания:');
DEFINE('_E_LAST_MOD','Последнее изменение:');
DEFINE('_E_HITS','Количество просмотров:');
DEFINE('_E_SAVE','Сохранить');
DEFINE('_E_CANCEL','Отмена');
DEFINE('_E_REGISTERED','Только для зарегистрированных пользователей');
DEFINE('_E_ITEM_INFO','Информация');
DEFINE('_E_ITEM_SAVED','Успешно сохранено!');
DEFINE('_ITEM_PREVIOUS','&laquo; ');
DEFINE('_ITEM_NEXT',' &raquo;');
DEFINE('_KEY_NOT_FOUND','Ключ не найден');


/** content.php*/
DEFINE('_SECTION_ARCHIVE_EMPTY','В этом разделе архива сейчас нет объектов. Пожалуйста, зайдите позже');
DEFINE('_CATEGORY_ARCHIVE_EMPTY','В этой категории архива сейчас нет объектов. Пожалуйста, зайдите позже');
DEFINE('_HEADER_SECTION_ARCHIVE','Архив разделов');
DEFINE('_HEADER_CATEGORY_ARCHIVE','Архив категорий');
DEFINE('_ARCHIVE_SEARCH_FAILURE','Не найдено архивных записей для %s %s'); // значения месяца, а затем года
DEFINE('_ARCHIVE_SEARCH_SUCCESS','Найдены архивные записи для %s %s'); // значения месяца, а затем года
DEFINE('_FILTER','Фильтр');
DEFINE('_ORDER_DROPDOWN_DA','Дата (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_DD','Дата (по убыванию)');
DEFINE('_ORDER_DROPDOWN_TA','Название (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_TD','Название (по убыванию)');
DEFINE('_ORDER_DROPDOWN_HA','Просмотры (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_HD','Просмотры (по убыванию)');
DEFINE('_ORDER_DROPDOWN_AUA','Автор (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_AUD','Автор (по убыванию)');
DEFINE('_ORDER_DROPDOWN_O','По порядку');

/** poll.php*/
DEFINE('_ALERT_ENABLED','Cookies должны быть разрешены!');
DEFINE('_ALREADY_VOTE','Вы уже проголосовали в этом опросе!');
DEFINE('_NO_SELECTION',
	'Вы не сделали свой выбор. Пожалуйста, попробуйте ещё раз');
DEFINE('_THANKS','Спасибо за Ваше участие в голосовании!');
DEFINE('_SELECT_POLL','Выберите опрос из списка');

/** classes/html/poll.php*/
DEFINE('_JAN','Январь');
DEFINE('_FEB','Февраль');
DEFINE('_MAR','Март');
DEFINE('_APR','Апрель');
DEFINE('_MAY','Май');
DEFINE('_JUN','Июнь');
DEFINE('_JUL','Июль');
DEFINE('_AUG','Август');
DEFINE('_SEP','Сентябрь');
DEFINE('_OCT','Октябрь');
DEFINE('_NOV','Ноябрь');
DEFINE('_DEC','Декабрь');
DEFINE('_POLL_TITLE','Результаты опроса');
DEFINE('_SURVEY_TITLE','Название опроса:');
DEFINE('_NUM_VOTERS','Количество проголосовавших:');
DEFINE('_FIRST_VOTE','Первый голос:');
DEFINE('_LAST_VOTE','Последний голос:');
DEFINE('_SEL_POLL','Выберите опрос:');
DEFINE('_NO_RESULTS','Нет данных по выбранному опросу.');

/** registration.php*/
DEFINE('_ERROR_PASS','Извините, такой пользователь не найден.');
DEFINE('_NEWPASS_MSG',
	'Учетная запись пользователя $checkusername соответствует адресу e-mail.\n'.
	' Пользователь сайта $mosConfig_live_site сделал запрос на получение нового пароля.\n\n'.
	' Ваш новый пароль: $newpass\n\nЕсли Вы не запрашивали изменение пароля, сообщите об этом администратору.'.
	' Только Вы можете увидеть это сообщение, больше никто. Если это ошибка, просто зайдите '.
	' на сайт, используя новый пароль, и затем, измените его на удобный Вам.');
DEFINE('_NEWPASS_SUB','$_sitename :: Новый пароль для $checkusername');
DEFINE('_NEWPASS_SENT','Новый пароль создан и отправлен пользователю!');
DEFINE('_REGWARN_NAME','Пожалуйста, введите свое настоящее имя (имя, отображаемое на сайте).');
DEFINE('_REGWARN_UNAME','Пожалуйста, введите свое имя пользователя (логин).');
DEFINE('_REGWARN_MAIL','Пожалуйста, правильно введите адрес e-mail.');
DEFINE('_REGWARN_PASS','Пожалуйста, правильно введите пароль. Пароль не должен содержать пробелы, его длина должна быть не меньше 6 символов и он должен состоять только из цифр (0-9) и латинских символов (a-z, A-Z)');
DEFINE('_REGWARN_VPASS1','Пожалуйста, проверьте пароль.');
DEFINE('_REGWARN_VPASS2','Пароль и его подтверждение не совпадают. Пожалуйста, попробуйте ещё раз.');
DEFINE('_REGWARN_INUSE','Это имя пользователя уже используется. Пожалуйста, выберите другое имя.');
DEFINE('_REGWARN_EMAIL_INUSE','Этот e-mail уже используется. Если Вы забыли свой пароль, Нажмите на ссылку "Забыли пароль?" и на указанный e-mail будет выслан новый пароль.');
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
DEFINE('_REG_COMPLETE_NOPASS',
	'<div class="componentheading">Регистрация завершена!</div><br />&nbsp;&nbsp;'.
	'Сейчас Вы можете войти на сайт.<br />&nbsp;&nbsp;');
DEFINE('_REG_COMPLETE',
	'<div class="componentheading">Регистрация завершена!</div><br />Сейчас Вы можете войти на сайт.');
DEFINE('_REG_COMPLETE_ACTIVATE',
	'<div class="componentheading">Регистрация завершена!</div><br />Ваша учетная запись создана и должна быть активирована перед тем, как вы ею воспользуетесь. На Ваш e-mail было отправлено письмо со ссылкой, с помощью которой Вы можете активировать свою учетную запись.');
DEFINE('_REG_ACTIVATE_COMPLETE',
	'<div class="componentheading">Учетная запись активирована!</div><br />Ваша учетная запись активирована. Теперь Вы можете зайти на сайт, используя имя пользователя и пароль, которые Вы ввели при регистрации.');
DEFINE('_REG_ACTIVATE_NOT_FOUND',
	'<div class="componentheading">Неверная ссылка активации!</div><br />Данная учетная запись отсутствует на сайте или уже активирована.');
DEFINE('_REG_ACTIVATE_FAILURE',
	'<div class="componentheading">Ошибка активации!</div><br />Активация вашей учетной записи невозможна. Пожалуйста, обратитесь к администратору.');

/** classes/html/registration.php*/
DEFINE('_PROMPT_PASSWORD','Забыли пароль?');
DEFINE('_NEW_PASS_DESC',
	'Пожалуйста, введите свои имя пользователя и адрес e-mail, затем нажмите кнопку "Отправить пароль".<br />'.
	'Вскоре, на указанный адрес e-mail Вы получите письмо с новым паролем. Используйте этот пароль для входа на сайт.');
DEFINE('_PROMPT_UNAME','Имя пользователя:');
DEFINE('_PROMPT_EMAIL','Адрес e-mail:');
DEFINE('_BUTTON_SEND_PASS','Отправить пароль');
DEFINE('_REGISTER_TITLE','Регистрация');
DEFINE('_REGISTER_NAME','Настоящее имя:');
DEFINE('_REGISTER_UNAME','Имя пользователя:');
DEFINE('_REGISTER_EMAIL','E-mail:');
DEFINE('_REGISTER_PASS','Пароль:');
DEFINE('_REGISTER_VPASS','Подтверждение пароля:');
DEFINE('_REGISTER_REQUIRED',
	'Все поля, отмеченные символом (*), обязательны для заполнения!');
DEFINE('_BUTTON_SEND_REG','Отправить данные');
DEFINE('_SENDING_PASSWORD',
	'Ваш пароль будет отправлен на указанный выше адрес e-mail.<br />Когда Вы получите'.
	' новый пароль, Вы сможете зайти на сайт и изменить этот пароль в любое время.');

/** classes/html/search.php*/
DEFINE('_SEARCH_TITLE','Поиск');
DEFINE('_PROMPT_KEYWORD','Поиск по ключевой фразе');
DEFINE('_SEARCH_MATCHES','найдено %d совпадений');
DEFINE('_CONCLUSION','Всего найдено $totalRows материалов.');
DEFINE('_NOKEYWORD','Ничего не найдено');
DEFINE('_IGNOREKEYWORD','В поиске были пропущены предлоги');
DEFINE('_SEARCH_ANYWORDS','Любое слово');
DEFINE('_SEARCH_ALLWORDS','Все слова');
DEFINE('_SEARCH_PHRASE','Целую фразу');
DEFINE('_SEARCH_NEWEST','По убыванию');
DEFINE('_SEARCH_OLDEST','По возрастанию');
DEFINE('_SEARCH_POPULAR','По популярности');
DEFINE('_SEARCH_ALPHABETICAL','Алфавитный порядок');
DEFINE('_SEARCH_CATEGORY','Раздел / Категория');
DEFINE('_SEARCH_MESSAGE','Текст для поиска должен содержать от 3 до 20 символов');
DEFINE('_SEARCH_ARCHIVED','В архиве');
DEFINE('_SEARCH_CATBLOG','Блог категории');
DEFINE('_SEARCH_CATLIST','Список категории');
DEFINE('_SEARCH_NEWSFEEDS','Ленты новостей');
DEFINE('_SEARCH_SECLIST','Список раздела');
DEFINE('_SEARCH_SECBLOG','Блог раздела');


/** templates/*.php*/
DEFINE('_ISO2','cp1251');
DEFINE('_ISO','charset=windows-1251');
DEFINE('_DATE_FORMAT','Сегодня: d.m.Y г.'); //Используйте формат PHP-функции DATE
/**
* измените строчку ниже, для изменения вывода даты на сайте
*
* например, DEFINE("_DATE_FORMAT_LC"," %d %B %Y г. %H:%M"); //Используйте формат PHP-функции strftime
*/
DEFINE('_DATE_FORMAT_LC',$mosConfig_form_date); //Используйте формат PHP-функции strftime
DEFINE('_DATE_FORMAT_LC2',$mosConfig_form_date_full); // Полный формат времени
DEFINE('_SEARCH_BOX','Поиск...');
DEFINE('_NEWSFLASH_BOX','Краткие новости!');
DEFINE('_MAINMENU_BOX','Главное меню');

/** classes/html/usermenu.php*/
DEFINE('_UMENU_TITLE','Меню пользователя');
DEFINE('_HI','Здравствуйте, ');

/** user.php*/
DEFINE('_SAVE_ERR','Пожалуйста, заполните все поля.');
DEFINE('_THANK_SUB','Спасибо за Ваш материал. Он будет просмотрен администратором перед размещением на сайте.');
DEFINE('_THANK_SUB_PUB','Спасибо за Ваш материал.');
DEFINE('_UP_SIZE','Вы не можете загружать файлы размером больше чем 15Кб.');
DEFINE('_UP_EXISTS','Изображение с именем $userfile_name уже существует. Пожалуйста, измените название файла и попробуйте ещё раз.');
DEFINE('_UP_COPY_FAIL','Ошибка при копировании');
DEFINE('_UP_TYPE_WARN','Вы можете загружать изображения только в формате gif или jpg.');
DEFINE('_MAIL_SUB','Новый материал от пользователя');
DEFINE('_MAIL_MSG','Здравствуйте $adminName,\n\nПользователь $author предлагает новый материал в раздел $type с названием $title'.
	' для сайта $mosConfig_live_site.\n\n\n'.
	'Пожалуйста, зайдите в панель администратора по адресу $mosConfig_live_site/administrator для просмотра и добавления его в $type.\n\n'.
	'На это письмо не надо отвечать, так как оно создано автоматически и предназначено только для уведомления\n');
DEFINE('_PASS_VERR1','Если Вы желаете изменить пароль, пожалуйста, введите его ещё раз для подтверждения изменения.');
DEFINE('_PASS_VERR2','Если Вы решили изменить пароль, пожалуйста, обратите внимание, что пароль и его подтверждение должны совпадать.');
DEFINE('_UNAME_INUSE','Выбранное имя пользователя уже используется.');
DEFINE('_UPDATE','Обновить');
DEFINE('_USER_DETAILS_SAVE','Ваши данные сохранены.');
DEFINE('_USER_LOGIN','Вход пользователя');

/** components/com_user*/
DEFINE('_EDIT_TITLE','Личные данные');
DEFINE('_YOUR_NAME','Полное имя');
DEFINE('_EMAIL','Адрес e-mail');
DEFINE('_UNAME','Имя пользователя (логин)');
DEFINE('_PASS','Пароль');
DEFINE('_VPASS','Подтверждение пароля');
DEFINE('_SUBMIT_SUCCESS','Ваша информация принята!');
DEFINE('_SUBMIT_SUCCESS_DESC','Ваша информация успешно отправлена администратору. После просмотра, Ваш материал будет опубликован на этом сайте');
DEFINE('_WELCOME','Добро пожаловать!');
DEFINE('_WELCOME_DESC','Добро пожаловать в раздел пользователя нашего сайта');
DEFINE('_CONF_CHECKED_IN','Все \'заблокированные\' Вами элементы теперь \'разблокированы\'.');
DEFINE('_CHECK_TABLE','Проверка таблицы');
DEFINE('_CHECKED_IN','Проверено ');
DEFINE('_CHECKED_IN_ITEMS','');
DEFINE('_PASS_MATCH','Пароли не совпадают');

/** components/com_banners*/
DEFINE('_BNR_CLIENT_NAME','Вы должны ввести имя клиента.');
DEFINE('_BNR_CONTACT','Вы должны выбрать контакт для клиента.');
DEFINE('_BNR_VALID_EMAIL','Адрес электронной почты клиента должен быть правильным.');
DEFINE('_BNR_CLIENT','Вы должны выбрать клиента,');
DEFINE('_BNR_NAME','Введите имя баннера.');
DEFINE('_BNR_IMAGE','Выберите изображения баннера.');
DEFINE('_BNR_URL','Вы должны ввести URL/Код баннера.');

/** components/com_login*/
DEFINE('_ALREADY_LOGIN','Вы уже авторизированы!');
DEFINE('_LOGOUT','Нажмите здесь для завершения работы');
DEFINE('_LOGIN_TEXT','Используйте поля "Пользователь" и "Пароль" для доступа к сайту');
DEFINE('_LOGIN_SUCCESS','Вы успешно вошли');
DEFINE('_LOGOUT_SUCCESS','Вы успешно закончили работу с сайтом');
DEFINE('_LOGIN_DESCRIPTION','Вы должны войти на сайт как пользователь, для доступа к закрытым разделам');
DEFINE('_LOGOUT_DESCRIPTION','Вы действительно хотите покинуть профиль?');


/** components/com_weblinks*/
DEFINE('_WEBLINKS_TITLE','Ссылки');
DEFINE('_WEBLINKS_DESC','В данном разделе собраны наиболее интересные и полезные ссылки в сети. <br />Выберите из списка один из разделов, а затем выберите нужную ссылку.');
DEFINE('_HEADER_TITLE_WEBLINKS','Ссылка');
DEFINE('_SECTION','Раздел:');
DEFINE('_SUBMIT_LINK','Добавить ссылку');
DEFINE('_URL','URL:');
DEFINE('_URL_DESC','Описание:');
DEFINE('_NAME','Название:');
DEFINE('_WEBLINK_EXIST','Ссылка с таким именем уже существует. Измените имя и попробуйте ещё раз.');
DEFINE('_WEBLINK_TITLE','Ссылка должна иметь название.');

/** components/com_newfeeds*/
DEFINE('_FEED_NAME','Название источника');
DEFINE('_FEED_ARTICLES','Статей');
DEFINE('_FEED_LINK','Ссылка источника');

/** whos_online.php*/
DEFINE('_WE_HAVE','Сейчас на сайте находятся: <br />');
DEFINE('_AND',' и ');
DEFINE('_GUEST_COUNT','%s гость');
DEFINE('_GUESTS_COUNT','%s гостей');
DEFINE('_MEMBER_COUNT','%s пользователь');
DEFINE('_MEMBERS_COUNT','%s пользователей');
DEFINE('_ONLINE','');
DEFINE('_NONE','Нет посетителей в онлайн');

/** modules/mod_banners*/
DEFINE('_BANNER_ALT','Реклама');

/** modules/mod_random_image*/
DEFINE('_NO_IMAGES','Нет изображений');

/** modules/mod_stats.php*/
DEFINE('_TIME_STAT','Время');
DEFINE('_MEMBERS_STAT','Пользователей');
DEFINE('_HITS_STAT','Посещений');
DEFINE('_NEWS_STAT','Новостей');
DEFINE('_LINKS_STAT','Ссылок');
DEFINE('_VISITORS','Посетителей');

/** /adminstrator/components/com_menus/admin.menus.html.php*/
DEFINE('_MAINMENU_HOME',
	'* Первый по списку опубликованный пункт этого меню [mainmenu] автоматически становится `Главной` страницей сайта*');
DEFINE('_MAINMENU_DEL',
	'* Вы не можете `удалить` это меню, поскольку оно необходимо для нормального функционирования сайта*');
DEFINE('_MENU_GROUP',
	'* Некоторые `Типы меню` появляются более чем в одной группе*');


/** administrators/components/com_users*/
DEFINE('_NEW_USER_MESSAGE_SUBJECT','Новые данные пользователя');
DEFINE('_NEW_USER_MESSAGE','Здравствуйте, %s


Вы были зарегистрированы Администратором на сайте %s.

Это сообщение содержит Ваши имя пользователя и пароль, для входа на сайт %s:

Имя пользователя - %s
Пароль - %s


На это сообщение не нужно отвечать. Оно сгенерировано роботом рассылок и отправлено только для информирования.');

/** administrators/components/com_massmail*/
DEFINE('_MASSMAIL_MESSAGE',"Это сообщение с сайта '%s'

Сообщение:
");

// Joostina!

DEFINE('_REG_CAPTCHA','Введите текст с изображения:*');
DEFINE('_REG_CAPTCHA_VAL','Необходимо ввести код с изображения.');
DEFINE('_REG_CAPTCHA_REF','Нажмите чтобы обновить изображение.');

DEFINE('_PRINT_PAGE_LINK','Адрес страницы на сайте');

$bad_text = array( ' авле ' , ' без ' , ' больше ' , ' был ' , ' была ' , ' были ' , ' было ' , ' быть ' , ' вам ' , ' вас ' , ' вверх ' , ' видно ' , ' вот ' , ' все ' , ' всегда ' , ' всех ' , ' где ' , ' говорила ' , ' говорим ' , ' говорит ' , ' даже ' , ' два ' , ' для ' , ' его ' , ' ему ' , ' если ' , ' есть ' , ' еще ' , ' затем ' , ' здесь ' , ' знала ' , ' знаю ' , ' иду ' , ' или ' , ' каждый ' , ' кажется ' , ' казалось ' , ' как ' , ' какие ' , ' когда ' , ' которое ' , ' которые ' , ' кто ' , ' меня ' , ' мне ' , ' мог ' , ' могла ' , ' могу ' , ' мое ' , ' моей ' , ' может ' , ' можно ' , ' мои ' , ' мой ' , ' мол ' , ' моя ' , ' надо ' , ' нас ' , ' начал ' , ' начала ' , ' него ' , ' нее ' , ' ней ' , ' немного ' , ' немножко ' , ' нему ' , ' несколько ' , ' нет ' , ' никогда ' , ' них ' , ' ничего ' , ' однако ' , ' она ' , ' они ' , ' оно ' , ' опять ' , ' очень ' , ' под ' , ' пока ' , ' после ' , ' потом ' , ' почти ' , ' при ' , ' про ' , ' раз ' , ' своей ' , ' свой ' ,  ' свою ' ,  ' себе ' ,  ' себя ' ,  ' сейчас ' ,  ' сказал ' ,  ' сказала ' ,  ' слегка ' , ' слишком ' ,  ' словно ' ,  ' снова ' ,  ' стал ' ,  ' стала ' ,  ' стали ' ,  ' так ' ,  ' там ' ,  ' твои ' , ' твоя ' ,  ' тебе ' ,  ' тебя ' ,  ' теперь ' ,  ' тогда ' ,  ' того ' ,  ' тоже ' ,  ' только ' ,  ' три ' ,  ' тут ' , ' уже ' ,  ' хотя ' ,  ' чем ' ,  ' через ' ,  ' что ' ,  ' чтобы ' ,  ' чуть ' ,  ' эта ' ,  ' эти ' ,  ' этих ' ,  ' это ' , ' этого ' ,  ' этой ' ,  ' этом ' ,  ' эту ' );


/* administrator components com_admin */
DEFINE('_FILE_UPLOAD','Загрузка файла');
DEFINE('_MAX_SIZE','Максимальный размер');
DEFINE('_ABOUT_JOOSTINA','О Joostina');
DEFINE('_ABOUT_SYSTEM','О системе');
DEFINE('_SYSTEM_OS','Система');
DEFINE('_DB_VERSION','Версия базы данных');
DEFINE('_PHP_VERSION','Версия PHP');
DEFINE('_APACHE_VERSION','Веб-сервер');
DEFINE('_PHP_APACHE_INTERFACE','Интерфейс между веб-сервером и PHP');
DEFINE('_JOOSTINA_VERSION','Версия Joostina!');
DEFINE('_BROWSER','Браузер (User Agent)');
DEFINE('_PHP_SETTINGS','Важные настройки PHP');
DEFINE('_RG_EMULATION','Эмуляция Register Globals');
DEFINE('_REGISTER_GLOBALS','Register Globals - регистрация глобальных переменных');
DEFINE('_MAGIC_QUOTES','Параметр Magic Quotes');
DEFINE('_SAFE_MODE','Безопасный режим - Safe Mode');
DEFINE('_SESSION_HANDLING','Обработка сессий');
DEFINE('_SESS_SAVE_PATH','Каталог хранения сессий - Session save path');
DEFINE('_PHP_TAGS','Спецтеги php');
DEFINE('_BUFFERING','Буферизация');
DEFINE('_OPEN_BASEDIR','Разрешенные/открытые каталоги');
DEFINE('_ERROR_REPORTING','Отображение ошибок');
DEFINE('_XML_SUPPORT','Поддержка XML');
DEFINE('_ZLIB_SUPPORT','Поддержка Zlib');
DEFINE('_DISABLED_FUNCTIONS','Запрещенные функции');
DEFINE('_CONFIGURATION_FILE','Файл конфигурации');
DEFINE('_ACCESS_RIGHTS','Права доступа');
DEFINE('_DIRS_WITH_RIGHTS','Для работы ВСЕХ функций и возможностей Joostina, ВСЕ указанные ниже каталоги должны быть доступны для записи');
DEFINE('_CACHE_DIRECTORY','Каталог кэша');
DEFINE('_SESSION_DIRECTORY','Каталог сессий');
DEFINE('_DATABASE','База данных');
DEFINE('_TABLE_NAME','Название таблицы');
DEFINE('_DB_CHARSET','Кодировка');
DEFINE('_DB_NUM_RECORDS','Записей');
DEFINE('_DB_SIZE','Размер');
DEFINE('_FIND','Найти');
DEFINE('_CLEAR','Очистить');
DEFINE('_GLOSSARY','Глоссарий');
DEFINE('_DEVELOPERS','Разработчики');
DEFINE('_SUPPORT','Поддержка');
DEFINE('_LICENSE','Лицензия');
DEFINE('_CHANGELOG','Журнал изменений');
DEFINE('_CHECK_VERSION','Проверить версию Joomla! RE');
DEFINE('_PREVIEW_SITE','Предпросмотр сайта');
DEFINE('_IN_NEW_WINDOW','Открыть в новом окне');


/* administrator components com_banners */

DEFINE('_BANNERS_MANAGEMENT','Управление баннерами');
DEFINE('_EDIT_BANNER','Редактирование баннера');
DEFINE('_NEW_BANNER','Создание баннера');
DEFINE('_IN_CURRENT_WINDOW','Том же окне');
DEFINE('_IN_PARENT_WINDOW','Текущем окне');
DEFINE('_IN_MAIN_FRAME','Главном фрейме');
DEFINE('_BANNER_CLIENTS','Клиенты баннеров');
DEFINE('_BANNER_CATEGORIES','Категории баннеров');
DEFINE('_NO_BANNERS','Банеры не обнаружены');
DEFINE('_BANNER_COUNTER_RESETTED','Счётчик показа баннеров обнулён');
DEFINE('_CHECK_PUBLISH_DATE','Проверьте правильность ввода даты публикации');
DEFINE('_CHECK_START_PUBLICATION_DATE','Проверьта дату начала публикации');
DEFINE('_CHECK_END_PUBLICATION_DATE','Проверьта дату окончания публикации');
DEFINE('_TASK_UPLOAD','Загрузить');
DEFINE('_BANNERS_PANEL','Панель баннеров');
DEFINE('_BANNERS_DIRECTORY_DOESNOT_EXISTS','Папка banners не существует');
DEFINE('_CHOOSE_BANNER_IMAGE','Выберите изображение для загрузки');
DEFINE('_BAD_FILENAME','Файл должен содержать алфавитно-числовые символы без пробелов.');
DEFINE('_FILE_ALREADY_EXISTS','Файл #FILENAME# уже существует в базе данных.');
DEFINE('_BANNER_UPLOAD_ERROR','Загрузка #FILENAME# неудачна');
DEFINE('_BANNER_UPLOAD_SUCCESS','Загрузка #FILENAME# в #DIRNAME# успешно завешена');
DEFINE('_UPLOAD_BANNER_FILE','Загрузить файл баннера');


/* administrator components com_categories */


DEFINE('_CATEGORY_CHANGES_SAVED','Изменения в категории сохранены');
DEFINE('_USER_GROUP_ALL','Общий');
DEFINE('_USER_GROUP_REGISTERED','Участники');
DEFINE('_USER_GROUP_SPECIAL','Специальный');
DEFINE('_CONTENT_CATEGORIES','Категории содержимого');
DEFINE('_ALL_CONTENT','Всё содержимое');
DEFINE('_ACTIVE','Активных');
DEFINE('_IN_TRASH','В корзине');
DEFINE('_VIEW_CATEGORY_CONTENT','_E_PUBLISHING');
DEFINE('_CHOOSE_MENU_PLEASE','Пожалуйста, выберите меню');
DEFINE('_CHOOSE_MENUTYPE_PLEASE','Пожалуйста, выберите тип меню');
DEFINE('_ENTER_MENUITEM_NAME','Пожалуйста, введите название для этого пункта меню');
DEFINE('_CATEGORY_NAME_IS_BLANK','Категория должна иметь название');
DEFINE('_ENTER_CATEGORY_NAME','Введите заголовок категории');
DEFINE('_EDIT_CATEGORY','Редактирование');
DEFINE('_NEW_CATEGORY','Новая');
DEFINE('_CATEGORY_PROPERTIES','Свойства категории');
DEFINE('_CATEGORY_TITLE','Заголовок категории (Title)');
DEFINE('_CATEGORY_NAME','Название категории (Name)');
DEFINE('_SORT_ORDER','Порядок расположения');
DEFINE('_IMAGE','Изображение');
DEFINE('_IMAGE_POSTITION','Расположение изображения');
DEFINE('_MENUITEM','Пункт меню');
DEFINE('_NEW_MENUITEM_IN_YOUR_MENU','Создание нового пункта в выбранном вами меню.');
DEFINE('_MENU_NAME','Название пункта меню');
DEFINE('_CREATE_MENU_ITEM','Создать пункт меню');
DEFINE('_EXISTED_MENU_ITEMS','Существующие ссылки меню');
DEFINE('_NOT_EXISTS','Отсутствуют');
DEFINE('_MENU_LINK_AVAILABLE_AFTER_SAVE','Связь с меню будет доступна после сохранения');
DEFINE('_IMAGES_DIRS','Каталоги изображений (MOSImage)');
DEFINE('_MOVE_CATEGORIES','Перемещение категорий');
DEFINE('_CHOOSE_CATEGORY_SECTION','Пожалуйста, выберите раздел для перемещаемой категории');
DEFINE('_MOVE_INTO_SECTION','Переместить в раздел');
DEFINE('_CATEGORIES_TO_MOVE','Перемещаемые категории');
DEFINE('_CONTENT_ITEMS_TO_MOVE','Перемещаемые объекты содержимого');
DEFINE('_IN_SELECTED_SECTION_WILL_BE_MOVED_ALL','В выбранный раздел будут перемещены все <br /> перечисленные категории и всё <br /> перечисленное содержимое этих категорий.');
DEFINE('_CATEGORY_COPYING','Копирование категорий');
DEFINE('_CHOOSE_CAT_SECTION_TO_COPY','Пожалуйста, выберите раздел для копируемой категории');
DEFINE('_COPY_TO_SECTION','Копировать в раздел');
DEFINE('_CATS_TO_COPY','Копируемые категории');
DEFINE('_CONTENT_ITEMS_TO_COPY','Копируемое содержимое категории');
DEFINE('_IN_SELECTED_SECTION_WILL_BE_COPIED_ALL','В выбранный раздел будут скопированы все <br /> перечисленные категории и всё <br /> перечисленное содержимое этих категорий.');
DEFINE('_COMPONENT','Компонент');
DEFINE('_BEFORE_CREATION_CAT_CREATE_SECTION','Перед созданием категории Вы должны создать хотя бы один раздел');
DEFINE('_CATEGORY_IS_EDITING_NOW','Категория #CATNAME# в настоящее время редактируется другим администратором');
DEFINE('_TABLE_WEBLINKS_CATEGORY','Таблица - Веб-ссылки категории');
DEFINE('_TABLE_NEWSFEEDS_CATEGORY','Таблица - Ленты новостей категории');
DEFINE('_TABLE_CATEGORY_CONTACTS','Таблица - Контакты категории');
DEFINE('_TABLE_CATEGORY_CONTENT','Таблица - Содержимое категории');
DEFINE('_BLOG_CATEGORY_CONTENT','Блог - Содержимое категории');
DEFINE('_BLOG_CATEGORY_ARCHIVE','Блог - Архивное содержимое категории');
DEFINE('_USE_SECTION_SETTINGS','Использовать настройки раздела');
DEFINE('_CMN_ALL','Все');
DEFINE('_CHOOSE_CATEGORY_TO_REMOVE','Выберите категорию для удаления');
DEFINE('_CANNOT_REMOVE_CATEGORY','Категория: #CIDS# не может быть удалена, т.к. она содержит записи');
DEFINE('_CHOOSE_CATEGORY_FOR_','Выберите категорию для');
DEFINE('_CHOOSE_OBJECT_TO_MOVE','Выберите объект для перемещения');
DEFINE('_CATEGORIES_MOVED_TO','Категории перемещены в ');
DEFINE('_CATEGORY_MOVED_TO','Категории перемещены в ');
DEFINE('_CATEGORIES_COPIED_TO','Категории скопированы в ');
DEFINE('_CATEGORY_COPIED_TO','Категория скопирована в ');
DEFINE('_NEW_ORDER_SAVED','Новый порядок сохранен');
DEFINE('_SAVE_AND_ADD','Сохранить и добавить');
DEFINE('_CLOSE','Закрыть');
DEFINE('_CREATE_CONTENT','Создать содержимое');
DEFINE('_MOVE','Перенести');
DEFINE('_COPY','Копировать');

/* administrator components com_checkin */

DEFINE('_BLOCKED_OBJECTS','Заблокированные объекты');
DEFINE('_OBJECT','Объект');
DEFINE('_WHO_BLOCK','Заблокировал');
DEFINE('_BLOCK_TIME','Время блокировки');
DEFINE('_ACTION','Действие');
DEFINE('_GLOBAL_CHECKIN','Глобальная разблокировка');
DEFINE('_TABLE_IN_DB','Таблица базы данных');
DEFINE('_OBJECT_COUNT','Кол-во объектов');
DEFINE('_UNBLOCKED','Разблокировано');
DEFINE('_CHECHKED_TABLE','Проверена таблица');
DEFINE('_ALL_BLOCKED_IS_UNBLOCKED','Все заблокированные объекты разблокированы');
DEFINE('_MINUTES','минут');
DEFINE('_HOURS','часов');
DEFINE('_DAYS','дней');
DEFINE('_ERROR_WHEN_UNBLOCKING','При разблокировании произошла ошибка');
DEFINE('_UNBLOCKED2','разблокирован');

/* administrator components com_config */

DEFINE('_CONFIGURATION_IS_UPDATED','Конфигурация успешно обновлена');
DEFINE('_CANNOT_OPEN_CONF_FILE','Ошибка! Невозможно открыть для записи файл конфигурации!');
DEFINE('_DO_YOU_REALLY_WANT_DEL_AUTENT_METHOD','Вы действительно хотите изменить `Метод аутентификации сессии`? \n\n Это действие удалит все существующие сессии фронтенда \n\n');
DEFINE('_GLOBAL_CONFIG','Глобальная конфигурация');
DEFINE('_PROTECT_AFTER_SAVE','Защитить от записи после сохранения');
DEFINE('_IGNORE_PROTECTION_WHEN_SAVE','Игнорировать защиту от записи при сохранении');
DEFINE('_CONFIG_SAVING','Сохранение конфигурации');
DEFINE('_NOT_AVAILABLE_CHECK_RIGHTS','');
DEFINE('_SITE_NAME','Название сайта');
DEFINE('_SITE_OFFLINE','Сайт выключен');
DEFINE('_SITE_OFFLINE_MESSAGE','Сообщение при выключенном сайте');
DEFINE('_SITE_OFFLINE_MESSAGE2','Сообщение, которое выводится пользователям вместо сайта, когда он находится в выключенном состоянии.');
DEFINE('_SYSTEM_ERROR_MESSAGE','Сообщение при системной ошибке');
DEFINE('_SYSTEM_ERROR_MESSAGE2','Сообщение, которое выводится пользователям вместо сайта, когда Joostina! не может подключиться к базе данных MySQL.');
DEFINE('_SHOW_READMORE_TO_AUTH','Показывать \"Подробнее...\" неавторизованным');
DEFINE('_SHOW_READMORE_TO_AUTH2','Если ДА, то неавторизованным пользователям будут показываться ссылки на содержимое с уровнем доступа -Для зарегистрированных-. Для возможности полного просмотра пользователь должен будет авторизоваться.');
DEFINE('_ENABLE_USER_REGISTRATION','Разрешить регистрацию пользователей');
DEFINE('_ENABLE_USER_REGISTRATION2','Если ДА, то пользователям будет разрешено регистрироваться на сайте.');
DEFINE('_ACCOUNT_ACTIVATION','Использовать активацию нового аккаунта');
DEFINE('_ACCOUNT_ACTIVATION2','Если ДА, то пользователю необходимо будет активировать новый аккаунт, после получения им письма со ссылкой для активации.');
DEFINE('_UNIQUE_EMAIL','Требовать уникальный E-mail');
DEFINE('_UNIQUE_EMAIL2','Если ДА, то пользователи не смогут создавать несколько аккаунтов с одинаковым адресом e-mail.');
DEFINE('_USER_PARAMS','Параметры пользователя сайта');
DEFINE('_USER_PARAMS2','Если `Нет`, то будет отключена возможность установки пользователем параметров сайта (выбор редактора)');
DEFINE('_DEFAULT_EDITOR','WYSIWYG-редактор по умолчанию');
DEFINE('_LIST_LIMIT','Длина списков (кол-во строк)');
DEFINE('_LIST_LIMIT2','Устанавливает длину списков по умолчанию в панели управления для всех пользователей');
DEFINE('_FRONTPAGE','Фронт');
DEFINE('_SITE','Сайт');
DEFINE('_CUSTOM_PRINT','Страница печати из каталога шаблона');
DEFINE('_CUSTOM_PRINT2','Использование произвольной страницы для печатного вида из каталога текущего шаблона');
DEFINE('_MODULES_MULTI_LANG','Разрешить многоязычность модулей');
DEFINE('_MODULES_MULTI_LANG2','Позволить системе проверять языковые файлы модулей, если у вас таких не имеется - рекомендуется установить НЕТ');
DEFINE('_DATE_FORMAT_TXT','Формат даты');
DEFINE('_DATE_FORMAT2','Выберите формат для отображения даты. Необходимо использовать формат в соответствии с правилами strftime.');
DEFINE('_DATE_FORMAT_FULL','Полный формат даты и времени');
DEFINE('_DATE_FORMAT_FULL2','Выберите полный формат для отображения даты и времени. Необходимо использовать формат в соответствии с правилами strftime.');
DEFINE('_USE_H1_FOR_HEADERS','Обрамлять заголовки содержимого тегом H1 при полном просмотре');
DEFINE('_USE_H1_FOR_HEADERS2','Обрамлять заголовки тегом h1 только в режиме полного просмотра содержимого ( при нажатии на Подробнее... ).');
DEFINE('_USE_H1_HEADERS_ALWAYS','Обрамлять все заголовки содержимого тегом H1');
DEFINE('_USE_H1_HEADERS_ALWAYS2','Помещать заголовки материала в теги h1.');
DEFINE('_DISABLE_RSS','Отключить генерацию RSS (syndicate)');
DEFINE('_DISABLE_RSS2','Если `Да`, то будет отключена возможность генерации RSS лент и работа с ними');
DEFINE('_USE_TEMPLATE','Использовать шаблон');
DEFINE('_USE_TEMPLATE2','При выборе шаблона он будет использован на всем сайте независимо от привязок к пунктам меню других шаблонов. Для использования нескольких шаблонов выберите \\\'Разные\\\'');
DEFINE('_FAVICON_IMAGE','Значок сайта в Закладках браузера');
DEFINE('_FAVICON_IMAGE2','Значок сайта в Закладках (Избранном) браузера. Если не указано или файл значка не найден, по умолчанию будет использоваться файл favicon.ico.');
DEFINE('_FAVICON_IMAGE3','Значок сайта в Закладках');
DEFINE('_DISABLE_FAVICON','Отключить favicon');
DEFINE('_DISABLE_FAVICON2','Использовать в качестве значка сайта favicon');
DEFINE('_DISABLE_SYSTEM_MAMBOTS','Отключить мамботы группы system');
DEFINE('_DISABLE_SYSTEM_MAMBOTS2','Если `Да`, то использование системных мамботов будет прекращено. ВНИМАНИЕ, если на сайте используются мамботы этой группы, то активация параметра не рекомендуется');
DEFINE('_DISABLE_CONTENT_MAMBOTS','Отключить мамботы группы content');
DEFINE('_DISABLE_CONTENT_MAMBOTS2','Если `Да`, то использование мамботов обработки контента будет прекращено. ВНИМАНИЕ, если на сайте используются мамботы этой группы, то активация параметра не рекомендуется');
DEFINE('_DISABLE_MAINBODY_MAMBOTS','Отключить мамботы группы mainbody');
DEFINE('_DISABLE_MAINBODY_MAMBOTS2','Если `Да`, то использование мамботов обработки стека компонентов (mainbody) будет прекращено.');
DEFINE('_SITE_AUTH','Авторизация на сайте');
DEFINE('_SITE_AUTH2','Если `Нет`, то страница авторизации на сайте будет отключена, если с ней не связан пункт меню. Также будет отключена возможность регистрации на сайте');
DEFINE('_FRONT_SESSION_TIME','Время существования сессии на фронте');
DEFINE('_FRONT_SESSION_TIME2','Время автоотключения пользователя сайта при неактивности. Большое значение этого параметра снижает безопасность.');
DEFINE('_DISABLE_FRONT_SESSIONS','Отключить сессии на фронте');
DEFINE('_DISABLE_FRONT_SESSIONS2','Если `Да`, то будет отключено ведение сессий для каждого пользователя на сайте. Если подсчет числа пользователей не нужен и регистрация отключена - можно выключить.');
DEFINE('_DISABLE_ACCESS_CHECK_TO_CONTENT','Отключить контроль доступа к содержимому');
DEFINE('_DISABLE_ACCESS_CHECK_TO_CONTENT2','Если `Да`, то контроль доступа к содержимому осуществляться не будет, и всем пользователям будет отображено всё содержимое. Рекомендуется совместно с пунктами отключения авторизации и сессий на фронте.');
DEFINE('_COUNT_CONTENT_HITS','Считать число прочтений содержимого');
DEFINE('_COUNT_CONTENT_HITS2','При выключении параметра статистика прочтений содержимого будет не активна.');
DEFINE('_DISABLE_CHECK_CONTENT_DATE','Отключить проверки публикации по датам');
DEFINE('_DISABLE_CHECK_CONTENT_DATE2','Если на сайте не критичны временные промежутки публикации содержимого - то данный параметр лучше активизировать.');
DEFINE('_DISABLE_MODULES_WHEN_EDIT','Отключать модули в редактировании');
DEFINE('_DISABLE_MODULES_WHEN_EDIT2','Если `Да`, то на странице редактирования содержимого с фронта будут отключены все модули');
DEFINE('_COUNT_GENERATION_TIME','Рассчитывать время генерации страницы');
DEFINE('_COUNT_GENERATION_TIME2','Если `Да`, то на каждой странице будет отображено время на её генерацию');
DEFINE('_ENABLE_GZIP','GZIP-сжатие страниц');
DEFINE('_ENABLE_GZIP2','Поддержка сжатия страниц перед отправкой (если поддерживается). Включение этой функции уменьшает размер загружаемых страниц и приводит к уменьшению трафика. В то же время, это увеличивает нагрузку на сервер.');
DEFINE('_IS_SITE_DEBUG','Режим отладки сайта');
DEFINE('_IS_SITE_DEBUG2','Если ДА, то будет показываться диагностическая информация, запросы и ошибки MySQL...');
DEFINE('_EXTENDED_DEBUG','Расширенный отладчик');
DEFINE('_EXTENDED_DEBUG2','Использовать на фронте сайта расширенный отладчик выводящий множество информации о сайте.');
DEFINE('_CONTROL_PANEL','Панель управления');
DEFINE('_DISABLE_ADMIN_SESS_DEL','Отключить удаление сессий в панели управления');
DEFINE('_DISABLE_ADMIN_SESS_DEL2','Не удалять сессии даже после истечения времени существования.');
DEFINE('_DISABLE_HELP_BUTTON','Отключить кнопку "Помощь"');
DEFINE('_DISABLE_HELP_BUTTON2','Позволяет запретить к показу кнопку помощи тулбара панели управления.');
DEFINE('_USE_OLD_TOOLBAR','Использовать старое отображение туллбара');
DEFINE('_USE_OLD_TOOLBAR2','При активировании параметра вывод кнопок туллбара будет произведён в режиме таблиц, как было в Joomla.');
DEFINE('_DISABLE_IMAGES_TAB','Отключить вкладку "Изображения"');
DEFINE('_DISABLE_IMAGES_TAB2','Позволяет запретить к показу при редактировании содержимого вкладку Изображения.');
DEFINE('_ADMIN_SESS_TIME','Время существования сессии в панели управления');
DEFINE('_SECONDS','секунд');
DEFINE('_ADMIN_SESS_TIME2','Время, по истечении которого будут отключены пользователи <strong>админцентра</strong> при неактивности. Слишком большое значение уменьшает защищенность сайта!');
DEFINE('_SAVE_LAST_PAGE','Запоминать страницу Админцентра при окончании сессии');
DEFINE('_SAVE_LAST_PAGE2','Если сессия работы в панели управления закончилась, и Вы заходите на сайт в течение 10 минут, то при входе вы будете перенаправлены на страницу, с которой произошло отключение');
DEFINE('_HTML_CSS_EDITOR','Визуальный редактор для html и css');
DEFINE('_HTML_CSS_EDITOR2','Использовать редактор с подсветкой синтаксиса для редактирования html и css файлов шаблона');
DEFINE('_THIS_PARAMS_CONTROL_CONTENT','* Эти параметры контролируют вывод элементов содержимого *');
DEFINE('_LINK_TITLES','Заголовки в виде ссылок');
DEFINE('_LINK_TITLES2','Если ДА, заголовки объектов содержимого начинают работать как гиперссылки на эти объекты');
DEFINE('_READMORE_LINK','Ссылка "Подробнее..."');
DEFINE('_READMORE_LINK2','Если выбран параметр Показать, то будет показываться ссылка -Подробнее...- для просмотра полного содержимого');
DEFINE('_VOTING_ENABLE','Рейтинг/Голосование');
DEFINE('_VOTING_ENABLE2','Если выбран параметр Показать, система --Рейтинг/Голосование-- будет включена');
DEFINE('_AUTHOR_NAMES','Имена авторов');
DEFINE('_AUTHOR_NAMES2','Выберите, показывать ли имена авторов. Это глобальная установка, но она может быть изменена в других местах на уровне меню или объекта.');
DEFINE('_DATE_TIME_CREATION','Дата и время создания');
DEFINE('_DATE_TIME_CREATION2','Если Показать, то показывается дата и время создания статьи. Это глобальная установка, но может быть изменена в других местах на уровне меню или объекта.');
DEFINE('_DATE_TIME_MODIFICATION','Дата и время изменения');
DEFINE('_DATE_TIME_MODIFICATION2','Если установлено Показать, то будет показываться дата изменения статьи. Это глобальная установка, но она может быть изменена в других местах.');
DEFINE('_VIEW_COUNT','Кол-во просмотров');
DEFINE('_VIEW_COUNT2','Если установлено -Показать-, то показывается количество просмотров объекта посетителями сайта. Эта глобальная установка может быть изменена в других местах панели управления.');
DEFINE('_LINK_PRINT','Ссылка Печать');
DEFINE('_LINK_EMAIL','Ссылка E-mail');
DEFINE('_PRINT_EMAIL_ICONS','Значки Печать и E-mail');
DEFINE('_PRINT_EMAIL_ICONS2','Если выбрано Показать, то ссылки Печать и E-mail будут отображаться в виде значков, иначе - простым текстом-ссылкой.');
DEFINE('_ENABLE_TOC','Оглавление для многостраничных объектов');
DEFINE('_BACK_BUTTON','Кнопка Назад (Вернуться)');
DEFINE('_CONTENT_NAV','Навигация по содержимому');
DEFINE('_UNIQ_ITEMS_IDS','Уникальные идентификаторы новостей');
DEFINE('_UNIQ_ITEMS_IDS2','При включении параметра для каждой новости в списке будет задаваться уникальный идентификатор стиля.');
DEFINE('_AUTO_PUBLICATION_FRONT','Автоматическая публикация на главной');
DEFINE('_AUTO_PUBLICATION_FRONT2','При включении параметра всё создаваемое содержимое будет автоматически помечено для публикации на главной странице.');
DEFINE('_DISABLE_BLOCK','Отключить блокировки содержимого');
DEFINE('_DISABLE_BLOCK2','При включении параметра блокировки объектов содержимого не будут проверяться. Не рекомендуется использовать на сайтах с большим числом редакторов.');
DEFINE('_ITEMID_COMPAT','Режим работы Itemid');
DEFINE('_ONE_EDITOR','Использовать единое поле редактора');
DEFINE('_ONE_EDITOR2','Использовать одно поле для Вводного и Основного текста.');
DEFINE('_LOCALE','Локаль');
DEFINE('_TIME_OFFSET','Часовой пояс (смещение времени относительно UTC, ч)');
DEFINE('_TIME_OFFSET2','Текущие дата и время, которые будут показываться на сайте:');
DEFINE('_TIME_DIFF','Разница со временем сервера, ч');
DEFINE('_TIME_DIFF2','RSS (смещение времени относительно UTC, ч)');
DEFINE('_CURR_DATE_TIME_RSS','Текущие дата и время, которые будут показываться в RSS');
DEFINE('_COUNTRY_LOCALE','Локаль страны');
DEFINE('_COUNTRY_LOCALE2','Определяет региональные настройки страны: отображение даты, времени, чисел и т.д.');
DEFINE('_LOCALE_WINDOWS','При использовании в Windows необходимо ввести <span style="color: red"><strong>RU</strong></span>.
	  <br />В Unix-системах, если не работает значение по умолчанию, можно попробовать изменить регистр символов локали на <strong>RU_RU.CP1251, ru_RU.cp1251, ru_ru.CP1251</strong>, или узнать значение русской локали у провайдера.<br />
Также можно попробовать ввести одну из следующих локалей: <strong>rus, russian</strong>');
DEFINE('_DB_HOST','Адрес хоста MySQL');
DEFINE('_DB_USER','Имя пользователя БД (MySQL)');
DEFINE('_DB_NAME','База данных MySQL');
DEFINE('_DB_PREFIX','Префикс базы данных MySQL');
DEFINE('_DB_PREFIX2','!! НЕ ИЗМЕНЯЙТЕ, ЕСЛИ У ВАС УЖЕ ЕСТЬ РАБОЧАЯ БАЗА ДАННЫХ. В ПРОТИВНОМ СЛУЧАЕ, ВЫ МОЖЕТЕ ПОТЕРЯТЬ К НЕЙ ДОСТУП !!');
DEFINE('_EVERYDAY_OPTIMIZATION','Ежедневная оптимизация таблиц базы данных');
DEFINE('_EVERYDAY_OPTIMIZATION2','Если `Да`, то каждые сутки база данных будет автоматически оптимизирована для лучшего быстродействия');
DEFINE('_OLD_MYSQL_SUPPORT','Поддержка младших версий MySQL');
DEFINE('_OLD_MYSQL_SUPPORT2','Параметр позволяет отключить автоматический перевод работы БД в режим совместимости с кириллицей');
DEFINE('_DISABLE_SET_SQL','Отключить SET sql_mode');
DEFINE('_DISABLE_SET_SQL2','Отключить перевод режима работы базы данных SET sql_mode');
DEFINE('_SERVER','Сервер');
DEFINE('_ABS_PATH','Абсолютный путь( корень сайта )');
DEFINE('_MEDIA_ROOT','Корень медиа менеджера');
DEFINE('_MEDIA_ROOT2','Корневой каталог для работы компонента управления медиа данными. Путь от корня сайта без / по краям.');
DEFINE('_FILE_ROOT','Корень файлового менеджера');
DEFINE('_FILE_ROOT2','Корневой каталог для работы компонента управления файлами. Без / в конце. При использовании в Windows (c) путь может начинаться с названия буквы диска.');
DEFINE('_SECRET_WORD','Секретное слово');
DEFINE('_GZ_CSS_JS','Сжатие CSS и JS файлов');
DEFINE('_SESSION_TYPE','Метод идентификации сессии');
DEFINE('_SESSION_TYPE2','Не изменяйте, если не знаете, зачем это надо!<br /><br /> Если сайт будет использоваться пользователями службы AOL или пользователями, использующими для доступа на сайт прокси-серверы, то можете использовать настройки 2 уровня');
DEFINE('_HELP_SERVER','Сервер помощи');
DEFINE('_HELP_SERVER2','Сервер помощи - Если поле пустое, то файлы помощи будут браться из локальной папки http://адрес_вашего_сайта/help/, Для включения сервера On-Line помощи введите http://help.joom.ru или http://help.joomla.org');
DEFINE('_FILE_MODE','Создание файлов');
DEFINE('_FILE_MODE2','Разрешения доступа к файлам');
DEFINE('_FILE_MODE3','Не менять CHMOD для новых файлов (использовать умолчание сервера)');
DEFINE('_FILE_MODE4','Установить CHMOD для новых файлов');
DEFINE('_FILE_MODE5','Выберите этот пункт для установки разрешений доступа к вновь создаваемым файлам');
DEFINE('_OWNER','Владелец');
DEFINE('_O_READ','чтение');
DEFINE('_O_WRITE','запись');
DEFINE('_O_EXEC','выполнение');
DEFINE('_APPLY_TO_FILES','Применить к существующим файлам');
DEFINE('_APPLY_TO_FILES2','Изменения коснутся <em>всех существующих файлов</em> на сайте.<br/><b>НЕПРАВИЛЬНОЕ ИСПОЛЬЗОВАНИЕ ЭТОЙ ОПЦИИ МОЖЕТ ПРИВЕСТИ К НЕРАБОТОСПОСОБНОСТИ САЙТА!</b>');
DEFINE('_DIR_CREATION','Создание каталогов');
DEFINE('_DIR_CREATION2','Разрешения доступа к каталогам');
DEFINE('_DIR_CREATION3','Не менять CHMOD для новых каталогов (использовать умолчание сервера)');
DEFINE('_DIR_CREATION4','Установить CHMOD для новых каталогов');
DEFINE('_DIR_CREATION5','Выберите этот пункт для установки разрешений доступа к вновь создаваемым каталогам');
DEFINE('_O_SEARCH','поиск');
DEFINE('_APPLY_TO_DIRS','Применить к существующим каталогам');
DEFINE('_APPLY_TO_DIRS2','Включение этих флагов будет применено ко<em> всем существующим каталогам</em> на сайте.<br/>'.'<b>НЕПРАВИЛЬНОЕ ИСПОЛЬЗОВАНИЕ ЭТОЙ ОПЦИИ МОЖЕТ ПРИВЕСТИ К НЕРАБОТОСПОСОБНОСТИ САЙТА!</b>');
DEFINE('_O_GROUP','Группа');
DEFINE('_O_AS','как');
DEFINE('_RG_EMULATION_TXT','Эмуляция Регистрации глобальных переменных');
DEFINE('_RG_DISABLE','Выкл. (РЕКОМЕНДУЕТСЯ) - дополнительная защита');
DEFINE('_RG_ENABLE','Вкл. (НЕ РЕКОМЕНДУЕТСЯ) - совместимость со старыми расширениями, при использовании параметра повышается угроза безопасности.');
DEFINE('_METADATA','Метаданные');
DEFINE('_SITE_DESC','Описание сайта, которое индексируется поисковиками');
DEFINE('_SITE_DESC2',' Вы можете не ограничивать Ваше описание двадцатью словами, в зависимости от Поискового сервера, который Вы планируете использовать. Делайте описание кратким и подходящим для содержания вашего сайта. Вы можете включить некоторые из ваших ключевых слов и ключевых фраз. Так как некоторые поисковые серверы читают больше 20 слов, то Вы можете добавить одно или два предложения. Пожалуйста удостоверьтесь, что самая важная часть вашего описания находится в первых 20 словах.');
DEFINE('_SITE_KEYWORDS','Ключевые слова сайта');
DEFINE('_SHOW_TITLE_TAG','Показывать мета-тег <b>title</b>');
DEFINE('_SHOW_TITLE_TAG2','Показывает мета-тег <b>title</b> при просмотре объектов содержимого');
DEFINE('_SHOW_AUTHOR_TAG','Показывать мета-тег <b>author</b>');
DEFINE('_SHOW_AUTHOR_TAG2','Показывает мета-тег <b>author</b> при просмотре объектов содержимого');
DEFINE('_SHOW_BASE_TAG','Показывать мета-тег <b>base</b>');
DEFINE('_SHOW_BASE_TAG2','Показывает мета-тег <b>base</b> в теле каждой страницы');
DEFINE('_REVISIT_TAG','Значение тега <b>revisit</b>');
DEFINE('_REVISIT_TAG2','Укажите значение тега <b>revisit</b> в днях');
DEFINE('_DISABLE_GENERATOR_TAG','Отключить тег Generator');
DEFINE('_DISABLE_GENERATOR_TAG2','Если `Да`, то из кода каждой HTML страницы будет исключен тег name=\\\'Generator\\\'');
DEFINE('_EXT_IND_TAGS','Расширенные теги индексации');
DEFINE('_EXT_IND_TAGS2','Если `Да`, то в код каждой страницы будут добавлены специальные теги для лучшей индексации');
DEFINE('_MAIL','Почта');
DEFINE('_MAIL_METHOD','Для отправки почты использовать');
DEFINE('_MAIL_FROM_ADR','Письма от (Mail From)');
DEFINE('_MAIL_FROM_NAME','Отправитель (From Name)');
DEFINE('_SENDMAIL_PATH','Путь к Sendmail');
DEFINE('_USE_SMTP','Использовать SMTP-авторизацию');
DEFINE('_USE_SMTP2','Выберите ДА, если для отправки почты используется smtp-сервер с необходимостью авторизации');
DEFINE('_SMTP_USER','Имя пользователя SMTP');
DEFINE('_SMTP_USER2','Заполняется, если для отправки почты используется smtp-сервер с необходимостью авторизации');
DEFINE('_SMTP_PASS','Пароль для доступа к SMTP');
DEFINE('_SMTP_PASS2','Заполняется, если для отправки почты используется smtp-сервер с необходимостью авторизации');
DEFINE('_SMTP_SERVER','Адрес SMTP-сервера');
DEFINE('_CACHE','Кэш');
DEFINE('_ENABLE_CACHE','Включить кэширование');
DEFINE('_ENABLE_CACHE2','Включение кэширования уменьшает запросы к MySQL и уменьшению нагрузки на сервер');
DEFINE('_CACHE_OPTIMIZATION','Оптимизация кэширования');
DEFINE('_CACHE_OPTIMIZATION2','Автоматически удаляет из файлов кэша лишние символы тем самым уменьшая размер файлов.');
DEFINE('_AUTOCLEAN_CACHE_DIR','Автоматическая очистка каталога кэша');
DEFINE('_AUTOCLEAN_CACHE_DIR2','Автоматически очищать каталог кэша удаляя просроченные файлы.');
DEFINE('_CACHE_MENU','Кэширование меню панели управления');
DEFINE('_CACHE_MENU2','Включение кэширования меню панели управления. Работает независимо от стандартного кэша.');
DEFINE('_CANNOT_CACHE','Кэширование не возможно');
DEFINE('_CANNOT_CACHE2','<font color="red"><b>Каталог кэша не доступен для записи.</b></font>');
DEFINE('_CACHE_DIR','Каталог кэша');
DEFINE('_CACHE_DIR2','Текущий каталог кэша <b>Доступен для записи</b>');
DEFINE('_CACHE_DIR3','Текущий каталог кэша <b>НЕ ДОСТУПЕН ДЛЯ ЗАПИСИ</b> - смените CHMOD каталога на 755 перед включением кэша');
DEFINE('_CACHE_TIME','Время жизни кэша');
DEFINE('_DB_CACHE','Кэш запросов базы данных');
DEFINE('_DB_CACHE_TIME','Время жизни кэша запросов базы данных');
DEFINE('_STATICTICS','Статистика');
DEFINE('_ENABLE_STATS','Включить сбор статистики');
DEFINE('_ENABLE_STATS2','Разрешить/запретить сбор статистики сайта');
DEFINE('_STATS_HITS_DATE','Вести статистику просмотра содержимого по дате');
DEFINE('_STATS_HITS_DATE2','ПРЕДУПРЕЖДЕНИЕ: В этом режиме записываются большие объемы данных!');
DEFINE('_STATS_SEARCH_QUERIES','Статистика поисковых запросов');
DEFINE('_SEF_URLS','Дружественные для поисковых систем URL-ы (SEF)');
DEFINE('_SEF_URLS2','Только для Apache! Перед использованием переименуйте htaccess.txt в .htaccess. Это необходимо для включения модуля apache - mod_rewrite');
DEFINE('_DYNAMIC_PAGETITLES','Динамические заголовки страниц (теги title)');
DEFINE('_DYNAMIC_PAGETITLES2','Динамическое изменение заголовков страниц в зависимости от текущего просматриваемого содержимого');
DEFINE('_CLEAR_FRONTPAGE_LINK','Очистка ссылки на com_frontpage');
DEFINE('_CLEAR_FRONTPAGE_LINK2','Придавать ссылке на компонент главной страницы более короткий вид.');
DEFINE('_DISABLE_PATHWAY_ON_FRONT','Скрывать пачвей (pathway) на главной');
DEFINE('_DISABLE_PATHWAY_ON_FRONT2','При включенном режиме строка \\\'Главная\\\' на первой странице сайта будет заменена на символ неразрывного пробела.');
DEFINE('_TITLE_ORDER','Порядок расположения элементов title');
DEFINE('_TITLE_ORDER2','Порядок расположения элементов заголовка страниц (тег title)');
DEFINE('_TITLE_SEPARATOR','Разделитель элементов title');
DEFINE('_TITLE_SEPARATOR2','Разделитель элементов заголовка страниц (тег title). По умолчанию - дефис.');
DEFINE('_INDEX_PRINT_PAGE','Индексация печатной версии');
DEFINE('_INDEX_PRINT_PAGE2','Если `Да`, то печатная версия содержимого будет доступна для индексации');
DEFINE('_REDIR_FROM_NOT_WWW','Переадресация с не WWW адресов');
DEFINE('_REDIR_FROM_NOT_WWW2','При заходе на сайт по ссылке site.ru, автоматически будет произведена переадресация на www.sie.ru');
DEFINE('_ADMIN_CAPTCHA','Для авторизации в панели управления');
DEFINE('_ADMIN_CAPTCHA2','Использовать captcha для более безопасной авторизации в панели управления.');
DEFINE('_REGISTRATION_CAPTCHA','Для регистрации');
DEFINE('_REGISTRATION_CAPTCHA2','Использовать captcha для более безопасной регистрации.');
DEFINE('_CONTACTS_CAPTCHA','Для формы контактов');
DEFINE('_CONTACTS_CAPTCHA2','Использовать captcha для более безопасной формы контактов.');

DEFINE('_O_OTHER','Разные');
DEFINE('_SECURITY_LEVEL3','3 уровень защиты - По умолчанию - наилучший');
DEFINE('_SECURITY_LEVEL2','2 уровень защиты - Разрешено для IP-адресов прокси');
DEFINE('_SECURITY_LEVEL1','1 уровень защиты - Обратная совместимость');
DEFINE('_PHP_MAIL_FUNCTION','Функцию PHP mail');
DEFINE('_CONSTRUCT_ERROR','ошибка сборки');

DEFINE('_TIME_OFFSET_M_12','(UTC -12:00) Международная линия суточного времени');
DEFINE('_TIME_OFFSET_M_11','(UTC -11:00) остров Мидуэй, Самоа');
DEFINE('_TIME_OFFSET_M_10','(UTC -10:00) Гавайи');
DEFINE('_TIME_OFFSET_M_9_5','(UTC -09:30) Тайохае, Маркизские острова');
DEFINE('_TIME_OFFSET_M_9','(UTC -09:00) Аляска');
DEFINE('_TIME_OFFSET_M_8','(UTC -08:00) Тихоокеанское время (США &amp; Канада)');
DEFINE('_TIME_OFFSET_M_7','(UTC -07:00) Время Монтаны (США &amp; Канада)');
DEFINE('_TIME_OFFSET_M_6','(UTC -06:00) Центральное время  (США &amp; Канада), Мехико');
DEFINE('_TIME_OFFSET_M_5','(UTC -05:00) Восточное время (США &amp; Канада), Богота, Лайма');
DEFINE('_TIME_OFFSET_M_4','(UTC -04:00) Атлантическое время (Канада), Каракас, Ла-Пас');
DEFINE('_TIME_OFFSET_M_3_5','(UTC -03:30) Ньюфаундленд и Лабрадор');
DEFINE('_TIME_OFFSET_M_3','(UTC -03:00) Бразилия, Буэнос Айрес, Джорджтаун');
DEFINE('_TIME_OFFSET_M_2','(UTC -02:00) Средне-Атлантическое время');
DEFINE('_TIME_OFFSET_M_1','(UTC -01:00 час) Азорские острова, Острова Зеленого Мыса');
DEFINE('_TIME_OFFSET_M_0','(UTC 00:00) Западно-Европейское время, Лондон, Лиссабон, Касабланка');
DEFINE('_TIME_OFFSET_P_1','(UTC +01:00 час) Брюссель, Копенгаген, Мадрид, Париж');
DEFINE('_TIME_OFFSET_P_2','(UTC +02:00) Украина, Киев, Минск, Калининград, Южная Африка');
DEFINE('_TIME_OFFSET_P_3','(UTC +03:00) Москва, Санкт-Петербург, Багдад, Эр-Рияд');
DEFINE('_TIME_OFFSET_P_3_5','(UTC +03:30) Тегеран');
DEFINE('_TIME_OFFSET_P_4','(UTC +04:00) Самара, Баку, Тбилиси, Абу-Даби, Мускат');
DEFINE('_TIME_OFFSET_P_4_5','(UTC +04:30) Кабул');
DEFINE('_TIME_OFFSET_P_5','(UTC +05:00) Оренбург, Екатеринбург, Пермь, Ташкент, Исламабад, Карачи');
DEFINE('_TIME_OFFSET_P_5_5','(UTC +05:30) Бомбей, Калькутта, Мадрас, Нью-Дели');
DEFINE('_TIME_OFFSET_P_5_75','(UTC +05:45) Катманду');
DEFINE('_TIME_OFFSET_P_6','(UTC +06:00) Омск, Новосибирск, Алматы, Дака, Коломбо');
DEFINE('_TIME_OFFSET_P_6_5','(UTC +06:30) Ягун');
DEFINE('_TIME_OFFSET_P_7','(UTC +07:00) Красноярск, Бангкок, Ханой, Джакарта');
DEFINE('_TIME_OFFSET_P_8','(UTC +08:00) Иркутск, Улан-Батор, Пекин, Сингапур, Гонконг');
DEFINE('_TIME_OFFSET_P_8_75','(UTC +08:00) Западная Австралия');
DEFINE('_TIME_OFFSET_P_9','(UTC +09:00) Якутск, Токио, Сеул, Осака, Саппоро');
DEFINE('_TIME_OFFSET_P_9_5','(UTC +09:30) Аделаида, Дарвин');
DEFINE('_TIME_OFFSET_P_10','(UTC +10:00) Владивосток, Гуам, Восточная Австралия');
DEFINE('_TIME_OFFSET_P_10_5','(UTC +10:30) остров Lord Howe (Австралия)');
DEFINE('_TIME_OFFSET_P_11','(UTC +11:00) Магадан, Соломоновы острова, Новая Каледония');
DEFINE('_TIME_OFFSET_P_11_5','(UTC +11:30) остров Норфолк');
DEFINE('_TIME_OFFSET_P_12','(UTC +12:00) Камчатка, Окленд, Уэллингтон, Фиджи');
DEFINE('_TIME_OFFSET_P_12_75','(UTC +12:45) Остров Чатем');
DEFINE('_TIME_OFFSET_P_13','(UTC +13:00) Тонга');
DEFINE('_TIME_OFFSET_P_14','(UTC +14:00) Кирибати');

/* administrator components com_contact */

DEFINE('_CONTACT_MANAGEMENT','Управление контактами');
DEFINE('_ON_SITE','На сайте');
DEFINE('_RELATED_WITH_USER','Связано с пользователем');
DEFINE('_CHANGE_CONTACT','Изменить контакт');
DEFINE('_CHANGE_CATEGORY','Изменить категорию');
DEFINE('_CHANGE_USER','Изменить пользователя');
DEFINE('_ENTER_NAME_PLEASE','Вы должны ввести имя');
DEFINE('_NEW_CONTACT','Новый');
DEFINE('_CONTACT_DETAILS','Детали контакта');
DEFINE('_USER_POSITION','Положение (должность)');
DEFINE('_ADRESS_STREET_HOUSE','Адрес (улица, дом)');
DEFINE('_CITY','Город');
DEFINE('_STATE','Край/Область/Республика');
DEFINE('_COUNTRY','Страна');
DEFINE('_POSTCODE','Почтовый индекс');
DEFINE('_ADDITIONAL_INFO','Дополнительная информация');
DEFINE('_PUBLISH_INFO','Информация о публикации');
DEFINE('_POSITION','Расположение');
DEFINE('_IMAGES_INFO','Информация об изображении');
DEFINE('_PARAMETERS','Параметры');
DEFINE('_CONTACT_PARAMS','* Эти параметры управляют отображением только при просмотре информации о контакте*');


/* administrator components com_content */

DEFINE('_SITE_CONTENT','Содержимое сайта');
DEFINE('_GOTO_EDIT','Перейти в редактирование');
DEFINE('_SORT_BY','Сортировка по');
DEFINE('_HIDE_NAV_TREE','Скрыть дерево навигации');
DEFINE('_ON_FRONTPAGE','На главной');
DEFINE('_SAVE_ORDER','Сохранить порядок');
DEFINE('_TO_TRASH','В корзину');
DEFINE('_NEVER','Никогда');
DEFINE('_START','Начало');
DEFINE('_ALWAYS','Всегда');
DEFINE('_END','Окончание');
DEFINE('_WITHOUT_END','Без окончания');
DEFINE('_CHANGE_USER_DATA','Изменить данные пользователя');
DEFINE('_CHANGE_CONTENT','Изменить содержимое');
DEFINE('_CHOOSE_OBJECTS_TO_TRASH','Пожалуйста, выберите из списка объекты, которые Вы хотите отправить в корзину');
DEFINE('_WANT_TO_TRASH','Вы уверены, что хотите отправить объект(ы) в корзину? \n Это не приведет к полному удалению объектов');
DEFINE('_ARCHIVE','Архив');
DEFINE('_ALL_SECTIONS','Все разделы');
DEFINE('_OBJECT_MUST_HAVE_TITLE','Этот объект должен иметь заголовок');
DEFINE('_PLEASE_CHOOSE_SECTION','Вы должны выбрать раздел');
DEFINE('_PLEASE_CHOOSE_CATEGORY','Вы должны выбрать категорию');
DEFINE('_O_EDITING','Редактирование');
DEFINE('_O_CREATION','Создание');
DEFINE('_OBJECT_DETAILS','Детали объекта');
DEFINE('_ALIAS','Псевдоним');
DEFINE('_INTROTEXT_M','Вводный Текст: (обязательно)');
DEFINE('_MAINTEXT_M','Основной текст: (необязательно)');
DEFINE('_NOTETEXT_M','Заметки: (необязательно)');
DEFINE('_HIDE_PARAMS_PANEL','Скрыть панель параметров');
DEFINE('_SETTINGS','Настройки');
DEFINE('_IN_ARCHIVE','В архиве');
DEFINE('_DRAFT_NOT_PUBLISHED','Черновик - Не опубликован');
DEFINE('_RESET','Обнулить');
DEFINE('_CHANGED','Изменялось');
DEFINE('_CREATED','Создано');
DEFINE('_NEW_DOCUMENT','Новый документ');
DEFINE('_LAST_CHANGE','Последнее изменение');
DEFINE('_NOT_CHANGED','Не изменялось');
DEFINE('_START_PUBLICATION','Начало публикации');
DEFINE('_END_PUBLICATION','Окончание публикации');
DEFINE('_OBJECT_ID','ID объекта');
DEFINE('_USED_IMAGES','Используемые изображения');
DEFINE('_SUBDIRECTORY','Подпапка');
DEFINE('_IMAGE_EXAMPLE','Пример изображения');
DEFINE('_ACTIVE_IMAGE','Активное изображение');
DEFINE('_SOURCE','Источник');
DEFINE('_ALIGN','Выравнивание');
DEFINE('_PARAMS_IN_VIEW','* Эти параметры управляют внешним видом только в режиме полного просмотра*');
DEFINE('_ROBOTS_PARAMS','Настройки управления роботами');
DEFINE('_MENU_LINK','Связь с меню');
DEFINE('_MENU_LINK2','Здесь создается пункт меню (Ссылка - объект содержимого), который вставляется в выбранное из списка меню');
DEFINE('_EXISTED_MENUITEMS','Существующие пункты меню');
DEFINE('_PLEASE_SELECT_SMTH','Пожалуйста, выберите что-нибудь');
DEFINE('_OBJECT_MOVING','Перемещение объектов');
DEFINE('_MOVE_INTO_CAT_SECT','Переместить в раздел/категорию');
DEFINE('_OBJECTS_TO_MOVE','Будут перемещены объекты');
DEFINE('_SELECT_CAT_TO_MOVE_OBJECTS','Пожалуйста, выберите раздел или категорию для копирования объектов');
DEFINE('_COPYING_CONTENT_ITEMS','Копирование объектов содержимого');
DEFINE('_COPY_INTO_CAT_SECT','Копировать в раздел/категорию');
DEFINE('_OBJECTS_TO_COPY','Будут скопированы объекты');
DEFINE('_ORDER_BY_NAME','Внутреннему порядку');
DEFINE('_ORDER_BY_HEADERS','Заголовкам');
DEFINE('_ORDER_BY_DATE_CR','Дате создания');
DEFINE('_ORDER_BY_DATE_MOD','Дате модификации');
DEFINE('_ORDER_BY_ID','Идентификаторам ID');
DEFINE('_ORDER_BY_HITS','Просмотрам');
DEFINE('_CANNOT_EDIT_ARCHIVED_ITEM','Вы не можете отредактировать архивный объект');
DEFINE('_NOW_EDITING_BY_OTHER','в настоящее время редактируется другим пользователем');
DEFINE('_ROBOTS_HIDE','Скрыть мета-тег robots');
DEFINE('_CONTENT_ITEM_SAVED','Изменения успешно сохранены в');
DEFINE('_OBJ_ARCHIVED','Объект(ы) успешно архивирован(ы)');
DEFINE('_OBJ_PUBLISHED','Объект(ы) успешно опубликован(ы)');
DEFINE('_OBJ_UNARCHIVED','Объект(ы) успешно извлечен(ы) из архива');
DEFINE('_OBJ_UNPUBLISHED','Объект(ы) успешно снят(ы) с публикации');
DEFINE('_CHOOSE_OBJ_TOGGLE','Выберите объект для переключения');
DEFINE('_CHOOSE_OBJ_DELETE','Выберите объект для удаления');
DEFINE('_MOVED_TO_TRASH','Отправлено в корзину');
DEFINE('_CHOOSE_OBJ_MOVE','Выберите объект для перемещения');
DEFINE('_ERROR_OCCURED','Произошла ошибка');
DEFINE('_OBJECTS_MOVED_TO_SECTION','объект(ы) успешно перемещен(ы) в раздел');
DEFINE('_OBJECTS_COPIED_TO_SECTION','объект(ы) успешно скопированы в раздел');
DEFINE('_HITCOUNT_RESETTED','Счетчик просмотров сброшен');

/* administrator components com_easysql */

DEFINE('_SQL_COMMAND','Команда');
DEFINE('_MANAGEMENT','Управление');
DEFINE('_FIELDS','Поля');
DEFINE('_EXEC_SQL','Выполнить SQL');

/* administrator components com_frontpage */

DEFINE('_UNKNOWN_ID','Идентификатор не опознан');
DEFINE('_REMOVE_FROM_FRONT','Убрать с главной');
DEFINE('_PUBLISH_TIME_END','Время публикации истекло');
DEFINE('_CANNOT_CHANGE_PUBLISH_STATE','Смена статуса публикации недоступна');
DEFINE('_CHANGE_SECTION','Изменить раздел');

/* administrator components com_installer */

DEFINE('_OTHER_COMPONENT_USE_DIR','Другой компонент уже использует каталог');
DEFINE('_CANNOT_CREATE_DIR','Невозможно создать каталог');
DEFINE('_SQL_ERROR','Ошибка выполнения SQL');
DEFINE('_ERROR_MESSAGE','Текст ошибки');
DEFINE('_CANNOT_COPY_PHP_INSTALL','Не могу скопировать PHP-файл установки');
DEFINE('_CANNOT_COPY_PHP_REMOVE','Не могу скопировать PHP-файл удаления');
DEFINE('_ERROR_DELETING','Ошибка удаления');
DEFINE('_IS_PART_OF_CMS','является элементом ядра Joomla и не может быть удален.<br />Вы должны снять его с публикации, если не хотите его использовать');
DEFINE('_DELETE_ERROR','Удаление - ошибка');
DEFINE('_UNINSTALL_ERROR','Ошибка деинсталляции');
DEFINE('_BAD_XML_FILE','Неправильный XML-файл');
DEFINE('_PARAM_FILED_EMPTY','Поле параметра пустое и невозможно удалить файлы');
DEFINE('_INSTALLED_COMPONENTS','Установленные компоненты');
DEFINE('_INSTALLED_COMPONENTS2','Здесь показаны только те расширения, которые Вы можете удалить. Части ядра Joostina удалить нельзя.');
DEFINE('_COMPONENT_NAME','Название компонента');
DEFINE('_COMPONENT_LINK','Ссылка меню компонента');
DEFINE('_COMPONENT_AUTHOR_URL','URL автора');
DEFINE('_OTHER_COMPONENTS_NOT_INSTALLED','Сторонние компоненты не установлены');
DEFINE('_COMPONENT_INSTALL','Установка компонента');
DEFINE('_DELETING','Удаление');
DEFINE('_CANNOT_DEL_LANG_ID','id языка пусто, поэтому невозможно удалить файлы');
DEFINE('_GOTO_LANG_MANAGEMENT','Перейти в Управление языками');
DEFINE('_INTALL_LANG','Установка языкового пакета сайта');
DEFINE('_NO_FILES_OF_MAMBOTS','Нет файлов, отмеченных как мамботы');
DEFINE('_WRONG_ID','Неправильный id объекта');
DEFINE('_BAD_DIR_NAME_EMPTY','Поле папки пустое, невозможно удалить файлы');
DEFINE('_INSTALLED_MAMBOTS','Установленные мамботы');
DEFINE('_MAMBOT','Мамбот');
DEFINE('_TYPE','Тип');
DEFINE('_OTHER_MAMBOTS','Это не мамботы ядра, а сторонние мамботы');
DEFINE('_INSTALL_MAMBOT','Установка мамбота');
DEFINE('_UNKNOWN_CLIENT','Неизвестный тип клиента');
DEFINE('_NO_FILES_MODULES','Файлы, отмеченные как модули, отсутствуют');
DEFINE('_ALREADY_EXISTS','уже существует');
DEFINE('_DELETING_XML_FILE','Удаление XML файла');
DEFINE('_INSTALL_MODULE','Установленные модулей');
DEFINE('_MODULE','Модуль');
DEFINE('_USED_ON','Используется');
DEFINE('_NO_OTHER_MODULES','Сторонние модули не установлены');
DEFINE('_MODULE_INSTALL','Установка модуля');
DEFINE('_SITE_MODULES','Модули сайта');
DEFINE('_ADMIN_MODULES','Модули панели управления');
DEFINE('_CANNOT_DEL_FILE_NO_DIR','Невозможно удалить файл, т.к. каталог не существует');
DEFINE('_WRITEABLE','Доступен для записи');
DEFINE('_UNWRITEABLE','Недоступен для записи');
DEFINE('_CHOOSE_DIRECTORY_PLEASE','Пожалуйста, выберите каталог');
DEFINE('_ZIP_UPLOAD_AND_INSTALL','Загрузка архива расширения с последующей установкой');
DEFINE('_PACKAGE_FILE','Файл пакета');
DEFINE('_UPLOAD_AND_INSTALL','Загрузить и установить');
DEFINE('_INSTALL_FROM_DIR','Установка из каталога');
DEFINE('_INSTALLATION_DIRECTORY','Каталог установки');
DEFINE('_CONTINUE','Продолжить');
DEFINE('_NO_INSTALLER','не найден инсталлятор');
DEFINE('_CANNOT_INSTALL','Установка [$element] невозможна');
DEFINE('_CANNOT_INSTALL_DISABLED_UPLOAD','Установка невозможна, пока запрещена загрузка файлов. Пожалуйста, используйте установку из каталога.');
DEFINE('_INSTALL_ERROR','Ошибка установки');
DEFINE('_CANNOT_INSTALL_NO_ZLIB','Установка невозможна, пока не установлена поддержка zlib');
DEFINE('_NO_FILE_CHOOSED','Файл не выбран');
DEFINE('_ERORR_UPLOADING_EXT','Ошибка загрузки нового модуля');
DEFINE('_UPLOADING_ERROR','Загрузка неудачна');
DEFINE('_SUCCESS','успешно');
DEFINE('_UNSUCCESS','неудачно');
DEFINE('_UPLOAD_OF_EXT','Загрузка нового элемента');
DEFINE('_DELETE_SUCCESS','Удаление успешно');
DEFINE('_CANNOT_CHMOD','Не могу изменить права доступа к закачанному файлу');
DEFINE('_CANNOT_MOVE_TO_MEDIA','Не могу переместить скачанный файл в каталог <code>/media</code>');
DEFINE('_CANNOT_WRITE_TO_MEDIA','Загрузка сорвана, так как каталог <code>/media</code> недоступен для записи.');
DEFINE('_CANNOT_INSTALL_NO_MEDIA','Загрузка сорвана, так как каталог <code>/media</code> не существует');
DEFINE('_ERROR_NO_XML_JOOMLA','ОШИБКА: В установочном пакете невозможно найти XML-файл установки Joomla.');
DEFINE('_ERROR_NO_XML_INSTALL','ОШИБКА: В установочном пакете невозможно найти XML-файл установки.');
DEFINE('_NO_NAME_DEFINED','Не определено имя файла');
DEFINE('_FILE','Файл');
DEFINE('_NOT_CORRECT_INSTALL_FILE_FOR_JOOMLA','не является корректным файлом установки Joomla!');
DEFINE('_CANNOT_RUN_INSTALL_METHOD','Метод "install" не может быть вызван классом');
DEFINE('_CANNOT_RUN_UNINSTALL_METHOD','Метод "uninstall" не может быть вызван классом');
DEFINE('_CANNOT_FIND_INSTALL_FILE','Установочный файл не найден');
DEFINE('_XML_NOT_FOR','Установочный XML-файл - не для');
DEFINE('_FILE_NOT_EXISTS','Файл не существует');
DEFINE('_INSTALL_TWICE','Вы пытаетесь дважды установить одно и то же расширение');
DEFINE('_ERROR_COPYING_FILE','Ошибка копирования файла');

/* administrator components com_jce */

DEFINE('_LANG_ALREADY_EXISTS','Язык уже существует');
DEFINE('_EMPTY_LANG_ID','Пустой id языка, невозможно удалить файлы');
DEFINE('_NO_PLUGIN_FILES','Файлы плагинов отсутствуют');
DEFINE('_BAD_OBJECT_ID','Неверный id объекта');
DEFINE('_EMPRY_DIR_NAME_CANNOT_DEL_FILE','Поле папки пустое, невозможно удалить файл');
DEFINE('_INSTALLED_JCE_PLUGINS','Установленные плагины JCE');
DEFINE('_PCLZIP_UNKNOWN_ERROR','Неисправимая ошибка');
DEFINE('_UNZIP_ERROR','Ошибка распаковки');
DEFINE('_JCE_INSTALL_ERROR_NO_XML','ОШИБКА: Невозможно найти в пакете XML-файл установки JCE 1.1.x.');
DEFINE('_JCE_INSTALL_ERROR_NO_XML2','ОШИБКА: Невозможно найти в пакете XML-файл установки.');
DEFINE('_JCE_UNKNOWN_FILENAME','Имя файла не определено');
DEFINE('_BAD_JCE_INSTALL_FILE',' неправильный файл установки JCE или его версия неправильная.');
DEFINE('_WRONG_PLUGIN_VERSION','Неправильная версия плагина');
DEFINE('_ERROR_CREATING_DIRECTORY','Ошибка создания каталога');
DEFINE('_INSTALLER_NOT_FIND_ELEMENT','Инсталлятор не обнаружил элемент');
DEFINE('_NO_INSTALLER_FOR_COMPONENT','Инсталлятор недоступен для элемента');
DEFINE('_NO_CHOOSED_FILES','Файлы не выбраны');
DEFINE('_ERROR_OF_UPLOAD','Ошибка загрузки');
DEFINE('_UPLOADING','Загрузка');
DEFINE('_IS_SUCCESS','успешна');
DEFINE('_HAS_ERROR','завершилась ошибкой');
DEFINE('_CANNOT_DELETE_LANG_FILE','Нельзя удалять используемый языковой пакет');
DEFINE('_GUEST','Гость');
DEFINE('_EDITOR','Редактор');
DEFINE('_PUBLISHER','Издатель');
DEFINE('_MANAGER','Менеджер');
DEFINE('_ADMINISTRATOR','Администратор');
DEFINE('_SUPER_ADMINISTRATOR','Супер-Администратор');
DEFINE('_CHANGES_FOR_PLUGIN','Изменения для плагина');
DEFINE('_SUCCESS_SAVE','успешное сохранение');
DEFINE('_PLUGIN','Плагин');
DEFINE('_MODULE_IS_EDITING_BY_ADMIN','Модуль $row->title в настоящее время редактируется другим администратором');
DEFINE('_CHOOSE_PLUGIN_FOR_ACCESS_MANAGEMENT','Для назначения прав доступа необходимо выбрать плагин');
DEFINE('_CHOOSE_PLUGIN_FOR','Выберите плагин для');
DEFINE('_JCE_CONFIG','Конфигурация JCE');
DEFINE('_EDITOR_CONFIG','Конфигурация редактора');
DEFINE('_EXTENSIONS','Расширения');
DEFINE('_EXTENSION_MANAGEMENT','Управление расширениями');
DEFINE('_ICONS_POSITIONS','Расположение значков');
DEFINE('_LANG_MANAGER','Менеджер локализаций');
DEFINE('_FILE_NOT_FOUND','Файл не найде');
DEFINE('_PLUGIN_NOT_FOUND','Плагин не найден');
DEFINE('_JCE_CONTENT_MAMBOT_NOT_INSTALLED','Мамбот редактора JCE не установлен');
DEFINE('_ICONS_POSITIONS_SAVED','Расположение значков сохранено');
DEFINE('_MAIN_PAGE','Главная');
DEFINE('_NEW','Новый');
DEFINE('_INSTALLATION','Установка');
DEFINE('_PREVIEW','Предпросмотр');
DEFINE('_PLUGINS','Плагины');
/*
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
DEFINE('_','');
*/
?>