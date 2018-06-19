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

require_once (dirname(__FILE__) . "/bootstrap/button.php");
require_once (dirname(__FILE__) . "/bootstrap/containerfluid.php");
require_once (dirname(__FILE__) . "/bootstrap/checkbox.php");
require_once (dirname(__FILE__) . "/bootstrap/form.php");
require_once (dirname(__FILE__) . "/bootstrap/textbox.php");
require_once (dirname(__FILE__) . "/bootstrap/well.php");

function bootstrap_init($page)
{
    $bs = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css';
    if (!in_array($bs, $page->ExternalCss))
        $page->ExternalCss[] = $bs;
    $bs_j = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js';
    if (!in_array($bs_j, $page->ExternalJs))
        $page->ExternalJs[] = $bs_j;
    $page->Header_Meta["viewport"] = "width=device-width, initial-scale=1";
}
