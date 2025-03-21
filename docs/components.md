# PSF Components Reference

This document provides detailed information about the available components in PSF.

## HTML Components

### HtmlPage

The core component that represents a complete web page.

```php
$page = new HtmlPage("Page Title");

// Optional settings
$page->Language = "en";
$page->TextEncoding = "UTF-8";
$page->AutoRefresh = 0; // Set to number of seconds for auto-refresh
$page->UseTidy = false; // Set to true to format output HTML

// Add content
$page->AppendParagraph("Hello World");
$page->PrintHtml();
```

### HtmlTable

Creates HTML tables with support for headers and multiple rows.

```php
$table = new HtmlTable();
$table->Headers = ["Name", "Age", "Location"];
$table->AppendRow(["John", "25", "New York"]);
$table->AppendRow(["Jane", "30", "London"]);

// Optional settings
$table->BorderSize = 1;
$table->RepeatHeader = 10; // Repeat headers every 10 rows
$table->NameAsClass = true; // Use header names as CSS classes
```

### TextBox

Creates HTML input fields.

```php
$textbox = new TextBox("username", "default value");
$textbox->Placeholder = "Enter username";
$textbox->Required = true;
$textbox->Password = false; // Set to true for password field
$textbox->ReadOnly = false;
$textbox->Size = 30;
```

### Form

Creates HTML forms with support for various input types.

```php
$form = new Form();
$form->Method = FormMethod::Post;
$form->Action = "process.php";

$form->AppendObject(new TextBox("username"));
$form->AppendObject(new Button("submit", "Submit"));
```

## Bootstrap Components

### BS_Table

Enhanced table with Bootstrap styling.

```php
$table = new BS_Table();
$table->Condensed = true;
$table->Hover = true;
$table->Headers = ["Column 1", "Column 2"];
$table->AppendRow(["Data 1", "Data 2"]);
```

### BS_ProgressBar

Creates a Bootstrap progress bar.

```php
$progress = new BS_ProgressBar(0, 100, 75, "75% Complete");
```

### BS_Well

Creates a Bootstrap well container.

```php
$well = new BS_Well();
$well->AppendParagraph("Content in a well");
```

## Authentication Components

### PsfPasswd

Basic password-based authentication.

```php
$auth = new PsfPasswd("users.txt");
if ($auth->Authenticate($username, $password)) {
    // User is authenticated
}
```

### PsfTokenAuth

Token-based authentication system.

```php
$auth = new PsfTokenAuth();
// Configure tokens and validate them
```

## Utility Functions

PSF provides several utility functions:

```php
// String manipulation
psf_string_auto_trim($string, $max, $suffix = "...");
psf_string_startsWith($string, $text);
psf_string_endsWith($string, $text);

// Debug helpers
psf_debug_log($text);
psf_print_debug_as_html();
psf_get_execution_time();

// System configuration
psf_php_enable_debug();
psf_php_disable_debug();
```

## CSS Integration

PSF provides both inline and external CSS support:

```php
$page = new HtmlPage("Styled Page");

// Add external CSS
$page->ExternalCss[] = "styles.css";

// Add inline CSS
$page->Style->items["body"]["background-color"] = "#f0f0f0";
$page->Style->items["p"]["color"] = "#333";
```

## JavaScript Integration

You can add both external and inline JavaScript:

```php
$page = new HtmlPage("Interactive Page");

// Add external JavaScript
$page->ExternalJs[] = "script.js";

// Add inline JavaScript
$script = new Script("alert('Hello!');");
$page->AppendObject($script);
```