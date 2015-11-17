<?php

/**
 * Assets static helper
 *
 * @author volter9
 * @author QsmaPL
 * @date 27th November, 2014
 * @date updated Sept 19, 2015
 */
namespace Helpers;

/**
 * Collect and output css and js link tags.
 */
class Assets
{

    /**
     *
     * @var array Asset templates
     */
    protected static $templates = array(
        'js' => '<script src="%s" type="text/javascript"></script>',
        'css' => '<link href="%s" rel="stylesheet" type="text/css">'
    );

    /**
     * Common templates for assets.
     *
     * @param string|array $files            
     * @param string $template            
     */
    protected static function resource($files, $template): string
    {
        $template = self::$templates[$template];
        
        if (is_array($files)) {
            foreach ($files as $file) {
                return sprintf($template, $file) . "\n";
            }
        } else {
            return sprintf($template, $files) . "\n";
        }
    }

    /**
     * Output script.
     *
     * @param array|string $file/s            
     */
    public static function js($files): string
    {
        return static::resource($files, 'js');
    }

    /**
     * Output stylesheet.
     *
     * @param string $file            
     */
    public static function css($files): string
    {
        return static::resource($files, 'css');
    }
}
