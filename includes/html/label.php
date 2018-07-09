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

require_once (dirname(__FILE__) . "/element.php");

class Label extends HtmlElement
{
    public $For;
    public $Value;

    public function __construct($_for = NULL, $_value = NULL, $_parent = NULL)
    {
        $this->For = $_for;
        $this->Value = $_value;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        $_e = "<label";
        if ($this->For !== NULL)
            $_e .= " for=\"$this->For\"";
        $_e .= ">";
        if ($this->Value !== NULL)
            $_e .= $this->Value;
        $_e .= "</label>";
        return $_e;
    }
}
