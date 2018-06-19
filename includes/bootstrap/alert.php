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

require_once (dirname(__FILE__) . "/../html/divcontainer.php");

class BS_Alert extends DivContainer
{
    public $Text;
    public $Type;
    public $EscapeHTML = true;
    public $IsDismissable = true;

    public function __construct($_text, $_type = "success", $_parent = NULL)
    {
        $this->Text = $_text;
        $this->Type = $_type;
        parent::__construct($_parent);
    }

    private function getClass()
    {
        $class = "alert alert-" . $this->Type;
        if ($this->IsDismissable)
            $class .= " alert-dismissable";
        return $class;
    }

    public function ToHtml()
    {
		if ($this->EscapeHTML)
		    $this->AppendHtmlLine(htmlspecialchars($this->Text));
		else
		    $this->AppendHtmlLine($this->Text);
        if ($this->ClassName === NULL)
        {
            $this->ClassName = $this->getClass();
        } else
        {
            $this->ClassName = $this->getClass() . " " . $this->ClassName;
        }
        return parent::ToHtml();
    }
}
