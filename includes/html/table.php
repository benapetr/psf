<?php

// Part of simple php framework (spf)

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2017

require_once (dirname(__FILE__) . "/../../functions.php");
require_once (dirname(__FILE__) . "/element.php");

class HtmlTable_Cell extends HtmlElement
{
    public $Format = NULL;
    public $Class = NULL;
    public $Html;

    public function __construct($_html_ = "", $_parent = NULL)
    {
        parent::__construct($_parent);
        $this->Html = $_html_;
    }

    public function ToHtml()
    {
        $html = "";
        $prefix = "";

        if ($this->Format !== NULL)
            $prefix .= " " . $this->Format;

        if ($this->Style !== NULL)
        {
            $style = $this->Style->ToCss();
            if (strlen($style) > 0)
                $prefix .= " style=\"" . $style . "\"";
        }

        if ($this->Class !== NULL)
            $prefix .= " class=\"" . $this->Class . "\"";

        $html = "<td" . $prefix . ">";
        $html .= $this->Html;
        $html .= "</td>";
        return $html;
    }
}

class HtmlTable extends HtmlElement
{
    public $Class = NULL;
    private $mRows = 0;
    private $mColumns = 0;
    public $Format = NULL;
    public $Headers = array();
    public $BorderSize = 1;
    //! If you set this every column will have a same class as its name as long as it contains valid symbols
    //! this is useful in combination with javascript
    public $NameAsClass = false;
    public $Width = NULL;
    //! This is array of cell arrays, or at least that is expected
    public $Rows = array();
    //! Put number bigger than zero in order to repeat headers after N rows
    public $RepeatHeader = 0;

    public function GetFormat()
    {
        $f = "border=\"" . $this->BorderSize . "\"";
        if ($this->Width !== NULL)
            $f .= " width=\"" . $this->Width . "\"";
        if ($this->Style !== NULL)
            $f .= " style=\"" . $this->Style->ToCss() . "\"";
        if ($this->ClassName !== NULL)
            $f .= " class=\"" . $this->ClassName . "\"";
        if ($this->Format !== NULL)
        {
            $f .= " $this->Format";
        }
        if ($this->Class !== NULL)
            $f .= " class=\"" . $this->Class . "\"";
        while (psf_string_startsWith($f, " "))
            $f = substr($f, 1);
        return $f;
    }

    public function AppendRow(array $cells, $default_style = NULL)
    {
        $this->InsertRow($cells, $default_style);
    }

    //! Insert a new row by array that consist of html blocks only
    //! if you want to directly append array of html cells, just append directly to $this->Rows instead
    public function InsertRow(array $cells, $default_style = NULL)
    {
        if (count($cells) > $this->mColumns)
            $this->mColumns = count($cells);
        $mc = array();
        foreach ($cells as $cell)
        {
            if ($default_style === NULL)
            {
                array_push($mc, new HtmlTable_Cell($cell));
            } else
            {
                $temp = clone $default_style;
                $temp->Html = $cell;
                array_push($mc, $temp);
            }
        }
        array_push($this->Rows, $mc);
    }

    public function RowCount()
    {
        return count($this->Rows);
    }

    private function getHeader()
    {
        $html = "";
        if (count($this->Headers) > 0)
        {
            $html .= "  <tr>\n";
            foreach ($this->Headers as $x)
            {
                if ($this->NameAsClass)
                    $html .= "    <th class=\"" . psf_generate_friendly_name($x) . "\">" . $x . "</th>\n";
                else
                    $html .= "    <th>" . $x . "</th>\n";
            }
            $html .= "  </tr>\n";
        }
        return $html;
    }

    //! Converts the table to CSV format and return as a string
    public function ToCSV($separator = ";", $replace_quotas = false)
    {
        $txt = "";
        if (count($this->Headers) > 0)
        {
            foreach ($this->Headers as $x)
            {
                $str = str_replace($separator, "", $x);
                if ($replace_quotas)
                {
                    $str = str_replace('"', "", $str);
                    $str = str_replace("'", "", $str);
                }
                $txt .= $str . $separator;
            }
            $txt .= "\n";
        }
	foreach ($this->Rows as $row)
	{
            foreach ($row as $cell)
            {
                $str = $cell->Html;
                if ($replace_quotas)
                {
                    $str = str_replace('"', "", $str);
                    $str = str_replace("'", "", $str);
                }
                $txt .= $str . $separator;
            }
            $txt .= "\n";
        }
        return $txt;
    }

    public function ToHtml()
    {
        $prefix = "";
        $html = "<table $prefix" . $this->GetFormat() .">\n";
        $header = $this->getHeader();
        $html .= $header;
        $current_header = 0;
        foreach ($this->Rows as $row)
        {
            if ($this->RepeatHeader)
            {
                if ($current_header >= $this->RepeatHeader)
                {
                    $html .= $header;
                    $current_header = 0;
                } else
                {
                    $current_header++;
                }
            }
            $html .= "  <tr>\n";
            $header_n = 0;
            foreach ($row as $cell)
            {
                if ($this->NameAsClass)
                  $cell->Class = psf_generate_friendly_name($this->Headers[$header_n]);
                $html .= "    " . $cell->ToHtml() . "\n";
                $header_n++;
            }
            $html .= "  </tr>\n";
        }
        $html .= "</table>";
        return $html;
    }
}
