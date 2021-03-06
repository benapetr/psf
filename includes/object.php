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

//! \brief Base class used for every single PSF object
class PsfObject
{
    protected $Parent = NULL;

    function __construct($_parent = NULL)
    {
        $this->Parent = $_parent;
        if ($_parent !== NULL)
            $_parent->AddChild($this);
    }

    function SetParent($_parent)
    {
        $this->Parent = $_parent;
    }

    function GetParent($_parent)
    {
        return $this->Parent;
    }

    function AddChild($_child) { }
}

