<?php

defined("_VALID_MOS") or die("Прямой вызов файла запрещён.");

$iso = explode('=',_ISO);

echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	mosShowHead();
    global $task,$my,$mosConfig_live_site;
	if ($my->id) { initEditor(); }
    $template_width = "90%";				// width in px | fluid
    $block1_count = (mosCountModules('user1')>0) + (mosCountModules('user2')>0) + (mosCountModules('user3')>0);
     $block2_count = (mosCountModules('user4')>0) + (mosCountModules('user5')>0) + (mosCountModules('user6')>0);
     $block3_count = (mosCountModules('user7')>0) + (mosCountModules('user8')>0) + (mosCountModules('user9')>0);
    
?>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/template_css.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $mainframe->getTemplate(); ?>/js/jquery.corner.js"></script>
<script type="text/javascript">
$(document).ready(function(){
        $('div.moduletable-round').corner();
        $('div.block2 h3').corner();
  });
</script>

<!--[if lte IE 6]>
<link href="<?php echo $mosConfig_live_site; ?>/templates/<?php echo $mainframe->getTemplate(); ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->

</head>

<body class="joo_flex">
<div class="main_wrap">



    <div class="wrapper">

       <div class="header">
            <a href="index.php" id="logo">&nbsp;</a>

            <div class="header_center">
                <?php mosLoadModules('header',-1); ?>
                <div class="top_menu_1">
                    <div class="top_menu_2">
                        <?php mosLoadModules('top',-1); ?>
                    </div>
                </div>
            </div>

           <div class="header_right">
            <a title="На главную" href="index.php" id="home" class="navbar">&nbsp;</a>
            <a title="Написать письмо" href="mailto:megazaisl@gmail.com" id="mail" class="navbar">&nbsp;</a>
            <a title="Карта сайта" href="map.php" id="map" class="navbar">&nbsp;</a>
          <?php mosLoadModules('toolbar',-2); ?>
            </div>

       </div>

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
        </div>


        <div class="content">
            <?php mosMainbody(); ?> <br />


        <?php if($block2_count) {
          $block2_width = 'w' .$block2_count;
        ?>
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
              </div>
         <?php } ?>
        </div>

        <div class="col">
           <?php mosLoadModules('left',-2); ?>
           <?php mosLoadModules('banner',-2); ?>
        </div>

        <?php if($block3_count) {
          $block3_width = 'w' .$block3_count;
        ?>
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
              </div>   </div>
         <?php } ?>


    </div>

</div>

    <div class="footer">
        <div class="bottom">
            <a title="О проекте" href="index.php" id="about" class="bottom_bar">&nbsp;</a>
            <?php mosLoadModules('bottom',-1); ?>

        </div>
    </div>
</body>
</html>
