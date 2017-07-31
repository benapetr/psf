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

class TextBox extends HtmlElement
{
    public $Enabled = true;
    public $Name;
    public $Value;
    private $Multiline = false;
    public $Rows = NULL;

    public function __construct($_name = NULL, $_value = NULL, $_parent = NULL)
    {
        $this->Name = $_name;
        $this->Value = $_value;
        parent::__construct($_parent);
    }

    public function SetMultiline()
    {
        $this->DisableIndenting();
        $this->Multiline = true;
        $this->Rows = 6;
    }

    public function ToHtml()
    {
        if (!$this->Multiline)
            $_e = "<input type=\"text\"";
        else
            $_e = "<textarea";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Rows !== NULL)
            $_e .= " rows=\"$this->Rows\"";
        if (!$this->Multiline && $this->Value !== NULL)
            $_e .= " value=\"$this->Value\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        $_e .= ">";
        if ($this->Multiline)
        {
            if ($this->Value !== NULL)
            {
                $_e .= htmlspecialchars($this->Value);
            }
            $_e .= "</textarea>";
        }
        return $_e;
    }
}
