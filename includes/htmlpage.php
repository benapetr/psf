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
require_once (dirname(__FILE__) . "/htmlcontainer.php");
require_once (dirname(__FILE__) . "/html/primitive_object.php");
require_once (dirname(__FILE__) . "/csspage.php");


//! \brief Represent a single Html page.
//! In order to create and print htmlpage with title Hello world you just do:
//! \code{.php}
//!     $html_page = new HtmlPage('Hello world');
//!     $html_page->PrintHtml();
//! \endcode
class HtmlPage extends HtmlContainer
{
    //! Title of the page
    public $Title;
    //! Body of a page (not a full html source code, but user defined body), in most cases you never need to directly use this
    public $Body;
    //! If enabled php module "tidy" will be used to format the output source code, it needs to be installed on server
    public $UseTidy = false;
    public $Language;
    public $TextEncoding;
    public $CssFile = NULL;
    public $InternalCss = array();
    public $ExternalCss = array();
    public $Style = NULL;
    public $ExternalJs = array();
    public $Prefix_Head = '';
    public $Suffix_Head = '';
    public $InternalJs = array();
    public $HtmlVersion = 5;
    public $Encoding = "UTF-8";
    public $AutoRefresh = 0;

    function __construct($_title, $_parent = NULL)
    {
        parent::__construct($_parent);
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
            $_header .= psf_indent_text($script, 6);
            $_header .= "    </script>\n";
        }
        if ($this->CssFile !== NULL)
            $_header .= "    <link rel='stylesheet' type='text/css' href='$this->CssFile'>\n";
        foreach ($this->InternalCss as $style)
        {
            $_header .= "    <style>\n";
            $_header .= psf_indent_text($style, 6);
            $_header .= "    </style>\n";
        }
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

    //! \brief Insert a header on bottom of current body of the page
    public function AppendHeader($text, $level = 1)
    {
        $this->AppendHtmlLine("<h$level>" . htmlspecialchars($text) . "</h$level>");
    }

    private function getBody()
    {
       $indent = 4;
       $_b = "  <body>\n";
       $_b .= $this->Body;
       foreach ($this->Items as $html)
       {
           // Convert the object to html
           $_b .= psf_indent_text($html->ToHtml(), $indent);
       }
       $_b .= "  </body>\n";
       return $_b;
    }

    private function getFooter()
    {
        $_f = "</html>\n";
        return $_f;
    }

    //! Prints a html source code of a page into stdout
    public function PrintHtml()
    {
        echo $this->ToHtml();
        return true;
    }

    //! Return whole html page as a string
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

