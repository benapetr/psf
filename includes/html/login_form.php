<?php

require_once (dirname(__FILE__) . "/element.php");

//Part of simple php framework (spf)

//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.

//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

//Copyright Petr Bena 2015


class LoginForm extends HtmlElement
{
    public $Format = NULL;
    public $Callback = "index.php?login";
    public $User = "";
    public $Pass = "";
    public $Html;

    public function __construct()
    {

    }

    public function ToHtml()
    {
        $html = "<div class=\"loginform\"><form action=\"" . $this->Callback . "\" method=\"post\">\n";
        $html .= "  <p><input type=\"text\" name=\"loginUsername\" value=\"" .$this->User. "\" placeholder=\"Username\"></p>\n";
        $html .= "  <p><input type=\"password\" name=\"loginPassword\" value=\"\" placeholder=\"Password\"></p>\n";
        $html .= "  <p class=\"loginform_submit\"><input type=\"submit\" name=\"commit\" value=\"Login\"></p>\n";
        $html .= "</form></div>";
        return $html;
    }
}
