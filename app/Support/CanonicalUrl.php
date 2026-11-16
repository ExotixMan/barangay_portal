<?php

namespace App\Support;

use Illuminate\Routing\UrlGenerator;

class CanonicalUrl
{
    public static function generator(): UrlGenerator
    {
        $url = clone app('url');
        $root = rtrim((string) config('app.url'), '/');
        $url->forceRootUrl($root);
        $url->forceScheme(parse_url($root, PHP_URL_SCHEME) ?: 'https');

        return $url;
    }
}
