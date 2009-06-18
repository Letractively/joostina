<?php $k = 0; ?>
    <div class="page_sectionblog<?php echo $sfx ;?>">

        <?php if($header && $params->get('page_title')) { ?>
		    <div class="componentheading"><h1><?php echo $header;?></h1></div>
	    <?php }?>

        <?php if($total) { ?>
            <div class="groupcats">

            <?php if($display_desc) { ?>
                <div class="contentdescription">

        			<?php if($display_desc_img) { ?>
        			    <img src="<?php echo $mosConfig_live_site;?>/images/stories/<?php echo $description->image;?>" align="<?php echo $description->image_position;?>"  alt="" />
        			<?php } ?>

                    <?php if($display_desc_text) { ?>
        				<p> <?php echo $description->description;?> </p>
        			<?php } ?>

    			</div>
		    <?php } ?>

<?php

	
	
	foreach($cats_arr as $key=>$v){
		echo '<h2 class="category_name">'.$v['cat_name'] . '</h2>';

		echo '<table>';
		$kk = 0; echo '<tr>';
			
		foreach($v['obj'] as $row){
			
			
		 
			
			echo '<td>'; 
			_showItem($row,$params,$gid,$access,$pop, 'intro/simple/default.php');
			echo '</td>';
			
			$kk++;

			
		
			if( $kk % $columns == 0 && (isset($cats_arr[$row->catid]['obj'][$kk]) && $cats_arr[$row->catid]['obj'][$kk]->catid == $row->catid )  ){
					echo "</tr><tr>";
			}
		}
		
		echo '</tr></table>';
		$cat_link = sefRelToAbs('index.php?option=com_content&amp;task=blogcategory&amp;id='.$key.'&amp;Itemid='.$_REQUEST['Itemid']);
  		echo '<div class="readmore"><a class="readmore" href="'.$cat_link.'">все статьи</a></div>';
	}

?>



        <?php if($display_blog_more){ ?>
			<div class="blog_more">
			    <?php  HTML_content::showLinks($rows,$links,$total,$i,$showmore);?>
			</div>
       <?php } ?>

       <?php if($display_pagination){
	        echo $pageNav->writePagesLinks($link);
            if($display_pagination_results){
    			echo $pageNav->writePagesCounter();
            }
       } ?>


       </div>

       <?php } else { echo _EMPTY_BLOG; }

        mosHTML::BackButton($params);

        ?>

    </div>
