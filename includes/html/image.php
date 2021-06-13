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

require_once (dirname(__FILE__) . "/../../functions.php");
require_once (dirname(__FILE__) . "/element.php");

class Image extends HtmlElement
{
    public $Format = NULL;
    public $Height = NULL;
    public $Width = NULL;
    public $URL = NULL;
    public $AlternateText = "";
    public $Title = NULL;
    //! Change to name of JS function to call when checkbox is clicked
    public $OnClickCallback = NULL;

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
        $html = "<img src=\"" . $this->URL . "\"" . $this->GetFormat();
        if ($this->Title !== NULL)
            $html .= " title=\"" . $this->Title . "\"";
        if ($this->ClassName !== NULL)
            $html .= " class=\"" . $this->ClassName . "\"";
        if ($this->OnClickCallback !== NULL)
            $html .= ' onclick="' . $this->OnClickCallback . '"';
        $html .= ">";
        if ($this->Style !== NULL)
        {
            $style = $this->Style->ToCss();
            if (strlen($style) > 0)
                $html .= " style=\"" . $style . "\"";
        }
        return $html;
    }
}
