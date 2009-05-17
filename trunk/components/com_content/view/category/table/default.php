<!--Страница категории:BEGIN-->
<div class="category_page<?php echo $sfx;?>">

    <!--Заголовок страницы:BEGIN-->
    <?php if ($page_title) {?>
	    <div class="componentheading"><h1><?php echo $page_title;?></h1></div>
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


    <!--Таблица с содержимым при просмотре категории:BEGIN-->
    <?php  if ($items) {
        //Подключаем шаблон вывода таблицы с записями
        include_once($mosConfig_absolute_path.'/components/com_content/view/item/table_of_items/default.php');
    } else 	if ($catid) { ?>
	    <br />
	    <?php echo _EMPTY_CATEGORY;?>
		<br /><br />
	<?php } ?>
    <!--Таблица с содержимым при просмотре категории:END-->



    <!--Кнопка добавления содержимого-->
    <?php if ($add_button ) { ?>
        <div class="add_button"><?php echo $add_button ;?></div>
    <?php } ?>


    <!--Список категорий раздела:BEGIN-->
	<?php if ($show_categories) {
	    include_once($mosConfig_absolute_path.'/components/com_content/view/category/show_categories/default.php');
	    //HTML_content :: showCategories($params, $items, $gid, $other_categories, $catid, $id, $Itemid);
	} ?>
    <!--Список категорий раздела:END-->


	<?php mosHTML :: BackButton($params); ?>

    <!--Основное содержимое страницы:END-->
    </div>

<!--Страница категории:END-->
</div>
