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

require_once (dirname(__FILE__) . "/../css/inline.php");

class BulletList extends HtmlElement
{
    //! You can override class name of each <li> element using this
    public $ClassName_LI = NULL;
    public $Items = array();

    public function __construct($list = NULL, $_parent = NULL)
    {
        parent::__construct($_parent);
        if ($list !== NULL)
            $this->Items = $list;
    }

    public function ToHtml()
    {
        $bx = "<ul>\n";
        if ($this->ClassName !== NULL)
            $bx = '<ul class="' . $this->ClassName . "\">\n";
        foreach ($this->Items as $item)
        {
            $class_li = '';
            if ($this->ClassName_LI !== NULL)
                $class_li = ' class="' . $this->ClassName_LI . '"';
            $bx .= "    <li${class_li}>" . $item . "</li>\n";
        }
        $bx .= "</ul>";
        return $bx;
    }
}
