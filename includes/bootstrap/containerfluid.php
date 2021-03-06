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

class BS_FluidContainer extends DivContainer
{
    public function __construct($_parent = NULL)
    {
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        if ($this->ClassName === NULL)
        {
            $this->ClassName = "container-fluid";
        } else
        {
            $this->ClassName = "container-fluid " . $this->ClassName;
        }
        return parent::ToHtml();
    }
}
