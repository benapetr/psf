<?php

// Part of php simple framework (psf)

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2025

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/element.php");

class Markdown extends HtmlElement
{
    public $SourceText;

    public function __construct($text = NULL, $_parent = NULL)
    {
        $this->SourceText = $text;
        parent::__construct($_parent);
    }

    public function Load($path)
    {
        $this->SourceText = file_get_contents($path);
    }

    private function parseInlineMarkdown($text)
    {
        // Bold (** or __)
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $text);

        // Italic (* or _)
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        $text = preg_replace('/_(.*?)_/', '<em>$1</em>', $text);

        // Optionally: implement underline (using HTML <u>), non-standard in Markdown
        $text = preg_replace('/~~(.*?)~~/', '<u>$1</u>', $text); // e.g., ~~underline~~

        return $text;
    }

    public function ToHtml()
    {
        if ($this->SourceText === NULL)
            return "NULL";

        $markdown = $this->SourceText;

        // Escape HTML special characters first
        $markdown = htmlspecialchars($markdown, ENT_NOQUOTES, 'UTF-8');

        // Normalize line endings
        $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);

        // Split into lines
        $lines = explode("\n", $markdown);
        $html = '';
        $inParagraph = false;

        foreach ($lines as $line)
        {
            $line = trim($line);

            if ($line === '')
            {
                if ($inParagraph)
                {
                    $html .= "</p>\n";
                    $inParagraph = false;
                }
                continue;
            }

            // Headers
            if (preg_match('/^(#{1,6})\s+(.*)$/', $line, $matches))
            {
                $level = strlen($matches[1]);
                $content = $matches[2];
                $html .= "<h$level>" . $this->parseInlineMarkdown($content) . "</h$level>\n";
                continue;
            }

            // Paragraph
            if (!$inParagraph)
            {
                $html .= "<p>";
                $inParagraph = true;
            } else
            {
                $html .= ' ';
            }

            $html .= $this->parseInlineMarkdown($line);
        }

        if ($inParagraph)
        {
            $html .= "</p>\n";
        }

        return $html;
    }
}
