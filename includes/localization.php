<?php

// Part of simple php framework (spf)

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2017

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/../default_config.php");

class Language
{
    protected $data = [];

    function Get($key)
    {
        if (!array_key_exists($key, $this->data))
            return NULL;
        return $this->data[$key];
    }

    function __construct($datafile)
    {
        $handle = fopen("$datafile", "r");
        if (!$handle)
            throw new Exception('Unable to load ' . $datafile);
        while (($line = fgets($handle)) !== false)
        {
            $line = str_replace("\n", "", $line);
            // process the line read.
            if (psf_string_startsWith($line, "//"))
                continue;
            if (!strpos($line, ":"))
                continue;
            $key = substr($line, 0, strpos($line, ':'));
            $value = substr($line, strpos($line, ':') + 1);
            $this->data[$key] = $value;
        }
    }
}

//! Localization system
class Localization
{
    protected static $__is_initialized = false;
    protected static $fallback = NULL;
    protected static $keys = [];
    protected static $dl;

    public static function SetDefaultLanguage($id)
    {
        self::Initialize();
        if (!array_key_exists($id, self::$keys))
            throw new Exception('Unknown language id');
    }

    public static function Get($key, $l = NULL)
    {
        self::Initialize();
        $x = self::$dl->Get($key);
        if (!$x && self::$fallback != self::$dl)
            $x = self::$fallback->Get($key);
        if (!$x)
            return "[$key]";
        return $x;
    }

    public static function SetLanguage($id)
    {
        self::Initialize();
        if (!array_key_exists($id, self::$keys))
            throw new Exception('Unknown language');

        self::$dl = self::$keys[$id];
    }

    public static function Initialize($folder = NULL)
    {
        global $psf_localization, $psf_localization_default_language;
        if (Localization::$__is_initialized)
            return;

        self::$__is_initialized = true; 

        if ($folder === NULL)
            $folder = $psf_localization;

        if (!file_exists($folder))
            throw new Exception("Localization folder " . $folder . " can't be found");

        if (is_file($folder))
            throw new Exception("Localization folder " . $folder . " is a file");

        $files = scandir($folder);

        foreach ($files as $file)
        {
            if (!psf_string_endsWith($file, ".txt"))
                continue;

            $id = substr($file, 0, strlen($file) - 4);
            self::$keys[$id] = new Language($folder . "/" . $file);
            self::$dl = self::$keys[$id];
        }
        if (array_key_exists($psf_localization_default_language, self::$keys))
            self::$dl = self::$keys[$psf_localization_default_language];
        self::$fallback = self::$dl;
    }
}
