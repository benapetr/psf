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

class BS_GridItem extends HtmlElement
{
    private $item = NULL;

    public function __construct($object)
    {
        $this->item = $object;
    }

    public function ToHtml()
    {
        if ($this->item === NULL)
            return "";

        $html = "<div class=\"col\">";
        $html .= $this->item->ToHtml();
        $html .= '</div>';
        return $html;
    }
}

class BS_GridRow extends DivContainer
{
    public $Size = 0;

    public function __construct($_name = NULL, $_value = NULL, $bs_class = NULL, $_parent = NULL)
    {
        //$this->ClassName = "row row-cols-{$this->ColsMax} row-cols-sm-{$this->ColsSm} row-cols-md-{$this->ColsMd} row-cols-lg-{$this->ColsLg}";
        $this->ClassName = "row";
        parent::__construct($_name, $_value, $_parent);
    }

    public function AppendObject($object)
    {
        $this->Size++;
        parent::AppendObject(new BS_GridItem($object));
    }
}

class BS_Grid extends DivContainer
{
    //! Maximum columns per row
    public $ColsMax = 0;
    /*public $ColsSm = 6;
    public $ColsMd = 8;
    public $ColsLg = 10;*/
    private $currentRow = NULL;

    public function __construct($_name = NULL, $_value = NULL, $bs_class = NULL, $_parent = NULL)
    {
        $this->ClassName = "container-fluid";
        parent::__construct($_name, $_value, $_parent);
    }

    public function AppendObject($object)
    {
        if ($this->currentRow === NULL || ($this->ColsMax > 0 && $this->currentRow->Size >= $this->ColsMax))
        {
            $this->currentRow = new BS_GridRow();
            parent::AppendObject($this->currentRow);
        }
        $this->currentRow->AppendObject($object);
    }
}
