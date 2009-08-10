<?php defined( '_VALID_MOS' ) or die( 'Доступ ограничен' );
/**
* STEPmenu v1.1.0.
* Модуль создания многоуровнего меню каждый уровень которого появляется в зависимости от текущего.
* Кирш Николай ( boston ).
* Русский дом Joomla! www.joom.ru 2007 год.
* Первая версия выпущена в декабре 2006 года специально для sourpuss.
*
* Для корректной работы родительские элементы должны быть типа "Ссылка - URL", и в качестве ссылки содержать #.
* Первый родитель! <--ссылка '#', 1 уровень
*.      L Новости <-- просто ссылка хоть на чо
*.      L Второй родитель  <--ссылка '#', 2-1 уровень
*.      .      L Подвторой - 1  <-- просто ссылка хоть на что
*.      .      L Подвторой - 2  <-- просто ссылка хоть на что
*.      L Третий родитель  <--ссылка '#', 2-2 уровень
*.      .      L Подтретий1 - 1  <-- просто ссылка хоть на что
*.      .      L Подтретий2 - 2  <-- просто ссылка хоть на что
**/

global $database, $Itemid,$mosConfig_shownoauth;

/*
параметры получаем из настроек модуля
*/
$active_css = $params->get( 'active_css' ); // CSS стить активного пункта меню
$select_css = $params->get( 'select_css' ); // CSS стить выбранного пункта меню
$menutype = $use_menu; // название меню для выборки данных
$on_type = $params->get( 'on_type','onclick' );// событие на которое расскрыать подменю

/*
Далее скрипт обработки элементов меню, сокрытие и смена стиля выбранной ссылки.
*/
?>
<script type="text/javascript">
	function go_main(num_id,state) {
		drop_div = new RegExp('ssmenu_' + state + "_([0-9])");
		all_div = document.getElementsByTagName('div');
		for ( var i = 0; i < all_div.length; i++ ) {
			if (drop_div.test(all_div[i].id)) {
					all_div[i].style.display = 'none';
			};
		};
		drop_link = new RegExp('sslink_' + state + "_([0-9])");
		all_link = document.getElementsByTagName('a');
		for ( var i = 0; i < all_link.length; i++ ) {
			if (drop_link.test(all_link[i].id)) {
				all_link[i].className='';
			};
		};
		document.getElementById('ssmenu_' + state + '_' + num_id).style.display = 'block';  // показываем слой активного пункта
		document.getElementById('sslink_' + state + '_' + num_id).className = '<?php echo $select_css;?>'; // тут класс кооторый назнчется текущему (НЕ активному) пункту меню
	};
</script>

<?php

if ($mosConfig_shownoauth) { // если в глобалконфиге разрешено показывать ссылке не авторизованным
	$sql = "SELECT m.* FROM #__menu AS m"
	. "\nWHERE menutype='$menutype' AND published='1'"
	. "\nORDER BY parent,ordering";
} else {
	$sql = "SELECT m.* FROM #__menu AS m"
	. "\nWHERE menutype='$menutype' AND published='1' AND access <= '$my->gid'"
	. "\nORDER BY parent,ordering";
};
$database->setQuery($sql);
$rows = $database->loadObjectList();
if(count($rows) <= 0) {
	echo 'Меню <b>'.$menutype.'</b> не найдено или не содержит ни одного элемента.'; // запрос свернул 0 результатов - такое меню не существует или не содержит элементы
}

$new_div	= ''; // сюбду будут складываться слои с дочерними меню
$all_menu	= $rows;
foreach ($rows as $menu ) { // обрабатываем все пунткы меню
	if($menu->parent == '0'){ // берём ссылки толкьо нулевого уровня - т.е. самые верхние
		if ($menu->link=='#') { // если текст ссылки записан как '#' - значит это наша ссылка, обрабатываем её
			$cild = go_child($all_menu,$menu->id,'1',$active_css,$spacer); // получаем все элементы подменю ссылки 0 уровня
			$new_div .=$cild['text']; // в элементе массива слои с дочерними элементами
			$aktiv = $cild['vis']; // если в дочерних ссылка есть хоть один активный элемент - передадим этот параметр родительскому пункту и выделим его указанным стилем
			echo "<a id=\"sslink_1_{$menu->id}\" href=\"javascript:void(0);\" {$on_type}=\"go_main('$menu->id','1');\" $aktiv >$menu->name</a>{$spacer}"; // родительская ссылка на открытие дочерних слоёв с ссылками
		} else { // если у нас простая ссылка
			if (!eregi( "Itemid=", $menu->link )){
				$menu->link .= "&Itemid=$menu->id"; // если нету иидентификатора - дописываем
			}
			if ($menu->id == $Itemid){
				$ad_active = 'class="'.$active_css.'"';
			}else{
				$ad_active = null; // если идентификатор совпадает сидентификатором из возвращенного запроса - подсвечиваем её
			}
			echo '<a href="'.sefRelToAbs($menu->link).'" '.$ad_active.'>'.$menu->name.'</a>'.$spacer; // выводим саму ссылку
		}
	}
};
echo $new_div; // отображаем слой с дочерними меню

/*
получение дочерних ссылок
*/
function go_child($rows,$pid,$state,$active_css,$spacer){
	global $Itemid; // берём глобальный идентификатор текущей ссылки
	$ret = '';
	$new_div ='';
	$all_menu = $rows;
	$ad_active = '';
	$aktiv = 0;
	$cild = array();
	$aktial = 0;
	$state2 = $state +1;
	foreach ($rows as $menu ) {
		if($menu->parent == $pid){ // берём ссылки только принадлежащие текущему родителю
			if ($menu->id==$Itemid) { // подсветка вслучаем активности ссылки
				$aktiv = 1; // создаём значение для последующей передачи его родительской ссылке
				$ad_active = "class=\"$active_css\"";
			}else{
				$ad_active = '';
			}
			// проделываем процедуру анадлогичную обработке родительской ссылки
			if ($menu->link=='#') {
				$cild = go_child($all_menu,$menu->id,$state2,$active_css); // слой с подузлами
				$new_div .=$cild['text'];
				$aktiv_menu = $cild['vis'];
				$cild['vis'] ? $aktial = 1 : null;
				$ret.= "<a id=\"sslink_{$state2}_{$menu->id}\" href=\"javascript:void(0);\" onClick=\"go_main('$menu->id','$state2');\" $aktiv_menu >$menu->name</a>{$spacer}"; // родительская ссылка на открытие подузлов
			} else {
				if (!eregi( 'Itemid=', $menu->link )) $menu->link .= "&Itemid=$menu->id";
				$ret .= '<a href="'.sefRelToAbs($menu->link)."\" $ad_active>$menu->name</a>{$spacer}";
			}
		}
	}
	// если в созданном объекте дочерних ссылокесть хоть одна активная - то выделим и родителя
	if($aktiv or $aktial) {
		$vis = "style=\"display:block\""; // слой с ссылкой будет виден и отображен
		$akt_parent = "class=\"$active_css\""; // сама ссылка будет подсвечена
	}else{
		$vis = "style=\"display:none\"";
		$akt_parent = null;
	};
	$return = array(); // собираем итоговый результат
	$return['text'] = '<div '.$vis.' id="ssmenu_'.$state.'_'.$pid.'">'.$ret.$new_div.'</div>';
	$return['vis'] = $akt_parent;
	return $return;
}