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

//Copyright Petr Bena 2015

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/../css/inline.php");
require_once (dirname(__FILE__) . "/../htmlcontainer.php");

abstract class FormMethod
{
    const Get = "get";
    const Post = "post";
}

class Form extends HtmlContainer
{
    public $Action = NULL;
    public $Method = FormMethod::Get;

    public function __construct($_action = NULL, $_parent = NULL)
    {
        $this->Action = $_action;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        if ($this->Action === NULL)
        {
            $bx = "<form>\n";
        } else
        {
            $bx = '<form action="' . $this->Action . '" method="' . $this->Method . '">' . "\n";
        }
        $bx .= parent::ToHtml();
        $bx .= "</form>";
        return $bx;
    }
}
