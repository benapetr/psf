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

require_once (dirname(__FILE__) . "/../default_config.php");
require_once (dirname(__FILE__) . "/csspage.php");

//! Represent a single Html page
class HtmlPage
{
    public $Title;
    public $Body;
    public $Language;
    public $TextEncoding;
    public $CssFile = NULL;
    public $Style = NULL;
    public $ExternalJs = array();
    private $cIndent = 4;

    function __construct($_title)
    {
        global $psf_language, $psf_encoding;
        $this->Style = new CssPage();
        $this->TextEncoding = $psf_encoding;
        $this->Language = $psf_language;
        $this->Title = $_title;
    }

    private function getHeader()
    {
        $_header = "<!DOCTYPE html>\n";
        $_header .= "<html>\n";
        $_header .= "  <head>\n";
        $_header .= "    <meta http-equiv=\"Content-Language\" content=\"$this->Language\">\n";
        $_header .= "    <title>$this->Title</title>\n";
        foreach ($this->ExternalJs as $js)
            $_header .=    "<script type='text/javascript' src='$js'></script>\n";
        if ($this->CssFile !== NULL)
            $_header .= "    <link rel=\"stylesheet\" type=\"text/css\" href=\"$this->CssFile\">\n";
        if ($this->Style !== NULL)
        {
            $_header .= "    <style>\n";
            $_header .= $this->Style->FetchCss(8);
            $_header .= "    </style>\n";
        }
        $_header .= "  </head>\n";
        return $_header;
    }

    public function AppendHtmlLine($html, $indent = -1)
    {
        $value = "";
        if ($indent < 0)
            $indent = $this->cIndent;
        while ($indent-- > 0)
        {
            $value .= " ";
        }
        $this->Body .= $value . $html . "\n";
    }

    public function AppendHtml($html, $indent = -1)
    {
        $lines = explode("\n", $html);
        foreach ($lines as $l)
            $this->AppendHtmlLine($l, $indent);
    }

    public function InsertFileToBody($f)
    {
        $tx =  file_get_contents($f);
        if ($tx === FALSE)
            throw new Exception("File couldn't be read: " . $f);
        $this->Body .= $tx;
    }

    private function getBody()
    {
       $_b = "  <body>\n";
       $_b .= $this->Body;
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
        // we first precache whole content in buffer, because if there were some exceptions, we don't want to get only partial html text
        $_header = $this->getHeader();
        $_body = $this->getBody();
        $_footer = $this->getFooter();
        echo ($_header . $_body . $_footer);
        return true;
    }
}

