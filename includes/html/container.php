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

require_once (dirname(__FILE__) . "/../object.php");
require_once (dirname(__FILE__) . "/../../default_config.php");
require_once (dirname(__FILE__) . "/../../functions.php");
require_once (dirname(__FILE__) . "/primitive_object.php");

//! Represent a single Html container, usually used by htmlpage or htmltable or any other element that is able to hold child html elements
class HtmlContainer extends HtmlElement
{
    protected $Items = array();
    protected $cIndent = 4;

    function __construct($_parent = NULL)
    {
        parent::__construct($_parent);
    }

    protected function ReplaceControl($text)
    {
        return str_replace("\n", "<br>", $text);
    }

    //! Insert a line of html into body of a page (to bottom of the body). If $indent contains anything else than -1 it's indented by that value, if it's -1 the indentation is automatic.
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
        $this->AppendHtmlLine($this->html_p($this->ReplaceControl(htmlspecialchars($text))));
    }

    public function AppendLine()
    {
        $this->AppendHtmlLine("<hr>");
    }

    public function AppendObject($object, $indent = -1)
    {
        $object->Parent = $this;
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
       $_b = "";
       foreach ($this->Items as $html)
       {
           // Convert the object to html
           if ($this->Indent && $html->Indent)
               $_b .= psf_indent_text($html->ToHtml(), $indent);
           else
               $_b .= $html->ToHtml() . "\n";
       }
       return $_b;
    }
}

