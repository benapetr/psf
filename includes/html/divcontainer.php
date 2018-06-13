<?php

//Part of simple php framework (spf)

//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.

//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

//Copyright Petr Bena 2015 - 2018

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/container.php");

//! Represents a simple div container that can contain html elements
class DivContainer extends HtmlContainer
{
    function __construct($_parent = NULL)
    {
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
       $_b = "<div";
       if ($this->Style !== NULL)
           $_b .= " style=\"" . $this->Style->ToCss() . "\"";
       if ($this->ClassName !== NULL)
           $_b .= " class=\"" . $this->ClassName . "\"";
       $_b .= ">\n";
       $_b .= parent::ToHtml();
       $_b .= "</div>";
       return $_b;
    }
}

