<?php
/**
 * Sample layout
 */
use Helpers\Assets;
use Helpers\Url;
use Helpers\Hooks;

// initialise hooks
$hooks = Hooks::get();
?>

</div>

<!-- JS -->
<?php
echo Assets::js(array(
    Url::getTemplateAssetsPath() . 'js/jquery.js',
    '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'
));

// hook for plugging in javascript
$hooks->run('js');

// hook for plugging in code into the footer
$hooks->run('footer');
?>

</body>
</html>
