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

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2020

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/../cli.php");

function psf_ut($name, $results)
{
    echo("Running test [" . $name . "]: ");
    if ($results === true)
        psf_print_colored_text(PSF_CLI_COLOR_LIGHT_GREEN, 'OK');
    else
        psf_print_colored_text(PSF_CLI_COLOR_RED, 'FAILED');
    echo("\n");
    return $results;
}

//! Used to process various unit tests, this class track their results and overall status of unit test suite
class UnitTest
{
    private $failedCounter = 0;
    private $successCounter = 0;

    //! Log unit test with name and result (boolean)
    public function Evaluate($name, $results)
    {
        if (psf_ut($name, $results))
            $this->successCounter++;
        else
            $this->failedCounter++;
    }

    //! Print unit test results
    public function PrintResults()
    {
        echo("Results of unit test:\n");
        echo("=============================\n");
        echo("Successful tasks: $this->successCounter\n");
        echo("Failed tasks:     $this->failedCounter\n");
        echo("Overal results:   ");
        if ($this->failedCounter === 0)
            psf_print_colored_text(PSF_CLI_COLOR_LIGHT_GREEN, 'PASSED');
        else
            psf_print_colored_text(PSF_CLI_COLOR_RED, 'FAILED');
        echo ("\n");
    }

    public function IsPassed()
    {
        return $this->failedCounter === 0;
    }

    public function IsFailed()
    {
        return $this->failedCounter !== 0;
    }

    public function ExitTest()
    {
        if ($this->IsPassed())
            exit(0);
        else
            exit(1);
    }

    public function ExitWithErrorCount()
    {
        exit($this->failedCounter);
    }
}
