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

require_once (dirname(__FILE__) . "/apibase.php");

class PsfApiBase_JSON extends PsfApiBase
{
    public function PrintObj($object)
    {
        header('Content-Type: application/json');
        echo (json_encode($object, JSON_PRETTY_PRINT));
        echo ("\n");
    }

    public function ThrowError($error, $message = NULL, $code = -1)
    {
        $error = [
            'error' => $error,
            'message' => $message,
            'code' => $code
        ];
        http_response_code(400);
        $this->PrintObj($error);
        die($code);
    }
}
