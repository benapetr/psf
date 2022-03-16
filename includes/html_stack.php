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

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2019

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/htmlpage.php");

// Form related
require_once (dirname(__FILE__) . "/html/checkbox.php");
require_once (dirname(__FILE__) . "/html/hidden.php");
require_once (dirname(__FILE__) . "/html/button.php");
require_once (dirname(__FILE__) . "/html/form.php");
require_once (dirname(__FILE__) . "/html/combobox.php");
require_once (dirname(__FILE__) . "/html/login_form.php");
require_once (dirname(__FILE__) . "/html/textbox.php");
require_once (dirname(__FILE__) . "/html/label.php");

// Html basics
require_once (dirname(__FILE__) . "/html/table.php");
require_once (dirname(__FILE__) . "/html/image.php");
require_once (dirname(__FILE__) . "/html/list.php");
require_once (dirname(__FILE__) . "/html/divcontainer.php");

// Inline misc
require_once (dirname(__FILE__) . "/html/script.php");

// Widgets
require_once (dirname(__FILE__) . "/html/widgets/paging.php");

// Advanced
require_once (dirname(__FILE__) . "/html/github.php");
// This is not working
//require_once (dirname(__FILE__) . "/wmfoauth.php");
require_once (dirname(__FILE__) . "/googlefonts.php");

if ($psf_bootstrap_enabled)
{
    // Bootstrap
    require_once (dirname(__FILE__) . "/bootstrap.php");
}


