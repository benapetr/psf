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

require_once (dirname(__FILE__) . "/../object.php");
require_once (dirname(__FILE__) . "/../html_stack.php");

class PsfApi extends PsfObject
{
    public $Name = NULL;
    public $ParametersRequired = [];
    public $ParametersOptional = [];
    public $ShortDescription = NULL;
    public $LongDescription = NULL;
    public $Example = NULL;
    public $Callback = NULL;

    public function __construct($_name, $_callback = NULL, $short_description = NULL, $long_description = NULL)
    {
        $this->Name = $_name;
        $this->LongDescription = $long_description;
        $this->ShortDescription = $short_description;
        $this->Callback = $_callback;
    }

    public function Process()
    {
        if ($this->Callback !== NULL)
        {
            return call_user_func($this->Callback, $this);
        }
        return false;
    }
}

class PsfApiBase extends PsfObject
{
    public $ApiList_GET = [];
    public $ApiList_POST = [];
    public $ApiList_DELETE = [];
    public $ApiList_PUT = [];
    public $ApiList_Action = [];
    public $ShowHelpOnNoAction = true;
    public $ApiBaseName = "API";
    public $ApiBaseIntro = "Welcome to web API. These API's can be used to perform various actions on the website.";

    public function RegisterAPI_Action($api, $name = NULL)
    {
        if ($name === NULL)
            $name = $api->Name;
        $name = strtolower($name);
        $this->ApiList_Action[$name] = $api;
    }

    public function RegisterAPI_GET($api)
    {
        array_push ($this->ApiList_GET, $api);
    }

    public function RegisterAPI_PUT($api)
    {
        array_push ($this->ApiList_PUT, $api);
    }

    public function RegisterAPI_POST($api)
    {
        array_push ($this->ApiList_POST, $api);
    }

    public function RegisterAPI_DELETE($api)
    {
        array_push ($this->ApiList_DELETE, $api);
    }

    public function ProcessAction()
    {
        if (!isset($_GET['action']))
            return false;

        $action = strtolower($_GET['action']);

        if (!array_key_exists($action, $this->ApiList_Action))
            return false;

        return $this->ApiList_Action[$action]->Process();
    }

    public function ProcessGET()
    {
        return false;
    }

    public function ProcessPOST()
    {
        return false;
    }

    public function ProcessDELETE()
    {
        return false;
    }

    public function ProcessPUT()
    {
        return false;
    }

    public function Process()
    {
        $result = false;
        if ($this->ProcessAction() ||
            $this->ProcessGET() ||
            $this->ProcessPOST() ||
            $this->ProcessDELETE() ||
            $this->ProcessPUT())
            $result = true;

        if ($this->ShowHelpOnNoAction && !$result)
            $this->PrintHelpAsHtml();
    }

    public function PrintHelpAsHtml()
    {
        global $psf_containers_auto_insert_child;
        $def_psf_containers_auto_insert_child = $psf_containers_auto_insert_child;
        $psf_containers_auto_insert_child = true;
        $help = new HtmlPage($this->ApiBaseName . ": help");
        bootstrap_init($help);
        $c = new BS_FluidContainer($help);
        $c->AppendHeader($this->ApiBaseName . " documentation");
        $c->AppendParagraph($this->ApiBaseIntro);

        if (!empty($this->ApiList_Action))
        {
            $c->AppendHeader("Action API", 2);
            $c->AppendParagraph("These API can be called using standard GET web request with parameter \"action\" (for example: \"?action=test\") where action is one of these:");
            foreach ($this->ApiList_Action as $key => $value)
            {
                $c->AppendHeader($key, 3);
                if ($value->ShortDescription !== NULL)
                {
                    $c->AppendParagraph($value->ShortDescription);
                }
            }
        }

        $psf_containers_auto_insert_child = $def_psf_containers_auto_insert_child;
        $help->PrintHtml();
    }
}
