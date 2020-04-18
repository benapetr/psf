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

// Copyright (c) Petr Bena <petr@bena.rocks> 2020

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

// Functions that help working with CLI

define('PSF_CLI_COLOR_BLACK', '0;30');
define('PSF_CLI_COLOR_GRAY', '1;30');
define('PSF_CLI_COLOR_BLUE', '0;34');
define('PSF_CLI_COLOR_LIGHT_BLUE', '1;34');
define('PSF_CLI_COLOR_GREEN', '0;32');
define('PSF_CLI_COLOR_LIGHT_GREEN', '1;32');
define('PSF_CLI_COLOR_CYAN', '0;36');
define('PSF_CLI_COLOR_LIGHT_CYAN', '1;36');
define('PSF_CLI_COLOR_RED', '0;31');
define('PSF_CLI_COLOR_LIGHT_RED', '1;31');
define('PSF_CLI_COLOR_PURPLE', '0;35');
define('PSF_CLI_COLOR_LIGHT_PURPLE', '1;35');
define('PSF_CLI_COLOR_BROWN', '0;33');
define('PSF_CLI_COLOR_YELLOW', '1;33');
define('PSF_CLI_COLOR_LIGHT_GRAY', '0;37');
define('PSF_CLI_COLOR_WHITE', '1;37');

function psf_start_color($color)
{
    echo ("\033[" . $color . 'm');
}

function psf_end_color()
{
    echo ("\033[0m");
}

function psf_print_colored_text($color, $text)
{
    psf_start_color($color);
    echo ($text);
    psf_end_color();
}
