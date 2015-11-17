<?php
namespace Helpers;

/*
 * class to get input data from _REQUEST and _POST
 */
class Request
{

    public static function get($paramName, $escapeHtml = true, $allowBasicFormatTags = false)
    {
        return \Helpers\Utilities::escapeString($_GET[$paramName], $escapeHtml, $allowBasicFormatTags);
    }

    public static function post($paramName, $escapeHtml = true, $allowBasicFormatTags = false)
    {
        if (is_array($paramName)) {
            $postArray = array();
            for ($i = 0; $i < count($paramName); $i ++) {
                $key = $aliasFields[$i] != '' ? $aliasFields[$i] : $paramName[$i];
                if ($escapeHtml) {
                    $postArray[$key] = \Helpers\Utilities::escapeString($_POST[$paramName[$i]], $escapeHtml, $allowBasicFormatTags);
                } else {
                    $postArray[$key] = $_POST[$paramName[$i]];
                }
            }
            return $postArray;
        } else {
            if (is_array($_POST[$paramName])) {
                $postArray = array();
                foreach ($_POST[$paramName] as $key => $value) {
                    if ($escapeHtml) {
                        $postArray[$key] = \Helpers\Utilities::escapeString($_POST[$paramName][$key], $escapeHtml, $allowBasicFormatTags);
                    } else {
                        $postArray[$key] = $_POST[$paramName][$key];
                    }
                }
                return $postArray;
            } else {
                return \Helpers\Utilities::escapeString($_POST[$paramName], $escapeHtml, $allowBasicFormatTags);
            }
        }
    }

    public static function getPostParams($postFields, $aliasFields = null, $escapeHtml = true, $allowBasicFormatTags = false)
    {
        $postArray = array();
        for ($i = 0; $i < count($postFields); $i ++) {
            $key = $aliasFields[$i] != '' ? $aliasFields[$i] : $postFields[$i];
            if ($escapeHtml) {
                $postArray[$key] = \Helpers\Utilities::escapeString($_POST[$postFields[$i]], $escapeHtml, $allowBasicFormatTags);
            } else {
                $postArray[$key] = $_POST[$postFields[$i]];
            }
        }
        return $postArray;
    }

    public static function files($fieldNames, $getMetaData = false)
    {
        
        // validations
        if (empty($_FILES[$fieldNames])) {
            return false;
        } else 
            if ($_FILES[$fieldNames]['name'] == '' || $_FILES[$fieldNames]['tmp_name'] == '') {
                return [
                    'error' => 'Invalid upload'
                ];
            } else 
                if (! is_uploaded_file($_FILES[$fieldNames]['tmp_name'])) {
                    return false;
                } else 
                    if ($_FILES[$fieldNames]['error'] != '') {
                        return [
                            'error' => $_FILES[$fieldNames]['error']
                        ];
                    } else 
                        if ($_FILES[$fieldNames]['size'] > UPLOAD_MAX_FILESIZE) { // from app_config.php
                            return [
                                'error' => 'File size exceeds maximum allowed size of 50MB'
                            ];
                        } else 
                            if (\Helpers\Utilities::strposArray($_FILES[$fieldNames]['name'], [
                                "/",
                                "\\",
                                '..',
                                '"',
                                "'"
                            ]) !== FALSE) {
                                return [
                                    'error' => 'File name contains invalid characters'
                                ];
                            } else {
                                // validations ok, so return data
                                return $_FILES[$fieldNames];
                            }
    }

    public static function cookie($paramName, $escapeHtml = true, $allowBasicFormatTags = false)
    {
        return \Helpers\Utilities::escapeString($_COOKIE[$paramName], $escapeHtml, $allowBasicFormatTags);
    }

    /**
     * detect if request is Ajax
     *
     * @static static method
     * @return boolean
     */
    public static function isAjax()
    {
        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        }
        return false;
    }

    /**
     * detect if request is POST request
     *
     * @static static method
     * @return boolean
     */
    public static function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    /**
     * detect if request is GET request
     *
     * @static static method
     * @return boolean
     */
    public static function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }
}
