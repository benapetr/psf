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
require_once (dirname(__FILE__) . "/html/primitive_object.php");
require_once (dirname(__FILE__) . "/csspage.php");

//! Represent a single Html page
class HtmlPage
{
    public $Title;
    public $Body;
    public $UseTidy = false;
    public $Language;
    public $TextEncoding;
    public $CssFile = NULL;
    public $ExternalCss = array();
    public $Style = NULL;
    public $ExternalJs = array();
    public $Prefix_Head = '';
    public $Suffix_Head = '';
    public $InternalJs = array();
    public $HtmlVersion = 5;
    public $Encoding = "UTF-8";
    public $AutoRefresh = 0;
    private $Items = array();
    private $cIndent = 4;

    function __construct($_title)
    {
        global $psf_language, $psf_encoding;
        $this->Style = new CssPage();
        $this->TextEncoding = $psf_encoding;
        $this->Language = $psf_language;
        $this->Title = $_title;
    }

    public static function IndentText($text, $in)
    {
        $prefix = '';
        while ($in-- > 0)
            $prefix .= ' ';
        
        $result = '';
        $lines = explode("\n", $text);
        foreach ($lines as $line)
            $result .= $prefix . $line . "\n";
        return $result; 
    }

    private function getHeader()
    {
        $_header = "<!DOCTYPE html>\n";
        $_header .= "<html>\n";
        $_header .= "  <head>\n";
        $_header .= $this->Prefix_Head;
        if ($this->HtmlVersion == 4)
            $_header .= "    <meta http-equiv=\"Content-Type\" content=\"text/html;charset=$this->Encoding\">\n";
        else if ($this->HtmlVersion > 4)
            $_header .= "    <meta charset=\"$this->Encoding\">\n";
        else
            $_header .= "    <!-- Unsupported html version: $this->HtmlVersion -->\n";
        $_header .= "    <meta http-equiv=\"Content-Language\" content=\"$this->Language\">\n";
        if ($this->AutoRefresh > 0)
            $_header .= "    <meta http-equiv=\"refresh\" content=\"" . $this->AutoRefresh . "\">\n";
        $_header .= "    <title>$this->Title</title>\n";
        foreach ($this->ExternalCss as $style)
            $_header .= "    <link rel='stylesheet' type='text/css' href='$style'>\n";
        foreach ($this->ExternalJs as $js)
            $_header .= "    <script type='text/javascript' src='$js'></script>\n";
        foreach ($this->InternalJs as $script)
        {
            $_header .= "    <script type=\"text/javascript\">\n";
            $_header .= self::IndentText($script, 6);
            $_header .= "    </script>\n";
        }
        if ($this->CssFile !== NULL)
            $_header .= "    <link rel='stylesheet' type='text/css' href='$this->CssFile'>\n";
        if ($this->Style !== NULL)
        {
            $_header .= "    <style>\n";
            $_header .= $this->Style->FetchCss(8);
            $_header .= "    </style>\n";
        }
        $_header .= $this->Suffix_Head;
        $_header .= "  </head>\n";
        return $_header;
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
        $this->AppendHtmlLine("<p>" . htmlspecialchars($text) . "</p>");
    }

    public function AppendObject($object, $indent = -1)
    {
        array_push($this->Items, $object);
    }

    public function InsertFileToBody($f)
    {
        $tx =  file_get_contents($f);
        if ($tx === FALSE)
            throw new Exception("File couldn't be read: " . $f);
        $this->AppendObject(new HtmlPrimitiveObject($tx));
    }

    private function getBody()
    {
       $indent = 4;
       $_b = "  <body>\n";
       $_b .= $this->Body;
       foreach ($this->Items as $html)
       {
           // Convert the object to html
           $_b .= HtmlPage::IndentText($html->ToHtml(), $indent);
       }
       $_b .= "  </body>\n";
       return $_b;
    }

    private function getFooter()
    {
        $_f = "</html>\n";
        return $_f;
    }

    public function PrintHtml()
    {
        echo $this->ToHtml();
        return true;
    }

    public function ToHtml()
    {
        // we first precache whole content in buffer, because if there were some exceptions, we don't want to get only partial html text
        $_header = $this->getHeader();
        $_body = $this->getBody();
        $_footer = $this->getFooter();
        if ($this->UseTidy)
        {
            $tidy = new tidy;
            $config = array( 'indent' => true, 'wrap' => 800 );
            $tidy->parseString($_header . $_body . $_footer, $config, 'utf8');
            return tidy_get_output($tidy);
        } else
        {
            return ($_header . $_body . $_footer);
        }
    }
}

