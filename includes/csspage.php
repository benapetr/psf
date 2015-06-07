<?php

class CssPage
{
    public $items = array();
    public $BackgroundColor = NULL;

    function __construct()
    {
        $this->items['*']['font-family'] = 'Helvetica, Arial';
    }

    private function Load()
    {
        if ($this->BackgroundColor !== NULL)
            $this->items['body']['background-color'] = $this->BackgroundColor;
    }

    public function FetchCss($n)
    {
        $buff = '';
        $indentation = '';
        $this->Load();
        while ($n-- > 0)
            $indentation .= ' ';
        foreach ($this->items as $name => $values)
        {
            $buff .= $indentation . $name . " {\n";
            foreach ($values as $vn => $xx)
            {
                $buff .= $indentation . '    ' . $vn . ': ' . $xx . ";\n";
            }
            $buff .= $indentation . "}\n";
        }
        return $buff;
    }

    public function PrintCss()
    {
        echo $this->FetchCss(2);
    }
}
