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

require_once (dirname(__FILE__) . "/../html/checkbox.php");

class BS_CheckBox extends CheckBox
{
    public $Inline = false;
    public function __construct($_name = NULL, $_value = NULL, $_checked = false, $bs_class = NULL, $_parent = NULL)
    {
        $this->ClassName = "checkbox";
        if ($bs_class !== NULL)
            $this->ClassName .= " " . $bs_class;
        parent::__construct($_name, $_value, $_checked, $_parent);
    }

    public function ToHtml()
    {
        $extras = "";
        if (!$this->Enabled)
            $extras .= " disabled";
        if ($this->Inline)
            return "<div class=\"checkbox-inline\"" . $extras . ">" . parent::ToHtml() . "</div>";
        else
            return "<div class=\"checkbox\"" . $extras . ">" . parent::ToHtml() . "</div>";
    }
}
