<?php

// Entry point to psf

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2018

define ("PSF_ENTRY_POINT", "psf.php");

// System stuff
require_once (dirname(__FILE__) . "/definitions.php");
require_once (dirname(__FILE__) . "/default_config.php");
require_once (dirname(__FILE__) . "/includes/systemlog.php");
require_once (dirname(__FILE__) . "/includes/htmlpage.php");
require_once (dirname(__FILE__) . "/includes/systemlog.php");
require_once (dirname(__FILE__) . "/includes/localization.php");

// Form related
require_once (dirname(__FILE__) . "/includes/html/checkbox.php");
require_once (dirname(__FILE__) . "/includes/html/hidden.php");
require_once (dirname(__FILE__) . "/includes/html/button.php");
require_once (dirname(__FILE__) . "/includes/html/form.php");
require_once (dirname(__FILE__) . "/includes/html/combobox.php");
require_once (dirname(__FILE__) . "/includes/html/login_form.php");
require_once (dirname(__FILE__) . "/includes/html/textbox.php");

// Html basics
require_once (dirname(__FILE__) . "/includes/html/table.php");
require_once (dirname(__FILE__) . "/includes/html/image.php");
require_once (dirname(__FILE__) . "/includes/html/list.php");
require_once (dirname(__FILE__) . "/includes/html/divcontainer.php");

// JS stuff
//require_once (dirname(__FILE__) . "/includes/js/jshandler.php");
//require_once (dirname(__FILE__) . "/includes/js/tooltip.php");

if ($psf_bootstrap_enabled)
{
    // Bootstrap
    require_once (dirname(__FILE__) . "/includes/bootstrap.php");
}

// Advanced
require_once (dirname(__FILE__) . "/includes/html/github.php");
require_once (dirname(__FILE__) . "/includes/wmfoauth.php");
require_once (dirname(__FILE__) . "/includes/googlefonts.php");

