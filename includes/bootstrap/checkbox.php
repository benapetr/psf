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

require_once (dirname(__FILE__) . "/../html/checkbox.php");

class BS_CheckBox extends CheckBox
{
    public $Inline = false;
    private static $generated_id = 0;

    public function __construct($_name = NULL, $_value = NULL, $_checked = false, $bs_class = NULL, $_parent = NULL, $_text = NULL)
    {
        global $psf_bootstrap_target_version;

        $this->ClassName = ($psf_bootstrap_target_version == 5) ? "form-check-input" : "checkbox";
        if ($bs_class !== NULL)
            $this->ClassName .= " " . $bs_class;
        parent::__construct($_name, $_value, $_checked, $_parent, $_text);
    }

    private function getBootstrap5Html()
    {
        $id = "psf_checkbox_" . self::$generated_id++;
        $_e = "<input type=\"checkbox\" id=\"$id\"";
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
            $_e .= "<label class=\"form-check-label\" for=\"$id\">" . $this->Text . "</label>";

        $class = $this->Inline ? "form-check form-check-inline" : "form-check";
        return "<div class=\"$class\">$_e</div>";
    }

    public function ToHtml()
    {
        global $psf_bootstrap_target_version;

        if ($psf_bootstrap_target_version == 5)
            return $this->getBootstrap5Html();

        $extras = "";
        if (!$this->Enabled)
            $extras .= " disabled";
        if ($this->Inline)
            return "<div class=\"checkbox-inline\"" . $extras . ">" . parent::ToHtml() . "</div>";
        else
            return "<div class=\"checkbox\"" . $extras . ">" . parent::ToHtml() . "</div>";
    }
}
