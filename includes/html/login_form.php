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

require_once (dirname(__FILE__) . "/button.php");
require_once (dirname(__FILE__) . "/form.php");
require_once (dirname(__FILE__) . "/checkbox.php");
require_once (dirname(__FILE__) . "/combobox.php");
require_once (dirname(__FILE__) . "/textbox.php");
require_once (dirname(__FILE__) . "/label.php");

class LoginForm extends Form
{
    public $Format = NULL;
    public $DefaultUser = "";
    private $UserInput = NULL;
    private $PassInput = NULL;
    private $bSubmit = NULL;

    public function __construct($_parent = NULL)
    {
        parent::__construct($_parent = NULL);
        // Remember if we want to auto insert childs
        $ic = $this->AutoInsertChilds;
        $this->AutoInsertChilds = true;
        $this->Action = "?login";
        $this->Method = FormMethod::Post;
        $this->UserInput = new TextBox("loginUsername", "", $this);
        $this->UserInput->Placeholder = "Username";
        $this->UserInput->Required = true;
        $this->AppendLineBreak();
        $this->PassInput = new TextBox("loginPassword", "", $this);
        $this->PassInput->Placeholder = "Password";
        $this->PassInput->Password = true;
        $this->AppendLineBreak();
        $this->bSubmit = new Button("login", "Login", $this);
        // Restore back original
        $this->AutoInsertChilds = $ic;
    }
}
