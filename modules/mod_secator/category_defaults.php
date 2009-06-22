<ul class="cat_list">
<?php  $i = 1;

    foreach($rows as $row){
    ?>
        <li class="cat_<?php echo $i;?>"><a href="<?php echo _get_secator_link($row,$params);?>"><?php echo $row->title;?></a></li>
    <?php $i++;
    }
  ?>

  </ul>