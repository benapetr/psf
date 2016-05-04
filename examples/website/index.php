<?php

# Example webpage created with psf
require("psf/psf.php");

$wp = new HtmlPage("Example web page");
$wp->AppendObject(new GitHub_Ribbon("benapetr/psf/blob/master/examples/website"));

# Create a line of text
$wp->AppendParagraph("This is an example web page");

# Create a html table

$table = new HtmlTable();
$table->Headers = [ "Sample", "header" ];
$table->AppendRow([ "1", "2" ]);
# Insert it to web page
$wp->AppendObject($table);

#print it
$wp->PrintHtml();


