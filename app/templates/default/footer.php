<?php
/**
 * Sample layout
 */
use Helpers\ {
	Assets, 
	Url
};

?>

</div>

<!-- JS -->
<?php
echo Assets::js(array (Url::getTemplateAssetsPath() . 'js/jquery.js','//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'));

?>

</body>
</html>
