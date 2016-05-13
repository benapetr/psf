<?php

//Part of simple php framework (spf)
//
////This program is free software: you can redistribute it and/or modify
////it under the terms of the GNU General Public License as published by
////the Free Software Foundation, either version 3 of the License, or
////(at your option) any later version.
//
////This program is distributed in the hope that it will be useful,
////but WITHOUT ANY WARRANTY; without even the implied warranty of
////MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
////GNU General Public License for more details.
//
////Copyright Petr Bena 2015

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

class CSS
{
    public $items = array();
    public $BackgroundColor = NULL;

    /*function __construct()
    {
        $this->items['*']['font-family'] = 'Helvetica, Arial';
    }*/

    protected function Load()
    {
        if ($this->BackgroundColor !== NULL)
            $this->items['body']['background-color'] = $this->BackgroundColor;
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
