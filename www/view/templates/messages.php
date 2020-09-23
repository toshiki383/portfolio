<?php foreach(get_errors() as $error){ ?>
  <p style="color:red;"><span><?php print $error; ?></span></p>
<?php } ?>
<?php foreach(get_messages() as $message){ ?>
  <p style="color:blue;"><span><?php print $message; ?></span></p>
<?php } ?>