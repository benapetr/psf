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

require_once (dirname(__FILE__) . "/../html/list.php");

class BS_Tabs extends BulletList
{
    public $SelectedTab = 0;

    public function __construct($list = NULL, $_parent = NULL)
    {
        parent::__construct($_parent);
        if ($list !== NULL)
            $this->Items = $list;
        $this->ClassName = 'nav nav-tabs';
    }

    public function ToHtml()
    {
        $bx = "<ul class='" . $this->ClassName . "'>\n";
        $i = 0;
        foreach ($this->Items as $item)
        {
            if ($i == $this->SelectedTab)
                $bx .= "    <li class='active'>" . $item . "</li>\n";
            else
                $bx .= "    <li>" . $item . "</li>\n";
            $i++;
        }
        $bx .= "</ul>";
        return $bx;
    }
}
