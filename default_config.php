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

$psf_language = "en";
//! If set to true, all containers will automatically insert child objects
$psf_containers_auto_insert_child = false;
$psf_bootstrap_enabled = True;
$psf_encoding = "UTF-8";
$psf_indent = 4;
// Set to false to improve performance on huge pages
$psf_indent_system_enabled = True;
$psf_home = "psf/";
$psf_log = "/tmp/psf.log";
$psf_localization = "lang";
$psf_localization_default_language = "en";
