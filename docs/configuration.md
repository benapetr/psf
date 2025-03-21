# PSF Configuration Guide

PSF provides several configuration options that can be customized for your project. These settings are defined in `default_config.php`.

## Global Settings

```php
// Language settings
$psf_language = "en";
$psf_encoding = "UTF-8";
$psf_localization = "lang";
$psf_localization_default_language = "en";

// Performance settings
$psf_indent = 4;
$psf_indent_system_enabled = True;  // Set to false for better performance on large pages

// Container behavior
$psf_containers_auto_insert_child = false;  // Auto-insert child objects into containers

// Bootstrap settings
$psf_bootstrap_enabled = True;
$psf_bootstrap_target_version = 3;
$psf_bootstrap_css_url = "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css";
$psf_bootstrap_js_url = "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js";

// System paths
$psf_home = "psf/";
$psf_log = "/tmp/psf.log";
```

## Customizing Configuration

To customize PSF configuration:

1. Create a custom configuration file (e.g., `my_config.php`)
2. Include it after the default configuration:

```php
require("psf/psf.php");
require("my_config.php");  // Override default settings
```

## Localization

PSF supports multiple languages through its localization system:

1. Create language files in your localization directory (default: `lang/`)
2. Name files with language code (e.g., `en.txt`, `fr.txt`)
3. Use key:value format in language files:

```
welcome:Welcome to my site
error:An error occurred
```

4. Use the localization in code:

```php
echo _l("welcome");  // Outputs text in current language
```

## Bootstrap Integration

PSF comes with Bootstrap 3 integration enabled by default. To use it:

1. Keep `$psf_bootstrap_enabled = True`
2. Initialize Bootstrap on your page:

```php
$page = new HtmlPage("Bootstrap Page");
bootstrap_init($page);  // Initialize Bootstrap
```

3. Use Bootstrap components (BS_ prefixed classes)

To disable Bootstrap:

```php
$psf_bootstrap_enabled = False;
```

## Performance Optimization

For large pages, you can improve performance by:

1. Disabling indentation:
```php
$psf_indent_system_enabled = False;
```

2. Minimizing debug output:
```php
psf_php_disable_debug();
```

## Security Configuration

When using authentication features:

1. For password-based auth:
```php
$auth = new PsfPasswd("users.txt", true, "your_custom_salt");
```

2. For OAuth:
```php
$oauth_config = [
    "url" => "https://oauth.provider.com",
    "consumerKey" => "your_key",
    "consumerSecret" => "your_secret"
];
```

## Debugging

Enable debug mode for development:

```php
psf_php_enable_debug();
```

To log debug information:

```php
psf_debug_log("Debug message");
psf_print_debug_as_html();  // Print debug info as HTML comments
```