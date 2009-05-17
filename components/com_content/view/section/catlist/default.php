<!--�������� �������:BEGIN-->
<div class="section_page<?php echo $sfx;?>">

    <!--��������� ��������:BEGIN-->
    <?php if ($page_title) {?>
	    <div class="componentheading<?php echo $sfx;?>"><h1><?php echo $page_title;?></h1></div>
	<?php }?>
    <!--�������� �������:END-->

    <!--�������� ���������� ��������:BEGIN-->
    <div class="contentpane<?php echo $sfx;?>">


    <!--��������:BEGIN-->
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
    <!--��������:END-->

    <!--������ ���������� �����������-->
    <?php if ($add_button ) { ?>
        <div class="add_button"><?php echo $add_button ;?></div>
    <?php } ?>


    <!--������ ��������� �������:BEGIN-->
	<?php if ($show_categories) {
	    include_once($mosConfig_absolute_path.'/components/com_content/view/category/show_categories/default.php');
	    //HTML_content :: showCategories($params, $items, $gid, $other_categories, $catid, $id, $Itemid);
	} ?>
    <!--������ ��������� �������:END-->


	<?php mosHTML :: BackButton($params); ?>

    <!--�������� ���������� ��������:END-->
    </div>

<!--�������� �������:END-->
</div>
