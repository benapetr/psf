<?php
require (dirname(__FILE__) . "/../default_config.php");

class SystemLog
{
    public static function Write($text)
    {
        global $psf_log;
        file_put_contents($psf_log, $text.PHP_EOL , FILE_APPEND);
    }
}
