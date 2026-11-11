<?php

// Run with: php tests/table_widths.php
require_once(dirname(__DIR__) . '/psf.php');

$check = function($condition, $message)
{
    if (!$condition)
        throw new RuntimeException($message);
};

foreach ([80 => '80px', '42.5' => '42.5px', '50mm' => '50mm', '80px' => '80px', '10%' => '10%'] as $width => $css_width)
{
    $table = new HtmlTable();
    $table->Headers = ['Header'];
    $table->SetColumnWidth(0, $width);
    $cell = new HtmlTable_Cell('Content');
    $cell->Style = new InlineCSS();
    $cell->Style->SetProperty('color', 'red');
    $table->Rows[] = [$cell];

    $html = $table->ToHtml();
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    foreach (['//col', '//th', '//td'] as $selector)
    {
        $style = $xpath->evaluate('string(' . $selector . '/@style)');
        $check(strpos($style, 'width:' . $css_width) !== false, 'Missing CSS width on ' . $selector . ': ' . $width);
        $check($xpath->query($selector . '/@width')->length === 0, 'Unexpected legacy width attribute: ' . $width);
    }
    $check(strpos($xpath->evaluate('string(//td/@style)'), 'color: red;') !== false, 'Existing cell style lost');
    $check($cell->Style->GetProperty('width') === NULL, 'Rendering mutated the cell style');
    $check($table->ToHtml() === $html, 'Repeated rendering changed output');
}

$table = new HtmlTable();
$table->SetColumnWidth(0, '50mm');
$table->SetColumnWidth(2, '20%');
$title = new HtmlTable_Cell('Title');
$title->ColSpan = 3;
$span = new HtmlTable_Cell('Two columns');
$span->ColSpan = 2;
$table->Rows = [[$title], [$span, new HtmlTable_Cell('Third column')]];
$dom = new DOMDocument();
$dom->loadHTML($table->ToHtml());
$xpath = new DOMXPath($dom);
$check($xpath->query('//col')->length === 3, 'Sparse column definitions lost');
$check($xpath->evaluate('string(//col[2]/@style)') === '', 'Unconfigured column received a width');
$check($xpath->query('//td[@colspan]/@style')->length === 0, 'Single-column width applied to a spanning cell');
$check($xpath->evaluate('string(//tr[2]/td[2]/@style)') === 'width:20%;', 'Wrong width after colspan');

$cell = new HtmlTable_Cell('Explicit width');
$cell->Style = new InlineCSS();
$cell->Style->SetProperty('width', '30mm');
$dom->loadHTML($cell->ToHtml('50mm'));
$style = (new DOMXPath($dom))->evaluate('string(//td/@style)');
$column_width_position = strpos($style, 'width:50mm;');
$cell_width_position = strpos($style, 'width: 30mm;');
$check($column_width_position !== false && $cell_width_position !== false && $column_width_position < $cell_width_position,
    'Explicit cell style must retain precedence');

$cell = new HtmlTable_Cell('No width');
$check($cell->ToHtml() === '<td>No width</td>', 'Unconfigured cell rendering changed');
echo "Table width regression checks passed.\n";
