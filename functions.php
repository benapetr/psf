<?php

//Part of simple php framework (spf)

//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.

//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

//Copyright Petr Bena 2015

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once(dirname(__FILE__) . "/definitions.php");

// String tools

function psf_string_startsWith($string, $text)
{
    return (strpos($string, $text) === 0);
}

function psf_version()
{
    return PSF_VERSION;
}

function psf_path($file = '')
{
    global $psf_home;
    return $psf_home . $file;
}

function psf_curl($link, $timeout=5)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

