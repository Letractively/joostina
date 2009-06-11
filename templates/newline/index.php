<?php
defined('_VALID_MOS') or die();
$iso = explode('=',_ISO);
echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>'."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $iso[1];?>" />
<?php 


	// загружаемверхнюю часть страницы со всеми js и css файлами, и обязательным использованием jquery
	mosShowHead(array('js'=>1,'css'=>1,'jquery'=>1));	

	
	global $task,$my,$mosConfig_live_site, $mosConfig_mailfrom;
	if ($my->id && $mainframe->allow_wysiwyg) { initEditor(); }
	$block1_count = (mosCountModules('user1')>0) + (mosCountModules('user2')>0) + (mosCountModules('user3')>0);
	$block2_count = (mosCountModules('user4')>0) + (mosCountModules('user5')>0) + (mosCountModules('user6')>0);
	$block3_count = (mosCountModules('user7')>0) + (mosCountModules('user8')>0) + (mosCountModules('user9')>0);
?>

<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
<link href="<?php echo $mosConfig_live_site; ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->


<script type="text/javascript">
    var _live_site = '<?php echo $mosConfig_live_site;?>';
    var _option = '<?php echo mosGetParam( $_REQUEST, 'option', '' );?>';
    var _js_defines = new Array();
</script>
</head>
<!--body:begin-->
<body class="joo_flex">
    <!--main_wrap:begin-->
    <div class="main_wrap">
        <!--wrapper:begin-->
        <div class="wrapper">
            <!--header:begin-->
            <div class="header">
                <a href="<?php echo $mosConfig_live_site;?>" id="logo">&nbsp;</a>
                <div class="header_center">
                    <?php mosLoadModules('header',-1); ?>
                    <div class="top_menu_1">
                        <div class="top_menu_2">
                            <?php mosLoadModules('top',-1); ?>
                        </div>
                    </div>
                </div>
                <div class="header_right">
                    <a title="Главная" href="<?php echo $mosConfig_live_site;?>" id="home" class="navbar">&nbsp;</a>
                    <a title="Обратная связь" href="mailto:<?php echo $mosConfig_mailfrom;?>" id="mail" class="navbar">&nbsp;</a>
                    <a title="Карта сайта" href="<?php echo sefRelToAbs('index.php?option=com_xmap&amp;Itemid=27'); ?>" id="map" class="navbar">&nbsp;</a>
                    <?php mosLoadModules('toolbar',-2); ?>
               </div>
            <!--header:end-->
            </div>
            <!--block1:begin-->
            <div class="block1">
                <?php if($block1_count) {
                    $block1_width = 'w' .$block1_count;
                ?>
                    <?php if(mosCountModules('user1')) { ?>
                    <div class="block_<?php echo $block1_width ?>">
        	            <?php mosLoadModules('user1', -2); ?>
        	        </div>
                    <?php } ?>

                    <?php if(mosCountModules('user2')) { ?>
                    <div class="block_<?php echo $block1_width ?>">
        	            <?php mosLoadModules('user2', -2); ?>
        	        </div>
                    <?php } ?>
                    <?php if(mosCountModules('user3')) { ?>
                    <div class="block_<?php echo $block1_width ?>">
        	            <?php mosLoadModules('user3', -2); ?>
        	        </div>
                    <?php } ?>
                <?php } ?>
            <!--block1:end-->
            </div>
            <!--content:begin-->
            <div class="content">
                <?php mosMainbody(); ?> <br />
                <?php if($block2_count) {
                    $block2_width = 'w' .$block2_count;
                ?>
                    <!--block2:begin-->
                    <div class="block2">
                        <?php if(mosCountModules('user4')) { ?>
                            <div class="block_<?php echo $block2_width ?>">
        	                    <?php mosLoadModules('user4', -2); ?>
        	                </div>
                        <?php } ?>
                        <?php if(mosCountModules('user5')) { ?>
                            <div class="block_<?php echo $block2_width ?>">
        	                    <?php mosLoadModules('user5', -2); ?>
        	                </div>
                        <?php } ?>
                        <?php if(mosCountModules('user6')) { ?>
                            <div class="block_<?php echo $block2_width ?>">
                              <?php mosLoadModules('user6', -2); ?>
                           </div>
                        <?php } ?>
                    <!--block2:end-->
                    </div>
                <?php } ?>
            <!--content:end-->
            </div>
            <!--col:begin-->
            <div class="col">
                <?php mosLoadModules('left',-2); ?>
                <?php mosLoadModules('banner',-2); ?>
            <!--col:end-->
            </div>
            <?php if($block3_count) {
                $block3_width = 'w' .$block3_count;
            ?>
                <!--block3:begin-->
                <div class="block3">
                    <div class="block3_bottom">
                        <?php if(mosCountModules('user7')) { ?>
                            <div class="block_<?php echo $block3_width ?> w25">
        	                    <?php mosLoadModules('user7', -2); ?>
        	                </div>
                        <?php } ?>
                        <?php if(mosCountModules('user8')) { ?>
                            <div class="block_<?php echo $block3_width ?> w35">
        	                    <?php mosLoadModules('user8', -2); ?>
        	                </div>
                        <?php } ?>
                        <?php if(mosCountModules('user9')) { ?>
                            <div class="block_<?php echo $block3_width ?> w35" >
        	                    <?php mosLoadModules('user9', -2); ?>
        	                </div>
                        <?php } ?>
                    </div>
               <!--block3:end-->
                </div>
            <?php } ?>
        <!--wrapper:end-->
        </div>
    <!--main_wrap:end-->
    </div>
    <!--footer:begin-->
    <div class="footer">
        <div class="bottom">
            <a title="Работает на системе управления сайтами Joostina CMS" href="http://www.joostina.ru" target="_blank" id="about" class="bottom_bar">&nbsp;</a>
            <?php mosLoadModules('bottom',-1); ?>
            <div class="valid">
                <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo $mosConfig_live_site;?>" target="_blank" title="CSS Validity" style="text-decoration: none;">
		            <img src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/w3c_css.png" border="none" alt="CSS Validity" />
		        </a>
		        <a href="http://validator.w3.org/check/referer" target="_blank" title="XHTML Validity" style="text-decoration: none;">
		            <img src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $mainframe->getTemplate(); ?>/images/w3c_xhtml.png" border="none" alt="XHTML Validity" />
		        </a>
            </div>
        </div>
    <!--footer:end-->
    </div>
<?php


	// подключаем в футер плагин Jquery
	mosCommonHTML::loadJqueryPlugins('corner', false, false, 'js');


// выводим js футера (первая ступень - в основном jQuery-плагины и вспомагательные скрипты)
mosShowFooter(array('js'=>1));
// выводим js футера (вторая ступень - js компонентов, инициализации для плагинов и т.п. - 
//всё, что должно быть загружено после всех основных скриптов)
mosShowFooter(array('custom'=>1));


?>

<script type="text/javascript">
$(document).ready(function(){
	$('div.moduletable-round').corner();
	$('div.block2 h3').corner();
});
</script>
<!--body:end-->
</body>
</html>
