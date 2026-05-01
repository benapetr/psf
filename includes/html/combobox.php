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
    public $Editable = false;
    public $Multiple = false;
    public $Enabled = true;
    public $Name;
    public $Autofocus = false;
    public $OnChangeCallback = NULL;
    public $Items = [];
    public $EditableListId = NULL;

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
        return $item;
    }

    public function AddValue($value, $text = NULL)
    {
        if ($text === NULL)
            $text = $value;
        $item = new ComboBoxItem($value, $text, $this);
        $this->Items[] = $item;
        return $item;
    }

    public function AddDefaultItem(ComboBoxItem $item)
    {
        $item->Selected = true;
        $this->Items[] = $item;
    }

    public function AddItem(ComboBoxItem $item)
    {
        $this->Items[] = $item;
    }

    public function SetDefault($key)
    {
        foreach ($this->Items as $item)
            $item->Selected = ($key == $item->Value);
    }

    public function ToHtml()
    {
        if ($this->Editable && !$this->Multiple)
            return $this->ToEditableHtml();

        $_e = "<select";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        if ($this->OnChangeCallback !== NULL)
            $_e .= ' onchange="' . $this->OnChangeCallback . '"';
        if ($this->Multiple)
            $_e .= " multiple";
        if (!$this->Enabled)
            $_e .= " disabled";
        $_e .= ">\n";
        foreach ($this->Items as $item)
            $_e .= "  " . $item->ToHtml() . "\n";
        $_e .= "</select>";
        return $_e;
    }

    protected function GetSelectedValue()
    {
        foreach ($this->Items as $item)
        {
            if ($item->Selected)
                return ($item->Value !== NULL) ? $item->Value : $item->Text;
        }
        return NULL;
    }

    protected function GetEditableListId()
    {
        if ($this->EditableListId !== NULL)
            return $this->EditableListId;
        if ($this->Name !== NULL)
            return "datalist_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $this->Name);
        return "datalist_" . spl_object_hash($this);
    }

    protected function ToEditableHtml()
    {
        $list_id = $this->GetEditableListId();
        $_e = "<input type=\"text\"";
        if ($this->Name !== NULL)
            $_e .= " name=\"$this->Name\"";
        $_e .= " list=\"" . htmlspecialchars($list_id) . "\"";
        $selected_value = $this->GetSelectedValue();
        if ($selected_value !== NULL)
            $_e .= " value=\"" . htmlspecialchars($selected_value) . "\"";
        if ($this->Style !== NULL)
            $_e .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_e .= " class=\"" . $this->ClassName . "\"";
        if ($this->OnChangeCallback !== NULL)
            $_e .= ' onchange="' . $this->OnChangeCallback . '"';
        if (!$this->Enabled)
            $_e .= " disabled";
        $_e .= ">\n";
        $_e .= "<datalist id=\"" . htmlspecialchars($list_id) . "\">\n";
        foreach ($this->Items as $item)
        {
            $value = ($item->Value !== NULL) ? $item->Value : $item->Text;
            $_e .= "  <option";
            if ($value !== NULL)
                $_e .= " value=\"" . htmlspecialchars($value) . "\"";
            if ($item->Style !== NULL)
                $_e .= " style=\"" . $item->Style->ToCss() . "\"";
            if ($item->ClassName !== NULL)
                $_e .= " class=\"" . $item->ClassName . "\"";
            $_e .= ">";
            if ($item->Text !== NULL && $item->Text != $value)
                $_e .= htmlspecialchars($item->Text);
            $_e .= "</option>\n";
        }
        $_e .= "</datalist>";
        return $_e;
    }
}
