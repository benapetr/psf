# PSF (PHP Simple Framework) Documentation

PSF is a lightweight PHP framework that allows you to create full-featured HTML pages using purely object-oriented PHP code, without writing any HTML, CSS, or JavaScript directly. It's designed to make web development simpler and more maintainable by providing a clean, object-oriented interface to web page elements.

See wiki or docs for a complete documentation.

Please note that PSF is a work in progress and many features are missing now.

Example code
=============

```
<?php
# Example webpage created with psf
require("psf/psf.php");

# Create a html page
$wp = new HtmlPage("Example web page");

# Github link
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
```

See http://petr.insw.cz/devel/psf/psf/examples/website/ for result
