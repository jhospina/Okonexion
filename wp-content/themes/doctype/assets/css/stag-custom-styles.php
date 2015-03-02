<?php
header("Content-type: text/css");

if(file_exists('../../../../wp-load.php')) :
  include '../../../../wp-load.php';
else:
  include '../../../../../wp-load.php';
endif;

@ob_flush();
?>
/**
 * This file contains styles related to the user settings of the theme
 */

<?php
$output = '';
echo apply_filters('stag_custom_styles', $output);
@ob_end_flush();
?>
