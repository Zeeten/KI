<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $title; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
  <div style="width: 680px;">
      <div style="float: right; margin-left: 20px;">
         <a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>">
            <img src="<?php echo $image; ?>" alt="<?php echo $store_name; ?>" style="margin-bottom: 20px; border: none;" />
         </a>
      </div>
      <div>
         <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_greeting; ?></p>
         <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_from; ?></p>
         <?php if ($message) { ?>
         <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_message; ?></p>
         <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $message; ?></p>
         <?php } ?>
         <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_redeem; ?></p>
         <p style="margin-top: 0px; margin-bottom: 20px;"><a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><?php echo $store_url; ?></a></p>
         <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
      </div>
  </div>
</body>
</html>
