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

//! Data types supported by API parameters
abstract class PsfApiParameterType
{
    const NULL = 0;
    const Variant = 1;
    const Number = 2;
    const String = 3;
    const Boolean = 4;

    public static function ToString($type)
    {
        if ($type === PsfApiParameterType::NULL)
            return "NULL";
        if ($type === PsfApiParameterType::Variant)
            return "Variant";
        if ($type === PsfApiParameterType::Number)
            return "Number";
        if ($type === PsfApiParameterType::String)
            return "String";
        if ($type === PsfApiParameterType::Boolean)
            return "Boolean";
        return "invalid type";
    }
}

class PsfApiParameter extends PsfObject
{
    public $Name;
    //! Type of 
    public $Type;
    public $Description;

    public function __construct($_name, $_type = PsfApiParameterType::Variant, $_description = NULL)
    {
        $this->Name = $_name;
        $this->Type = $_type;
        $this->Description = $_description;
    }
};

//! This class represents a single API
//! PSF supports quite flexible API framework which can be used to create simple action based or REST based API for your service
//! In order to create API entry point just create an object PsfApiBase and execute Process function on it.
class PsfApi extends PsfObject
{
    public $Name = NULL;
    public $ParametersRequired = [];
    public $ParametersOptional = [];
    public $ShortDescription = NULL;
    public $LongDescription = NULL;
    public $Example = NULL;
    public $Callback = NULL;
    public $RequiresAuthentication = false;

    public function __construct($_name, $_callback = NULL, $short_description = NULL, $long_description = NULL, $params_req = NULL, $params_opt = NULL)
    {
        $this->Name = $_name;
        $this->LongDescription = $long_description;
        $this->ShortDescription = $short_description;
        $this->Callback = $_callback;
        if ($params_req !== NULL)
            $this->ParametersRequired = $params_req;
        if ($params_opt !== NULL)
            $this->ParametersOptional = $params_opt;
    }

    public function Process()
    {
        if ($this->Callback !== NULL)
        {
            return call_user_func($this->Callback, $this);
        }
        return false;
    }

    public function GetParameterCount()
    {
        return count($this->ParametersOptional) + count($this->ParametersRequired);
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
    public $ApiBaseIntro = "Welcome to web API. These APIs can be used to perform various actions on the website.";
    public $TreatDocsAsHTML = false;
    //! This must be an instance of PsfAuth object, see derivatives of PsfAuthBase for more details
    public $AuthenticationBackend = NULL;

    public function RegisterAPI_Action($api, $name = NULL)
    {
        if ($name === NULL)
            $name = $api->Name;
        $name = strtolower($name);
        $api->Parent = $this;
        $this->ApiList_Action[$name] = $api;
    }

    public function RegisterAPI_GET($api)
    {
        $api->Parent = $this;
        array_push ($this->ApiList_GET, $api);
    }

    public function RegisterAPI_PUT($api)
    {
        $api->Parent = $this;
        array_push ($this->ApiList_PUT, $api);
    }

    public function RegisterAPI_POST($api)
    {
        $api->Parent = $this;
        array_push ($this->ApiList_POST, $api);
    }

    public function RegisterAPI_DELETE($api)
    {
        $api->Parent = $this;
        array_push ($this->ApiList_DELETE, $api);
    }

    public function ProcessAction()
    {
        if (isset($_GET['action']))
        {
            $action = strtolower($_GET['action']);
        } else if (isset($_POST['action']))
        {
            $action = strtolower($_POST['action']);
        } else
        {
            return false;
        }

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

    private function appendDocs($c, $t)
    {
        if (!$this->TreatDocsAsHTML)
            $c->AppendParagraph($t);
        else
            $c->AppendHtml($t);
    }

    public function ThrowError($error, $message = NULL, $code = -1)
    {
        print("Error: " . $error . "\n");
        if ($message !== NULL)
            print("Details: " . $message . "\n");

        // Terminate here
        die($code);
    }

    public function PrintHelpAsHtml()
    {
        global $psf_containers_auto_insert_child;
        $def_psf_containers_auto_insert_child = $psf_containers_auto_insert_child;
        $psf_containers_auto_insert_child = true;
        $help = new HtmlPage($this->ApiBaseName . ": help");
        bootstrap_init($help);
        $help->Style->items["blockquote"]["font-size"] = "14px";
        $c = new BS_FluidContainer($help);
        $c->AppendHeader($this->ApiBaseName . " documentation");
        $this->appendDocs($c, $this->ApiBaseIntro);

        if (!empty($this->ApiList_Action))
        {
            $c->AppendHeader("Action APIs", 2);
            $c->AppendHtmlLine('<p>These APIs can be called using standard GET or POST web request with parameter <code>action</code> (for example: <code>?action=test</code>) where action is one of these:</p>');
            foreach ($this->ApiList_Action as $key => $value)
            {
                $c->AppendHeader($key, 3);
                if ($value->ShortDescription !== NULL)
                    $this->appendDocs($c, $value->ShortDescription);
                $c->AppendHtmlLine('<blockquote>');
                if ($value->RequiresAuthentication)
                    $c->AppendHtmlLine('<p class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> This action requires authentication</p>');
                if ($value->GetParameterCount() === 0)
                {
                    $c->AppendParagraph("This action has no parameters", "text-info");
                } else
                {
                    if (count($value->ParametersRequired) > 0)
                    {
                        $c->AppendHeader("Parameters (required)", 4);
                        foreach ($value->ParametersRequired as $reqp)
                        {
                            $c->AppendHtmlLine('<p><b>' . $reqp->Name . '</b> (<code>' . PsfApiParameterType::ToString($reqp->Type) . '</code>): ' . $reqp->Description . '</p>');
                        }
                    }
                    if (count($value->ParametersOptional) > 0)
                    {
                        $c->AppendHeader("Parameters (optional)", 4);
                        foreach ($value->ParametersOptional as $optp)
                        {
                            $c->AppendHtmlLine('<p><b>' . $optp->Name . '</b> (<code>' . PsfApiParameterType::ToString($optp->Type) . '</code>): ' . $optp->Description . '</p>');
                        }
                    }
                }
                if ($value->LongDescription !== NULL)
                    $this->appendDocs($c, $value->LongDescription);
                if ($value->Example !== NULL)
                    $c->AppendHtmlLine('<p><b>Example:</b> <code>' . htmlspecialchars($value->Example) . '</code></p>');
                $c->AppendHtmlLine('</blockquote>');
            }
        }

        $psf_containers_auto_insert_child = $def_psf_containers_auto_insert_child;
        $help->PrintHtml();
    }
}
