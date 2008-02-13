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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

// Language File for Russian
DEFINE("_JWMEDIAMAN_TITLE","Медиа менеджер");
DEFINE("_JWMEDIAMAN_CREATE_FOLDER_DONE","Каталог создан!");
DEFINE("_JWMEDIAMAN_CREATE_FOLDER_ERROR","Каталог НЕ создан!");
DEFINE("_JWMEDIAMAN_DEL_FILE_DONE","Файл удалён!");
DEFINE("_JWMEDIAMAN_DEL_FILE_ERROR","Файл Не удалён!");
DEFINE("_JWMEDIAMAN_DEL_FOLDER_DONE","Каталог удалён!");
DEFINE("_JWMEDIAMAN_DEL_FOLDER_ERROR","Каталог НЕ удалён!");
DEFINE("_JWMEDIAMAN_REN_FILE_DONE","Переименовано!");
DEFINE("_JWMEDIAMAN_REN_FILE_ERROR","Не переименовано!");
DEFINE("_JWMEDIAMAN_TMP_DONE","Временная папка очищена!");
DEFINE("_JWMEDIAMAN_TMP_ERROR","Временная папка НЕ очищена!");
DEFINE("_JWMEDIAMAN_UPL_SERVER_ERROR","Файл(ы) НЕ загружены на сервер!");
DEFINE("_JWMEDIAMAN_UPL_SERVER_DONE","Файлы загружены!");
DEFINE("_JWMEDIAMAN_FOLDERS","Каталоги");
DEFINE("_JWMEDIAMAN_IMAGES","Изображения");
DEFINE("_JWMEDIAMAN_FILES","Файлы");
DEFINE("_JWMEDIAMAN_ALPHANUMERIC_FOLDER","Пожалуйста, не используйте в названиях пробелы и спецсимволы!");
DEFINE("_JWMEDIAMAN_NOEMPTY_FOLDER","Каталог не пустой.\\nПожалуйста, удалите сначала содержимое внутри каталога!");
DEFINE("_JWMEDIAMAN_ALERT_DEL_FOLDER","Удалить каталог ");
DEFINE("_JWMEDIAMAN_ALERT_DEL_FILE","Удалить файл ");
DEFINE("_JWMEDIAMAN_LEG_SEL_DIR","Местоположение:");
DEFINE("_JWMEDIAMAN_TEXT_DIR_PATH","Расположение каталога");
DEFINE("_JWMEDIAMAN_LEG_CRE_FOLDER","Создать каталог:");
DEFINE("_JWMEDIAMAN_TEXT_NAME_CRE_FOLDER","Имя");
DEFINE("_JWMEDIAMAN_CLICKTOCREATE","Создать");
DEFINE("_JWMEDIAMAN_LEG_UPL_IMAGES","Загрузить файл:");
DEFINE("_JWMEDIAMAN_LEG_UPL_MIMAGES","(+)");
DEFINE("_JWMEDIAMAN_CLICKTOUPLOAD","Загрузить");
DEFINE("_JWMEDIAMAN_LEG_REN_FILE","Переименование: ");
DEFINE("_JWMEDIAMAN_TEXT_NAME_REN_FILE","Новое имя (включая расширение!)");
DEFINE("_JWMEDIAMAN_CLICKTORENAME","Переименовать");
DEFINE("_JWMEDIAMAN_LEG_TMP","Временный каталог");
DEFINE("_JWMEDIAMAN_TEXT_TMP","Число изображений во временном каталоге");
DEFINE("_JWMEDIAMAN_CLICKTOTMP","Очистить каталог");
DEFINE("_JWMEDIAMAN_SELECT","-- выбор --");
/* EDIT page */
DEFINE("_JWMEDIAMAN_CLICKCONVERT","Применить");
DEFINE("_JWMEDIAMAN_CLICKORIGINAL","Отменить всё");
DEFINE("_JWMEDIAMAN_CLICKSAVEIMAGE","Сохранить");
DEFINE("_JWMEDIAMAN_CLICKJWMEDIAMAN","Выход");
DEFINE("_JWMEDIAMAN_LEG_WIDTHHEIGHT","Высота x Ширина");
DEFINE("_JWMEDIAMAN_TEXT_WIDTH","ширина");
DEFINE("_JWMEDIAMAN_TEXT_HEIGHT","высота");
DEFINE("_JWMEDIAMAN_LEG_EXT","Расширение изображения");
DEFINE("_JWMEDIAMAN_TEXT_EXT","Расширение");
DEFINE("_JWMEDIAMAN_LEG_GROP","Обрезать");
DEFINE("_JWMEDIAMAN_TEXT_GROP_PER","Размеры");
DEFINE("_JWMEDIAMAN_TEXT_GROP_DIMEN","X и Y координаты");
DEFINE("_JWMEDIAMAN_TEXT_V","вертикали");
DEFINE("_JWMEDIAMAN_TEXT_H","горизонтали");
DEFINE("_JWMEDIAMAN_LEG_BORD","Бордюр");
DEFINE("_JWMEDIAMAN_LEG_BORD_ALL","Все бордюры");
DEFINE("_JWMEDIAMAN_LEG_BORD_SIDES","Обрезать");
DEFINE("_JWMEDIAMAN_TEXT_BORD_TOP","Сверху");
DEFINE("_JWMEDIAMAN_TEXT_BORD_LEFT","Слева");
DEFINE("_JWMEDIAMAN_TEXT_BORD_RIGHT","Справа");
DEFINE("_JWMEDIAMAN_TEXT_BORD_BOTTOM","Снизу");
DEFINE("_JWMEDIAMAN_LEG_ROT","Поворот");
DEFINE("_JWMEDIAMAN_TEXT_DEGREES","Повернуть на");
DEFINE("_JWMEDIAMAN_LEG_FLIP","Отражение");
DEFINE("_JWMEDIAMAN_TEXT_DIRECTION","Отразить по");
DEFINE("_JWMEDIAMAN_LEG_BEVEL","Bevel");
DEFINE("_JWMEDIAMAN_TEXT_BEVEL_PX","Bevel px");
DEFINE("_JWMEDIAMAN_TEXT_BEVEL_TL","Bevel Top-Left");
DEFINE("_JWMEDIAMAN_TEXT_BEVEL_RB","Bevel Right-Bottom");
DEFINE("_JWMEDIAMAN_TEXT_COLOR","Цвет");
DEFINE("_JWMEDIAMAN_LEG_TINT","Tint Color");
DEFINE("_JWMEDIAMAN_LEG_OVERLAY","Overlay");
DEFINE("_JWMEDIAMAN_TEXT_OVERLAY","Percent");
DEFINE("_JWMEDIAMAN_LEG_BRIGHTNESS","Яркость");
DEFINE("_JWMEDIAMAN_LEG_CONTRAST","Контраст");
DEFINE("_JWMEDIAMAN_LEG_THRESHOLD","Threshold filter");
DEFINE("_JWMEDIAMAN_LEG_SPECIAL","Дополнительные действия");
DEFINE("_JWMEDIAMAN_TEXT_GREYSCALE","Градиент серого");
DEFINE("_JWMEDIAMAN_TEXT_NEGATIVE","Негатив");
DEFINE("_JWMEDIAMAN_LEG_TEXT","Добавить текст");
DEFINE("_JWMEDIAMAN_TEXT_TEXT","Текст");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_COLOR","Цвет текста");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_FONT","Шрифт текста");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_PER","Размер текста");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_DIRECTION","Ориентация");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_POS","Позиция");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_BG_PER","Bg Percent");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_BG_COLOR","Цвет фона");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_PADDING","Расположение по X и Y");
DEFINE("_JWMEDIAMAN_TEXT_TEXT_ABS_POS","Отступы по X и Y");
DEFINE("_JWMEDIAMAN_NUM_DIR","Каталогов");
DEFINE("_JWMEDIAMAN_NUM_FILES","Файлов");
DEFINE("_JWMEDIAMAN_FILESIZE","Размер");
DEFINE("_JWMEDIAMAN_LEG_COPY_FILE","Выберите каталог для копирования: ");
DEFINE("_JWMEDIAMAN_TEXT_NAME_COPY_FILE","Копировать в");
DEFINE("_JWMEDIAMAN_CLICKTOCOPY","Копировать");
DEFINE("_JWMEDIAMAN_COPY_DONE","Скопировано!");
DEFINE("_JWMEDIAMAN_COPY_ERROR","Не скопировано!");
DEFINE("_JWMEDIAMAN_LEG_MOVE_FILE","Выберите каталог для перемещения: ");
DEFINE("_JWMEDIAMAN_TEXT_NAME_MOVE_FILE","Переместить в");
DEFINE("_JWMEDIAMAN_CLICKTOMOVE","Переместить");
DEFINE("_JWMEDIAMAN_MOVE_DONE","Перемещено!");
DEFINE("_JWMEDIAMAN_MOVE_ERROR","Не перемещено!");
DEFINE("_JWMEDIAMAN_SAVED_AS","Изображение сохранено как ");
DEFINE("_JWMEDIAMAN_IMAGE_PATH","Расположение: ");
DEFINE("_JWMEDIAMAN_SAVEEDIT_DONE","Изображение сохранено как ");
DEFINE("_JWMEDIAMAN_SAVEEDIT_ERROR","Изображение НЕ сохранено!");
/* actions */
DEFINE("_JWMEDIAMAN_ACT_EDIT","Редактировать");
DEFINE("_JWMEDIAMAN_ACT_RENAME","Переименовать");
DEFINE("_JWMEDIAMAN_ACT_COPY","Копировать");
DEFINE("_JWMEDIAMAN_ACT_MOVE","Переместить");
DEFINE("_JWMEDIAMAN_ACT_DELETE","Удалить");
DEFINE("_JWMEDIAMAN_ACT_PREV","Нажмите для просмотра!");
DEFINE("_JWMEDIAMAN_ACT_SLIMBOX","Файл:");
/* videobox */
DEFINE("_JWMEDIAMAN_VB_FLV","Видео файл:");
DEFINE("_JWMEDIAMAN_VB_SWF","Flash файл:");
DEFINE("_JWMEDIAMAN_VB_PREV","Нажмите на значок для просмотра!");
/* .zip messages */
DEFINE("_JWMEDIAMAN_LEG_ZIP_FILE","Выберите каталог для распаковки: ");
DEFINE("_JWMEDIAMAN_TEXT_NAME_ZIP_FILE","Каталог распаковки");
DEFINE("_JWMEDIAMAN_CLICKTOUNZIP","Распаковать");
DEFINE("_JWMEDIAMAN_ZIP_FILES_EXTRACTED"," файл(ы) распакованы.");
DEFINE("_JWMEDIAMAN_ZIP_FILES_UNEXPECTED_ERROR","Ошибка распаковки: ");
DEFINE("_JWMEDIAMAN_ZIP_FILE_STRING","Файл: ");
DEFINE("_JWMEDIAMAN_ZIP_NOT_A_ZIP_FILE"," не является архивным файлом!");
DEFINE("_JWMEDIAMAN_ZIP_NOT_EXISTS"," не существует!");
/* JW */
DEFINE('_JWMEDIAMAN_CREDITS','JW Media Manager XTD v1.0 by <a href="http://www.joomlaworks.gr" target="_blank">JoomlaWorks</a>');
DEFINE('_JWMEDIAMAN_NOIE','Используемый Вами браузер (Internet Explorer) <u>не</u> поддерживает нормальную работу компонента.<br /><br />Пожалуйста, используйте более совершенные браузеры <a href="http://www.mozilla.com" target="_blank">Mozilla Firefox</a> или <a href="http://www.opera.com" target="_blank">Opera</a> если Вы всё еще используете Windows, <a href="http://www.mozilla.com" target="_blank">Mozilla Firefox</a>, <a href="http://www.caminobrowser.org/" target="_blank">Mozilla Camino</a> или <a href="http://www.apple.com/safari/" target="_blank">Safari</a> если Вы используете Mac.<br /><br />Спасибо!');

?>
