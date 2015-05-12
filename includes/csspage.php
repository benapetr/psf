<?php

class CssPage
{
    public $items = array();

    function __construct()
    {
        $this->items['*']['font-family'] = 'Helvetica, Arial';
    }

    public function FetchCss($n)
    {
        $buff = '';
        $indentation = '';
        while ($n-- > 0)
            $indentation .= ' ';
        foreach ($this->items as $name => $values)
        {
            $buff .= $indentation . $name . " {\n";
            foreach ($values as $vn => $xx)
            {
                $buff .= $indentation . '    ' . $vn . ': ' . $xx . "\n";
            }
            $buff .= $indentation . "}\n";
        }
        return $buff;
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
