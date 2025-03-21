# PSF (PHP Simple Framework) Documentation

PSF is a lightweight PHP framework that allows you to create full-featured HTML pages using purely object-oriented PHP code, without writing any HTML, CSS, or JavaScript directly. It's designed to make web development simpler and more maintainable by providing a clean, object-oriented interface to web page elements.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Key Features](#key-features)
3. [Basic Concepts](#basic-concepts)
4. [Detailed Documentation](components.md)

## Getting Started

### Installation

1. Copy the PSF framework files to your project directory
2. Include the main PSF file in your PHP script:

```php
require("psf/psf.php");
```

### Basic Usage

Here's a simple example that creates a webpage with a table:

```php
<?php
require("psf/psf.php");

// Create a new HTML page with a title
$wp = new HtmlPage("My First PSF Page");

// Add a paragraph of text
$wp->AppendParagraph("Welcome to my page!");

// Create and configure a table
$table = new HtmlTable();
$table->Headers = ["Column 1", "Column 2"];
$table->AppendRow(["Value 1", "Value 2"]);

// Add the table to the page
$wp->AppendObject($table);

// Output the complete HTML
$wp->PrintHtml();
```

## Key Features

- **Pure PHP Development**: Create complete web pages using only PHP code
- **Object-Oriented Design**: Everything is an object, making code more organized and maintainable
- **Bootstrap Integration**: Built-in support for Bootstrap 3
- **Component Library**: Rich set of pre-built components including:
  - Tables
  - Forms
  - Buttons
  - Text inputs
  - Checkboxes
  - Progress bars
  - And more

## Basic Concepts

### Page Structure

The basic structure of a PSF page revolves around the `HtmlPage` class, which represents the entire web page. You can add elements to the page using various methods:

- `AppendObject()`: Add any PSF component to the page
- `AppendParagraph()`: Quick way to add text content

### Components

PSF provides two types of components:

1. **Basic HTML Components**:
   - `HtmlTable`
   - `TextBox`
   - `Button`
   - `CheckBox`
   - `Form`
   - `Image`

2. **Bootstrap Components** (prefixed with BS_):
   - `BS_Table`
   - `BS_Button`
   - `BS_Form`
   - `BS_ProgressBar`
   - `BS_Well`

### Authentication

PSF includes several authentication mechanisms:
- Password-based authentication
- Token-based authentication
- OAuth support
- Callback-based authentication for custom implementations