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

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2018

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/element.php");

class Script extends HtmlElement
{
    public $Source;

    function __construct($_src = "", $_parent = NULL)
    {
        $this->Source = $_src;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
       $_b = "<script>\n";
       $_b .= $this->Source;
       $_b .= "</script>";
       return $_b;
    }
}

