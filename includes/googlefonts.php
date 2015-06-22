<?php

function googlefonts_init($page)
{
    $open_sans = 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,700';
    if (!in_array($open_sans, $page->ExternalCss))
        $page->ExternalCss[] = $open_sans;
}
