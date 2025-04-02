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

// Copyright (c) Petr Bena <petr@bena.rocks> 2025

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

    require_once (dirname(__FILE__) . "/../html/list.php");

class BS_NavbarItem extends HtmlElement
{
    public $Name;
    public $Link;
    public $ClassName = 'nav-link';

    public function __construct($name, $link)
    {
        $this->Name = $name;
        $this->Link = $link;
    }

    public function ToHtml()
    {
        return "<a class='" . $this->ClassName . "' href='" . $this->Link . "'>" . $this->Name . "</a>";
    }
}

class BS_NavbarItems extends BulletList
{
    public $SelectedItemIndex = 0;

    public function __construct($list = NULL, $_parent = NULL)
    {
        parent::__construct($_parent);
        if ($list !== NULL)
            $this->Items = $list;
        $this->ClassName = 'navbar-nav ms-auto';
        $this->ClassName_LI = 'nav-item';
    }

    public function ToHtml()
    {
        $bx = "<ul class='" . $this->ClassName . "'>\n";
        $i = 0;
        foreach ($this->Items as $item)
        {
            if ($i == $this->SelectedItemIndex)
            {
                $class_li = 'class="active"';
                if ($this->ClassName_LI !== NULL)
                    $class_li = 'class="active ' . $this->ClassName_LI . '"';
                $bx .= "    <li ${class_li}>" . $item->ToHtml() . "</li>\n";
            } else
            {
                $class_li = '';
                if ($this->ClassName_LI !== NULL)
                    $class_li = ' class="' . $this->ClassName_LI . '"';
                $bx .= "    <li${class_li}>" . $item->ToHtml() . "</li>\n";
            }
            $i++;
        }
        $bx .= "</ul>";
        return $bx;
    }
}

class BS_Navbar extends HtmlElement
{
    public $DarkMode = false;
    public $LogoText = '';
    public $Tabs = NULL;

    public function __construct($_parent = NULL)
    {
        parent::__construct($_parent);
        $this->Tabs = new BS_NavbarItems();
    }

    public function AddItem($name, $link)
    {
        $this->Tabs->Items[] = new BS_NavbarItem($name, $link);
    }

    public function ToHtml()
    {
        $_e = '';
        if ($this->DarkMode)
            $_e = '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">';
        else
            $_e = '<nav class="navbar navbar-expand-lg navbar-light bg-light">';

        $_e .= '<div class="container">';
        $_e .= '<a class="navbar-brand" href="#">' . $this->LogoText . '</a>';
        $_e .= '<div class="collapse navbar-collapse" id="navbarNav">';
        $_e .= "\n" . psf_indent_text($this->Tabs->ToHtml(), 2);
        $_e .= '</div>';
        $_e .= '</div>';
        $_e .= '</nav>';

        return $_e;
    }
}