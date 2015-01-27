<div class="box pav-store">
    <h3 class="box-heading">
        <span><?php echo $heading_title; ?></span>
    </h3>
    <section class="content wrapper">
        <p style="text-align: center;"><?php echo $text_store; ?></p>
        <?php foreach ($stores as $store) { ?>
        <?php if ($store['store_id'] == $store_id) { ?>
        <p><a href="<?php echo $store['url']; ?>"><b><?php echo $store['name']; ?></b></a></p>
        <?php } else { ?>
        <p><a href="<?php echo $store['url']; ?>"><?php echo $store['name']; ?></a></p>    
        <?php } ?>
        <?php } ?>    
    </section>
</div>
