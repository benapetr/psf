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

require_once (dirname(__FILE__) . "/../object.php");
require_once (dirname(__FILE__) . "/../css/inline.php");

class HtmlElement extends PsfObject
{
    public $Indentation = 0;
    public $Indent = True;
    public $Style = NULL;
    //! The class of html element, if HTML5, this will be added as <element class="text"> if not NULL
    public $ClassName = NULL;
    public $IsVisible = true;

    function __clone()
    {
        if ($this->Style !== NULL)
            $this->Style = clone $this->Style;
    }

    public function ToHtml()
    {
        return "";
    }

    public function DisableIndenting()
    {
        if ($this->Parent !== NULL && $this->Parent instanceof HtmlElement)
            $this->Parent->DisableIndenting();
        $this->Indent = false;
    }
}
