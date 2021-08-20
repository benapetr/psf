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

class ComboBoxItem extends HtmlElement
{
    public $Enabled = true;
    public $Value = NULL;
    public $Selected = false;
    public $Text = NULL;

    public function __construct($_value = NULL, $_text = NULL, $_parent = NULL)
    {
        $this->Value = $_value;
        $this->Text = $_text;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        $_e = "<option";
        if ($this->Selected)
            $_e .= ' selected="selected"';
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        if ($this->Value !== NULL)
            $_e .= ' value="' . $this->Value . '"';
        $_e .= ">";
        $_e .= $this->Text . "</option>";
        return $_e;
    }
}

class ComboBox extends HtmlElement
{
    public $Multiple = false;
    public $Enabled = true;
    public $Name;
    public $Autofocus = false;
    public $OnChangeCallback = NULL;
    public $Items = [];

    public function __construct($_name = NULL, $_parent = NULL)
    {
        $this->Name = $_name;
        parent::__construct($_parent);
    }

    public function AddDefaultValue($value, $text = NULL)
    {
        if ($text === NULL)
            $text = $value;
        $item = new ComboBoxItem($value, $text, $this);
		$item->Selected = true;
        $this->Items[] = $item;
    }

    public function AddValue($value, $text = NULL)
    {
        if ($text === NULL)
            $text = $value;
        $item = new ComboBoxItem($value, $text, $this);
        $this->Items[] = $item;
    }

    public function ToHtml()
    {
        $_e = "<select";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        if ($this->OnChangeCallback !== NULL)
            $_e .= ' onchange="' . $this->OnChangeCallback . '"';
        if (!$this->Enabled)
            $_e .= " disabled";
        $_e .= ">\n";
        foreach ($this->Items as $item)
            $_e .= "  " . $item->ToHtml() . "\n";
        $_e .= "</select>";
        return $_e;
    }
}
