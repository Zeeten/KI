<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (!empty($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		 <tr>
            <td><?php echo $entry_module; ?></td>
            <td>
            <select name="citrus_module">
              <?php $cm=explode('|',$entry_module_id);foreach($cm as $m){?>
                <?php if ($citrus_module == $m) { ?>
                <option value="<?php echo $m; ?>" selected="selected"><?php echo $m; ?></option>
                <?php } else { ?>
                <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                <?php }} ?>
              </select>
              <?php if (!empty($error_module)) { ?>
              <span class="error"><?php echo $error_module; ?></span>
              <?php } ?>
              
                            
              </td>
          </tr>
		   <tr>
            <td><?php echo $entry_vanityurl; ?></td>
            <td><input type="text" name="citrus_vanityurl" value="<?php echo $citrus_vanityurl; ?>" />
              <?php if (!empty($error_vanityrul)) { ?>
              <span class="error"><?php echo $error_vanityrul; ?></span>
              <?php } ?></td>
          </tr>
		  
		  <tr>
            <td><?php echo $entry_access_key; ?></td>
            <td><input type="text" name="citrus_access_key" value="<?php echo $citrus_access_key; ?>" />
              <?php if (!empty($error_accesskey)) { ?>
              <span class="error"><?php echo $error_accesskey; ?></span>
              <?php } ?></td>
          </tr>
         
		  <tr>
            <td><?php echo $entry_secret_key; ?></td>
            <td><input type="text" name="citrus_secret_key" value="<?php echo $citrus_secret_key; ?>" />
              <?php if (!empty($error_secretkey)) { ?>
              <span class="error"><?php echo $error_secretkey; ?></span>
              <?php } ?></td>
          </tr>
                    
          <!--order_status-->
          
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="citrus_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $citrus_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          
          <!--order_status-->          
          
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="citrus_status">
                <?php if ($citrus_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="citrus_sort_order" value="<?php echo $citrus_sort_order; ?>" size="1" /></td>
          </tr>
          
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>