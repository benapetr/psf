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

require_once (dirname(__FILE__) . "/../html/button.php");

class BS_ProgressBar extends DivContainer
{
    public $Min = 0;
    public $Max = 100;
    public $Value = 0;
    public $Text = NULL;

    public function __construct($_min = 0, $_max = 100, $_value = 0, $_text = "", $_parent = NULL)
    {
        $this->Min = $_min;
        $this->Max = $_max;
        $this->Value = $_value;
        $this->Text = $_text;
        parent::__construct($_parent);
    }

    public function ToHtml()
    {
        return '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="' . $this->Value . '" aria-valuemin="' . $this->Min . '" aria-valuemax="' . $this->Max . '" style="width:' . $this->Value . '%">' . $this->Text . '</div></div>';
    }
}

/*
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:70%">
    <span class="sr-only">70% Complete</span>
  </div>
</div> 
*/