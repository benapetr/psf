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

// Copyright (c) Petr Bena <petr@bena.rocks> 2025

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

require_once (dirname(__FILE__) . "/authbase.php");

// Define OIDC status constants
define("PSF_OIDC_OK", 0);
define("PSF_OIDC_NOT_AUTH", 1);
define("PSF_OIDC_ERROR", 2);

/**
 * Class PsfOIDC_User
 * Represents a user authenticated through OIDC
 */
class PsfOIDC_User
{
    public $Username;
    public $Email;
    public $Name;
    public $Roles = array();
    public $TokenData = array();
    public $IdToken = null;
    public $AccessToken = null;
    public $RefreshToken = null;
    public $TokenExpiry = null;

    public function __construct($id_token_data = null)
    {
        if ($id_token_data !== null) {
            $this->PopulateFromIdToken($id_token_data);
        }
    }

    /**
     * Populate user data from ID token
     * 
     * @param array $id_token_data The decoded ID token data
     */
    public function PopulateFromIdToken($id_token_data)
    {
        $this->TokenData = $id_token_data;
        
        // Extract standard OIDC claims
        if (isset($id_token_data['sub']))
            $this->Username = $id_token_data['sub'];
        
        if (isset($id_token_data['email']))
            $this->Email = $id_token_data['email'];
        
        if (isset($id_token_data['name']))
        {
            $this->Name = $id_token_data['name'];
        } else
        {
            // Try to construct name from given_name and family_name
            $name_parts = array();
            if (isset($id_token_data['given_name']))
                $name_parts[] = $id_token_data['given_name'];

            if (isset($id_token_data['family_name']))
                $name_parts[] = $id_token_data['family_name'];

            if (!empty($name_parts))
                $this->Name = implode(' ', $name_parts);
        }
        
        // Extract roles from token
        // Different OIDC providers use different claims for roles
        // Microsoft typically uses 'roles' or 'wids'
        if (isset($id_token_data['roles']) && is_array($id_token_data['roles'])) {
            $this->Roles = $id_token_data['roles'];
        } elseif (isset($id_token_data['groups']) && is_array($id_token_data['groups'])) {
            $this->Roles = $id_token_data['groups'];
        }
    }

    /**
     * Check if user has a specific role
     * 
     * @param string $role Role name to check
     * @return bool True if user has the role
     */
    public function HasRole($role)
    {
        return in_array($role, $this->Roles);
    }
}

/**
 * PsfOIDC - OpenID Connect authentication provider for PSF
 * 
 * This class implements OIDC authentication flow for the PSF framework
 * with support for Microsoft, Google, and other OIDC-compliant providers
 */
class PsfOIDC extends PsfAuthBase
{
    // Configuration
    public $ClientId = null;
    public $ClientSecret = null;
    public $RedirectUri = null;
    public $Scopes = 'openid profile email';
    public $Provider = null;  // Provider URL (e.g., 'https://login.microsoftonline.com/{tenant}/v2.0')
    public $MetadataEndpoint = null;  // Optional override for metadata URL
    
    // OIDC endpoints (discovered or manually set)
    protected $AuthorizationEndpoint = null;
    protected $TokenEndpoint = null;
    protected $UserinfoEndpoint = null;
    protected $JwksUri = null;
    
    // Session and state management
    protected $State = null;
    protected $Nonce = null;
    protected $PKCECodeVerifier = null;
    
    // User info
    protected $User = null;
    protected $PrivilegedRoles = array();
    
    /**
     * Constructor
     * 
     * @param string $client_id OIDC Client ID
     * @param string $client_secret OIDC Client Secret
     * @param string $redirect_uri Redirect URI for OIDC flow
     * @param string $provider Provider base URL
     */
    public function __construct($client_id, $client_secret, $redirect_uri, $provider)
    {
        $this->ClientId = $client_id;
        $this->ClientSecret = $client_secret;
        $this->RedirectUri = $redirect_uri;
        $this->Provider = $provider;
        
        // Initialize session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Try to load existing user session
        $this->loadSession();
    }
    
    /**
     * Get the identity provider ID
     * 
     * @return string The ID of this auth provider
     */
    public function GetID()
    {
        return "OIDC";
    }
    
    /**
     * Check if user has a specific privilege
     * 
     * @param string $privilege The privilege to check
     * @return bool True if user has the specified privilege
     */
    public function IsPrivileged($privilege)
    {
        if (!$this->IsAuthenticated())
            return false;
        
        // Check if user has the role that maps to this privilege
        if (isset($this->PrivilegedRoles[$privilege]))
        {
            $required_role = $this->PrivilegedRoles[$privilege];
            return $this->User->HasRole($required_role);
        }
        
        return false;
    }
    
    /**
     * Check if user is authenticated
     * 
     * @return bool True if user is authenticated
     */
    public function IsAuthenticated()
    {
        // Check if we have a valid user object and tokens haven't expired
        if ($this->User !== null && $this->User->AccessToken !== null)
        {
            // Check if token has expired
            if ($this->User->TokenExpiry !== null && $this->User->TokenExpiry > time())
            {
                return true;
            } else if ($this->User->RefreshToken !== null)
            {
                // Try to refresh the token
                return $this->refreshToken();
            }
        }
        
        return false;
    }
    
    /**
     * Set role mappings to privileges
     * 
     * @param array $role_map Associative array mapping privileges to roles
     */
    public function SetPrivilegeRoleMap($role_map)
    {
        $this->PrivilegedRoles = $role_map;
    }
    
    /**
     * Get the current authenticated user
     * 
     * @return PsfOIDC_User|null The user object or null if not authenticated
     */
    public function GetUser()
    {
        return $this->User;
    }
    
    /**
     * Start the authorization flow
     * 
     * @param array $additional_params Additional parameters to include in auth request
     * @return bool True if redirect was successful
     */
    public function Authorize($additional_params = array())
    {
        // Discover endpoints if needed
        if ($this->AuthorizationEndpoint === null)
            $this->discoverEndpoints();
        
        // Generate state and nonce for security
        $this->State = $this->generateRandomString(32);
        $this->Nonce = $this->generateRandomString(32);
        
        // Generate PKCE code verifier and challenge
        $this->PKCECodeVerifier = $this->generateRandomString(64);
        $code_challenge = $this->generateCodeChallenge($this->PKCECodeVerifier);
        
        // Save state in session
        $_SESSION['oidc_state'] = $this->State;
        $_SESSION['oidc_nonce'] = $this->Nonce;
        $_SESSION['oidc_code_verifier'] = $this->PKCECodeVerifier;
        
        // Build authorization URL
        $params = array(
            'client_id' => $this->ClientId,
            'response_type' => 'code',
            'redirect_uri' => $this->RedirectUri,
            'scope' => $this->Scopes,
            'state' => $this->State,
            'nonce' => $this->Nonce,
            'code_challenge' => $code_challenge,
            'code_challenge_method' => 'S256'
        );
        
        // Add any additional parameters
        if (!empty($additional_params)) {
            $params = array_merge($params, $additional_params);
        }
        
        $auth_url = $this->AuthorizationEndpoint . '?' . http_build_query($params);
        
        // Redirect to authorization endpoint
        header('Location: ' . $auth_url);
        echo 'Redirecting to authentication provider. Click <a href="' . htmlspecialchars($auth_url) . '">here</a> if you are not redirected automatically.';
        exit;
    }
    
    /**
     * Handle the authorization callback
     * 
     * @return int OIDC status code
     */
    public function HandleCallback()
    {
        // Check if there's an error
        if (isset($_GET['error']))
            return PSF_OIDC_ERROR;
        
        // Check if we have an authorization code
        if (!isset($_GET['code']))
            return PSF_OIDC_NOT_AUTH;
        
        // Verify state parameter
        if (!isset($_GET['state']) || !isset($_SESSION['oidc_state']) || $_GET['state'] !== $_SESSION['oidc_state'])
            return PSF_OIDC_ERROR;
        
        // Get the authorization code
        $code = $_GET['code'];
        $code_verifier = $_SESSION['oidc_code_verifier'];
        $nonce = $_SESSION['oidc_nonce'];
        
        // Exchange code for tokens
        $result = $this->exchangeCodeForTokens($code, $code_verifier);
        if (!$result)
            return PSF_OIDC_ERROR;
        
        // Verify ID token
        $id_token_data = $this->verifyAndDecodeIdToken($this->User->IdToken, $nonce);
        if (!$id_token_data)
            return PSF_OIDC_ERROR;
        
        // Update user object with ID token data
        $this->User->PopulateFromIdToken($id_token_data);
        
        // Store user in session
        $this->saveSession();
        
        return PSF_OIDC_OK;
    }
    
    /**
     * Log out the current user
     */
    public function Logout()
    {
        // Clear user data
        $this->User = null;
        
        // Clear session data
        unset($_SESSION['oidc_user']);
        unset($_SESSION['oidc_access_token']);
        unset($_SESSION['oidc_id_token']);
        unset($_SESSION['oidc_refresh_token']);
        unset($_SESSION['oidc_token_expiry']);
        unset($_SESSION['oidc_state']);
        unset($_SESSION['oidc_nonce']);
        unset($_SESSION['oidc_code_verifier']);
        
        // If provider has end session endpoint, redirect there
        if (isset($this->EndSessionEndpoint) && !empty($this->EndSessionEndpoint)) {
            $params = array(
                'post_logout_redirect_uri' => $this->RedirectUri
            );
            
            $logout_url = $this->EndSessionEndpoint . '?' . http_build_query($params);
            header('Location: ' . $logout_url);
            exit;
        }
    }
    
    /**
     * Discover OIDC endpoints from the provider's well-known configuration
     * 
     * @return bool True if discovery was successful
     */
    protected function discoverEndpoints()
    {
        $metadata_url = $this->MetadataEndpoint;
        if ($metadata_url === null) {
            $metadata_url = rtrim($this->Provider, '/') . '/.well-known/openid-configuration';
        }
        
        $response = $this->makeRequest($metadata_url);
        if (!$response) {
            return false;
        }
        
        $metadata = json_decode($response, true);
        if (!$metadata) {
            return false;
        }
        
        // Extract endpoints
        if (isset($metadata['authorization_endpoint'])) {
            $this->AuthorizationEndpoint = $metadata['authorization_endpoint'];
        }
        
        if (isset($metadata['token_endpoint'])) {
            $this->TokenEndpoint = $metadata['token_endpoint'];
        }
        
        if (isset($metadata['userinfo_endpoint'])) {
            $this->UserinfoEndpoint = $metadata['userinfo_endpoint'];
        }
        
        if (isset($metadata['jwks_uri'])) {
            $this->JwksUri = $metadata['jwks_uri'];
        }
        
        if (isset($metadata['end_session_endpoint'])) {
            $this->EndSessionEndpoint = $metadata['end_session_endpoint'];
        }
        
        return true;
    }
    
    /**
     * Exchange authorization code for tokens
     * 
     * @param string $code Authorization code
     * @param string $code_verifier PKCE code verifier
     * @return bool True if exchange was successful
     */
    protected function exchangeCodeForTokens($code, $code_verifier)
    {
        // Discover endpoints if needed
        if ($this->TokenEndpoint === null) {
            $this->discoverEndpoints();
        }
        
        $params = array(
            'client_id' => $this->ClientId,
            'client_secret' => $this->ClientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->RedirectUri,
            'code_verifier' => $code_verifier
        );
        
        $response = $this->makeRequest($this->TokenEndpoint, $params, 'POST');
        if (!$response) {
            return false;
        }
        
        $token_data = json_decode($response, true);
        if (!$token_data || !isset($token_data['access_token']) || !isset($token_data['id_token'])) {
            return false;
        }
        
        // Create user object if it doesn't exist
        if ($this->User === null) {
            $this->User = new PsfOIDC_User();
        }
        
        // Store tokens
        $this->User->AccessToken = $token_data['access_token'];
        $this->User->IdToken = $token_data['id_token'];
        
        if (isset($token_data['refresh_token'])) {
            $this->User->RefreshToken = $token_data['refresh_token'];
        }
        
        if (isset($token_data['expires_in'])) {
            $this->User->TokenExpiry = time() + intval($token_data['expires_in']);
        }
        
        return true;
    }
    
    /**
     * Verify and decode the ID token
     * 
     * @param string $id_token Raw ID token
     * @param string $nonce Expected nonce value
     * @return array|false Decoded token data or false on failure
     */
    protected function verifyAndDecodeIdToken($id_token, $nonce)
    {
        // For production use, this should be replaced with proper JWT validation
        // including signature verification using JWK from the JWKS endpoint
        
        // Basic JWT parsing
        $token_parts = explode('.', $id_token);
        if (count($token_parts) !== 3) {
            return false;
        }
        
        // Decode the payload
        $payload = json_decode(base64_decode(strtr($token_parts[1], '-_', '+/')), true);
        if (!$payload) {
            return false;
        }
        
        // Verify issuer
        if (!isset($payload['iss']) || strpos($payload['iss'], $this->Provider) !== 0) {
            return false;
        }
        
        // Verify audience
        if (!isset($payload['aud']) || (is_string($payload['aud']) && $payload['aud'] !== $this->ClientId) || 
            (is_array($payload['aud']) && !in_array($this->ClientId, $payload['aud']))) {
            return false;
        }
        
        // Verify nonce if provided
        if ($nonce !== null && (!isset($payload['nonce']) || $payload['nonce'] !== $nonce)) {
            return false;
        }
        
        // Verify token is not expired
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
    
    /**
     * Refresh the access token using the refresh token
     * 
     * @return bool True if refresh was successful
     */
    protected function refreshToken()
    {
        if ($this->User === null || $this->User->RefreshToken === null)
            return false;
        
        // Discover endpoints if needed
        if ($this->TokenEndpoint === null)
            $this->discoverEndpoints();
        
        $params = array(
            'client_id' => $this->ClientId,
            'client_secret' => $this->ClientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->User->RefreshToken
        );
        
        $response = $this->makeRequest($this->TokenEndpoint, $params, 'POST');
        if (!$response)
            return false;
        
        $token_data = json_decode($response, true);
        if (!$token_data || !isset($token_data['access_token']))
            return false;
        
        // Update tokens
        $this->User->AccessToken = $token_data['access_token'];
        
        if (isset($token_data['id_token']))
        {
            $this->User->IdToken = $token_data['id_token'];
            
            // Update user data from new ID token
            $id_token_data = $this->verifyAndDecodeIdToken($this->User->IdToken, null);
            if ($id_token_data)
                $this->User->PopulateFromIdToken($id_token_data);
        }
        
        if (isset($token_data['refresh_token']))
            $this->User->RefreshToken = $token_data['refresh_token'];
        
        if (isset($token_data['expires_in']))
            $this->User->TokenExpiry = time() + intval($token_data['expires_in']);
        
        // Save updated session
        $this->saveSession();
        
        return true;
    }
    
    /**
     * Load user session from PHP session
     */
    protected function loadSession()
    {
        if (isset($_SESSION['oidc_user']))
            $this->User = unserialize($_SESSION['oidc_user']);
    }
    
    /**
     * Save user session to PHP session
     */
    protected function saveSession()
    {
        if ($this->User !== null)
            $_SESSION['oidc_user'] = serialize($this->User);
    }
    
    /**
     * Generate a random string for security purposes
     * 
     * @param int $length Length of the random string
     * @return string Random string
     */
    protected function generateRandomString($length)
    {
        $bytes = random_bytes($length);
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
    
    /**
     * Generate a PKCE code challenge from code verifier
     * 
     * @param string $code_verifier PKCE code verifier
     * @return string PKCE code challenge
     */
    protected function generateCodeChallenge($code_verifier)
    {
        $hash = hash('sha256', $code_verifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }
    
    /**
     * Make an HTTP request
     * 
     * @param string $url The URL to request
     * @param array $params Parameters to send
     * @param string $method HTTP method (GET or POST)
     * @return string|false Response body or false on failure
     */
    protected function makeRequest($url, $params = array(), $method = 'GET')
    {
        $ch = curl_init();
        
        if ($method === 'GET' && !empty($params)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded'
            ));
        }
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("OIDC request error: " . $error);
            return false;
        }
        
        return $response;
    }
}

/**
 * Create a PsfOIDC instance from an ini file
 * 
 * @param string $file Path to the ini file
 * @return PsfOIDC The configured OIDC instance
 * @throws Exception If the file cannot be read
 */
function psf_oidc_from_ini_file($file)
{
    $config = parse_ini_file($file);
    if ($config === false)
        throw new Exception("Unable to read: " . $file);
    
    if (!isset($config["client_id"]) || !isset($config["client_secret"]) || 
        !isset($config["redirect_uri"]) || !isset($config["provider"])) {
        throw new Exception("Missing required OIDC configuration parameters");
    }
    
    $oidc = new PsfOIDC(
        $config["client_id"],
        $config["client_secret"],
        $config["redirect_uri"],
        $config["provider"]
    );
    
    // Set optional parameters
    if (isset($config["scopes"]))
        $oidc->Scopes = $config["scopes"];
    
    if (isset($config["metadata_endpoint"]))
        $oidc->MetadataEndpoint = $config["metadata_endpoint"];
    
    // Set privilege role mappings if defined
    if (isset($config["privilege_roles"]) && is_array($config["privilege_roles"]))
        $oidc->setPrivilegeRoleMap($config["privilege_roles"]);
    
    return $oidc;
}
