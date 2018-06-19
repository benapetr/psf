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

function googlefonts_init($page)
{
    $open_sans = 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,700';
    if (!in_array($open_sans, $page->ExternalCss))
        $page->ExternalCss[] = $open_sans;
    $page->Style->items["*"]["font-family"] = "Open Sans";
}
