# OIDC Authentication for PSF Framework

This document explains how to implement OpenID Connect (OIDC) authentication in your PSF-based applications.

## Overview

The `PsfOIDC` class provides OpenID Connect authentication for the PSF framework, allowing you to integrate with:

- Microsoft Azure AD
- Google Identity Platform
- Auth0
- Okta
- Any other OIDC-compliant identity provider

## Configuration

Create an INI file with your OIDC provider settings:

```ini
client_id = "your-client-id"
client_secret = "your-client-secret"
redirect_uri = "https://your-app.example.com/oidc_callback.php"
provider = "https://login.microsoftonline.com/your-tenant-id/v2.0"

# Optional parameters
scopes = "openid profile email offline_access"

# Map PSF privileges to provider roles or groups
privilege_roles[admin] = "Admin"
privilege_roles[user] = "User"
```

## Basic Usage

```php
// Define PSF entry point
define("PSF_ENTRY_POINT", 1);
require_once("psf/psf.php");

// Initialize OIDC authentication
$oidc = OIDCFromIniFile("path/to/oidc_config.ini");

// Handle OIDC callback
if (isset($_GET['code']) && isset($_GET['state'])) {
    $result = $oidc->HandleCallback();
    if ($result === OIDC_OK) {
        // Authentication successful
        header("Location: index.php");
        exit;
    }
}

// Check if user is authenticated
if (!$oidc->IsAuthenticated()) {
    // Start authentication flow
    $oidc->Authorize();
    exit;
}

// User is authenticated
$user = $oidc->GetUser();
echo "Hello, " . htmlspecialchars($user->Name);
```

## Integration with PSF Auth Stack

To use OIDC as your primary authentication method, update your application's bootstrap code:

```php
// Initialize HTML stack with OIDC authentication
$html = new HTMLStack();
$oidc = psf_oidc_from_ini_file("path/to/oidc_config.ini");
$html->SetAuthorizationProvider($oidc);
```

## Role-Based Access Control

Define role mappings in your config file and check privileges in your code:

```php
// Check if user has admin privileges
if ($oidc->IsPrivileged('admin')) {
    // Show admin features
}
```

## Microsoft Azure AD Specific Setup

1. Register a new application in Azure AD
2. Set redirect URI to your callback URL
3. Enable ID tokens in Authentication settings
4. Grant API permissions as needed
5. Create App roles for your application if you need role-based access control

## Logout

To log the user out:

```php
$oidc->Logout();
```

This will clear the local session and, if supported by the provider, redirect to the identity provider's logout endpoint.

## Troubleshooting

- Ensure your client ID and secret are correct
- Verify the redirect URI exactly matches what's registered with the provider
- Check that your scopes include at least "openid"
- For role-based access, ensure the proper claims are included in the ID token
