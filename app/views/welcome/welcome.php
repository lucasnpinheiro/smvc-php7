<?php
/**
 * Sample layout
 */
use Core\Locale;

?>

<div class="page-header">
	<h1><?php echo $data['title'] ?></h1>
</div>

<p><?php echo $data['welcome_message'] ?></p>

<a class="btn btn-md btn-success" href="<?=DIR?>/welcome/subpage">
	<?php echo Locale::show('open_subpage', 'Welcome'); ?>
</a>

<a class="btn btn-md btn-info" href="<?=DIR ?>/test/module-controller">
<?php echo Locale::show('open_modulepage', 'Welcome'); ?></a>
