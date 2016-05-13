<?php

//Entry point to psf

//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.

//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

//Copyright Petr Bena 2015

define ("PSF_ENTRY_POINT", "psf.php");

require_once (dirname(__FILE__) . "/definitions.php");
require_once (dirname(__FILE__) . "/default_config.php");
require_once (dirname(__FILE__) . "/includes/htmlpage.php");
require_once (dirname(__FILE__) . "/includes/html/table.php");
require_once (dirname(__FILE__) . "/includes/html/list.php");
require_once (dirname(__FILE__) . "/includes/html/login_form.php");
require_once (dirname(__FILE__) . "/includes/html/github.php");
require_once (dirname(__FILE__) . "/includes/wmfoauth.php");
require_once (dirname(__FILE__) . "/includes/googlefonts.php");
require_once (dirname(__FILE__) . "/includes/systemlog.php");

