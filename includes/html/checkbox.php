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

class CheckBox extends HtmlElement
{
    public $Enabled = true;
    public $Name;
    public $Value;
    public $Title;
    public $Text = NULL;
    public $Checked;
    //! Change to name of JS function to call when checkbox is clicked
    public $OnClickCallback = NULL;

    public function __construct($_name = NULL, $_value = NULL, $_checked = false, $_parent = NULL, $_text = NULL)
    {
        $this->Checked = $_checked;
        $this->Name = $_name;
        $this->Value = $_value;
        $this->Text = $_text;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        if ($this->IsVisible === false)
            return "";
        $_e = "<input type=\"checkbox\"";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Value !== NULL)
            $_e .= " value=\"$this->Value\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        if ($this->Title !== NULL)
            $_e .= " title=\"$this->Title\"";
        if ($this->OnClickCallback !== NULL)
            $_e .= ' onclick="' . $this->OnClickCallback . '"';
        if ($this->Checked)
            $_e .= " checked";
        if (!$this->Enabled)
            $_e .= " disabled readonly";
        $_e .= ">";
        if ($this->Text !== NULL)
            $_e = "<label>$_e" . $this->Text . "</label>";
        return $_e;
    }
}
