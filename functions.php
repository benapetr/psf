<?php

// Part of php simple framework (psf)

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2019

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once(dirname(__FILE__) . "/default_config.php");
require_once(dirname(__FILE__) . "/definitions.php");
require_once(dirname(__FILE__) . "/variables.php");


// PHP

//! Enable PHP debug
function psf_php_enable_debug()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// String tools

//! Check if string is NULL or empty, unlike php's empty() it really works
function psf_string_is_null_or_empty($string)
{
    if ($string === NULL)
        return true;
    
    if ($string === '')
        return true;

    return false;
}

//! Trim string is it's longer than $max
function psf_string_auto_trim($string, $max, $suffix = "")
{
    if (strlen($string) <= $max)
        return $string;
    return substr($string, 0, $max) . $suffix;
}

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
    global $psf_indent_system_enabled;
    if (!$psf_indent_system_enabled)
        return $text;
    $prefix = '';
    while ($in-- > 0)
        $prefix .= " ";
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

function psf_string2bool($str)
{
    $str = strtolower($str);
    if ($str == "true")
        return true;
    if ($str == "t")
        return true;
    return false;
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

function psf_debug_log($text)
{
    global $psf_global_debug_ring;
    $psf_global_debug_ring[] = $text;
}

function psf_print_debug_as_html()
{
    global $psf_global_debug_ring;
    $html = "";
    foreach ($psf_global_debug_ring as $log)
        $html .= "<!-- PSF Debug: " . htmlspecialchars($log) . " -->\n";
    echo($html);
}

//! Give you a time in secods since the psf was launched
function psf_get_execution_time()
{
    global $psf_global_startup_time;
    return (microtime(true) - $psf_global_startup_time);
}


