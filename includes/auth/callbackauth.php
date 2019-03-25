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

require_once (dirname(__FILE__) . "/authbase.php");

//! This class allow for very simple implementation of authentication mechanism via callback methods

//! Both standard functions are calling user defined callbacks, so the PHP implementation of authentication mechanism
//! is completely left up to user
class PsfCallbackAuth extends PsfAuthBase
{
    public $Tokens = array();
    public $callback_IsAuthenticated = NULL;
    public $callback_IsPrivileged = NULL;

    public function GetID()
    {
        return "CallbackAuth";
    }

    public function IsPrivileged($privilege)
    {
        return call_user_func($this->callback_IsPrivileged, $this, $privilege);
    }

    public function IsAuthenticated()
    {
        return call_user_func($this->callback_IsAuthenticated, $this);
    }
}
