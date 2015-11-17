<?php
/**
 * Sample layout
 */

use Core\Locale;

?>

<div class="page-header">
	<h1>Sub folder called</h1>
</div>

<p><?php echo $data['welcome_message'] ?></p>

<a class="btn btn-md btn-success" href="<?php echo DIR;?>subpage">
	<?php echo Locale::show('open_subpage', 'Welcome'); ?>
</a>
