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

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2019

define ("PSF_ENTRY_POINT", "psf.php");

// System stuff
require_once (dirname(__FILE__) . "/definitions.php");
require_once (dirname(__FILE__) . "/default_config.php");
require_once (dirname(__FILE__) . "/includes/systemlog.php");
require_once (dirname(__FILE__) . "/includes/localization.php");

// Authentication stack
require_once (dirname(__FILE__) . "/includes/auth_stack.php");

// HTML
require_once (dirname(__FILE__) . "/includes/html_stack.php");

// API
require_once (dirname(__FILE__) . "/includes/api/apibase_json.php");

// JS stuff
//require_once (dirname(__FILE__) . "/includes/js/jshandler.php");
//require_once (dirname(__FILE__) . "/includes/js/tooltip.php");

