<?php

define(OAUTH_OK, 0);
define(OAUTH_NOT_AUTH, 2);

class OAuth
{
    public $ConsumerKey = null;
    public $UserAgent = "psf";
    public $TokenSecret = null;
    public $URL = null;
    public $TokenKey = null;
    public $ConsumerSecret = null;

    public function __construct($url)
    {
        $this->URL = $url;
        session_start();
        if (isset( $_SESSION['tokenKey']))
        {
            $o->TokenKey = $_SESSION['tokenKey'];
            $o->TokenSecret = $_SESSION['tokenSecret'];
        }
        session_write_close();
    }

    private function SignRequest($method, $url, $params = array())
    {
       	$parts = parse_url( $url );

	// We need to normalize the endpoint URL
	$scheme = isset( $parts['scheme'] ) ? $parts['scheme'] : 'http';
	$host = isset( $parts['host'] ) ? $parts['host'] : '';
	$port = isset( $parts['port'] ) ? $parts['port'] : ( $scheme == 'https' ? '443' : '80' );
	$path = isset( $parts['path'] ) ? $parts['path'] : '';
	if (( $scheme == 'https' && $port != '443' ) ||
		( $scheme == 'http' && $port != '80' ))
        {
		// Only include the port if it's not the default
		$host = "$host:$port";
	}

	// Also the parameters
	$pairs = array();
	parse_str( isset( $parts['query'] ) ? $parts['query'] : '', $query );
	$query += $params;
	unset( $query['oauth_signature'] );
	if ( $query )
        {
	    $query = array_combine(
		// rawurlencode follows RFC 3986 since PHP 5.3
		array_map( 'rawurlencode', array_keys( $query ) ),
		array_map( 'rawurlencode', array_values( $query ) )
		);
		ksort( $query, SORT_STRING );
		foreach ( $query as $k => $v ) {
			$pairs[] = "$k=$v";
		}
	}

	$toSign = rawurlencode( strtoupper( $method ) ) . '&' .
	rawurlencode( "$scheme://$host$path" ) . '&' .
	rawurlencode( join( '&', $pairs ) );
	$key = rawurlencode( $this->ConsumerSecret ) . '&' . rawurlencode( $this->TokenSecret );
	return base64_encode( hash_hmac( 'sha1', $toSign, $key, true ) );
    }

    public function ProcessToken()
    {
	$url = $this->URL . '/token';
	$url .= strpos( $url, '?' ) ? '&' : '?';
	$url .= http_build_query( array(
		'format' => 'json',
		'oauth_verifier' => $_GET['oauth_verifier'],

		// OAuth information
		'oauth_consumer_key' => $this->ConsumerKey,
		'oauth_token' => $this->TokenKey,
		'oauth_version' => '1.0',
		'oauth_nonce' => md5( microtime() . mt_rand() ),
		'oauth_timestamp' => time(),

		// We're using secret key signatures here.
		'oauth_signature_method' => 'HMAC-SHA1',
	) );
	$signature = sign_request( 'GET', $url );
	$url .= "&oauth_signature=" . urlencode( $signature );
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	//curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_USERAGENT, $this->UserAgent );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	if ( !$data ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
		exit(0);
	}
	curl_close( $ch );
	$token = json_decode( $data );
	if ( is_object( $token ) && isset( $token->error ) ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		echo 'Error retrieving token: ' . htmlspecialchars( $token->error );
		exit(0);
	}
	if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret ) ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		echo 'Invalid response from token request';
		exit(0);
	}

	// Save the access token
	session_start();
	$_SESSION['tokenKey'] = $this->TokenKey = $token->key;
	$_SESSION['tokenSecret'] = $this->TokenSecret = $token->secret;
	session_write_close();
    }

    public function AuthorizationRedirect()
    {
        $this->TokenSecret = '';
	$url = $this->URL . '/initiate';
	$url .= strpos( $url, '?' ) ? '&' : '?';
	$url .= http_build_query( array(
		'format' => 'json',
		// OAuth information
		'oauth_callback' => 'oob', // Must be "oob" for MWOAuth
		'oauth_consumer_key' => $this->ConsumerKey,
		'oauth_version' => '1.0',
		'oauth_nonce' => md5( microtime() . mt_rand() ),
		'oauth_timestamp' => time(),
		// We're using secret key signatures here.
		'oauth_signature_method' => 'HMAC-SHA1',
	) );
	$signature = $this->SignRequest( 'GET', $url );
	$url .= "&oauth_signature=" . urlencode( $signature );
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
        SystemLog::Write($url);
	curl_setopt( $ch, CURLOPT_USERAGENT, $this->UserAgent );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	if ( !$data ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
		exit(0);
	}
	curl_close( $ch );
        echo $data;
	$token = json_decode( $data );
	if ( is_object( $token ) && isset( $token->error ) ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		echo 'Error retrieving token: ' . htmlspecialchars( $token->error );
		exit(0);
	}
	if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret ) ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		echo 'Invalid response from token request';
		exit(0);
	}

	// Now we have the request token, we need to save it for later.
	session_start();
	$_SESSION['tokenKey'] = $token->key;
	$_SESSION['tokenSecret'] = $token->secret;
	session_write_close();

	// Then we send the user off to authorize
	$url = $this->URL;
	$url .= strpos( $url, '?' ) ? '&' : '?';
	$url .= http_build_query( array(
		'oauth_token' => $token->key,
		'oauth_consumer_key' => $this->ConsumerKey,
	) );
	header( "Location: $url" );
	echo 'Please see <a href="' . htmlspecialchars( $url ) . '">' . htmlspecialchars( $url ) . '</a>';
        return true;
    }

    public function fetchAccessToken() {
	$url = $this->OAuth_URL . '/token';
	$url .= strpos( $url, '?' ) ? '&' : '?';
	$url .= http_build_query( array(
		'format' => 'json',
		'oauth_verifier' => $_GET['oauth_verifier'],

		// OAuth information
		'oauth_consumer_key' => $gConsumerKey,
		'oauth_token' => $gTokenKey,
		'oauth_version' => '1.0',
		'oauth_nonce' => md5( microtime() . mt_rand() ),
		'oauth_timestamp' => time(),

		// We're using secret key signatures here.
		'oauth_signature_method' => 'HMAC-SHA1',
	) );
	$signature = $this->SignRequest( 'GET', $url );
	$url .= "&oauth_signature=" . urlencode( $signature );
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	//curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_USERAGENT, $gUserAgent );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	if ( !$data )
        {
		header( "HTTP/1.1 500 Internal Server Error" );
		echo 'Curl error: ' . htmlspecialchars( curl_error( $ch ) );
	}
	curl_close( $ch );
	$token = json_decode( $data );
	if (is_object( $token ) && isset( $token->error ))
        {
		header( "HTTP/1.1 500 Internal Server Error" );
		throw new Exception('Error retrieving token: ' . htmlspecialchars( $token->error ));
	}
	if ( !is_object( $token ) || !isset( $token->key ) || !isset( $token->secret)) {
		header( "HTTP/1.1 500 Internal Server Error" );
		throw new Exception('Invalid response from token request');
	}

	// Save the access token
	session_start();
	$_SESSION['tokenKey'] = $gTokenKey = $token->key;
	$_SESSION['tokenSecret'] = $gTokenSecret = $token->secret;
	session_write_close();
    }

    public function Identify()
    {
        $url = $this->URL . '/identify';
	$headerArr = array(
		// OAuth information
		'oauth_consumer_key' => $this->ConsumerKey,
		'oauth_token' => $this->TokenKey,
		'oauth_version' => '1.0',
		'oauth_nonce' => md5( microtime() . mt_rand() ),
		'oauth_timestamp' => time(),

		// We're using secret key signatures here.
		'oauth_signature_method' => 'HMAC-SHA1',
	);
	$signature = $this->SignRequest( 'GET', $url, $headerArr );
	$headerArr['oauth_signature'] = $signature;

	$header = array();
	foreach ( $headerArr as $k => $v )
        {
		$header[] = rawurlencode( $k ) . '="' . rawurlencode( $v ) . '"';
	}
	$header = 'Authorization: OAuth ' . join( ', ', $header );

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $header ) );
	curl_setopt( $ch, CURLOPT_USERAGENT, $gUserAgent );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$data = curl_exec( $ch );
	if ( !$data ) {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		throw new Exception('Curl error: ' . htmlspecialchars( curl_error( $ch )));
	}
	$err = json_decode( $data );
	if ( is_object( $err ) && isset( $err->error ) && $err->error === 'mwoauthdatastore-access-token-not-found' ) {
		// We're not authorized!
		return OAUTH_NOT_AUTH;
	}

	// There are three fields in the response
	$fields = explode( '.', $data );
	if ( count( $fields ) !== 3 )
        {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		throw new Exception('Invalid identify response: ' . htmlspecialchars( $data ));
	}

	// Validate the header. MWOAuth always returns alg "HS256".
	$header = base64_decode( strtr( $fields[0], '-_', '+/' ), true );
	if ( $header !== false )
        {
            $header = json_decode( $header );
	}
	if ( !is_object( $header ) || $header->typ !== 'JWT' || $header->alg !== 'HS256' )
        {
            header( "HTTP/1.1 $errorCode Internal Server Error" );
            throw new Exception('Invalid header in identify response: ' . htmlspecialchars( $data ));
	}

	// Verify the signature
	$sig = base64_decode( strtr( $fields[2], '-_', '+/' ), true );
	$check = hash_hmac( 'sha256', $fields[0] . '.' . $fields[1], $gConsumerSecret, true );
	if ( $sig !== $check )
        {
            header( "HTTP/1.1 $errorCode Internal Server Error" );
            throw new Exception('JWT signature validation failed: ' . htmlspecialchars( $data ) . var_dump( base64_encode($sig), base64_encode($check) ));
	}

	// Decode the payload
	$payload = base64_decode( strtr( $fields[1], '-_', '+/' ), true );
	if ( $payload !== false )
        {
		$payload = json_decode( $payload );
	}
	if ( !is_object( $payload ) )
        {
		header( "HTTP/1.1 $errorCode Internal Server Error" );
		throw new Exception('Invalid payload in identify response: ' . htmlspecialchars( $data ));
	}
	SystemLog::Write( 'JWT payload: <pre>' . htmlspecialchars( var_export( $payload, 1 ) ) . '</pre>');
        return OAUTH_OK;
    }
}

function OAuthFromIniFile($file)
{
    $r = parse_ini_file($file);
    if ($r === false)
        throw new Exception("Unable to read: " . $file);

    $o = new OAuth($r["url"]); 
    $o->ConsumerSecret = $r["consumerSecret"];
    $o->ConsumerKey = $r["consumerKey"];
    return $o;
}
