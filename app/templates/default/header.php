<?php
/**
 * Sample layout
 */
use Helpers\Assets;
use Helpers\Url;

?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>

<!-- Site meta -->
<meta charset="utf-8">
<title><?php echo $data['title'].' - '.SITE_TITLE; //SITE_TITLE defined in app/config.php ?></title>

<!-- CSS -->
	<?php
	echo Assets::css(array ('//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',Url::getTemplateAssetsPath() . 'css/style.css'));
	
	?>

</head>
<body>

	<div class="container">