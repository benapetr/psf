<?php

class CssPage
{
    public $items = [];

    function __construct()
    {
        $this->items['*']['font-family'] = 'Helvetica, Arial';
    }

    public function PrintCss()
    {
        $buff = '';
        foreach ($this->items as $name => $values)
        {
            $buff .= $name . " {\n";
            foreach ($values as $vn => $xx)
            {
                $buff .= '    ' . $vn . ': ' . $xx . "\n";
            }
            $buff .= "}\n";
        }
        echo $buff;
    }
}
