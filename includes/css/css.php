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

class CSS extends PsfObject
{
    public $items = array();
    public $BackgroundColor = NULL;
    public $FontSize = NULL;

    /*function __construct()
    {
        $this->items['*']['font-family'] = 'Helvetica, Arial';
    }*/

    protected function Load()
    {
        if ($this->BackgroundColor !== NULL)
            $this->items['body']['background-color'] = $this->BackgroundColor;
        if ($this->FontSize !== NULL)
            $this->items['body']['font-size'] = $this->FontSize;
    }

    //! This is just a skeleton function that can be overriden in order to auto-initialize style for certain elements
    //! some PSF classes will call this function when the element is used, so that you can generate style only when
    //! this element is present somewhere in body of page, this saves web browser some parsing and few bytes of CSS
    public function AutoInit($element)
    {

    }

    public function FetchCss($n)
    {
        return $this->ToCss($n);
    }

    public function ToCss($n = 0)
    {
        $buff = '';
        $indentation = '';
        $this->Load();
        while ($n-- > 0)
            $indentation .= ' ';
        foreach ($this->items as $name => $values)
        {
            $buff .= $indentation . $name . " {\n";
            foreach ($values as $vn => $xx)
            {
                $buff .= $indentation . '    ' . $vn . ': ' . $xx . ";\n";
            }
            $buff .= $indentation . "}\n";
        }
        return $buff;
    }
}
