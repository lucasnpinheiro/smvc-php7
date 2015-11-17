<?php
namespace Helpers;

/*
 * HTML Utilites class. Many of the methods are static
 */
class HTMLGenerators
{

    /*
     * Internal method to be used in other methods
     */
    protected function _styleDecider($style)
    {
        if (strpos($style, ':'))
            return ' style="' . $style . '"';
        else
            return ' class="' . $style . '"';
    }

    /*
     * Adds center div tags to the text
     */
    public static function center($string)
    {
        return '<div align="center">' . $string . '</div>';
    }

    /*
     * Adds div tag to the text
     */
    public static function div($string, $divID = false, $style = false)
    {
        if ($style)
            $style = $this->_styleDecider($style);
        if ($divID)
            $style = $style . ' id="' . $divID . '"';
        return '<div' . $style . '>' . $string . '</div>' . "\n";
    }

    /*
     * _____APPEARANCE TAGS_____
     */
    public static function para($str, $style = false)
    {
        if ($style)
            $style = $this->_styleDecider($style);
        return '<p' . $style . '>' . $str . '</p>' . "\n";
    }

    public static function heading($size, $text)
    {
        return '<h' . $size . '>' . $text . '</h' . $size . '>';
    }

    public static function strong($str)
    {
        return '<strong>' . $str . '</strong>';
    }

    public static function em($str)
    {
        return "<em>" . $str . "</em>";
    }

    public static function subScript($str)
    {
        return "<sub>" . $str . "</sub>";
    }

    public static function supScript($str)
    {
        return "<sup>" . $str . "</sup>";
    }

    public static function smallText($str)
    {
        return "<small>" . $str . "</small>";
    }

    public static function pre($content)
    {
        return "<pre>$content</pre>";
    }
    
    // _____OTHERS_____
    // Elements
    public static function comment($str)
    {
        return '<!--' . $str . '-->';
    }

    public static function hr()
    {
        return "<hr />\n";
    }

    public static function br()
    {
        return "<br />\n";
    }

    public static function nl()
    {
        return "\n";
    }
    
    // Hyperlink
    public static function hyperlink($text, $url, $target = false, $style = false)
    {
        if ($target)
            $target = ' target="' . $target . '"';
        if ($style)
            $style = $this->_styleDecider($style);
        $linker = '<a href="' . $url . '"' . $target . $style . '>' . $text . '</a>';
        return $linker;
    }
    
    // Image
    public static function image($srcUrl, $w = false, $h = false, $style = false, $altText = false)
    {
        $w = $w ? ' width="' . $w . '"' : '';
        $h = $h ? ' height="' . $h . '"' : '';
        $altText = $altText ? ' alt="' . $altText . '"' : '';
        if ($style)
            $style = $this->_styleDecider($style);
        return '<img src="' . $srcUrl . '"' . $w . $h . $altText . $style . '>';
    }
    
    // including css and js files and javascript code
    public static function cssLinkTag($cssFileName)
    {
        return '<link href="' . $cssFileName . '" rel="stylesheet" type="text/css" />' . "\n";
    }

    public static function jsScriptTag($jsFileName)
    {
        return '<script language="javascript" type="text/javascript" src="' . $jsFileName . '"></script>' . "\n";
    }
    
    // ----------------------------------
    // javascript functions
    public static function javascript($code)
    {
        return '<script language="javascript" type="text/javascript">' . $code . '</script>' . "\n";
    }

    public static function jsAlert($message)
    {
        if (! empty($message))
            return '<script language="javascript">alert("' . stripslashes($message) . ' "); </script>' . "\n";
    }
    
    // ----------------------------------
    // meta information
    public static function metaKeywords($str)
    {
        return "\n" . '<meta name="keywords" content="' . $str . '" />' . "\n";
    }

    public static function metaDescription($str)
    {
        return "\n" . '<meta name="description" content="' . $str . '" />' . "\n" . $this->metaInfo();
    }

    public static function metaInfo()
    { // internal
        return "\n" . '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="robots" content="all" />' . "\n";
    }

    public static function coloredText($text, $hexColorCode = '#000000', $displayAsPara = false)
    {
        // if second param, check for not null and then display it as seperate para.
        if ($displayAsPara) {
            if ($text)
                return self::para('<span style="color:' . $hexColorCode . '">' . $text . '</span>');
        } else
            return '<span style="color:' . $hexColorCode . '">' . $text . '</span>';
    }

    public static function redText($text)
    {
        return '<span style="color:#FF0000">' . $text . '</span>';
    }

    public static function jQueryUIMessage($message, $showAlert = false, $idName = '')
    {
        $returnString = '';
        if ($showAlert)
            $returnString = $returnString . '<script>alert("Message: ' . $message . '")</script>';
        
        $returnString = $returnString . '<div class="ui-widget" id="' . $idName . '">
		<div class="ui-state-highlight ui-corner-all" style="margin-top: 5px; padding: 5px;">
			<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
			<strong>Message: </strong>' . $message . '</p>
			</div>
		</div>
		';
        
        return $returnString;
    }

    public static function bootstrapUIError($error, $dismissable = false)
    {
        if ($dismissable) {
            $returnString = '<div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Error: </strong> ' . $error . '</div>';
        } else {
            $returnString = '<div class="alert alert-warning" role="alert"><strong>Error: </strong> ' . $error . '</div>';
        }
        return $returnString;
    }

    public static function bootstrapUIMessage($message, $dismissable = false)
    {
        if ($dismissable) {
            $returnString = '<div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Message: </strong> ' . $error . '</div>';
        } else {
            $returnString = '<div class="alert alert-warning" role="alert"><strong>Error: </strong> ' . $error . '</div>';
        }
        return $returnString;
    }

    public static function jQueryUIError($error, $showAlert = false, $idName = '')
    {
        if ($showAlert)
            $returnString = '<script>alert("Error: ' . $error . '")</script>';
        $returnString .= '<div class="ui-widget" id="' . $idName . '">
		<div class="ui-state-error ui-corner-all" style="margin-top: 5px; padding: 5px;"> 
			<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
			<strong>Error: </strong>' . $error . '</p>
			</div>
		</div>
		';
        return $returnString;
    }
}