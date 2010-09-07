<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* @version $Id: ukranian.php, 2008/01/27 Denys Nosov
* @copyright (C) 2006-2008 переклад на українську мову - Денис Носов (Joomla! Україна, e-mail: dgm.denys@gmail.com http:www.joomla-ua.org)
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Доступ заборонено.' );
global $mosConfig_form_date,$mosConfig_form_date_full;

// Помилка 404
DEFINE('_404', 'Вибачте, але такої сторінки не існує.');
DEFINE('_404_RTS', 'Повернутися на сайт');

define( '_SYSERR1', 'Зв\'язок із базою даних відсутній' );
define( '_SYSERR2', 'Неможливо підключитись до серверу бази даних' );
define( '_SYSERR3', 'Неможливо підключитись до бази даних' );

// common
DEFINE('_LANGUAGE','uk');
DEFINE('_NOT_AUTH','<h2>Помилка 404!</h2>');
DEFINE('_DO_LOGIN','Можливо ця сторінка є доступною для авторизованих користувачів?');
DEFINE('_VALID_AZ09',"Будь-ласка, введіть правильно %s. Без жодних пробілів, тільки символи 0-9, a-z та A-Z не більше, ніж %d символів");
DEFINE('_VALID_AZ09_USER',"Будь-ласка, введіть правильно %s. Допустимо не менше, ніж %d символів і символи 0-9, a-z та A-Z");
DEFINE('_CMN_YES','Так');
DEFINE('_CMN_NO','Ні');
DEFINE('_CMN_SHOW','Показати');
DEFINE('_CMN_HIDE','Сховати');

DEFINE('_CMN_NAME','Ім\'я');
DEFINE('_CMN_DESCRIPTION','Опис');
DEFINE('_CMN_SAVE','Зберегти');
DEFINE('_CMN_APPLY','Застосувати');
DEFINE('_CMN_CANCEL','Скасувати');
DEFINE('_CMN_PRINT','Надрукувати');
DEFINE('_CMN_PDF','PDF');
DEFINE('_CMN_EMAIL','Надіслати електронною поштою');
DEFINE('_ICON_SEP','|');
DEFINE('_CMN_PARENT','Батьківський');
DEFINE('_CMN_ORDERING','Сортування');
DEFINE('_CMN_ACCESS','Рівень доступу');
DEFINE('_CMN_SELECT','Вибір');

DEFINE('_CMN_NEXT','Наступна');
DEFINE('_CMN_NEXT_ARROW'," &gt;&gt;");
DEFINE('_CMN_PREV','Попередня');
DEFINE('_CMN_PREV_ARROW',"&lt;&lt; ");

DEFINE('_CMN_SORT_NONE',"Без сортування");
DEFINE('_CMN_SORT_ASC',"За зростанням");
DEFINE('_CMN_SORT_DESC',"За спаданням");

DEFINE('_CMN_NEW','Новий');
DEFINE('_CMN_NONE','За замовчуванням');
DEFINE('_CMN_LEFT','Ліворуч');
DEFINE('_CMN_RIGHT','Праворуч');
DEFINE('_CMN_CENTER','У центрі');
DEFINE('_CMN_ARCHIVE','Архів');
DEFINE('_CMN_UNARCHIVE','Вилучитити з архіву');
DEFINE('_CMN_TOP','Вгорі');
DEFINE('_CMN_BOTTOM','Знизу');

DEFINE('_CMN_PUBLISHED','Опубліковано');
DEFINE('_CMN_UNPUBLISHED','Неопубліковано');

DEFINE('_CMN_EDIT_HTML','Редагувати HTML');
DEFINE('_CMN_EDIT_CSS','Редагувати CSS');

DEFINE('_CMN_DELETE','Знищити');

DEFINE('_CMN_FOLDER','Папка');
DEFINE('_CMN_SUBFOLDER','Підпапка');
DEFINE('_CMN_OPTIONAL','За бажанням');
DEFINE('_CMN_REQUIRED','Обов\'язково');

DEFINE('_CMN_CONTINUE','Продовжити');

DEFINE('_STATIC_CONTENT','Статичний зміст');

DEFINE('_CMN_NEW_ITEM_LAST','Цей елемент буде останнім. Після його збереження порядок сортування можна змінити.');
DEFINE('_CMN_NEW_ITEM_FIRST','Цей елемент буде першим. Після його збереження порядок сортування можна змінити.');
DEFINE('_LOGIN_INCOMPLETE','Будь-ласка, заповніть поля "Користувач" та "Пароль".');
DEFINE('_LOGIN_BLOCKED','Ваш обліковий запис заблоковано. За більш детальною інформацією звернітся до Адміністратора.');
DEFINE('_LOGIN_INCORRECT','Некоректні ім\'я користувача та пароль. Спробуйте ще раз.');
DEFINE('_LOGIN_NOADMINS','Ви не можете ввійти в систему. Система не має Адміністраторів.');
DEFINE('_CMN_JAVASCRIPT','Увага! Для виконання операції java-script повинен підтримуватися Вашим браузером. Будь-ласка, впевніться, що java-script включений.');

DEFINE('_NEW_MESSAGE','Отримано нове приватне повідомлення');
DEFINE('_MESSAGE_FAILED','Користувач заблокував свою скриньку. Повідомлення не доставлено.');

DEFINE('_CMN_IFRAMES', 'Ця функція не буде працювати коректно.  На жаль, але Ваш браузер не підтримує Inline Frames');

DEFINE('_INSTALL_3PD_WARN','Увага! Інсталяція сторонніх розширень може привести до погіршення безпеки вашого сервера! Оновлення Joomla! не приведе до оновлення встановлених Вами сторонніх розширень.<br />За більш детальною інформацією щодо безпеки сайту звертайтесь на <a href="http://forum.joomla.org/index.php/board,267.0.html" target="_blank" style="color: blue; text-decoration: underline;">Joomla! Security Forum [eng]</a>.<br/>Обговорення українського перекладу та інших тем ведуться на <a href="http://www.joomla-ua.org/forum/" target="_blank" style="color: blue; text-decoration: underline;">форумі Joomla! Україна</a>.<br/>Завантажити найновішу версію мовного файла можете на сторінці <a href="http://www.joomla-ua.org/content/view/22/30/" target="_blank" style="color: blue; text-decoration: underline;">"Українська локалізація Joomla!"</a>');
DEFINE('_INSTALL_WARN','Для Вашої безпеки, будь-ласка, повністю знищіть папку Встановлення <i>/installation</i>, включно з усіма файлами та підпапками та оновіть цю сторінку.');
DEFINE('_TEMPLATE_WARN','<font color=\"red\"><b>Файл Шаблону Не Знайдено! Задіяно шаблон:</b></font>');
DEFINE('_NO_PARAMS','Цей елемент не має параметрів');
DEFINE('_HANDLER','Для цього типу не вказано обробник');

/** mambots */
DEFINE('_TOC_JUMPTO','Зміст статті');

/**  content */
DEFINE('_READ_MORE','Детальніше...');
DEFINE('_READ_MORE_REGISTER','Зайдіть як Користувач, щоб прочитати повністю...');
DEFINE('_MORE','Додаткові статті:');
DEFINE('_ON_NEW_CONTENT', "[%s] додав новий елемент з назвою [%s] у розділі [%s] та категорії [%s]." );
DEFINE('_SEL_CATEGORY','== Оберіть категорію ==');
DEFINE('_SEL_SECTION','== Оберіть розділ ==');
DEFINE('_SEL_AUTHOR','== Оберіть автора ==');
DEFINE('_SEL_POSITION','== Оберіть розташування ==');
DEFINE('_SEL_TYPE','== Оберіть тип ==');
DEFINE('_EMPTY_CATEGORY','Ця категорія порожня');
DEFINE('_EMPTY_BLOG','На цій сторінці зміст відсутній.');
DEFINE('_NOT_EXIST','<h1>Помилка 404!</h1><br />Такої сторінки не існує.');
DEFINE('_SUBMIT_BUTTON','Відправити');

/** classes/html/modules.php */
DEFINE('_BUTTON_VOTE','Добре');
DEFINE('_BUTTON_RESULTS','Результати');
DEFINE('_USERNAME','Користувач');
DEFINE('_LOST_PASSWORD','Забули пароль?');
DEFINE('_PASSWORD','Пароль');
DEFINE('_BUTTON_LOGIN','Вхід');
DEFINE('_BUTTON_LOGOUT','Вихід');
DEFINE('_NO_ACCOUNT','Ще не зареєстровані?');
DEFINE('_CREATE_ACCOUNT','Реєстрація');
DEFINE('_VOTE_POOR','Гірша');
DEFINE('_VOTE_BEST','Краща');
DEFINE('_USER_RATING','Рейтинг');
DEFINE('_RATE_BUTTON','Проголосувати');
DEFINE('_REMEMBER_ME','Запам\'ятати мене');

/** contact.php */
DEFINE('_ENQUIRY','Контакт');
DEFINE('_ENQUIRY_TEXT','Це повідомлення було відправлено з розділу Контакти сторінки %s від:');
DEFINE('_COPY_TEXT','Це копія повідомлення, яке було відправлено до %s, з %s ');
DEFINE('_COPY_SUBJECT','Копія від: ');
DEFINE('_THANK_MESSAGE','Дякуємо. Повідомлення успішно відправлено.');
DEFINE('_CLOAKING','Ця адреса електронної пошти приховується від різних спамерських та пошукових роботів. Щоб побачити її потрібно активувати java-script.');
DEFINE('_CONTACT_HEADER_NAME','Ім\'я');
DEFINE('_CONTACT_HEADER_POS','Розташування');
DEFINE('_CONTACT_HEADER_EMAIL','Електронна пошта');
DEFINE('_CONTACT_HEADER_PHONE','Телефон');
DEFINE('_CONTACT_HEADER_FAX','Факс');
DEFINE('_CONTACTS_DESC','Перелік Контактів цієї сторінки.');
DEFINE('_CONTACT_MORE_THAN','Ви не можете ввести більше, ніж одну електронну адресу.');

/** classes/html/contact.php */
DEFINE('_CONTACT_TITLE','Контакти');
DEFINE('_EMAIL_DESCRIPTION','Надішліть електронний лист, заповнивши контактну форму:');
DEFINE('_NAME_PROMPT',' Ваше ім\'я:');
DEFINE('_EMAIL_PROMPT',' Адреса Вашої електронної пошти:');
DEFINE('_MESSAGE_PROMPT',' Ваше повідомлення:');
DEFINE('_SEND_BUTTON','Надіслати');
DEFINE('_CONTACT_FORM_NC','Будь-ласка, заповніть форму повністю та вірно.');
DEFINE('_CONTACT_TELEPHONE','Телефон: ');
DEFINE('_CONTACT_MOBILE','Мобільний телефон: ');
DEFINE('_CONTACT_FAX','Факс: ');
DEFINE('_CONTACT_EMAIL','Електронна пошта: ');
DEFINE('_CONTACT_NAME','Ім\'я: ');
DEFINE('_CONTACT_POSITION','Розташування: ');
DEFINE('_CONTACT_ADDRESS','Адреса: ');
DEFINE('_CONTACT_MISC','Додаткова інформація: ');
DEFINE('_CONTACT_SEL','Виберіть контакти:');
DEFINE('_CONTACT_NONE','Немає жодної інформації.');
DEFINE('_CONTACT_ONE_EMAIL','Ви не можете вказати більше одніеї електронної адреси.');
DEFINE('_EMAIL_A_COPY','Надіслати копію повідомлення на Вашу власну адресу');
DEFINE('_CONTACT_DOWNLOAD_AS','Завантажити цю інформацію як');
DEFINE('_VCARD','електронну візитку');

/** pageNavigation */
DEFINE('_PN_LT','&lt;');
DEFINE('_PN_RT','&gt;');
DEFINE('_PN_PAGE','Сторінка');
DEFINE('_PN_OF','з');
DEFINE('_PN_START','Початок');
DEFINE('_PN_PREVIOUS','Попередня');
DEFINE('_PN_NEXT','Наступна');
DEFINE('_PN_END','Кінець');
DEFINE('_PN_DISPLAY_NR','Показано #');
DEFINE('_PN_RESULTS','Всього');

/** emailfriend */
DEFINE('_EMAIL_TITLE','Надіслати сторінку другові');
DEFINE('_EMAIL_FRIEND','Надіслати на електронну адресу посилання на сторінку.');
DEFINE('_EMAIL_FRIEND_ADDR',"Електронна адреса друга:");
DEFINE('_EMAIL_YOUR_NAME','Ваше ім\'я:');
DEFINE('_EMAIL_YOUR_MAIL','Ваша електронна адреса:');
DEFINE('_SUBJECT_PROMPT',' Тема листа:');
DEFINE('_BUTTON_SUBMIT_MAIL','Надіслати');
DEFINE('_BUTTON_CANCEL','Скасувати');
DEFINE('_EMAIL_ERR_NOINFO','Ви повинні вірно ввести свою адресу електронної пошти та адресу отримувача.');
DEFINE('_EMAIL_MSG',' Посилання на інформацію зі сторінки "%s" надіслав Вам %s ( %s ).

Ви можете переглянути її, скориставшись цією адресою:
%s');
DEFINE('_EMAIL_INFO','Лист надіслано від');
DEFINE('_EMAIL_SENT','Посилання на цю сторінку надіслано для');
DEFINE('_PROMPT_CLOSE','Закрити вікно');

/** classes/html/content.php */
DEFINE('_AUTHOR_BY', ' Автор');
DEFINE('_WRITTEN_BY', ' Написав');
DEFINE('_LAST_UPDATED', 'Останє оновлення');
DEFINE('_BACK','Назад');
DEFINE('_LEGEND','Історія');
DEFINE('_DATE','Дата');
DEFINE('_ORDER_DROPDOWN','Порядок');
DEFINE('_HEADER_TITLE','Назва');
DEFINE('_HEADER_AUTHOR','Автор');
DEFINE('_HEADER_SUBMITTED','Подано');
DEFINE('_HEADER_HITS','Переглядів');
DEFINE('_E_EDIT','Редагувати');
DEFINE('_E_ADD','Додати');
DEFINE('_E_WARNUSER','Будь-ласка, Збережіть або Скасуйте зміни');
DEFINE('_E_WARNTITLE','Матеріал повинен мати назву');
DEFINE('_E_WARNTEXT','Матеріал повинен мати вступний текст');
DEFINE('_E_WARNCAT','Будь-ласка, виберіть категорію');
DEFINE('_E_CONTENT','Матеріал');
DEFINE('_E_TITLE','Назва:');
DEFINE('_E_CATEGORY','Категорія:');
DEFINE('_E_INTRO','Вступ');
DEFINE('_E_MAIN','Основний текст');
DEFINE('_E_MOSIMAGE','Вставити тег {mosimage}');
DEFINE('_E_IMAGES','Зображення');
DEFINE('_E_GALLERY_IMAGES','Галерея зображень');
DEFINE('_E_CONTENT_IMAGES','Зображення до тексту');
DEFINE('_E_EDIT_IMAGE','Параметри зображення');
DEFINE('_E_NO_IMAGE','Зображення відсутнє');
DEFINE('_E_INSERT','Додати');
DEFINE('_E_UP','Догори');
DEFINE('_E_DOWN','Донизу');
DEFINE('_E_REMOVE','Знищити');
DEFINE('_E_SOURCE','Назва файлу:');
DEFINE('_E_ALIGN','Розташування:');
DEFINE('_E_ALT','Альтернативний текст:');
DEFINE('_E_BORDER','Рамка:');
DEFINE('_E_CAPTION','Заголовок');
DEFINE('_E_CAPTION_POSITION','Розташування Заголовка');
DEFINE('_E_CAPTION_ALIGN','Вирівнювання Заголовка');
DEFINE('_E_CAPTION_WIDTH','Ширина Заголовка');
DEFINE('_E_APPLY','Застосувати');
DEFINE('_E_PUBLISHING','Публікація');
DEFINE('_E_STATE','Стан:');
DEFINE('_E_AUTHOR_ALIAS','Псевдонім автора:');
DEFINE('_E_ACCESS_LEVEL','Права доступу:');
DEFINE('_E_ORDERING','Порядок сортування:');
DEFINE('_E_START_PUB','Дата початку публікації:');
DEFINE('_E_FINISH_PUB','Дата закінчення публікації:');
DEFINE('_E_SHOW_FP','Показувати на Головній Сторінці:');
DEFINE('_E_HIDE_TITLE','Не показувати заголовок:');
DEFINE('_E_METADATA','МЕТАДАНІ');
DEFINE('_E_M_DESC','Опис:');
DEFINE('_E_M_KEY','Ключові слова:');
DEFINE('_E_SUBJECT','Тема:');
DEFINE('_E_EXPIRES','Дійсне до:');
DEFINE('_E_VERSION','Версія:');
DEFINE('_E_ABOUT','Про');
DEFINE('_E_CREATED','Створено:');
DEFINE('_E_LAST_MOD','Оновлено:');
DEFINE('_E_HITS','Кількість переглядів:');
DEFINE('_E_SAVE','Зберегти');
DEFINE('_E_CANCEL','Скасувати');
DEFINE('_E_REGISTERED','Лише для зареєстрованих користувачів');
DEFINE('_E_ITEM_INFO','Інформація');
DEFINE('_E_ITEM_SAVED','Збережено успішно.');
DEFINE('_ITEM_PREVIOUS','&lt; Попередня');
DEFINE('_ITEM_NEXT','Наступна &gt;');
DEFINE('_KEY_NOT_FOUND','Ключ не знайдено');

/** content.php */
DEFINE('_SECTION_ARCHIVE_EMPTY','На даний момент Розділ не містить Архіву, будь-ласка, завітайте пізніше');
DEFINE('_CATEGORY_ARCHIVE_EMPTY','На даний момент Категорія не містить Архіву, будь-ласка, завітайте пізніше');
DEFINE('_HEADER_SECTION_ARCHIVE','Архів Розділу');
DEFINE('_HEADER_CATEGORY_ARCHIVE','Архів категорії');
DEFINE('_ARCHIVE_SEARCH_FAILURE','Для %s %s Архіву немає');	// values are month then year
DEFINE('_ARCHIVE_SEARCH_SUCCESS','Для %s %s існує Архів');	// values are month then year
DEFINE('_FILTER','Фільтр');
DEFINE('_ORDER_DROPDOWN_DA','Дата за зростанням');
DEFINE('_ORDER_DROPDOWN_DD','Дата за спаданням');
DEFINE('_ORDER_DROPDOWN_TA','Тема за зростанням');
DEFINE('_ORDER_DROPDOWN_TD','Тема за спаданням');
DEFINE('_ORDER_DROPDOWN_HA','Переглядів за зростанням');
DEFINE('_ORDER_DROPDOWN_HD','Переглядів за спаданням');
DEFINE('_ORDER_DROPDOWN_AUA','Автор за зростанням');
DEFINE('_ORDER_DROPDOWN_AUD','Автор за спаданням');
DEFINE('_ORDER_DROPDOWN_O','Сортовано');

/** poll.php */
DEFINE('_ALERT_ENABLED','Кукі (cookies) повинні бути включені!');
DEFINE('_ALREADY_VOTE','Ви вже приймали участь у цьому опитуванні!');
DEFINE('_NO_SELECTION','Ви не зробили свого вибору, будь-ласка, спробуйте ще раз');
DEFINE('_THANKS','Дякуємо за голосування!');
DEFINE('_SELECT_POLL','Оберіть опитування з переліку');

/** classes/html/poll.php */
DEFINE('_JAN','Січень');
DEFINE('_FEB','Лютий');
DEFINE('_MAR','Березень');
DEFINE('_APR','Квітень');
DEFINE('_MAY','Травень');
DEFINE('_JUN','Червень');
DEFINE('_JUL','Липень');
DEFINE('_AUG','Серпень');
DEFINE('_SEP','Вересень');
DEFINE('_OCT','Жовтень');
DEFINE('_NOV','Листопад');
DEFINE('_DEC','Грудень');
DEFINE('_POLL_TITLE','Опитування - Результати');
DEFINE('_SURVEY_TITLE','Назва опитування:');
DEFINE('_NUM_VOTERS','Кількість учасників');
DEFINE('_FIRST_VOTE','Перший голос');
DEFINE('_LAST_VOTE','Останній голос');
DEFINE('_SEL_POLL','Оберіть опитування:');
DEFINE('_NO_RESULTS','Про вибране опитування дані відсутні.');

/** registration.php */
DEFINE('_ERROR_PASS','Вибачте, але користувача не знайдено');
DEFINE('_NEWPASS_MSG','Адреса електронної пошти використовується користувачем $checkusername.\n'
.'Користувач сторінки $mosConfig_live_site здійснив запит на отримання нового паролю.\n\n'
.' Ваш новий пароль: $newpass\n\nЯкщо це не Ви ініціювали зміну паролю, то не звертайте увагу.'
.' Лише Ви можете бачити це повідомлення - більше ніхто. Якщо це помилка, тоді просто зайдіть'
.' на сайт використовуючи новий пароль, а потім змініть на новий, зручний для Вас.');
DEFINE('_NEWPASS_SUB','$_sitename :: Новий пароль для - $checkusername');
DEFINE('_NEWPASS_SENT','Новий пароль для користувача було створено та надіслано електронною поштою!');
DEFINE('_REGWARN_NAME','Будь-ласка, введіть Ваше справжнє ім\'я.');
DEFINE('_REGWARN_UNAME','Будь-ласка, введіть Ваше ім\`я користувача (Логін).');
DEFINE('_REGWARN_MAIL','Будь-ласка, введіть правильну адресу електронної пошти.');
DEFINE('_REGWARN_PASS','Будь-ласка, введіть вірно пароль. Пароль не повинен містити пробіли, його довжина повинна бути більше 6-ти символів та складатись лише з символів 0-9, a-z, A-Z');
DEFINE('_REGWARN_VPASS1','Будь-ласка, перевірьте пароль.');
DEFINE('_REGWARN_VPASS2','Пароль та його підтвердження не співпадають, будь-ласка, спробуйте ще раз.');
DEFINE('_REGWARN_INUSE','Це Ім`я Користувача вже використовується. Будь-ласка, виберіть інше.');
DEFINE('_REGWARN_EMAIL_INUSE', 'Ця адреса електронної пошти вже використовується. Якщо Ви забули свій Пароль натисніть "Забули пароль". На Вашу електронну адресу прийде лист з Вашим паролем.');
DEFINE('_SEND_SUB','Дані нового Користувача %s на %s');
DEFINE('_USEND_MSG_ACTIVATE', 'Доброго дня, %s!

Дякуємо за реєстрацію на сайті %s.

Ваш обліковий запис створено, проте його потрібно спочатку активувати.
Для активації натисніть на посилання: %s

Після активації Ви зможете зайти на сайт %s, використовуючи такі дані:
Користувач: %s
Пароль: %s

--------------------
На цей лист не потрібно відповідати, оскільки він створений автоматично.

З повагою, Адміністрація сайту!');
DEFINE('_USEND_MSG', "Доброго дня, %s!

Дякуємо за реєстрацію на %s.

Тепер Ви можете увійти в систему на %s, використовуючи Ім\'я Користувача та Пароль, які були вказані при реєстрації.

--------------------
На цей лист не потрібно відповідати, оскільки він створений автоматично.

З повагою, Адміністрація сайту!");
DEFINE('_USEND_MSG_NOPASS','Доброго дня, $name,\n\nВас додано до користувачів $mosConfig_live_site.\n'
.'Тепер Ви можете зайти на $mosConfig_live_site, використовуючи ім\'я користувача та пароль, які були вказані при реєстрації.\n\n'
.'--------------------\nНа цей лист не потрібно відповідати, оскільки він створений автоматично.\n\nЗ повагою, Адміністрація сайту!');
DEFINE('_ASEND_MSG','Доброго дня, %s,

На %s зареєструвався новий користувач.

Його дані:

Ім\'я - %s
Електронна пошта - %s
Користувач - %s

--------------------
На цей лист не потрібно відповідати, оскільки він створений автоматично.

З повагою, Адміністрація сайту!');
DEFINE('_REG_COMPLETE_NOPASS','<div class="componentheading">Реєстрацію завершено!</div><br />&nbsp;&nbsp;'
.'Тепер Ви можете зайти на сайт як користувач.<br />');
DEFINE('_REG_COMPLETE', '<div class="componentheading">Реєстрацію завершено!</div><br />Тепер можете зайти на сайт як користувач.');
DEFINE('_REG_COMPLETE_ACTIVATE', '<div class="componentheading">Реєстрацію завершено!</div><br />Ваш обліковий запис був створено і за вказаною адресою електронної пошти надіслано адресу активації. Для завершення реєстрації перейдіть по посиланню, яке знайдете у листі.');
DEFINE('_REG_ACTIVATE_COMPLETE', '<div class="componentheading">Реєстрацію завершено!</div><br />Ваш обліковий запис було успішно активовано. Тепер Ви можете увійти в систему як користувач, використовуючи дані, вказані при реєстрації.');
DEFINE('_REG_ACTIVATE_NOT_FOUND', '<div class="componentheading">Невірне посилання для активації!</div><br />Такого облікового запису не існує, або він вже активований.');
DEFINE('_REG_ACTIVATE_FAILURE', '<div class="componentheading">Збій активації!</div><br />Сиситема не змогла активувати ваш обліковий запис. Будь-ласка, зв\'яжіться із адміністратором сайту.');

/** classes/html/registration.php */
DEFINE('_PROMPT_PASSWORD','Забули пароль?');
DEFINE('_NEW_PASS_DESC','Будь-ласка, введіть Ваше Ім\'я Користувача та адресу електронної пошти, потім натисніть кнопку [Надіслати пароль].<br /><br />'
.'Через деякий час на Вашу електронну адресу надійде лист із новим Паролем. Використовуйте Ваш новий пароль для входу на сайт.<br /><br />');
DEFINE('_PROMPT_UNAME','Користувач:');
DEFINE('_PROMPT_EMAIL','Адреса електронної пошти:');
DEFINE('_BUTTON_SEND_PASS','Надіслати пароль');
DEFINE('_REGISTER_TITLE','Реєстрація');
DEFINE('_REGISTER_NAME','Справжнє Ім\'я:');
DEFINE('_REGISTER_UNAME','Користувач (Логін):');
DEFINE('_REGISTER_EMAIL','Електронна пошта:');
DEFINE('_REGISTER_PASS','Пароль:');
DEFINE('_REGISTER_VPASS','Підтвердження паролю:');
DEFINE('_REGISTER_REQUIRED','Поля відмічені зірочкою (*) обов\'язкові для заповнення!!!');
DEFINE('_BUTTON_SEND_REG','Надіслати дані');
DEFINE('_SENDING_PASSWORD','Ваш пароль буде відправлено на зазначену вище електронну адресу.<br />Після того як Ви отримаєте'
.' новий пароль Ви зможете зайти на сторінку та змінити його в будь-який зручний для Вас час.');

/** classes/html/search.php */
DEFINE('_SEARCH_TITLE','Пошук по сайту');
DEFINE('_PROMPT_KEYWORD','Пошук за ключовою фразою');
DEFINE('_SEARCH_MATCHES','надав %d результат.');
DEFINE('_CONCLUSION','Всього знайдено <b>$totalRows</b> документів. <br />Шукати <b>$searchword</b> за допомогою ');
DEFINE('_NOKEYWORD','Нічого не знайдено!');
DEFINE('_IGNOREKEYWORD','Під час пошуку були пропущено прийменники');
DEFINE('_SEARCH_ANYWORDS','Будь-яке слово');
DEFINE('_SEARCH_ALLWORDS','Всі слова');
DEFINE('_SEARCH_PHRASE','Точне співпадання');
DEFINE('_SEARCH_NEWEST','Нові на початку');
DEFINE('_SEARCH_OLDEST','Старі на початку');
DEFINE('_SEARCH_POPULAR','Найбільш популярні');
DEFINE('_SEARCH_ALPHABETICAL','За абеткою');
DEFINE('_SEARCH_CATEGORY','Розділ/Категорія');
DEFINE('_SEARCH_MESSAGE','Фраза для пошуку повинна містити мінімум <b>3</b> і максимум <b>20</b> символів');
DEFINE('_SEARCH_ARCHIVED','В архіві');
DEFINE('_SEARCH_CATBLOG','У блозі категорій');
DEFINE('_SEARCH_CATLIST','У таблиці категорій');
DEFINE('_SEARCH_NEWSFEEDS','У стрічках новин');
DEFINE('_SEARCH_SECLIST','У таблиці розділів');
DEFINE('_SEARCH_SECBLOG','У блозі розділу');

/** templates/*.php */
DEFINE('_ISO','charset=windows-1251');
DEFINE('_DATE_FORMAT','d.m.Y');  //Uses PHP's DATE Command Format - Depreciated
/**
* Modify this line to reflect how you want the date to appear in your site
*
*e.g. DEFINE("_DATE_FORMAT_LC","%A, %d %B %Y %H:%M"); //Uses PHP's strftime Command Format
*/
DEFINE('_DATE_FORMAT_LC',"%d.%m.%Y"); //Uses PHP's strftime Command Format
DEFINE('_DATE_FORMAT_LC2',"%d.%m.%Y, %H:%M");
DEFINE('_SEARCH_BOX','Пошук...');
DEFINE('_NEWSFLASH_BOX','Оголошення!');
DEFINE('_MAINMENU_BOX','Навігація');

/** classes/html/usermenu.php */
DEFINE('_UMENU_TITLE','Меню користувача');
DEFINE('_HI','Привіт, ');

/** user.php */
DEFINE('_SAVE_ERR','Будь-ласка? заповніть всі поля.');
DEFINE('_THANK_SUB','Дякуємо за Вашу статтю. Вона буде переглянута \n Адміністратором, перед публікацією на сайті.');
DEFINE('_THANK_SUB_PUB','Дякуємо за Вашу статтю.');
DEFINE('_UP_SIZE','Ви не можете завантажувати файли разміром більше, ніж <b>15Кб</b>.');
DEFINE('_UP_EXISTS','Зображення з назвою $userfile_name вже існує. Будь-ласка, змініть назву файлу та спробуйте ще раз.');
DEFINE('_UP_COPY_FAIL','Помилка при копіюванні');
DEFINE('_UP_TYPE_WARN','Ви можете завантажувати лише \".gif\" або \".jpg\" зображення.');
DEFINE('_MAIL_SUB','Нова стаття від користувача');
DEFINE('_MAIL_MSG','Привіт, $adminName,\n\n\nКористувач [ $author ]запропонував нову статтю у розділ $type:\n'
.' для $mosConfig_live_site.\n\n\n\n'
.'Будь-ласка, зайдіть в Адміністративну частину $mosConfig_live_site/administrator для перегляду та схвалення.\n\n'
.'Непотрібно відповідати на цей лист, оскільки його було створено автоматично.\n');
DEFINE('_PASS_VERR1','Якщо Ви бажаєте змінити пароль, будь-ласка, введіть його ще раз для підтвердження.');
DEFINE('_PASS_VERR2','Якщо Ви вирішили змінити пароль, будь-ласка зверніть увагу на те, що пароль та його підтвердження повинні співпадати.');
DEFINE('_UNAME_INUSE','Таке ім\'я Користувача вже використовується.');
DEFINE('_UPDATE','Поновити');
DEFINE('_USER_DETAILS_SAVE','Ваші дані збережено.');
DEFINE('_USER_LOGIN','Рєєстрація');

/** components/com_user */
DEFINE('_EDIT_TITLE','Особисті дані');
DEFINE('_YOUR_NAME','Справжнє Ім\'я:');
DEFINE('_EMAIL','Адреса електронної пошти:');
DEFINE('_UNAME','Користувач:');
DEFINE('_PASS','Пароль:');
DEFINE('_VPASS','Підтвердження паролю:');
DEFINE('_SUBMIT_SUCCESS','Ваші дані оновлено!');
DEFINE('_SUBMIT_SUCCESS_DESC','Ваша інформація успішно відправлена Адміністратору. Після перегляду та одобрення Ваша стаття буде опублікована на сайті.');
DEFINE('_WELCOME','Ласкаво просимо!');
DEFINE('_WELCOME_DESC','Ласкаво просимо в розділ Користувача нашого сайту');
DEFINE('_CONF_CHECKED_IN','Всі \"очікувані\" елементи тепер мають статус \"перевірені\"');
DEFINE('_CHECK_TABLE','таблиця перевірок');
DEFINE('_CHECKED_IN','Перевірено ');
DEFINE('_CHECKED_IN_ITEMS',' матеріалів');
DEFINE('_PASS_MATCH','Паролі не співпадають');

/** components/com_banners */
DEFINE('_BNR_CLIENT_NAME','Виберіть Ім`я для клієнта.');
DEFINE('_BNR_CONTACT','Виберіть Контакт для клієнта.');
DEFINE('_BNR_VALID_EMAIL','Клієнт повинен мати коректну адресу ел. пошти.');
DEFINE('_BNR_CLIENT','Оберіть клієнта');
DEFINE('_BNR_NAME','Оберіть назву банера.');
DEFINE('_BNR_IMAGE','Оберіть малюнок банера.');
DEFINE('_BNR_URL','Ви повинні вибрати Посилання/Код для цього банера.');

/** components/com_login */
DEFINE('_ALREADY_LOGIN','Ви вже авторизовані!');
DEFINE('_LOGOUT','Натисніть сюди для завершення роботи');
DEFINE('_LOGIN_TEXT','Використовуйте Ім\'я Користувача та Пароль для доступу на сайт');//Use the login and password fields opposite to gain full access
DEFINE('_LOGIN_SUCCESS','Вас успішно авторизовано');
DEFINE('_LOGOUT_SUCCESS','Ви успішно вийшли з системи');
DEFINE('_LOGIN_DESCRIPTION','Щоб отримати доступ до приватних розділів, будь-ласка, авторизуйтесь.');
DEFINE('_LOGOUT_DESCRIPTION','На даний момент Ви авторизовані і можете переглядати приватні розділи сайту.');

/** components/com_weblinks */
DEFINE('_WEBLINKS_TITLE','Посилання');
DEFINE('_WEBLINKS_DESC','В даному розділі зібрані найбільш цікаві та корисні посилання.'
.'<br />Виберіть з переліку один з розділів, далі оберіть одне з посилань.');
DEFINE('_HEADER_TITLE_WEBLINKS','Посилання');
DEFINE('_SECTION','Розділ:');
DEFINE('_SUBMIT_LINK','Додати нове посилання');
DEFINE('_URL','URL:');
DEFINE('_URL_DESC','Опис:');
DEFINE('_NAME','Назва:');
DEFINE('_WEBLINK_EXIST','Посилання з такою назвою вже існує. Спробуйте ще раз.');
DEFINE('_WEBLINK_TITLE','Посилання необхідно назвати.');

/** components/com_newfeeds */
DEFINE('_FEED_NAME','Назва стрічки новин');
DEFINE('_FEED_ARTICLES','Кількістьсть повідомлень');
DEFINE('_FEED_LINK','Джерело стрічки новин');

/** whos_online.php */
DEFINE('_WE_HAVE', 'Зараз на сайті: ');
DEFINE('_AND', ' та ');
DEFINE('_GUEST_COUNT','%s гість');
DEFINE('_GUESTS_COUNT','%s гостей');
DEFINE('_MEMBER_COUNT','%s користувач');
DEFINE('_MEMBERS_COUNT','%s користувачів');
DEFINE('_ONLINE','');
DEFINE('_NONE','Немає нікого');

/** modules/mod_banners */
DEFINE('_BANNER_ALT','Реклама');

/** modules/mod_random_image */
DEFINE('_NO_IMAGES','Зображення відсутні');

/** modules/mod_stats.php */
DEFINE('_TIME_STAT','Час');
DEFINE('_MEMBERS_STAT','Користувачів');
DEFINE('_HITS_STAT','Переглядів');
DEFINE('_NEWS_STAT','Новин');
DEFINE('_LINKS_STAT','Посилань');
DEFINE('_VISITORS','Відвідувачів');

/** /adminstrator/components/com_menus/admin.menus.html.php */
DEFINE('_MAINMENU_HOME','* Перший опублікований елемент в цьому меню є "Домашньою сторінкою" для сайту *');
DEFINE('_MAINMENU_DEL','* Ви не можете "Видалити" це Меню, оскільки воно необхідне для нормальної роботи Joomla! *');
DEFINE('_MENU_GROUP','* Деякі з "Типів Меню" присутні в більше, ніж одній групі *');

/** administrators/components/com_users */
DEFINE('_NEW_USER_MESSAGE_SUBJECT', 'Новий користувач' );
DEFINE('_NEW_USER_MESSAGE', 'Доброго дня, %s!


Адміністратор додав Вас як користувача на %s.

Для входу на %s використовуйте такі параметри:

Ім\'я користувача - %s
Пароль            - %s


--------------------
На цей лист не потрібно відповідати, оскільки він створений автоматично.

З повагою, Адміністрація сайту!');

/** administrators/components/com_massmail */
DEFINE('_MASSMAIL_MESSAGE', "Це лист від '%s'

Повідомлення:
" );

/** includes/pdf.php */
DEFINE('_PDF_GENERATED','Згенеровано:');
DEFINE('_PDF_POWERED','Працює на Joomla!');

/** Імена заголовків колонок [Joomla 1.0.x RUS Paranoia]**/
DEFINE('_LEFT_COLUMN_NAME','Навігація');
DEFINE('_RIGHT_COLUMN_NAME','Статистика');
?>