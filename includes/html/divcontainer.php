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

require_once (dirname(__FILE__) . "/container.php");

//! Represents a simple div container that can contain html elements
class DivContainer extends HtmlContainer
{
    //! If true container will produce some HTML code even if it's completely empty
    public $AllowEmpty = false;
    //! ID of HTML element, this will be added as <element id='text'> if not null, not all elements support this
    public $ID = NULL;

    function __construct($_parent = NULL)
    {
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        // If container is empty, don't produce unnecessary HTML
        if ($this->AllowEmpty === false && empty($this->Items))
            return '';
             
        $_b = "<div";
        if ($this->Style !== NULL)
            $_b .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $_b .= " class=\"" . $this->ClassName . "\"";
        if ($this->ID !== NULL)
            $_b .= " id=\"" . $this->ID . "\"";
        $_b .= ">\n";
        $_b .= parent::ToHtml();
        $_b .= "</div>";
        return $_b;
    }
}

