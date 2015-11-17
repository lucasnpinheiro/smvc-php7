<?php

/**
 * Logger class - Custom errors
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Sept 19, 2015
 */
namespace Core;

/**
 * Record and email/display errors or a custom error message.
 */
class Logger
{

    /**
     * Determins if error should be displayed.
     *
     * @var boolean
     */
    private static $printError = false;

    /**
     * Determins if error should be emailed to SITEEMAIL defined in app/config.php.
     *
     * @var boolean
     */
    private static $emailError = false;

    /**
     * Clear the errorlog.
     *
     * @var boolean
     */
    private static $clear = false;

    /**
     * Path to error file.
     *
     * @var boolean
     */
    public static function getCurrentErrorLog(): string
    {
        return SMVC . 'storage/logs/log-' . date('Y-m-d') . '.html';
    }

    /**
     * In the event of an error show this message.
     */
    public static function customErrorMsg()
    {
        echo "<p>An error occured, The error has been reported.</p>";
        exit();
    }

    /**
     * Saved the exception and calls customer error function.
     *
     * @param exeption $e            
     */
    public static function exceptionHandler($e)
    {
        self::newMessage($e);
        self::customErrorMsg();
    }

    /**
     * Saves error message from exception.
     *
     * @param numeric $number
     *            error number
     * @param string $message
     *            the error
     * @param string $file
     *            file originated from
     * @param numeric $line
     *            line number
     */
    public static function errorHandler($number, $message, $file, $line)
    {
        $msg = "$message in $file on line $line";
        
        if (($number !== E_NOTICE) && ($number < 2048)) {
            self::errorMessage($msg);
            self::customErrorMsg();
        }
        
        return 0;
    }

    /**
     * New exception.
     *
     * @param Exception $exception            
     * @param boolean $printError
     *            show error or not
     * @param boolean $clear
     *            clear the errorlog
     * @param string $errorFile
     *            file to save to
     */
    public static function newMessage(\Error $exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $trace = str_replace(DB_PASS, '********', $trace);
        $date = date('M d, Y G:iA');
        
        $logMessage = "<h3>Exception information:</h3>\n
           <p><strong>Date:</strong> {$date}</p>\n
           <p><strong>Message:</strong> {$message}</p>\n
           <p><strong>Code:</strong> {$code}</p>\n
           <p><strong>File:</strong> {$file}</p>\n
           <p><strong>Line:</strong> {$line}</p>\n
           <h3>Stack trace:</h3>\n
           <pre>{$trace}</pre>\n
           <hr />\n";
        
        $errorFile = self::getCurrentErrorLog();
        
        if (is_file($errorFile) === false) {
            file_put_contents($errorFile, '');
        }
        
        if (self::$clear) {
            $f = fopen($errorFile, "r+");
            if ($f !== false) {
                ftruncate($f, 0);
                fclose($f);
            }
            
            $content = null;
        } else {
            $content = file_get_contents($errorFile);
        }
        
        file_put_contents($errorFile, $logMessage . $content);
        
        if (self::$printError == true) {
            echo $logMessage;
            exit();
        }
    }

    /**
     * Custom error.
     *
     * @param string $error
     *            the error
     * @param boolean $printError
     *            display error
     * @param string $errorFile
     *            file to save to
     */
    public static function errorMessage($error)
    {
        $date = date('M d, Y G:iA');
        $logMessage = "<p>Error on $date - $error</p>";

        $errorFile = self::getCurrentErrorLog();
        
        if (is_file($errorFile) === false) {
            file_put_contents($errorFile, '');
        }
        
        if (self::$clear) {
            $f = fopen($errorFile, "r+");
            if ($f !== false) {
                ftruncate($f, 0);
                fclose($f);
            }
            
            $content = null;
        } else {
            $content = file_get_contents($errorFile);
            file_put_contents($errorFile, $logMessage . $content);
        }
        
        
        if (self::$printError == true) {
            echo $logMessage;
            exit();
        }
    }
}
