<?php

//Part of simple php framework (spf)
//
////This program is free software: you can redistribute it and/or modify
////it under the terms of the GNU General Public License as published by
////the Free Software Foundation, either version 3 of the License, or
////(at your option) any later version.
//
////This program is distributed in the hope that it will be useful,
////but WITHOUT ANY WARRANTY; without even the implied warranty of
////MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
////GNU General Public License for more details.
//
////Copyright Petr Bena 2015

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/../default_config.php");

class SystemLog
{
    public static function Write($text)
    {
        global $psf_log;
        file_put_contents($psf_log, $text.PHP_EOL , FILE_APPEND);
    }

    public static function Warning($text)
    {
        SystemLog::Write("WARNING: " . $text);
    }

    public static function Error($text)
    {
        SystemLog::Write("ERROR: " . $text);
    }
}
