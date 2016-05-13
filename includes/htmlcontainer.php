<?php

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

if (!defined("PSF_ENTRY_POINT"))
        die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/../default_config.php");
require_once (dirname(__FILE__) . "/../functions.php");
require_once (dirname(__FILE__) . "/html/primitive_object.php");

//! Represent a single Html page
class HtmlContainer
{
    protected $Items = array();
    protected $cIndent = 4;

    function __construct()
    {
    }

    public function AppendHtmlLine($html, $indent = -1)
    {
        $value = "";
        while ($indent-- > 0)
        {
            $value .= " ";
        }
        $this->AppendObject(new HtmlPrimitiveObject($value . $html));
    }

    public function AppendHtml($html, $indent = -1)
    {
        $lines = explode("\n", $html);
        foreach ($lines as $l)
            $this->AppendHtmlLine($l, $indent);
    }

    public function AppendParagraph($text)
    {
        $this->AppendHtmlLine($this->html_p(htmlspecialchars($text)));
    }

    public function AppendObject($object, $indent = -1)
    {
        array_push($this->Items, $object);
    }

    public function _gen_html_tag($name, $value, $param = "")
    {
        if (strlen($param) == 0)
            return "<$name>" . $value . "</$name>";
        else
            return "<$name $param>" . $value . "</$name>";
    }

    public function html_b($text)                   { return $this->_gen_html_tag("b",   $text); }
    public function html_div($text)                 { return $this->_gen_html_tag("div", $text); }
    public function html_font($text, $param = "")   { return $this->_gen_html_tag("font", $text, $param); }
    public function html_p($text)                   { return $this->_gen_html_tag("p",   $text); }

    public function InsertFileToBody($f)
    {
        $tx =  file_get_contents($f);
        if ($tx === FALSE)
            throw new Exception("File couldn't be read: " . $f);
        $this->AppendObject(new HtmlPrimitiveObject($tx));
    }

    public function ToHtml()
    {
       $indent = 4;
       foreach ($this->Items as $html)
       {
           // Convert the object to html
           $_b .= psf_indent_text($html->ToHtml(), $indent);
       }
       return $_b;
    }
}
