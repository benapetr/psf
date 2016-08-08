<?php

require_once (dirname(__FILE__) . "/../../functions.php");
require_once (dirname(__FILE__) . "/element.php");

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

class Image extends HtmlElement
{
    public $Format = NULL;
    public $Height = NULL;
    public $Width = NULL;
    public $URL = NULL;
    public $AlternateText = "";

    public function __construct($image, $alt = "", $w = NULL, $h = NULL, $_parent = NULL)
    {
        parent::__construct($_parent = NULL);
        $this->URL = $image;
        $this->AlternateText = $alt;
        $this->Height = $h;
        $this->Width = $w;
    }

    public function GetFormat()
    {
        $f = " alt=\"" . $this->AlternateText . "\"";
        if ($this->Width !== NULL)
            $f .= " width=\"" . $this->Width . "\"";
        if ($this->Height !== NULL)
            $f .= " height=\"" . $this->Height . "\"";
        if ($this->Style !== NULL)
            $f .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->Format !== NULL)
        {
            $f .= " $this->Format";
        }
        return $f;
    }

    public function ToHtml()
    {
        $html = "<img src=\"" . $this->URL . "\"" . $this->GetFormat() .">";
        return $html;
    }
}
