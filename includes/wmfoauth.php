<?php

require (dirname(__FILE__) . "/oauth.php");

class WmfOAuth extends OAuth
{
    public $OAuth_ApiUrl = 'https://mediawiki.org/w/api.php';
    public $OAuth_Pref = 'mw';
}
