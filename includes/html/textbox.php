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

class TextBox extends HtmlElement
{
    public $Enabled = true;
    public $Name;
    //! If input should remember history
    public $Autocomplete = true;
    public $Value;
    private $Multiline = false;
    public $Rows = NULL;
    public $ReadOnly = false;
    public $Required = false;
    //! If true, this text box will be acting as password input
    public $Password = false;
    public $Placeholder = NULL;
    public $Size = NULL;
    //! Change to name of JS function to call when textbox content is modified
    public $OnChangeCallback = NULL;

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
        if ($this->IsVisible === false)
            return "";
        if ($this->Password)
            $_e = "<input type=\"password\"";
        else if (!$this->Multiline)
            $_e = "<input type=\"text\"";
        else
            $_e = '<textarea';

        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Rows !== NULL)
            $_e .= " rows=\"$this->Rows\"";
        if ($this->Size !== NULL)
            $_e .= " size=\"$this->Size\"";
        if (!$this->Multiline && $this->Value !== NULL)
            $_e .= " value=\"" . htmlspecialchars($this->Value) . "\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        if ($this->ReadOnly === true || $this->Enabled === false)
            $_e .= " readonly";
        if ($this->Placeholder !== NULL)
            $_e .= " placeholder=\"" . $this->Placeholder . "\"";
        if ($this->OnChangeCallback !== NULL)
            $_e .= " onChange=\"" . $this->OnChangeCallback . "\"";
        if ($this->Required === true)
            $_e .= " required";
        if ($this->Autocomplete === false)
            $_e .= " autocomplete=\"off\"";
        $_e .= ">";
        if ($this->Multiline)
        {
            if ($this->Value !== NULL)
            {
                $_e .= htmlspecialchars($this->Value);
            }
            $_e .= '</textarea>';
        }
        return $_e;
    }
}
