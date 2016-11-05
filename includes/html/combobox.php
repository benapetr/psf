<?php

//Part of simple php framework (spf)
//
////This program is free software: you can redistribute it and/or modify
////it under the terms of the GNU General Public License as published by
////the Free Software Foundation, either version 3 of the License, or
////(at your option) any later version.
//
////This program is distributed in the hope that it will be useful,
////but WITHOUT ANY WARRANTY; without even the implied warranty of
////MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
////GNU General Public License for more details.
//
////Copyright Petr Bena 2015

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
    }

    public function ToHtml()
    {
        $_e = "<option";
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
    public $Items = [];

    public function __construct($_name = NULL, $_parent = NULL)
    {
        $this->Name = $_name;
        parent::__construct($_parent);
    }

    public function AddValue($value, $text)
    {
        $this->Items[] = new ComboBoxItem($value, $text, $this);
    }

    public function ToHtml()
    {
        $_e = "<select";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        $_e .= ">\n";
        foreach ($this->Items as $item)
            $_e .= "  " . $item->ToHtml() . "\n";
        $_e .= "</select>";
        return $_e;
    }
}
