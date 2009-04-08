<?php
/**
* @package Joostina
* @copyright Авторлық құқықтың (C) 2008 Joostina team. Барлық құқықтар қорғалған.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, немесе help/license.php
* Joostina! - Таратылатын азат бағдарламалық қамтамасыз ету GNU/GPL лицензия шарттарымен.
* Хабар алуына арналған туралы қолданылатындарды кеңейтулерде және ескерпелердің авторлық құқық туралы, файлды қарайсыздар help/copyright.php.
* Перевод на казахский язык Графов Артем aka doctorgrif - www.hospsurg.ru
* Обо всех неточностях в переводе соообщайте по e-mail Artem.Grafov@gmail.com,
* Обязательно указывайте строку (полностью) с неточным переводом и ваш вариант перевода
*/

/* Түзу рұқсатты қамайды */
defined('_VALID_MOS') or die('Кіру шектелген');
global $mosConfig_form_date,$mosConfig_form_date_full;

/* Сайтқа беті табылған емес */
define('_404','Талап қылынған парақ тақылман.');
define('_404_RTS','Сайтқа оралу.');
define('_SYSERR1','MySQL Сүйеулер жоқ.');
define('_SYSERR2','Тап осы база серверіне қосылу.');
define('_SYSERR3','Тап осы базаға қосылу.');

/* жалпы */
DEFINE('_LANGUAGE','kz');
DEFINE('_NOT_AUTH','Кешіріңіз, бұл парақты қарауға сіздің құқығыңыз жеткіліксыз.');
DEFINE('_DO_LOGIN','Сіз әзіңіз жөнінде мәлімет беруге не, тіркеуден өтуге тиістісіз.');
DEFINE('_VALID_AZ09',"Өтініш %s. Дұрыс жазылғандығын, тексеріңіз. Атау әріптері арасында бос торкөз болмауы,  тиіс тек қана 0-9, a-z, A-Z белгілер, және %d дан ұзын белгілер болмауы тиіс.");
DEFINE('_VALID_AZ09_USER',"Өтініш, %s - ті дұрыс енгізіңшз. Тиіс тек қана 0-9, a-z, A-Z белгілер, және %d дан ұзын белгілер болмауы тиіс.");
DEFINE('_CMN_YES','Иә');
DEFINE('_CMN_NO','Жоқ');
DEFINE('_CMN_SHOW','Көрсету');
DEFINE('_CMN_HIDE','Жасыру');
DEFINE('_CMN_NAME','Атау');
DEFINE('_CMN_DESCRIPTION','Мінездеме');
DEFINE('_CMN_SAVE','Сақтау');
DEFINE('_CMN_APPLY','Қолдану');
DEFINE('_CMN_CANCEL','Бекер қылу');
DEFINE('_CMN_PRINT','Қағазға басу');
DEFINE('_CMN_EMAIL','E-mail');
DEFINE('_ICON_SEP','|');
DEFINE('_CMN_PARENT','Әке-шеше');
DEFINE('_CMN_ORDERING','Сұрыптау');
DEFINE('_CMN_ACCESS','Рұқсат деңгейі');
DEFINE('_CMN_SELECT','Таңдау');
DEFINE('_CMN_NEXT','Келесі');
DEFINE('_CMN_NEXT_ARROW',"&nbsp;&raquo;");
DEFINE('_CMN_PREV','Алдындағы');
DEFINE('_CMN_PREV_ARROW',"&laquo;&nbsp;");
DEFINE('_CMN_SORT_NONE','Сорттаусыз');
DEFINE('_CMN_SORT_ASC','Өсумен');
DEFINE('_CMN_SORT_DESC','Кемумен');
DEFINE('_CMN_NEW','Создать');
DEFINE('_CMN_NONE','Жоқ');
DEFINE('_CMN_LEFT','Сол жағында');
DEFINE('_CMN_RIGHT','Оңңан');
DEFINE('_CMN_CENTER','Орталықпен');
DEFINE('_CMN_ARCHIVE','Архивқа қосу');
DEFINE('_CMN_UNARCHIVE','Архивтан шығару');
DEFINE('_CMN_TOP','Үстінде');
DEFINE('_CMN_BOTTOM','Төменде');
DEFINE('_CMN_PUBLISHED','Жарияланған');
DEFINE('_CMN_UNPUBLISHED','Жарияланған емес');
DEFINE('_CMN_EDIT_HTML','HTML Редакциялау');
DEFINE('_CMN_EDIT_CSS','CSS Редакциялау');
DEFINE('_CMN_DELETE','Қашықтату');
DEFINE('_CMN_FOLDER','Каталог');
DEFINE('_CMN_SUBFOLDER','Подкаталог');
DEFINE('_CMN_OPTIONAL','Міндетті емес');
DEFINE('_CMN_REQUIRED','Міндетті');
DEFINE('_CMN_CONTINUE','Жалғастыру');
DEFINE('_STATIC_CONTENT','Статикалық ұсталушы.');
DEFINE('_CMN_NEW_ITEM_LAST','Үндемеумен жаңа объектілер тізім соңысына қосылған болады. Орналастыру реті объекті сақтауынан кейін тек қана мүмкін өзгертілген.');
DEFINE('_CMN_NEW_ITEM_FIRST','Үндемеумен жаңа объектілер тізім басына қосылған болады. Орналастыру реті объекті сақтауынан кейін тек қана мүмкін өзгертілген.');
DEFINE('_LOGIN_INCOMPLETE','Өтінем, пайдаланушы аты және пароль далаларды толтырыңыздар.');
DEFINE('_LOGIN_BLOCKED','Кешіріңіздер, сіздердің есептік жазуыңыз қоршалған. Үшін көбірек хабармен толықтың сайта әкіміне бұрылаcыздар.');
DEFINE('_LOGIN_INCORRECT','Пайдаланушы атысы (логин) немесе пароль. Бір тағы байқап көріңіздер.');
DEFINE('_LOGIN_NOADMINS','Кешіріңіздер, сіздер сайтеге әкімдер тіркелген емес сайт. Кіре алмаcыздар.');
DEFINE('_CMN_JAVASCRIPT','Назар! Тап осы операция орындалуына арналған, сіздердің браузереңізде Java-script сүйеуі тиісті қосылған болу.');
DEFINE('_NEW_MESSAGE','Сіздерге жаңа жеке хабарлау келді.');
DEFINE('_MESSAGE_FAILED','Пайдаланушы өз пошталық жәшікті қоршады. Хабарлау жеткізілген емес.');
DEFINE('_CMN_IFRAMES','Мынау дөрекі бет бейнеленген болады. Фреймымен ішіне салынған сіздердің браузеріңіз сүйемейді  (IFrame).');
DEFINE('_INSTALL_3PD_WARN','Ескерту: Joomla! Жаңартуы жанында шеттегі кеңейтулердің құруы сіздердің сайта. Қауіпсіздігін бұза алады! Шеттегі кеңейтулер жаңармайды.<br />Қосымша хабар алуына арналған сайтамен өз қорғаныш өлшемдері туралы және сервердің, өтінем, барып шығыңыздар <a href="http://forum.joomla.org/index.php/board,267.0.html" target="_blank" style="color: blue; text-decoration: underline;">Форум безопасности Joomla!</a>.');
DEFINE('_INSTALL_WARN','Қауіпсіздік түсініктерімен, өтінем, каталогті қашықтатыңыздар <strong>installation</strong> сіздердің серверіңізден және бетті жаңартыңыздар.');
DEFINE('_TEMPLATE_WARN','<font color=\"red\"><strong>Үлгі файлы табылған емес:</strong></font><br />басқарулары сайт панельге кіріңіздер және жаңа үлгіні таңдаңыздар.');
DEFINE('_NO_PARAMS','Объекті қалпына келтірілетін параметрлерді асырамайды.');
DEFINE('_HANDLER','Тап осы үлгіге арналған өңдеуші жоқ болады.');

/* мамботы */
DEFINE('_TOC_JUMPTO','Мазмұн');

/* Ұсталушы */
DEFINE('_READ_MORE','Басу жанында...');
DEFINE('_READ_MORE_REGISTER','Тіркелген пайдаланушылардың артынан тек қана...');
DEFINE('_MORE','Онан әрі...');
DEFINE('_ON_NEW_CONTENT',"Пайдаланушы [%s] жаңа объекті қосты [%s]. Бөлім:[%s]. Категория:[%s]");
DEFINE('_SEL_CATEGORY','- Категорияны таңдаңыздар -');
DEFINE('_SEL_SECTION','- Бөлімді таңдаңыздар -');
DEFINE('_SEL_AUTHOR','- Авторды таңдаңыздар -');
DEFINE('_SEL_POSITION','- Позицияны таңдаңыздар -');
DEFINE('_SEL_TYPE','- Үлгіні таңдаңыздар -');
DEFINE('_EMPTY_CATEGORY','Тап осы категория объектілерді асырамайды.');
DEFINE('_EMPTY_BLOG','Елестетуге арналған объектілерді жоқ!');
DEFINE('_NOT_EXIST','Кешіріңіздер, бет табылған емес.<br />Өтінем, сайта негізгі бетіне қайтыңыздар.');
DEFINE('_SUBMIT_BUTTON','Жіберу');

/* classes/html/modules.php */
DEFINE('_BUTTON_VOTE','Дауыс беру');
DEFINE('_BUTTON_RESULTS','Нәтижелер');
DEFINE('_USERNAME','Пайдаланушы');
DEFINE('_LOST_PASSWORD','Ұмыттыныз ба?');
DEFINE('_PASSWORD','Пароль');
DEFINE('_BUTTON_LOGIN','Кіру');
DEFINE('_BUTTON_LOGOUT','Шығу');
DEFINE('_NO_ACCOUNT','Тағы тіркелген емес?');
DEFINE('_CREATE_ACCOUNT','Тіркеу');
DEFINE('_VOTE_POOR','Жаманырақ');
DEFINE('_VOTE_BEST','Жақсы');
DEFINE('_USER_RATING','Рейтингі ');
DEFINE('_RATE_BUTTON','Бағалау');
DEFINE('_REMEMBER_ME','Есте қалу');

/* contact.php */
DEFINE('_ENQUIRY','Контакті');
DEFINE('_ENQUIRY_TEXT','Мынау хабарлау сайтамен жүріп кеткен %s. Хабарлау авторы:');
DEFINE('_COPY_TEXT','Мынау хабарлау көшірмесі, сіздер жібердіңіздер үшін %s сайтамен %s.');
DEFINE('_COPY_SUBJECT','Көшірме:');
DEFINE('_THANK_MESSAGE','Рахмет! Хабарлау табысты жүріп кеткен.');
DEFINE('_CLOAKING','Мынау e-mail спам-боттардан қорғалған. Сіздердің браузереңізде оның қарауына арналған Java-script сүйеуі тиісті қосылған болу.');
DEFINE('_CONTACT_HEADER_NAME','Аты');
DEFINE('_CONTACT_HEADER_POS','Жай');
DEFINE('_CONTACT_HEADER_EMAIL','E-mail');
DEFINE('_CONTACT_HEADER_PHONE','Телефон');
DEFINE('_CONTACT_HEADER_FAX','Факс');
DEFINE('_CONTACTS_DESC','Мына сайта контактілерінің тізімі.');
DEFINE('_CONTACT_MORE_THAN','Сіздер e-mail көбірек бір мекенжайының енгізе алмаcыздар.');

/* classes/html/contact.php */
DEFINE('_CONTACT_TITLE','Кері байланыс');
DEFINE('_EMAIL_DESCRIPTION','Пайдаланушыға e-mail жіберу:');
DEFINE('_NAME_PROMPT','Сіздердің атыңыз енгізіңіздер:');
DEFINE('_EMAIL_PROMPT','Сіздердің e-mail енгізіңіздер:');
DEFINE('_MESSAGE_PROMPT','Жабарлау мәтінін енгізіңіздер:');
DEFINE('_SEND_BUTTON','Жіберу');
DEFINE('_CONTACT_FORM_NC','Өтінем, толық және дұрыс түрді толтырыңыздар.');
DEFINE('_CONTACT_TELEPHONE','Телефон:');
DEFINE('_CONTACT_MOBILE','Мобиль:');
DEFINE('_CONTACT_FAX','Факс:');
DEFINE('_CONTACT_EMAIL','E-mail:');
DEFINE('_CONTACT_NAME','Аты: ');
DEFINE('_CONTACT_POSITION','Лауазым:');
DEFINE('_CONTACT_ADDRESS','Мекенжай:');
DEFINE('_CONTACT_MISC','Қосымша хабар:');
DEFINE('_CONTACT_SEL','Алушыны таңдаңыздар:');
DEFINE('_CONTACT_NONE','Бөлшектің мынаның түйіскен жазулар жоқ болады.');
DEFINE('_CONTACT_ONE_EMAIL','e-mail көбірек бір мекенжайының болмайды енгізу.');
DEFINE('_EMAIL_A_COPY','Өзіне меншікті мекенжайға хабарлау көшірмесін жіберу.');
DEFINE('_CONTACT_DOWNLOAD_AS','Форматта хабарды секіру.');
DEFINE('_VCARD','VCard');

/* pageNavigation */
DEFINE('_PN_LT','&lt;');
DEFINE('_PN_RT','&gt;');
DEFINE('_PN_PAGE','Бет');
DEFINE('_PN_OF','А');
DEFINE('_PN_START','[Бірінші]');
DEFINE('_PN_PREVIOUS','Алдындағы');
DEFINE('_PN_NEXT','Келесі');
DEFINE('_PN_END','[Соңғы]');
DEFINE('_PN_DISPLAY_NR','Суреттеу');
DEFINE('_PN_RESULTS','Нәтижелер');

/* Хат досқа */
DEFINE('_EMAIL_TITLE','Досқа e-mail жіберу');
DEFINE('_EMAIL_FRIEND','E-mail бет сілтемесін жіберу:');
DEFINE('_EMAIL_FRIEND_ADDR','Дос E-Mail:');
DEFINE('_EMAIL_YOUR_NAME','Сіздердің атыңыз:');
DEFINE('_EMAIL_YOUR_MAIL','Сіздердің e-mail:');
DEFINE('_SUBJECT_PROMPT','Хабарлау тақырыбы:');
DEFINE('_BUTTON_SUBMIT_MAIL','Жіберу');
DEFINE('_BUTTON_CANCEL','Бекер қылу');
DEFINE('_EMAIL_ERR_NOINFO','Сіздер e-mail өз енгізуге дұрыс тиісті және мына хат алушы e-mail.');
DEFINE('_EMAIL_MSG','Аман-сау болыңыздар! Сайта бетіне сілтеме келесі "%s" сіздерге жіберді %s (%s). Сіздер көріп шығуға істей алады её мына сілтемемен :%s');
DEFINE('_EMAIL_INFO','Хат жіберді');
DEFINE('_EMAIL_SENT','Мына бетке сілтеме жүріп кеткен үшін');
DEFINE('_PROMPT_CLOSE','Терезе жабу');

/* classes/html/content.php */
DEFINE('_AUTHOR_BY',' Автор');
DEFINE('_WRITTEN_BY',' Автор');
DEFINE('_LAST_UPDATED','Соңғы жаңарту');
DEFINE('_BACK','Қайту');
DEFINE('_LEGEND','Тарих');
DEFINE('_DATE','Дата');
DEFINE('_ORDER_DROPDOWN','Рет');
DEFINE('_HEADER_TITLE','Тақырыбы');
DEFINE('_HEADER_AUTHOR','Автор');
DEFINE('_HEADER_SUBMITTED','Жазылған');
DEFINE('_HEADER_HITS','Қараулардың');
DEFINE('_E_EDIT','Редакциялау');
DEFINE('_E_ADD','Қосу');
DEFINE('_E_WARNUSER','Өтінем, бүркеншектегі шегені басыңыздар "Бекер қылу" немесе "Сақтау", мына бетті тастау үшін.');
DEFINE('_E_WARNTITLE','Ұсталушы тақырыбы тиісті болу');
DEFINE('_E_WARNTEXT','Ұсталушы кіріспе мәтін тиісті болу');
DEFINE('_E_WARNCAT','Өтінем, Категорияны таңдаңыздар');
DEFINE('_E_CONTENT','Ұсталушы');
DEFINE('_E_TITLE','Тақырыбы:');
DEFINE('_E_CATEGORY','Категория');
DEFINE('_E_INTRO','Кіріспе мәтін');
DEFINE('_E_MAIN','Ең басты мәтін');
DEFINE('_E_MOSIMAGE','Салу тег {mosimage}');
DEFINE('_E_IMAGES','Бейнелеудің');
DEFINE('_E_GALLERY_IMAGES','Бейнелеулердің галереясы');
DEFINE('_E_CONTENT_IMAGES','Мәтінге бейнелеудің');
DEFINE('_E_EDIT_IMAGE','Бейнелеу параметрлері');
DEFINE('_E_NO_IMAGE','Бейнелеусіз');
DEFINE('_E_INSERT','Салу');
define('_e_up','Жоғарырақ');
DEFINE('_E_DOWN','Төмен');
DEFINE('_E_REMOVE','Қашықтату');
DEFINE('_E_SOURCE','Файл аты:');
DEFINE('_E_ALIGN','Орналастыру:');
DEFINE('_E_ALT','Баламалық мәтін:');
DEFINE('_E_BORDER','Рамка:');
DEFINE('_E_CAPTION','Тақырыбы');
DEFINE('_E_CAPTION_POSITION','Қол қою жайы');
DEFINE('_E_CAPTION_ALIGN','Қол қою тегістеуі');
DEFINE('_E_CAPTION_WIDTH','Қол қою ені');
DEFINE('_E_APPLY','Қолдану');
DEFINE('_E_PUBLISHING','Жариялау');
DEFINE('_E_STATE','Күй-жағдай:');
DEFINE('_E_AUTHOR_ALIAS','Автор бүркеншік аты:');
DEFINE('_E_ACCESS_LEVEL','Рұқсат деңгейі:');
DEFINE('_E_ORDERING','Рет:');
DEFINE('_E_START_PUB','Жариялау бас датасы:');
DEFINE('_E_FINISH_PUB','Жариялау аяғы датасы:');
DEFINE('_E_SHOW_FP','Негізгі бетте көрсету:');
DEFINE('_E_HIDE_TITLE','Тақырыбы жасыру:');
DEFINE('_E_METADATA','Таңба-тэги');
DEFINE('_E_M_DESC','Суреттеу:');
DEFINE('_E_M_KEY','Маңызды сөздің:');
DEFINE('_E_SUBJECT','Тақырып:');
DEFINE('_E_EXPIRES','Өту датасы:');
DEFINE('_E_VERSION','Болжама');
DEFINE('_E_ABOUT','Объекті туралы');
DEFINE('_E_CREATED','Жасау датасы');
DEFINE('_E_LAST_MOD','Соңғы өзгерту:');
DEFINE('_E_HITS','Қараулардың саны:');
DEFINE('_E_SAVE','Сақтау');
DEFINE('_E_CANCEL','Бекер қылу');
DEFINE('_E_REGISTERED','Тіркелген пайдаланушылардың артынан тек қана');
DEFINE('_E_ITEM_INFO','Хабар');
DEFINE('_E_ITEM_SAVED','Табысты сақталған!');
DEFINE('_ITEM_PREVIOUS','&laquo; ');
DEFINE('_ITEM_NEXT','&raquo;');
DEFINE('_KEY_NOT_FOUND','Кілт табылған емес');

/** content.php*/
DEFINE('_SECTION_ARCHIVE_EMPTY','Архив мына бөлімінде объектілерді қазір жоқ. Өтінем, кешірек кіріңіздер.');
DEFINE('_CATEGORY_ARCHIVE_EMPTY','Архив мына категориясында объектілерді қазір жоқ. Өтінем, кешірек кіріңіздер.');
DEFINE('_HEADER_SECTION_ARCHIVE','Бөлімдердің архивы');
DEFINE('_HEADER_CATEGORY_ARCHIVE','категориялардың архивы');
DEFINE('_ARCHIVE_SEARCH_FAILURE','Архивтік жазуларды табылған емес үшін %s %s'); // ай мағыналары, ал жыл содан соң
DEFINE('_ARCHIVE_SEARCH_SUCCESS','Архивтік жазуларды табылған үшін  %s %s'); // ай мағыналары, ал жыл содан соң
DEFINE('_FILTER','Фильтр');
DEFINE('_ORDER_DROPDOWN_DA','Дата (Өсумен)');
DEFINE('_ORDER_DROPDOWN_DD','Дата (Кемумен)');
DEFINE('_ORDER_DROPDOWN_TA',' Ат (Өсумен)');
DEFINE('_ORDER_DROPDOWN_TD',' Ат (Кемумен)');
DEFINE('_ORDER_DROPDOWN_HA',' Қараулар (Өсумен)');
DEFINE('_ORDER_DROPDOWN_HD',' Қараулар (Кемумен)');
DEFINE('_ORDER_DROPDOWN_AUA','Автор (Өсумен)');
DEFINE('_ORDER_DROPDOWN_AUD','Автор (Кемумен)');
DEFINE('_ORDER_DROPDOWN_O','Ретпен');

/** poll.php*/
DEFINE('_ALERT_ENABLED','Cookies тиісті рұқсат етілген болу!');
DEFINE('_ALREADY_VOTE','Ссіздер мына сұрақта дауыс бердіңіздер!');
DEFINE('_NO_SELECTION','Ссіздер өз таңдауыңызды істемедіңіздер. Өтінем, бір тағы байқап көріңіздер.');
DEFINE('_THANKS','Рахмет дауысқа салуда сіздердің қатысуыңыздың артынан!');
DEFINE('_SELECT_POLL','Тізімнен сұрақты таңдаңыздар');

/** classes/html/poll.php*/
DEFINE('_JAN','Қаңтар');
DEFINE('_FEB','Ақпан');
DEFINE('_MAR','Наурыз');
DEFINE('_APR','Сәуір');
DEFINE('_MAY','Мамыр');
DEFINE('_JUN','Маусым');
DEFINE('_JUL','Шілде');
DEFINE('_AUG','Тамызт');
DEFINE('_SEP','Қыркүйек');
DEFINE('_OCT','Қазан');
DEFINE('_NOV','Қараша');
DEFINE('_DEC','Желтоқсан');
DEFINE('_POLL_TITLE','Сұрақ нәтижелері');
DEFINE('_SURVEY_TITLE','Сұрақ аты:');
DEFINE('_NUM_VOTERS','Сан дауыс бергендердің:');
DEFINE('_FIRST_VOTE','Бірінші дауыс:');
DEFINE('_LAST_VOTE','Соңғы дауыс:');
DEFINE('_SEL_POLL','Сұрақты таңдаңыздар:');
DEFINE('_NO_RESULTS','Таңдалған сұрақпен тап осы жоқ.');

/** registration.php*/
DEFINE('_ERROR_PASS','Кешіріңіздер, сондай пайдаланушы табылған емес.');
DEFINE('_NEWPASS_MSG','Пайдаланушы есептік жазуы $checkusername e-mail мекенжайына талапқа сай болады.\n'.
'Сайта пайдаланушысы $ mosConfig_live_site жаңа пароль алуына сауалды істеді.\n\n'.
'Сіздердің жаңа пароліңіз: $newpass \n\n. қарапайым сіздер пароль өзгертуін сұрамадыңыздар, мына әкім туралы хабарлаңыздар.'
'Сіздер тек қана хабарлау мынау көре алаcыздар, көбірек ешкім. Егер мынау қате, қарапайым кіріңіздер'.
'Сайтқа, жаңа пароль қолдана, және содан соң, оның өзгертіңіздер ыңғайлы сіздерге.');
DEFINE('_NEWPASS_SUB','$_sitename :: Жаңа пароль үшін $checkusernamee');
DEFINE('_NEWPASS_SENT','Жаңа пароль пайдаланушыға жасалған және жүріп кеткен!');
DEFINE('_REGWARN_NAME','Өтінем, өз қазіргі атыны енгізіңіздер (аты, елестету сайтеге).');
DEFINE('_REGWARN_UNAME','Өтінем, пайдаланушы өз атысын енгізіңіздер (логин).');
DEFINE('_REGWARN_MAIL','Өтінем, e-mail мекенжайын дұрыс енгізіңіздер.');
DEFINE('_REGWARN_PASS','Өтінем, парольді дұрыс енгізіңіздер. Тиісті болу пароль ашық жерлер, оның ұзындығы тиісті асырамау 6 символ және ол азырақ емес цифрлардан тек қана тиісті түзелу (0-9) және латын символдарының (a-z, A-Z)');
DEFINE('_REGWARN_VPASS1','Өтінем, парольді тексеріңіздер.');
DEFINE('_REGWARN_VPASS2','Пароль және оның растауы сәйкес келмейді. Өтінем, бір тағы байқап көріңіздер.');
DEFINE('_REGWARN_INUSE','Мынау пайдаланушы атысы қолданылады. Өтінем, басқа атыны таңдаңыздар.');
DEFINE('_REGWARN_EMAIL_INUSE','Мынау e-mail қолданылады. Егер сіздер өз пароліңізді ұмытсаңыздар, сілтемеге басыңыздар "парольді ұмытты?" Және e-mail көрсетілген жаңа пароль жөнелтілген болады.');
DEFINE('_SEND_SUB','Тап осылар жаңа пайдаланушы туралы %s %s');
DEFINE('_USEND_MSG_ACTIVATE','Аман-сау болыңыздар %s,
Сайтеге тіркеудің артынан алғыс айтимыз %s. Сіздердің есептік жазуыңыз табысты жасалған және тиісті активтенген болу.
Есептік жазу активтендіру үшін, сілтемеде басыңыздар немесе браузера адрестік жолына оның жазып сызып алыңыздар:
%s
Активтендіруден кейін сіздер сайтқа кіре алаcыздар %s, пайдаланушы өз аты және пароль қолдана:
Пайдаланушы атысы -%s
Пароль -%s');
DEFINE('_USEND_MSG',"аман-сау болыңыздар %s,
Сайтеге тіркеудің артынан сізді алғыс айтимыз %s.
Сіздер қазір сайтқа кіре алаcыздар %s, пайдаланушы аты және пароль қолдана, енгізілгендер сіздермен тіркеу жанында.");
DEFINE('_USEND_MSG_NOPASS','Аман-сау болыңыздар $name,\n\n сіздер сайтеге табысты тіркелген $mosConfig_live_site.\n'.
'Сіздер сайтқа кіре алаcыздар $mosConfig_live_site, тап осы қолдана, сіздер тіркеу жанында көрсеттіңіздер.\n\n'.
'Мынауды хат керек емес жауап беру, дәл осылай қалай ол хабарландыру үшін тек қана жасалған автоматты және арналған\n');
DEFINE('_ASEND_MSG','Аман-сау болыңыздар! Мынау жүйелік хабарлау сайтамен %s.
Сайтеге %s жаңа пайдаланушы тіркелді.
Тап осылар пайдаланушының:
Қазіргі аты -%s
E-mail мекенжайы -%s
Пайдаланушы атысы -%s
Мынауды хат керек емес жауап беру, дәл осылай қалай ол хабарландыру үшін тек қана жасалған автоматты және арналған. ');
DEFINE('_REG_COMPLETE_NOPASS','<div class="componentheading">Тіркеу аяқталған!</div ><br />&nbsp;&nbsp;'.
'Сіздер қазір сайтқа кіре алаcыздар.<br />&nbsp;&nbsp;');
DEFINE('_REG_COMPLETE','<div class="componentheading">Тіркеу аяқталған!</div><br />Сіздер қазір сайтқа кіре алаcыздар.');
DEFINE('_REG_COMPLETE_ACTIVATE','<div class="componentheading">Тіркеу аяқталған!</div><br />Сіздердің есептік жазуыңыз жасалған және тиісті активтенген болу бұрынның сіздер онымен пайдаланып қалаcыздар. Сіздердің e-mail сілтемемен хат жүріп кеткен болатын, арқасында қайсының сіздер өз есептік жазуды активтендіре алаcыздар.');
DEFINE('_REG_ACTIVATE_COMPLETE','<div class="componentheading">Есептік жазу активтенген!</div><br />Сіздердің есептік жазуыңыз активтенген. Сіздер қазір сайтқа кіре алаcыздар, пайдаланушы аты және пароль қолдана, сіздер тіркеу жанында енгіздіңіздер.');
DEFINE('_REG_ACTIVATE_NOT_FOUND','<div class="componentheading">Активтендіру сенімсіз сілтемесі!</div><br />Тап осы есептік жазу сайтеге жоқ болады немесе активтенген.');
DEFINE('_REG_ACTIVATE_FAILURE','<div class="componentheading">Активтендіру қатесі!</div><br />Сіздердің есептік жазуыңыз активтендіруі. Өтінем, әкімге бұрылаcыздар.');

/** classes/html/registration.php*/
DEFINE('_PROMPT_PASSWORD','Парольді ұмытты?');
DEFINE('_NEW_PASS_DESC','Өтінем, пайдаланушы өз атылары және e-mail мекенжайын енгізіңіздер, бүркеншектегі шегені содан соң басыңыздар "пароль жіберу".<br />'.
'Көп кешікпей, e-mail көрсетілген мекенжайына сіздер жаңа парольмен хатты алыңыздар. Сайтқа кіруге арналған пароль мынау қолданыңыздар.');
DEFINE('_PROMPT_UNAME','Пайдаланушы атысы:');
DEFINE('_PROMPT_EMAIL','E-mail мекенжайы:');
DEFINE('_BUTTON_SEND_PASS','Пароль жіберуь');
DEFINE('_REGISTER_TITLE','Тіркеу');
DEFINE('_REGISTER_NAME','Қазіргі аты:');
DEFINE('_REGISTER_UNAME','Пайдаланушы атысы:');
DEFINE('_REGISTER_EMAIL','E-mail:');
DEFINE('_REGISTER_PASS','Пароль:');
DEFINE('_REGISTER_VPASS','Пароль растауы:');
DEFINE('_REGISTER_REQUIRED','Барлық даланың, символмен белгіленгендер (*), толтыру үшін міндетті!');
DEFINE('_BUTTON_SEND_REG','Тап осы жіберу');
DEFINE('_SENDING_PASSWORD','Сіздердің пароліңіз жүріп кеткен болады көрсетілген e-mail мекенжайы жоғарырақ.<br />Қашан сіздер алыңыздар'.
'Жаңа пароль, сіздер сайтқа кіруге істей алады және пароль мынау өзгерту қашан болса да.');

/** classes/html/search.php*/
DEFINE('_SEARCH_TITLE','Іздеу');
DEFINE('_PROMPT_KEYWORD','Іздеу маңызды сөйлемге');
DEFINE('_SEARCH_MATCHES','Табылған %d сәйкес келулердің');
DEFINE('_CONCLUSION','Барлығы табылған $totalRows материалдардың.');
DEFINE('_NOKEYWORD','Ештеңе табылған емес');
DEFINE('_IGNOREKEYWORD','Іздеуде сылтауларды жіберілген болатын.');
DEFINE('_SEARCH_ANYWORDS','Сөз');
DEFINE('_SEARCH_ALLWORDS','Барлық сөздің');
DEFINE('_SEARCH_PHRASE','Бүтін сөйлемді');
DEFINE('_SEARCH_NEWEST','Кемумен');
DEFINE('_SEARCH_OLDEST','Өсумен');
DEFINE('_SEARCH_POPULAR','Әйгілілікпен');
DEFINE('_SEARCH_ALPHABETICAL','Әліпбилік рет');
DEFINE('_SEARCH_CATEGORY','Бөлім / Категория');
DEFINE('_SEARCH_MESSAGE',' Арналған мәтін тиісті асырау 3-20 символдардың');
DEFINE('_SEARCH_ARCHIVED','Архивта');
DEFINE('_SEARCH_CATBLOG','Категория Блогі');
DEFINE('_SEARCH_CATLIST','Категория тізімі');
DEFINE('_SEARCH_NEWSFEEDS','Жаңалықтардың баулары');
DEFINE('_SEARCH_SECLIST','Бөлім тізімі');
DEFINE('_SEARCH_SECBLOG','Бөлім Блогі');

/** templates/*.php*/
DEFINE('_ISO2','cp1251');
DEFINE('_ISO','charset=windows-1251');
DEFINE('_DATE_FORMAT','Бүгін: d.m.Y г.'); //PHP-функция DATE форматын қолданыңыздар

/* Төмен жиі тігісті өзгертіңіздер, сайтеге дата шығаруы өзгертуіне арналған
* Мысалы, DEFINE("_DATE_FORMAT_LC"," %d %B %Y г. %H:%M"); strftime PHP-функция форматын қолданыңыздар */
DEFINE('_DATE_FORMAT_LC',$mosConfig_form_date); //Strftime PHP-функция форматын қолданыңыздар
DEFINE('_DATE_FORMAT_LC2',$mosConfig_form_date_full); //Уақыттардың толық форматы
DEFINE('_SEARCH_BOX','Іздеу...');
DEFINE('_NEWSFLASH_BOX','Қысқаша жаңалықтың!');
DEFINE('_MAINMENU_BOX','Менюмен негізгі');

/** classes/html/usermenu.php*/
DEFINE('_UMENU_TITLE','Пайдаланушы менюі');
DEFINE('_HI','Аман-сау болыңыздар, ');

/** user.php*/
DEFINE('_SAVE_ERR','Өтінем, даланың барлық толтырыңыздар.');
DEFINE('_THANK_SUB','Рахмет сіздердің материалыңыздың артынан. Ол сайтеге орналастырудың алдында әкіммен көрілген болады.');
DEFINE('_THANK_SUB_PUB','Рахмет сіздердің материалыңыздың артынан.');
DEFINE('_UP_SIZE','Сіздер мөлшермен файлдарды 15Кб толтыра арти алмаcыздар.');
DEFINE('_UP_EXISTS','Ибейнелеу атпен $userfile_name бар болады. Өтінем, файл атын өзгертіңіздер және бір тағы байқап көріңіздер.');
DEFINE('_UP_COPY_FAIL','Қате көшіріп алу жанында');
DEFINE('_UP_TYPE_WARN','Сіздер форматында gif немесе jpg тек қана бейнелеулерді толтыра арти алаcыздар.');
DEFINE('_MAIL_SUB','Пайдаланушының жаңа материалы');
DEFINE('_MAIL_MSG','Аман-сау болыңыздар $adminName,\n\n пайдаланушы $author бөлімге жаңа материалды ұсынады $type атпен $title'.
'Сайтаға арналған $mosConfig_live_site.\n\n\n'.
'Өтінем, мекенжаймен әкім панеліне кіріңіздер $mosConfig_live_site/administrator қарауға арналған және қосудың оның $type.\n\n'.
'Мынауды хат керек емес жауап беру, дәл осылай қалай ол хабарландыру үшін тек қана жасалған автоматты және арналған\n');
DEFINE('_PASS_VERR1','Егер сіздер парольді өзгертуді тілесеңіздер, өтінем, өзгерту растауына арналған бір тағы оның енгізіңіздер.');
DEFINE('_PASS_VERR2','Егер сіздер парольді өзгертуге шешсеңіздер, өтінем, назарды ықылас білдіріңіздер, не пароль және оның растауы тиісті сәйкес келу.');
DEFINE('_UNAME_INUSE','Пайдаланушы таңдалған атысы қолданылады.');
DEFINE('_UPDATE','Жаңарту');
DEFINE('_USER_DETAILS_SAVE','Сіздердің тап осылар сақталған.');
DEFINE('_USER_LOGIN','Пайдаланушы кіруі');

/** components/com_user*/
DEFINE('_EDIT_TITLE','Жеке тап осылар');
DEFINE('_YOUR_NAME','Толық аты');
DEFINE('_EMAIL','E-mail мекенжайы');
DEFINE('_UNAME','Пайдаланушы атысы (логин)');
DEFINE('_PASS','Пароль');
DEFINE('_VPASS','Пароль растауы ');
DEFINE('_SUBMIT_SUCCESS','Сіздердің хабарыңыз қабылданған!');
DEFINE('_SUBMIT_SUCCESS_DESC','Сіздердің хабарыңыз әкімге табысты жүріп кеткен. Қараудан кейін, сіздердің материалыңыз мына сайтеге жарияланған болады.');
DEFINE('_WELCOME','Қош келдіңіз!');
DEFINE('_WELCOME_DESC','Қош келдіңіз біздің сайтамыз пайдаланушысы бөліміне');
DEFINE('_CONF_CHECKED_IN','Барлық \'қоршалғандар\' сіздермен элементтер қазір \'жол ашылған\'.');
DEFINE('_CHECK_TABLE','Кесте тексеруі');
DEFINE('_CHECKED_IN','Сыналған');
DEFINE('_CHECKED_IN_ITEMS','');
DEFINE('_PASS_MATCH','Парольдер сәйкес келмейді');

/** components/com_banners*/
DEFINE('_BNR_CLIENT_NAME','Сіздер клиент атысы тиісті енгізу.');
DEFINE('_BNR_CONTACT','Сіздер клиентке арналған контакті тиісті таңдау.');
DEFINE('_BNR_VALID_EMAIL','Адұрыс клиент электрондық пошта мекенжайы тиісті болу.');
DEFINE('_BNR_CLIENT','Сіздер клиентті тиісті таңдау,');
DEFINE('_BNR_NAME','Баннера атысын енгізіңіздер.');
DEFINE('_BNR_IMAGE','Баннера бейнелеулері таңдаңыздар.');
DEFINE('_BNR_URL','Сіздер тиісті енгізу баннера URL/коды.');

/** components/com_login*/
DEFINE('_ALREADY_LOGIN','Сіздер жіңішке авторизированыдан!');
DEFINE('_LOGOUT','Жұмыс аяқтауына арналған осында басыңыздар');
DEFINE('_LOGIN_TEXT','Далаларды қолданыңыздар "пайдаланушы" және "пароль" сайтуға рұқсатқа арналған');
DEFINE('_LOGIN_SUCCESS','Сіздер табысты кірдіңіздер');
DEFINE('_LOGOUT_SUCCESS','Сіздер сайтоммен жұмысты табысты аяқтадыңыздар');
DEFINE('_LOGIN_DESCRIPTION','Сіздер пайдаланушы сияқты сайтқа тиісті кіру, жабық бөлімдерге рұқсатқа арналған');
DEFINE('_LOGOUT_DESCRIPTION','Сіздер профильді нақты тастауды қалаңыздар?');

/** components/com_weblinks*/
DEFINE('_WEBLINKS_TITLE','Сілтеменің');
DEFINE('_WEBLINKS_DESC','Тап осы бөлімде ауда қызықты және пайдалы сілтемелерді ең жиналған.<br />Бөлімдерден бір тізімнен таңдаңыздар, ал керек сілтемені содан соң таңдаңыздар.');
DEFINE('_HEADER_TITLE_WEBLINKS','Сілтеме');
DEFINE('_SECTION','Бөлім');
DEFINE('_SUBMIT_LINK','Сілтемені қосу');
DEFINE('_URL','URL:');
DEFINE('_URL_DESC','Суреттеу:');
DEFINE('_NAME','Ат:');
DEFINE('_WEBLINK_EXIST','Сілтеме сондай атпен бар болады. Атыны өзгертіңіздер және бір тағы байқап көріңіздер.');
DEFINE('_WEBLINK_TITLE','Сілтеме ат тиісті болу.');

/** components/com_newfeeds*/
DEFINE('_FEED_NAME','Қайнар аты');
DEFINE('_FEED_ARTICLES','Мақалалардың');
DEFINE('_FEED_LINK','Қайнар сілтемесі');

/** whos_online.php*/
DEFINE('_WE_HAVE','Қазір сайтеге орнында болады:<br />');
DEFINE('_AND','  және  ');
DEFINE('_GUEST_COUNT','%s қонақ');
DEFINE('_GUESTS_COUNT','%s қонақтардың');
DEFINE('_MEMBER_COUNT','%s пайдаланушы');
DEFINE('_MEMBERS_COUNT','%s пайдаланушылардың');
DEFINE('_ONLINE','');
DEFINE('_NONE','Онлайнда келушілерді жоқ');

/** modules/mod_banners*/
DEFINE('_BANNER_ALT','Жарнама');

/** modules/mod_random_image*/
DEFINE('_NO_IMAGES','Бейнелеулерді жоқ');

/** modules/mod_stats.php*/
DEFINE('_TIME_STAT','Уақыт');
DEFINE('_MEMBERS_STAT','Пайдаланушылардың');
DEFINE('_HITS_STAT','Қатысулардың');
DEFINE('_NEWS_STAT','Жаңалықтардың');
DEFINE('_LINKS_STAT','Сілтемелердің');
DEFINE('_VISITORS','Келушілердің');

/** /adminstrator/components/com_menus/admin.menus.html.php*/
DEFINE('_MAINMENU_HOME','*Бірінші тізіммен мына меню жарияланған пунктісі [mainmenu] автоматты тұрады `негізгінің` сайта бетімен*');
DEFINE('_MAINMENU_DEL','*Сіздер `қашықтатуға алу` мынау меню, ол сондықтан қажетті үшін сайта нормалы жұмыс жасаулары*');
DEFINE('_MENU_GROUP','*`Меню үлгілері` көбірек көрінеді біреу топтың*');

/** administrators/components/com_users*/
DEFINE('_NEW_USER_MESSAGE_SUBJECT','Жаңа тап осылар пайдаланушының');
DEFINE('_NEW_USER_MESSAGE','Аман-сау болыңыздар, %s
Сіздер сайтеге әкіммен тіркелген болатын %s.
Мынау пайдаланушы аты және пароль сіздердің хабарлау асырайды, сайтқа кіруге арналған %s:
Пайдаланушы атысы -%s
Пароль -%s
Мынауды хабарлау керек емес жауап беру. Ол жіберулердің роботымен және хабарлау үшін тек қана жүріп кеткен сгенерировано.');

/** administrators/components/com_massmail*/
DEFINE('_MASSMAIL_MESSAGE',"Мынау хабарлау сайтамен '%s'
Хабарлау:
");

// Joostina!
DEFINE('_REG_CAPTCHA','Бейнелеуден мәтінді енгізіңіздер:*');
DEFINE('_REG_CAPTCHA_VAL','Бейнелеуден код қажетті енгізу.');
DEFINE('_REG_CAPTCHA_REF','Бейнелеу жаңарту үшін басыңыздар.');
DEFINE('_PRINT_PAGE_LINK','Сайтеге бет мекенжайы');
$bad_text = array('авле','көбірек','болды','сіздерге','сізді','жоғары','көрініп тұр','міне','барлық','әрқашан','барлықтардың','қайда','сөйледі','сөйлейміз','сөйлейді','тіпті','екі','үшін','оның','оған','егер','бар','тағы','содан соң','осында','білді','білемін','жүремін','немесе','әрбір','көрінеді','көрінді','қалай','қандайлар','қашан','қайсы','қайсылар','кім','мені','маған','жасай','алуға','алу','менің','менің','боладыға алу','менің','менің','мол','менің','керек','біздің','бастады','бастың','оны','оны','оған','көпсіз','аздап','оған','бірнешенің','жоқ','ешуақытта','оларды','ештеңе','бірақ','ол','олар','тағы да','өте','әзірше','кейін','сонан соң','дерлік','жайлы','бір','өз','өзіме','өзінді','қазір','айтыды','айтыды','сәл','өте','сияқты','қайтадан','болаттың','дәл осылай','онда','сенің','сенің','саған','сені','қазір','сол уақытта','ананы','да','тек қана','үш','мұнда','жіңішке','бірақ','немен','арқылы','не','үшін сәл','мынау','бұлар','бұларды','мынаны','мынаның','мынада','мынаны');

/* administrator components com_admin */
DEFINE('_FILE_UPLOAD','Файл тиеуі');
DEFINE('_MAX_SIZE','Барынша көп мөлшер');
DEFINE('_ABOUT_JOOSTINA','Joostina туралы');
DEFINE('_ABOUT_SYSTEM','Жүйе туралы');
DEFINE('_SYSTEM_OS','Жүйе');
DEFINE('_DB_VERSION','Тап осы база болжамасы');
DEFINE('_PHP_VERSION','PHP болжамасы ');
DEFINE('_APACHE_VERSION','Веб-сервер');
DEFINE('_PHP_APACHE_INTERFACE','веб-сервер жјне PHP интерфейс аралық');
DEFINE('_JOOSTINA_VERSION','Joostina болжамасы');
DEFINE('_BROWSER','Браузер (User Agent)');
DEFINE('_PHP_SETTINGS','Маңызды PHP күйге келтірулері');
DEFINE('_RG_EMULATION','Register Globals эмуляциясы');
DEFINE('_REGISTER_GLOBALS','Register Globals - тіркеу глобальды өзгергіштердің');
DEFINE('_MAGIC_QUOTES','Magic Quotes параметрі');
DEFINE('_SAFE_MODE','Safe Mode - тәртіп қауіпсіз');
DEFINE('_SESSION_HANDLING','Сессиялардың өңдеуі');
DEFINE('_SESS_SAVE_PATH','Session save path - сессиялардың сақтау каталогі');
DEFINE('_PHP_TAGS','Спецтеги php');
DEFINE('_BUFFERING','Буферлеу');
DEFINE('_OPEN_BASEDIR','Рұқсат етілгендер/ашық каталогтер');
DEFINE('_ERROR_REPORTING','Қателердің елестетуі');
DEFINE('_XML_SUPPORT','XML сүйеуі');
DEFINE('_ZLIB_SUPPORT','Zlib сүйеуі');
DEFINE('_DISABLED_FUNCTIONS','Тиым салынған функцияның');
DEFINE('_CONFIGURATION_FILE','Кескін үйлесімі файлы');
DEFINE('_ACCESS_RIGHTS','Рұқсат құқықтары');
DEFINE('_DIRS_WITH_RIGHTS','Барлық функциялардың жұмысына арналған және Joostina мүмкіншіліктерінің, барлық көрсетілгендер төмен каталогтер жазу үшін тиісті қолайлы болу.');
DEFINE('_CACHE_DIRECTORY','Кэш каталогі');
DEFINE('_SESSION_DIRECTORY','Сессиялардың каталогі');
DEFINE('_DATABASE','Мәліметтер қоры');
DEFINE('_TABLE_NAME','Кесте аты');
DEFINE('_DB_CHARSET','Кодталу');
DEFINE('_DB_NUM_RECORDS','Жазулардың');
DEFINE('_DB_SIZE','Мөлшер');
DEFINE('_FIND','Табу');
DEFINE('_CLEAR','Тазалау');
DEFINE('_GLOSSARY','Глоссарий');
DEFINE('_DEVELOPERS','Өңдеушілер');
DEFINE('_SUPPORT','Сүйеу');
DEFINE('_LICENSE','Лицензия');
DEFINE('_CHANGELOG','Өзгертулердің журналы');
DEFINE('_CHECK_VERSION','Joostina болжамасын тексеру');
DEFINE('_PREVIEW_SITE','Предпросмотр сайта');
DEFINE('_IN_NEW_WINDOW','Жаңа терезеде ашу');

/* administrator components com_banners */
DEFINE('_BANNERS_MANAGEMENT','Баннер басқаруы');
DEFINE('_EDIT_BANNER','Баннер редакциялауы');
DEFINE('_NEW_BANNER','Баннер жасауы');
DEFINE('_IN_CURRENT_WINDOW','Томже окне');
DEFINE('_IN_PARENT_WINDOW','Ағымдағы терезеде');
DEFINE('_IN_MAIN_FRAME','Фреймемен негізгіде');
DEFINE('_BANNER_CLIENTS','Баннер клиенттері ');
DEFINE('_BANNER_CATEGORIES','Баннер категориялары');
DEFINE('_NO_BANNERS','БАНЕР табылған емес');
DEFINE('_BANNER_COUNTER_RESETTED','Көрсету баннеров счётчик обнулён');
DEFINE('_CHECK_PUBLISH_DATE','Жариялау дата енгізу дұрыстығын тексеріңіздер');
DEFINE('_CHECK_START_PUBLICATION_DATE','Датаны жариялау бастары');
DEFINE('_CHECK_END_PUBLICATION_DATE','Датаны жариялау аяғылары');
DEFINE('_TASK_UPLOAD','Толтыра арту');
DEFINE('_BANNERS_PANEL','Баннер панелі');
DEFINE('_BANNERS_DIRECTORY_DOESNOT_EXISTS','banners папкасы бар болмайды');
DEFINE('_CHOOSE_BANNER_IMAGE','Тиеуге арналған бейнелеуді таңдаңыздар');
DEFINE('_BAD_FILENAME','Ашық жерлерсіз файл әліпбилік - сандық символдар тиісті асырау.');
DEFINE('_FILE_ALREADY_EXISTS','Файл #FILENAME# тап осы базада бар болады.');
DEFINE('_BANNER_UPLOAD_ERROR','Тиеу #FILENAME# сәтті емес');
DEFINE('_BANNER_UPLOAD_SUCCESS','Тиеу #FILENAME# в #DIRNAME# абысты өлшенген');
DEFINE('_UPLOAD_BANNER_FILE','Баннер файлы толтыра арту');

/* administrator components com_categories */
DEFINE('_CATEGORY_CHANGES_SAVED','Категорияда өзгертулер сақталған');
DEFINE('_USER_GROUP_ALL','Жалпы');
DEFINE('_USER_GROUP_REGISTERED','Қатысушылар');
DEFINE('_USER_GROUP_SPECIAL','Арнайы');
DEFINE('_CONTENT_CATEGORIES','Ұсталушы категорияның');
DEFINE('_ALL_CONTENT','Всё содержимое');
DEFINE('_ACTIVE','Белсенділердің');
DEFINE('_IN_TRASH','Кәрзеңкеде');
DEFINE('_VIEW_CATEGORY_CONTENT','Ұсталушы категория қарауы');
DEFINE('_CHOOSE_MENU_PLEASE','Өтінем, менюді таңдаңыздар');
DEFINE('_CHOOSE_MENUTYPE_PLEASE','Өтінем, меню үлгісін таңдаңыздар');
DEFINE('_ENTER_MENUITEM_NAME','Өтінем, меню мына пунктісіне арналған атты енгізіңіздер');
DEFINE('_CATEGORY_NAME_IS_BLANK','Категория ат тиісті болу');
DEFINE('_ENTER_CATEGORY_NAME','Категория атысын енгізіңіздер');
DEFINE('_ENTER_CATEGORY_TITLE','Категория тақырыбысын енгізіңіздер');
DEFINE('_CATEGORY_ALREADY_EXISTS','Категория сондай атпен бар болады. Қайтадан қайта айтыңыздар');
DEFINE('_EDIT_CATEGORY','Редакциялау');
DEFINE('_NEW_CATEGORY','Жаңа');
DEFINE('_CATEGORY_PROPERTIES','Категория қасиеттері');
DEFINE('_CATEGORY_TITLE','Категория тақырыбысы (Title)');
DEFINE('_CATEGORY_NAME','Категория аты (Name)');
DEFINE('_SORT_ORDER','Орналастыру реті');
DEFINE('_IMAGE','Бейнелеу');
DEFINE('_IMAGE_POSTITION','Бейнелеу орналастыруы');
DEFINE('_MENUITEM','Меню пунктісі ');
DEFINE('_NEW_MENUITEM_IN_YOUR_MENU','Жаңа пункті жасауы таңдалғанда сіздермен меню.');
DEFINE('_MENU_NAME','Меню пункті аты');
DEFINE('_CREATE_MENU_ITEM','Меню пунктісі жасау');
DEFINE('_EXISTED_MENU_ITEMS','Меню бар болу сілтемелері');
DEFINE('_NOT_EXISTS','Жоқ болады');
DEFINE('_MENU_LINK_AVAILABLE_AFTER_SAVE','Байланыс менюмен сақтаудан кейін қолайлы болады');
DEFINE('_IMAGES_DIRS','Бейнелеулердің каталогтері (MOSImage)');
DEFINE('_MOVE_CATEGORIES','Категориялардың ауыспалылығы');
DEFINE('_CHOOSE_CATEGORY_SECTION','Өтінем, ауыстырылатын категорияға арналған бөлімді таңдаңыздар');
DEFINE('_MOVE_INTO_SECTION','Бөлімге басқаша орналастыру');
DEFINE('_CATEGORIES_TO_MOVE','Ауыстырылатын категорияның');
DEFINE('_CONTENT_ITEMS_TO_MOVE','Ауыстырылатын объектілер ұсталушыны ');
DEFINE('_IN_SELECTED_SECTION_WILL_BE_MOVED_ALL','Барлық таңдалған бөлімге ауыстырылыған болады<br />саналған категорияның және барлық<br />саналған ұсталушы бұларды категориялардың.');
DEFINE('_CATEGORY_COPYING','Категориялардың көшіріп алуы');
DEFINE('_CHOOSE_CAT_SECTION_TO_COPY','Өтінем, көшірілетін категорияға арналған бөлімді таңдаңыздар');
DEFINE('_COPY_TO_SECTION','Бөлімге көшіру');
DEFINE('_CATS_TO_COPY','Көшірілетін категорияның');
DEFINE('_CONTENT_ITEMS_TO_COPY','Көшірілетін ұсталушы категорияның');
DEFINE('_IN_SELECTED_SECTION_WILL_BE_COPIED_ALL','Барлық таңдалған бөлімге көшіріп алынған болады<br />саналған категорияның және барлық<br />саналған ұсталушы бұларды категориялардың.');
DEFINE('_COMPONENT','Компонент');
DEFINE('_BEFORE_CREATION_CAT_CREATE_SECTION','Категория жасауының алдында сіздер тиісті жасау бірақ бір бөлім');
DEFINE('_CATEGORY_IS_EDITING_NOW','Категория #CATNAME# осы шақ басқа әкіммен редакциялайды ');
DEFINE('_TABLE_WEBLINKS_CATEGORY',' Кесте - категория Веб-сілтемелері ');
DEFINE('_TABLE_NEWSFEEDS_CATEGORY','Кесте - категория жаңалықтарының баулары');
DEFINE('_TABLE_CATEGORY_CONTACTS',' Кесте - категория контактілері');
DEFINE('_TABLE_CATEGORY_CONTENT','Кесте - ұсталушы категорияның');
DEFINE('_BLOG_CATEGORY_CONTENT','Блог - ұсталушы категорияның');
DEFINE('_BLOG_CATEGORY_ARCHIVE','Блог - архивтік ұсталушы категорияның');
DEFINE('_USE_SECTION_SETTINGS','Бөлім күйге келтірулері қолдану');
DEFINE('_CMN_ALL','Барлық');
DEFINE('_CHOOSE_CATEGORY_TO_REMOVE','Қашықтауға арналған категорияны таңдаңыздар');
DEFINE('_CANNOT_REMOVE_CATEGORY','Категория: #CIDS# мүмкін алысталған емес, ол жазулар асырайды');
DEFINE('_CHOOSE_CATEGORY_FOR_','Категорияны таңдаңыздар үшіня');
DEFINE('_CHOOSE_OBJECT_TO_MOVE','Ауыспалылыққа арналған объектіні таңдаңыздар');
DEFINE('_CATEGORIES_MOVED_TO','Категориялар ауыстырылыған ');
DEFINE('_CATEGORY_MOVED_TO','Категория ауыстырылыған ');
DEFINE('_CATEGORIES_COPIED_TO','Категориялар көшіріп алынған ');
DEFINE('_CATEGORY_COPIED_TO','Категория көшіріп алынған ');
DEFINE('_NEW_ORDER_SAVED','Жаңа рет сақтау');
DEFINE('_SAVE_AND_ADD','Сақтау және қосу');
DEFINE('_CLOSE','Жабу');
DEFINE('_CREATE_CONTENT','Ұсталушы жасау');
DEFINE('_MOVE','Алып бару');
DEFINE('_COPY','Көшіру');

/* administrator components com_checkin */
DEFINE('_BLOCKED_OBJECTS','Қоршалған объектілер');
DEFINE('_OBJECT','Объекті');
DEFINE('_WHO_BLOCK','Қоршады');
DEFINE('_BLOCK_TIME','Бітеу уақыты');
DEFINE('_ACTION','Әрекет');
DEFINE('_GLOBAL_CHECKIN','Разблокировкамен глобальды');
DEFINE('_TABLE_IN_DB','Тап осы база кестесі');
DEFINE('_OBJECT_COUNT','Қазық объектілердің');
DEFINE('_UNBLOCKED','Жол ашылған');
DEFINE('_CHECHKED_TABLE','Кесте сыналған');
DEFINE('_ALL_BLOCKED_IS_UNBLOCKED','Барлық қоршалған объектілер жол ашылған');
DEFINE('_MINUTES','Минуттың');
DEFINE('_HOURS','Сағаттардың');
DEFINE('_DAYS','Күндердің');
DEFINE('_ERROR_WHEN_UNBLOCKING','Жол ашылған жанында қате болды');
DEFINE('_UNBLOCKED2','Жол ашылған');

/* administrator components com_config */
DEFINE('_CONFIGURATION_IS_UPDATED','Кескін үйлесімі табысты жаңартылған');
DEFINE('_CANNOT_OPEN_CONF_FILE','Қате! кескін үйлесімі файлы жазу үшін ашу!');
DEFINE('_DO_YOU_REALLY_WANT_DEL_AUTENT_METHOD','Сіздер нақты өзгертуді қалаңыздар `сессия аутентификация әдісі`? \n\n Мынау фронтенда бар болу сессиялары барлық әрекет қашықтатады \n\n');
DEFINE('_GLOBAL_CONFIG','Глобальды кескін үйлесімі');
DEFINE('_PROTECT_AFTER_SAVE','Сақтаудан кейін жазудан қорғау');
DEFINE('_IGNORE_PROTECTION_WHEN_SAVE','Сақтау жанында жазудың қорғанышын елемеу');
DEFINE('_CONFIG_SAVING','Кескін үйлесімі сақтауы');
DEFINE('_NOT_AVAILABLE_CHECK_RIGHTS','Қолайлы емес');
DEFINE('_AVAILABLE_CHECK_RIGHTS','Қолайлы');
DEFINE('_SITE_NAME','Сайта аты');
DEFINE('_SITE_OFFLINE','Сайт өшірілген');
DEFINE('_SITE_OFFLINE_MESSAGE','Хабарлау сайтемен өшірілгенде');
DEFINE('_SITE_OFFLINE_MESSAGE2','Хабарлау, выводится пайдаланушыларға сайта орнына, қашан ол өшірілген күй-жағдайда орнында болады.');
DEFINE('_SYSTEM_ERROR_MESSAGE','Хабарлау жүйелік қате жанында');
DEFINE('_SYSTEM_ERROR_MESSAGE2','Хабарлау, выводится пайдаланушыларға сайта орнына, қашан Joostina! тап осы MySQL базаға қосыла алмайды.');
DEFINE('_SHOW_READMORE_TO_AUTH','Көрсету "басу жанында..." авторластырымағанмен');
DEFINE('_SHOW_READMORE_TO_AUTH2','Егер ИӘ, онда авторластырымаған пайдаланушыларға сілтемелерді көрінеді ұсталушыны деңгеймен рұқсатты - үшін тіркелгендердің. толық қарау мүмкіншілігіне арналған пайдаланушы тиісті болады авторизоваться.');
DEFINE('_ENABLE_USER_REGISTRATION','Пайдаланушылардың тіркеуін рұқсат беру');
DEFINE('_ENABLE_USER_REGISTRATION2','Егер ИӘ, онда пайдаланушыларға сайтеге тіркелуге рұқсат етілген болады.');
DEFINE('_ACCOUNT_ACTIVATION','Аккаунтамен жаңа активтендіруді қолдану');
DEFINE('_ACCOUNT_ACTIVATION2','Егер ИӘ, онда аккаунтпен жаңа пайдаланушыға қажетті активтендіреді, алудан кейін оларға хаттың активтендіруге арналған сілтемемен.');
DEFINE('_UNIQUE_EMAIL','E-mail бірегей талап ету');
DEFINE('_UNIQUE_EMAIL2','Егер Иә, онда пайдаланушылар e-mail бірдей мекенжайымен бірнеше аккаунтовтың жасауға істей алмайды.');
DEFINE('_USER_PARAMS','Сайта пайдаланушы параметрлері');
DEFINE('_USER_PARAMS2','Егер `ЖОҚ`, онда сайта параметрлерінің пайдаланушысымен құру мүмкіншілігі сөндірілген болады (редактор таңдауы)');
DEFINE('_DEFAULT_EDITOR','WYSIWYG-редактор үндемеумен');
DEFINE('_LIST_LIMIT','Тізімдердің ұзындығы (қазық-жолдардың)');
DEFINE('_LIST_LIMIT2','Барлық пайдаланушыларға арналған басқару панелінде үндемеумен тізімдердің ұзындығын қондырады');
DEFINE('_FRONTPAGE','Майдан');
DEFINE('_SITE','Сайт');
DEFINE('_CUSTOM_PRINT','Үлгі каталогінен баспа беті');
DEFINE('_CUSTOM_PRINT2','Каталогтен баспа түрге арналған өз бетімен бет қолдануы үлгі ағымдағы');
DEFINE('_MODULES_MULTI_LANG','Модульдердің многоязычносты рұқсат беру');
DEFINE('_MODULES_MULTI_LANG2','Модульдердің тілден жасалатын файлдары тексеру жүйеге рұқсат ету, егер сіздерде сондайлардың ЖОҚ орнатуға болады - ұсынылмаса');
DEFINE('_DATE_FORMAT_TXT','Дата форматы');
DEFINE('_DATE_FORMAT2','Дата елестетуіне арналған форматты таңдаңыздар. Strftime ережелерімен сәйкестікте формат қажетті қолдану.');
DEFINE('_DATE_FORMAT_FULL','Дата толық форматы және уақыттардың');
DEFINE('_DATE_FORMAT_FULL2','Дата елестетуіне арналған толық форматты таңдаңыздар және уақыттардың. Strftime ережелерімен сәйкестікте формат қажетті қолдану.');
DEFINE('_USE_H1_FOR_HEADERS','H1 тақырыбылары көмкеру толық қарау жанында');
DEFINE('_USE_H1_FOR_HEADERS2','Сталушы толық қарау тәртібінде тек қана H1 тақырыбылар көмкеру (басу жанында...)');
DEFINE('_USE_H1_HEADERS_ALWAYS','H1 тақырыбылары барлық көмкеру');
DEFINE('_USE_H1_HEADERS_ALWAYS2','H1 материал тақырыбылары орналасу.');
DEFINE('_DISABLE_RSS','RSS генерациясын сөндіріп тастау (syndicate)');
DEFINE('_DISABLE_RSS2','Егер `ИӘ`, онда баулардың RSS генерация мүмкіншілік және жұмыс сөндірілген болады олардын');
DEFINE('_USE_TEMPLATE','Үлгі қолдану');
DEFINE('_USE_TEMPLATE2','Ол үлгі таңдауы жанында қолданған болады бәріне сайте - басқа үлгілердің меню пунктілеріне баулардың. Қолдануға арналған бірнеше үлгі таңдаңыздар \\\'әр түрлілер\\\'');
DEFINE('_FAVICON_IMAGE','Браузера сала бастауларында сайта белгісі');
DEFINE('_FAVICON_IMAGE2','Сала бастауларда сайта белгісі ( сайланғанда ) браузера. Егер көрсетілген емессе немесе белгі файлы табылған емес, файл favicon.ico үндемеумен.');
DEFINE('_FAVICON_IMAGE3','Сала бастауларда сайта белгісі');
DEFINE('_DISABLE_FAVICON','favicon сөндіріп тастау');
DEFINE('_DISABLE_FAVICON2','Сайта favicon белгі ретінде пайдалануы');
DEFINE('_DISABLE_SYSTEM_MAMBOTS','system топ мамботысы сөндіріп тастау');
DEFINE('_DISABLE_SYSTEM_MAMBOTS2','Егер `ИӘ`, анау қолдану мамботовпен жүйеліктердің тоқтатылған болады. Назар, егер сайтеге мына топ мамботысы қолданылса, онда параметр активтендіруі ұсынылмайды');
DEFINE('_DISABLE_CONTENT_MAMBOTS','content топ мамботысы сөндіріп тастау');
DEFINE('_DISABLE_CONTENT_MAMBOTS2','Егер `ИӘ`, онда қолдану контента өңдеу мамботовы тоқтатылған болады. Назар, егер сайтеге мына топ мамботысы қолданылса, онда параметр активтендіруі ұсынылмайды');
DEFINE('_DISABLE_MAINBODY_MAMBOTS','mainbody топ мамботысы сөндіріп тастау');
DEFINE('_DISABLE_MAINBODY_MAMBOTS2','Егер `ИӘ`, анау компоненттердің қолдану стек өңдеу мамботовы (mainbody) тоқтатылған болады.');
DEFINE('_SITE_AUTH','Сайтеге авторластыру');
DEFINE('_SITE_AUTH2','Егер `ЖОҚ`, онда сайтеге авторластыру беті сөндірілген болады, егер оған меню пункті байлаулы емес. Сайтеге тіркеу мүмкіншілігі сонымен қатар сөндірілген болады');
DEFINE('_FRONT_SESSION_TIME','Сессия бар болу уақыты майданда');
DEFINE('_FRONT_SESSION_TIME2','Уақыт сайта пайдаланушы автоотключениясы белсенділіксіз жанында. Мына параметр үлкен мағынасы қауіпсіздікті төмендетеді.');
DEFINE('_DISABLE_FRONT_SESSIONS','Майданда сессиялар сөндіріп тастау');
DEFINE('_DISABLE_FRONT_SESSIONS2','Егер `Иә`, онда сайте. әрбір пайдаланушыға арналған сессиялардың басқаруы сөндірілген болады егер пайдаланушылардың сан есептеуі керек емессе және тіркеу өшіруге сөндірілген - болады.');
DEFINE('_DISABLE_ACCESS_CHECK_TO_CONTENT','Ұсталушыға рұқсат бақылауы сөндіріп тастау');
DEFINE('_DISABLE_ACCESS_CHECK_TO_CONTENT2','Егер `ИӘ`, онда рұқсат бақылауы ұсталушыға жүзеге аспайды, және бәріне пайдаланушыларға бейнеленген болады всё ұсталушы. Авторластыру сөндірулері пунктілермен бірге ұсынылады және сессиялардың майданда.');
DEFINE('_COUNT_CONTENT_HITS','Ұсталушы оқып шығулардың саны есептеу');
DEFINE('_COUNT_CONTENT_HITS2','Оқып шығулардың статистикасы параметр сөнуі жанында ұсталушыны белсенді болмайды.');
DEFINE('_DISABLE_CHECK_CONTENT_DATE','Даталармен жариялау тексерулері сөндіріп тастау');
DEFINE('_DISABLE_CHECK_CONTENT_DATE2','Егер сайтеге ұсталушыны тап осы параметрді жариялау уақытша аралары қиын-қыстау емес жақсырақ активтеу.');
DEFINE('_DISABLE_MODULES_WHEN_EDIT','Редакциялауда модульдер сөндіріп тастау');
DEFINE('_DISABLE_MODULES_WHEN_EDIT2','Егер `ИӘ`, онда ұсталушы редакциялау бетінде модульдер барлық майданнан сөндірілген болады');
DEFINE('_COUNT_GENERATION_TIME','Бет генерация уақыты есептеу');
DEFINE('_COUNT_GENERATION_TIME2','Егер `Иә`, онда әрбір бетте уақыт бейнеленген болады её генерацияны');
DEFINE('_ENABLE_GZIP','Беттердің GZIP-қысуы');
DEFINE('_ENABLE_GZIP2','Сүйеуі жіберудің алдында (егер сүйенсе). Мына функция қосуы толтыра арту беттердің мөлшерін кемітеді және трафик азаюына ертіп әкеледі. Анау ғой уақыт, мынау серверге жүкті тиеуді үлкейтиді.');
DEFINE('_IS_SITE_DEBUG','Сайта жөндеу тәртібі');
DEFINE('_IS_SITE_DEBUG2','Егер ИӘ, онда диагноздық хабар, сауалдар және MySQL қателері көрінеді.');
DEFINE('_EXTENDED_DEBUG','Үлкейтілген жөндеуші');
DEFINE('_EXTENDED_DEBUG2','Сайте туралы хабар жиын шығарушы үлкейтілген жөндеушісі сайта майданында қолдану.');
DEFINE('_CONTROL_PANEL','Басқару панелі');
DEFINE('_DISABLE_ADMIN_SESS_DEL','Басқару панелінде сессиялардың қашықтауы сөндіріп тастау');
DEFINE('_DISABLE_ADMIN_SESS_DEL2','Бар болу уақыттарының өтуінен кейін тіпті сессиялар алыстатпау.');
DEFINE('_DISABLE_HELP_BUTTON','Бүркеншектегі шегені сөндіріп тастау "көмек"');
DEFINE('_DISABLE_HELP_BUTTON2','Басқару панель тулбарасы көмек бүркеншектегі шегесін көрсетуге тиым салуға рұқсат етеді.');
DEFINE('_USE_OLD_TOOLBAR','Туллбара кәрі елестетуі қолдану');
DEFINE('_USE_OLD_TOOLBAR2','Туллбара бүркеншектегі шегелерінің шығаруы параметр активтендіруі жанында болады - кестелердің тәртібінде, қалай Joomla болды.');
DEFINE('_DISABLE_IMAGES_TAB','Қосымшаны сөндіріп тастау "бейнелеудің"');
DEFINE('_DISABLE_IMAGES_TAB2','Бейнелеу ұсталушы қосымшасын редакциялау жанында көрсетуге тиым салуға рұқсат етеді.');
DEFINE('_ADMIN_SESS_TIME','Басқару панелінде сессия бар болу уақыты');
DEFINE('_SECONDS','Секундылардың');
DEFINE('_ADMIN_SESS_TIME2','Уақыт, өту бойынша қайсыны пайдаланушыларды сөндірілген болады <strong>админцентра</strong> белсенділіксіз жанында. Өте үлкен мағына сайта қорғанушылығын кемітеді!');
DEFINE('_SAVE_LAST_PAGE','Сессия аяғысы жанында Админцентра бетін жадында тұту');
DEFINE('_SAVE_LAST_PAGE2','Егер басқару панелінде жұмыс сессиясы аяқталса, және сіздер 10 минуттың ішінде сайтқа кіріңіздер, онда сіздер кіру жанында бетке перенаправлены, қайсымен сөндіруді болаcыздар');
DEFINE('_HTML_CSS_EDITOR','html арналған көзбен шолу редактор және css');
DEFINE('_HTML_CSS_EDITOR2','html редакциялауына арналған синтаксис көмескі жарығымен редактор қолдану және үлгі файлдарының css');
DEFINE('_THIS_PARAMS_CONTROL_CONTENT','*Бұлар ұсталушы параметрлер элементтердің шығаруын бақылайды*');
DEFINE('_LINK_TITLES','Тақырыбылар сілтемелер түрінде');
DEFINE('_LINK_TITLES2','Егер ИӘ, объектілердің тақырыбылары ұсталушыны гиперссылки сияқты жұмыс істеуге бастайды бұлар объектілер');
DEFINE('_READMORE_LINK','Сілтеме "басу жанында..."');
DEFINE('_READMORE_LINK2','Егер параметр таңдалған көрсету, онда жанында сілтеме - басына көрінеді...- толық ұсталушы қарауға арналған');
DEFINE('_VOTING_ENABLE','Рейтингі/Дауысқа салу');
DEFINE('_VOTING_ENABLE2','Егер параметр таңдалған көрсету, жүйе --Рейтингі/Дауысқа салу-- қосылған болады');
DEFINE('_AUTHOR_NAMES','Авторлардың аттары');
DEFINE('_AUTHOR_NAMES2','Таңдаңыздар, көрсету авторлардың аттары. Мынау глобальды құру, бірақ ол мүмкін өзгертілген басқаларды орындарда меню деңгейінде немесе объектінің.');
DEFINE('_DATE_TIME_CREATION','Дата және жасау уақыты');
DEFINE('_DATE_TIME_CREATION2','Егер көрсету, онда дата және мақала жасау уақыты көрінеді. Мынау глобальды құру, бірақ мүмкін өзгертілген басқаларды орындарда меню деңгейінде немесе объектінің.');
DEFINE('_DATE_TIME_MODIFICATION','Дата және өзгерту уақыты');
DEFINE('_DATE_TIME_MODIFICATION2','Егер анықталған көрсету, онда мақала өзгерту датасы көрінеді. Мынау глобальды құру, бірақ ол мүмкін өзгертілген басқаларды орындарда.');
DEFINE('_VIEW_COUNT','Қараулардың саны');
DEFINE('_VIEW_COUNT2','Егер анықталған көрсету, онда глобальды құру мүмкін өзгертілген мынау сайта. Келушілерімен объекті қарауларының саны көрінеді басқаларды орындарда басқару панельдері.');
DEFINE('_LINK_PRINT','Сілтеме Қағазға басу');
DEFINE('_LINK_EMAIL','Сілтеме E-mail');
DEFINE('_PRINT_EMAIL_ICONS','Қағазға белгілері басқа және E-mail');
DEFINE('_PRINT_EMAIL_ICONS2','Егер таңдалған көрсету, онда белгілер түрінде Қағазға сілтемелері басқа және e-mail суреттелінеді, басқаша қарапайым мәтінмен сілтемемен.');
DEFINE('_ENABLE_TOC','Үшін объектілердің мазмұны');
DEFINE('_BACK_BUTTON','Батырма артқа (қайту)');
DEFINE('_CONTENT_NAV','Навигация ұсталушыға ');
DEFINE('_UNIQ_ITEMS_IDS','Жаңалықтардың бірегей теңестірулері');
DEFINE('_UNIQ_ITEMS_IDS2','Тізімде әрбір жаңалыққа арналған параметр қосуы жанында стиль бірегей теңестіруі сұраулар қояды.');
DEFINE('_AUTO_PUBLICATION_FRONT','Автоматты жариялау негізгіде');
DEFINE('_AUTO_PUBLICATION_FRONT2','Параметр қосуы жанында всё жасалынушы ұсталушы негізгі бетте жариялау үшін автоматты таңбаланған болады');
DEFINE('_DISABLE_BLOCK','Ұсталушы бітеудің сөндіріп тастау');
DEFINE('_DISABLE_BLOCK2','Объектілердің бітеу параметрі қосуы жанында ұсталушыны тексерілмейді. Редакторлардың үлкен санымен сайтахке қолдануға ұсынылмайды.');
DEFINE('_ITEMID_COMPAT','Itemid жұмыс тәртібі');
DEFINE('_ONE_EDITOR','Редактор бірыңғай даласы қолдану');
DEFINE('_ONE_EDITOR2','Кіріспе және негізгі мәтінге арналған бір дала қолдану.');
DEFINE('_LOCALE','Локаль');
DEFINE('_TIME_OFFSET','Сағаттық белдік (уақыттардың қызметтен алуы салыстырмалы UTC, сағ)');
DEFINE('_TIME_OFFSET2','Дата және уақыт, сайтеге көрінеді :');
DEFINE('_TIME_DIFF','Айырма келе-келе, сағ');
DEFINE('_TIME_DIFF2','RSS (уақыттардың қызметтен алуы салыстырмалы UTC, сағ)');
DEFINE('_CURR_DATE_TIME_RSS','Дата және уақыт ағымдағылар, RSS көрінеді');
DEFINE('_COUNTRY_LOCALE','Ел локалі');
DEFINE('_COUNTRY_LOCALE2','Ел аймақтық күйге келтірулері анықтайды: дата елестетуі, уақыттардың, сандардылардың және дәл осылай онан әрі');
DEFINE('_LOCALE_WINDOWS','Windows қолдану жанында қажетті енгізу <span style="color: red"><strong>KZ</strong></span>.
<br />Unix жүйелерде, егер үндемеумен мағына жұмыс істемесе, локали символдарының тізімі өзгертуге байқап көруге болады <strong>RU_RU.CP1251, ru_RU.cp1251, ru_ru.CP1251</strong>, немесе провайдераға орыс локалисы мағынасы білу.<br />
Біреуіннің енгізуге байқап көруге сонымен қатар болады локалей келесі: <strong>rus, russian</strong>');
DEFINE('_DB_HOST','MySQL хоста мекенжайы');
DEFINE('_DB_USER','Пайдаланушы атысы (MySQL)');
DEFINE('_DB_NAME','MySQL мәліметтер қоры');
DEFINE('_DB_PREFIX','Тап осы MySQL база префиксі');
DEFINE('_DB_PREFIX2','!!Өзгертімеңіздер, егер сіздерде тап осы жұмысшы база бар. Болмаған жағдайда, сіздер жоғалтып ала алаcыздар оған рұқсат!!');
DEFINE('_EVERYDAY_OPTIMIZATION','Тап осы база кестелерінің күн сайын ықшамдауы');
DEFINE('_EVERYDAY_OPTIMIZATION2','Егер `ИӘ`, анау әрбір тәуліктер база тап осылардың жақсы тез әрекеті үшін автоматты ықшамдалған болады');
DEFINE('_OLD_MYSQL_SUPPORT','MySQL кіші болжамаларының сүйеуі');
DEFINE('_OLD_MYSQL_SUPPORT2','Параметр кириллицамен сыйысушылық тәртібіне БД жұмыс автоматты аудармасын сөндіріп тастауға рұқсат етеді');
DEFINE('_DISABLE_SET_SQL','SET sql_mode сөндіріп тастау');
DEFINE('_DISABLE_SET_SQL2','SET sql_mode ап осы база жұмыс тәртіп аудармасы сөндіріп тастау');
DEFINE('_SERVER','Сервер');
DEFINE('_ABS_PATH','Абсолютті жол (сайта түбірі)');
DEFINE('_MEDIA_ROOT','Түбір менеджер медиасы');
DEFINE('_MEDIA_ROOT2','Жұмысқа арналған тамыр каталогті басқару компоненттісі тап осы медиалармен. Сайта түбірінің жолы / жақпен.');
DEFINE('_FILE_ROOT','Файлдық менеджер түбірі ');
DEFINE('_FILE_ROOT2','Жұмысқа арналған тамыр каталогті басқару компоненттісі файлдармен. Соңыда Windows (c) қолдану жанында тегеріш әріптері жол аттан бастала алады.');
DEFINE('_SECRET_WORD','Құпия сөз');
DEFINE('_GZ_CSS_JS','Сжатие CSS и JS файлов');
DEFINE('_SESSION_TYPE','Сессия теңестіру әдісі');
DEFINE('_SESSION_TYPE2','Өзгертімеңіздер, егер білмесеңіздер, неге мынау керек!<br /><br />Егер қызметтері сайт пайдаланушылармен қолданылса немесе пайдаланушылармен, сайт прокси - серверлерге рұқсат үшін қолданатындармен, онда 2 деңгей күйге келтірулерді қолдана алаcыздар');
DEFINE('_HELP_SERVER','Көмек сервері');
DEFINE('_HELP_SERVER2','Сервер көмектің - егер бос дала, онда көмек файлдары алады жергілікті папкалары http://мекенжай_сіздердің_сайта/help/. Сервер қосуына арналған көмек on-line http://help.joom.ru немесе  http://help.joomla.org енгізіңіздер');
DEFINE('_FILE_MODE','Файлдардың жасауы');
DEFINE('_FILE_MODE2','Файлдарға рұқсат рұқсаты');
DEFINE('_FILE_MODE3','Алмастыру емес CHMOD алмастырмау файлдардың (сервер үндемеуі қолдану)');
DEFINE('_FILE_MODE4','CHMOD алмастырмау файлдардың орнату');
DEFINE('_FILE_MODE5','Рұқсат рұқсаттарының құруына арналған пункті мынау таңдаңыздар жасалынушы файлдарға жаңадан');
DEFINE('_OWNER','Ие');
DEFINE('_O_READ','Оқу');
DEFINE('_O_WRITE','Жазу');
DEFINE('_O_EXEC','Орындалу');
DEFINE('_APPLY_TO_FILES','Бар болу файлдарға қолдану');
DEFINE('_APPLY_TO_FILES2','Өзгерту тигізеді <em> барлық бар болу файлдардың</em> сайтеге.<br/><b>НЕПРАВИЛЬНОЕ мына опция қолдануы ертіп әкеле алады!</b>');
DEFINE('_DIR_CREATION','Каталогтердің жасауы');
DEFINE('_DIR_CREATION2','Каталогтерге рұқсат рұқсаты');
DEFINE('_DIR_CREATION3','Алмастыру емес CHMOD алмастырмау каталогтердің (сервер үндемеуі қолдану)');
DEFINE('_DIR_CREATION4','CHMOD алмастырмау каталогтердің орнату');
DEFINE('_DIR_CREATION5','Рұқсат рұқсаттарының құруына арналған пункті мынау таңдаңыздар жасалынушы каталогтерге жаңадан');
DEFINE('_O_SEARCH','Іздеу');
DEFINE('_APPLY_TO_DIRS','Бар болу каталогтерге қолдану');
DEFINE('_APPLY_TO_DIRS2','Қосу бұларды жалауларды қолданылған болады <em>бәріне бар болу каталогтерге</em> сайтеге.<br/>'.'<b>НЕПРАВИЛЬНОЕ мына опция қолдануы ертіп әкеле алады!</b>');
DEFINE('_O_GROUP','Топ');
DEFINE('_O_AS','Қалай');
DEFINE('_RG_EMULATION_TXT','Лобальды өзгергіш тіркеу эмуляциясы');
DEFINE('_RG_DISABLE','Выкл. (ұсынылады) - қосымша қорғаныш');
DEFINE('_RG_ENABLE','Вкл. (ұсынылмайды)- сыйысушылық кәрі кеңейтулермен, параметр қолдануы жанында қауіпсіздік қорқытуы жоғарылайды.');
DEFINE('_METADATA','Метаданные');
DEFINE('_SITE_DESC','Сайта суреттеу, которое индексируется поисковиками');
DEFINE('_SITE_DESC2','Сіздер жиырма сөзбен сіздердің суреттеуіңіз жасай алуға шек қоймау, іздеу сервердің тәуелділігінде, сіздер қолдануға жоспарлайсыздар. Жасай алаcыздар сіздер сіздердің сайта. Ұстауы үшін қысқаша және лайық суреттеуді істеңіздер қосу - сіздердің маңызды сөздеріңіздің және маңызды сөйлемдердің. Дәл осылай қалай - іздеулер серверлер оқиды көбірек 20 сөздердің, анау сіздер жасай алаcыздар қосу бір немесе екі ұсыныстар. Өтінем куәландырылыңыздар, не сіздердің суреттеуіңіз ең маңызды бөлімі бірінші 20 сөзге орнында болады.');
DEFINE('_SITE_KEYWORDS','Сайта маңызды сөздері');
DEFINE('_SHOW_TITLE_TAG','Көрсету таңба-тег <b>title</b>');
DEFINE('_SHOW_TITLE_TAG2','Көрсету таңба-тег <b>title</b> объектілердің қарауы жанында ұсталушыны');
DEFINE('_SHOW_AUTHOR_TAG','Көрсету таңба-тег <b>author</b>');
DEFINE('_SHOW_AUTHOR_TAG2','Көрсету таңба-тег <b>author</b> объектілердің қарауы жанында ұсталушыны');
DEFINE('_SHOW_BASE_TAG','Көрсету таңба-тег <b>base</b>');
DEFINE('_SHOW_BASE_TAG2','Көрсету таңба-тег <b>base</b> әрбір бет денесінде');
DEFINE('_REVISIT_TAG','Мағына тега <b>revisit</b>');
DEFINE('_REVISIT_TAG2','Мағына тега көрсетіңіздер <b>revisit</b> күндерде');
DEFINE('_DISABLE_GENERATOR_TAG','Сөндіріп тастау тег Generator');
DEFINE('_DISABLE_GENERATOR_TAG2','Егер `ИӘ`, онда кодтан шығарып тасталған болады тег name=\\\'Generator\\\'');
DEFINE('_EXT_IND_TAGS','Үлкейтілгендер теги индексацияның');
DEFINE('_EXT_IND_TAGS2','Егер `ИӘ`, онда әрбір бет кодына қосылған болады арнайылар - үшін жақсы индексацияның');
DEFINE('_MAIL','Пошта');
DEFINE('_MAIL_METHOD','Пошта жіберуіне арналған қолдану');
DEFINE('_MAIL_FROM_ADR','Хаттың (Mail From)');
DEFINE('_MAIL_FROM_NAME','Жіберуші (From Name)');
DEFINE('_SENDMAIL_PATH','Sendmail жол');
DEFINE('_USE_SMTP','SMTP-авторластыруды қолдану');
DEFINE('_USE_SMTP2','Таңдаңыздар иә, егер пошта жіберуіне арналған авторластыру қажеттілігімен smtp-сервер қолданылса');
DEFINE('_SMTP_USER','SMTP пайдаланушы атысы');
DEFINE('_SMTP_USER2','Толады, егер пошта жіберуіне арналған авторластыру қажеттілігімен smtp-сервер қолданылса');
DEFINE('_SMTP_PASS','SMTP рұқсатқа арналған пароль');
DEFINE('_SMTP_PASS2','Толады, егер пошта жіберуіне арналған авторластыру қажеттілігімен smtp-сервер қолданылса');
DEFINE('_SMTP_SERVER','SMTP-сервер мекенжайы');
DEFINE('_CACHE','Кэш');
DEFINE('_ENABLE_CACHE','Кэширование қосу');
DEFINE('_ENABLE_CACHE2','Кэширования қосуы сауалдар MySQL кемітеді және серверге жүкті тиеу азаюына');
DEFINE('_CACHE_OPTIMIZATION','Кэширования ықшамдауы');
DEFINE('_CACHE_OPTIMIZATION2','Файлдардың мөлшері кеміте ең анамен артық символдар кэша файлдарынан автоматты алыстатады.');
DEFINE('_AUTOCLEAN_CACHE_DIR','Кэша каталог автоматты тазалауы');
DEFINE('_AUTOCLEAN_CACHE_DIR2','Мерзімі өткен файлдар алыстата кэша каталогі автоматты тазалау.');
DEFINE('_CACHE_MENU','Кэширование басқару панель менюі');
DEFINE('_CACHE_MENU2','Кэширования қосу басқару панель менюі. Жұмыс істейді - кэшамен стандарттыны.');
DEFINE('_CANNOT_CACHE','Кэширование мүмкін емес');
DEFINE('_CANNOT_CACHE2','<font color="red"><b>Кэша каталогі жазу үшін қолайлы емес.</b></font>');
DEFINE('_CACHE_DIR','Кэша каталогі');
DEFINE('_CACHE_DIR2','Ағымдағы кэша каталогі <b>жазу үшін қолайлы</b>');
DEFINE('_CACHE_DIR3','Ағымдағы кэша каталогі <b>жазу үшін қолайлы емес</b> - CHMOD 755 кэша қосуымен ауыстырыңыздар');
DEFINE('_CACHE_TIME','Кэша өмір уақыты');
DEFINE('_DB_CACHE','Кэш сауалдарының тап осы база');
DEFINE('_DB_CACHE_TIME','Тап осы база сауалдарының кэша өмір уақыты');
DEFINE('_STATICTICS','Статистика');
DEFINE('_ENABLE_STATS','Статистиктар жинау қосу');
DEFINE('_ENABLE_STATS2','Рұқсат сайта статистиктары жинау тиым беру/салу');
DEFINE('_STATS_HITS_DATE','Датамен ұсталушы қарау статистигына жүргізу');
DEFINE('_STATS_HITS_DATE2','Ескерту: мына тәртіпте тап осы үлкен көлемдерді жазылады!');
DEFINE('_STATS_SEARCH_QUERIES','Іздеу сауалдардың статистикасы ');
DEFINE('_SEF_URLS','Достықтар URL іздеу жүйелеріне арналған (SEF)');
DEFINE('_SEF_URLS2','Apache арналған тек қана! қолданудың алдында htaccess.txt қайта ат қойыңыздар .htaccess. Мынау apache - mod_rewrite модулі қосуына арналған қажетті');
DEFINE('_DYNAMIC_PAGETITLES','Беттердің динамикалық тақырыбылары (теги title)');
DEFINE('_DYNAMIC_PAGETITLES2','Тәуелділікте беттердің тақырыбылардың динамикалық өзгертуі ағымдағы қаралушы ұсталушының');
DEFINE('_CLEAR_FRONTPAGE_LINK','com_frontpage сілтеме тазалауы');
DEFINE('_CLEAR_FRONTPAGE_LINK2','Қысқа түр көбірек негізгі бет компонентіне сілтемеге қосып беру.');
DEFINE('_DISABLE_PATHWAY_ON_FRONT','Пачвей жасыру (pathway) негізгіде');
DEFINE('_DISABLE_PATHWAY_ON_FRONT2','Жол қосылған тәртіп жанында \\\'негізгі\\\' айта бірінші бетінде тығыз ашық жер символына ауыстырылған болады.');
DEFINE('_TITLE_ORDER','title элементтерінің орналастыру реті');
DEFINE('_TITLE_ORDER2','Беттердің тақырыбы элементтерінің орналастыру реті (тег title)');
DEFINE('_TITLE_SEPARATOR','title элементтерінің бөлгіші');
DEFINE('_TITLE_SEPARATOR2','Беттердің тақырыбы элементтерінің бөлгіші (тег title). үндемеуге - сызықшадан.');
DEFINE('_INDEX_PRINT_PAGE','Баспа болжама индексациясы');
DEFINE('_INDEX_PRINT_PAGE2','Егер `ИӘ`, анау баспа болжама ұсталушыны индексация үшін қолайлы болады');
DEFINE('_REDIR_FROM_NOT_WWW','Переадресация мекенжайлардың www емес');
DEFINE('_REDIR_FROM_NOT_WWW2','Сілтемемен сайтқа бату жанында site.ru, www.sie.ru переадресация автоматты туындатылған болады');
DEFINE('_ADMIN_CAPTCHA','Басқару панелінде авторластыруға арналған');
DEFINE('_ADMIN_CAPTCHA2','captcha қолдану үшін басқару панелінде қауіпсіз авторластыру көбірек.');
DEFINE('_REGISTRATION_CAPTCHA','Тіркеуге арналған');
DEFINE('_REGISTRATION_CAPTCHA2','captcha қолдану үшін қауіпсіз тіркеу көбірек.');
DEFINE('_CONTACTS_CAPTCHA','Контактілердің түріне арналған');
DEFINE('_CONTACTS_CAPTCHA2','captcha қолдану үшін контактілердің қауіпсіз түрлері көбірек.');
DEFINE('_O_OTHER','Әр түрлілер');
DEFINE('_SECURITY_LEVEL3','3 деңгей қорғаныштар - үндемеуге - ең жақсы');
DEFINE('_SECURITY_LEVEL2','2 деңгей қорғаныштар - прокси IP-мекенжайлары үшін рұқсат етілген');
DEFINE('_SECURITY_LEVEL1','1 деңгей қорғаныштар - кері сыйысушылық');
DEFINE('_PHP_MAIL_FUNCTION','PHP mail функциясын');
DEFINE('_CONSTRUCT_ERROR','Құрастыру қатесі');
DEFINE('_TIME_OFFSET_M_12','(UTC -12:00) Халықаралық сызық тәуліктікті уақыттардың');
DEFINE('_TIME_OFFSET_M_11','(UTC -11:00) Мидуэй арал, Самоа');
DEFINE('_TIME_OFFSET_M_10','(UTC -10:00) Гавайи');
DEFINE('_TIME_OFFSET_M_9_5','(UTC -09:30) Тайохае, арал Маркизскиесі');
DEFINE('_TIME_OFFSET_M_9','(UTC -09:00) Аляска');
DEFINE('_TIME_OFFSET_M_8','(UTC -08:00) Тынық мұхиттық уақыт (США &amp; Канада)');
DEFINE('_TIME_OFFSET_M_7','(UTC -07:00) Монтана уақыты (США &amp; Канада)');
DEFINE('_TIME_OFFSET_M_6','(UTC -06:00) Орталық уақыт (США &amp; Канада), Мехико');
DEFINE('_TIME_OFFSET_M_5','(UTC -05:00) Шығыс уақытые (США &amp; Канада), Богота, Лайма');
DEFINE('_TIME_OFFSET_M_4','(UTC -04:00) Атлантикалық уақыт (Канада), Каракас, Ла-Пас');
DEFINE('_TIME_OFFSET_M_3_5','(UTC -03:30) Ньюфаундленд және Лабрадор');
DEFINE('_TIME_OFFSET_M_3','(UTC -03:00) Бразилия, Буэнос Айрес, Джорджтаун');
DEFINE('_TIME_OFFSET_M_2','(UTC -02:00) Орташа-атлантикалық уақыт');
DEFINE('_TIME_OFFSET_M_1','(UTC -01:00 час) Азор аралдар, жасыл мүйіс аралдарыа (Острова Зеленого Мыса)');
DEFINE('_TIME_OFFSET_M_0','(UTC 00:00) Батыстағы-европалық уақыт, Лондон, Лиссабон, Касабланка');
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
DEFINE('_TIME_OFFSET_P_10_5','(UTC +10:30) Lord Howe (Австралия) аралы');
DEFINE('_TIME_OFFSET_P_11','(UTC +11:00) Магадан, Соломоновысы арал, Новая Каледония');
DEFINE('_TIME_OFFSET_P_11_5','(UTC +11:30) Норфолк аралы');
DEFINE('_TIME_OFFSET_P_12','(UTC +12:00) Камчатка, Окленд, Уэллингтон, Фиджи');
DEFINE('_TIME_OFFSET_P_12_75','(UTC +12:45) Чатем аралы');
DEFINE('_TIME_OFFSET_P_13','(UTC +13:00) Тонга');
DEFINE('_TIME_OFFSET_P_14','(UTC +14:00) Кирибати');

/* administrator components com_contact */
DEFINE('_CONTACT_MANAGEMENT','Басқару контактілермен');
DEFINE('_ON_SITE','Сайтеге');
DEFINE('_RELATED_WITH_USER','Пайдаланушымен байлаулы');
DEFINE('_CHANGE_CONTACT','Контакті өзгерту');
DEFINE('_CHANGE_CATEGORY','Категорияны өзгерту');
DEFINE('_CHANGE_USER','Пайдаланушыны өзгерту');
DEFINE('_ENTER_NAME_PLEASE','Сіздер аты тиісті енгізу');
DEFINE('_NEW_CONTACT','Жаңа');
DEFINE('_CONTACT_DETAILS','Контакті бөлшектері');
DEFINE('_USER_POSITION','Жай (лауазым)');
DEFINE('_ADRESS_STREET_HOUSE','Мекенжай (көше, үй)');
DEFINE('_CITY','Қала');
DEFINE('_STATE','Жақ/Облыс/Республика');
DEFINE('_COUNTRY','Ел');
DEFINE('_POSTCODE','Пошталық көрсеткіш ');
DEFINE('_ADDITIONAL_INFO','Қосымша хабар');
DEFINE('_PUBLISH_INFO','Хабар жариялау туралы');
DEFINE('_POSITION','Орналастыру');
DEFINE('_IMAGES_INFO','Хабар бейнелеу туралы');
DEFINE('_PARAMETERS','Параметрлер');
DEFINE('_CONTACT_PARAMS','*Бұлар контакті туралы тек қана хабар қарауы жанында параметрлер елестетумен басқарады*');

/* administrator components com_content */
DEFINE('_SITE_CONTENT','Сайтамен ұсталушы');
DEFINE('_GOTO_EDIT','Редакциялауға өту');
DEFINE('_SORT_BY','Сорттау');
DEFINE('_HIDE_NAV_TREE','Навигация ағашы жасыру');
DEFINE('_ON_FRONTPAGE','Негізгіде');
DEFINE('_SAVE_ORDER','Рет сақтау');
DEFINE('_TO_TRASH','Кәрзеңкеге');
DEFINE('_NEVER','Ешуақытта');
DEFINE('_START','Бас');
DEFINE('_ALWAYS','Әрқашан');
DEFINE('_END','Аяғы');
DEFINE('_WITHOUT_END','Аяғысыз');
DEFINE('_CHANGE_USER_DATA','Пайдаланушының тап осы өзгерту');
DEFINE('_CHANGE_CONTENT','Ұсталушы өзгерту');
DEFINE('_CHOOSE_OBJECTS_TO_TRASH','Өтінем, объектілер тізімнен таңдаңыздар, сіздер кәрзеңкеге жіберуді қалаңыздар.');
DEFINE('_WANT_TO_TRASH','Сіздер сенім, не объектіні жіберуді қалаңыздар кәрзеңкеге? \n Мынау объектілердің толық қашықтауына ертіп әкелмейді.');
DEFINE('_ARCHIVE','Архив');
DEFINE('_ALL_SECTIONS','Барлық бөлімдер');
DEFINE('_OBJECT_MUST_HAVE_TITLE','Мынау объекті тақырыбы тиісті болу');
DEFINE('_PLEASE_CHOOSE_SECTION','Сіздер бөлім тиісті таңдау');
DEFINE('_PLEASE_CHOOSE_CATEGORY','Сіздер категорияны тиісті таңдау');
DEFINE('_O_EDITING','Редакциялау');
DEFINE('_O_CREATION','Жасау');
DEFINE('_OBJECT_DETAILS','Объекті бөлшектері');
DEFINE('_ALIAS','Бүркеншік ат');
DEFINE('_INTROTEXT_M','Кіріспе мәтін: (Міндетті)');
DEFINE('_MAINTEXT_M','Ең басты мәтін: (Міндетті емес)');
DEFINE('_NOTETEXT_M','Белгінің: (Міндетті емес)');
DEFINE('_HIDE_PARAMS_PANEL','Параметрлердің панелі жасыру');
DEFINE('_SETTINGS','Күйге келтірудің');
DEFINE('_IN_ARCHIVE','Архивта');
DEFINE('_DRAFT_NOT_PUBLISHED','Шимай дәптер - емес жарияланған');
DEFINE('_RESET','Обнулить');
DEFINE('_CHANGED','Өзгерді');
DEFINE('_CREATED','Жасалған');
DEFINE('_NEW_DOCUMENT','Жаңа құжат');
DEFINE('_LAST_CHANGE','Соңғы өзгерту');
DEFINE('_NOT_CHANGED','Өзгермеді');
DEFINE('_START_PUBLICATION','Жариялау басы');
DEFINE('_END_PUBLICATION','Жариялау аяғысы');
DEFINE('_OBJECT_ID','ID Объекта');
DEFINE('_USED_IMAGES','Қолданылатын бейнелеудің');
DEFINE('_SUBDIRECTORY','Подпапка');
DEFINE('_IMAGE_EXAMPLE','Бейнелеу үлгісі');
DEFINE('_ACTIVE_IMAGE','Белсенді бейнелеу');
DEFINE('_SOURCE','Қайнар');
DEFINE('_ALIGN','Тегістеу');
DEFINE('_PARAMS_IN_VIEW','*Бұлар толық қарау тәртібінде тек қана параметрлер сырт пішінімен басқарады*');
DEFINE('_ROBOTS_PARAMS','Басқару күйге келтірулері роботтармен');
DEFINE('_MENU_LINK','Байланыс менюмен');
DEFINE('_MENU_LINK2','Меню пунктісі осында жасалады (ұсталушы Сілтеме - Объекті), қайсы вставляется таңдалғанды меню тізімінен');
DEFINE('_EXISTED_MENUITEMS','Меню бар болу пунктілері');
DEFINE('_PLEASE_SELECT_SMTH','Өтінем, бірдеңе таңдаңыздар');
DEFINE('_OBJECT_MOVING','Объектілердің ауыспалылығы');
DEFINE('_MOVE_INTO_CAT_SECT','Басқаша Бөлімге/Категорияны орналастыру');
DEFINE('_OBJECTS_TO_MOVE','Объектілерді ауыстырылыған болады');
DEFINE('_SELECT_CAT_TO_MOVE_OBJECTS','Өтінем, бөлім немесе объектілердің көшіріп алуына арналған категорияны таңдаңыздар');
DEFINE('_COPYING_CONTENT_ITEMS','Объектілердің көшіріп алуы ұсталушыны');
DEFINE('_COPY_INTO_CAT_SECT','Бөлімге/Категорияны көшіру');
DEFINE('_OBJECTS_TO_COPY','Объектілерді көшіріп алынған болады');
DEFINE('_ORDER_BY_NAME','Ішкі ретке');
DEFINE('_ORDER_BY_HEADERS','Тақырыбыларға');
DEFINE('_ORDER_BY_DATE_CR','Жасау датасына');
DEFINE('_ORDER_BY_DATE_MOD','Модификация датасына');
DEFINE('_ORDER_BY_ID','ID Теңестірулеріне');
DEFINE('_ORDER_BY_HITS','Қарауларға');
DEFINE('_CANNOT_EDIT_ARCHIVED_ITEM','Сіздер архивтік объектіні редакциялай алмаcыздар');
DEFINE('_NOW_EDITING_BY_OTHER','Осы шақ редакциялайды басқа пайдаланушымен');
DEFINE('_ROBOTS_HIDE','Жасыру таңба-тег robots');
DEFINE('_CONTENT_ITEM_SAVED','Өзгертулер табысты сақталған');
DEFINE('_OBJ_ARCHIVED','Объекті табысты архивирован');
DEFINE('_OBJ_PUBLISHED','Объекті табысты жарияланған');
DEFINE('_OBJ_UNARCHIVED','Объекті абысты архивтан суырылған');
DEFINE('_OBJ_UNPUBLISHED','Объекті табысты жариялаудан алынған');
DEFINE('_CHOOSE_OBJ_TOGGLE','Ауыстырып қосуға арналған объектіні таңдаңыздар');
DEFINE('_CHOOSE_OBJ_DELETE','Қашықтауға арналған объектіні таңдаңыздар');
DEFINE('_MOVED_TO_TRASH','Кәрзеңкеге жүріп кеткен');
DEFINE('_CHOOSE_OBJ_MOVE','Ауыспалылыққа арналған объектіні таңдаңыздар');
DEFINE('_ERROR_OCCURED','Қате болды');
DEFINE('_OBJECTS_MOVED_TO_SECTION','Объекті табысты бөлімге ауыстырылыған');
DEFINE('_OBJECTS_COPIED_TO_SECTION','Объекті бөлімге табысты көшіріп алынған');
DEFINE('_HITCOUNT_RESETTED','Қараулардың есепшісі лақтырылған');
DEFINE('_TIMES','Бір');

/* administrator components com_easysql */
DEFINE('_SQL_COMMAND','Команда');
DEFINE('_MANAGEMENT','Басқару');
DEFINE('_FIELDS','Даланың');
DEFINE('_EXEC_SQL','SQL орындау');

/* administrator components com_frontpage */
DEFINE('_UNKNOWN_ID','Теңестіру танылған емес');
DEFINE('_REMOVE_FROM_FRONT','Негізгімен алып тастау');
DEFINE('_PUBLISH_TIME_END','Жариялау уақыты ақты');
DEFINE('_CANNOT_CHANGE_PUBLISH_STATE','Жариялау статус алмастыруы қол жетпеу');
DEFINE('_CHANGE_SECTION','Бөлім өзгерту');

/* administrator components com_installer */
DEFINE('_OTHER_COMPONENT_USE_DIR','Басқа компонент каталогті қолданады');
DEFINE('_CANNOT_CREATE_DIR','Каталог мүмкін емес жасау');
DEFINE('_SQL_ERROR','SQL орындалу қатесі');
DEFINE('_ERROR_MESSAGE','Қате мәтіні');
DEFINE('_CANNOT_COPY_PHP_INSTALL','Құру PHP-файлы жазып сызып ала алмаймын');
DEFINE('_CANNOT_COPY_PHP_REMOVE','Қашықтау PHP-файлы жазып сызып ала алмаймын');
DEFINE('_ERROR_DELETING','Қашықтау қатесі');
DEFINE('_IS_PART_OF_CMS','Joomla түйіндері элементпен келеді және мүмкін алысталған емес.<br />сіздер жариялаудан оның тиісті шешу, егер оны қолдануды қалау.');
DEFINE('_DELETE_ERROR','Қашықтау - қате');
DEFINE('_UNINSTALL_ERROR','Деинсталляции қатесі');
DEFINE('_BAD_XML_FILE','Дұрыссыз XML-файл');
DEFINE('_PARAM_FILED_EMPTY','бос Параметр даласы файлдар және мүмкін емес қашықтату');
DEFINE('_INSTALLED_COMPONENTS','Анықталған компоненттер');
DEFINE('_INSTALLED_COMPONENTS2','Ана кеңейтулерді тек қана осында көрсетілген, сіздер қашықтата алаcыздар. Болмайды бөлімдер Joostina түйіндері қашықтату.');
DEFINE('_COMPONENT_NAME','Ат компонентті');
DEFINE('_COMPONENT_LINK','Меню сілтеме компонентті');
DEFINE('_COMPONENT_AUTHOR_URL','URL автор дың');
DEFINE('_OTHER_COMPONENTS_NOT_INSTALLED','Шеттегі компоненттер анықталған емес');
DEFINE('_COMPONENT_INSTALL','Құру компонентті');
DEFINE('_DELETING','Қашықтау');
DEFINE('_CANNOT_DEL_LANG_ID','Тіл id бос, файлдар сондықтан мүмкін емес қашықтату');
DEFINE('_GOTO_LANG_MANAGEMENT','Тілдермен басқаруға өту');
DEFINE('_INTALL_LANG','Сайта тілден жасалатын пакет құруы');
DEFINE('_NO_FILES_OF_MAMBOTS','Файлдарды жоқ , белгіленгендердің мамботы сияқты');
DEFINE('_WRONG_ID','id дұрыссыз объектінің');
DEFINE('_BAD_DIR_NAME_EMPTY','Папка даласы бос, мүмкін емес файлдар қашықтату');
DEFINE('_INSTALLED_MAMBOTS','Мамботымен анықталғандар');
DEFINE('_MAMBOT','Мамбот');
DEFINE('_TYPE','Үлгі');
DEFINE('_OTHER_MAMBOTS','Мынау түйін мамботы емес, ал мамботымен шеттегілер');
DEFINE('_INSTALL_MAMBOT','Мамбота құруы');
DEFINE('_UNKNOWN_CLIENT','Клиент белгісіз үлгісі');
DEFINE('_NO_FILES_MODULES','Файлдар, белгіленгендер модульдер сияқты, жоқ болады');
DEFINE('_ALREADY_EXISTS','Бар болады');
DEFINE('_DELETING_XML_FILE','XML файл дың қашықтау');
DEFINE('_INSTALL_MODULE','Анықталған модульдердің');
DEFINE('_MODULE','Модуль');
DEFINE('_USED_ON','Қолданылады');
DEFINE('_NO_OTHER_MODULES','Шеттегі модульдер анықталған емес');
DEFINE('_MODULE_INSTALL','Модуль құруы');
DEFINE('_SITE_MODULES','Сайта модульдері');
DEFINE('_ADMIN_MODULES','Модульдерді басқару панельдері');
DEFINE('_CANNOT_DEL_FILE_NO_DIR','фАйл мүмкін емес қашықтату, дәл осылай қалай Каталог бар болмайды');
DEFINE('_WRITEABLE','Жазу үшін қолайлы');
DEFINE('_UNWRITEABLE','Жазу үшін қол жетпеу');
DEFINE('_CHOOSE_DIRECTORY_PLEASE','Өтінем, каталогті таңдаңыздар');
DEFINE('_ZIP_UPLOAD_AND_INSTALL','Кеңейту архив тиеуі келесімен құрумен');
DEFINE('_PACKAGE_FILE','Пакет файлы');
DEFINE('_UPLOAD_AND_INSTALL','Толтыра арту және орнату');
DEFINE('_INSTALL_FROM_DIR','Каталогтен құру');
DEFINE('_INSTALLATION_DIRECTORY','құру каталогі');
DEFINE('_CONTINUE','Жалғастыру');
DEFINE('_NO_INSTALLER','Инсталлятор табылған емес');
DEFINE('_CANNOT_INSTALL','Құру [$element] мүмкін емес');
DEFINE('_CANNOT_INSTALL_DISABLED_UPLOAD','Құру мүмкін емес, тиым салынғанып жатқанда файлдардың тиеуі. Өтінем, каталогтен құруды қолданыңыздар.');
DEFINE('_INSTALL_ERROR','Құру қатесі');
DEFINE('_CANNOT_INSTALL_NO_ZLIB',' Құру мүмкін емес, анықталған емесіп жатқанда zlib сүйеуі');
DEFINE('_NO_FILE_CHOOSED','Файл таңдалған емес');
DEFINE('_ERORR_UPLOADING_EXT','Жаңа модуль тиеу қатесі ');
DEFINE('_UPLOADING_ERROR','Тиеу сәтті емес');
DEFINE('_SUCCESS','Табысты');
DEFINE('_UNSUCCESS','Сәтті емес');
DEFINE('_UPLOAD_OF_EXT','Жаңа элемент тиеуі');
DEFINE('_DELETE_SUCCESS','Қашықтау табысты');
DEFINE('_CANNOT_CHMOD','Шайқалған файлға рұқсат құқықтары өзгерти алмаймын');
DEFINE('_CANNOT_MOVE_TO_MEDIA','Каталогке секірген файл басқаша орналастыра алмаймын <code>/media</code>');
DEFINE('_CANNOT_WRITE_TO_MEDIA','Иеу жұлынған, дәл осылай каталог сияқты <code>/media</code> жазу үшін қол жетпеу.');
DEFINE('_CANNOT_INSTALL_NO_MEDIA','Тиеу жұлынған, дәл осылай каталог сияқты <code>/media</code> бар болмайды');
DEFINE('_ERROR_NO_XML_JOOMLA','Қате:  Бекітіп тұратында Joomla құру XML-файлы пакетте мүмкін емес табу.');
DEFINE('_ERROR_NO_XML_INSTALL','Қате: Бекітіп тұратында құру XML-файлы пакетте мүмкін емес табу.');
DEFINE('_NO_NAME_DEFINED','Файл аты айқын емес');
DEFINE('_FILE','Файл');
DEFINE('_NOT_CORRECT_INSTALL_FILE_FOR_JOOMLA','Joomla құрулары сыпайылық файлмен келмейді !');
DEFINE('_CANNOT_RUN_INSTALL_METHOD','Әдіс "install" сыныппен мүмкін шақырылған емес');
DEFINE('_CANNOT_RUN_UNINSTALL_METHOD','Әдіс "uninstall" сыныппен мүмкін шақырылған емес');
DEFINE('_CANNOT_FIND_INSTALL_FILE','Бекітіп тұратын файл табылған емес');
DEFINE('_XML_NOT_FOR','Бекітіп тұратын XML-файл - емес үшін');
DEFINE('_FILE_NOT_EXISTS','Файл бар болмайды');
DEFINE('_INSTALL_TWICE','Сіздер кеңейту бәз-баяғы екі рет орнатуға тырысаcыздар');
DEFINE('_ERROR_COPYING_FILE','Файл көшіріп алу қатесі');

/* administrator components com_jce */
DEFINE('_LANG_ALREADY_EXISTS','Тіл бар болады');
DEFINE('_EMPTY_LANG_ID','id бос тілдің, файлдар қашықтату');
DEFINE('_NO_PLUGIN_FILES','Плагиналардың файлдары жоқ болады');
DEFINE('_BAD_OBJECT_ID','id сенімсіз объектінің');
DEFINE('_EMPRY_DIR_NAME_CANNOT_DEL_FILE','Папка даласы бос, мүмкін емес файл қашықтату');
DEFINE('_INSTALLED_JCE_PLUGINS','JCE плагиналар анықталғандар');
DEFINE('_PCLZIP_UNKNOWN_ERROR','Неисправимая қате');
DEFINE('_UNZIP_ERROR','Түйіншекті шешу қатесі');
DEFINE('_JCE_INSTALL_ERROR_NO_XML','Қате: Мүмкін емес құру XML-файл JCE 1.1.x пакетте табу.');
DEFINE('_JCE_INSTALL_ERROR_NO_XML2','Қате: Құру XML-файл пакетте табу.');
DEFINE('_JCE_UNKNOWN_FILENAME','Файл атысы айқын емес');
DEFINE('_BAD_JCE_INSTALL_FILE','Құру дұрыссыз JCE файлы немесе оның дұрыссыз болжама.');
DEFINE('_WRONG_PLUGIN_VERSION','Дұрыссыз плагин болжамасы');
DEFINE('_ERROR_CREATING_DIRECTORY','Каталог жасау қатесі');
DEFINE('_INSTALLER_NOT_FIND_ELEMENT','Инсталлятор элементті анықтамады');
DEFINE('_NO_INSTALLER_FOR_COMPONENT','Инсталлятор элемент үшін қол жетпеу');
DEFINE('_NO_CHOOSED_FILES','Файлдар таңдалған емес');
DEFINE('_ERROR_OF_UPLOAD','Тиеу қатесі');
DEFINE('_UPLOADING','Тиеу');
DEFINE('_IS_SUCCESS','Табысты');
DEFINE('_HAS_ERROR','Қатемен аяқталынды');
DEFINE('_CANNOT_DELETE_LANG_FILE','Қолданылатын тілдік пакет болмайды алыстату');
DEFINE('_GUEST','Қонақ');
DEFINE('_EDITOR','Редактор');
DEFINE('_PUBLISHER','Бастырушы');
DEFINE('_MANAGER','Менеджер');
DEFINE('_ADMINISTRATOR','Басқарушы Әкімші');
DEFINE('_SUPER_ADMINISTRATOR','Супер-Басқарушы Әкімші');
DEFINE('_CHANGES_FOR_PLUGIN','Плагинаға арналған өзгертудің');
DEFINE('_SUCCESS_SAVE','Табысты сақтау');
DEFINE('_PLUGIN','Плагин');
DEFINE('_MODULE_IS_EDITING_BY_ADMIN','Модуль осы шақ басқа әкіммен редакциялайды');
DEFINE('_CHOOSE_PLUGIN_FOR_ACCESS_MANAGEMENT','Рұқсат құқықтарының тағайындауына арналған плагин қажетті таңдау');
DEFINE('_CHOOSE_PLUGIN_FOR','Плагинды таңдаңыздар үшін');
DEFINE('_JCE_CONFIG','Кескін үйлесімі JCE');
DEFINE('_EDITOR_CONFIG','Редактор кескін үйлесімі');
DEFINE('_EXTENSIONS','Кеңейтудің');
DEFINE('_EXTENSION_MANAGEMENT','Басқару кеңейтулермен');
DEFINE('_ICONS_POSITIONS','Белгілердің орналастыруы ');
DEFINE('_LANG_MANAGER',' Жайылтпаушылықтың менеджері');
DEFINE('_FILE_NOT_FOUND','Файлды табылған емес');
DEFINE('_PLUGIN_NOT_FOUND','Плагин табылған емес');
DEFINE('_JCE_CONTENT_MAMBOT_NOT_INSTALLED','JCE редактор Мамботы анықталған емес');
DEFINE('_ICONS_POSITIONS_SAVED','Белгілердің орналастыруы сақталған');
DEFINE('_MAIN_PAGE','Негізгі');
DEFINE('_NEW','Жаңа');
DEFINE('_INSTALLATION','Құру');
DEFINE('_PREVIEW','Предпросмотр');
DEFINE('_PLUGINS','Плагины');

/* administrator components com_jce */
DEFINE('_USERS','Пайдаланушылар');
DEFINE('_USER_LOGIN_TXT','Пайдаланушы атысы (логин)');
DEFINE('_LOGGED_IN','Сайтеге');
DEFINE('_ALLOWED','Рұқсат етілген');
DEFINE('_LAST_LOGIN','Соңғы қатысу');
DEFINE('_USER_BLOCK','Бітеу');
DEFINE('_ALLOW','Рұқсат беру');
DEFINE('_DISALLOW','Тиым салу');
DEFINE('_ENTER_LOGIN_PLEASE','Сіздер сайтқа кіруге арналған пайдаланушы атысы тиісті енгізу');
DEFINE('_BAD_USER_LOGIN','Кіруге арналған сіздердің атыңыз асырайды - символдар немесе өте қысқа.');
DEFINE('_ENTER_EMAIL_PLEASE','Сіздер e-mail мекенжайы тиісті енгізу');
DEFINE('_ENTER_GROUP_PLEASE','Сіздер рұқсат тобын пайдаланушыға тиісті тағайындау');
DEFINE('_BAD_PASSWORD','Дұрыссыз пароль');
DEFINE('_BAD_GROUP_1','Өтінем, басқа топты таңдаңыздар. Үлгі топтары `Public Front-end` болмайды таңдау');
DEFINE('_BAD_GROUP_2','Өтінем, басқа топты таңдаңыздар. Үлгі топтары `Public Back-end` болмайды таңдау');
DEFINE('_USER_INFO','Хабар пайдаланушы туралы');
DEFINE('_NEW_PASSWORD','Жаңа пароль');
DEFINE('_REPEAT_PASSWORD','Пароль тексеруі');
DEFINE('_BLOCK_USER','Пайдаланушыны қоршау');
DEFINE('_RECEIVE_EMAILS',' e-mail жүйелік хабарлаулар алу');
DEFINE('_REGISTRATION_DATE','тіркеу датасы');
DEFINE('_CONTACT_INFO','Түйіскен хабар');
DEFINE('_NO_USER_CONTACTS','Мына пайдаланушыда түйіскен хабарға жоқ:<br />толықтықтарға арналған қарайсыздар \'Компоненттер -> Контактілер -> Басқару контактілермен\'');
DEFINE('_FULL_NAME','Толық аты');
DEFINE('_CHANGE_CONTACT_INFO','Түйіскен хабарды өзгерту');
DEFINE('_CONTACT_INFO_PATH_URL','Компоненттер -> Контактілер -> Басқару контактілермен');
DEFINE('_RESTRICT_FUNCTION','Функционалдылық шек қойылған');
DEFINE('_NO_RIGHT_TO_CHANGE_GROUP','Сіздер пайдаланушылардың мына тобын өзгерти алмаcыздар. Мынау сайта негізгі әкімі тек қана істей алады');
DEFINE('_NO_RIGHT_TO_USER_CREATION','сіздер рұқсат мына деңгейімен пайдаланушыны жасай алмаcыздар. Мынау сайта негізгі әкімі тек қана істей алады');
DEFINE('_PROFILE_SAVE_SUCCESS','Пайдаланушы профиль өзгертулері табысты сақталған');
DEFINE('_CANNOT_DEL_ONE_SUPER_ADMIN','Сіздер негізгі әкімді, дәл осылай қалай мынаны қашықтата алмаcыздар ол сайта жалғыз негізгі әкімі ');
DEFINE('_CHOOSE_USER_TO','Пайдаланушыны таңдаңыздар үшін');
DEFINE('_PLEASE_CHOOSE_USER','Өтінем, пайдаланушыны таңдаңыздар');
DEFINE('_CANNOT_DISABLE_SUPER_ADMIN','Сіздер негізгі әкімді сөндіріп тастай алмаcыздар');
DEFINE('_THIS_CAN_DO_HIGHLEVEL_USERS','Мынау пайдаланушылар тек қана істей алады көбірек жоғары дәреже рұқсаттың');
DEFINE('_DISABLE','Сөндіріп тастау');

/* administrator components com_typedcontent */
DEFINE('_ACCESS','Рұқсат');
DEFINE('_LINKS_COUNT','Сілтемелердің');
DEFINE('_DATE_PUBL_END','Жариялау мезгілі ақты');
DEFINE('_CHANGE_STATIC_CONTENT','Статикалық ұсталушы өзгерту');
DEFINE('_WANT_TO_RESET_HITCOUNT','Сіздер қараулардың есепшісін нақты қалаңыздар обнулить? \nСақтамаған өзгерту кез келген мынаны ұсталушыны жоғалған болады...');
DEFINE('_CONTENT_OBJECT_MUST_HAVE_NAME','Ұсталушы объекті ат тиісті болу.');
DEFINE('_CONTENT_INFO','Хабар туралы ұсталушыда');
DEFINE('_O_STATE','Күй-жағдай');
DEFINE('_CHANGE_AUTHOR','Авторды өзгерту');
DEFINE('_GALLERY_IMAGES','Галерея бейнелеулері');
DEFINE('_CONTENT_IMAGES','Ұсталушы бейнелеудің');
DEFINE('_EDITING_SELECTED_IMAGE','Таңдалған бейнелеу редакциялауы');
DEFINE('_ALTERNATIVE_TEXT','Баламалық мәтін');
DEFINE('_MENU_LINK_3','Пункті үлгі менюі осында жасалады\'Сілтеме - Статикалық ұсталушы\', қайсы таңдалғанды меню тізімінен салу.');
DEFINE('_EXISTED_MENU_LINKS','Бар болу байланыстың менюмен');
DEFINE('_CONTENT_SAVED','Ұсталушы сақталған');
DEFINE('_CHOOSE_OBJECT_FOR','Объектіні таңдаңыздар үшін');
DEFINE('_O_SECCESS_PUBLISHED','Объектілерді табысты жарияланған');
DEFINE('_O_SUCCESS_UNPUBLISHED','Объектілердің табысты жасырылу');
DEFINE('_HIT_COUNT_RESETTED','Қараулардың есепшісі табысты обнулен ');
DEFINE('_SUCCESS_MENU_CR_1','(Сілтеме - Статикалық ұсталушы) менюде');

/* administrator components com_trash */
DEFINE('_TRASH','Кәрзеңке');
DEFINE('_OBJECT_DELETION','Объектілердің қашықтауы');
DEFINE('_OBJECTS_TO_DELETE','Алыстатылған объектілер');
DEFINE('_THIS_ACTION_WILL_DELETE_O_FOREVER','*Мынау әрекет <strong><font color="#FF0000">біржолата қашықтатады</font></strong><br />базадан саналған объектілер тап осылардың*');
DEFINE('_REALLY_DELETE_OBJECTS','Сіздер саналған объектілерді нақты қашықтатуды қалаңыздар? \n Мынау тап осы әрекет базадан саналған объектілер біржолата қашықтатады.');
DEFINE('_OBJECT_RESTORE','Объектілердің бұрынғы қалпына келуі');
DEFINE('_OBECTS_TO_RESTORE','Қалпына келтірілетін объектілер');
DEFINE('_THIS_ACTION_WILL_RESTORE_O_FOREVER','*Мынау әрекет <strong><font color="#FF0000">қалпына келтіреді</font></strong> олар содан соң бұрынғы орындарға қайтарылған болады, жарияланбаған объектілер сияқты*');
DEFINE('_REALLY_RESTORE_OBJECTS','Сіздер саналған объектілерді нақты қалпына келтіруді қалаңыздар?');
DEFINE('_RESTORE','Қалпына келтіру');
DEFINE('_CONTENT_ITEMS','Объектілер ұсталушыны');
DEFINE('_MENU_ITEMS','Меню пунктілері');
DEFINE('_OBJECTS_DELETED','Объекті табысты алысталған');
DEFINE('_SUCCESS_DELETION','Абысты алысталған');
DEFINE('_OBJECTS_RESTORED','Объекті табысты бұрынғы қалпына келтірген');
DEFINE('_CLEAR_TRASH','Кәрзеңкені тазалау');

/* administrator components com_templates */
DEFINE('_UNSUCCESS_OPERATION_NO_TEMPLATE','Операция сәтті емес: үлгі айқын емес.');
DEFINE('_UNSUCCESS_OPERATION_EMPTY_FILE','Операция сәтті емес: бос ұсталушы.');
DEFINE('_UNSUCCES_OPERAION','Операция сәтті емес');
DEFINE('_CANNOT_OPEN_FILE_DOR_WRITE','Жазуға арналған файл ашу қатесі.');
DEFINE('_NO_PREVIEW','Предпросмотр қол жетпеу');
DEFINE('_TEMPLATES','Үлгілер');
DEFINE('_TEMPLATE_PREVIEW','Үлгі предпросмотры');
DEFINE('_DEFAULT','Үндемеумен');
DEFINE('_ASSIGNED_TO','Белгіленген');
DEFINE('_MAKE_UNWRITEABLE_AFTER_SAVING','Сақтаудан кейін жазуға арналған қол жетпеу істеу.');
DEFINE('_IGNORE_WRITE_PROTECTION_WHEN_SAVE','Сақтау жанында жазудың қорғанышын елемеу.');
DEFINE('_CHANGE_EDITOR','Редактор өзгерту');
DEFINE('_CSS_TEMPLATE_EDITOR','Үлгі CSS редактор');
DEFINE('_ASSGIN_TEMPLATE_TO_MENU','Меню пунктілеріне арналған үлгі тағайындауы.');
DEFINE('_MODULES_POSITION','Модульдердің позициялары');
DEFINE('_INOGLOBAL_CONFIG_ONE_TEMPLATE_USING','Глобальды кескін үйлесімінде бір үлгінің қолдану таңдалған:');
DEFINE('_CANNOT_DELETE_THIS_TEMPLATE_WHEN_USING','Мынау үлгі қолданылады және мүмкін алысталған емес.');
DEFINE('_UNSUCCES_OPERATION_CANNOT_OPEN','Операция сәтті емес: мүмкін емес ашу');
DEFINE('_POSITIONS_SAVED','Позициялар сақталған');

/* menubar.html.old.php + menubar.html.php */
DEFINE('_BUTTON','Батырма');
DEFINE('_PLEASE_CHOOSE_ELEMENT','Өтінем, элементті таңдаңыздар.');
DEFINE('_PLEASE_CHOOSE_ELEMENT_FOR_PUBLICATION','Өтінем, сайтеге олардың жариялауына арналған объектілер тізімнен таңдаңыздар.');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_MAKE_DEFAULT','Өтінем, объектіні таңдаңыздар, үндемеумен оның тағайындау үшін.');
DEFINE('_ASSIGN','Тағайындау');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_UNPUBLISH','Жоюға арналған объекті жариялаулары, оның алдымен таңдаңыздар.');
DEFINE('_TO_ARCHIVE','Архивқа');
DEFINE('_FROM_ARCHIVE','Архивтан');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_ARCHIVE','Өтінем, олардың архивациисына арналған объектілер тізімнен таңдаңыздар.');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_UNARCHIVE','Архивтан оның бұрынғы қалпына келуге арналған объектіні таңдаңыздар.');
DEFINE('_CHANGE','Өзгерту');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_EDIT','Оның редакциялауына арналған тізімнен.');
DEFINE('_EDIT_HTML','HTML Редакциялау');
DEFINE('_EDIT_CSS','CSS редакциялау');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_DELETE','Оның қашықтауына арналған тізімнен объектіні таңдаңыздар.');
DEFINE('_REALLY_WANT_TO_DELETE_OBJECTS','Сіздер таңдалған объектілерді нақты қашықтатуды қалаңыздар?');
DEFINE('_REMOVE_TO_TRASH','Кәрзеңкеге');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_TRASH','Кәрзеңкеге оның ауыспалылыққа арналған тізімнен объектіні таңдаңыздар.');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_ASSIGN','Өтінем, оның объекті тағайындауына арналған таңдаңыздар.');
DEFINE('_HELP','Көмек');

/* administrator components com_languages */
DEFINE('_LANGUAGE_PACKS','Тілден жасалатын пакеттер');
DEFINE('_E_LANGUAGE','Тіл');
DEFINE('_LANGUAGE_EDITOR','Тіл редакторы');
DEFINE('_LANGUAGE_SAVED','Тіл табысты өзгертілген');
DEFINE('_YOU_CANNOT_DELETE_LANG_FILE','Сіздер қолданылатын тілдік файлды қашықтата алмаcыздар.');
DEFINE('_UNSUCCESS_OPERATION_NO_LANGUAGE','Операция сәтті емес: тіл айқын емес.');

/* administrator components com_linkeditor */
DEFINE('_COMPONENTS_MENU_EDITOR','Компоненттердің меню редакциялауы');
DEFINE('_ICON','Белгі');
DEFINE('_KERNEL','Түйін');
DEFINE('_COMPONENTS_MENU_EDIT','Компоненттердің меню пункті редакциялауы.');
DEFINE('_COMPONENTS_MENU_NEW','Компоненттердің меню жаңа пункті жасауы.');
DEFINE('_COMPONENT_IS_A_PART_OF_CMS','<b>Назар:</b> мынау компонент түйін бөлімімен келеді, дөрекі басқару жанында оларға жүйе жұмысында проблемаларды мүмкін.');
DEFINE('_MENU_NAME_REQUIRED','Меню пункті аты. Толтыруға арналған міндетті.');
DEFINE('_MENU_ITEM_ICON','Меню пункті белгісі');
DEFINE('_MENU_ITEM_DESCRIPTION','Меню пункті суреттеуі.');
DEFINE('_MENU_ITEM_LINK','Компонентке сілтеме. Егер дала анау меню пунктісі ауыстырып алуға асырамаса толтыруға арналған міндетті.');
DEFINE('_PARENT_MENU_ITEM','Әке-шешелік пункті ');
DEFINE('_PARENT_MENU_ITEM2','Меню әке-шешелік пунктісі. Ішіне салынушылық 1 деңгейі барлығы рұқсат етіледі.');
DEFINE('_THIS_FILEDS_REQUIRED','<font color="red">*</font> Пунктілер толтыру үшін міндетті.');
DEFINE('_MENU_ITEM_DELETED','Пункті меню удалён');
DEFINE('_FIRST_LEVEL','Бірінші деңгей');

/* administrator components com_mambots */
DEFINE('_MAMBOTS','Мамботы');
DEFINE('_MAMBOT_NAME','Мамбота аты');
DEFINE('_NO_MAMBOT_NAME','Мамбот ат тиісті болу');
DEFINE('_NO_MAMBOT_FILENAME','Мамбот файл атысы тиісті болу');
DEFINE('_SITE_MAMBOT','Мамбот сайта');
DEFINE('_MAMBOT_DETAILS','Мамбота бөлшектері ');
DEFINE('_USE_THIS_MAMBOT_FILE','Қолданылатын файл');
DEFINE('_MAMBOT_ORDER','Нөмір ретпен');
DEFINE('_NO_MAMBOT_PARAMS','<i>Параметрлер жоқ болады</i>');
DEFINE('_NEW_MAMBOTS_IN_THE_END','Жаңа объектілер үндемеумен соңыда орналасады. Орналастыру реті мына объекті сақтауынан кейін тек қана мүмкін өзгертілген.');
DEFINE('_CHOOSE_MAMBOT_FOR','Мамботты таңдаңыздар үшін');

/* administrator components com_massmail */
DEFINE('_PLEASE_ENTER_SUBJECT','Өтінем, тақырыпты тізімге кіргізіңіздер');
DEFINE('_PLEASE_CHOOSE_GROUP','Өтінем, топты таңдаңыздар');
DEFINE('_PLEASE_ENTER_MESSAGE','Өтінем, хабарлауды толтырыңыздар');
DEFINE('_MASSMAIL_TTILE','Пошта жіберуі');
DEFINE('_DETAILS','Бөлшектің');
DEFINE('_SEND_TO_SUBGROUPS','Бағынышты топтарға жіберу');
DEFINE('_SEND_IN_HTML','HTML-форматта жіберу');
DEFINE('_MAIL_SUBJECT','Тақырып');
DEFINE('_MESSAGE','Хабарлау');
DEFINE('_ALL_USER_GROUPS','- Арлық пайдаланушылардың топтары -');
DEFINE('_PLEASE_FILL_FORM','Өтінем, түрді сыпайылық толтырыңыздар');
DEFINE('_MESSAGE_SENDED_TO_USERS','e-mail пайдаланушыға(ларға) жүріп кеткен - ');

/* administrator components com_menumanager */
DEFINE('_MENU_MANAGER','Меню басқаруы');
DEFINE('_MENU_ITEMS_UNPUBLISHED','Жасырылу');
DEFINE('_MENU_MUDULES','Модульдердің');
DEFINE('_CHANGE_MENU_NAME','Меню аты өзгерту');
DEFINE('_CHANGE_MENU_ITEMS','Меню пунктілері өзгерту');
DEFINE('_PLEASE_ENTER_MENU_NAME','Өтінем, меню атын енгізіңіздер');
DEFINE('_NO_QUOTES_IN_NAME','Меню аты тиісті емес асырау \\\'');
DEFINE('_PLEASE_ENTER_MENU_MODULE_NAME','Өтінем, меню модуль атын енгізіңіздер');
DEFINE('_MENU_INFO','Хабар меню туралы');
DEFINE('_MENU_NAME_TIP','Мынау меню атысы жүйемен қолданылады үшін оның теңестірулер - ол бірегей тиісті болу. Ашық жерлерсіз ат қоюға ұсынылады');
DEFINE('_MODULE_TITLE_TIP','mod_mainmenu тақырыбысы требуется мына меню елестетуіне арналған');
DEFINE('_MODULE_TITLE','Модуль тақырыбысы');
DEFINE('_NEW_MENU_ITEM_TIP','*Меню жаңа модулі автоматты жасалған болады соң сіздер тақырыбы енгізесіздер, ал бүркеншектегі шегені содан соң басаcыздар "Сақтау".*<br /><br />Бөлімде қолайлы жасалған модуль параметрлерінің редакциялауы болады \'басқарудың модульдермен [сайт]\': Модульдер -> Сайта модульдері');
DEFINE('_REMOVE_MENU','Меню қашықтату');
DEFINE('_MODULES_TO_REMOVE','Модуль қашықтауға арналған ');
DEFINE('_MENU_ITEMS_TO_REMOVE','Меню алыстатылған пунктілері');
DEFINE('_THIS_OP_REMOVES_MENU','*Мынау операция <strong><font color="#FF0000"алыстатады</font></strong> мынау меню,<br />барлық оның пунктілер және модуль, белгіленген оған*');
DEFINE('_REALLY_DELETE_MENU','Сіздер сенім , не меню мынау қашықтатуды қалаңыздар? \n Меню қашықтауы, оның пунктілердің және модульдердің болады.');
DEFINE('_PLEASE_ENTER_MENY_COPY_NAME','Өтінем, меню көшірмесіне арналған атыны енгізіңіздер');
DEFINE('_PLEASE_ENTER_MODULE_NAME','Өтінем, жаңа модульге арналған атыны енгізіңіздер');
DEFINE('_MENU_COPYING','Меню көшіріп алуы');
DEFINE('_NEW_MENU_NAME','Менюмен жаңа аты');
DEFINE('_NEW_MODULE_NAME','Жаңа модуль атысы');
DEFINE('_MENU_TO_COPY','Менюмен көшірілетін');
DEFINE('_MENU_ITEMS_TO_COPY','Меню көшірілетін пунктілері');
DEFINE('_CANNOT_RENAME_MAINMENU','Сіздер менюді қайта ат қоя алмаcыздар \\\'mainmenu\\\', дәл осылай қалай мынау Joomla дұрыс жұмыс жасауы бұзады');
DEFINE('_MENU_ALREADY_EXISTS','Менюмен сондай атпен бар болады. Сіздер меню бірегей атысы тиісті енгізу');
DEFINE('_NEW_MENU_CREATED','Менюмен жаңа жасалған');
DEFINE('_MENU_ITEMS_AND_MODULES_UPDATED','Меню пунктілері және модульдер жаңартылған');
DEFINE('_MENU_DELETED','Меню алысталған');
DEFINE('_NEW_MENU','Менюмен жаңа');
DEFINE('_NEW_MENU_MODULE','Меню жаңа модулі');

/* administrator components com_menus */
DEFINE('_MODULE_IS_EDITING_MY_ADMIN','Модуль осы шақ редакциялайды басқа әкіммен');
DEFINE('_LINK_MUST_HAVE_NAME','Сілтеме аты тиісті болу');
DEFINE('_CHOOSE_COMPONENT_FOR_LINK','Сіздер оған сілтеме жасауына арналған компонент тиісті таңдау');
DEFINE('_MENU_ITEM_COMPONENT_LINK','Меню пунктісі :: Сілтеме - Объекті компонентті');
DEFINE('_LINK_TITLE','Сілтеме title');
DEFINE('_LINK_COMPONENT','Сілтемеге арналған компонент');
DEFINE('_LINK_TARGET','Басу жанында ашу');
DEFINE('_OBJECT_MUST_HAVE_NAME','Объекті аты тиісті болу');
DEFINE('_CHOOSE_COMPONENT','Компонентті таңдаңыздар');
DEFINE('_MENU_ITEM_COMPONENT','Меню пунктісі :: компонент');
DEFINE('_MENU_PARAMS_AFTER_SAVE','Меню пунктісі сақтауынан кейін тек қана параметрлердің тізімі қолайлы болады');
DEFINE('_MENU_ITEM_TABLE_CONTACT_CATEGORY','Меню пунктісі :: категория кесте - контактілері');
DEFINE('_CATEGORY_TITLE_IF_FILED_IS_EMPTY','Егер бос дала қалдырылған болса, онда категория аты автоматты қолданған болады');
DEFINE('_CHOOSE_CONTACT_FOR_LINK','Сілтеме жасауына арналған контакті қажетті таңдау');
DEFINE('_MENU_ITEM_CONTACT_OBJECT','Меню пунктісі :: Сілтеме - Контакті объектісі');
DEFINE('_MENU_ITEM_BLOG_CATEGORY_ARCHIVE','Меню пунктісі :: Блог - Ұсталушы архивта категорияның');
DEFINE('_MENU_ITEM_BLOG_SECTION_ARCHIVE','Меню пунктісі :: Архивта Блог - Ұсталушы бөлімнің');
DEFINE('_SECTION_TITLE_IF_FILED_IS_EMPTY','Егер бос дала қалдырылған болса, онда бөлім аты автоматты қолданған болады');
DEFINE('_MENU_ITEM_SAVED','меню пунктісі сохранен');
DEFINE('_MENU_ITEM_BLOGCATEGORY','Меню пунктісі :: Блог - Ұсталушы категорияның');
DEFINE('_YOU_CAN_CHOOSE_SEVERAL_CATEGORIES','Сіздер бірнеше категорияның таңдай алаcыздар');
DEFINE('_MENU_ITEM_BLOG_CONTENT_CATEGORY','Меню пунктісі :: Блог - Ұсталушы бөлімнің');
DEFINE('_YOU_CAN_CHOOSE_SEVERAL_SECTIONS','Сіздер бірнеше бөлімнің таңдай алаcыздар');
DEFINE('_MENU_ITEM_TABLE_CONTENT_CATEGORY', ' Меню пунктісі :: кесте - ұсталушы категорияның ');
DEFINE('_CHANGE_CONTENT_ITEM', ' Ұсталушы объекті өзгерту ');
DEFINE('_CONTENT_ITEM_TO_LINK_TO', Байланысқа арналған мақаланы таңдаңыздар);
DEFINE('_MENU_ITEM_CONTENT_ITEM','Меню пунктісі :: ұсталушы Сілтеме - объекті ');
DEFINE('_CONTENT_TO_LINK_TO',' Ұсталушы байланысқа арналған');
DEFINE('_MENU_ITEM_TABLE_CONTENT_SECTION',' Меню пунктісі :: кесте - ұсталушы бөлімнің');
DEFINE('_CHOOSE_OBJECT_TO_LINK_TO',' Сіздер байланысқа арналған объекті тиісті таңдау оларға);
DEFINE('_MENU_ITEM_STATIC_CONTENT',' Меню пунктісі :: Сілтеме - статикалық ұсталушы);
DEFINE('_MENU_ITEM_CATEGORY_NEWSFEEDS',' Меню пунктісі :: категориядан жаңалықтардың кесте - баулары');
DEFINE('_CHOOSE_NEWSFEED_TO_LINK',' Сіздер меню пунктісімен байланысқа арналған жаңалықтардың бауын тиісті таңдау');
DEFINE('_MENU_ITEM_NEWSFEED',' Меню пунктісі :: Сілтеме - жаңалықтардың бауы');
DEFINE('_LINKED_TO_NEWSFEED',' Баумен байлаулы');
DEFINE('_MENU_ITEM_SEPARATOR',' Меню пунктісі :: бөлгіш | толтырғыш');
DEFINE('_ENTER_URL_PLEASE',' Сіздер url тиісті енгізу');
DEFINE('_MENU_ITEM_URL',' Меню пунктісі :: Сілтеме - URL');
DEFINE('_MENU_ITEM_WEBLINKS_CATEGORY',' Меню пунктісі :: категория кесте - Web-сілтемелері');
DEFINE('_MENU_ITEM_WRAPPER',' Меню пунктісі :: Wrapper');
DEFINE('_WRAPPER_LINK','Сілтеме Wrapper\'a');
DEFINE('_MAXIMUM_LEVELS',' Деңгейлер барынша көп ');
DEFINE('_NOTE_MENU_ITEMS1','*  Іреді назар, меню не - пунктілері ықылас білдіріңіздер бірнеше топтың, бірақ олар меню үлгісіне біреуінеге жатады.');
DEFINE('_MENU_ITEMS_OTHER',' Әр түрлі ');
DEFINE('_MENU_ITEMS_SEND',' жіберу ');
DEFINE('_COMPONENTS',' Компоненттер');
DEFINE('_LINKS',' Сілтеменің');
DEFINE('_MOVE_MENU_ITEMS',' Меню пунктілерінің ауыспалылығы');
DEFINE('_MENU_ITEMS_TO_MOVE',' Меню ауыстырылатын пунктілері');
DEFINE('_COPY_MENU_ITEMS',' Меню пунктілерінің көшіріп алуы');
DEFINE('_COPY_MENU_ITEMS_TO',' Менюде көшіру');
DEFINE('_CHANGE_THIS_NEWSFEED',' Жаңалықтардың мына бауын өзгерту');
DEFINE('_CHANGE_THIS_CONTACT',' Контакті мынау өзгерту');
DEFINE('_CHANGE_THIS_CONTENT',' Ұсталушы мынау өзгерту');
DEFINE('_CHANGE_THIS_STATIC_CONTENT',' Статикалық ұсталушы мынау өзгерту');
DEFINE('_MENU_NEXT','Онан әрі');
DEFINE('_MENU_BACK',' Артқа');

/* administrator components com_messages */
DEFINE('_PRIVATE_MESSAGES','Жеке хабарлаудың');
DEFINE('_MAIL_FROM','От');
DEFINE('_MAIL_READED','Оқып шығылған');
DEFINE('_MAIL_NOT_READED','Оқып шығылған емес');
DEFINE('_PRIVATE_MESSAGES_SETTINGS','Жеке хабарлаулардың күйге келтірулері');
DEFINE('_BLOCK_INCOMING_MAIL','Кірушіге қоршау почту');
DEFINE('_SEND_NEW_MESSAGES','Жаңа хабарлаудың маған жіберу');
DEFINE('_AUTO_PURGE_MESSAGES','Хабарлаулардың автоматты тазалауы');
DEFINE('_AUTO_PURGE_MESSAGES2','Үлкен');
DEFINE('_AUTO_PURGE_MESSAGES3','Күн');
DEFINE('_VIEW_PRIVATE_MESSAGES','Арнайы хабарлаулардың қарауы');
DEFINE('_MESSAGE_SEND_DATE','Жүріп кеткен');
DEFINE('_PLEASE_ENTER_MAIL_SUBJECT','Сіздер тақырып аты тиісті енгізу.');
DEFINE('_PLEASE_ENTER_MESSAGE_BODY','Сіздер хабарлау мәтіні тиісті енгізу.');
DEFINE('_PLEASE_ENTER_USER','Сіздер алушыны тиісті таңдау');
DEFINE('_NEW_PERSONAL_MESSAGE','Жаңа арнайы хабарлау');
DEFINE('_MAIL_TO','Кімге');
DEFINE('_MAIL_ANSWER','Жауап беру');

/* administrator components com_syndicate */
DEFINE('_NEWS_EXPORT_SETUP','Жаңалықтардың экспорт күйге келтірулері');
DEFINE('_RSS_EXPORT','RSS экспорт');
DEFINE('_RSS_EXPORT_SETUP','Басқару жаңалықтардың экспорт күйге келтірулерімен');

/* administrator components com_statistics */
DEFINE('_STAT_BROWSERS_AND_OSES','Статистика браузераммен, ОС және домендерге');
DEFINE('_BROWSERS','Браузеры');
DEFINE('_DOMAINS','Домендер');
DEFINE('_DOMAIN','Домен');
DEFINE('_PAGES_HITS','Беттердің қатысу статистикасы');
DEFINE('_CONTENT_TITLE','Тақырыбы ұсталушыны');
DEFINE('_SEARCH_QUERIES','Іздеу сауалдар');
DEFINE('_LOG_SEARCH_QUERIES','Жинау тап осылардың');
DEFINE('_DISALLOWED','Тиым салынған');
DEFINE('_LOG_LOW_PERFOMANCE','Мына параметр активтендіруі сайта өнімділігін өте күшті төмендете алады үлкен қатысуының.');
DEFINE('_HIDE_SEARCH_RESULTS','Іздеу нәтижелері жасыру');
DEFINE('_SHOW_SEARCH_RESULTS','Іздеу қорытындылауы');
DEFINE('_SEARCH_QUERY_TEXT','Іздеу мәтіні');
DEFINE('_SEARCH_QUERY_COUNT','Сауалдардың');
DEFINE('_SHOW_RESULTS','Нәтижелерді берілген');

/* administrator components com_quickicons */
DEFINE('_QUICK_BUTTONS','Жылдам рұқсат бүркеншектегі шегелері');
DEFINE('_DISPLAY_METHOD','Елестету');
DEFINE('_DISPLAY_ONLY_TEXT','Мәтін тек қана');
DEFINE('_DISPLAY_ONLY_ICON','Белгі тек қана');
DEFINE('_DISPLAY_TEXT_AND_ICON','Белгі және мәтін');
DEFINE('_PRESS_TO_EDIT_ELEMENT','Элемент редакциялауы үшін басыңыздар');
DEFINE('_EDIT_BUTTON','Бүркеншектегі шеге редакциялауы');
DEFINE('_BUTTON_TEXT','Бүркеншектегі шеге мәтіні');
DEFINE('_BUTTON_TITLE','Сыбыр сөз');
DEFINE('_BUTTON_TITLE_TIP','<strong>Опционально</strong><br />сіздер осында қалқып шығушы сыбыр сөзге арналған мәтінді анықтай алаcыздар.<br />Мынау қасиет өте маңызды толтыру егер сіздер сурет тек қана елестетуді таңдасаңыздар!');
DEFINE('_BUTTON_LINK_TIP','Сайта деп атауға арналған сілтеме немесе компонентті.<br />Компоненттерге арналған ұқсас жүйе ішінде сілтеме тиісті болу:<br />index2.php?option=com_joomlastats&task=stats  [ joomlastats - компонент, &task=stats определённой компонентті шақыру ].<br />Сыртқы сілтемелер тиісті болу</strong> (мысалы: http://www...)!');
DEFINE('_BUTTON_LINK_IN_NEW_WINDOW','Жаңа терезеде');
DEFINE('_BUTTON_LINK_IN_NEW_WINDOW_TIP','Сілтеме жаңа терезеде ашық болады');
DEFINE('_BUTTON_ORDER','Жайғастыру кейін');
DEFINE('_BUTTONS_TAB_GENERAL','Жалпы');
DEFINE('_BUTTONS_TAB_DISPLAY','Елестету');
DEFINE('_DISPLAY_BUTTON','Суреттеу');
DEFINE('_PRESS_TO_CHOOSE_ICON','Сурет таңдауы үшін басыңыздар (жаңа терезеде ашылады)');
DEFINE('_CHOOSE_ICON','Суретті таңдау');
DEFINE('_CHOOSE_ICON_TIP','өтінем, мына бүркеншектегі шегеге арналған суретті таңдаңыздар. Егер бүркеншектегі шегеге арналған өзіне меншікті суретті толтыра артуды қаласа, онда ол тиісті толтырылған болу ../administrator/images - ../images ../images/icons');
DEFINE('_PLEASE_ENTER_NUTTON_LINK','Сурет требуется');
DEFINE('_PLEASE_ENTER_BUTTON_TEXT','Пөтінем, дала мәтінді толтырыңыздар');
DEFINE('_BUTTON_ERROR_PUBLISHING','Жариялау қатесі');
DEFINE('_BUTTON_ERROR_UNPUBLISHING','Қате скрытия');
DEFINE('_BUTTONS_DELETED','Бүркеншектегі шегелер табысты алысталған');
DEFINE('_CHANGE_QUICK_BUTTONS','Жылдам рұқсат бүркеншектегі шегелері өзгерту');

/* administrator components com_sections */
DEFINE('_SECTION_CHANGES_SAVED','Бөлім өзгертулері сақталған');
DEFINE('_CONTENT_SECTIONS','Бөлімдер ұсталушыны');
DEFINE('_SECTION_NAME','Бөлім аты');
DEFINE('_SECTION_CATEGORIES','Категориялардың');
DEFINE('_SECTION_CONTENT_ITEMS','Белсенділердің');
DEFINE('_SECTION_CONTENT_ITEMS_IN_TRASH','Кәрзеңкеде');
DEFINE('_VIEW_SECTION_CATEGORIES','Бөлім категорияларының қарауы');
DEFINE('_VIEW_SECTION_CONTENT','Ұсталушы бөлім қарауы');
DEFINE('_NEW_SECTION_MASK','Жаңа бөлім');
DEFINE('_CHOOSE_MENU_ITEM_NAME','Өтінем, меню мына пунктісіне арналған атыны енгізіңіздер');
DEFINE('_ENTER_SECTION_NAME','Бөлім ат тиісті болу');
DEFINE('_ENTER_SECTION_TITLE','Бөлім тақырыбы тиісті болу');
DEFINE('_SECTION_ALREADY_EXISTS','Сондай атпен бөлім болады. Өтінем, бөлім атын өзгертіңіздер.');
DEFINE('_SECTION_DETAILS','Бөлім бөлшектері');
DEFINE('_SECTION_USED_IN','Қолданылады');
DEFINE('_MENU_SHORT_NAME','Менюге арналған қысқа аты');
DEFINE('_SECTION_NAME_OF_CATEGORY','Категорияның');
DEFINE('_SECTION_NAME_OF_SECTION','Бөлімнің');
DEFINE('_SECTION_NAME_TIP','Ұзын ат, елестету тақырыбыларда');
DEFINE('_SECTION_NEW_MENU_LINK','Мынау функция жаңа пунктіні жасайды таңдалғанда сіздермен меню');
DEFINE('_CHOOSE_MENU','Менюді таңдаңыздар');
DEFINE('_CHOOSE_MENU_TYPE','Меню үлгісін таңдаңыздар');
DEFINE('_SECTION_COPYING','Бөлім көшіріп алуы');
DEFINE('_SECTION_COPY_NAME','Бөлім көшірме аты');
DEFINE('_SECTION_COPY_DESCRIPTION','Жаңадан жасалған бөлім болады<br />саналған категорияларды көшіріп алынған<br />және барлық саналған объектілер<br />ұсталушыны категориялардың.');
DEFINE('_MASS_CONTENT_ADD','Бұқаралық қосу');
DEFINE('_NEW_CAT_SECTION_ON_NEW_LINE','Әрбір жаңа категория/бөлім жол жаңасымен тиісті басталу');
DEFINE('_MASS_ADD_AS','Қалай қосу');
DEFINE('_SECTIONS','Бөлімдер');
DEFINE('_CATEGORIES','Категорияның');
DEFINE('_CATEGORIES_WILL_BE_IN_SECTION','Категориялар бөлімге жатамын');
DEFINE('_CONTENT_WILL_BE_IN_CATEGORY','Ұсталушы категориялар жатады');
DEFINE('_ADD_SECTIONS_RESULT','Бөлімдердің қосу нәтижесі');
DEFINE('_ADD_CATEGORIES_RESULT','Категориялардың қосу нәтижесі');
DEFINE('_ADD_CONTENT_RESULT','Ұсталушы қосу нәтижесі');
DEFINE('_ERROR_WHEN_ADDING','Қосу жанында қате болды');
DEFINE('_SECTION_IS_BEING_EDITED_BY_ADMIN','Бөлім осы шақ редакциялайды басқа әкіммен');
DEFINE('_SECTION_TABLE','Бөлім кестесі');
DEFINE('_SECTION_BLOG','Бөлім блогі');
DEFINE('_SECTION_BLOG_ARCHIVE','Бөлім архив блогі');
DEFINE('_SECTION_SAVED','Бөлім сохранен');
DEFINE('_CHOOSE_SECTION_TO_DELETE','Қашықтауға арналған бөлімді таңдаңыздар');
DEFINE('_CANNOT_DELETE_NOT_EMPTY_SECTIONS','Бөлімдер алысталған бола алмайды, дәл осылай қалай категорияларды асырайды');
DEFINE('_CHOOSE_SECTION_FOR','Бөлімді таңдаңыздар үшін');
DEFINE('_CANNOT_PUBLISH_EMPTY_SECTION','Мүмкін емес бос бөлім жариялау');
DEFINE('_SECTION_CONTENT_COPYED','Таңдалған ұсталушы бөлімді бөлімге көшіріп алынған болатын');
DEFINE('_MENU_MASS_ADD','Тағы қосу');

/* administrator components com_poll */
DEFINE('_POLLS','Сұрақтар');
DEFINE('_POLL_HEADER','Сұрақ тақырыбысы');
DEFINE('_POLL_LAG','Тоқтау');
DEFINE('_CHANGE_POLL','Сұрақ өзгерту');
DEFINE('_ENTER_POLL_NAME','Сұрақ ат тиісті болу');
DEFINE('_ENTER_POLL_LAG','Ноль жауаптар аралық тоқтау тиісті болу');
DEFINE('_POLL_DETAILS','Сұрақ бөлшектері');
DEFINE('_POLL_LAG_QUESIONS','Жауаптар аралық тоқтау');
DEFINE('_POLL_LAG_QUESIONS2','Дауыстардың қабыл алуы аралық секунды');
DEFINE('_POLL_OPTIONS','Жауаптардың түрлері');
DEFINE('_POLL_IS_BEING_EDITED_BY_ADMIN','Сұрақ осы шақ редакциялайды басқа әкіммен');

/* administrator components com_newsfeeds */
DEFINE('_NEWSFEEDS_MANAGEMENT','Басқару жаңалықтардың бауларымен');
DEFINE('_NEWSFEED_TITLE','Жаңалықтардың бауы');
DEFINE('_NEWSFEED_ON_SITE','Сайтеге');
DEFINE('_NEWSFEEDS_NUM_OF_CONTENT_ITEMS','маҚалалардың саны');
DEFINE('_NEWSFEED_CACHE_TIME','Кэша уақыты (секундылардың)');
DEFINE('_CHANGE_NEWSFEED','Жаңалықтардың бауын өзгерту.');
DEFINE('_PLEASE_ENTER_NEWSFEED_NAME','Өтінем, бау атын енгізіңіздер.');
DEFINE('_PLEASE_ENTER_NEWSFEED_LINK','Өтінем, жаңалықтардың бау сілтемесін енгізіңіздер.');
DEFINE('_PLEASE_ENTER_NEWSFEED_NUM_OF_CONTENT_ITEMS','Өтінем, елестетуге арналған мақалалардың санын енгізіңіздер.');
DEFINE('_PLEASE_ENTER_NEWSFEED_CACHE_TIME','Өтінем, кэша жаңарту уақытын енгізіңіздер.');
DEFINE('_NEWSFEED_LINK','Сілтеме');
DEFINE('_NEWSFEED_DECODE_FROM_UTF','UTF-8 қайтадан кодпен жазу');

/* administrator components com_modules */
DEFINE('_ALL_MODULE_CHANGES_SAVED','Барлық модуль өзгертулері табысты сақталған');
DEFINE('_MODULES','Модульдер');
DEFINE('_MODULE_NAME','Модуль аты');
DEFINE('_MODULE_POSITION','Позиция');
DEFINE('_ASSIGN_TO_URL','URL бау');
DEFINE('_ASSIGN_TO_URL_TIP','Үлгі:<br><br>!option=com_content&task=view&id=4<br>option=com_content&task=view<br>option=com_content&task=blogcategory&id>4<br><br>! - Бұларды беттерде модуль суреттелмейді<br>= < > != бірдей, азырақ, көбірек, сандық параметрлерге арналған салыстыру бірдей - белгілері емес.');
DEFINE('_MODULE_PAGES','Беттің');
DEFINE('_MODULE_PAGES_SOME','Некоторые');
DEFINE('_SHOW_TITLE','Тақырыбы көрсету');
DEFINE('_MODULE_ORDER','Модуль реті');
DEFINE('_MODULE_PAGE_MENU_ITEMS','Беттің / Меню пунктілері');
DEFINE('_MODULE_USER_CONTENT','Пайдаланушылық код / ұсталушы модульдің');
DEFINE('_MODULE_COPIED','Модуль көшіріп алынған');
DEFINE('_CANNOT_DELETE_MOD_MAINMENU','Сіздер mod_ mainmenu модулін қашықтата алмаcыздар, елестету қалай \\\'mainmenu\\\', дәл осылай қалай мынау меню түйіні');
DEFINE('_CANNOT_DELETE_MODULES','Модульдер алысталған бола алмайды, дәл осылай қалай  олар деинсталлированы тек қана бола алады, қалай барлық Joomla! модульдері');
DEFINE('_PREVIEW_ONLY_CREATED_MODULES','Сіздер тек қана көріп шыға алаcыздар `жасалғандар` модульдер');

/* administrator components com_modules */
DEFINE('_WEBLINKS_MANAGEMENT','Web-сілтемелермен басқару ');
DEFINE('_WEBLINKS_HITS','Асулардың');
DEFINE('_CHANGE_WEBLINK','Web-сілтемені өзгерту');
DEFINE('_ENTER_WEBLINK_TITLE','Web-сілтеме тақырыбы тиісті болу');
DEFINE('_PLEASE_ENTER_URL','Сіздер URL тиісті енгізу');
DEFINE('_WEBLINK_URL','Web-Сілтеме');
DEFINE('_WEBLINK_NAME','Ат');

/* administrator components com_jwmmxtd */
DEFINE('_RENAME','Қайта ат қою');
DEFINE('_JWMM_DIRECTORIES','Каталогтердің');
DEFINE('_JWMM_FILES','Файлдардың');
DEFINE('_JWMM_MOVE','Басқаша орналастыру');
DEFINE('_JWMM_BYTES','Байт');
DEFINE('_JWMM_KBYTES','Кб');
DEFINE('_JWMM_MBYTES','Мб');
DEFINE('_JWMM_DELETE_FILE_CONFIRM','Файл қашықтату');
DEFINE('_CLICK_TO_PREVIEW','Қарау үшін басыңыздар');
DEFINE('_JWMM_FILESIZE','Мөлшер');
DEFINE('_WIDTH','Ен');
DEFINE('_HEIGHT','Биік');
DEFINE('_UNPACK','Буманы шешу');
DEFINE('_JWMM_VIDEO_FILE','Файл бейне');
DEFINE('_JWMM_HACK_ATTEMPT','Бұзу әрекеті...');
DEFINE('_JWMM_DIRECTORY_NOT_EMPTY','Каталог емес бос. Өтінем, ұсталушы алдымен қашықтатыңыздар каталог ішінде!');
DEFINE('_JWMM_DELETE_CATALOG','Каталог қашықтату');
DEFINE('_JWMM_SAFE_MODE_WARNING','SAFE MODE Активтенген параметрі жанында каталогтердің жасауымен проблемаларды мүмкін');
DEFINE('_JWMM_CATALOG_CREATED','Каталог жасалған');
DEFINE('_JWMM_CATALOG_NOT_CREATED','Каталог емес жасалған');
DEFINE('_JWMM_FILE_DELETED','Файл табысты қашықтатты ');
DEFINE('_JWMM_FILE_NOT_DELETED','Файл емес қашықтатты ');
DEFINE('_JWMM_DIRECTORY_DELETED','Каталог қашықтатты ');
DEFINE('_JWMM_DIRECTORY_NOT_DELETED','Каталог емес қашықтатты ');
DEFINE('_JWMM_RENAMED','Жанадан аталған');
DEFINE('_JWMM_NOT_RENAMED','Жанадан аталған емес');
DEFINE('_JWMM_COPIED','Көшіріп алынған');
DEFINE('_JWMM_NOT_COPIED','Көшіріп алынған емес');
DEFINE('_JWMM_FILE_MOVED','Файл перемещён');
DEFINE('_JWMM_FILE_NOT_MOVED','Файл емес перемещён');
DEFINE('_TMP_DIR_CLEANED','Уақытша каталог толық тазаланған');
DEFINE('_TMP_DIR_NOT_CLEANED','Уақытша каталог тазаланған емес');
DEFINE('_FILES_UNPACKED','Файл шешілген');
DEFINE('_ERROR_WHEN_UNPACKING','Түйіншекті шешу қатесі');
DEFINE('_FILE_IS_NOT_A_ZIP','Файл архивпен сыпайылық zip келмейді');
DEFINE('_FILE_NOT_EXIST','Файл бар болмайды');
DEFINE('_IMAGE_SAVED_AS','Қалай редакцияланған бейнелеу сақталған');
DEFINE('_IMAGE_NOT_SAVED','Бейнелеу сақталған емес');
DEFINE('_FILES_NOT_UPLOADED','Файл серверге толтырылған емес');
DEFINE('_FILES_UPLOADED','Файлдар толтырылған');
DEFINE('_DIRECTORIES','Каталогтер');
DEFINE('_JWMM_FILE_NAME_WARNING','Өтінем, ашық жерлер және спецсимволыларда аттарда қолдану');
DEFINE('_JWMM_MEDIA_MANAGER','Медиа менеджер');
DEFINE('_JWMM_CREATE_DIRECTORY','Каталог жасау');
DEFINE('_UPLOAD_FILE','Файл толтыра арту');
DEFINE('_JWMM_FILE_PATH','Тұрған жері');
DEFINE('_JWMM_UP_TO_DIRECTORY','Жоғарырақ каталогке өту');
DEFINE('_JWMM_RENAMING','Қайта ат қою');
DEFINE('_JWMM_NEW_NAME','Жаңа аты (кеңейту қоса!)');
DEFINE('_CHOOSE_DIR_TO_COPY','Көшіріп алуға арналған каталогті таңдаңыздар');
DEFINE('_JWMM_COPY_TO','Көшіру');
DEFINE('_CHOOSE_DIR_TO_MOVE','Ауыспалылыққа арналған каталогті таңдаңыздар');
DEFINE('_JWMM_MOVE_TO','Басқаша орналастыру');
DEFINE('_CHOOSE_DIR_TO_UNPACK','Түйіншекті шешуге арналған каталогті таңдаңыздар');
DEFINE('_DERICTORY_TO_UNPACK','Түйіншекті шешу каталогі');
DEFINE('_NUMBER_OF_IMAGES_IN_TMP_DIR','Уақытша каталогте бейнелеулердің саны');
DEFINE('_CLEAR_DIRECTORY','Каталог тазалау');
DEFINE('_JWMM_ERROR_EDIT_FILE','Қате файл өңдеуі жанында');
DEFINE('_JWMM_EDIT_IMAGE','Бейнелеу редакциялауы');
DEFINE('_JWMM_IMAGE_RESIZE','Бейнелеу кеңейтуі');
DEFINE('_JWMM_IMAGE_CROP','Кесу');
DEFINE('_JWMM_IMAGE_SIZE','Мөлшерлер');
DEFINE('_JWMM_X_Y_POSITION','X және Y координаты');
DEFINE('_JWMM_BY_HEIGHT','Тіктің');
DEFINE('_JWMM_BY_WIDTH','Горизонтальдің');
DEFINE('_JWMM_CROP_TOP','Үстіңгі жағынан');
DEFINE('_JWMM_CROP_LEFT','Сол жағында');
DEFINE('_JWMM_CROP_RIGHT','Оңңан');
DEFINE('_JWMM_CROP_BOTTOM','Төмен жағынан');
DEFINE('_JWMM_ROTATION','Бұрылу');
DEFINE('_JWMM_CHOOSE','-- Таңдау --');
DEFINE('_JWMM_MIRROR','Тойтарыс');
DEFINE('_JWMM_VERICAL','Тіктің');
DEFINE('_JWMM_HORIZONTAL','Горизонтальдің');
DEFINE('_JWMM_GRADIENT_BORDER','Градиентті рамка');
DEFINE('_JWMM_SIZE_PX','px мөлшері');
DEFINE('_JWMM_TOP_LEFT','Үстіңгі жағынан-Сол жағында');
DEFINE('_JWMM_PRESS_TO_CHOOSE_COLOR','түс Таңдауы үшін басыңыздар');
DEFINE('_JWMM_BOTTOM_RIGHT','Оңңан-төмен жағынан');
DEFINE('_JWMM_BORDER','Жиек');
DEFINE('_COLOR','Түс');
DEFINE('_JWMM_ALL_BORDERS','Барлық жиектер');
DEFINE('_JWMM_TOP','Үстіңгі жағынан');
DEFINE('_JWMM_LEFT','Сол жағында');
DEFINE('_JWMM_RIGHT','Оңңан');
DEFINE('_JWMM_BOTTOM','Төмен жағынан');
DEFINE('_JWMM_BRIGHTNESS','Жарықтық');
DEFINE('_JWMM_CONTRAST','Қарама-қарсылық');
DEFINE('_JWMM_ADDITIONAL_ACTIONS','Қосымша әрекеттің');
DEFINE('_JWMM_GRAY_SCALE','Сұр градиент');
DEFINE('_JWMM_NEGATIVE','Негатив');
DEFINE('_JWMM_ADD_TEXT','Мәтін қосу');
DEFINE('_JWMM_TEXT','Мәтін');
DEFINE('_JWMM_TEXT_COLOR','Мәтін түсі');
DEFINE('_JWMM_TEXT_FONT','Мәтін әрібі');
DEFINE('_JWMM_TEXT_SIZE','Мәтін мөлшері');
DEFINE('_JWMM_ORIENTATION','Хабардар болу');
DEFINE('_JWMM_BG_COLOR','Фон түсі');
DEFINE('_JWMM_XY_POSITION',' Орналастыру X және Y');
DEFINE('_JWMM_XY_PADDING',' Бос жерлер X және Y');
DEFINE('_JWMM_FIRST','Бірінші');
DEFINE('_JWMM_SECOND','Екінші');
DEFINE('_JWMM_THIRDTH','Үшінші...');
DEFINE('_JWMM_CANCEL_ALL','Өзгерту всё');

/* administrator components com_joomlaxplorer */
DEFINE('_MENU_GZIP','Буып-түю');
DEFINE('_MENU_MOVE','Басқаша орналастыру');
DEFINE('_MENU_CHMOD','Құқықтардың алмастыруы');

/* administrator components com_joomlapack */
DEFINE('_JP_BACKUPPING','Сақтау');
DEFINE('_JP_PHPINFO','--- Хабар туралы PHP ---');
DEFINE('_JP_FREEMEMORY','Жад азат');
DEFINE('_JP_GZIP_ENABLED','GZIP Қысу : қолайлы (мынау жақсы)');
DEFINE('_JP_GZIP_NOT_ENABLED','GZIP қысу : қол жетпеу (мынау жаман)');
DEFINE('_JP_START_BACKUP_DB','Тап осы база сақтау басы');
DEFINE('_JP_START_BACKUP_FILES','Сайтамен барлық тап осы сақтау басы');
DEFINE('_JP_CUBE_ON_STEP','CUBE :: жұмыс адымда');
DEFINE('_JP_CUBE_STEP_FINISHED','CUBE :: адым аяқталған');
DEFINE('_JP_CUBE_FINISHED','CUBE :: аяқталған!');
DEFINE('_JP_ERROR_ON_STEP','CUBE :: қате адымда');
DEFINE('_JP_CLEANUP','Тазалау');
DEFINE('_JP_RECURSING_DELETION','Кері курсивті қашықтау');
DEFINE('_JP_NOT_FILE','Қашықтау <b>мынау файл, каталог емес!</b>');
DEFINE('_JP_ERROR_DEL_DIRECTORY','Каталог қашықтау қатесі. Рұқсат құқықтары тексеріңіздер.');
DEFINE('_JP_QUICK_MODE','Однопроходности тәртібі');
DEFINE('_JP_QUICK_MODE_ON_STEP','Адымда жылдам алгоритм қолданылады');
DEFINE('_JP_CANNOT_USE_QUICK_MODE','Адымда жылдам алгоритм қолдану');
DEFINE('_JP_MULTISTEP_MODE','Многопроходности тәртібі');
DEFINE('_JP_MULTISTEP_MODE_ON_STEP','Адымда баяу алгоритм қолданылады.');
DEFINE('_JP_MULTISTEP_MODE_ERROR','Баяу алгоритм орындалу қатесі адымда.');
DEFINE('_JP_SMART_MODE','Жылдамдатылған тәртіп');
DEFINE('_JP_SMART_MODE_ON_STEP','Жылдамдатылған тәртіп орындалуы адымда.');
DEFINE('_JP_SMART_MODE_ERROR','Жылдамдатылған тәртіп орындалу қатесі адымда.');
DEFINE('_JP_CHOOSED_ALGO','Таңдалған');
DEFINE('_JP_ALGORITHM_FOR','Алгоритм үшін');
DEFINE('_JP_NEXT_STEP_BACKUP_DB','Адым келесі --> база сақтауы');
DEFINE('_JP_NEXT_STEP_FILE_LIST','Адым келесі --> файлдардың тізім жасауы');
DEFINE('_JP_NEXT_STEP_FINISHING','Адым келесі --> аяқтау');
DEFINE('_JP_NEXT_STEP_GZIP','Адым келесі --> орама');
DEFINE('_JP_NEXT_STEP_FINISHED','Адым келесі --> аяқталған');
DEFINE('_JP_NO_NEXT_STEP','Адым келесі емес требуется; всё аяқталған');
DEFINE('_JP_NO_CUBE','Бар болуға CUBE жоқ; жаңа жасау');
DEFINE('_JP_CURRENT_STEP','Адым ағымдағы');
DEFINE('_JP_UNPACKING_CUBE','CUBE бар болу түйіншекті шешу.');
DEFINE('_JP_TIMEOUT','Операция орындалуына уақыт шықты, бірақ операция аяқталған емес.');
DEFINE('_JP_FETCHING_TABLE_LIST','CDBBackupEngine :: кестелердің тізім алуы');
DEFINE('_JP_BACKUP_ONLY_DB','CDBBackupEngine :: сақтау база тек қана тап осылардың');
DEFINE('_JP_ONE_FILE_STORE','CDBBackupEngine :: файлмен біріктіру берілген');
DEFINE('_JP_FILE_STRUCTURE','CDBBackupEngine :: құрылым файлы');
DEFINE('_JP_DATAFILE','CDBBackupEngine :: файл тап осылардың');
DEFINE('_JP_FILE_DELETION','CDBBackupEngine :: файлдардың қашықтауы');
DEFINE('_JP_FIRST_STEP','CDBBackupEngine :: бірінші өту');
DEFINE('_JP_ALL_COMPLETED','CDBBackupEngine :: Всё аяқталған');
DEFINE('_JP_START_TICK','CDBBackupEngine :: өңдеу басы');
DEFINE('_JP_READY_FOR_TABLE','Кесте үшін орындалған');
DEFINE('_JP_DB_BACKUP_COMPLETED','Тап осы база сақтауы аяқталған');
DEFINE('_JP_NEW_FRAGMENT_ADDED','Жаңа үзінді қосылған');
DEFINE('_JP_KERNEL_TABLES','Түйін кестелері');
DEFINE('_JP_FIRST_STEP_2','Бірінші өту');
DEFINE('_JP_NEXT_VALUE','Шығатын мағына');
DEFINE('_JP_SKIP_TABLE','Кесте кіргізуі');
DEFINE('_JP_GETTING','Алу');
DEFINE('_JP_COLUMN_FROM','Бағананың');
DEFINE('_JP_ERROR_WRITING_FILE','Файлға жазу қатесі');
DEFINE('_JP_CANNOT_SAVE_DUMP','Мүмкін емес дампыға сақтау');
DEFINE('_JP_CHECK_RESULTS','Тексеру нәтижелері');
DEFINE('_JP_ANALYZE_RESULTS','Талдау нәтижелері');
DEFINE('_JP_OPTIMIZE_RESULTS','Ықшамдау нәтижелері');
DEFINE('_JP_REPAIR_RESULTS','Дұрыстау нәтижелері');
DEFINE('_JP_GETTING_DIRS_LIST','Резервтегі көшірмеден шығаруға арналған каталогтердің тізім алуы');
DEFINE('_JP_GZIP_FIRST_STEP','Орама: алғашқы қадам');
DEFINE('_JP_GZIP_FINISHED','Орама :: аяқталған');
DEFINE('_JP_PACK_FINISHED','Архивирования аяқтауы');
DEFINE('_JP_GZIP_OF_FRAGMENT','Үзінді архивированиесі  #');
DEFINE('_JP_CURRENT_FRAGMENT','Үзінді ағымдағы');
DEFINE('_JP_DELETE_PATH','Қашықтауға арналған жол:');
DEFINE('_JP_PATH_TO_DELETE','Қосуға арналған жол');
DEFINE('_JP_SAVING_ARCHIVE_INFO','Хабар сақтауы туралы архивтіктерді объектілерде');
DEFINE('_JP_LOADING_ARCHIVE_INFO','ХАБАР тиеуі туралы архивтіктерді объектілерде');
DEFINE('_JP_ADDING_FILE_TO_ARCHIVE','Архивқа файлдардың қосуларының');
DEFINE('_JP_ARCHIVING','Архивирование');
DEFINE('_JP_ARCHIVE_COMPLETED','Архивирование аяқталған');
DEFINE('_JP_BACKUP_CONFIG','Резервтегі көшіріп алу кескін үйлесімі тап осылардың');
DEFINE('_JP_CONFIG_SAVING','Күйге келтірулердің сақтауы');
DEFINE('_JP_MAIN_CONFIG','Негізгі күйге келтірудің');
DEFINE('_JP_CONFIG_DIRECTORY','Архивтардың сақтау каталогі');
DEFINE('_JP_ARCHIVE_NAME','Архив аты');
DEFINE('_JP_LOG_LEVEL','Сай басқару деңгейі');
DEFINE('_JP_ADDITIONAL_CONFIG','Осымша күйге келтірудің');
DEFINE('_JP_DELETE_PREFIX','Кестелердің преффиксі алыстату');
DEFINE('_JP_EXPORT_TYPE','Тап осы база экспорт үлгісі');
DEFINE('_JP_FILELIST_ALGORITHM','Файлдардың өңдеуі');
DEFINE('_JP_CONFIG_DB_BACKUP','База өңдеуі');
DEFINE('_JP_CONFIG_GZIP','Файлдардың қысуы');
DEFINE('_JP_CONFIG_DUMP_GZIP','Тап осы база дампы қысуы');
DEFINE('_JP_AVAILABLE','<font color="green"><b>Қолайлы</b></font>');
DEFINE('_JP_NOT_AVAILABLE','<font color="red"><b>Қол жетпеу</b></font>');
DEFINE('_JP_MYSQL4_COMPAT','В режиме совместимости с MySQL 4');
DEFINE('_JP_NO_GZIP',' Емес (.sql) архивировать ');
DEFINE('_JP_GZIP_TAR_GZ','TAR.GZ (.tar.gz) архивировать ');
DEFINE('_JP_GZIP_ZIP','ZIP (.zip) архивировать ');
DEFINE('_JP_QUICK_METHOD','Жылдам - бір өту');
DEFINE('_JP_STANDARD_METHOD','Ұсынылған - стандартты');
DEFINE('_JP_SLOW_METHOD','Баяу - мультипроходность');
DEFINE('_JP_LOG_ERRORS_OLY','Қате тек қана');
DEFINE('_JP_LOG_ERROR_WARNINGS','Қателер және ескертудің');
DEFINE('_JP_LOG_ALL','Хабар бәрі');
DEFINE('_JP_LOG_ALL_DEBUG','Хабар және жөндеу бәрі');
DEFINE('_JP_DONT_SAVE_DIRECTORIES_IN_BACKUP','Резервтегі көшірмелер каталогтер сақтамау');
DEFINE('_FILE_NAME','Файл атысы');
DEFINE('_JP_DOWNLOAD_FILE','Секіру');
DEFINE('_JP_REALLY_DELETE_FILE','Файл нақты қашықтату?');
DEFINE('_JP_FILE_CREATION_DATE','Жасалған');
DEFINE('_JP_NO_BACKUPS','Резервтегі найзалардың файлдары жоқ болады');
DEFINE('_JP_ACTIONS_LOG','Әрекеттердің орындалу сайы');
DEFINE('_JP_BACKUP_MANAGEMENT','Резервтегі көшіріп алу');
DEFINE('_JP_CREATE_BACKUP','Басқару базамен тап осылардың');
DEFINE('_JP_DB_MANAGEMENT','Каталогтер сақтамау');
DEFINE('_JP_DONT_SAVE_DIRECTORIES','Сақтау күйге келтірулері');
DEFINE('_JP_CONFIG','Сақтау күйге келтірулері');
DEFINE('_JP_ERRORS_TMP_DIR','Қателерді табылған, резервтегі найзалардың сақтау каталогіне жазу мүмкіншілігін тексеріңіздер.');
DEFINE('_JP_BACKUP_CREATION','Тап осы резервтегі көшірме жасауы.');
DEFINE('_JP_DONT_CLOSE_BROWSER_WINDOW','Өтінем, браузера терезесін жаппаңыздар және сақтау аяғысына дейін мына беттен кешіп өту және лайықты хабарлау елестетулері.');
DEFINE('_JP_ERRORS_VIEW_LOG','Қателерді жұмыс барысында табылған, өтінем, <a href="index2.php?option=com_joomlapack&act=log">сайды қарайсыздар</a> жұмыстың және себепті анықтаңыздар.');
DEFINE('_JP_BACKUP_SUCCESS','Сақтау сайтамен тап осылардың табысты орындалған. Секіру.');
DEFINE('_JP_CREATION_FILELIST','1. Архивированияға арналған файлдардың тізім. Жасауы.');
DEFINE('_JP_BACKUPPING_DB','2. Тап осы база архивированиесі.');
DEFINE('_JP_CREATION_OF_ARCHIVE','3. Қорытынды архив жасауы.');
DEFINE('_JP_ALL_COMPLETED_2','4. Всё орындалған');
DEFINE('_JP_PROGRESS','Өңдеу');
DEFINE('_JP_TABLES','Кестенің');
DEFINE('_JP_TABLE_ROWS','Жазулардың');
DEFINE('_JP_SIZE','Мөлшер');
DEFINE('_JP_INCREMENT','Инкремент');
DEFINE('_JP_CREATION_DATE','Жасалған');
DEFINE('_JP_CHECKING','Тексеру');
DEFINE('_JP_FULL_BACKUP','Толық резерв');
DEFINE('_JP_BACKUP_BASE','Базаны сақтау');
DEFINE('_JP_BACKUP_PANEL','Сақтау панелі');

/* administrator modules mod_components */
DEFINE('_FULL_COMPONENTS_LIST','Компоненттердің толық тізімі');

/* administrator modules mod_fullmenu */
DEFINE('_MENU_CMS_FEATURES','Басқару жүйе негізгі мүмкіншіліктерімен');
DEFINE('_MENU_GLOBAL_CONFIG','Глобальды кескін үйлесімі');
DEFINE('_MENU_GLOBAL_CONFIG_TIP','Негізгі параметрлердің күйге келтіруі жүйе кескін үйлесімдері');
DEFINE('_MENU_LANGUAGES','Тілден жасалатын пакеттер');
DEFINE('_MENU_LANGUAGES_TIP','Басқару тілден жасалатын файлдармен');
DEFINE('_MENU_SITE_PREVIEW','Предпросмотр сайта');
DEFINE('_MENU_SITE_PREVIEW_IN_NEW_WINDOW','Жаңа терезеде');
DEFINE('_MENU_SITE_PREVIEW_IN_THIS_WINDOW','Ішінде');
DEFINE('_MENU_SITE_PREVIEW_WITH_MODULE_POSITIONS','Позициялармен ішінде');
DEFINE('_MENU_SITE_STATS','Сайта статистикасы');
DEFINE('_MENU_SITE_STATS_TIP','Қарау статистиктар сайтумен');
DEFINE('_MENU_STATS_BROWSERS','Браузеры, ОС, домендер');
DEFINE('_MENU_STATS_BROWSERS_TIP','Сайта қатысуларының статистикасы браузераммен, ОС және домендерге');
DEFINE('_MENU_SEARCHES','Іздеу сауалдар');
DEFINE('_MENU_SEARCHES_TIP','Іздеу сауалдардың статистикасы сайтумен');
DEFINE('_MENU_PAGE_STATS','Беттердің қатысу статистикасы');
DEFINE('_MENU_TEMPLATES_TIP','Басқару үлгілермен');
DEFINE('_MENU_SITE_TEMPLATES','Сайта үлгілері ');
DEFINE('_MENU_NEW_SITE_TEMPLATE','Жаңа үлгі құруы');
DEFINE('_MENU_ADMIN_TEMPLATES','Админцентра үлгілері');
DEFINE('_MENU_NEW_ADMIN_TEMPLATE','Жаңа үлгі құруы');
DEFINE('_MENU','Меню');
DEFINE('_MENU_TRASH','Меню кәрзеңкесі');
DEFINE('_CONTENT_IN_SECTIONS','Ұсталушы бөлімдермен');
DEFINE('_CONTENT_IN_SECTION','Ұсталушы бөлімде');
DEFINE('_SECTION_ARCHIVE','Бөлім архивы');
DEFINE('_SECTION_CATEGORIES2','Бөлім категориясы');
DEFINE('_ADD_CONTENT_ITEM','Жаңалық қосу / мақаланы');
DEFINE('_ADD_STATIC_CONTENT','Статикалық ұсталушы қосу');
DEFINE('_CONTENT_ON_FRONTPAGE','Ұсталушы негізгіде');
DEFINE('_CONTENT_TRASH','Кәрзеңке ұсталушыны');
DEFINE('_ALL_COMPONENTS','Барлық компоненттер...');
DEFINE('_EDIT_COMPONENTS_MENU','Компоненттердің менюі редакциялау');
DEFINE('_COMPONENTS_INSTALL_UNINSTALL','Құру/Компоненттердің қашықтауы');
DEFINE('_MODULES_SETUP','Басқару модульдермен');
DEFINE('_MODULES_INSTALL_DEINSTALL','Құру/Модульдердің қашықтауы');
DEFINE('_SITE_MAMBOTS','Мамботы сайта');
DEFINE('_MAMBOTS_INSTALL_UNINSTALL','Құру/Мамботов қашықтауы');
DEFINE('_SITE_LANGUAGES','Сайта тілдері');
DEFINE('_JOOMLA_TOOLS','Аспаптар');
DEFINE('_PRIVATE_MESSAGES_CONFIG','Хабарлаулардың күйге келтірулері');
DEFINE('_FILE_MANAGER','Файлдардың менеджерів');
DEFINE('_SQL_CONSOLE','SQL консоль');
DEFINE('_BACKUP_CONFIG','Тап осы сақтау күйге келтірулері');
DEFINE('_CLEAR_CONTENT_CACHE','Кэш тазалау');
DEFINE('_CLEAR_ALL_CACHE','Кэш барлығы тазалау');
DEFINE('_SYSTEM_INFO','Хабар жүйе туралы');
DEFINE('_NO_ACTIVE_MENU_ON_THIS_PAGE','Меню мына бетінде белсенді емес');

/* administrator modules mod_latest */
DEFINE('_LAST_ADDED_CONTENT','Соңғы қосылған ұсталушы');
DEFINE('_USER_WHO_ADD_CONTENT','Қосты');

/* administrator modules mod_latest_users */
DEFINE('_NOW_ON_SITE','Сайтеге қазір');
DEFINE('_REGISTERED_USERS_COUNT','Тіркелген');
DEFINE('_ALL_REGISTERED_USERS_COUNT','Барлығы');
DEFINE('_TODAY_REGISTERED_USERS_COUNT','Үшін бүгін');
DEFINE('_WEEK_REGISTERED_USERS_COUNT','Аптаның артынан');
DEFINE('_MONTH_REGISTERED_USERS_COUNT','Айдың артынан');

/* administrator modules mod_logged */
DEFINE('_NOW_ON_SITE_REGISTERED','Қазір сайтеге авторластырылған');

/* administrator modules mod_online */
DEFINE('_ONLINE_USERS','Онлайн пайдаланушыларының');

/* administrator modules mod_popular */
DEFINE('_POPULAR_CONTENT','Жиі қаралушы');
DEFINE('_CREATED_CONTENT','Жасалған');
DEFINE('_CONTENT_HITS','Қараулардың');

/* administrator modules mod_stats */
DEFINE('_MENU_ITEMS_COUNT','Пунктілердің');

/* administrator modules includes admin.php */
DEFINE('_CACHE_DIR_IS_NOT_WRITEABLE','Өтінем, жазуға арналған қолайлы кэшамен каталогті істеңіздер.');
DEFINE('_CACHE_DIR_IS_NOT_WRITEABLE2','Кэша каталогі жазу үшін қолайлы емес.');
DEFINE('_PHP_MAGIC_QUOTES_ON_OFF','PHP magic_quotes_gpc анықталған `OFF` орнына `ON`');
DEFINE('_PHP_REGISTER_GLOBALS_ON_OFF','PHP register_globals анықталған `ON` орнына `OFF`');
DEFINE('_RG_EMULATION_ON_OFF','Joostina параметрі RG_EMULATION в файле globals.php анықталған `ON` орнына `OFF`<br />`ON` - <span style="font-weight: normal; font-style: italic; color: #666;">параметр үндемеуге</span> - үшін сыйысушылықтың');
DEFINE('_PHP_SETTINGS_WARNING','PHP үйлесімді келесі күйге келтірулері келмейді үшін <strong>БЕЗОПАСНОСТИ</strong> және өзгертуге оларды ұсынылады.');
DEFINE('_MENU_CACHE_CLEANED','Кэш басқару панель менюі тазаланған');
DEFINE('_CLEANING_ADMIN_MENU_CACHE','Кэша тазалау қате басқару панель менюі.');
DEFINE('_NO_MENU_ADMIN_CACHE','Кэш басқару панель менюі табылған емес. Кэша каталогіне рұқсат құқықтары тексеріңіздер.');

/* administrator modules includes pageNavigation.php */
DEFINE('_NAV_SHOW','Көрсетілген');
DEFINE('_NAV_SHOW_FROM',' а ');
DEFINE('_NAV_NO_RECORDS','Жазулар табылған емес');
DEFINE('_NAV_ORDER_UP','Жоғарырақ басқаша орналастыру');
DEFINE('_NAV_ORDER_DOWN','Төмен басқаша орналастыру');

/* administrator modules popups pollwindow.php */
DEFINE('_POLL_PREVIEW','Сұрақ предпросмотры');

/* administrator modules popups uploadimage.php */
DEFINE('_CHOOSE_IMAGE_FOR_UPLOAD','Өтінем, тиеуге арналған бейнелеуді таңдаңыздар.');
DEFINE('_BAD_UPLOAD_FILE_NAME','Файлдардың аттары әліпби символдарынан тиісті түзелу және ашық жерлерді тиісті асырамау.');
DEFINE('_IMAGE_ALREADY_EXISST','Бейнелеу бар болады');
DEFINE('_FILE_MUST_HAVE_THIS_EXTENSION','Файл кеңейту тиісті болу.');
DEFINE('_FILE_UPLOAD_UNSUCCESS','Тиеу файлды сәтті емес.');
DEFINE('_FILE_UPLOAD_SUCCESS','Файл тиеуі табысты аяқталған.');

/* administrator index.php index2.php index3.php */
DEFINE('_PLEASE_ENTER_PASSWORD','Өтінем, парольді енгізіңіздер.');
DEFINE('_BAD_CAPTCHA_STRING','Тексеру сенімсіз коды енгізілген.');
DEFINE('_BAD_USERNAME_OR_PASSWORD','Сенімсіздер пайдаланушы аты, пароль, немесе рұқсат деңгейі. Өтінем, қайтадан қайта айтыңыздар.');
DEFINE('_BAD_USERNAME_OR_PASSWORD2','Аты немесе пароль дұрыс емес. Енгізуді қайта айтыңыздар.');//not equal to _BAD_USERNAME_OR_PASSWORD!!!

/* administrator templates jostfree index.php */
DEFINE('_JOOSTINA_CONTRIL_PANEL','Басқару панелі [ Joostina ]');
DEFINE('_GO_TO_MAIN_ADMIN_PAGE','Басқару панельдері негізгі бетке өту');
DEFINE('_PLEASE_WAIT','күтіңіздер...');
DEFINE('_TOGGLE_WYSIWYG_EDITOR','Көзбен шолу редактор қолдануы');
DEFINE('_DISABLE_WYSIWYG_EDITOR','Редактор сөндіріп тастау');
DEFINE('_PRESS_HERE_TO_RELOAD_CAPTCHA','бейнЕлеу жаңарту үшін басыңыздар');
DEFINE('_SHOW_CAPTCHA','Бейнелеу жаңарту');
DEFINE('_PLEASE_ENTER_CAPTCHA','Жоғарырақ суреттен тексеру кодын енгізіңіздер');
DEFINE('_PLEASE_ENABLE_JAVASCRIPT','!Ескерту! Javascript тиісті рұқсат етілген болу үшін панель дұрыс жұмыстары әкім басқарулары!');

/* includes feedcreator.class.php */
DEFINE('_ERROR_CREATING_NEWSFEED','Жаңалықтардың бау файл жасау қатесі. Өтінем, жазуға рұқсаттарды тексеріңіздерь.');

/* includes joomla.php */
DEFINE('_YOU_NEED_TO_AUTH','Қажетті авторизоваться');
DEFINE('_ADMIN_SESSION_ENDED','Әкім сессиясы аяқталды');
DEFINE('_YOU_NEED_TO_AUTH_AND_FIX_PHP_INI','Сіздерге қажетті авторизоваться. Егер PHP session.auto_start параметр қосылған немесе параметр өшірілген session.use_cookies setting, онда сіздер алдымен оларды түзеуге тиісті кіруге істей алмас бұрын.');
DEFINE('_WRONG_USER_SESSION','Дұрыссыз сессия');
DEFINE('_FIRST','Бірінші');
DEFINE('_LAST','Соңғы');
DEFINE('_MOS_WARNING','Назар!');
DEFINE('_ADM_MENUS_TARGET_CUR_WINDOW','Ағымдағы терезеде навигация панелімен.');
DEFINE('_ADM_MENUS_TARGET_NEW_WINDOW_WITH_PANEL','Жаңа терезеде навигация панелімен.');
DEFINE('_ADM_MENUS_TARGET_NEW_WINDOW_WITHOUT_PANEL','Жаңа терезеде навигация панельдері.');
DEFINE('_WITH_UNASSIGNED','Азаттармен');
DEFINE('_CHOOSE_IMAGE','Бейнелеуді таңдаңыздар');
DEFINE('_NO_USER','Пайдаланушыны жоқ');
DEFINE('_CREATE_CATEGORIES_FIRST','Категориялар алдымен қажетті жасау');
DEFINE('_NOT_CHOOSED','Таңдалған емес');
DEFINE('_PUBLISHED_VUT_NOT_ACTIVE','Жарияланған, бірақ <u>белсенді емес</u>');
DEFINE('_PUBLISHED_AND_ACTIVE','Жарияланған және <u>белсенді</u>');
DEFINE('_PUBLISHED_BUT_DATE_EXPIRED','Жарияланған, бірақ <u>жариялау мезгілі ақты</u>.');
DEFINE('_NOT_PUBLISHED','Жарияланған емес');
DEFINE('_LINK_NAME','Сілтеме аты');
DEFINE('_MENU_EXPIRED','Ескірді');
DEFINE('_MENU_ITEM_NAME','Пункті аты');
DEFINE('_CHECKED_OUT','Қоршалған');
DEFINE('_PUBLISH_ON_FRONTPAGE','Сайтеге жариялау');
DEFINE('_UNPUBLISH_ON_FRONTPAGE','Жасыру (сайтеге көрсетпеу)');

/* includes joomla.xml.php */
DEFINE('_DONT_USE_IMAGE','- Бейнелеу қолдану -');
DEFINE('_DEFAULT_IMAGE','- Бейнелеу үндемеумен -');

/* includes debug jdebug.php */
DEFINE('_SQL_QUERIES_COUNT','SQL сауалдардың саны');

/* includes Cache Lite Lite.php */
DEFINE('_ERROR_DELETING_CACHE','Кэша қашықтау қатесі');
DEFINE('_ERROR_READING_CACHE_DIR','Кэша директория оқу қатесі');
DEFINE('_ERROR_READING_CACHE_FILE','Кэша файл оқу қатесі');
DEFINE('_ERROR_WRITING_CACHE_FILE','Кэша файл жазу қатесі');
DEFINE('_SCRIPT_MEMORY_USING','Жадқа қолданған');

/* components com_content */
DEFINE('_YOU_HAVE_NO_CONTENT','Қосылған жоқ сіздермен ұсталушыны.');
DEFINE('_CONTENT_IS_BEING_EDITED_BY_OTHER_PEOPLE','Ұсталушы қазір редакциялайды басқа адаммен.');

/* components com_poll */
DEFINE('_MODULE_WITH_THIS_NAME_ALREADY_EDISTS','Сондай атпен модуль бар болады. Басқа атты енгізіңіздер.');

/* components com_registration */
DEFINE('_USER_ACTIVATION_FAILED','<div class="componentheading">Активтендіру қатесі!</div><br />Сіздердің есептік жазуыңыз активтендіруі. Өтінем, сайта әкімшілігіне бұрылаcыздар.');

/* components com_weblinks */
DEFINE('_ENTER_CORRECT_URL','URL дұрыс енгізіңіздер.');

/* components com_xmap */
DEFINE('_XMAP_PAGE','Бет');

/* administrator index2.php */
DEFINE('_TEMPLATE_NOT_FOUND','Үлгі табылған емес.');
DEFINE('_ACCESS_DENIED','Рұқсатта қабыл алынбаған.');
DEFINE('_CHECKIN_OJECT','Жол ашу');

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
*/

?>