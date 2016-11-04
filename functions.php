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

//! Check if string starts with text
function psf_string_startsWith($string, $text)
{
    return (strpos($string, $text) === 0);
}

//! Check string ends with the text
function psf_string_endsWith($string, $text)
{
    $length = strlen($text);
    if ($length == 0)
        return true;

    return (substr($string, -$length) === $text);
}

//! Returns true if $string contains $text
function psf_string_contains($string, $text)
{
   return strpos($string, $text) !== false;
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

//! This function takes any text and turn it into function / variable friendly name
//! useful for proceduraly generated JS / CSS
function psf_generate_friendly_name($text)
{
    $text = str_replace(";", "", $text);
    $text = str_replace("&", "", $text);
    $text = str_replace("\"", "", $text);
    $text = str_replace(">", "", $text);
    $text = str_replace("<", "", $text);
    $text = str_replace(" ", "_", $text);
    $text = str_replace("(", "", $text);
    $text = str_replace(")", "", $text);
    $text = str_replace("/", "", $text);
    $text = strtolower($text);
    return $text;
}

function psf_indent_text($text, $in)
{
    $prefix = '';
    while ($in-- > 0)
        $prefix .= ' ';
    $result = '';
    $lines = explode("\n", $text);
    foreach ($lines as $line)
        $result .= $prefix . $line . "\n";
    return $result;
}

function _l($key)
{
    return Localization::Get($key);
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

