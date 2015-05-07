<?php

require ("../default_config.php");

//! Represent a single Html page
class HtmlPage
{
    public $Title;
    public $Body;
    public $Language = "en";
    public $CssFile = NULL;

    function __construct($_title)
    {
        $this->Title = $_title;
    }

    private function getHeader()
    {
        $_header = "<!DOCTYPE html>\n";
        $_header .= "<html>\n";
        $_header .= "  <head>\n";
        $_header .= "    <meta http-equiv=\"Content-Language\" content=\"$this->Language\">\n";
        $_header .= "    <title>$this->Title</title>\n";
        if ($this->CssFile !== NULL)
            $_header .= "    <link rel=\"stylesheet\" type=\"text/css\" href=\"$this->CssFile\">\n";
        $_header .= "  </head>";
        return $_header;
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
       $_b = "  </body>\n";
       return $_b;
    }

    private function getFooter()
    {
        $_f = "</html>";
        return $_f;
    }

    public function RenderHtml()
    {
        // we first precache whole content in buffer, because if there were some exceptions, we don't want to get only partial html text
        $_header = $this->getHeader();
        $_body = $this->renderBody();
        $_footer = $this->renderFooter();
        echo ($_header . $_body . $_footer);
        return true;
    }
}

