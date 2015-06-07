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

class HtmlTable_Cell extends HtmlElement
{
    public $Style;
    public $Html;

    public function __construct($_html_)
    {
        $this->Html = $_html_;
    }

    public function ToHtml()
    {
        $html = "<td>";
        $html .= $this->Html;
        $html .= "</td>";
        return $html;
    }
}

class HtmlTable extends HtmlElement
{
    private $mRows = 0;
    private $mColumns = 0;
    public $Headers = array();
    public $BorderSize = 1;
    //! This is array of cell arrays, or at least that is expected
    public $Rows = array();

    public function GetFormat()
    {
        return "border=" . $this->BorderSize;
    }

    //! Insert a new row by array that consist of html blocks only
    //! if you want to directly append array of html cells, just append directly to $this->Rows instead
    public function InsertRow(array $cells)
    {
        $mc = array();
        foreach ($cells as $cell)
        {
            array_push($mc, new HtmlTable_Cell($cell));
        }
        array_push($this->Rows, $mc);
    }

    public function ToHtml()
    {
        $html = "<table " . $this->GetFormat() .">\n";
        foreach ($this->Rows as $row)
        {
            $html .= "  <tr>\n";
            foreach ($row as $cell)
            {
                $html .= "    " . $cell->ToHtml() . "\n";
            }
            $html .= "  </tr>\n";
        }
        $html .= "</table>";
        return $html;
    }
}