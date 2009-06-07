<!--Страница раздела:BEGIN-->
<div class="section_page<?php echo $sfx;?>">

    <!--Заголовок страницы:BEGIN-->
    <?php if ($page_title) {?>
	    <div class="componentheading<?php echo $sfx;?>"><h1><?php echo $page_title;?></h1></div>
	<?php }?>
    <!--Страница разделе:END-->

    <!--Основное содержимое страницы:BEGIN-->
    <div class="contentpane<?php echo $sfx;?>">


    <!--Описание:BEGIN-->
    <?php if ($title_description || $title_image) { ?>
	    <div class="contentdescription">
            <?php if($title_image){
                ?>
                    <div class="desc_img">
                        <?php echo $title_image;?>
                    </div>
                <?php
            }?>
            <?php if($title_description){
                ?>
                    <p>
                        <?php echo $title_description;?>
                    </p>
                <?php
            }?>
        </div>
	<?php } ?>
    <!--Описание:END-->

    <!--Кнопка добавления содержимого-->
    <?php if ($add_button ) { ?>
        <div class="add_button"><?php echo $add_button ;?></div>
    <?php } ?>


    <!--Список категорий раздела:BEGIN-->
	<?php include_once($mosConfig_absolute_path.'/components/com_content/view/section/catlist_list/default.php'); ?>
    <!--Список категорий раздела:END-->


	<?php mosHTML :: BackButton($params); ?>

    <!--Основное содержимое страницы:END-->
    </div>

<!--Страница раздела:END-->
</div>
