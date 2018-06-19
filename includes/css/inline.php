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

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2018

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/css.php");

//! Represent a single Html page
class InlineCSS extends CSS
{
    function __construct($_parent = NULL)
    {
        parent::__construct($_parent);
    }

    protected function Load()
    {
        if ($this->BackgroundColor !== NULL)
            $this->items['_inline_']['background-color'] = $this->BackgroundColor;
    }

    public function SetProperty($name, $value)
    {
        $this->items['_inline_'][$name] = $value;
    }

    public function GetProperty($name)
    {
        return $this->items['_inline_'][$name];
    }

    public function ToCss($n = 0)
    {
        $this->Load();
        if (!array_key_exists("_inline_", $this->items))
           return;
        $buff = '';
        foreach ($this->items['_inline_'] as $name => $values)
        {
            $buff .= $name . ': ' . $values . ";";
        }
        return $buff;
    }
}

