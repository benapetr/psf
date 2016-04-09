This simple php framework makes it super easy to create full featured HTML
pages with basically zero knowledge of HTML, CSS or JS. You create web pages
just using the object oriented PHP code and nothing else.

See wiki for a complete documentation.

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
