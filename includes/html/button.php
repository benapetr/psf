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

class Button extends HtmlElement
{
    public $Name;
    public $Value;

    public function __construct($_name = NULL, $_value = NULL, $_parent = NULL)
    {
        $this->Name = $_name;
        $this->Value = $_value;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        $_e = "<input type=\"submit\"";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Value !== NULL)
            $_e .= " value=\"$this->Value\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        $_e .= ">";
        return $_e;
    }
}
