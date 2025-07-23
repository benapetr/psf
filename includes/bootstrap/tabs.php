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
        global $psf_bootstrap_target_version;
        parent::__construct($_parent);
        if ($list !== NULL)
            $this->Items = $list;
        $this->ClassName = 'nav nav-tabs';
        if ($psf_bootstrap_target_version == 5)
            $this->ClassName .= ' mb-3';
    }

    public function ToHtml()
    {
        global $psf_bootstrap_target_version;
        $bx = "<ul class='" . $this->ClassName . "' role='tablist'>\n";
        $i = 0;

        if ($psf_bootstrap_target_version == 5)
        {
            foreach ($this->Items as $item)
            {
                $li = '    <li class="nav-item" role="presentation">';
                $li .= $item;
                $li .= "</li>\n";
                $bx .= $li;
                $i++;
            }
        } else
        {
            foreach ($this->Items as $item)
            {
                if ($i == $this->SelectedTab)
                {
                    $class_li = 'class="active"';
                    if ($this->ClassName_LI !== NULL)
                        $class_li = 'class="active ' . $this->ClassName_LI . '"';
                    $bx .= "    <li ${class_li}>" . $item . "</li>\n";
                } else
                {
                    $class_li = '';
                    if ($this->ClassName_LI !== NULL)
                        $class_li = ' class="' . $this->ClassName_LI . '"';
                    $bx .= "    <li${class_li}>" . $item . "</li>\n";
                }
                $i++;
            }
        }
        $bx .= "</ul>";
        return $bx;
    }
}
