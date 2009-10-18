<?php

defined('_VALID_MOS') or die();
global $task,$my,$mosConfig_live_site, $mosConfig_mailfrom;
$iso = explode('=',_ISO); echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $iso[1];?>" />

<script type="text/javascript">
    var _live_site = '<?php echo $mosConfig_live_site;?>';
    var _option = '<?php echo mosGetParam( $_REQUEST, 'option', '' );?>';
    var _cur_template = '<?php echo JTEMPLATE;?>';
    var _js_defines = new Array();
</script>
<?php

	//MainBody
	$page = new PageModel($mainframe);

	//Модули
	$modules = new mosModule($database, $mainframe);
	$modules->initModules();

	// загружаем верхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
	$page->_header(array('js'=>1,'css'=>1,'jquery'=>1));

	//Инициализация визуального редактора
	if ($my->id && $mainframe->allow_wysiwyg) { initEditor(); }
	
	//Принудительно настраиваем модуль авторизации
	$login_params = new stdClass();
	$login_params->template = 'popup.php'; 
	$login_params->show_login_text = 3; 
	$login_params->show_pass_text = 3;
	

	$block1_count = $modules->mosCountModules('user1');
	$block2_count = $modules->mosCountModules('user5');
	$block3_count = $modules->mosCountModules('user7');
	
	$body_class = 'inside';
	if($block1_count){$body_class = 'mainpage';}

?>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo JTEMPLATE; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 7]>
    <link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo JTEMPLATE; ?>/css/fix/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if IE 8]><link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo JTEMPLATE; ?>/css/fix/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
</head>


<body class="<?php echo $body_class;?>">
    
    <div class="main_wrap">
    
        <div class="wrapper">

            <div class="header">
                <a href="<?php echo $mosConfig_live_site;?>" id="logo">&nbsp;</a>
                
                <div class="header_center"><?php $modules->mosLoadModules('header',-1); ?></div> 
  
                <div class="header_right">
                    <a title="Обратная связь" href="mailto:<?php echo $mosConfig_mailfrom;?>" id="mail" class="navbar">&nbsp;</a>
                    <a title="Карта сайта" href="<?php echo sefRelToAbs('index.php?option=com_xmap&amp;Itemid=27'); ?>" id="map" class="navbar">&nbsp;</a>      
				</div>               
               
               	<div class="top_menu_l"><div class="top_menu_r"><div class="top_menu_mid">
					<?php $modules->mosLoadModules('top',-1); ?>
					<?php $modules->mosLoadModule('mod_ml_login', '', -1, 0, $login_params); ?>
					<?php //mosLoadModules('toolbar',-2); ?>                        
       			</div></div></div>
            
            </div><!--header:end-->
            
            <?php if($block1_count) {
            	$block1_width = 'w' .$block1_count;
            ?>
            <div class="block1" id="block_round">
                    <div class="block_<?php echo $block1_width ?>">
        	            <?php $modules->mosLoadModules('user1', -2); ?>
        	        </div>
            </div><!--block1:end-->
            <?php } ?>
            

            <div class="content">
                <?php $page->_body(); ?> <br />
                
                <?php if($block2_count) {
                    $block2_width = 'w' .$block2_count;
                ?>
                    <div class="block2">
                            <div class="block_<?php echo $block2_width ?>">
        	                    <?php $modules->mosLoadModules('user5', -2); ?>
        	                </div>        
                    </div><!--block2:end-->
                <?php } ?>                
            
            </div><!--content:end-->
            
            
            <div class="col">
                <?php $modules->mosLoadModules('left',-2); ?>
                <?php $modules->mosLoadModules('banner',-2); ?>            
            </div><!--col:end-->
            
            
            <?php if($block3_count) {
                $block3_width = 'w' .$block3_count;
            ?>
                <div class="block3">
                    <div class="block3_bottom">
                            <div class="block_<?php echo $block3_width ?>">
        	                    <?php $modules->mosLoadModules('user7', -2); ?>
        	                </div>
                    </div>               
                </div><!--block3:end-->
            <?php } ?>            
        
        </div><!--wrapper:end-->
        
   
    </div> <!--main_wrap:end-->
    

    <div class="footer">
        <div class="bottom">
            <a title="Работает на системе управления сайтами Joostina CMS" href="http://www.joostina.ru" target="_blank" id="about" class="bottom_bar">Работает на Joostina CMS</a>
            <?php $modules->mosLoadModules('bottom',-1); ?>
        </div>    
    </div><!--footer:end-->
    
    
<?php

//подключаем js-скрипт
$mainframe->addJS(JPATH_SITE.'/templates/'.JTEMPLATE.'/js/corners.js', 'js');
//подключаем js-файл шаблона
$mainframe->addJS(JPATH_SITE.'/templates/'.JTEMPLATE.'/js/template.js', 'custom');

// выводим js футера (первая ступень - в основном jQuery-плагины и вспомагательные скрипты)
$page->_footer(array('js'=>1));
// выводим js футера (вторая ступень - js компонентов, инициализации для плагинов и т.п. - 
//всё, что должно быть загружено после всех основных скриптов)
$page->_footer(array('custom'=>1));
?>

</body>
</html>
